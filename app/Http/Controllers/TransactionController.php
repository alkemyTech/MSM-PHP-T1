<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function sendMoney(Request $request)
    {
        // Obtener el usuario emisor desde el token
        $user = Auth::user();

        // Validar si el usuario tiene suficiente saldo y límite
        $account = Account::find($request->input('account_id'));

        if (!$account || $account->user_id !== $user->id) {
            return response()->json(['error' => 'La cuenta emisora no existe o no pertenece al usuario logueado.'], 422);
        }

        if ($account->balance < $request->input('amount') || $account->transaction_limit < $request->input('amount')) {
            return response()->json(['error' => 'Saldo o límite insuficiente'], 400);
        }

        // Obtener el usuario receptor
        $recipient = Account::find($request->input('usuario_destino_id'));

        if (!$recipient) {
            return response()->json(['error' => 'Cuenta destino no encontrada'], 404);
        }
    
        // Validar si las monedas de las cuentas son iguales
        if ($account->currency !== $recipient->currency) {
            return response()->json(['error' => 'Las monedas de las cuentas no coinciden'], 400);
        }

        try {
            // Iniciar una transacción de base de datos
            DB::beginTransaction();

            // Crear transacción INCOME para el usuario receptor
            $incomeTransaction = new Transaction([
                'amount' => $request->input('amount'),
                'type' => 'INCOME',
                'description' => 'Transferencia recibida de ' . $user->name,
                'transaction_date' => now(),
            ]);

            // Relacionar la transacción con la cuenta del receptor
            $recipient->transactions()->save($incomeTransaction);

            // Crear transacción PAYMENT para el usuario emisor
            $paymentTransaction = new Transaction([
                'amount' => $request->input('amount'),
                'type' => 'PAYMENT',
                'description' => 'Transferencia enviada a ' . $recipient->user->name,
                'transaction_date' => now(),
            ]);

            // Relacionar la transacción con la cuenta del emisor
            $account->transactions()->save($paymentTransaction);

            // Actualizar el balance de las cuentas
            $account->update(['balance' => $account->balance - $request->input('amount')]);
            $recipient->update(['balance' => $recipient->balance + $request->input('amount')]);

            // Confirmar la transacción
            DB::commit();

            // Obtener el balance actualizado del usuario que envia dinero
            $updatedBalance = [
                'balance_actual' => $account->fresh()->balance,
            ];

            // Retornar una respuesta de éxito con los balances actualizados
            return response()->json(['message' => 'Transferencia exitosa', 'data' => [
                'transaccion_emisor' => $paymentTransaction,
                'transaccion_receptor' => $incomeTransaction,
                'balance' => $updatedBalance,
            ]]);

        } catch (\Exception $e) {
            // Si hay un error, revierte la transacción
            DB::rollBack();
            return response()->json(['error' => 'Error en la transacción: ' . $e->getMessage()], 500);
        }
    }
    public function index()
    {
        $user = auth()->user(); // Obtiene el usuario autenticado
        $transactions = Transaction::with('account')
                                    // Filtra las transacciones que pertenecen al usuario autenticado.
                                    ->whereHas('account', function ($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                    })
                                    ->get(); // Obtiene todas las transacciones que cumplen con los criterios anteriores.
    
        $message = "Listado de transacciones de {$user->name} {$user->last_name}";
        
        return response()->json(['message' => $message, 'transactions' => $transactions]);
    } 
    public function updateDescription($id, Request $request)
    {
        // Validar que 'description' esté presente en la solicitud.
        // Si no se completa con un STRING devuelve un error.
        $request->validate([
            'description' => 'required|string',
        ]);

        // Obtener la transacción a actualizar.
        $transaction = Transaction::find($id);

        // Verificar si la transacción existe.
        if (!$transaction) {
            return response()->json(['error' => 'La transacción no existe.'], 404);
        }

        // Verificar si la transacción pertenece al usuario logueado.
        if ($transaction->account->user_id !== Auth::id()) {
            return response()->json(['error' => 'La transacción no pertenece al usuario logueado.'], 403);
        }

        // Actualizar la descripción de la transacción.
        $transaction->update([
            'description' => $request->input('description'),
        ]);

        // Devolver la transacción actualizada.
        return response()->json(['message' => 'Descripción de la transacción actualizada con éxito.', 'transaction' => $transaction]);
    }

    
    public function transactionDescription ( $transaction_id,Request $request){ //metodo para consultar el detalle de una transaccion.

   
        
        $transaction = Transaction::find($transaction_id);//traemos la solicitud con el id correspondiente 
    
            // Verificamos si la transacción existe.
            if (!$transaction) {
                return response()->json(['error' => 'La transacción no existe.'], 404);
            }

              // Verificar si la transacción pertenece al usuario logueado.
              if ($transaction->account->user_id !== Auth::id()) {
                return response()->json(['error' => 'La transacción no pertenece al usuario logueado.'], 403);// en caso de no ser el usuario logeado error
            }
            else{  return response()->created(['message' => 'Description successfully updated', $transaction]);}// en caso de ser el usuario devolvemos una respuesta 
       
    }
}