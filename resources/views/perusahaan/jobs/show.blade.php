@extends('layouts.perusahaan')

@section('title', $job->title . ' - Job Details')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Job Details</h4>
        <div>
            <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-light btn-sm">Edit</a>
            <a href="{{ route('jobs.index') }}" class="btn btn-light btn-sm">Back to List</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>{{ $job->title }}</h2>
                <div class="d-flex gap-3 mb-3">
                    <span class="badge bg-secondary">{{ $job->location }}</span>
                    @if($job->salary_min || $job->salary_max)
                        <span class="badge bg-info">{{ $job->formatted_salary_range }}</span>
                    @endif
                    
                    @if($job->status == 'pending')
                        <span class="badge bg-warning">Pending Approval</span>
                    @elseif($job->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($job->status == 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <p class="text-muted">Posted on: {{ $job->created_at->format('d M Y') }}</p>
                @if($job->deadline)
                    <p class="text-muted">Deadline: {{ date('d M Y', strtotime($job->deadline)) }}</p>
                @endif
            </div>
        </div>
        
        <div class="mb-4">
            <h5>Job Description</h5>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($job->description)) !!}
            </div>
        </div>
        
        @if($job->requirements)
            <div class="mb-4">
                <h5>Requirements</h5>
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($job->requirements)) !!}
                </div>
            </div>
        @endif
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back to Jobs</a>
            <div>
                <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-primary">Edit Job</a>
                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Job</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
