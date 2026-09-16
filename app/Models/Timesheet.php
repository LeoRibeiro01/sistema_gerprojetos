<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarefa_id',
        'user_id',
        'inicio',
        'fim',
        'duracao_minutos',
        'descricao',
        'billable',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fim' => 'datetime',
        'billable' => 'boolean',
    ];

    public function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDuracaoHorasAttribute(): float
    {
        return round(($this->duracao_minutos ?? 0) / 60, 2);
    }
}
