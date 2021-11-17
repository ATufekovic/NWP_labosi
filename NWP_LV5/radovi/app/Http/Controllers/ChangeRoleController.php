<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Models\User;

class ChangeRoleController extends Controller
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
        if(Auth::user()->role_id != 1){ 
            //ako nije admin odbij ga
            return redirect(App::currentLocale() . "/home");
        }
        return view('changeRole');
    }

    public function changeRole(Request $request)
    {
        if(Auth::user()->role_id != 1){ 
            //ako nije admin odbij ga
            return redirect(App::currentLocale() . "/home");
        }

        $input = $request->all();
        $validated = $request->validate([
            "role" => "in:admin,professor,student",
            "userId" => "integer"
        ]);

        $roles = [
            "admin" => 1,
            "professor" => 2,
            "student" => 3
        ];

        $user = User::find($input["userId"]);
        if($user){
            //našlo je korisnika, nastavi sa prepravom
            $user->role_id = $roles[$input["role"]];
            $user->save();

            return redirect(App::currentLocale() . "/changeRole");
        } else {
            //nije našlo korisnika, vrati natrag na home
            return redirect(App::currentLocale() . "/home");
        }
    }
}
