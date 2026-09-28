<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class LoginUserAction
{
    use AsAction;

    public function __construct(
        protected FirebaseAuth $firebaseAuth
    ) {}

    public function handle(array $credentials): User
    {
        // Authenticate with Firebase
        try {
            $signInResult = $this->firebaseAuth->signInWithEmailAndPassword(
                $credentials['email'],
                $credentials['password']
            );
            $firebaseUid = $signInResult->firebaseUserId();
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.']
            ]);
        }

        // Fetch local user by email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Account exists in Firebase but not in the local database.']
            ]);
        }

        // Backfill firebase_uid if missing
        if (!$user->firebase_uid) {
            $user->update(['firebase_uid' => $firebaseUid]);
        }

        return $user;
    }
}