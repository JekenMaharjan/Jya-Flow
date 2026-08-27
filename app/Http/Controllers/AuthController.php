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
    // GET: Show register page
    public function showRegister()
    {
        return view('auth.register');  
    }

    // GET: Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // POST: User registration
    public function register(RegisterAuthRequest $request)
    {
        // Validate incoming request
        $credentials = $request->validated();

        // Create the user
        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),     // Hasing the password before storing it into database
        ]);

        // Immediately login user after registration
        Auth::login($user);

        // // TEST: Check response
        // return response()->json([
        //     'user_credentials' => $credentials,
        //     'database_user_credentials' => $user
        // ]);

        // Return JSON response
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User created successfully.',
                'user_data' => $user
            ], 201);    // '201' status code for created
        }

        return redirect()
            ->route('login')
            ->with('status', 'User created successfully! Please log in.');
    }

    // POST: User login
    public function login(LoginAuthRequest $request)
    {
        // Get validated input
        $credentials = $request->validated();

        // Find user's credentials stored in database which matches entered credentials email and get the first user's data that matches the email
        $user = User::where('email', $credentials['email'])->first();
        
        // // TEST: Check response
        // return response()->json([
        //     'user_credentials' => $credentials,
        //     'database_user_credentials' => $user
        // ]);

        // Check user exists and verify password hash
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Log the user in to start the authenticated session
            Auth::login($user);

            // Prevent Session Fixation attacks
            $request->session()->regenerate();

            // Return JSON response
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'User logged in successfully.',
                    'user_data' => $user
                ], 200);
            }

            // Redirect to intended destination
            return redirect()
                ->intended(route('tasks'))
                ->with('status', 'User logged in successfully.');
        }

        // // Tip: Easy way to login as all process thats done above is done automatically by Auth::attempt
        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();

        //     return redirect()
        //         ->intended(route('tasks'))
        //         ->with('status', 'User logged in successfully.');
        // }

        // Return back if credentials fail
        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ])->onlyInput('email');     // allows form to repopulate the email field
    }

    // POST: Logout User
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'User logged out successfully.');
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
}
