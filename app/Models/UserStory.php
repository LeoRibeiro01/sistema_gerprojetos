<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStory extends Model
{
    use HasFactory;

    protected $fillable = ['epic_id', 'sprint_id', 'titulo', 'descricao', 'pontos', 'criterio_aceite', 'status'];

    public function epic(): BelongsTo
    {
        return $this->belongsTo(Epic::class);
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }
}
