@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Data Perusahaan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('data-perusahaan.update', $perusahaan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')


        <div class="mb-3">
            <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control" id="nama_perusahaan" value="{{ $perusahaan->nama_perusahaan }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
            <textarea name="alamat_perusahaan" class="form-control" id="alamat_perusahaan" rows="3" required>{{ $perusahaan->alamat_perusahaan }}</textarea>
        </div>

        <div class="mb-3">
            <label for="website" class="form-label">Website</label>
            <input type="url" name="website" class="form-control" id="website" value="{{ $perusahaan->website }}">
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" id="deskripsi" rows="4">{{ $perusahaan->deskripsi }}</textarea>
        </div>

        {{-- status_perusahaan --}}
        <div class="mb-3">
            <label for="status_perusahaan" class="form-label">Status Perusahaan</label>
            <select name="status_perusahaan" class="form-select" id="status_perusahaan" required>
                <option value="" disabled>Pilih Status</option>
                <option value="1" {{ $perusahaan->status_perusahaan == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $perusahaan->status_perusahaan == 0 ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto Perusahaan (Opsional)</label>
            <input type="file" name="foto" class="form-control" id="foto" accept="image/*">
            @if($perusahaan->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $perusahaan->foto) }}" alt="Foto Perusahaan" width="150">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('data-perusahaan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
