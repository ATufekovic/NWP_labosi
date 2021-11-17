<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get("/myProjects", [\App\Http\Controllers\MyProjectsController::class, 'content'])->name("myProjects");

Route::get("/newProject", [App\Http\Controllers\NewProjectController::class, 'content'])->name("newProject");

Route::get("/viewProject/{id}", [\App\Http\Controllers\ViewProjectController::class, 'content']);

Route::post("/addMember/{id}", [\App\Http\Controllers\EditProjectController::class, 'addMember']);

Route::post("/removeMember/{id}", [\App\Http\Controllers\EditProjectController::class, 'removeMember']);

Route::post('/createNewProject', [App\Http\Controllers\NewProjectController::class, 'createNewProject']);

Route::get("/editProject/{id}", [App\Http\Controllers\EditProjectController::class, 'content']);

Route::post('/saveChanges', [\App\Http\Controllers\EditProjectController::class, "saveChanges"]);

Route::post('/saveDetails', [\App\Http\Controllers\EditProjectController::class, "saveDetails"]);