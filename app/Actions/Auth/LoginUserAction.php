<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class LoginUserAction
{
    use AsAction;

    public function handle(array $credentials): User
    {
        // Check first email that matches credential's email
        $user = User::where('email', $credentials['email'])->first();

        // Check user exists and verify password hash
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.']
            ]);
        }

        return $user;
    }
}
