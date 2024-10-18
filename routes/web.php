<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Aqui você pode registrar as rotas web para sua aplicação. Essas rotas
| são carregadas pelo RouteServiceProvider e todas serão atribuídas ao grupo
| de middleware "web".
|
*/

// Página principal
Route::get('/', function () {
    return view('home');
})->name('home');

// Dashboard (após login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rotas para projetos
Route::resource('projeto', ProjetoController::class);

// Rotas para tarefas
Route::resource('tarefas', TarefaController::class);

// Rotas para usuários
Route::resource('users', UserController::class);

// Login e Cadastro com Breeze
Route::get('login', function () {
    return view('auth.login'); // Tela de login do Breeze
})->name('login');

Route::get('register', function () {
    return view('auth.register'); // Tela de cadastro do Breeze
})->name('register');

// Rotas de autenticação geradas pelo Breeze
require __DIR__.'/auth.php';

// Rotas do ProfileController
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
