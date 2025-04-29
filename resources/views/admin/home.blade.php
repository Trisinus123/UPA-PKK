@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Admin Dashboard</h4>
                </div>
                <div class="card-body">
                    <h5>Welcome, {{ Auth::user()->name }}!</h5>
                    <p>This is your administration dashboard for the Job Portal.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card mb-4 bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Pending Jobs</h6>
                                            <h2>{{ App\Models\Job::where('status', 'pending')->count() }}</h2>
                                        </div>
                                        <i class="fas fa-clock fa-3x"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="{{ route('admin.jobs.approval') }}" class="text-white">View Details <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4 bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Approved Jobs</h6>
                                            <h2>{{ App\Models\Job::where('status', 'approved')->count() }}</h2>
                                        </div>
                                        <i class="fas fa-check-circle fa-3x"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="{{ route('admin.jobs.approval') }}?tab=approved" class="text-white">View Details <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4 bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Rejected Jobs</h6>
                                            <h2>{{ App\Models\Job::where('status', 'rejected')->count() }}</h2>
                                        </div>
                                        <i class="fas fa-times-circle fa-3x"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="{{ route('admin.jobs.approval') }}?tab=rejected" class="text-white">View Details <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Job Management</h5>
                                </div>
                                <div class="card-body">
                                    <p>Review and manage job postings from companies</p>
                                    <a href="{{ route('admin.jobs.approval') }}" class="btn btn-primary">Manage Jobs</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">User Management</h5>
                                </div>
                                <div class="card-body">
                                    <p>Manage registered companies and students</p>
                                    <a href="#" class="btn btn-primary">Manage Users</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
