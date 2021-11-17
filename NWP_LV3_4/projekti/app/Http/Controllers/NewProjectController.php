<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

class NewProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function content()
    {
        return view('newProject');
    }

    public function rules(){
        return [
            "projectName" => "required|string|max:255",
            "projectDesc" => "required|string",
            "projectStartTime" => "required|date"
        ];
    }

    public function createNewProject(Request $request){
        $input = $request->all();
        $validated = $request->validate($this->rules());
        $user_id = Auth::user()->id;

        $project = new Project;
        $project->leader_user_id = $user_id;
        $project->naziv_projekta = $input["projectName"];
        $project->opis_projekta = $input["projectDesc"];
        $project->datum_pocetka = $input["projectStartTime"];
        $project->save();

        return redirect("myProjects");
    }
}
