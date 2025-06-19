<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\HomeController;

// Halaman Login & Register
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Registration routes
Route::get('/register', [AuthController::class, 'showRegisterSelection'])->name('register');
Route::get('/register/mahasiswa', [AuthController::class, 'showRegisterMahasiswa'])->name('register.mahasiswa');
Route::get('/register/perusahaan', [AuthController::class, 'showRegisterPerusahaan'])->name('register.perusahaan');
Route::post('/register/mahasiswa', [AuthController::class, 'registerMahasiswa'])->name('register.mahasiswa.submit');
Route::post('/register/perusahaan', [AuthController::class, 'registerPerusahaan'])->name('register.perusahaan.submit');


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/artikel/{id}/view', [HomeController::class, 'showArticle'])->name('articlee.show');


// Autentikasi
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Routes untuk Admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/jobs/approval', [JobController::class, 'adminJobApproval'])->name('admin.jobs.approval');
    Route::put('/jobs/{id}/status', [JobController::class, 'updateStatus'])->name('jobs.update.status');
});

// Routes untuk Mahasiswa
Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/profile', [MahasiswaController::class, 'profile'])->name('mahasiswa.profile');
    Route::post('/upload-cv', [MahasiswaController::class, 'updateProfile'])->name('mahasiswa.upload_cv');
    Route::get('/jobs/approved', [JobController::class, 'listApprovedJobs'])->name('mahasiswa.applications');
});

// Routes untuk Perusahaan
Route::prefix('perusahaan')->middleware(['auth', 'role:perusahaan'])->group(function () {
    Route::get('/dashboard', [PerusahaanController::class, 'index'])->name('perusahaan.dashboard');
    Route::get('/profile', [PerusahaanController::class, 'profile'])->name('perusahaan.profile');
    Route::post('/profile', [PerusahaanController::class, 'updateProfile'])->name('perusahaan.profile.update');
    Route::resource('jobs', JobController::class);
    // storeJob

    // New routes for applications
    Route::get('/applications', [PerusahaanController::class, 'applications'])->name('applications.index');
Route::put('/applications/{application}/status', [PerusahaanController::class, 'updateStatus'])
    ->name('applications.updateStatus');

    Route::get('/applications/{application}', [PerusahaanController::class, 'showApplications'])->name('applications.show');

    // Route::put('/applications/{application}/status', [PerusahaanController::class, 'updateApplicationStatus'])->name('applications.update.status');

    // New routes for CV view and download
    Route::get('/applications/{application}/cv/download', [PerusahaanController::class, 'downloadCV'])->name('applications.cv.download');
    Route::get('/applications/{application}/cv/view', [PerusahaanController::class, 'viewCV'])->name('applications.cv.view');
});

Route::post('/jobs/{job}/apply', [JobController::class, 'storeJob'])
    ->name('applyJob.store')
    ->middleware('auth');


// Group Middleware untuk User yang Login
Route::middleware('auth:sanctum')->group(function () {
    // Routes untuk Perusahaan
    Route::post('/jobs', [JobController::class, 'store'])->middleware('role:perusahaan');

    // Semua User Bisa Melihat Job yang Disetujui
    Route::get('/jobs', [JobController::class, 'listApprovedJobs']);
    Route::get('/student/jobs', [JobController::class, 'listApprovedJobs'])->name('jobs.browse');
    Route::get('/student/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');

    // Ensure this route is defined and working correctly
    Route::get('/student/applications', [App\Http\Controllers\JobController::class, 'myApplications'])->name('student.applications');
});

// Student job browsing and application routes
Route::get('/student/jobs', [App\Http\Controllers\JobController::class, 'listApprovedJobs'])->name('jobs.browse');
Route::get('/student/jobs/{id}', [App\Http\Controllers\JobController::class, 'show'])->name('jobs.show');

// Add new route for companies listing
Route::get('/student/companies', [App\Http\Controllers\JobController::class, 'listCompanies'])->name('companies.browse');

// Add POST route for job application
Route::post('/student/jobs/{id}/apply', [App\Http\Controllers\MahasiswaController::class, 'applyJob'])->name('job.apply');

Route::get('/student/applications', [App\Http\Controllers\JobController::class, 'myApplications'])->name('student.applications');

// Company profile view for students - make sure this is OUTSIDE any middleware groups that restrict to perusahaan
Route::get('/company/{id}', [App\Http\Controllers\PerusahaanController::class, 'showCompanyProfile'])->name('company.profile');

// Routes artikel role:admin

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/artikel', [App\Http\Controllers\ArtikelController::class, 'index'])->name('artikel.index');
    Route::get('/artikel/create', [App\Http\Controllers\ArtikelController::class, 'create'])->name('artikel.create');
    Route::post('/artikel/store', [App\Http\Controllers\ArtikelController::class, 'store'])->name('artikel.store');
    Route::get('/artikel/{id}', [App\Http\Controllers\ArtikelController::class, 'show'])->name('artikel.show');
    Route::get('/artikel/{id}/edit', [App\Http\Controllers\ArtikelController::class, 'edit'])->name('artikel.edit');
    Route::put('/artikel/{id}', [App\Http\Controllers\ArtikelController::class, 'update'])->name('artikel.update');
    Route::delete('/artikel/{id}', [App\Http\Controllers\ArtikelController::class, 'destroy'])->name('artikel.destroy');
});

// Routes data-perusahaan role:admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/data-perusahaan', [App\Http\Controllers\DataPerusahaanController::class, 'index'])->name('data-perusahaan.index');
    Route::get('/data_perusahaan/create', [App\Http\Controllers\DataPerusahaanController::class, 'create'])->name('data-perusahaan.create');
    Route::post('/data_perusahaan/store', [App\Http\Controllers\DataPerusahaanController::class, 'store'])->name('data-perusahaan.store');
    Route::get('/data_perusahaan/{id}', [App\Http\Controllers\DataPerusahaanController::class, 'show'])->name('data-perusahaan.show');
    Route::get('/data_perusahaan/{id}/edit', [App\Http\Controllers\DataPerusahaanController::class, 'edit'])->name('data-perusahaan.edit');
    Route::put('/data_perusahaan/{id}', [App\Http\Controllers\DataPerusahaanController::class, 'update'])->name('data-perusahaan.update');
    Route::delete('/data_perusahaan/{id}', [App\Http\Controllers\DataPerusahaanController::class, 'destroy'])->name('data-perusahaan.destroy');
});

Route::resource('category-job', App\Http\Controllers\CategoryJobController::class);


// Routes for users
Route::resource('users', App\Http\Controllers\UserController::class)->middleware('auth');

// get artikel
