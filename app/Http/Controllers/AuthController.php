<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class AuthController extends Controller
{
    // ----------- REGISTER -----------
    public function showRegister()
    {
        return view('auth.register');       // GET: Display register form  
    }

    public function register(Request $request)
    {
        // 1. Validate incoming request
        $credentials = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        // 2. Create the user
        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);

        // 3. Log the user in and redirect
        Auth::login($user);

        // 4. Return JSON response
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User have been successfully created!',
                'user' => $user
            ], 201);
        }

        return redirect()->intended('/login');
    }

    // ----------- LOGIN -----------
    public function showLogin()
    {
        return view('auth.login');          // GET: Display login form
    }

    public function login(Request $request)
    {
        // 1. Validate request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

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
            return redirect()->intended(route('dashboard'))->with('status', 'You have been logged in successfully.');
        }

        // 4. On failure: Redirect BACK to form with validation errors and old email input
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // ----------- LOGOUT -----------
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out successfully.');
    }
}
