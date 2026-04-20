<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Api\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
// NOTE: Import RateLimiter for brute-force protection
use Illuminate\Support\Facades\RateLimiter;

/**
 * @group Authentication
 * API for user login and logout.
 */
class IndexController extends Controller
{
    /**
     * Login
     * Authenticate user and return a Bearer token.
     * * @unauthenticated
     * @bodyParam email string required User email. Example: admin@aile.tv
     * @bodyParam password string required User password. Example: password
     * @bodyParam device_name string Name of the device (e.g., iPhone, Chrome).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);

        $deviceName = $request->input('device_name') ?? $request->userAgent() ?? 'Unknown Device';

        // 1. PROTECTION: Limit login attempts (e.g., 5 attempts per minute per IP)
        if (RateLimiter::tooManyAttempts('login:' . $request->ip(), 5)) {
            $seconds = RateLimiter::availableIn('login:' . $request->ip());
            return response()->json([
                'message' => "Too many login attempts. Please try again in $seconds seconds."
            ], 429);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Record a failed attempt
            RateLimiter::hit('login:' . $request->ip());

            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // Clear attempt counter on successful login
        RateLimiter::clear('login:' . $request->ip());

        // 2. CONVENIENCE: Create a new access token
        $token = $user->createToken($deviceName)->plainTextToken;

        // 3. RELATION: Return name and role for frontend UI logic (e.g., hiding/showing admin buttons)
        return response()->json([
            'token' => $token,
            'user'  => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Log the user out of the application.
     * Revoke the current access token.
     * * @authenticated
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // 1. Get the currently authenticated user
        $user = $request->user();

        // 2. Log the activity using Spatie Activity Log
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('User logged out of the system');

        // 3. Physically remove the current access token from the database
        $user->currentAccessToken()->delete();

        // 4. Return a successful JSON response
        return response()->json([
            'status' => 'success',
            'message' => 'Session terminated successfully'
        ], 200);
    }
}
