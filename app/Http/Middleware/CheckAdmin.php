<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckClient
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está autenticado e se é um administrador
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); // Permite o acesso
        }

        // Redireciona para a home com mensagem de erro se não for admin
        return redirect('/')->with('error', 'Acesso negado. Você não tem permissão para acessar esta área.');
    }
}
