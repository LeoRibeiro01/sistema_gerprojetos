<?php

namespace App\Policies;

use App\Models\Timesheet;
use App\Models\User;

class TimesheetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isInternalTeam();
    }

    public function view(User $user, Timesheet $timesheet): bool
    {
        return $user->isInternalTeam();
    }

    public function create(User $user): bool
    {
        return $user->isInternalTeam();
    }

    public function update(User $user, Timesheet $timesheet): bool
    {
        return $user->isInternalTeam() && ($user->isAdmin() || (int) $timesheet->user_id === (int) $user->id);
    }
}
