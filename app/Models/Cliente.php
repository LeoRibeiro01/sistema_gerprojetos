<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco',
    ];

    public function projetos(): HasMany
    {
        return $this->hasMany(Projeto::class, 'cliente_id');
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'cliente_id');
    }
}
