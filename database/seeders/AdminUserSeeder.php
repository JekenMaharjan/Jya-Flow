<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Throwable;

class AdminUserSeeder extends Seeder
{
    // Inject Kreait Firebase Auth SDK
    public function __construct(protected FirebaseAuth $firebaseAuth)
    {

    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = 'ADMIN';
        $email = 'admin@gmail.com';
        $password = 'admin@123';
        $role = UserRole::ADMIN->value;

        // Get existing Firebase user or create a new one
        try {
            $firebaseUser = $this->firebaseAuth->getUserByEmail($email);
        } catch (Throwable $e) {
            $firebaseUser = $this->firebaseAuth->createUser([
                'displayName' => $name,
                'email' => $email,
                'password' => $password,
            ]);

            // Set role as a Custom User Claim in Firebase
            $this->firebaseAuth->setCustomUserClaims($firebaseUser->uid, ['role' => $role]);
        }

        // Save user locally with the real Firebase UID
        $user = new User;

        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->firebase_uid = $firebaseUser->uid;
        $user->role = $role;

        $user->save();
    }
}
