@extends('mahasiswa.mahasiswa')
@section('content')
<div class="container">
    <h2>Profil Mahasiswa</h2>
    <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
    <p><strong>CV Saat Ini:</strong>
        @if($mahasiswa->cv)
            <a href="#" target="_blank">Lihat CV</a>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        @else
            Belum ada
        @endif
    </p>
    <form action="{{ route('mahasiswa.upload_cv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="cv" class="form-label">Upload CV (PDF)</label>
            <input class="form-control" type="file" name="cv" accept="application/pdf" required>
        </div>
        <button class="btn btn-primary" type="submit">Upload</button>
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
    </form>
</div>
@endsection
