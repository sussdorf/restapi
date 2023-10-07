<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestLogger
{
    /** @var int */
    private $startTime;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->startTime = round(microtime(true) * 1000);

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $user = $request->user();
        $userId = $user ? $user->id : 0;

        $token = $user && $user->token() ? $user->token()->id : null;

        $method = strtoupper($request->getMethod());

        $statusCode = $response->getStatusCode();

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(['password', 'password_confirmation']));

        $contentType = $response->headers->get('Content-Type');

        $runTime = round(microtime(true) * 1000) - $this->startTime;

        Log::info("{$statusCode} {$runTime}ms {$method} {$contentType} {$uri} | User: {$userId} | Token: {$token} | {$bodyAsJson}");
    }
}
