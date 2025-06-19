@extends('layouts.perusahaan')

@section('title', 'Company Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header text-white d-flex justify-content-between align-items-center"
                style="background-color: #2e86cf;">
                <h4 class="mb-0">Profil Perusahaan</h4>
                <button class="btn btn-sm btn-light" id="editBtn" onclick="toggleEditMode()">Edit Data</button>
            </div>

            <div class="card-body">
                <!-- View Mode -->
                <div id="viewMode">
                    <div class="text-center mb-4">
                        @if(isset($profile) && $profile->foto)
                        <img src="{{ asset('storage/' . $profile->foto) }}" alt="Current Logo" class="img-thumbnail"
                            style="height: 100px;">

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
                                <div class="col-md-4 fw-bold">Nama Perusahaan:</div>
                                <div class="col-md-8">{{ Auth::user()->name ?? '-' }}</div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Alamat:</div>
                                <div class="col-md-8">{{ $profile->alamat_perusahaan ?? '-' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Website:</div>
                                <div class="col-md-8">
                                    @if(!empty($profile->website))
                                    <a href="{{ $profile->website }}" target="_blank">{{ $profile->website }}</a>
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 fw-bold">Deskripsi:</div>
                                <div class="col-md-8">{{ $profile->deskripsi ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Mode -->
                <div id="editMode" style="display: none;">
                    <form method="POST" action="{{ route('perusahaan.profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                id="nama_perusahaan" name="nama_perusahaan"
                                value="{{ old('nama_perusahaan', Auth::user()->name ?? '') }}" required>

                            @error('nama_perusahaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
                            <textarea class="form-control @error('alamat_perusahaan') is-invalid @enderror"
                                id="alamat_perusahaan" name="alamat_perusahaan" rows="3"
                                required>{{ old('alamat_perusahaan', $profile->alamat_perusahaan ?? '') }}</textarea>
                            @error('alamat_perusahaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website"
                                name="website" value="{{ old('website', $profile->website ?? '') }}">
                            @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="5">{{ old('deskripsi', $profile->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Logo / Foto Perusahaan</label>
                            @if(isset($profile) && $profile->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $profile->foto) }}" alt="Current Logo"
                                    class="img-thumbnail" style="height: 100px;">

                            </div>
                            @endif
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto"
                                name="foto">
                            <small class="text-muted">Upload JPG, PNG atau GIF (Maks. 2MB)</small>
                            @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
