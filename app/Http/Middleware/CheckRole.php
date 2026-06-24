<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Se não estiver logado ou o cargo não coincidir, barra o acesso
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Acesso não autorizado ao painel administrativo.');
        }

        return $next($request);
    }
}