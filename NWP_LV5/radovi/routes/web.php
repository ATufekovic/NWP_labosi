<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




//Auth::routes();

Route::group([
    "prefix" => "{locale}",
    "where" => ["locale" => "[a-z]{2}"],
    "middleware" => "SetLocale"
], function(){
    Route::get('/', function () {
        return view('welcome');
    });
    Auth::routes();
    
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get("/newWork",[\App\Http\Controllers\NewWorkController::class, 'content']);
    Route::get("/changeRole", [\App\Http\Controllers\ChangeRoleController::class, 'content']);
    Route::get('/chooseTask/{order}', [\App\Http\Controllers\ChooseTaskController::class, 'content']);
    Route::get("/chooseStudent/{taskId}", [\App\Http\Controllers\ChooseStudentController::class, 'content']);
});

Route::get('/', function () {
    return redirect(App::currentLocale() . "/");
});
Route::get('/home', function () {
    return redirect(App::currentLocale() . "/home");
});
Route::get('/logout', function () {
    return redirect(App::currentLocale() . "/");
});

Route::post("createNewWork", [\App\Http\Controllers\NewWorkController::class, 'createNewWork']);

Route::post("changeRoleOfUser", [\App\Http\Controllers\ChangeRoleController::class, 'changeRole']);

Route::post("chooseTargetedTask", [\App\Http\Controllers\ChooseTaskController::class, 'chooseTargetedTask']);

Route::post("chooseNewStudent", [\App\Http\Controllers\ChooseStudentController::class, 'chooseNewStudent']);