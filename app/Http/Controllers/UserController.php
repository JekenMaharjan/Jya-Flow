<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUsers(User $user)
    {
        // $usernames = User::latest()->pluck('name');

        // $username = User::where('name', 'Jeken Maharjan')->firstOrFail();

        // return $username;

        return response()->json([
            'name' => $user->name,
        ]);
    }
}
