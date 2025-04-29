@extends('layouts.perusahaan')

@section('title', 'Company Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Company Profile</h4>
                <button class="btn btn-sm btn-light" id="editBtn" onclick="toggleEditMode()">Edit Data</button>
            </div>
            <div class="card-body">
                <!-- View Mode -->
                <div id="viewMode">
                    <div class="text-center mb-4">
                        @if(isset($profile) && $profile->foto)
                            <img src="{{ asset('uploads/perusahaan/' . $profile->foto) }}" 
                                class="img-thumbnail rounded-circle" style="max-height: 200px; max-width: 200px;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                style="width: 200px; height: 200px;">
                                <span class="text-secondary fs-1">{{ Auth::user()->name[0] }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Company Name:</div>
                                <div class="col-md-8">
                                    {{ Auth::user()->name }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Address:</div>
                                <div class="col-md-8">{{ $profile->alamat ?? '-' }}</div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 fw-bold">Description:</div>
                                <div class="col-md-8">{{ $profile->deskripsi ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Edit Mode (Hidden by Default) -->
                <div id="editMode" style="display: none;">
                    <form method="POST" action="{{ route('perusahaan.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Company Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" 
                                value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Address</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                id="alamat" name="alamat" rows="3">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Description</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                id="deskripsi" name="deskripsi" rows="5">{{ old('deskripsi', $profile->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="foto" class="form-label">Company Logo/Photo</label>
                            @if(isset($profile) && $profile->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('uploads/perusahaan/' . $profile->foto) }}" 
                                        alt="Current Logo" class="img-thumbnail" style="height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto">
                            <small class="text-muted">Upload JPG, PNG or GIF (Max. 2MB)</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Cancel</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleEditMode() {
        const viewMode = document.getElementById('viewMode');
        const editMode = document.getElementById('editMode');
        const editBtn = document.getElementById('editBtn');

        if (viewMode.style.display !== 'none') {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
            editBtn.style.display = 'none';
        } else {
            viewMode.style.display = 'block';
            editMode.style.display = 'none';
            editBtn.style.display = 'inline-block';
        }
    }
</script>
@endsection
