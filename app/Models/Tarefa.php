<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\Auditable;

class Tarefa extends Model
{
    use Auditable, HasFactory;

    public const STATUS_BACKLOG = 'backlog';
    public const STATUS_A_FAZER = 'a_fazer';
    public const STATUS_DESENVOLVIMENTO = 'em_desenvolvimento';
    public const STATUS_CODE_REVIEW = 'code_review';
    public const STATUS_QA = 'qa';
    public const STATUS_CONCLUIDO = 'concluido';

    public const TIPOS = ['feature', 'bug', 'melhoria', 'debito_tecnico'];

    public const PRIORIDADES = ['baixa', 'media', 'alta', 'critica'];

    public static function statuses(): array
    {
        return [
            self::STATUS_BACKLOG,
            self::STATUS_A_FAZER,
            self::STATUS_DESENVOLVIMENTO,
            self::STATUS_CODE_REVIEW,
            self::STATUS_QA,
            self::STATUS_CONCLUIDO,
        ];
    }

    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_termino',
        'status',
        'projeto_id',
        'user_id',
        'updated_by',
        'estimativa_minutos',
        'tipo',
        'prioridade',
        'tag',
        'sprint_id',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_termino' => 'date',
    ];

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class, 'projeto_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function dependencias(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'tarefa_dependencias', 'tarefa_id', 'depende_de_id');
    }

    public function dependentes(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'tarefa_dependencias', 'depende_de_id', 'tarefa_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'auditable_id')->where('auditable_type', self::class);
    }
}
