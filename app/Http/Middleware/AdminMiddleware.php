<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Verificar si el usuario autenticado tiene el rol de administrador
        if (Auth::check() && Auth::user()->role_id === 2) {
            return $next($request);
        }

        // Si el usuario no es administrador, puedes redirigir o devolver una respuesta de error
        return response()->json(['error' => 'Acceso no autorizado'], 403);
    }
}
