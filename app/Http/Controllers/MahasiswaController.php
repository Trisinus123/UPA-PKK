<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:mahasiswa']);
    }

    public function index()
    {
        return view('mahasiswa.dashboard');
    }

    public function profile()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        // Membuat profil jika belum ada
        if (!$mahasiswa) {
            $mahasiswa = MahasiswaProfile::create([
                'user_id' => $user->id,
                'nim' => $user->nim ?? 'N/A' // Gunakan NIM dari model user jika tersedia
            ]);
        } else if ($mahasiswa->nim == 'N/A' && $user->nim) {
            // Perbarui NIM jika user memiliki tapi profil belum
            $mahasiswa->nim = $user->nim;
            $mahasiswa->save();
        }

        return view('mahasiswa.profile', compact('mahasiswa'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'cv' => 'nullable|mimes:pdf|max:10240', // Diubah dari 2048 ke 10240 (10MB)
        ]);

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $path = $file->store('cv', 'public');

            // Ambil profil yang ada atau buat baru
            $mahasiswa = MahasiswaProfile::where('user_id', $user->id)->first();

            if ($mahasiswa) {
                $mahasiswa->cv = $path;
                $mahasiswa->save();
            } else {
                MahasiswaProfile::create([
                    'user_id' => $user->id,
                    'nim' => $user->nim ?? 'N/A',
                    'cv' => $path
                ]);
            }

            return back()->with('success', 'CV berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload CV.');
    }

    /**
     * Memeriksa apakah user yang login sudah mengupload CV
     *
     * @return bool
     */
    public function hasCV()
    {
        $user = Auth::user();
        $mahasiswa = MahasiswaProfile::where('user_id', $user->id)->first();

        return $mahasiswa && $mahasiswa->cv && $mahasiswa->cv != '';
    }

    /**
     * Proses lamaran pekerjaan dengan opsi upload CV
     *
     * @param Request $request
     * @param int $jobId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyJob(Request $request, $jobId)
    {
        $user = Auth::user();
        $cvUpdated = false;
        $resumePath = null;

        // Cek apakah user sudah memiliki CV
        $mahasiswa = MahasiswaProfile::where('user_id', $user->id)->first();
        $hasExistingCV = $mahasiswa && $mahasiswa->cv && $mahasiswa->cv != '';

        // Handle upload CV baru jika disediakan
        if ($request->hasFile('cv')) {
            $request->validate([
                'cv' => 'required|mimes:pdf|max:10240', // Diubah dari 2048 ke 10240 (10MB)
            ]);

            $file = $request->file('cv');
            $resumePath = $file->store('cv', 'public');

            // Update CV user
            if ($mahasiswa) {
                $mahasiswa->cv = $resumePath;
                $mahasiswa->save();
            } else {
                $mahasiswa = MahasiswaProfile::create([
                    'user_id' => $user->id,
                    'nim' => $user->nim ?? 'N/A',
                    'cv' => $resumePath
                ]);
            }
            $cvUpdated = true;
        }
        // Jika use_existing_cv dipilih dan user memiliki CV, gunakan CV yang ada
        else if ($request->has('use_existing_cv') && $hasExistingCV) {
            $resumePath = $mahasiswa->cv;
        }
        // Jika tidak ada CV yang disediakan dan tidak ada CV yang bisa digunakan, kembalikan error
        else if (!$hasExistingCV) {
            return redirect()->back()->with('error', 'Anda harus mengupload CV untuk melamar pekerjaan ini.');
        }

        // Buat record lamaran pekerjaan menggunakan model JobApplication
        \App\Models\JobApplication::create([
            'job_id' => $jobId,
            'student_id' => $user->id,
            'resume_path' => $resumePath,
            // 'cover_letter' => $request->input('cover_letter', ''),
            'status' => 'pending'
        ]);

        $message = $cvUpdated ?
            'Lamaran berhasil dikirim dengan CV baru Anda!' :
            'Lamaran berhasil dikirim dengan CV yang sudah ada!';

        return redirect()->back()->with('success', $message);
    }
}
