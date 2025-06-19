<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = [
            'mahasiswa' => 'Mahasiswa',
            'perusahaan' => 'Perusahaan',
            'admin' => 'Admin'
        ];
        return view('users.create', compact('roles'));
    }


    public function store(Request $request)
{
    try {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:mahasiswa,perusahaan,admin',
            'nim' => 'nullable|string|max:20|unique:mahasiswa_profiles,nim',
            'no_hp' => 'nullable|string|max:20',
            'alamat_perusahaan' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            // 'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Mulai database transaction
        DB::beginTransaction();

        try {
            // Create user
            $userData = $validated;
            unset($userData['nim'], $userData['alamat_perusahaan'], $userData['website'], $userData['deskripsi'], $userData['foto']);
            
            $user = User::create($userData);

            // Handle file upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                try {
                    $file = $request->file('foto');
                    $filename = Str::uuid() . '.' . $file->extension();
                    $fotoPath = $file->storeAs('foto_perusahaan', $filename, 'public');
                    Log::info('File uploaded successfully: ' . $fotoPath);
                } catch (\Exception $e) {
                    Log::error('File upload failed: ' . $e->getMessage());
                    throw new \Exception('Failed to upload file');
                }
            }

            // Create profile berdasarkan role
            if ($request->role === 'perusahaan') {
                $user->perusahaanProfile()->create([
                    'alamat_perusahaan' => $request->alamat_perusahaan,
                    'website' => $request->website,
                    'deskripsi' => $request->deskripsi,
                    'foto' => $fotoPath
                ]);
                Log::info('Perusahaan profile created for user: ' . $user->id);
            } elseif ($request->role === 'mahasiswa' && $request->nim) {
                $user->mahasiswaProfile()->create([
                    'nim' => $request->nim,
                    'no_hp' => $request->no_hp
                ]);
                Log::info('Mahasiswa profile created for user: ' . $user->id);
            }

            // Commit transaction jika semua berhasil
            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            DB::rollBack();
            Log::error('User creation failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create user. Error: ' . $e->getMessage());
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation error: ' . json_encode($e->errors()));
        return back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        Log::error('System error: ' . $e->getMessage());
        return back()
            ->withInput()
            ->with('error', 'An unexpected error occurred. Please try again.');
    }
}

    /**
     * Display the specified resource.
     */
public function show(User $user)
{
    return view('users.show', [
        'user' => $user,
        'roleBadgeClass' => $this->getRoleBadgeClass($user->role)
    ]);
}

private function getRoleBadgeClass($role)
{
    return match($role) {
        'admin' => 'bg-danger',
        'perusahaan' => 'bg-primary',
        'mahasiswa' => 'bg-success',
        default => 'bg-secondary'
    };
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = [
            'mahasiswa' => 'Mahasiswa',
            'perusahaan' => 'Perusahaan',
            'admin' => 'Admin'
        ];
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:mahasiswa,perusahaan,admin',
        'nim' => 'nullable|string|max:20|unique:mahasiswa_profiles,nim,' . ($user->mahasiswaProfile ? $user->mahasiswaProfile->id : 'NULL'),
        'no_hp' => 'nullable|string|max:20',
        'alamat_perusahaan' => 'nullable|string|max:255',
        'website' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string',
    ]);

    // Handle password update
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    // Update user data (excluding profile-specific fields)
    $userData = $validated;
    unset($userData['nim'], $userData['alamat_perusahaan'], $userData['website'], $userData['deskripsi']);
    $user->update($userData);

    // Handle profile updates based on role
    switch ($request->role) {
        case 'mahasiswa':
            // Update or create mahasiswa profile
            if ($request->nim) {
                $mahasiswaData = ['nim' => $request->nim];
                
                if ($user->mahasiswaProfile) {
                    $user->mahasiswaProfile()->update($mahasiswaData);
                } else {
                    $user->mahasiswaProfile()->create($mahasiswaData);
                }
            }
            
            // Remove perusahaan profile if exists
            if ($user->perusahaanProfile) {
                $user->perusahaanProfile()->delete();
            }
            break;

        case 'perusahaan':
            // Update or create perusahaan profile
            $perusahaanData = [
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'website' => $request->website,
                'deskripsi' => $request->deskripsi,
            ];

            if ($user->perusahaanProfile) {
                $user->perusahaanProfile()->update($perusahaanData);
            } else {
                $user->perusahaanProfile()->create($perusahaanData);
            }
            
            // Remove mahasiswa profile if exists
            if ($user->mahasiswaProfile) {
                $user->mahasiswaProfile()->delete();
            }
            break;

        case 'admin':
            // Remove both profiles if exists
            if ($user->mahasiswaProfile) {
                $user->mahasiswaProfile()->delete();
            }
            if ($user->perusahaanProfile) {
                $user->perusahaanProfile()->delete();
            }
            break;
    }

    return redirect()->route('users.index')
        ->with('success', 'User updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
