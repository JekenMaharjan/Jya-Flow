<?php

namespace App\Actions\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterUserAction
{
    use AsAction;

    // Inject the Contract via Constructor
    public function __construct(protected FirebaseAuth $firebaseAuth)
    {
        //
    }

    public function handle(array $data): User
    {
        // Create in Firebase
        $firebaseUser = $this->firebaseAuth->createUser([
            'displayName' => $data['name'],
            'email'       => $data['email'],
            'password'    => $data['password'],
        ]);

        // Set role in Firebase Custom User Claims
        $this->firebaseAuth->setCustomUserClaims(
            $firebaseUser->uid,
            ['role' => $data['role']]
        );

        try {
            // Persist in Local Database
            $user = User::create([
                'name'         => $data['name'],
                'email'        => $data['email'],
                'password'     => Hash::make($data['password']),
                'firebase_uid' => $firebaseUser->uid,
                'role'         => $data['role'],
            ]);
        } catch (Exception $e) {
            // Rollback Firebase user if local creation fails
            $this->firebaseAuth->deleteUser($firebaseUser->uid);
            throw $e;
        }

        UserRegistered::dispatch($user);

        return $user;
    }
}