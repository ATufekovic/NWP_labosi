<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class NewWorkController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function content()
    {
        if(Auth::user()->role_id == 3){ 
            //ako je student odbij mu kreiranje rada
            redirect(App::currentLocale() . "/home");
        }
        return view('newWork');
    }

    public function createNewWork(Request $request){
        $input = $request->all();
        $validated = $request->validate([
            "newWorkName" => "string|required",
            "newWorkNameEnglish" => "string|required",
            "newWorkDescription" => "string",
            "newWorkStudyType" => "required|in:professionalStudy,undergraduate,graduate"
        ]);
        //dd($input);
        $studyType = "enum('stručni', 'preddiplomski', 'diplomski')";
        if($input["newWorkStudyType"] == "professionalStudy"){
            $studyType = "stručni";
        } else if($input["newWorkStudyType"] == "undergraduate"){
            $studyType = "preddiplomski";
        } else if($input["newWorkStudyType"] == "graduate"){
            $studyType = "diplomski";
        }

        $work = new Task();
        $work->naziv_rada = $input["newWorkName"];
        $work->naziv_rada_engleski = $input["newWorkNameEnglish"];
        $work->zadatak_rada = $input["newWorkDescription"];
        $work->tip_studija = $studyType;
        $work->creator_id = Auth::user()->id;

        $work->save();

        return redirect(App::currentLocale() . "/home");
    }
}
