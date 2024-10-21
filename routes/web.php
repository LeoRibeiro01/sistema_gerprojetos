<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aqui você pode registrar as rotas web para sua aplicação.
| Todas as rotas atribuídas ao grupo "web" serão carregadas pelo RouteServiceProvider.
*/

// Página principal
Route::get('/', function () {
    return view('home');
})->name('home');

// Dashboard (após login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rotas para projetos e tarefas, disponíveis para todos os usuários autenticados
Route::middleware('auth')->group(function () {
    Route::resource('projeto', ProjetoController::class); // Alterado para 'projetos' (plural)
    Route::resource('tarefas', TarefaController::class); // Mantido como 'tarefas'
    
    // Rota para atualizar o status de uma tarefa
    // Rota para concluir uma tarefa
    Route::patch('/tarefas/{id}/concluir', [TarefaController::class, 'concluir'])->name('tarefas.concluir');

});

// Rotas para gerenciamento de usuários, restritas ao admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Login e Cadastro com Breeze
Route::get('login', function () {
    return view('auth.login'); // Tela de login do Breeze
})->name('login');

Route::get('register', function () {
    return view('auth.register'); // Tela de cadastro do Breeze
})->name('register');

// Rotas de autenticação geradas pelo Breeze
require __DIR__.'/auth.php';

// Rotas do ProfileController, acessíveis a todos os usuários autenticados
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
