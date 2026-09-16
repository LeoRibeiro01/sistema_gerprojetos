<?php

namespace App\Policies;

use App\Models\User;

class AgilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isInternalTeam();
    }

    public function view(User $user, object $model): bool
    {
        return $user->isInternalTeam();
    }

    public function create(User $user): bool
    {
        return $user->isInternalTeam();
    }

    public function update(User $user, object $model): bool
    {
        return $user->isInternalTeam();
    }

    public function delete(User $user, object $model): bool
    {
        return $user->isAdmin() || $user->hasRole(\App\Enums\UserRole::Pm);
    }
}
