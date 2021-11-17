<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ChooseTaskController extends Controller
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

    public function content($locale, $order)
    {
        return view('chooseTask', [
            "currentlyChosenTask" => $this->getCurrentTask($order)
        ]);
    }

    private function getCurrentTask($order){
        foreach (Auth::user()->appliedTo()->get() as $item) {
            if($item->pivot->order == $order){
                //našao je rad, vrati ga natrag
                return $item;
            }
        }
        return null;
    }

    public function chooseTargetedTask(Request $request){
        $input = $request->all();
        $validated = $request->validate([
            "taskId" => "integer|numeric|required",
            "order" => "in:1,2,3,4,5|required"
        ]);

        //prvo provjeri dali je ovaj task već među odabrani taskovima
        $taskAlreadyChosen = false;
        $task = Task::find($input["taskId"]);
        $isInTasks = Auth::user()->appliedTo()->find($input["taskId"]);
        if($isInTasks){
            //vrati null ako nema taskova ovog id-a, pa ni ne odradi ostatak ovih if-ova
            $taskAlreadyChosen = true;
        }

        if($taskAlreadyChosen){
            //sad treba pregledati radove korisnika, naći rad sa istim id-em te promijeniti mu order
            //prije toga moramo provjeriti dali se nalazi neki rad u trenutnom orderu
            $taskAlreadyInThisOrder = null;
            $checkIfTaskIsAlreadyInThisOrder = false;
            foreach (Auth::user()->appliedTo()->get() as $item) {
                if($item->pivot->order == $input["order"]){
                    $taskAlreadyInThisOrder = $item;
                    $checkIfTaskIsAlreadyInThisOrder = true;
                }
            }
            
            //ako su oba taska isti u istoj poziciji, ne radi nista
            if($checkIfTaskIsAlreadyInThisOrder){
                if($task->id == $taskAlreadyInThisOrder->id){
                    return redirect(App::currentLocale() . "/home");
                }
            }

            //ako ima task odspoji ga
            if($checkIfTaskIsAlreadyInThisOrder){
                $taskAlreadyInThisOrder->applicants()->detach(Auth::user());
            }

            //nakon što je rad odspojen, možemo promijeniti order već nađenog task-a
            foreach (Auth::user()->appliedTo()->get() as $item) {
                if($item->id == $task->id){
                    $item->pivot->order = $input["order"];
                    $item->pivot->save();
                }
            }
        } else {
            //ako nije među odabranim, provjeri dali ima task u trenutnom orderu
            $taskAlreadyInThisOrder = null;
            $checkIfTaskIsAlreadyInThisOrder = false;
            foreach (Auth::user()->appliedTo()->get() as $item) {
                if($item->pivot->order == $input["order"]){
                    $taskAlreadyInThisOrder = $item;
                    $checkIfTaskIsAlreadyInThisOrder = true;
                }
            }
            //ako ima task odspoji ga
            if($checkIfTaskIsAlreadyInThisOrder){
                $taskAlreadyInThisOrder->applicants()->detach(Auth::user());
            }
            //te spoji onda novi task
            $task->applicants()->attach(Auth::user(), ["order"=>$input["order"]]);
        }
                

        //dd($input);
        return redirect(App::currentLocale() . "/home");
    }
}
