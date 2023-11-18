<?php

namespace App\Http;

use App\Models\Account;
use App\Models\FixedTerm;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserBalanceDTO
{
    private $user;
    private $balance;
    private $accounts;
    private $fixed_term_deposits;
    private $history;

    public function __construct()
    {
        $userId = Auth::id(); // Obtener el ID del usuario autenticado
        $this->user = User::find($userId); //Carga de datos del usuario cuya ID corresponda a la recibida en auth
        if ($this->user) { //Si el usuario se encuentra en db, se ejecutara el bloque de codigo siguiente
            $this->accounts = $this->user->accounts; //Trae las cuentas del usuario
            $this->balance = ['ARS accounts balance' => 0, 'USD accounts balance' => 0]; // Inicializa la variable de balance
            $this->history = Transaction::whereIn('account_id', $this->accounts->pluck('id'))->get(); //Se obtiene el historial de transacciones del usuario
            $this->fixed_term_deposits = FixedTerm::whereIn('account_id', $this->accounts->pluck('id'))->get(); //Se obtienen todos los plazos fijos asociados a la cuenta
        }
    }

    private function calculateBalance() // Funcion para calcular el balance de cuentas del usuario
    {
        $arsBalance = 0;
        $usdBalance = 0;

        foreach ($this->accounts as $account) {
            if ($account->currency === 'ARS') {
                $arsBalance += $account->balance;
            } elseif ($account->currency === 'USD') {
                $usdBalance += $account->balance;
            }
        }

        return [
            'ARS accounts balance' => $arsBalance,
            'USD accounts balance' => $usdBalance,
        ];
    }

    public function toArray() //Retornado de los datos recolectados de la cuenta, se introducen en un array y son mostrados
    {
        return [
            'user' => $this->user,
            'accounts' => $this->accounts,
            'balance' => $this->calculateBalance(),
            'history' => $this->history,
            'fixed_term_deposits' => $this->fixed_term_deposits,
        ];
    }
}
