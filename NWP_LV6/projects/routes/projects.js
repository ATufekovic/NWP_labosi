let Project = require("../lib/project");
let User = require("../lib/user");

exports.list = (req, res, next) => {
    if (req.session.uid) {
        let project = new Project();
        project.getAllKeysByCreatorId(req.session.uid, function (err, values) {
            if (err) return next(err);

            if (values === null) {//ako nema radova od korisnika vrati prazno
                res.render("index", { title: "Naslovna stranica" });
                return;
            }

            let promises = [];
            //za svaki kljuc dohvati njegov projekt i ideve clanova
            for (const project_id of Object.values(values)) {
                promises.push(new Promise(function (resolve, reject) {
                    let new_project = new Project;
                    new_project.getById(project_id, function (err, val) {
                        if (err) {
                            reject(err);
                        } else {
                            val.getMembers(function (err, members) {
                                if (err) {
                                    reject(err);
                                } else {
                                    val.members = members;
                                    resolve(val);
                                }
                            });
                        }
                    });
                }));
            }

            Promise.all(promises)
                .then(function (data) {
                    let member_promises = [];
                    for (const project of data) {
                        project.users = {};
                        //ako postoje clanovi nastavi
                        if (project.members) {
                            for (const member_id of Object.keys(project.members)) {
                                member_promises.push(new Promise(function (resolve, reject) {
                                    let new_user = new User;
                                    new_user.getUserFromId(member_id, function (err, user) {
                                        if (err) {
                                            reject(err);
                                        } else {
                                            project.users[user.id] = user.name;
                                            resolve(user.id);
                                        }
                                    });
                                }));
                            }
                        }
                    }
                    Promise.all(member_promises)
                        .then(function (resolves) {
                            console.log(data);
                            res.render("projects", { title: "Projekti", projects: data });
                        }).catch(function (error) {
                            return next(error[0]);
                        });
                }).catch(function (error) {
                    return next(error[0]);
                });
        });
    } else {
        res.render("index", { title: "Naslovna stranica" });
    }
}
exports.index = (req, res, next) => {
    res.render("index", { title: "Naslovna stranica" });
}

exports.form = function (req, res) {
    if (req.session.uid) {
        res.render('post', { title: 'Novi projekt' });
    } else {
        res.render("index", { title: "Naslovna stranica" });
    }
};

exports.submit = function (req, res, next) {
    if (req.session.uid) {
        var data = req.body;

        var project = new Project({
            name: data.name,
            desc: data.desc,
            cost: data.cost,
            tasks: data.tasks,
            date_start: data.date_start,
            date_finish: data.date_finish,
            creator_id: req.session.uid
        });

        project.save(req.session.uid, function (err) {
            if (err) return next(err);
            res.redirect("/");
        });
    } else {
        res.redirect("/");
    }

};

exports.delete = function (req, res, next) {
    if (req.session.uid) {
        //req.body je u obliku {delete:"id"} zbog buttona
        var id = req.body.delete;
        //ako projekt ima zapravo id nastavi
        if (id) {
            let project = new Project();
            project.getById(id, function (err, projectToDelete) {
                if (err) return next(err);
                //ako je ulogirani korisnik vlasnik projekta, nastavi brisanje
                if (req.session.uid == projectToDelete.creator_id) {
                    projectToDelete.delete(function (err) {
                        if (err) return next(err);
                        res.redirect("/");
                    });
                }
            });
        }
    } else {
        res.redirect("/");
    }
};

exports.addMember = function (req, res, next) {
    if (req.session.uid) {
        let project_id = req.body.project_id;
        let member_name = req.body.username;

        var user = new User();
        user.getIdFromName(member_name, function (err, member_id) {
            if (err) return next(err);

            let temp = new Project();
            temp.getById(project_id, function (err, project) {
                if (err) return next(err);

                project.addMember(member_id, function (err, message) {
                    if (err) return next(err);
                    console.log(message);
                    return res.redirect("/projects");
                })
            });
        });
    }
};