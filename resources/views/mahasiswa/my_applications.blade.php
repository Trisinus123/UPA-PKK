@extends('mahasiswa.mahasiswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">My Job Applications</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if(count($applications) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Company</th>
                                    <th>Applied On</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                <tr>
                                    <td>{{ $application->job->title }}</td>
                                    <td>{{ $application->job->company->name }}</td>
                                    <td>{{ $application->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($application->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($application->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($application->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $application->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('jobs.show', $application->job->id) }}" class="btn btn-sm btn-info">View Job</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        You haven't applied to any jobs yet. 
                        <a href="{{ route('jobs.browse') }}" class="alert-link">Browse available jobs</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
