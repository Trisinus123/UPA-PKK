@extends('layouts.perusahaan')

@section('title', 'Create Job Listing')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Create New Job</h4>
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

        <form action="{{ route('jobs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Job Title *</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Location *</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="salary_min" class="form-label">Minimum Salary (Rp)</label>
                    <input type="number" class="form-control" id="salary_min" name="salary_min" value="{{ old('salary_min') }}" min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="salary_max" class="form-label">Maximum Salary (Rp)</label>
                    <input type="number" class="form-control" id="salary_max" name="salary_max" value="{{ old('salary_max') }}" min="0">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="deadline" class="form-label">Application Deadline</label>
                <input type="date" class="form-control" id="deadline" name="deadline" value="{{ old('deadline') }}">
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Job Description *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
            </div>
            
            <div class="mb-3">
                <label for="requirements" class="form-label">Requirements</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="5">{{ old('requirements') }}</textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Submit Job</button>
            </div>
        </form>
    </div>
</div>
@endsection
