@extends('layouts.admin')

@section('content')
<div class="container">
    {{-- Modal Edit Kategori --}}
    <div class="modal fade show" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel" aria-modal="true" style="display: block;">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori Pekerjaan</h5>
                    <a href="{{ route('category-job.index') }}" class="btn-close"></a>
                </div>
                <div class="modal-body">
                    {{-- Pesan sukses --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- Error --}}
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

                    {{-- Form --}}
                    <form action="{{ route('category-job.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_category" class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_category" class="form-control" id="nama_category"
                                   value="{{ old('nama_category', $category->nama_category) }}" required>
                            <small class="text-muted">Masukkan nama kategori pekerjaan (max: 100 karakter)</small>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('category-job.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Notifikasi SweetAlert sukses
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
