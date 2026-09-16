<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCheckin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'data', 'ontem', 'hoje', 'impedimentos'];

    protected $casts = ['data' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
