<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
        'cliente_id',
        'avatar_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function projetos(): HasMany
    {
        return $this->hasMany(Projeto::class);
    }

    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class);
    }

    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    public function dashboardCharts(): HasMany
    {
        return $this->hasMany(DashboardChart::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function avatarUrl(): string
    {
        return $this->avatar_path
            ? asset('storage/'.$this->avatar_path)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=1e3a8a&color=fff';
    }

    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === UserRole::Admin->value;
    }

    public function hasRole(UserRole ...$roles): bool
    {
        $values = array_map(fn (UserRole $role) => $role->value, $roles);
        $effectiveRole = $this->isAdmin() ? UserRole::Admin->value : ($this->role ?? UserRole::Dev->value);

        return in_array($effectiveRole, $values, true);
    }

    public function isInternalTeam(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($this->role, UserRole::internalTeam(), true);
    }
}
