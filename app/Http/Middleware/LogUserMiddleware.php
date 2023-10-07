<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Client\Response;
class LogUserMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
     $response = $next($request);
		$token = $request->bearerToken();

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        $user = \App\Models\User::where('token', $token)->first();

        if ($user)
        {

            // Log the user activity to the database
            ActivityLog::query()->create([
                'user_id' => $user->id,
                'request' => $request->fullUrl(),
				'response'=> $response->getContent(),
				'status'=> $response->status(),
				'ip'=> $request->ip(),
            ]);
        }

        return $response;
    }
}
