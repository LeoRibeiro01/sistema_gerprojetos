<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\Auditable;

class Projeto extends Model
{
    use Auditable, HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_termino',
        'status',
        'user_id',
        'updated_by',
        'cliente_id',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_termino' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function clientApprovals(): HasMany
    {
        return $this->hasMany(ClientApproval::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class, 'projeto_id');
    }

    public function epics(): HasMany
    {
        return $this->hasMany(Epic::class);
    }

    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'auditable_id')->where('auditable_type', self::class);
    }
}
