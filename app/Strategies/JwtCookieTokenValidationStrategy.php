<?php

namespace App\Strategies;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtCookieTokenValidationStrategy implements TokenValidationStrategy
{
    private $jwtSecret;

    public function __construct()
    {
        $this->jwtSecret = env('JWT_SECRET');
    }

    public function validate(Request $request) : array
    {
        $token = $request->cookie('auth_token');
        if (!$token) {
            return [
                'status' => 'error',
                'message' => 'Token not provided',
                'code' => 401
            ];
        }

        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return [
                'status' => 'success',
                'decoded' => $decoded
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Invalid token',
                'code' => 403
            ];
        }
    }
}