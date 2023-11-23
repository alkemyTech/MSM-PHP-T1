<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\UserBalanceDTO;

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




    public function getUserAccounts(Request $request, $user_id)
{
    // Obtiene el usuario autenticado
    $user = $request->user();

    // Obtiene el rol de administrador dinámicamente
    $adminRole = Role::where('name', 'ADMIN')->first();

    // Verifica si el usuario autenticado tiene el rol de administrador
    if ($user && $user->role_id !== $adminRole->id) {
        return response()->json(['message' => "No tiene permiso para acceder a esta función"], 403);
    }

    // Busca las cuentas asociadas al usuario con el ID proporcionado
    $accounts = Account::where('user_id', $user_id)->get();

    if ($accounts->isEmpty()) {
        return response()->json(['message' => "No se encontraron cuentas asociadas a este usuario"], 404);
    }

    return response()->json(['accounts' => $accounts], 200);
}

    // Proporciona el balance de la cuenta del usuario mediante DTO UserBalance y transforma en Array el resultado
    public function balance()
    {
        return response()->ok(['Balance de la cuenta' => (new UserBalanceDTO())->toArray()]);
    }

    public function editAccount($id, Request $request)
    {
        // Validar que 'transaction_limit' esté presente en la solicitud.
        // Si no se completa con un NUMBER devuelve un error.
        $request->validate([
            'transaction_limit' => 'required|numeric',
        ]);

        // Obtener la cuenta a editar.
        $account = Account::find($id);

        // Verificar si la cuenta existe.
        if (!$account) {
            return response()->json(['error' => 'La cuenta no existe.'], 404);
        }

        // Verificar si la cuenta pertenece al usuario logueado.
        if ($account->user_id !== Auth::id()) {
            return response()->json(['error' => 'La cuenta no pertenece al usuario logueado.'], 403);
        }

        // Actualizar el tope de transferencia de la cuenta.
        $account->update([
            'transaction_limit' => $request->input('transaction_limit'),
        ]);

        // Devolver la cuenta actualizada.
        return response()->json(['message' => 'Cuenta actualizada con éxito.', 'account' => $account]);
    }
}