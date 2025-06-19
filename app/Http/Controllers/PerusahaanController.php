<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\MahasiswaProfile;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{
    public function __construct()
    {
        // Hanya terapkan middleware ke fungsi khusus perusahaan
        // Izinkan akses publik ke showCompanyProfile untuk mahasiswa
        $this->middleware(['auth', 'role:perusahaan'])->except(['showPublicProfile', 'showCompanyProfile']);
    }

    public function index()
    {
        $jobs = Job::with('CategoryJob') // Tambahkan eager loading
                ->where('company_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        return view('perusahaan.jobs.index', compact('jobs'));
    }

    public function profile()
    {
        $user = Auth::user();
        $profile = PerusahaanProfile::where('user_id', $user->id)->first();

        return view('perusahaan.profile', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'website' => 'nullable|url',
            'alamat_perusahaan' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Perbarui nama perusahaan di tabel users (kolom name)
        $user->name = $request->nama_perusahaan;
        $user->save();

        // Perbarui data lain di tabel perusahaan_profiles
        $profile = PerusahaanProfile::firstOrNew(['user_id' => $user->id]);
        $profile->website = $request->website;
        $profile->alamat_perusahaan = $request->alamat_perusahaan;
        $profile->deskripsi = $request->deskripsi;

        // Upload foto menggunakan Laravel storage
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($profile->foto && Storage::disk('public')->exists($profile->foto)) {
                Storage::disk('public')->delete($profile->foto);
            }

            // Simpan foto baru
            $profile->foto = $request->file('foto')->store('foto_perusahaan', 'public');
        }

        $profile->save();

        return redirect()->route('perusahaan.profile')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    public function showPublicProfile($id)
    {
        // Hapus pengecekan middleware di sini, izinkan pengunjung melihat profil perusahaan
        $user = \App\Models\User::findOrFail($id);

        // Cek apakah user benar-benar perusahaan
        if ($user->role !== 'perusahaan') {
            abort(404, 'Perusahaan tidak ditemukan');
        }

        $profile = \App\Models\PerusahaanProfile::where('user_id', $id)->first();
        // Hanya tampilkan pekerjaan yang disetujui dari perusahaan ini
        $jobs = \App\Models\Job::where('company_id', $id)
                          ->where('status', 'approved')
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('perusahaan.profile', compact('user', 'profile', 'jobs'));
    }

    /**
     * Menampilkan profil perusahaan untuk tampilan publik/mahasiswa
     * Method ini dapat diakses oleh role mahasiswa tanpa batasan
     */
    public function showCompanyProfile($id)
    {
        // Cari user perusahaan
        $company = User::where('id', $id)
                    ->where('role', 'perusahaan')
                    ->firstOrFail();

        // Ambil profil perusahaan
        $profile = PerusahaanProfile::where('user_id', $id)->first();

        // Ambil pekerjaan perusahaan yang sudah disetujui
        $jobs = Job::where('company_id', $id)
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('mahasiswa.company_profile', compact('company', 'profile', 'jobs'));
    }

    public function applications()
    {
        // Ambil perusahaan yang sedang login
        $company = auth()->user();

        // Ambil aplikasi yang terkait dengan pekerjaan milik perusahaan tersebut
        $applications = JobApplication::with(['job', 'student'])
            ->whereHas('job', function($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->get();

        // Kirim data ke view
        return view('perusahaan.applications.index', compact('applications'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,rejected,interview,accepted'
        ]);

        $application->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status aplikasi berhasil diperbarui');
    }

    public function showApplications(JobApplication $application)
    {
        // Eager load semua relasi yang diperlukan untuk menghindari query N+1
        $application->load([
            'job',
            'job.applications',
            'job.applications.student',
            'documents',
            'documents.requirement'
        ]);

        // Ambil pekerjaan dan aplikasinya dari relasi yang sudah diload
        $job = $application->job;
        $applications = $job->applications;

        return view('perusahaan.applications.show', [
            'job' => $job,
            'applications' => $applications,
            'currentApplication' => $application // Kirim aplikasi spesifik jika diperlukan
        ]);
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        // Pastikan user adalah pemilik pekerjaan
        $job = Job::find($application->job_id);
        if (!$job || Auth::id() !== $job->company_id) {
            return redirect()->route('jobs.index')->with('error', 'Akses tidak diizinkan');
        }

        // Validasi request
        $request->validate([
            'status' => 'required|in:pending,reviewing,accepted,rejected,interview'
        ]);

        // Perbarui status aplikasi
        $application->status = $request->status;
        $application->save();

        return redirect()->back()->with('success', 'Status aplikasi berhasil diperbarui');
    }

    public function downloadCV($applicationId)
    {
        $application = JobApplication::with('job')->findOrFail($applicationId);

        // Pastikan user adalah pemilik pekerjaan
        $job = $application->job;
        if (!$job || Auth::id() !== $job->company_id) {
            return redirect()->route('jobs.index')->with('error', 'Akses tidak diizinkan');
        }

        // Ambil path resume (baik dari aplikasi atau profil mahasiswa)
        $resumePath = $application->resume_path;

        // Jika tidak ada resume di record aplikasi, coba ambil dari profil mahasiswa
        if (!$resumePath) {
            $mahasiswaProfile = MahasiswaProfile::where('user_id', $application->student_id)->first();
            if ($mahasiswaProfile) {
                $resumePath = $mahasiswaProfile->cv;
            }
        }

        // Cek apakah file ada
        if (!$resumePath || !Storage::disk('public')->exists($resumePath)) {
            return redirect()->back()->with('error', 'File CV tidak ditemukan');
        }

        // Return file sebagai download
        return response()->download(storage_path('app/public/' . $resumePath));
    }

    public function viewCV($applicationId)
    {
        $application = JobApplication::with('job')->findOrFail($applicationId);

        // Pastikan user adalah pemilik pekerjaan
        $job = $application->job;
        if (!$job || Auth::id() !== $job->company_id) {
            return redirect()->route('jobs.index')->with('error', 'Akses tidak diizinkan');
        }

        // Ambil path resume (baik dari aplikasi atau profil mahasiswa)
        $resumePath = $application->resume_path;

        // Jika tidak ada resume di record aplikasi, coba ambil dari profil mahasiswa
        if (!$resumePath) {
            $mahasiswaProfile = MahasiswaProfile::where('user_id', $application->student_id)->first();
            if ($mahasiswaProfile) {
                $resumePath = $mahasiswaProfile->cv;
            }
        }

        // Cek apakah file ada
        if (!$resumePath || !Storage::disk('public')->exists($resumePath)) {
            return redirect()->back()->with('error', 'File CV tidak ditemukan');
        }

        // Return file untuk dilihat di browser
        return response()->file(storage_path('app/public/' . $resumePath));
    }
}
