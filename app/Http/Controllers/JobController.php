<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use App\Models\MahasiswaProfile;
use App\Models\User;
use App\Models\PerusahaanProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;
use App\Models\JobDocumentRequirement;
// Dokumen aplikasi
use App\Models\ApplicationDocument;


class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan semua lowongan untuk perusahaan
    public function index()
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        $jobs = Job::where('company_id', Auth::id())->get();
        return view('perusahaan.jobs.index', compact('jobs'));
    }

    // Menampilkan form pembuatan lowongan
    public function create()
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        // Mengambil kategori untuk dropdown
        $categories = \App\Models\CategoryJob::all();

        return view('perusahaan.jobs.create', compact('categories'));
    }

    // Menyimpan lowongan baru
    public function store(Request $request)
    {
        $userId = Auth::id();
        $logContext = ['user_id' => $userId, 'ip' => $request->ip()];

        try {
            // Pengecekan otorisasi
            if (Auth::user()->role !== 'perusahaan') {
                Log::warning('Percobaan pembuatan lowongan tidak diizinkan', $logContext);
                return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
            }

            // Log input (kecuali field besar)
            Log::debug('Permintaan pembuatan lowongan diterima', $logContext + [
                'input' => $request->except(['description', 'requirements'])
            ]);

            // Membersihkan input HTML
            $request->merge([
                'description' => Purifier::clean($request->description, [
                    'HTML.Allowed' => 'p,br,strong,em,u,strike,ul,ol,li,a[href|target],img[src|alt|width|height],table,thead,tbody,tr,th,td,blockquote,code',
                    'AutoFormat.AutoParagraph' => true,
                    'AutoFormat.RemoveEmpty' => true,
                ]),
                'requirements' => $request->has('requirements') ? Purifier::clean($request->requirements, [
                    'HTML.Allowed' => 'p,br,strong,em,u,strike,ul,ol,li'
                ]) : null,
            ]);

            // Validasi
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:category,id',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'salary_min' => 'nullable|numeric|min:0',
                'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
                'requirements' => 'nullable|string',
                'deadline' => 'nullable|date|after_or_equal:today',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'deadline.after_or_equal' => 'Batas waktu harus hari ini atau tanggal setelahnya.',
                'salary_max.gte' => 'Gaji maksimal harus lebih besar atau sama dengan gaji minimal.',
            ]);

            Log::info('Validasi lowongan berhasil', $logContext + [
                'fields' => array_keys($validated),
                'title' => $validated['title'],
                'location' => $validated['location']
            ]);

            // Menyiapkan data
            $data = [
                'company_id' => $userId,
                'title' => strip_tags($validated['title']),
                'description' => strip_tags($validated['description']),
                'location' => strip_tags($validated['location']),
                'salary_min' => $validated['salary_min'] ?? null,
                'salary_max' => $validated['salary_max'] ?? null,
                'requirements' => strip_tags($validated['requirements']),
                'deadline' => $validated['deadline'] ?? null,
                'status' => 'pending',
                'category_id' => $validated['category_id'],
            ];

            // Upload file
            if ($request->hasFile('gambar')) {
                try {
                    $imagePath = $request->file('gambar')->store('gambar_perusahaan', 'public');
                    $data['gambar'] = $imagePath;
                    Log::info('Gambar lowongan diunggah', $logContext + [
                        'image_path' => $imagePath,
                        'image_size' => $request->file('gambar')->getSize()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Gagal mengunggah gambar', $logContext + [
                        'error' => $e->getMessage()
                    ]);
                    return back()->withErrors('Gagal mengunggah gambar.');
                }
            }

            // Menyimpan Lowongan dan Persyaratan dalam transaksi
            try {
                \DB::beginTransaction();
                $job = Job::create($data);

                // Menyimpan persyaratan dokumen
                if ($request->has('document_requirements')) {
                    foreach ($request->document_requirements as $requirement) {
                        JobDocumentRequirement::create([
                            'job_id' => $job->id,
                            'document_name' => $requirement['name'],
                            'is_required' => $requirement['required'] ?? true,
                            'description' => $requirement['description'] ?? null,
                        ]);
                    }
                }

                \DB::commit();

                Log::info('Lowongan berhasil dibuat', $logContext + [
                    'job_id' => $job->id
                ]);

                return redirect()->route('jobs.index')
                    ->with('success', 'Lowongan diajukan untuk persetujuan.');

            } catch (\Exception $e) {
                \DB::rollBack();

                Log::error('Gagal membuat lowongan dalam transaksi', $logContext + [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => array_merge($data, ['description' => '...dipotong...', 'requirements' => '...dipotong...']),
                    'document_requirements' => $request->input('document_requirements', [])
                ]);

                if (isset($imagePath)) {
                    try {
                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                            Log::info('Membersihkan gambar yang diunggah', $logContext + ['image_path' => $imagePath]);
                        }
                    } catch (\Exception $cleanupException) {
                        Log::error('Gagal membersihkan gambar', $logContext + [
                            'error' => $cleanupException->getMessage()
                        ]);
                    }
                }

                return back()->withErrors('Gagal menyimpan lowongan. Silakan coba lagi.');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal', $logContext + [
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors());

        } catch (\Exception $e) {
            Log::error('Kesalahan tak terduga', $logContext + [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors('Terjadi kesalahan tak terduga.');
        }
    }

    // Menampilkan form edit lowongan
    public function edit($id)
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        $job = Job::where('company_id', Auth::id())->findOrFail($id);

        // Mengambil kategori untuk dropdown
        $categories = \App\Models\CategoryJob::all();
        return view('perusahaan.jobs.edit', compact('job', 'categories'));
    }

    // Memperbarui lowongan
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $logContext = ['user_id' => $userId, 'ip' => $request->ip(), 'job_id' => $id];

        try {
            // Pengecekan otorisasi
            if (Auth::user()->role !== 'perusahaan') {
                Log::warning('Percobaan pembaruan lowongan tidak diizinkan', $logContext);
                return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
            }

            $job = Job::where('company_id', $userId)->findOrFail($id);
            $originalTitle = $job->title;

            // Log input mentah untuk debugging (kecuali field sensitif)
            Log::debug('Permintaan pembaruan lowongan diterima', $logContext + [
                'input' => $request->except(['description', 'requirements'])
            ]);

            // Membersihkan input HTML sebelum validasi dengan pengaturan yang sama seperti store
            $request->merge([
                'description' => Purifier::clean($request->description, [
                    'HTML.Allowed' => 'p,br,strong,em,u,strike,ul,ol,li,a[href|target],img[src|alt|width|height],table,thead,tbody,tr,th,td,blockquote,code',
                    'CSS.AllowedProperties' => '',
                    'AutoFormat.AutoParagraph' => true,
                    'AutoFormat.RemoveEmpty' => true,
                ]),
                'requirements' => $request->has('requirements') ? Purifier::clean($request->requirements, [
                    'HTML.Allowed' => 'p,br,strong,em,u,strike,ul,ol,li'
                ]) : null,
            ]);

            // Aturan validasi (konsisten dengan method store)
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:category,id',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'salary_min' => 'nullable|numeric|min:0',
                'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
                'requirements' => 'nullable|string',
                'deadline' => 'nullable|date|after_or_equal:today',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'deadline.after_or_equal' => 'Batas waktu harus hari ini atau tanggal setelahnya.',
                'salary_max.gte' => 'Gaji maksimal harus lebih besar atau sama dengan gaji minimal.',
            ]);

            Log::info('Validasi pembaruan lowongan berhasil', $logContext + [
                'fields' => array_keys($validated),
                'title' => $validated['title'],
                'location' => $validated['location']
            ]);

            // Menyiapkan data untuk pembaruan
            $data = [
                'title' => strip_tags($validated['title']),
                'description' => strip_tags($validated['description']),
                'location' => strip_tags($validated['location']),
                'salary_min' => $validated['salary_min'] ?? null,
                'salary_max' => $validated['salary_max'] ?? null,
                'requirements' => strip_tags($validated['requirements']),
                'deadline' => $validated['deadline'] ?? null,
                'category_id' => $validated['category_id'], // Memastikan category_id disertakan
            ];

            // Menangani upload file (dengan logging)
            if ($request->hasFile('gambar')) {
                try {
                    // Hapus gambar lama jika ada
                    if ($job->gambar) {
                        Storage::disk('public')->delete($job->gambar);
                        Log::info('Gambar lowongan lama dihapus', $logContext + [
                            'old_image_path' => $job->gambar
                        ]);
                    }

                    $imagePath = $request->file('gambar')->store('gambar_perusahaan', 'public');
                    $data['gambar'] = $imagePath;
                    Log::info('Gambar lowongan baru diunggah', $logContext + [
                        'image_path' => $imagePath,
                        'image_size' => $request->file('gambar')->getSize()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Gagal memperbarui gambar lowongan', $logContext + [
                        'error' => $e->getMessage(),
                        'file_name' => $request->file('gambar')->getClientOriginalName(),
                        'file_size' => $request->file('gambar')->getSize()
                    ]);
                    return back()->withErrors('Gagal mengunggah gambar. Silakan coba lagi.');
                }
            }

            // Reset ke pending jika judul berubah (untuk lowongan yang sudah disetujui)
            if ($job->status == 'approved' && $originalTitle != $validated['title']) {
                $data['status'] = 'pending';
                $statusMessage = 'Lowongan diperbarui dan diajukan ulang untuk persetujuan (judul berubah)';
                Log::info('Status lowongan direset ke pending karena perubahan judul', $logContext);
            }

            // Memperbarui lowongan dengan transaksi
            try {
                \DB::beginTransaction();
                $job->update($data);
                \DB::commit();

                Log::info('Lowongan berhasil diperbarui', $logContext + [
                    'company_id' => $job->company_id,
                    'status' => $job->status
                ]);

                return redirect()->route('jobs.index')
                    ->with('success', $statusMessage ?? 'Lowongan berhasil diperbarui');

            } catch (\Exception $e) {
                \DB::rollBack();

                $errorData = $logContext + [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => array_merge($data, ['description' => '...dipotong...', 'requirements' => '...dipotong...'])
                ];

                Log::error('Gagal memperbarui lowongan - kesalahan database', $errorData);

                // Membersihkan file yang diunggah jika transaksi gagal
                if (isset($imagePath)) {
                    try {
                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                            Log::info('Membersihkan gambar yang diunggah setelah gagal memperbarui lowongan', $logContext + [
                                'image_path' => $imagePath
                            ]);
                        }
                    } catch (\Exception $cleanupException) {
                        Log::error('Gagal membersihkan gambar setelah gagal memperbarui lowongan', $logContext + [
                            'image_path' => $imagePath,
                            'cleanup_error' => $cleanupException->getMessage()
                        ]);
                    }
                }

                return back()->withErrors('Gagal memperbarui data lowongan. Silakan coba lagi.');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi pembaruan lowongan gagal', $logContext + [
                'errors' => $e->errors(),
                'input' => $request->except(['description', 'requirements'])
            ]);
            return back()->withErrors($e->errors());

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Lowongan tidak ditemukan untuk diperbarui', $logContext);
            return redirect()->route('jobs.index')
                ->with('error', 'Lowongan tidak ditemukan atau Anda tidak memiliki izin untuk mengeditnya.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui lowongan - kesalahan sistem', $logContext + [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['description', 'requirements'])
            ]);

            return back()->withErrors('Terjadi kesalahan tak terduga. Silakan coba lagi nanti.');
        }
    }

    // Menghapus lowongan
    public function destroy($id)
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        $job = Job::where('company_id', Auth::id())->findOrFail($id);
        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Lowongan berhasil dihapus');
    }

    // Menampilkan detail lowongan
    public function show($id)
    {
    $job = Job::with('categoryJob')->findOrFail($id); // eager loading kategori

        if (Auth::user()->role === 'perusahaan' && $job->company_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        // Menggunakan view berbeda tergantung peran pengguna
        if (Auth::user()->role === 'mahasiswa') {
            // Memeriksa apakah mahasiswa sudah melamar lowongan ini
            $alreadyApplied = $job->hasApplied(Auth::id());
            return view('mahasiswa.job_details', compact('job', 'alreadyApplied'));
        } else {
            return view('perusahaan.jobs.show', compact('job'));
        }
    }

    // Mahasiswa melamar lowongan
    public function applyForJob($id)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        $job = Job::where('status', 'approved')->findOrFail($id);

        // Memeriksa apakah sudah melamar
        $alreadyApplied = $job->hasApplied(Auth::id());
        if ($alreadyApplied) {
            return redirect()->route('jobs.show', $id)->with('info', 'Anda sudah melamar lowongan ini');
        }

        // Membuat lamaran
        JobApplication::create([
            'job_id' => $job->id,
            'student_id' => Auth::id(),
            'status' => 'Menunggu',
        ]);

        return redirect()->route('jobs.show', $id)->with('success', 'Lamaran Anda berhasil dikirim');
    }

    public function storeJob(Request $request, Job $job)
    {
        DB::beginTransaction();  // Tambahkan ini di awal method

        if (Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        $request->validate([
            'documents.*' => 'required|file|mimes:pdf|max:2048',
        ]);

        try {
            // Membuat lamaran
            $application = JobApplication::create([
                'job_id' => $job->id,
                'student_id' => Auth::id(),
                'status' => 'pending',
            ]);

            // Menyimpan setiap dokumen
            foreach ($job->documentRequirements as $requirement) {
                if ($request->hasFile("documents.{$requirement->id}")) {
                    $file = $request->file("documents.{$requirement->id}");
                    $path = $file->store("applications/{$application->id}", 'public');

                    ApplicationDocument::create([
                        'application_id' => $application->id,
                        'document_requirement_id' => $requirement->id,
                        'file_path' => $path,
                    ]);
                } elseif ($requirement->is_required) {
                    // Jika dokumen wajib tidak ada, hapus lamaran dan kembalikan error
                    $application->delete();

                    Log::warning("Dokumen wajib tidak ada", [
                        'requirement_id' => $requirement->id,
                        'requirement_name' => $requirement->document_name,
                        'application_id' => $application->id,
                        'student_id' => Auth::id(),
                    ]);

                    return back()->withErrors("Dokumen {$requirement->document_name} wajib diisi.");
                }
            }

            Log::info('Lamaran pekerjaan berhasil dikirim', [
                'application_id' => $application->id,
                'student_id' => Auth::id(),
            ]);

            DB::commit();  // Tambahkan ini sebelum return success

            return view('mahasiswa.jobs', [
                'jobs' => $jobs,
                'appliedJobIds' => $appliedJobIds,
                'success' => 'Lamaran berhasil dikirim!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();  // Tambahkan ini di awal catch block

            Log::error('Gagal mengirim lamaran pekerjaan', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'student_id' => Auth::id(),
                'job_id' => $job->id,
            ]);

            return back()->withErrors('Terjadi kesalahan tak terduga saat mengirim lamaran. Silakan coba lagi.');
        }
    }

    // Mahasiswa melihat lowongan yang sudah disetujui
    public function listApprovedJobs(Request $request)
    {
        $query = Job::where('status', 'approved')
            ->where(function($q) {
                $q->whereNull('deadline')  // Lowongan tanpa batas waktu
                  ->orWhere('deadline', '>=', now()->toDateString()); // Atau batas waktu belum lewat
            });

        // Urutkan berdasarkan tanggal
        $sort = $request->input('sort', 'newest');
        if ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        $jobs = $query->get();

        // Memeriksa lowongan yang sudah dilamar mahasiswa
        if (Auth::user()->role === 'mahasiswa') {
            $appliedJobIds = JobApplication::where('student_id', Auth::id())
                ->pluck('job_id')
                ->toArray();
        } else {
            $appliedJobIds = [];
        }

        return view('mahasiswa.jobs', compact('jobs', 'sort', 'appliedJobIds'));
    }

    // Melihat lamaran pekerjaan mahasiswa
    public function myApplications()
    {
        if (Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        $applications = JobApplication::with(['job.company'])
            ->where('student_id', Auth::id())
            ->whereHas('job')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Diubah dari get() ke paginate()

        return view('mahasiswa.my_applications', compact('applications'));
    }

    // Admin menyetujui atau menolak lowongan
    public function updateStatus(Request $request, $id) {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        $request->validate(['status' => 'required|in:approved,rejected']);

        $job = Job::findOrFail($id);
        $job->status = $request->status;
        $job->save();

        return redirect()->back()->with('success', 'Status lowongan berhasil diperbarui');
    }

    // Admin melihat semua lowongan yang menunggu persetujuan
    public function adminJobApproval()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses tidak diizinkan');
        }

        // Mengambil data lowongan dengan relasi categoryJob
        $pendingJobs = Job::with(['categoryJob', 'company'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedJobs = Job::with(['categoryJob', 'company'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $rejectedJobs = Job::with(['categoryJob', 'company'])
            ->where('status', 'rejected')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.job_approval', compact('pendingJobs', 'approvedJobs', 'rejectedJobs'));
    }

    // Menjelajahi lowongan dengan pengecekan CV
    public function browse(Request $request)
    {
        $sort = $request->query('sort', 'newest');

        // Mengambil lowongan dengan pengurutan dan filter berdasarkan batas waktu
        $query = Job::with('company')
            ->where('status', 'approved')
            ->where(function($q) {
                $q->whereNull('deadline')  // Lowongan tanpa batas waktu
                  ->orWhere('deadline', '>=', now()->toDateString()); // Atau batas waktu belum lewat
            });

        if ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $jobs = $query->get();

        // Mendapatkan ID lowongan yang sudah dilamar pengguna
        $appliedJobIds = [];
        if (Auth::check()) {
            $appliedJobIds = JobApplication::where('student_id', Auth::id())
                ->pluck('job_id')
                ->toArray();
        }

        // Memeriksa apakah pengguna memiliki CV
        $hasCV = false;
        if (Auth::check()) {
            $user = Auth::user();
            $mahasiswa = MahasiswaProfile::where('user_id', $user->id)->first();
            $hasCV = $mahasiswa && $mahasiswa->cv && $mahasiswa->cv != '';
        }

        return view('mahasiswa.jobs', compact('jobs', 'sort', 'appliedJobIds', 'hasCV'));
    }

    /**
     * Menampilkan semua perusahaan dengan jumlah lowongan
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function listCompanies(Request $request)
    {
        // Mengambil semua pengguna dengan peran 'perusahaan' yang memiliki profil perusahaan
        $companies = User::where('role', 'perusahaan')
            ->with('perusahaanProfile')
            ->withCount(['jobs' => function($query) {
                $query->where('status', 'approved')
                    ->where(function($q) {
                        $q->whereNull('deadline')  // Lowongan tanpa batas waktu
                          ->orWhere('deadline', '>=', now()->toDateString()); // Atau batas waktu belum lewat
                    });
            }])
            ->orderBy('name')
            ->get();

        return view('mahasiswa.companies', compact('companies'));
    }
}
