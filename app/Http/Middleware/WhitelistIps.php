<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class WhitelistIps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->ip(); // Get the IP from the request

        // Check if the IP exists in the 'accessip' table
        $accessIp = DB::table('accessip')->where('ip', $ip)->first();

        if (!$accessIp) {
            // IP not found in the 'accessip' table, deny access
            return response()->json(['error' => 'Unauthorized. Your IP is not allowed.'], 403);
        }

        return $next($request);
    }
}
