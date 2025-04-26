<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'roles' => 'peminjam',
        ]);

        $user_log = Auth::id();
        Log::create([
            'user_id' => $user_log,
            'action' => 'create',
            'model' => 'User',
            'details' => 'Create users with ID ' . $user->id,
            'message' => 'User created successfully '
        ]);

        return response()->json(
            [
                'message' => 'User created successfully',
                'user' => $user
            ], 201
        );
    }

}
