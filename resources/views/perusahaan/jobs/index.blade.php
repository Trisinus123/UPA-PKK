@extends('layouts.perusahaan')

@section('title', 'Manage Jobs')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Manage Job Listings</h4>
        <a href="{{ route('jobs.create') }}" class="btn btn-light">Add New Job</a>
    </div>
    <div class="card-body">
        @if($jobs->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Salary Range</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->location }}</td>
                                <td>{{ $job->formatted_salary_range }}</td>
                                <td>
                                    @if($job->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($job->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($job->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $job->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                You haven't posted any jobs yet. <a href="{{ route('jobs.create') }}">Create your first job posting</a>.
            </div>
        @endif
    </div>
</div>
@endsection
