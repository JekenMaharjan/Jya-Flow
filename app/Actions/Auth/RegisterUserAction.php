<?php

namespace App\Actions\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterUserAction
{
    use AsAction;

    public function handle(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Dispatch the event
        UserRegistered::dispatch($user);

        return $user;
    }
}
