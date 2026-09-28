<?php

namespace App\Actions\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterUserAction
{
    use AsAction;

    public function __construct(
        protected FirebaseAuth $firebaseAuth
    ) {}

    public function handle(array $data): User
    {
        // Create User in Firebase
        $firebaseUser = $this->firebaseAuth->createUser([
            'displayName' => $data['name'],
            'email'       => $data['email'],
            'password'    => $data['password'],
        ]);

        try {
            // Set Firebase Custom Claims
            $this->firebaseAuth->setCustomUserClaims($firebaseUser->uid, [
                'role' => $data['role']
            ]);

            // Persist in Database within a Transaction
            $user = DB::transaction(fn () => User::create([
                'name'         => $data['name'],
                'email'        => $data['email'],
                'password'     => Hash::make($data['password']),
                'firebase_uid' => $firebaseUser->uid,
                'role'         => $data['role'],
            ]));

            // Dispatch Event
            UserRegistered::dispatch($user);

            return $user;

        } catch (Throwable $e) {
            // Rollback Firebase user if claims, DB creation, or event fails
            $this->firebaseAuth->deleteUser($firebaseUser->uid);
            
            throw $e;
        }
    }
}