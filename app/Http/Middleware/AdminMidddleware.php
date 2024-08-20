<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AdminMidddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private $jwtSecret;
    public function __construct()
    {
        $this->jwtSecret = env('JWT_SECRET');
    }
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token not provided',
                    'data' => null
                ], 401);
            }

           
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));

            
            if ($decoded->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized: Admins only',
                    'data' => null
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid token',
                'data' => $e->getMessage()
            ], 403);
        }

       
        return $next($request);
    }
}
