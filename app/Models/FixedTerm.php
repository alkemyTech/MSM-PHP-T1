<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedTerm extends Model
{
    use HasFactory;

    //asigna qué columnas de la base de datos se pueden llenar de forma masiva
    protected $fillable = [
        'amount',
        'interest',
        'total',
        'duration'
    ];

    //oculta los siguientes campos para que no aparezcan al hacer una consulta a la base de datos
    protected $hidden = [
       // 'account_id',
        'created_at',
        'updated_at',
        'closed_at'
    ];

     /* public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }  */

}
