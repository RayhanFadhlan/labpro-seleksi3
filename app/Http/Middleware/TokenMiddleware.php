<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->cookie('auth_token');

            if (!$token) {
                return redirect()->route('login')->with('message', 'Please log in to continue.');
            }

            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $userId = $decoded->sub;

            $request->merge(['user_id' => $userId]);
            return $next($request);
        } catch (\Exception $e) {
            return redirect()->route('error', ['message' => $e->getMessage()]);

        }

    }
}
