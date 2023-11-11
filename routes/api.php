<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['api', 'auth:api'])->group(function () {
    // Define un grupo de rutas con prefijo 'auth'
    Route::prefix('auth')->group(function () { 
        
        // RUTA REGISTER: Esta ruta maneja el registro de nuevos usuarios.
        Route::post('register', [AuthController::class, 'register'])->name('auth.registro')->withoutMiddleware(['auth:api']);

        // RUTA LOGIN: Esta ruta maneja la autenticacion de usuarios.
        Route::post('login', [AuthController::class, 'login'])->name('auth.login')->withoutMiddleware(['auth:api']);

    });
});
