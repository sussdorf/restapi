<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

//        try {
//            $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
//        } catch(ExpiredException $e) {
//            return response()->json([
//                'error' => 'Provided token is expired.'
//            ], 400);
//        } catch(Exception $e) {
//            return response()->json([
//                'error' => 'An error while decoding token.'
//            ], 400);
//        }


        $user = \App\Models\User::where('token', $token)->first();

        if ($user){
            // Now let's put the user in the request class so that you can grab it from there
            $request->auth = $user;

            return $next($request);
        } else {
            return response()->json([
                'error' => 'Provided token is not valid.'
            ], 400);
        }
    }
}
