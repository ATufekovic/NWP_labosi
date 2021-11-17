<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;

class MyProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function content()
    {
        return view('myProjects', $this->getProjects());
    }

    public function getProjects(){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $projectsLeader = $user->projects_leader()->get();
        $projectsMember = $user->projects()->get();
        //dd([$projectsLeader, $projectsMember]);

        return ["projects" => $projectsLeader, "memberOf" => $projectsMember];
    }
}
