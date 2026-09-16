<?php

namespace App\Actions\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterUserAction
{
    use AsAction;

    public function __construct()
    {
        $path = storage_path('app/firebase/firebase_credentials.json');

        if (!file_exists($path)) {
            throw new Exception("Firebase credentials file missing at: {$path}");
        }
    }

    public function handle(array $data): User
    {
        // Register user in firebase
        $firebaseUser = Firebase::auth()->createUser([
            'displayName' => $data['name'],  // Firebase uses 'displayName' for name
            'email' => $data['email'],
            'password' => $data['password'],  // Pass plain test as firebase uses own secure password hashing called 'Scrypt' on its servers
        ]);

        try {
            // Register user in local database
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'firebase_uid' => $firebaseUser->uid,
            ]);
        } catch (Exception $e) {
            // Rollback: Delete user from Firebase if local DB fails
            Firebase::auth()->deleteUser($firebaseUser->uid);
            throw $e;
        }

        // Dispatch post-registration event
        UserRegistered::dispatch($user);

        return $user;
    }
}
