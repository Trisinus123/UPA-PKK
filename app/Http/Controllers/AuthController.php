<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\PerusahaanProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Show registration type selection
    public function showRegisterSelection()
    {
        return view('auth.register');
    }
    
    // Show mahasiswa registration form
    public function showRegisterMahasiswa()
    {
        return view('auth.register_mahasiswa');
    }
    
    // Show perusahaan registration form
    public function showRegisterPerusahaan()
    {
        return view('auth.register_perusahaan');
    }
    
    // Register mahasiswa
    public function registerMahasiswa(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15',
            'nim' => 'required|string|unique:mahasiswa_profiles,nim',
        ]);
        
        DB::beginTransaction();
        
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
                'role' => 'mahasiswa',
            ]);
            
            MahasiswaProfile::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
            ]);
            
            DB::commit();
            
            return redirect()->route('login')->with('success', 'Registrasi mahasiswa berhasil. Silahkan login.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['msg' => 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage()]);
        }
    }
    
    // Register perusahaan
    public function registerPerusahaan(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15',

        ]);
        
        DB::beginTransaction();
        
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
                'role' => 'perusahaan',
            ]);
            
            PerusahaanProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
            ]);
            
            DB::commit();
            
            return redirect()->route('login')->with('success', 'Registrasi perusahaan berhasil. Silahkan login.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['msg' => 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage()]);
        }
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
