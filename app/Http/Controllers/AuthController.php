<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    // Menampilkan pilihan jenis pendaftaran
    public function showRegisterSelection()
    {
        return view('auth.register');
    }

    // Menampilkan form pendaftaran untuk mahasiswa
    public function showRegisterMahasiswa()
    {
        return view('auth.register_mahasiswa'); // Pastikan view ini tersedia
    }

    // Menampilkan form pendaftaran untuk perusahaan
    public function showRegisterPerusahaan()
    {
        return view('auth.register_perusahaan');
    }

    // Proses pendaftaran akun mahasiswa
    public function registerMahasiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15|regex:/^[0-9]+$/',
            'nim' => 'required|string|unique:mahasiswa_profiles,nim',
        ]);

        DB::beginTransaction();

        try {
            // Format nomor HP agar menggunakan kode negara Indonesia (+62)
            $no_hp = $this->formatPhoneNumber($request->no_hp);

            // Simpan data user ke tabel users
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $no_hp,
                'role' => 'mahasiswa',
            ]);

            // Simpan data tambahan ke tabel mahasiswa_profiles
            MahasiswaProfile::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['msg' => 'Gagal registrasi: ' . $e->getMessage()]);
        }
    }

    // Fungsi untuk memformat nomor HP menjadi +62
    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '+62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '62')) {
            return '+' . $phone;
        }

        return '+62' . $phone;
    }

    // Proses pendaftaran akun perusahaan
    public function registerPerusahaan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15',
            'alamat_perusahaan' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Simpan data user perusahaan
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
                'role' => 'perusahaan',
            ]);

            // Simpan profil perusahaan
            PerusahaanProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'alamat_perusahaan' => $request->alamat_perusahaan,
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi perusahaan berhasil. Silakan login.');
        } catch (\Exception $e) {
            DB::rollback();

            // Simpan kesalahan ke log Laravel
            Log::error('Gagal registrasi perusahaan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->withErrors(['msg' => 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage()]);
        }
    }

    // Proses login untuk semua jenis pengguna (mahasiswa, perusahaan, admin)
    public function login(Request $request)






    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Tentukan apakah login menggunakan NIM (angka) atau email
        $isNumeric = is_numeric($request->login);
        $loginType = $isNumeric ? 'nim' : 'email';

        if ($isNumeric) {
            // Login menggunakan NIM (untuk mahasiswa)
            $mahasiswaProfile = MahasiswaProfile::where('nim', $request->login)->first();

            if (!$mahasiswaProfile) {
                return back()->withErrors(['login' => 'NIM tidak ditemukan']);
            }

            $user = User::find($mahasiswaProfile->user_id);

            if (!$user || $user->role !== 'mahasiswa') {
                return back()->withErrors(['login' => 'Akun bukan termasuk mahasiswa']);
            }
        } else {
            // Login menggunakan email (untuk perusahaan dan admin)
            $user = User::where('email', $request->login)->first();

            if (!$user) {
                return back()->withErrors(['login' => 'Email tidak ditemukan']);
            }

            if ($user->role === 'mahasiswa') {
                return back()->withErrors(['login' => 'Mahasiswa harus login menggunakan NIM']);
            }
        }

        // Verifikasi password
        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->expectsJson()) {
                throw ValidationException::withMessages(['login' => 'Password salah']);
            }
            return back()->withErrors(['login' => 'Password salah']);
        }

        // Jika request dari API
        if ($request->expectsJson()) {
            // Berikan token untuk API
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token, 'role' => $user->role]);
        } else {
            // Login via web menggunakan Laravel Auth
            Auth::login($user);

            // Arahkan sesuai role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else if ($user->role === 'perusahaan') {
                return redirect()->route('perusahaan.dashboard');
            } else {
                return redirect()->route('jobs.browse');
            }
        }
    }

    // Logout
    public function logout(Request $request) {
        // Hapus semua token (jika API)
        $request->user()->tokens()->delete();

        // Logout dari sesi web
        Auth::logout();

        // Hancurkan sesi
        $request->session()->invalidate();

        // Buat ulang token CSRF
        $request->session()->regenerateToken();

        // Jika dari API
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Berhasil logout']);
        } else {
            return redirect()->route('login');
        }
    }
}
