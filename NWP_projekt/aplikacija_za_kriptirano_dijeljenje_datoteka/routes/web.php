<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;


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
require __DIR__.'/auth.php';

Route::group([
    "prefix" => "{locale}",
    "where" => ["locale" => "[a-z]{2}"],
    "middleware" => "SetLocale"
],  function(){
        Route::get("/", function(){
            return view("welcome");
        });

        Route::get('/dashboard',[\App\Http\Controllers\FilesController::class, "dashboard"])->name('dashboard');
        Route::get("/files", [\App\Http\Controllers\FilesController::class, "content"])->name("files");

        Route::post("/newFile", [\App\Http\Controllers\FilesController::class, "newFile"]);
        
        Route::get("downloadFile/{id}", [\App\Http\Controllers\FilesController::class, "downloadFile"]);
        Route::get("viewFile/{id}", [\App\Http\Controllers\FilesController::class, "viewFile"]);

        Route::get("/viewUserFiles", [\App\Http\Controllers\FilesController::class, "viewUserFilesGetRequest"]);
        Route::get("/viewUserFiles/{username}", [\App\Http\Controllers\FilesController::class, "viewUserFiles"]);
        
        Route::delete('deleteFile', [\App\Http\Controllers\FilesController::class, "deleteFile"]);
        Route::put('changeVisibility', [\App\Http\Controllers\FilesController::class, "changeVisibility"]);
});

Route::get('/', function () {
    return redirect(App::currentLocale() . "/");
});
Route::get('/logout', function () {
    return redirect(App::currentLocale() . "/");
});



