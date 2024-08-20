<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use App\Models\User;

class AuthApiController extends Controller
{
    private $jwtSecret;
    public function __construct()
    {
        $this->jwtSecret = env('JWT_SECRET');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|unique:users',
                'email' => 'required|email|unique:users',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'password' => 'required|string|min:8'
            ]);

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => bcrypt($request->password),
               
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => null
            ]);
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function login(Request $request)
    {
        try {


            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            if (!Auth::attempt($request->only(['username', 'password']))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid login details',
                    'data' => null
                ], 401);
            }


            $user = Auth::user();
            
            $payload = [
                'iss' => "seleksi3",
                'sub' => $user->id,
                'role' => $user->role,
                'iat' => time(),
                'exp' => time() + 60 * 60 * 24
            ];

            $token = JWT::encode($payload, $this->jwtSecret, 'HS256');

            $user->auth_token = $token;
            $user->save();


            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'username' => $user->username,
                    'role' => $user->role,
                    'token' => $token
                ]
            ]);
        } catch (\Exception $e) {
        
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
                'data' => $e->getMessage()
            ], 400);
        }
    }
    

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}