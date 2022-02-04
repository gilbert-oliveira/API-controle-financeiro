<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class AuthenticatorMiddleware
{
    /**
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        try {

            if (!$request->hasHeader('Authorization')) {
                throw new Exception();
            }

            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);
            $authenticationData = JWT::decode($token, new Key(env('JWT_KEY'), 'HS256'));

            if (!array_key_exists('email', (array)$authenticationData))
                throw new Exception();

            $user = User::where('email', $authenticationData->email)->first();

            if (is_null($user))
                throw new Exception();
        } catch (Exception $e) {
            return response()->json('Não autorizado', 401);
        }


        return $next($request);
    }
}
