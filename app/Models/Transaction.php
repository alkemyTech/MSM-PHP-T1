<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model //Creacion de la nueva clase "Transaction"
{
    use HasFactory;

    protected $fillable  = [ //Configuracion de las propiedades rellenables en base de datos
        'amount',
        'type',
        'description',
        'account_id',
        'transaction_date',
    ];

    public function account() //Esta funcion indica que nuestro modelo "transaction" pertenece a "account" y asocia a los datos de la transaccion a la cuenta del cliente.
    {
        return $this->belongsTo(Account::class);
    }
}
