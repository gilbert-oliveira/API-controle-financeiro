<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $this->validate($request, [
            'password' => [
                'required'
            ],
            'email' => [
                'required',
                'email'
            ]
        ], [
            'password.required' => 'Por favor. Informe uma senha!',
            'email.required' => 'Por favor. Informe um e-mail!',
            'email.email' => 'Por favor. Informe um e-mail válido!'
        ]);

        $user = User::where('email', $request->email)->first();

        if (is_null($user) || !Hash::check($request->password, $user->password)) {
            return response()->json('Usuário ou senha inválidos!', 401);
        }

        $token = JWT::encode(
            ['email' => $request->email],
            env('JWT_KEY'),
            'HS256'
        );

        return response()->json([
            'access_token' => $token
        ]);
    }
}
