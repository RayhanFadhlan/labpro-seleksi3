<?php

namespace App\Http\Middleware;

use App\Strategies\JwtBearerTokenValidationStrategy;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Strategies\TokenValidationStrategy;

class AdminMidddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private $tokenValidationStrategy;

    public function __construct(JwtBearerTokenValidationStrategy $tokenValidationStrategy)
    {
        $this->tokenValidationStrategy = $tokenValidationStrategy;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $validationResult = $this->tokenValidationStrategy->validate($request);

        if ($validationResult['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $validationResult['message'],
                'data' => null
            ], $validationResult['code']);
        }

        $decoded = $validationResult['decoded'];
        if ($decoded->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Admins only',
                'data' => null
            ], 403);
        }

        return $next($request);
    }
}
