<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required']
        ]);

        User::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully'
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ]);
        }

        $user = Auth::user();

        $token = $user->createToken('myToken')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User logged in',
            'token' => $token
        ]);
    }

    public function profile() {
        $user = Auth::user();
        
        return response()->json([
            'status' => true,
            'message' => 'User profile data',
            'user' => $user
        ]);
    }

    public function logout(){
        Auth::logout();

        return response()->json([
            'status' => true,
            'message' => 'User logout successfully'
        ]);
    }
}
