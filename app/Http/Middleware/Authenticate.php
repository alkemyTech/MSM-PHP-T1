<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    public function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('auth.login'); // Asegúrate de que 'login' coincida con el nombre de tu ruta de inicio de sesión.
        }
    }
}
