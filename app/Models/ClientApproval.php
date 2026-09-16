<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientApproval extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = ['projeto_id', 'user_id', 'status', 'comentario', 'approved_at'];

    protected $casts = ['approved_at' => 'datetime'];

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
