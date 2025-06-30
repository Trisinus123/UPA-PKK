@extends('layouts.admin')

@section('content')
<!-- Modal Tambah User Langsung Muncul -->
<div class="modal fade show" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-modal="true" style="display: block;">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createUserModalLabel">Tambah User</h5>
          <a href="{{ url()->previous() }}" class="btn-close"></a>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>

          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
          </div>

          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required onchange="toggleRoleFields()">
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="perusahaan" {{ old('role') == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
          </div>

          <div id="mahasiswa-fields" style="display: none;">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="nim" name="nim" value="{{ old('nim') }}">
            </div>
          </div>

          <div id="perusahaan-fields" style="display: none;">
            <div class="mb-3">
                <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
                <input type="text" class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" value="{{ old('alamat_perusahaan') }}">
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}">
            </div>
            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="text" class="form-control" id="website" name="website" value="{{ old('website') }}">
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Profile</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi">{{ old('deskripsi') }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Create User</button>
          <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    function toggleRoleFields() {
        const role = document.getElementById('role').value;
        document.getElementById('mahasiswa-fields').style.display = 'none';
        document.getElementById('perusahaan-fields').style.display = 'none';
        if (role === 'mahasiswa') {
            document.getElementById('mahasiswa-fields').style.display = 'block';
        } else if (role === 'perusahaan') {
            document.getElementById('perusahaan-fields').style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', toggleRoleFields);
</script>
@endsection
