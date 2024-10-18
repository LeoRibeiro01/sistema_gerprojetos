<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
|
| Aqui você pode registrar as rotas web para sua aplicação. Essas rotas
| são carregadas pelo RouteServiceProvider e todas serão atribuídas ao grupo
| de middleware "web".
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


// Routes for projects
Route::resource('projeto', ProjetoController::class);

// Routes for tasks
Route::resource('tarefas', TarefaController::class);

// Routes for users
Route::resource('users', UserController::class);

// Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Auth routes
require __DIR__.'/auth.php';
