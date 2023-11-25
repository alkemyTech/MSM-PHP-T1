<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FixedTermController;
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
        // SOLICITUD POST a register: Ruta que maneja el registro de nuevos usuarios.
        Route::post('register', [AuthController::class, 'register'])->name('auth.registro')->withoutMiddleware(['auth:api']);

        // SOLICITUD POST a login: Ruta que maneja la autenticacion de usuarios.
        Route::post('login', [AuthController::class, 'login'])->name('auth.login')->withoutMiddleware(['auth:api']);

        // SOLICITUD DELETE a /users/{id}: Ruta para eliminar un usuario
        Route::delete('/users/{id}', [UserController::class, 'delete']);

        // SOLICITUD GET a /users: Ruta para traer todos los usuarios (Solo ADMIN)
        Route::get('/users', [UserController::class, 'index']);
    });
    
    // SOLICITUD POST a /accounts: Ruta para crear una cuenta en ARS o USD
    Route::post('/accounts', [AccountController::class, 'createAccount']);
    
    // SOLICITUD POST a /transactions/deposit: Ruta que maneja el depósito en una cuenta propia.
    Route::post('/transactions/deposit', [TransactionController::class, 'deposit']);

    // SOLICITUD POST a /transactions/send: Ruta que maneja el envio de dinero entre cuentas.
    Route::post('/transactions/send', [TransactionController::class, 'sendMoney']);
      
    // SOLICITUD GET a /accounts/balance: Ruta para obtener el estado de la cuenta del cliente.
    Route::get('/accounts/balance', [AccountController::class, 'balance']);
  
    // SOLICITUD POST a /transactions/payment: Ruta que maneja los pagos de una cuenta propia.
    Route::post('/transactions/payment', [PaymentController::class, 'makePayment']);

    // SOLICITUD GET a /transactions: Ruta para obtener las transacciones del usuario autenticado.
    Route::get('/transactions', [TransactionController::class, 'index']);

    // SOLICITUD PATCH a /transactions/{id}: Ruta para editar la descripcion de una transaccion.
    Route::patch('/transactions/{id}', [TransactionController::class, 'updateDescription']);

    // SOLICITUD PATCH a /accounts/{id}: Ruta para editar el limite de transaccion de una cuenta
    Route::patch('/accounts/{id}', [AccountController::class, 'editAccount']);

    // SOLICITUD POST a /fixed_terms para crear un nuevo plazo fijo
    Route::post('fixed_terms', [FixedTermController::class, 'create']);});
