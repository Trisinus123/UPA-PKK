@extends('mahasiswa.mahasiswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">My Job Applications</h4>
            </div>
            <div class="card-body">
                @if(count($applications) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Job Title</th>
                                    <th>Company</th>
                                    <th>Applied On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                <tr>
                                    <td>{{ $application->job->title }}</td>
                                    <td>{{ $application->job->company->name }}</td>
                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($application->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($application->status == 'accepted')
                                            <span class="badge badge-success">Accepted</span>
                                        @elseif($application->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @elseif($application->status == 'interview')
                                            <span class="badge badge-info">Interview</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('jobs.show', $application->job_id) }}" class="btn btn-sm btn-info">View Job</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <p>You haven't applied to any jobs yet.</p>
                        <a href="{{ route('jobs.browse') }}" class="btn btn-primary mt-2">Browse Available Jobs</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
