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
                'bail',
                'required'
            ],
            'email' => [
                'bail',
                'required',
                'unique:users,email',
                'email'
            ],
            'name' => [
                'bail',
                'required'
            ]
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
