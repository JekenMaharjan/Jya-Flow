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

        return redirect()
            ->route('login')
            ->with('success', 'User created successfully! Please log in.');
    }

    // POST: User login
    public function login(LoginAuthRequest $request)
    {
        // Verify credentials via Action (throws error if invalid)
        $user = LoginUserAction::run($request->validated());

        // Start session & prevent Session Fixation attacks
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->intended(route('tasks.index'))
            ->with('success', 'User logged in successfully.');
    }

    // POST: User logout
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->firebase_uid) {
            try {
                $this->firebaseAuth->revokeRefreshTokens($user->firebase_uid);
            } catch (\Throwable $e) {
                logger()->error("Failed to revoke Firebase tokens: " . $e->getMessage());
            }
        }

        Auth::logout();

        if ($user && $user->firebase_uid) {
            $this->firebaseAuth->revokeRefreshTokens($user->firebase_uid);
        }

        // Invalidate web session & regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'User logged out successfully.');
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
    //         'redirect' => route('api.tasks.index'),
    //     ], 200);
    //     }

    //     return redirect()->route('tasks.index')->with('status', 'You have been logged in successfully.');
    // }
}
