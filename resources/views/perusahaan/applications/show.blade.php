@extends('layouts.perusahaan')

@section('content')
<div class="container mt-4">
    <h1>Applications for: {{ $job->title }}</h1>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Job Details</span>
            <a href="{{ route('applications.index') }}" class="btn btn-sm btn-light">Back to All Jobs</a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Title:</strong> {{ $job->title }}</p>
                    <p><strong>Location:</strong> {{ $job->location }}</p>
                    <p><strong>Status:</strong> 
                        @if($job->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($job->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Salary Range:</strong> 
                        @if($job->salary_min && $job->salary_max)
                            Rp {{ number_format($job->salary_min) }} - Rp {{ number_format($job->salary_max) }}
                        @elseif($job->salary_min)
                            From Rp {{ number_format($job->salary_min) }}
                        @elseif($job->salary_max)
                            Up to Rp {{ number_format($job->salary_max) }}
                        @else
                            Not specified
                        @endif
                    </p>
                    <p><strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}</p>
                    <p><strong>Deadline:</strong> {{ $job->deadline ? date('M d, Y', strtotime($job->deadline)) : 'None' }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <span>Applications ({{ $applications->count() }})</span>
        </div>
        <div class="card-body">
            @if($applications->isEmpty())
                <p class="text-muted">No applications received yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Applied On</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td>
                                        {{ $application->student->name }}
                                    </td>
                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($application->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($application->status == 'reviewing')
                                            <span class="badge bg-info">Reviewing</span>
                                        @elseif($application->status == 'interview')
                                            <span class="badge bg-primary">Interview</span>
                                        @elseif($application->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($application->status == 'accepted')
                                            <span class="badge bg-success">Accepted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#applicantModal{{ $application->id }}">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Modal for each application -->
                                <div class="modal fade" id="applicantModal{{ $application->id }}" tabindex="-1" aria-labelledby="applicantModalLabel{{ $application->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="applicantModalLabel{{ $application->id }}">Applicant: {{ $application->student->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <p><strong>Email:</strong> {{ $application->student->email }}</p>
                                                        <p><strong>Applied On:</strong> {{ $application->created_at->format('M d, Y') }}</p>
                                                    </div>
                                                </div>
                                                
                                                @php
                                                    // Determine if CV is available
                                                    $resumePath = $application->resume_path;
                                                    if (!$resumePath && isset($application->student->mahasiswaProfile)) {
                                                        $resumePath = $application->student->mahasiswaProfile->cv;
                                                    }
                                                    $hasCv = !empty($resumePath);
                                                @endphp
                                                
                                                @if($hasCv)
                                                    <div class="mb-3">
                                                        <h6>Resume/CV</h6>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('applications.cv.view', $application->id) }}" class="btn btn-primary" target="_blank">View CV</a>
                                                            <a href="{{ route('applications.cv.download', $application->id) }}" class="btn btn-secondary">Download CV</a>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <hr>
                                                <form action="{{ route('applications.update.status', $application) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Update Application Status</label>
                                                        <select class="form-select" name="status" id="status">
                                                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="reviewing" {{ $application->status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                                            <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Interview</option>
                                                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                                            <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accept</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
