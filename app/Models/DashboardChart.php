<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardChart extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'titulo', 'metrica', 'tipo', 'cor'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
