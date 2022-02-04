<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        $this->validate($request, [
            'password' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'name' => [
                'required'
            ]
        ], [
            'password.required' => 'Por favor. Informe uma senha!',
            'email.required' => 'Por favor. Informe um e-mail!',
            'email.email' => 'Por favor. Informe um e-mail válido!',
            'email.unique' => 'E-mail já cadastrado. Infome outro!',
            'name.required' => 'Por favor. Informe um nome!'
        ]);

        $dados = $request->all();
        $dados['password'] = Hash::make($request->password);

        $user = User::create($dados);

        $token = JWT::encode(
            ['email' => $user->email],
            env('JWT_KEY'),
            'HS256'
        );

        return response()->json([
            'access_token' => $token
        ]);
    }
}
