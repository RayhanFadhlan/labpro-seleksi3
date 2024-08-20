<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserApiController extends Controller
{
    public function self(Request $request)
    {
        try {

            $bearerToken = $request->bearerToken();
            $user = User::where('auth_token', $bearerToken)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => null,
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User data',
                'data' => [
                    'username' => $user->username,
                    'token' => $bearerToken
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 400);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = $request->input('q');

            $users = User::where('username', 'like', '%' . $query . '%')->get();



            $usersData = [];
            foreach ($users as $user) {
                $usersData[] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'balance' => $user->balance,
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Users retrieved successfully',
                'data' => $usersData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User retrieved successfully',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'balance' => $user->balance,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function topup(Request $request, $id)
    {
        try {
            $request->validate([
                'increment' => 'required|integer',
            ]);
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => null,
                ], 400);
            }

            $user->balance += $request->increment;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Balance updated successfully',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'balance' => $user->balance,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => null,
                ], 404);
            }
            $userData = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'balance' => $user->balance,
            ];

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
                'data' => $userData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}