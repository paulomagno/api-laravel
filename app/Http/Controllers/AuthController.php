<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    // Array para retorno dos dados
    private $array = ['error' => '', 'result' => [] , 'token' => ''];

    // Cria o usuário
    public function create(Request $request)
    {
        $this->array['error'] = '';

        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            $this->array['error'] = $validator->messages();
            return $this->array;
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user = new User();
        $user->email = $email;
        $user->password = password_hash($password,PASSWORD_DEFAULT);
        $user->token = '';
        $user->save();

        return $this->array;
    }

    // Autenticação do usuário
    public function login(Request $request)
    {
        $this->array['error'] = '';

        $creds = $request->only('email','password');

        if(Auth::attempt($creds)) {

            $user  = User::where('email',$creds['email'])->first();

            $token = $user->createToken(time().rand(0,9999))->plainTextToken;

            $this->array['token'] = $token;

        } else {
            $this->array['error'] = 'E-mail e/ou senha incorretos';
        }

        return $this->array;
    }

    // Logout do Usuário com Sanctum
    public function logout(Request $request)
    {
        $this->array = ['error' => ''];

        $user = $request->user();
        $user->tokens()->delete();

        return $this->array;
    }

    // Logout com JWT
    public function logoutSanctum()
    {
        $this->array['error'] = '';

        Auth::logout();

        return $this->array;
    }

    // Login COM JWT
    public function loginJWT(Request $request)
    {
        $this->array['error'] = '';

        $creds = $request->only('email','password');

        $token = Auth::attempt($creds);

        if($token) {
            $this->array['token'] = $token;
        }
        else {
            $this->array['error'] = 'E-mail e/ou senha incorretos';
        }

        return $this->array;
    }

    // Retorna o usuário logado
    public function me()
    {
        $this->array['error'] = '';

        $user = Auth::user();

        $this->array['email'] = $user->email;

        return $this->array;
    }
}
