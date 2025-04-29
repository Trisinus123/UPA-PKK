@extends('layouts.perusahaan')

@section('title', 'Edit Job Listing')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Edit Job: {{ $job->title }}</h4>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('jobs.update', $job->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Job Title *</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $job->title) }}" required>
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Location *</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $job->location) }}" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="salary_min" class="form-label">Minimum Salary (Rp)</label>
                    <input type="number" class="form-control" id="salary_min" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="salary_max" class="form-label">Maximum Salary (Rp)</label>
                    <input type="number" class="form-control" id="salary_max" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" min="0">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="deadline" class="form-label">Application Deadline</label>
                <input type="date" class="form-control" id="deadline" name="deadline" value="{{ old('deadline', $job->deadline ? date('Y-m-d', strtotime($job->deadline)) : '') }}">
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Job Description *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $job->description) }}</textarea>
            </div>
            
            <div class="mb-3">
                <label for="requirements" class="form-label">Requirements</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="5">{{ old('requirements', $job->requirements) }}</textarea>
            </div>
            
            <div class="alert alert-warning">
                <small>Note: After editing, this job will return to "pending" status and require admin approval again.</small>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Update Job</button>
            </div>
        </form>
    </div>
</div>
@endsection
