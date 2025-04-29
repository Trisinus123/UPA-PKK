<!-- filepath: c:\Users\HP\Downloads\jobportal\resources\views\mahasiswa\jobs.blade.php -->
@extends('mahasiswa.mahasiswa')

@section('content')
@php
    // Calculate hasCV variable directly in the view
    $user = Auth::user();
    $mahasiswa = \App\Models\MahasiswaProfile::where('user_id', $user->id)->first();
    $hasCV = $mahasiswa && $mahasiswa->cv && $mahasiswa->cv != '';
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Available Job Opportunities</h4>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort By: {{ ucfirst($sort) }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sortDropdown">
                        <a class="dropdown-item {{ $sort == 'newest' ? 'active' : '' }}" href="{{ route('jobs.browse', ['sort' => 'newest']) }}">Newest</a>
                        <a class="dropdown-item {{ $sort == 'oldest' ? 'active' : '' }}" href="{{ route('jobs.browse', ['sort' => 'oldest']) }}">Oldest</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(count($jobs) > 0)
                    <div class="row">
                        @foreach($jobs as $job)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{ $job->title }}</h5>
                                    <small class="text-muted">{{ $job->company->name }} • {{ $job->location }}</small>
                                </div>
                                <div class="card-body">
                                    <p class="text-primary mb-1">{{ $job->formatted_salary_range }}</p>
                                    <p class="mb-0"><small class="text-muted">Posted {{ $job->created_at->diffForHumans() }}</small></p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-info">
                                        View Details
                                    </a>
                                    @if(in_array($job->id, $appliedJobIds))
                                        <button class="btn btn-sm btn-secondary" disabled>Already Applied</button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('applyJobModal-{{ $job->id }}').style.display='block'">
                                            Apply Now
                                        </button>
                                    @endif
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
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        No job opportunities available at the moment. Please check back later.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection