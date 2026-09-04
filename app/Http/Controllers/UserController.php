<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUsers()
    {
        $users = User::get(['name', 'email']);

        return view('users.users', compact('users'));
    }
}
