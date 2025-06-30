@extends('layouts.admin')

@section('content')
<!-- Modal Tambah Artikel (langsung tampil) -->
<div class="modal fade show" id="createArtikelModal" tabindex="-1" aria-labelledby="createArtikelModalLabel" aria-modal="true" style="display: block; background: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createArtikelModalLabel">Tambah Artikel</h5>
          <a href="{{ url()->previous() }}" class="btn-close"></a>
        </div>
        <div class="modal-body">
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="mb-3">
            <label for="judul_artikel" class="form-label">Judul Artikel</label>
            <input type="text" class="form-control" id="judul_artikel" name="judul_artikel" value="{{ old('judul_artikel') }}" required>
          </div>

          <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
          </div>

          <div class="mb-3">
            <label for="gambar" class="form-label">Gambar</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.svg" required>
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
{{-- SweetAlert sudah tidak diperlukan di create modal --}}
@endsection
