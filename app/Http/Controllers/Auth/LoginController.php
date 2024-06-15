<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    use AuthenticatesUsers {
        attemptLogin as traitAttemptLogin;
    }
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
        // Realizar autenticación utilizando el método del trait
        if (!$this->traitAttemptLogin($request)) {
            return false;
        }

        $user = Auth::user();

        if ($user->usertype_id != 1) {
            $this->guard()->logout();
            return $this->sendFailedLoginResponse($request, 'no-admin');
        }

        return true;
    }
}
