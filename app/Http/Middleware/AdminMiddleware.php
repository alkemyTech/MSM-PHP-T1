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
    if (Auth::check() && Auth::user()->role_id === 2) {
        return $next($request);
    }

    return redirect()->route('login'); // Esto redirigirá al usuario a la página de inicio de sesión si no está autenticado.
}
}

