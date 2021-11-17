<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\User;

class ViewProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function content($id)
    {
        return view('viewProject', ["project" => $this->getProject($id), "members" => $this->getMembers($id)]);
    }

    public function getProject($id){
        $project = Project::where("id", $id)->get();
        return ["project" => $project[0]];
    }

    public function getMembers($id){
        $project = Project::find($id);
        $fetchedUsers = $project->users()->get()->toArray();
        $user_leader = $project->leader()->get()->toArray();
        //dd(["users" => [$user_leader, $fetchedUsers]]);
        return ["users" => [$user_leader, $fetchedUsers]];
    }
}
