<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Verifica se o usuário é um administrador.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->email === 'admin@gmail.com'; // Substitua pelo e-mail do admin ou pela lógica que você usa
        // Exemplo com campo role:
        // return $this->role === 'admin'; // Caso utilize um campo para definir o tipo de usuário
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
