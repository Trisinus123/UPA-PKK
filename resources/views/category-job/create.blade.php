@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Tambah Kategori Pekerjaan</h2>

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

    <form action="{{ route('category-job.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama_category" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_category" class="form-control" id="nama_category" required>
            <small class="text-muted">Masukkan nama kategori pekerjaan (max: 100 karakter)</small>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('category-job.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Anda bisa menambahkan script khusus untuk halaman ini jika diperlukan
    $(document).ready(function() {
        // Contoh script untuk menampilkan sweetalert saat form berhasil disubmit
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    });
</script>
@endsection
