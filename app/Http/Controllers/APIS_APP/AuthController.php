<?php

namespace App\Http\Controllers\APIS_APP;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //Registro de usuarios
    public function register(Request $request)
    {
        try {
            // Validación de  datos de entrada
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            // validación es exitosa
            $user = User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'usertype_id' => 2,
                'status' => 1,
                'zone_id'=>$request->zone_id
            ]);


            $token = $user->createToken('auth_token')->plainTextToken; // Generamos un token para el usuario recién registrado

            // Respuesta exitosa con el token y los datos del usuario
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Registro exitoso, Bienvenido!',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            // Manejar cualquier excepción que ocurra durante el proceso de registro
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Inicio de sesión de usuarios
    public function login(Request $request)
    {
        try {
            $data = json_decode($request->getContent());
            $user = User::where('email', $data->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Usuario no encontrado'
                ], 400);
            }

            if (!Hash::check($data->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Credenciales incorrectas'
                ], 400);
            }

            if ($user->usertype_id != 2) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Su perfil de usuario es inválido'
                ], 400);
            }

            if ($user->status != 1) {
                return response()->json([
                    'status' => 'error',
                    'code' => 403,
                    'message' => 'Su usuario se encuentra inactivo'
                ], 403);
            }

            //Validaciones exitosas
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Inicio de sesión exitoso, Bienvenido!',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    //Cerrar Sesión
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete(); // Eliminar el token de acceso actual del usuario

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Sesión cerrada exitosamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
