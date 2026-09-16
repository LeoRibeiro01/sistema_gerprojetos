<?php

namespace App\Providers;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\User;
use App\Models\Sprint;
use App\Models\Epic;
use App\Models\UserStory;
use App\Models\Bug;
use App\Models\Timesheet;
use App\Policies\AgilePolicy;
use App\Policies\TimesheetPolicy;
use App\Policies\ProjetoPolicy;
use App\Policies\TarefaPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Projeto::class => ProjetoPolicy::class,
        Tarefa::class => TarefaPolicy::class,
        User::class => UserPolicy::class,
        Sprint::class => AgilePolicy::class,
        Epic::class => AgilePolicy::class,
        UserStory::class => AgilePolicy::class,
        Bug::class => AgilePolicy::class,
        Timesheet::class => TimesheetPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
