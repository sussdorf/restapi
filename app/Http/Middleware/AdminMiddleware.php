<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class AdminMiddleware
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

        $user = \App\Models\User::where('token', $token)->first();

        if ($user->is_admin == 1){
            // Now let's put the user in the request class so that you can grab it from there

            return $next($request);
        } else {
            return response()->json([
                'error' => 'You have not permission to do this.'
            ], 400);
        }
    }
}
