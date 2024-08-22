<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Firebase\JWT\JWT;


class LoginController extends Controller
{
    private $jwtSecret;
    public function __construct()
    {
        $this->jwtSecret = env('JWT_SECRET');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                $errorMessage = $validator->errors()->first();
                return redirect()->route('error', ['message' => $errorMessage]);
            }

            if (!Auth::attempt($request->only(['username', 'password']))) {
                return redirect()->route('error', ['message' => 'Invalid username or password']);
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

            $cookie = cookie('auth_token', $token, 60 * 24);
            return redirect()->route('browse')->cookie($cookie);

        } catch (\Exception $e) {
            return redirect()->route('error', ['message' => $e->getMessage()]);
        }
    }
    public function logout(Request $request)
    {
        Cookie::queue(Cookie::forget('auth_token'));

        
        return redirect()->route('login')->with('message', 'You have been logged out.');
    }
}