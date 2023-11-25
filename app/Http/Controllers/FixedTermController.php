<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\UserBalanceDTO;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use App\Models\FixedTerm;
use Illuminate\Validation\Rule;

class FixedTermController extends Controller
{
    public function create(Request $req)
    {
        // Obtencion del usuario autenticado
        $user = Auth::user();
    
        // Buscamos la cuenta en pesos que pertenece al usuario
        $account = Account::where('id', $req->account_id)
            ->where('user_id', $user->id)
            ->where('currency', 'ARS')
            ->where('deleted', false)
            ->first();
    
        // Validacion de la solicitud
        $req->validate([
            'account_id' => Rule::exists('accounts', 'id')->where('user_id', $user->id)->where('currency', 'ARS')->where('deleted', false),
            'amount' => "numeric|gte:1000|lte:{$account->transaction_limit}",
            'duration' => 'numeric|gte:30',
        ]);
    
        // Verificacion del dinero en cuenta, si no hay suficiente retorna mensaje
        if ($account->balance < $req->amount) {
            return response()->unprocessableContent([], 'No dispone del dinero suficiente para realizar esta operacion');
        }
    
        // Obtencion de interes diario via variable de entorno
        $fixedTermInterest = env('FIXED_TERM_INTEREST');
    
        // Calculamos el total del plazo fijo
        $fixedTermTotal = $req->amount + ((($req->amount * $fixedTermInterest) / 100) * $req->duration);
    
        // Creacion del nuevo plazo fijo
        $fixedTerm = new FixedTerm([
            'amount' => $req->amount,
            'duration' => $req->duration,
            'account_id' => $account->id,
            'interest' => $fixedTermInterest,
            'total' => $fixedTermTotal,
            'closed_at' => Carbon::now()->addDays(intval($req->duration)),
        ]);
    
        // Se actualiza el balance de la cuenta
        $account->balance -= $req->amount;
    
        // Guardado y actualizacion de la cuenta con el plazo fijo
        $fixedTerm->save();
        $account->save();
    
        // Carga la cuenta para devolverla en el json de la respuesta
        $fixedTerm->load('account');
    
        // Return de respuesta creacion satisfactoria 
        return response()->created(['message' => 'Su plazo fijo ha sido creado exitosamente', 'fixed_term' => $fixedTerm]);
    }
}
