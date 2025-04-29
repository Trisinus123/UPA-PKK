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
        
        // Create profile if it doesn't exist
        if (!$mahasiswa) {
            $mahasiswa = MahasiswaProfile::create([
                'user_id' => $user->id,
                'nim' => $user->nim ?? 'N/A' // Use the NIM from user model if available
            ]);
        } else if ($mahasiswa->nim == 'N/A' && $user->nim) {
            // Update the NIM if user has one but profile doesn't
            $mahasiswa->nim = $user->nim;
            $mahasiswa->save();
        }
        
        return view('mahasiswa.profile', compact('mahasiswa'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'cv' => 'nullable|mimes:pdf|max:10240', // Changed from 2048 to 10240 (10MB)
        ]);

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $path = $file->store('cv', 'public');
            
            // Get existing profile or create new one
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
     * Check if the current authenticated user has uploaded a CV
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
     * Process job application with CV upload option
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
        
        // Check if user has a CV already
        $mahasiswa = MahasiswaProfile::where('user_id', $user->id)->first();
        $hasExistingCV = $mahasiswa && $mahasiswa->cv && $mahasiswa->cv != '';
        
        // Handle new CV upload if provided
        if ($request->hasFile('cv')) {
            $request->validate([
                'cv' => 'required|mimes:pdf|max:10240', // Changed from 2048 to 10240 (10MB)
            ]);
            
            $file = $request->file('cv');
            $resumePath = $file->store('cv', 'public');
            
            // Update user's CV
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
        // If use_existing_cv is set and user actually has a CV, use the existing CV
        else if ($request->has('use_existing_cv') && $hasExistingCV) {
            $resumePath = $mahasiswa->cv;
        }
        // If no CV is provided and no existing CV to use, return error
        else if (!$hasExistingCV) {
            return redirect()->back()->with('error', 'You must upload a CV to apply for this job.');
        }
        
        // Create the job application record using the JobApplication model
        \App\Models\JobApplication::create([
            'job_id' => $jobId,
            'student_id' => $user->id,
            'resume_path' => $resumePath,
            // 'cover_letter' => $request->input('cover_letter', ''),
            'status' => 'pending'
        ]);
        
        $message = $cvUpdated ? 
            'Application submitted successfully with your new CV!' : 
            'Application submitted successfully with your existing CV!';
            
        return redirect()->back()->with('success', $message);
    }
}
