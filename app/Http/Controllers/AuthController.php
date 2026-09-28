<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected FirebaseAuth $firebaseAuth)
    {
        //
    }

    // GET: Register page
    public function showRegister()
    {
        return view('auth.register');  
    }

    // GET: Login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // POST: User registration
    public function register(RegisterAuthRequest $request)
    {
        // Pass validated data into Action
        $user = RegisterUserAction::run($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "User registered successfully! Please log in.",
                'user' => $user,
            ], 201);
        }
        
        return redirect()
            ->route('login')
            ->with('success', 'User registered successfully! Please log in.');
    }

    // POST: User login
    public function login(LoginAuthRequest $request)
    {
        // Verify credentials via Action (throws error if invalid)
        $user = LoginUserAction::run($request->validated());

        // Start session & prevent Session Fixation attacks
        Auth::login($user);
        
        // Safely manage sessions ONLY if the route has session middleware
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "User logged in successfully!",
                'user' => $user,
            ], 200);
        }

        return redirect()
            ->intended(route('tasks.index'))
            ->with('success', 'User logged in successfully.');
    }

    // POST: User logout
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Safely revoke Firebase tokens if user exists
        if ($user?->firebase_uid) {
            try {
                $this->firebaseAuth->revokeRefreshTokens($user->firebase_uid);
            } catch (\Throwable $e) {
                logger()->error("Failed to revoke Firebase tokens: " . $e->getMessage());
            }
        }

        // Clear Laravel auth session and invalidate CSRF
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User logged out successfully.'
            ], 200);
        }

        return redirect()
            ->route('login')
            ->with('success', 'User logged out successfully.');
    }
}
