<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\AdminMiddleware;
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
        // SOLICITUD POST A REGISTER: Esta ruta maneja el registro de nuevos usuarios.
        Route::post('register', [AuthController::class, 'register'])->name('auth.registro')->withoutMiddleware(['auth:api']);

        // SOLICITUD POST A LOGIN: Esta ruta maneja la autenticacion de usuarios.
        Route::post('login', [AuthController::class, 'login'])->name('auth.login')->withoutMiddleware(['auth:api']);

        // SOLICITUD DELETE A /users/{id} para eliminar un usuario
        Route::delete('/users/{id}', [UserController::class, 'delete']);

        // SOLICITUD GET A /users para traer todos los usuarios (Solo ADMIN)
        Route::get('/users', [UserController::class, 'index']);


    });

    //RUTA listar cuentas de usuarios segun su id
    Route::get('/accounts/{user_id}', [AccountController::class, 'getUserAccounts'])->middleware([AdminMiddleware::class]);

    // SOLICITUD POST a /accounts para la creación de cuentas
    Route::post('/accounts', [AccountController::class, 'createAccount']);
    
    // SOLICITUD POST A /transactions/deposit: Esta ruta maneja el depósito en una cuenta propia.
    Route::post('/transactions/deposit', [TransactionController::class, 'deposit']);

    // SOLICITUD POST A /transactions/send: Esta ruta maneja el envio de dinero entre cuentas
    Route::post('/transactions/send', [TransactionController::class, 'sendMoney']);
      
    // SOLICITUD GET a /accounts/balance para obtener el estado de la cuenta del cliente
    Route::get('/accounts/balance', [AccountController::class, 'balance']);
  
    // SOLICITUD POST A /transactions/payment: Esta ruta maneja los pagos de una cuenta propia.
    Route::post('/transactions/payment', [PaymentController::class, 'makePayment']);

    // SOLICITUD GET a /transactions: Esta ruta obtiene todas las transacciones del usuario autenticado.
    Route::get('/transactions', [TransactionController::class, 'index']);
});
