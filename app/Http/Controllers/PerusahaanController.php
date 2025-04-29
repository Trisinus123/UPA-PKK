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
        // Only apply middleware to company-specific functions
        // Allow public access to showCompanyProfile for mahasiswa
        $this->middleware(['auth', 'role:perusahaan'])->except(['showPublicProfile', 'showCompanyProfile']);
    }

    public function index()
    {
        $jobs = Job::where('company_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        
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
            'name' => 'required|string|max:255', // validate company name from the user table
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        // Update the user's name directly
        $user = \App\Models\User::find($user->id);
        $user->name = $request->name;
        $user->save();
        
        // Update profile fields except nama_perusahaan
        $profile = PerusahaanProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = new PerusahaanProfile();
            $profile->user_id = $user->id;
        }
        
        $profile->alamat = $request->alamat;
        $profile->deskripsi = $request->deskripsi;
        
        // Handle photo upload (unchanged)
        if ($request->hasFile('foto')) {
            $uploadPath = public_path('uploads/perusahaan');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }
            if ($profile->foto && File::exists(public_path('uploads/perusahaan/' . $profile->foto))) {
                File::delete(public_path('uploads/perusahaan/' . $profile->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);
            $profile->foto = $filename;
        }
        
        $profile->save();
        
        return redirect()->route('perusahaan.profile')->with('success', 'Company profile updated successfully');
    }

    public function showPublicProfile($id)
    {
        // Remove any middleware checks here, allow any visitor to see company profiles
        $user = \App\Models\User::findOrFail($id);
        
        // Check if the user is actually a company
        if ($user->role !== 'perusahaan') {
            abort(404, 'Company not found');
        }
        
        $profile = \App\Models\PerusahaanProfile::where('user_id', $id)->first();
        // Only show approved jobs from this company
        $jobs = \App\Models\Job::where('company_id', $id)
                          ->where('status', 'approved')
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('perusahaan.public_profile', compact('user', 'profile', 'jobs'));
    }

    /**
     * Display company profile for public/students view
     * This method is accessible to mahasiswa role without restrictions
     */
    public function showCompanyProfile($id)
    {
        // Find the company user
        $company = User::where('id', $id)
                    ->where('role', 'perusahaan')
                    ->firstOrFail();
        
        // Get company profile
        $profile = PerusahaanProfile::where('user_id', $id)->first();
        
        // Get company jobs that are approved
        $jobs = Job::where('company_id', $id)
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->get();
        
        return view('mahasiswa.company_profile', compact('company', 'profile', 'jobs'));
    }

    public function applications()
    {
        // Get all jobs from this company with application counts
        $jobs = Job::where('company_id', Auth::id())
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('perusahaan.applications.index', compact('jobs'));
    }
    
    public function jobApplications(Job $job)
    {
        // Ensure user is the owner of this job
        if (Auth::id() !== $job->company_id) {
            return redirect()->route('jobs.index')->with('error', 'Unauthorized access');
        }
        
        // Get all applications for this job with student information
        $applications = JobApplication::where('job_id', $job->id)
            ->with(['student', 'student.mahasiswaProfile'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('perusahaan.applications.show', compact('job', 'applications'));
    }
    
    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        // Ensure user is the owner of the job
        $job = Job::find($application->job_id);
        if (!$job || Auth::id() !== $job->company_id) {
            return redirect()->route('jobs.index')->with('error', 'Unauthorized access');
        }
        
        // Validate the request
        $request->validate([
            'status' => 'required|in:pending,reviewing,accepted,rejected,interview'
        ]);
        
        // Update the application status
        $application->status = $request->status;
        $application->save();
        
        return redirect()->back()->with('success', 'Application status updated successfully');
    }
    
    public function downloadCV($applicationId)
    {
        $application = JobApplication::with('job')->findOrFail($applicationId);
        
        // Ensure user is the owner of the job
        $job = $application->job;
        if (!$job || Auth::id() !== $job->company_id) {
            return redirect()->route('jobs.index')->with('error', 'Unauthorized access');
        }
        
        // Get the resume path (either from application or student profile)
        $resumePath = $application->resume_path;
        
        // If no resume in application record, try getting from student profile
        if (!$resumePath) {
            $mahasiswaProfile = MahasiswaProfile::where('user_id', $application->student_id)->first();
            if ($mahasiswaProfile) {
                $resumePath = $mahasiswaProfile->cv;
            }
        }
        
        // Check if file exists
        if (!$resumePath || !Storage::disk('public')->exists($resumePath)) {
            return redirect()->back()->with('error', 'CV file not found');
        }
        
        // Return the file as a download
        return response()->download(storage_path('app/public/' . $resumePath));
    }
    
    public function viewCV($applicationId)
    {
        $application = JobApplication::with('job')->findOrFail($applicationId);
        
        // Ensure user is the owner of the job
        $job = $application->job;
        if (!$job || Auth::id() !== $job->company_id) {
            return redirect()->route('jobs.index')->with('error', 'Unauthorized access');
        }
        
        // Get the resume path (either from application or student profile)
        $resumePath = $application->resume_path;
        
        // If no resume in application record, try getting from student profile
        if (!$resumePath) {
            $mahasiswaProfile = MahasiswaProfile::where('user_id', $application->student_id)->first();
            if ($mahasiswaProfile) {
                $resumePath = $mahasiswaProfile->cv;
            }
        }
        
        // Check if file exists
        if (!$resumePath || !Storage::disk('public')->exists($resumePath)) {
            return redirect()->back()->with('error', 'CV file not found');
        }
        
        // Return the file for viewing in the browser
        return response()->file(storage_path('app/public/' . $resumePath));
    }
}
