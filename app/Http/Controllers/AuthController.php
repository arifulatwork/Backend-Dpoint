<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
            'age'            => ['required', 'integer', 'min:18'],
            'interests'      => ['sometimes', 'array'],
            'interests.*'    => ['string'],
            'location'       => ['nullable', 'string', 'max:255'],
            'avatar'         => ['nullable', 'image', 'max:5120'], // 5MB
        ]);

        // Handle avatar upload (optional)
        $avatarUrl = null;
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $filename = Str::uuid().'.'.$avatarFile->getClientOriginalExtension();
            $path = $avatarFile->storeAs('avatars', $filename, 'public');
            $avatarUrl = Storage::url($path); // e.g. /storage/avatars/...
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'age'        => $request->age,
            'interests'  => $request->interests ?? [],
            'location'   => $request->location,
            'avatar_url' => $avatarUrl,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 201);
    }

    /**
     * Login a user and return token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        /** @var \App\Models\User $user */
        $user  = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }

    /**
     * Logout current token
     */
    public function logout(Request $request)
    {
        $token = $request->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        return response()->json(['message' => 'Logged out']);
    }

    /**
     * Send password reset link email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        try {
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => 'Password reset link sent to your email.'], 200);
            }

            return response()->json(['message' => __($status)], 400);
        } catch (\Throwable $e) {
            Log::error('Forgot-password error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Unable to send reset link at the moment.'], 500);
        }
    }

    /**
     * Reset the user's password (SPA flow)
     *
     * Expects: token, email, password, password_confirmation
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been reset.'], 200);
        }

        return response()->json(['message' => __($status)], 400);
    }

    /**
     * Optional: return current authenticated user
     */
    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
}
