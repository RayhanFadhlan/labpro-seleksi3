<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

/**

 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for Users"
 * )
 */
class UserApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/self",
     *     tags={"Users"},
     *     summary="Get authenticated user data",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User data",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User data"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="token", type="string", example="JWT token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Search query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Users retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="username", type="string", example="johndoe"),
     *                     @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                     @OA\Property(property="balance", type="integer", example=100)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error retrieving users",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error message"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="balance", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/users/{id}/topup",
     *     tags={"Users"},
     *     summary="Top up user balance",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"increment"},
     *             @OA\Property(property="increment", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Balance updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Balance updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="balance", type="integer", example=200)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User deleted successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="balance", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
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