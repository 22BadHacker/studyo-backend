<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Register API (name, email, password, confirm_pass)
    public function register(Request $request) {
        
        $request->validate([
            'username'  => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            // 'role'     => 'in:user,artist,admin',
        ]);


        $user = User::create([
            'username'  => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            // 'role'     => $request->role ?? 'user',
        ]);

        $token = $user->createToken('studyo_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
            'message'=> 'User registered successfully'
        ], 201);
    }

    // Login API (email, password)
    public function login(Request $request) {
        
       $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password.', 'status' => false], 401);
        }

        $token = $user->createToken('studyo_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
            'message'=> 'User logged in successfully'
        ]);
    }

    // profile API
    public function profile(Request $request) {

        return response()->json($request->user());
    }

    // Logout API
    public function logout(Request $request) {
        
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }



    public function googleAuth(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Create user if doesn't exist
            $user = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'image' => $request->profile_image,
                'role' => 'user',
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)), // dummy password
            ]);
        }

        Auth::login($user);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
        ]);
    }

}
