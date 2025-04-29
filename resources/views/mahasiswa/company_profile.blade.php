@extends('mahasiswa.mahasiswa')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Company Profile</h4>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            @if($profile && $profile->foto)
                                <img src="{{ asset('uploads/perusahaan/' . $profile->foto) }}" alt="{{ $company->name }}" 
                                    class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" 
                                    style="width: 150px; height: 150px; margin: 0 auto;">
                                    <i class="fas fa-building fa-4x"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-9">
                            <h2>{{ $company->name }}</h2>
                            
                            @if($profile)
                                @if($profile->alamat)
                                    <p><i class="fas fa-map-marker-alt mr-2 text-primary"></i> {{ $profile->alamat }}</p>
                                @endif
                                
                                @if($profile->deskripsi)
                                    <div class="mt-4">
                                        <h5>About the Company</h5>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                {!! nl2br(e($profile->deskripsi)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted"><i>This company has not completed their profile yet.</i></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Current Job Openings</h4>
                </div>
                
                <div class="card-body">
                    @if($jobs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Location</th>
                                        <th>Salary</th>
                                        <th>Posted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $job)
                                        <tr>
                                            <td>{{ $job->title }}</td>
                                            <td><i class="fas fa-map-marker-alt mr-1 text-secondary"></i> {{ $job->location }}</td>
                                            <td>{{ $job->formatted_salary_range }}</td>
                                            <td>{{ $job->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i> This company currently has no active job openings.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
