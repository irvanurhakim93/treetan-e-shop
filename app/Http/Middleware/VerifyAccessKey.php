<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccessKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

         $accessKey = $request->header('X-Access-key');

        // Bisa simpan access key di .env agar mudah diatur
        // $validKey = env('ACCESS_KEY');

     if (!$accessKey || $accessKey !== config('services.api.access_key')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing access key',
            ], 403); // 403 = Forbidden
        }

        return $next($request);
    }
}
