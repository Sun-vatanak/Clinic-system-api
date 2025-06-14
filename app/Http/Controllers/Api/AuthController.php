<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ]);

    $user = User::with(['profile', 'role'])
        ->where('email', $request->email)
        ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'result' => false,
            'message' => 'The provided credentials are incorrect.'
        ], 401);
    }

    // Change from status to is_active
    if ($user->is_active !== 1) { // Using 1 for active as seen in UserController
        return response()->json([
            'result' => false,
            'message' => 'Your account is inactive. Please contact support.'
        ], 403);
    }

    $user->tokens()->delete();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'result' => true,
        'message' => 'Login successful',
        'data' => [
            'user' => $user,
            'token' => $token
        ]
    ]);
}    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'result' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Get authenticated user details
     */
    public function user(Request $request)
    {
        return response()->json([
            'result' => true,
            'data' => $request->user()->load('role', 'profile'),
        ]);
    }
}
