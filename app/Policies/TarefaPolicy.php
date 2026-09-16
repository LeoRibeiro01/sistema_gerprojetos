<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Tarefa;
use App\Models\User;

class TarefaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isInternalTeam();
    }

    public function view(User $user, Tarefa $tarefa): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->isInternalTeam();
    }

    public function update(User $user, Tarefa $tarefa): bool
    {
        return $user->isInternalTeam();
    }

    public function delete(User $user, Tarefa $tarefa): bool
    {
        return $user->hasRole(UserRole::Admin, UserRole::Pm);
    }
}
