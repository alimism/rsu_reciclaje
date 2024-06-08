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
    //
       // Registro de usuarios
       public function register(Request $request)
       {
           $validator = Validator::make($request->all(), [
               'name' => 'required|string|max:255',
               'email' => 'required|string|email|max:255|unique:users',
               'password' => 'required|string|min:8',
           ]);

           if ($validator->fails()) {
               return response()->json($validator->errors(), 400);
           }

           $user = User::create([
               'name' => $request->name,
               'email' => $request->email,
               'password' => Hash::make($request->password),
           ]);

           $token = $user->createToken('auth_token')->plainTextToken;

           return response()->json([
               'access_token' => $token,
               'token_type' => 'Bearer',
           ]);
       }

    // Inicio de sesión de usuarios
    public function login(Request $request)
    {

        $response = ["status"=>0, "message"=>"", "access_token"=>"","token_type"=>"Bearer" ];

        $data = json_decode($request->getContent());

        $user = User::where('email',$data->email)->first();

        if($user){
            if(Hash::check($data->password,$user->password)){
                $token = $user->createToken('auth_token')->plainTextToken;
                $response['status'] = 200;
                $response['message'] = 'Inicio exitoso';
                $response['access_token'] = $token;

            }else{
                $response['message'] = 'Credenciales incorrectas.';
            }
        }else{
            $response['message'] = 'User not found';
        }
       return response()->json($response);

        // $credentials = $request->only('email', 'password');

        // if (!Auth::attempt($credentials)) {
        //     // Agregar registro de error

        //     return response()->json(['message' => 'Invalid login details'], 401);
        // }

        // $user = Auth::user();
        // if (!$user) {
        //     // Manejar el caso en el que Auth::user() devuelve null

        //     return response()->json(['message' => 'User not found'], 401);
        // }

        // $token = $user->createToken('auth_token')->plainTextToken;

        // // Agregar registro de éxito


        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        // ]);
    }



}
