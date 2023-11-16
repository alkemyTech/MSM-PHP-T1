<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{   // metodo index par listar los usuarios 
    public function index(Request $request)
    {   //aca recuperamos el role_id
        $roleId = $request->user()->role_id;
        if ($roleId == 2) {
            // Si el role_id es 2, devolver la lista de todos los usuarios
            $usuarios = User::all();
            return response()->json($usuarios, 200);
        } else {
            // Si el role_id no es 2, devolver un mensaje de error
            return response()->json(['message' => 'Role de usuario no válido'], 403);
        }
    }

    public function delete(Request $request, $id)
    {
        // Verifica si el usuario tiene permisos de ADMIN
        if ($request->user()->role === 'ADMIN' || $request->user()->id == $id) {
            // Busca al usuario en la base de datos por su ID
            $user = User::find($id);

            if ($user) {
                // Actualiza la columna 'deleted' a 1 para realizar el borrado lógico
                $user->update(['deleted' => 1]);
                // Retorna una respuesta JSON indicando que el usuario fue dado de baja exitosamente
                return response()->json(['message' => 'Usuario dado de baja exitosamente']);
            } else {
                // Retorna una respuesta JSON indicando que el usuario no fue encontrado (código 404)
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }
        } else {
            // Retorna una respuesta JSON indicando que el usuario no tiene permisos para realizar la acción (código 403)
            return response()->json(['message' => 'No tienes permiso para realizar esta acción.'], 403);
        }
    }
}