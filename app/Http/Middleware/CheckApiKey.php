<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $API_KEY = 'Non-Manger_only-requestINNc54cffsw5e';
        $apiKey = $request->query('api_key');

        if (!$apiKey || $apiKey !== $API_KEY) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
