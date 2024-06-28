<?php

namespace App\Http\Controllers\APIS_APP;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    //Actualizar datos de usuario
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user(); // Obtenemos al usuario autenticado


            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'lastname' => 'nullable|string|max:255',
                'dni' => 'nullable|string|max:20',
                'birthdate' => 'nullable',
                'address' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            ]);



            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => $validator->errors()->first(),
                ], 400);
            }


            // validación es exitosa
            $updateData = [
                'name' => $request->name,
                'lastname' => $request->lastname,
                'dni' => $request->dni,
                'birthdate' => $request->birthdate,
                'address' => $request->address,
                'email' => $request->email,
            ];

            $user->update($updateData);


            // Respuesta exitosa con el token y los datos del usuario
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Datos actualizados correctamente!',
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Eliminar usaurio
    public function deleteUser(Request $request)
    {
        try {
            // Obtenemos al usuario autenticado
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Usuario no encontrado',
                ], 404);
            }

              // Eliminar tokens asociados al usuario
            PersonalAccessToken::where('tokenable_id', $user->id)
              ->where('tokenable_type', get_class($user))
              ->delete();

            // Eliminamos al usuario
            $user->delete();

            // Respuesta exitosa
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Usuario eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
