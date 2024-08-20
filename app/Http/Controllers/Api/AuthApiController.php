<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for Authentication"
 * )

 */
class AuthApiController extends Controller
{
    private $jwtSecret;
    public function __construct()
    {
        $this->jwtSecret = env('JWT_SECRET');
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","email","first_name","last_name","password"},
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Registration failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Registration failed"),
     *             @OA\Property(property="data", type="string", example="Error message")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="role", type="string", example="user"),
     *                 @OA\Property(property="token", type="string", example="JWT token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid login details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid login details"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Login failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Login failed"),
     *             @OA\Property(property="data", type="string", example="Error message")
     *         )
     *     )
     * )
     */
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
}