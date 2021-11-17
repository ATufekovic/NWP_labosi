<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;

class EditProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Returns view of target id content
     */
    public function content($id)
    {
        return view('editProject', [
            "project" => $this->getProject($id),
            "members" => $this->getMembers($id),
            "isLeader" => $this->isLeader($id),
            "notMembers" => $this->getUsersThatAreNotMembers($id),
            "leader" => $this->getLeader($id)
        ]);
    }

    public function getProject($id){
        $project = Project::where("id", $id)->get()[0];
        return $project;
    }

    public function getUsersThatAreNotMembers($id){
        $project = Project::find($id);
        $leader = $project->leader()->get()[0];
        $allUsers = User::all();
        $members = $project->users()->get();

        //ako nema registriranih korisnika ne radi nista, vrati prazan array
        if($allUsers === null){
            return [];
        }

        //ako nema clanova u projektu vrati sve korisnike osim vođe
        if($members === null){
            dd($allUsers->diff($leader));
            return $allUsers->diff([$leader]);
        }

        //na kraju vrati razliku svih korisnika i trenutnih clanova te vođe
        return $allUsers->diff($members)->diff([$leader]);
    }
    /**
     * Check if the authenticated user is the leader of the target id project
     */
    public function isLeader($projectId){
        $user = Auth::user();
        $project = Project::find($projectId);
        $leader = $project->leader()->get()[0];
        if($user->id == $leader->id){
            return true;
        }
        return false;
    }

    public function isMember($projectId){
        $user = Auth::user();
        $project = Project::find($projectId);
        $members = $project->users()->get();

        return ($members->contains($user));
    }

    public function getLeader($id){
        $project = Project::find($id);
        $leader = $project->leader()->get()[0];
        return $leader;
    }

    public function getMembers($id){
        $project = Project::find($id);
        $fetchedUsers = $project->users()->get();
        return $fetchedUsers;
    }

    public function rules(){
        return [
            "newProjectName" => "required|string|max:255",
            "newProjectDesc" => "required|string",
            "newProjectPrice" => "required|numeric|regex:/^[0-9]+(\.[0-9]{1,2})?$/",
            "newProjectStartTime" => "required|date",
            "newProjectEndTime" => "required|date",
            "newProjectJobDetails" => "required|string"
        ];
    }

    public function saveChanges(Request $request){
        $input = $request->all();
        $validated = $request->validate($this->rules());
        if(!($this->isLeader($input["id"]))){
            return redirect("home");
        }
        $project = Project::find($input["id"]);

        $project->naziv_projekta = $input["newProjectName"];
        $project->opis_projekta = $input["newProjectDesc"];
        $project->cijena_projekta = $input["newProjectPrice"];
        $project->obavljeni_poslovi = $input["newProjectJobDetails"];
        $project->datum_pocetka = $input["newProjectStartTime"];
        $project->datum_zavrsetka = $input["newProjectEndTime"];

        $project->save();

        return redirect("viewProject/" . $input["id"]);
    }

    public function saveDetails(Request $request){
        $input = $request->all();
        $validated = $request->validate(["newProjectJobDetails" => "required|string"]);
        if(!($this->isMember($input["id"]))){
            return redirect("home");
        }
        $project = Project::find($input["id"]);

        $project->obavljeni_poslovi = $input["newProjectJobDetails"];

        $project->save();

        return redirect("viewProject/" . $input["id"]);
    }

    public  function addMember(Request $request, $id){
        $input = $request->all();
        $validated = $request->validate(["selectNotMemberId"=>"required|numeric"]);
        if(!($this->isLeader($id))){
            return redirect("home");
        }

        if($input["selectNotMemberId"] == "default"){
            //nothing was chosen, do nothing
            return redirect("viewProject/" . $id);
        }

        $user_id = $input["selectNotMemberId"];
        $user = User::find($user_id);
        if($user === null){//no such user exists
            return redirect("viewProject/" . $id);
        }

        $project = Project::find($id);
        $users = $project->users()->get();
        $leader = $project->leader()->get()[0];

        //see if the user is the leader of the project
        //in both cases dont save the relation, it's invalid/redundant
        if($user->id == $leader->id || $users->contains($user)){
            return redirect("viewProject/" . $id);
        }

        $project->users()->attach($user);

        return redirect("editProject/" . $id);
    }

    public function removeMember(Request $request, $id){
        $input = $request->all();
        $validated = $request->validate(["selectMemberId"=>"required|numeric"]);
        if(!($this->isLeader($id))){
            return redirect("home");
        }

        $user = User::find($input["selectMemberId"]);
        if($user === null){
            return redirect("viewProject/" . $id);
        }
        $project = Project::find($id);
        $users = $project->users()->get();
        $leader = $project->leader()->get()[0];

        //ako se pokušava maknuti vlasnik ili korisnik koji nije u projektu, zanemari i vrati ga natrag
        if($leader->id == $user->id || !$users->contains($user)){
            return redirect("viewProject/" . $id);
        }
        //inace obrisi relaciju
        $project->users()->detach($user);
        
        return redirect("editProject/" . $id);
    }
}
