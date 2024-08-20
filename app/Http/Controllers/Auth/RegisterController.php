<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        
        try {


            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:users',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                $errorMessage = $validator->errors()->first();
                return redirect()->route('error', ['message' => $errorMessage]);
            }

            $user = User::create([
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,

                'password' => bcrypt($request->password),
            ]);
            return redirect()->route('home')->with('success', '');

        } catch (\Exception $e) {
            return redirect()->route('error', ['message' => $e->getMessage()]);
        }
    }
}