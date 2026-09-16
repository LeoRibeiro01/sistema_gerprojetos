<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['tarefa_id', 'user_id', 'conteudo', 'mencoes'];

    protected $casts = ['mencoes' => 'array'];

    public function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function extractMentions(string $content): array
    {
        preg_match_all('/@([A-Za-z0-9._-]+)/', $content, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }
}
