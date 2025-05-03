@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard</h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-1">Welcome, {{ Auth::user()->name }}!</h5>
                    <p class="text-muted">This is your administration dashboard for the Job Portal.</p>

                    <div class="row mt-4">
                        <!-- Pending Jobs -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 text-white bg-info shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase">Pending Jobs</h6>
                                        <h2>{{ App\Models\Job::where('status', 'pending')->count() }}</h2>
                                    </div>
                                    <i class="fas fa-clock fa-3x"></i>
                                </div>
                                <div class="card-footer border-0 bg-transparent">
                                    <a href="{{ route('admin.jobs.approval') }}" class="text-white font-weight-bold">View Details <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Approved Jobs -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 text-white bg-success shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase">Approved Jobs</h6>
                                        <h2>{{ App\Models\Job::where('status', 'approved')->count() }}</h2>
                                    </div>
                                    <i class="fas fa-check-circle fa-3x"></i>
                                </div>
                                <div class="card-footer border-0 bg-transparent">
                                    <a href="{{ route('admin.jobs.approval') }}?tab=approved" class="text-white font-weight-bold">View Details <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Rejected Jobs -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 text-white bg-danger shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase">Rejected Jobs</h6>
                                        <h2>{{ App\Models\Job::where('status', 'rejected')->count() }}</h2>
                                    </div>
                                    <i class="fas fa-times-circle fa-3x"></i>
                                </div>
                                <div class="card-footer border-0 bg-transparent">
                                    <a href="{{ route('admin.jobs.approval') }}?tab=rejected" class="text-white font-weight-bold">View Details <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /row -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
