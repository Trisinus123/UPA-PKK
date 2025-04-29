@extends('mahasiswa.mahasiswa')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show">
        {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Job Details</h4>
            <a href="{{ route('jobs.browse') }}" class="btn btn-sm btn-light">
                <i class="fas fa-arrow-left mr-1"></i> Back to Jobs
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h3>{{ $job->title }}</h3>
                    <h5 class="text-primary">
                        <a href="{{ url('/company/' . $job->company_id) }}" class="text-primary text-decoration-none">
                            {{ $job->company->name }}
                        </a>
                    </h5>
                    <p class="mb-1"><i class="fas fa-map-marker-alt mr-2"></i>{{ $job->location }}</p>
                    <p class="mb-3"><i class="fas fa-money-bill-wave mr-2"></i>{{ $job->formatted_salary_range }}</p>
                    
                    @if($job->deadline)
                    <div class="alert alert-warning">
                        <i class="fas fa-calendar-alt mr-1"></i> Application Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <h5>Job Description</h5>
                        <div class="card p-3 bg-light">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>
                    
                    @if($job->requirements)
                    <div class="mt-4">
                        <h5>Requirements</h5>
                        <div class="card p-3 bg-light">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Apply for this Position</h5>
                        </div>
                        <div class="card-body">
                            <p>Interested in this job opportunity? Click below to submit your application.</p>
                            
                            @if($alreadyApplied)
                                <button class="btn btn-secondary btn-block" disabled>Already Applied</button>
                                <p class="text-success mt-2"><i class="fas fa-check-circle"></i> You have already applied for this position.</p>
                            @else
                                <button type="button" class="btn btn-primary btn-block" onclick="document.getElementById('applyJobModal-{{ $job->id }}').style.display='block'">
                                    Apply Now
                                </button>
                            @endif
                            
                            <hr>
                            <p class="text-muted mb-0"><small>Posted {{ $job->created_at->diffForHumans() }}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Apply Job Modal - Standard HTML/CSS Modal -->
    <div id="applyJobModal-{{ $job->id }}" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);">
        <div class="modal-dialog" style="margin:10% auto; width:90%; max-width:500px;">
            <div class="modal-content" style="background-color:#fff; border-radius:5px; padding:20px;">
                <div class="modal-header" style="border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:20px;">
                    <h5 class="modal-title">Apply for {{ $job->title }}</h5>
                    <span onclick="document.getElementById('applyJobModal-{{ $job->id }}').style.display='none'" style="float:right; cursor:pointer; font-size:20px;">&times;</span>
                </div>
                
                <!-- Modal body - CV options -->
                <div>
                    @php
                        $user = Auth::user();
                        $mahasiswa = \App\Models\MahasiswaProfile::where('user_id', $user->id)->first();
                        $hasCV = $mahasiswa && $mahasiswa->cv && $mahasiswa->cv != '';
                    @endphp
                    
                    @if($hasCV)
                        <div class="alert alert-info">
                            <p>You have already uploaded a CV. You can:</p>
                            <ul>
                                <li>Continue with your existing CV</li>
                                <li>Upload a new CV for this application</li>
                            </ul>
                        </div>
                        <form action="{{ route('job.apply', $job->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label for="new-cv-{{ $job->id }}">Upload a new CV (PDF only)</label>
                                <input type="file" class="form-control-file" id="new-cv-{{ $job->id }}" name="cv">
                                <small class="form-text text-muted">Maximum file size: 10MB</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Application with New CV</button>
                        </form>
                        <div class="text-center">
                            <p>- OR -</p>
                            <form action="{{ route('job.apply', $job->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="use_existing_cv" value="1">
                                <button type="submit" class="btn btn-success">Continue with Existing CV</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <p>You need to upload a CV to apply for this job.</p>
                        </div>
                        <form action="{{ route('job.apply', $job->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="cv-{{ $job->id }}">Upload your CV (PDF only)</label>
                                <input type="file" class="form-control-file" id="cv-{{ $job->id }}" name="cv" required>
                                <small class="form-text text-muted">Maximum file size: 10MB</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
