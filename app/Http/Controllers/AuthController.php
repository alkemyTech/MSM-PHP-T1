<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Comprobar si la validación falla y devolver una respuesta de error
        if ($validator->fails()) {
            return response()->badRequest();
        }

        // Hashear la contraseña antes de almacenarla
        $request['password'] = Hash::make($request['password']);

        // Crear un nuevo usuario con el rol de usuario regular (USER)
        $usuario = User::create(array_merge($request->toArray(), ['role_id' => Role::where('name', 'USER')->first()->id]));

        // Crear cuenta en pesos argentinos asociada al usuario
        Account::create([
            'currency' => 'ARS',
            'transaction_limit' => 300000,
            'balance' => 0,
            'user_id' => $usuario->id,
            'cbu' => '9832576142058764312901', // CBU genérico
            'deleted' => false,
        ]);

        // Crear cuenta en dólares asociada al usuario
        Account::create([
            'currency' => 'USD',
            'transaction_limit' => 1000,
            'balance' => 0,
            'user_id' => $usuario->id,
            'cbu' => '7651092384275098123468', // CBU genérico
            'deleted' => false,
        ]);

        // Generar un token de acceso para el usuario recién registrado
        $token = $usuario->createToken('token')->accessToken;

        // Devolver una respuesta de éxito con el token y la información del usuario
        return response()->created(['token' => $token, 'usuario' => $usuario]);
    }
}
