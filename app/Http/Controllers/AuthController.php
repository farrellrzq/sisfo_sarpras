<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return redirect()->route('login');

    }

    public function loginPage() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard()->attempt($credentials)) {
            $request->session()->regenerate();

            if(Auth::user()){
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::create([
            'user_id' => $request->user()->id,
            'action' => 'logout',
            'model' => 'User',
            'details' => 'User logged out',
        ]);

        return redirect()->route('login');
    }
}
