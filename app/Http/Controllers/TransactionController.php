<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function deposit(Request $request)
    {
        // Se verifica que 'account_id' y 'amount' estén presentes en la solicitud y sean numéricos.
        // Además, se verifica que 'amount' sea mayor o igual a 0.
        $request->validate([
            'account_id' => 'required|numeric',
            'amount' => 'required|numeric|min:0',
        ]);

        // Intentar obtener la cuenta del usuario autenticado. 
        // Si no se encuentra, se lanza una excepción y se devuelve un error y un código de estado HTTP 422.
        try {
            $account = Account::findOrFail($request->input('account_id'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'La cuenta no existe.'], 422);
        }

        // Verificar que la cuenta obtenida pertenece al usuario logueado. 
        if ($account->user_id !== Auth::id()) {
            return response()->json(['error' => 'La cuenta no pertenece al usuario logueado.'], 422);
        }

        // Crear un nuevo registro en la tabla 'transactions'.
        $transaction = Transaction::create([
            'amount' => $request->input('amount'),
            'type' => 'DEPOSIT',
            'description' => 'Depósito en cuenta',
            'account_id' => $account->id,
            'transaction_date' => now(),
        ]);

        // Actualizar el balance de la cuenta sumándole el 'amount' proporcionado en la solicitud.
        $account->balance += $request->input('amount');
        $account->save();

        // Devolver el registro de la transacción creado y la cuenta con el balance actualizado en formato JSON.
        return response()->json(['transaction' => $transaction, 'account' => $account]);
    }
}