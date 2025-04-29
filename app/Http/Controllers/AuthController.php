<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    // Register
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:mahasiswa,perusahaan,admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User registered'], 201);
    }

    // Login
    public function login(Request $request) {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Check if login is email or NIM
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';
        
        // Find user by email or NIM
        $user = User::where($loginField, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->expectsJson()) {
                throw ValidationException::withMessages(['login' => 'Invalid credentials']);
            }
            return back()->withErrors(['login' => 'Invalid credentials']);
        }

        // Check if this is an API request
        if ($request->expectsJson()) {
            // For API requests, create and return token
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token, 'role' => $user->role]);
        } else {
            // For web requests, use Laravel's built-in authentication
            Auth::login($user);
            
            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else if ($user->role === 'perusahaan') {
                return redirect()->route('perusahaan.dashboard');
            } else {
                return redirect()->route('jobs.browse'); // Changed from mahasiswa.jobs to jobs.browse
            }
        }
    }

    // Logout
    public function logout(Request $request) {
        // Revoke all tokens
        $request->user()->tokens()->delete();
        
        // Log the user out of the session
        Auth::logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate the CSRF token
        $request->session()->regenerateToken();
        
        // Check if it's an API request
        if ($request->expectsJson()) {
            // For API requests, return JSON response
            return response()->json(['message' => 'Logged out']);
        } else {
            // For web requests, redirect to login page
            return redirect()->route('login');
        }
    }
}

