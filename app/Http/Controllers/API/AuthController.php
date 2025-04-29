<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),

        ]);


        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);

    }

    //create login method with api token sanctum
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::create([
                'user_id' => $user->id,
                'action' => 'login',
                'model' => 'User',
                'details' => 'User logged in',
            ]);

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'message' => 'Login failed',
            ], 401);
        }
    }


    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        Log::create([
            'user_id' => $request->user()->id,
            'action' => 'logout',
            'model' => 'User',
            'details' => 'User logged out',
        ]);

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
