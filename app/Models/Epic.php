<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Epic extends Model
{
    use HasFactory;

    protected $fillable = ['projeto_id', 'titulo', 'descricao', 'status'];

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }

    public function userStories(): HasMany
    {
        return $this->hasMany(UserStory::class);
    }
}
