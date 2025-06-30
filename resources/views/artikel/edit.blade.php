@extends('layouts.admin')

@section('content')
<!-- Modal Edit Artikel (langsung tampil) -->
<div class="modal fade show" id="editArtikelModal" tabindex="-1" aria-labelledby="editArtikelModalLabel" aria-modal="true" style="display: block; background: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editArtikelModalLabel">Edit Artikel – {{ $artikel->judul_artikel }}</h5>
          <a href="{{ url()->previous() }}" class="btn-close"></a>
        </div>
        <div class="modal-body">
          @if(session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
          @endif

          <div class="mb-3">
              <label for="judul_artikel" class="form-label">Judul Artikel</label>
              <input type="text" class="form-control" id="judul_artikel" name="judul_artikel" value="{{ old('judul_artikel', $artikel->judul_artikel) }}" required>
          </div>

          <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $artikel->deskripsi) }}</textarea>
          </div>

          <div class="mb-3">
              <label for="gambar" class="form-label">Gambar</label>
              <input type="file" class="form-control" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.svg">
              @if($artikel->gambar)
                  <div class="mt-2">
                      <img src="{{ asset('storage/gambar_artikel/' . $artikel->gambar) }}" alt="Gambar Sebelumnya" width="150" class="img-thumbnail">
                  </div>
              @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
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
  // Tidak ada skrip tambahan untuk SweetAlert delete di edit modal
</script>
@endsection
