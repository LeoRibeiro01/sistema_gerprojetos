<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\EpicController;
use App\Http\Controllers\UserStoryController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\DailyCheckinController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardChartController;
use App\Http\Controllers\DashboardChartReportController;
use App\Models\DailyCheckin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    return view('home');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/kanban', [TarefaController::class, 'kanban'])->name('kanban.index');
    Route::post('/daily-checkins', [DailyCheckinController::class, 'store'])->name('daily-checkins.store');
    Route::post('/dashboard/charts', [DashboardChartController::class, 'store'])->name('dashboard.charts.store');
    Route::delete('/dashboard/charts/{chart}', [DashboardChartController::class, 'destroy'])->name('dashboard.charts.destroy');
    Route::get('/dashboard/charts/{chart}/pdf', [DashboardChartReportController::class, 'show'])->name('dashboard.charts.pdf');
    Route::get('/portal-cliente', [ClientPortalController::class, 'index'])->name('client-portal.index');
    Route::post('/portal-cliente/projetos/{projeto}/aprovacao', [ClientPortalController::class, 'approve'])->name('client-portal.approvals.store');
    Route::resource('sprints', SprintController::class);
    Route::resource('epics', EpicController::class);
    Route::resource('user-stories', UserStoryController::class);
    Route::resource('bugs', BugController::class);
    Route::resource('projeto', ProjetoController::class);
    Route::resource('tarefas', TarefaController::class);
    Route::patch('/tarefas/{tarefa}/status', [TarefaController::class, 'updateStatus'])->name('tarefas.status');
    Route::post('/tarefas/{tarefa}/timer/start', [TarefaController::class, 'startTimer'])->name('tarefas.timer.start');
    Route::post('/tarefas/{tarefa}/timer/stop', [TarefaController::class, 'stopTimer'])->name('tarefas.timer.stop');
    Route::post('/tarefas/{tarefa}/comments', [TarefaController::class, 'storeComment'])->name('tarefas.comments.store');
    Route::delete('/tarefas/{tarefa}/comments/{comment}', [TarefaController::class, 'destroyComment'])->name('tarefas.comments.destroy');
    Route::post('/tarefas/{tarefa}/dependencies', [TarefaController::class, 'addDependency'])->name('tarefas.dependencies.store');
    Route::delete('/tarefas/{tarefa}/dependencies/{dependency}', [TarefaController::class, 'removeDependency'])->name('tarefas.dependencies.destroy');

    Route::patch('/tarefas/{id}/concluir', [TarefaController::class, 'concluir'])->name('tarefas.concluir');
    Route::patch('/projeto/{id}/concluir', [ProjetoController::class, 'concluir'])->name('projeto.concluir');

    Route::get('projetos/report', [ProjetoController::class, 'report'])->name('projetos.report');
    Route::get('projeto/report/{id}', [ProjetoController::class, 'singleReport'])->name('projeto.singleReport');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{id}', [ReportController::class, 'show'])->name('reports.show');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::get('register', function () {
    return view('auth.register');
})->name('register');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
