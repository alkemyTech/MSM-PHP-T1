<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    // ATRIBUTOS QUE SE PUEDEN ASIGNAR EN MASA
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'role_id',
        'deleted'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    // ATRIBUTOS OCULTOS PARA LA SERIALIZACIÓN
    protected $hidden = [
        'password',
        'role_id'
    ];

    protected $with = [
        'role',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];
    
    // RELACIONES DE FOREIGN KEYS
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    public function account(): HasMany
    {
        return $this->hasMany(Account::class);
    }

}