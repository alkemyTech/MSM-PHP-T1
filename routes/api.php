<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;




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

        // SOLICITUD DELETE A /users/{id} para eliminar un usuario
        Route::delete('/users/{id}', [UserController::class, 'delete']);

        //RUTA listar cuentas de usuarios segun su id
        Route::middleware(['auth'])->get('/api/accounts/{id}', [AccountController::class, 'getUserAccounts']);
    });
        // RUTA A /accounts: Esta ruta maneja la creación de cuentas
        Route::post('/accounts', [AccountController::class, 'createAccount']);

        // RUTA A /transactions/deposit: Esta ruta maneja el depósito en una cuenta propia.
        Route::post('/transactions/deposit', [TransactionController::class, 'deposit']);
});
