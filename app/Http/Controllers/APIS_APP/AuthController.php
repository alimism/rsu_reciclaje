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

        //$response = ["status"=>0, "message"=>"", "token"=>"","token_type"=>"Bearer" ];

           $validator = Validator::make($request->all(), [
               //'name' => 'required|string|max:255',
               'email' => 'required|string|email|max:255|unique:users',
               //'password' => 'required|string|min:8',
           ]);

           if ($validator->fails()) {
                //$mensaje = $validator->errors();
                $response = ['status'=> 400,'message' => 'El email ingresado ya está siendo usado' ];
           }else{
                $user = User::create([
                    'name' => $request->name,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'usertype_id' => 2,
                    'status' => 1,
                ]);

                $token = $user->createToken('auth_token')->plainTextToken;
                $response = ['status' => 200, 'message'=>'Registro exitoso, Bienvenido!','token' => $token,'user' => $user ] ;

           }
           return response()->json($response);

       }

    // Inicio de sesión de usuarios
    public function login(Request $request)
    {

        $data = json_decode($request->getContent());

        $user = User::where('email',$data->email)->first();

        if($user){
            if(Hash::check($data->password,$user->password)){

                if($user->usertype_id != 2){
                    $response = ['status'=> 400,'message' => 'Su perfil de usuario es inválido' ];
                } else if ($user->status != 1){
                    $response = ['status'=> 403,'message' => 'Su usuario se encuentra inactivo' ];
                } else{
                    $token = $user->createToken('auth_token')->plainTextToken;
                    $response = ['status'=> 200,'message' => 'Inicio de sesión exitoso, Bienvenido!', 'user' => $user, 'token' => $token, 'token_type'=>'Bearer'];
                }

            }else{
                $response = ['status'=> 400,'message'=>'Credenciales incorrectas.'];
            }
        }else{
           $response = ['status'=> 400, 'message' => 'Usuario no encontrado'];
        }
       return response()->json($response);
    }

    public function logout(Request $request){

        $request->user()->currentAccesToken()->delete();
        return response()->json(['status'=>200,'message'=>'Sesion cerrada']);
    }



}
