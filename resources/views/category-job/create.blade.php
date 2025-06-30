@extends('layouts.admin')

@section('content')
<!-- Modal Tambah Kategori Pekerjaan (langsung tampil) -->
<div class="modal fade show" id="createKategoriModal" tabindex="-1" aria-labelledby="createKategoriModalLabel" aria-modal="true" style="display: block; background: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('category-job.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createKategoriModalLabel">Tambah Kategori Pekerjaan</h5>
          <a href="{{ url()->previous() }}" class="btn-close"></a>
        </div>
        <div class="modal-body">
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if ($errors->any())
            <div class="alert alert-danger">
              <strong>Terjadi kesalahan:</strong>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="mb-3">
            <label for="nama_category" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_category" class="form-control" id="nama_category" value="{{ old('nama_category') }}" required>
            <small class="text-muted">Masukkan nama kategori pekerjaan (max: 100 karakter)</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
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
