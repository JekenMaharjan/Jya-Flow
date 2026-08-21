<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ======================== SHOW VIEWS ========================
    public function showRegister()
    {
        return view('auth.register');       // GET: Display register form  
    }

    public function showLogin()
    {
        return view('auth.login');          // GET: Display login form
    }


    // ======================== REGISTER ========================
    public function register(RegisterAuthRequest $request)
    {
        // 1. Validate incoming request
        $validatedCredentials = $request->validated();

        // 2. Create the user
        $user = User::create([
            'name' => $validatedCredentials['name'],
            'email' => $validatedCredentials['email'],
            'password' => Hash::make($validatedCredentials['password']),
        ]);

        // 3. Create Sanctum token
        $token = $user->createToken('task-manager-api')->plainTextToken;

        // 4. Return JSON response with 201 Created status
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User has been successfully created!',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ]
                ]
            ], 201);
        }

        // Redirect to the login route with a success message
        return redirect()->route('login')->with('status', 'Registration successful! Please log in.');
    }


    // ======================== LOGIN ========================
    // public function login(LoginAuthRequest $request)
    // {
    //     // 1. Validate incoming input
    //     $credentials = $request->validated();

    //     // 2. Attempt authentication
    //     if (!Auth::attempt($credentials)) {
    //         return response()->json([
    //             'message' => 'Invalid credentials.'
    //         ], 401);
    //     }

    //     // 3. Issue Sanctum API Token
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();
    //     $token = $user->createToken('task-manager-api')->plainTextToken;

    //     // 4. Return JSON response
    //     if ($request->wantsJson()){
    //         return response()->json([
    //             'message' => 'Logged in successfully',
    //             'token'   => $token,
    //             'user'    => $user,
    //         ], 200);
    //     }

    //     // Redirect back to original destination (or fallback to /dashboard)
    //     return redirect()->route('tasks')->with('status', 'You have been logged in successfully.');
    // }

    public function login(LoginAuthRequest $request)
    {
        // 1. Validate request
        $credentials = $request->validated();

        // 2. Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // 2. Prevent Session Fixation Attacks
            $request->session()->regenerate();
            
            if ($request->wantsJson()) {
                return response()->json([
                'message' => 'Logged in successfully',
                'user' => Auth::user()
            ], 200);
            }

            // 3. Redirect back to original destination (or fallback to /dashboard)
            return redirect()->intended(route('tasks'))->with('status', 'You have been logged in successfully.');
        }

        // 4. On failure: Redirect BACK to form with validation errors and old email input
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // ======================== LOGOUT ========================
    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            $request->user()?->currentAccessToken()?->delete();
            return response()->json(['message' => 'Logged out!']);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out successfully.');
    }
}
