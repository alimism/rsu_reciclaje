<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = '/home';
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'password.required' => 'Debe establecer una contraseña antes de iniciar sesión. Contacte al administrador para habilitar su cuenta y obtener una contraseña.',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user->usertype_id != 1) {
            return $this->sendFailedLoginResponse($request, 'no-admin');
        }

        return redirect()->intended($this->redirectTo);
    }
}
