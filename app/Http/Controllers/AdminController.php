<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Data untuk statistik pekerjaan
        $jobStats = [
            'pending' => Job::where('status', 'pending')->count(),
            'approved' => Job::where('status', 'approved')->count(),
            'rejected' => Job::where('status', 'rejected')->count(),
        ];

        // Data untuk chart pendaftaran pengguna
        $userRegistrations = $this->getUserRegistrationData();

        // Data untuk chart pekerjaan per bulan
        $jobPostings = $this->getJobPostingData();

        return view('admin.home', compact('jobStats', 'userRegistrations', 'jobPostings'));
    }

    private function getUserRegistrationData()
    {
        $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = [];
        $counts = [];

        foreach ($users as $user) {
            $dates[] = Carbon::parse($user->date)->format('M d');
            $counts[] = $user->count;
        }

        return [
            'dates' => $dates,
            'counts' => $counts,
        ];
    }

    private function getJobPostingData()
    {
        $jobs = Job::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $jobCounts = [];

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        foreach ($jobs as $job) {
            $months[] = $monthNames[$job->month];
            $jobCounts[] = $job->count;
        }

        return [
            'months' => $months,
            'counts' => $jobCounts,
        ];
    }
}
