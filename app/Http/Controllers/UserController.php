<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUsers(Request $request)
    {
        $users = User::get(['name', 'email']);

        if ($request->wantsJson()) {
            if ($users->isEmpty()) {
                return response()->json([
                    'message' => 'No Users available'
                ], 404);
            }

            return response()->json([
                'message' => 'List of Registered Users',
                'users' => $users,
            ], 200);
        }

        return view('users.users', compact('users'));
    }
}
