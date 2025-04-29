@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Job Approval Management</h2>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    <ul class="nav nav-tabs mb-4" id="jobTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">
                Pending <span class="badge badge-warning">{{ count($pendingJobs) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">
                Approved <span class="badge badge-success">{{ count($approvedJobs) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">
                Rejected <span class="badge badge-danger">{{ count($rejectedJobs) }}</span>
            </a>
        </li>
    </ul>
    
    <div class="tab-content" id="jobTabsContent">
        <!-- Pending Jobs Tab -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            @if(count($pendingJobs) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Posted</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingJobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->company->name }}</td>
                                <td>{{ $job->created_at->format('M d, Y') }}</td>
                                <td>{{ $job->location }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#jobModal{{ $job->id }}">View</a>
                                        <form action="{{ route('jobs.update.status', $job->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('jobs.update.status', $job->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">No pending jobs to approve.</div>
            @endif
        </div>
        
        <!-- Approved Jobs Tab -->
        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
            @if(count($approvedJobs) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Approved Date</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedJobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->company->name }}</td>
                                <td>{{ $job->updated_at->format('M d, Y') }}</td>
                                <td>{{ $job->location }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#jobModal{{ $job->id }}">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">No approved jobs.</div>
            @endif
        </div>
        
        <!-- Rejected Jobs Tab -->
        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
            @if(count($rejectedJobs) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Rejected Date</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedJobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->company->name }}</td>
                                <td>{{ $job->updated_at->format('M d, Y') }}</td>
                                <td>{{ $job->location }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#jobModal{{ $job->id }}">View</a>
                                        <form action="{{ route('jobs.update.status', $job->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">No rejected jobs.</div>
            @endif
        </div>
    </div>
    
    <!-- Job Detail Modals -->
    @foreach(array_merge($pendingJobs->toArray(), $approvedJobs->toArray(), $rejectedJobs->toArray()) as $job)
    <div class="modal fade" id="jobModal{{ $job['id'] }}" tabindex="-1" role="dialog" aria-labelledby="jobModalLabel{{ $job['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobModalLabel{{ $job['id'] }}">{{ $job['title'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Company: {{ App\Models\User::find($job['company_id'])->name }}</h6>
                    <h6>Location: {{ $job['location'] }}</h6>
                    
                    <hr>
                    <h6>Description:</h6>
                    <p>{{ $job['description'] }}</p>
                    
                    <h6>Requirements:</h6>
                    <p>{{ $job['requirements'] ?: 'Not specified' }}</p>
                    
                    <h6>Salary Range:</h6>
                    <p>
                        @if($job['salary_min'] && $job['salary_max'])
                            Rp {{ number_format($job['salary_min'], 0, ',', '.') }} - Rp {{ number_format($job['salary_max'], 0, ',', '.') }}
                        @elseif($job['salary_min'])
                            From Rp {{ number_format($job['salary_min'], 0, ',', '.') }}
                        @elseif($job['salary_max'])
                            Up to Rp {{ number_format($job['salary_max'], 0, ',', '.') }}
                        @else
                            Negotiable
                        @endif
                    </p>
                    
                    @if($job['deadline'])
                    <h6>Application Deadline:</h6>
                    <p>{{ \Carbon\Carbon::parse($job['deadline'])->format('M d, Y') }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @if($job['status'] === 'pending')
                    <form action="{{ route('jobs.update.status', $job['id']) }}" method="post" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <form action="{{ route('jobs.update.status', $job['id']) }}" method="post" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
