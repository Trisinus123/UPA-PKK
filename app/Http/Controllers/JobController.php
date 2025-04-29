<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use App\Models\MahasiswaProfile;
use App\Models\User;
use App\Models\PerusahaanProfile;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Display all jobs for company
    public function index()
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $jobs = Job::where('company_id', Auth::id())->get();
        return view('perusahaan.jobs.index', compact('jobs'));
    }
    
    // Show job creation form
    public function create()
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        return view('perusahaan.jobs.create');
    }

    // Store a new job
    public function store(Request $request) {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'requirements' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $job = Job::create([
            'company_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'salary_min' => $validated['salary_min'] ?? null,
            'salary_max' => $validated['salary_max'] ?? null,
            'requirements' => $validated['requirements'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('jobs.index')->with('success', 'Job submitted for approval');
    }
    
    // Show job edit form
    public function edit($id)
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $job = Job::where('company_id', Auth::id())->findOrFail($id);
        return view('perusahaan.jobs.edit', compact('job'));
    }
    
    // Update job
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $job = Job::where('company_id', Auth::id())->findOrFail($id);
        $originalTitle = $job->title;
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'requirements' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);
        
        // Only reset to pending if the job title was changed
        if ($job->status == 'approved' && $originalTitle != $validated['title']) {
            $job->status = 'pending';
            $statusMessage = 'Job updated and submitted for approval because title was changed';
        } else {
            // Keep the current status
            $statusMessage = 'Job updated successfully';
        }
        
        $job->update($validated);
        $job->save();
        
        return redirect()->route('jobs.index')->with('success', $statusMessage);
    }
    
    // Delete job
    public function destroy($id)
    {
        if (Auth::user()->role !== 'perusahaan') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $job = Job::where('company_id', Auth::id())->findOrFail($id);
        $job->delete();
        
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully');
    }
    
    // Show job details
    public function show($id)
    {
        $job = Job::findOrFail($id);
        
        if (Auth::user()->role === 'perusahaan' && $job->company_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        // Use different views depending on user role
        if (Auth::user()->role === 'mahasiswa') {
            // Check if student has already applied for this job
            $alreadyApplied = $job->hasApplied(Auth::id());
            return view('mahasiswa.job_details', compact('job', 'alreadyApplied'));
        } else {
            return view('perusahaan.jobs.show', compact('job'));
        }
    }

    // Student applies for a job
    public function applyForJob($id)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $job = Job::where('status', 'approved')->findOrFail($id);
        
        // Check if already applied
        $alreadyApplied = $job->hasApplied(Auth::id());
        if ($alreadyApplied) {
            return redirect()->route('jobs.show', $id)->with('info', 'You have already applied for this job');
        }
        
        // Create application
        JobApplication::create([
            'job_id' => $job->id,
            'student_id' => Auth::id(),
            'status' => 'pending',
        ]);
        
        return redirect()->route('jobs.show', $id)->with('success', 'Your application has been submitted successfully');
    }

    // Mahasiswa melihat job yang sudah disetujui
    public function listApprovedJobs(Request $request) 
    {
        $query = Job::where('status', 'approved')
            ->where(function($q) {
                $q->whereNull('deadline')  // Jobs with no deadline
                  ->orWhere('deadline', '>=', now()->toDateString()); // Or deadline not passed
            });
        
        // Sort by date
        $sort = $request->input('sort', 'newest');
        if ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        }
        
        $jobs = $query->get();
        
        // Check which jobs the student has already applied for
        if (Auth::user()->role === 'mahasiswa') {
            $appliedJobIds = JobApplication::where('student_id', Auth::id())
                ->pluck('job_id')
                ->toArray();
        } else {
            $appliedJobIds = [];
        }
        
        return view('mahasiswa.jobs', compact('jobs', 'sort', 'appliedJobIds'));
    }
    
    // View student's job applications
    public function myApplications()
    {
        if (Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $applications = JobApplication::with(['job.company'])
            ->where('student_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('mahasiswa.my_applications', compact('applications'));
    }

    // Admin menyetujui atau menolak job
    public function updateStatus(Request $request, $id) {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $request->validate(['status' => 'required|in:approved,rejected']);

        $job = Job::findOrFail($id);
        $job->status = $request->status;
        $job->save();

        return redirect()->back()->with('success', 'Job status updated successfully');
    }

    // Admin view all pending jobs
    public function adminJobApproval()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $pendingJobs = Job::where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $approvedJobs = Job::where('status', 'approved')->orderBy('created_at', 'desc')->get();
        $rejectedJobs = Job::where('status', 'rejected')->orderBy('created_at', 'desc')->get();
        
        return view('admin.job_approval', compact('pendingJobs', 'approvedJobs', 'rejectedJobs'));
    }

    // Browse jobs with CV check
    public function browse(Request $request)
    {
        $sort = $request->query('sort', 'newest');
        
        // Fetch jobs with sorting and filter by deadline
        $query = Job::with('company')
            ->where('status', 'approved')
            ->where(function($q) {
                $q->whereNull('deadline')  // Jobs with no deadline
                  ->orWhere('deadline', '>=', now()->toDateString()); // Or deadline not passed
            });
            
        if ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $jobs = $query->get();
        
        // Get user's applied job IDs
        $appliedJobIds = [];
        if (Auth::check()) {
            $appliedJobIds = JobApplication::where('student_id', Auth::id())
                ->pluck('job_id')
                ->toArray();
        }
        
        // Check if user has CV
        $hasCV = false;
        if (Auth::check()) {
            $user = Auth::user();
            $mahasiswa = MahasiswaProfile::where('user_id', $user->id)->first();
            $hasCV = $mahasiswa && $mahasiswa->cv && $mahasiswa->cv != '';
        }
        
        return view('mahasiswa.jobs', compact('jobs', 'sort', 'appliedJobIds', 'hasCV'));
    }

    /**
     * List all companies with job counts
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function listCompanies(Request $request)
    {
        // Get all users with 'perusahaan' role that have company profiles
        $companies = User::where('role', 'perusahaan')
            ->with('perusahaanProfile')
            ->withCount(['jobs' => function($query) {
                $query->where('status', 'approved')
                    ->where(function($q) {
                        $q->whereNull('deadline')  // Jobs with no deadline
                          ->orWhere('deadline', '>=', now()->toDateString()); // Or deadline not passed
                    });
            }])
            ->orderBy('name')
            ->get();
        
        return view('mahasiswa.companies', compact('companies'));
    }
}

