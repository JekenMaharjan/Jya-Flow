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
    // ===============================================================
    // GET: Show Register and Login Views
    // ===============================================================
    public function showRegister()
    {
        return view('auth.register');       // GET: Display register form  
    }

    public function showLogin()
    {
        return view('auth.login');          // GET: Display login form
    }

    
    // ===============================================================
    // POST: Register User
    // ===============================================================
    public function register(RegisterAuthRequest $request)
    {
        // Validate incoming request
        $validatedCredentials = $request->validated();

        // Create the user
        $user = User::create([
            'name' => $validatedCredentials['name'],
            'email' => $validatedCredentials['email'],
            'password' => Hash::make($validatedCredentials['password']),
        ]);

        // Return JSON response
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User have been successfully created!',
                'user' => $user
            ], 201);
        }

        return redirect()->intended('/login');
    }


    // ===============================================================
    // POST: Login User
    // ===============================================================
    public function login(LoginAuthRequest $request)
    {
        // Get validated input
        $credentials = $request->validated();

        // // Attempt Web Session Login
        // if (Auth::attempt($credentials)) {
        //     // Regenerate session ID to prevent Session Fixation attacks
        //     $request->session()->regenerate();

        //     // Redirect to tasks route (Session cookie is sent automatically)
        //     return redirect()->route('tasks')->with('status', 'You have been logged in successfully.');
        // }

        // What laravel does under the hood
        $user = User::where('email', $credentials['email'])->first();

        // Check user exists and verify password hash
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Authenticates user and creates session
            // return true;

            // Log the user in to start the authenticated session
            Auth::login($user);

            // Prevent Session Fixation attacks
            $request->session()->regenerate();

            // Redirect to intended destination
            return redirect()->intended(route('tasks'))->with('status', 'You have been logged in successfully.');
        }

        // Return back if credentials fail
        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ])->onlyInput('email');
    }


    // // ======================== REGISTER ========================
    // public function register(RegisterAuthRequest $request)
    // {
    //     // 1. Validate incoming request
    //     $validatedCredentials = $request->validated();

    //     // 2. Create the user
    //     $user = User::create([
    //         'name' => $validatedCredentials['name'],
    //         'email' => $validatedCredentials['email'],
    //         'password' => Hash::make($validatedCredentials['password']),
    //     ]);

    //     // 3. Create Sanctum token
    //     $token = $user->createToken('task-manager-api')->plainTextToken;

    //     // 4. Return JSON response with 201 Created status
    //     if ($request->wantsJson()) {
    //         return response()->json([
    //             'message' => 'User has been successfully created!',
    //             'data' => [
    //                 'token' => $token,
    //                 'user' => [
    //                     'name' => $user->name,
    //                     'email' => $user->email,
    //                 ]
    //             ]
    //         ], 201);
    //     }

    //     // Redirect to the login route with a success message
    //     return redirect()->route('login')->with('status', 'Registration successful! Please log in.');
    // }


    // // ======================== LOGIN ========================
    // public function login(LoginAuthRequest $request)
    // {
    //     // 1. Validate incoming input
    //     $credentials = $request->validated();

    //     // 2. Find the user as per provided email
    //     $user = User::where('email', $credentials['email'])->first();

    //     // 3. Check password of the user found through email
    //     if (!$user || !Hash::check($credentials['password'], $user->password)) {
    //         return response()->json([
    //             'message' => 'Invalid login credentials'
    //         ], 401);
    //     }

    //     // 4. Issue Sanctum Token
    //     $token = $user->createToken('auth-token')->plainTextToken;

    //     if ($request->wantsJson()) {
    //         return response()->json([
    //         'message' => 'Logged in Successfully!',
    //         'token' => $token,
    //         'user' => $user,
    //         'redirect' => route('api.tasks'),
    //     ], 200);
    //     }

    //     return redirect()->route('tasks')->with('status', 'You have been logged in successfully.');
    // }


    // ===============================================================
    // POST: Logout User
    // ===============================================================
    public function logout(Request $request)
    {
        // if ($request->wantsJson()) {
        //     $request->user()?->currentAccessToken()?->delete();
        //     return response()->json(['message' => 'Logged out!']);
        // }

        // Revoke the token that was used to authenticate the current request
        // $request->user()->currentAccessToken()->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect()->route('login')->with('status', 'You have been logged out successfully.');
    }
}
