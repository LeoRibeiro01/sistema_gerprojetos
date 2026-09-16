<?php

namespace App\Policies;

use App\Models\Projeto;
use App\Models\User;

class ProjetoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isInternalTeam();
    }

    public function view(User $user, Projeto $projeto): bool
    {
        return $user->isInternalTeam();
    }

    public function create(User $user): bool
    {
        return $user->isInternalTeam();
    }

    public function update(User $user, Projeto $projeto): bool
    {
        return $user->isInternalTeam();
    }

    public function delete(User $user, Projeto $projeto): bool
    {
        return $user->isAdmin() || $user->hasRole(\App\Enums\UserRole::Pm);
    }
}
