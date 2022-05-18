<?php

//revisar
use Illuminate\Support\Facades\Auth;
//Add los controladores
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\TechnologyController;

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
    return view('auth.login');
});

Auth::routes();
  
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  
Route::group(['middleware' => ['auth']], function(){
    Route::resource('roles', RoleController::class);
    Route::resource('usuarios', UserController::class);
    Route::resource('candidatos', CandidateController::class);
    Route::resource('tecnologias', TechnologyController::class);
    //Route::get('tecnologias/experience', [App\Http\Controllers\TechnologyController::class, 'experience'])->name('tecnologias.experience');
    //ponerle prefijo admin luego
});






