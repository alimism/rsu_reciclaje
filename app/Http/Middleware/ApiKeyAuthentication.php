<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $apiKey = $request->header('X-API-Key');

            if (!isset($apiKey) || $apiKey !== env('API_KEY')) {
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Acceso no autorizado. API Key inválida.',
                ], 401);
            }

            return $next($request);
        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error interno del servidor.',
            ], 500);
        }
    }
}
