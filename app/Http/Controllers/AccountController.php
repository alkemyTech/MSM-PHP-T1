<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function createAccount(Request $request)
    {
        // Validación de la solicitud
        // Asegura que se ha proporcionado una moneda y que es una de las opciones permitidas ('ARS' o 'USD').
        $request->validate([
            'currency' => 'required|in:ARS,USD',
        ]);

        // Crear una nueva cuenta
        $account = new Account();
        $account->user_id = Auth::id(); // Asociar al usuario autenticado
        $account->currency = $request->input('currency'); // Trae los datos del request
        $account->balance = 0; // Establece el balance inicial en cero
        $account->transaction_limit = ($request->input('currency') == 'ARS') ? 300000 : 1000; // Limite de transaccion
        $account->cbu = $this->generarCbuAleatorio(); // Generar CBU aleatorio

        $account->save();

        // Devuelve una respuesta JSON con los datos de la cuenta creada y una respuesta HTTP 201.
        return response()->json($account, 201);
    }

    // Utiliza la función str_shuffle para mezclar una cadena de números y luego selecciona los primeros 22 caracteres.
    private function generarCbuAleatorio()
    {
        return substr(str_shuffle(str_repeat('0123456789', 3)), 0, 22);
    }
}