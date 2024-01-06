<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ["Wrong input. Please try again."],
            ]);
        }

        // Fetch username and role from the $user object
        $username = $user->username; // Assuming the username field is 'username'
        $role = $user->role; // Replace 'role' with the actual field name for user role

        $response = [
            'username' => $username,
            'role'     => $role,
            'token'    => $user->createToken($request->username)->plainTextToken,
        ];

        return $response;
    }
}
