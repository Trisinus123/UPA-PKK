@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Tambah Data Perusahaan</h2>

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

    <form action="{{ route('data-perusahaan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf


        <div class="mb-3">
            <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control" id="nama_perusahaan" required>
        </div>

        <div class="mb-3">
            <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
            <textarea name="alamat_perusahaan" class="form-control" id="alamat_perusahaan" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="website" class="form-label">Website</label>
            <input type="url" name="website" class="form-control" id="website">
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" id="deskripsi" rows="4"></textarea>
        </div>

        {{-- status_perusahaan --}}
        <div class="mb-3">
            <label for="status_perusahaan" class="form-label">Status Perusahaan</label>
            <select name="status_perusahaan" class="form-select" id="status_perusahaan" required>
                <option value="" disabled selected>Pilih Status</option>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto Perusahaan</label>
            <input type="file" name="foto" class="form-control" id="foto" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('data-perusahaan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
