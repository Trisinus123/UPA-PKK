@extends('layouts.perusahaan')

@section('content')
<div class="container mt-4">
    <h1>Job Applications</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Your Jobs and Applications
        </div>
        <div class="card-body">
            @if($jobs->isEmpty())
                <p class="text-muted">You haven't posted any jobs yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Date Posted</th>
                                <th>Status</th>
                                <th>Applications</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>{{ $job->title }}</td>
                                    <td>{{ $job->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($job->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($job->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $job->applications_count }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('applications.show', $job) }}" class="btn btn-sm btn-info">
                                            View Applications
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
