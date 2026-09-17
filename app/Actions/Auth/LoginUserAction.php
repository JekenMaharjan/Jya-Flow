<?php

namespace App\Actions\Auth;

use App\Models\User;
use Exception;
use Illuminate\Validation\ValidationException;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class LoginUserAction
{
    use AsAction;

    public function __construct()
    {
        $path = storage_path('app/firebase/firebase_credentials.json');

        if (!file_exists($path)) {
            throw new Exception("Firebase credentials file missing at: {$path}");
        }
    }

    public function handle(array $credentials): User
    {
        try {
            // Authenticate credentials against Firebase Atuh Service
            $signInResult = Firebase::auth()->signInWithEmailAndPassword(
                $credentials['email'], 
                $credentials['password']
            );

            // Extract the Firebase Unique Identifier (UID)
            $firebaseUid = $signInResult->firebaseUserId();
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.']
            ]);
        }

        // Find the corresponding user record in local db
        $user = User::where('email', $credentials['email'])
            ->orWhere('firebase_uid', $firebaseUid)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Account exists in Firebase but not in the local db.']
            ]);
        }

        // Backfill/Sync firebase_uid if it wasn't saved previously
        if (is_null($user->firebase_uid)) {
            $user->update(['firebase_uid' => $firebaseUid]);
        }

        return $user;
    }
}
