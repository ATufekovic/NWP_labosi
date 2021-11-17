var redis = require('redis');
var db = redis.createClient();

module.exports = Project;

function Project(obj) {
    for (var key in obj) {
        this[key] = obj[key];
    }
}
//skoro sve CRUD operacije ce se bazirati na funkcijama sa predavanja jer su komotne

//CRUD za create preko trenutnog objekta jer zahtjeva znanje o uid-u
//neznam dali  bi trebalo dopustiti kreiranje bez creator_id
Project.prototype.save = function (creator_id, fn) {
    if (!this.creator_id) this.creator_id = creator_id;
    if (this.id) {
        this.update(creator_id, fn);
    } else {
        let project = this;
        db.incr("project:ids", function (err, id) {
            if (err) return fn(err);
            project.id = id;
            project.update(creator_id, fn);
        });
    }
};

//CRUD za update
Project.prototype.update = function (creator_id, fn) {
    let project = this;
    let id = project.id;
    db.hmset("project:" + id, project.toJSON(), function (err) {
        if (err) return fn(err);
        db.hset("project:uid:" + creator_id, id, id, function (err) {
            fn(err);
        });
    });
};

//CRUD za dohvacanje po id-u
//nebih rekao da je moguce dohvacanje po imenu jer projekti se mogu isto nazivati
Project.prototype.getById = function (id, fn) {
    db.hgetall("project:" + id, function (err, project) {
        if (err) return fn(err);
        fn(null, new Project(project));
    });
};

//može se koristiti zajedno sa getById da se dohvate svi od jednog korisnika
//ali asinkrono je sve pa mora se sa Promise rijesiti skupljanje
Project.prototype.getAllKeysByCreatorId = function (creator_id, fn) {
    db.hgetall("project:uid:" + creator_id, function (err, values) {
        if (err) return fn(err);
        fn(null, values);
    });
};

//dohvati sve project id ovisno dali je korisnik clan tih projekata
Project.prototype.getAllKeysByMembership = function(uid, fn){
    db.hgetall("user:members:" + uid, function(err, values){
        if(err) return fn(err);
        fn(null, values);
    });
};

Project.prototype.toJSON = function () {
    return {
        id: this.id,
        name: this.name,
        desc: this.desc,
        cost: this.cost,
        tasks: this.tasks,
        date_start: this.date_start,
        date_finish: this.date_finish,
        creator_id: this.creator_id,
        archived: this.archived
    }
};

//CRUD za brisanje
Project.prototype.delete = function (fn) {
    let project = this;
    console.log(project);
    if (project.id) {
        db.del("project:" + project.id, function (err) {
            if (err) return fn(err);
            db.hdel("project:uid:" + project.creator_id, project.id, function (err) {
                if (err) return fn(err);
                //brisanje relacija
                project.getMembers(function (err, members) {//ie. { '13': '13' }
                    if (err) return fn(err);

                    let promises = [];
                    for (const member_id of Object.values(members)) {
                        promises.push(new Promise(function (resolve, reject) {
                            db.hdel("user:members:" + member_id, project.id, function (err) {
                                if (err) {
                                    reject(err);
                                } else {
                                    resolve("OK");
                                }
                            });
                        }));
                    }

                    Promise.all(promises)
                        .then(function (data) {
                            db.del("project:members:" + project.id, function (err) {
                                if (err) return fn(err);
                                return fn(null, "OK");
                            })
                        }).catch(function (error) {
                            return fn(error[0]);
                        })

                });
            });
        });
    } else {
        return fn("No such project id");
    }
}

//dodavanje se odvija na strani projekta, nisam bio originalno siguran
//kako ce izgledati relacija pa stavljam vecinu na projekt
Project.prototype.addMember = function (member_id, fn) {
    let project = this;
    if (project.id) {
        db.hset("project:members:" + project.id, member_id, member_id, function (err) {
            if (err) return fn(err);
            db.hset("user:members:" + member_id, project.id, project.id, function (err) {
                if (err) return fn(err);
                fn(null, "OK");
            })
        })
    } else {
        return fn("No such project id");
    }
}

Project.prototype.getMembers = function (fn) {
    let project = this;
    if (project.id) {
        db.hgetall("project:members:" + project.id, function (err, members) {
            if (err) return fn(err);
            fn(null, members);
        })
    } else {
        return fn("No such project id");
    }
};

Project.prototype.archive = function(fn){
    let project = this;
    if(project.id){
        project.archived = "yes";
        db.hset("project:" + project.id, "archived", project.archived, function(err){
            if(err) return fn(err);
            fn(null);
        });
    }
};
