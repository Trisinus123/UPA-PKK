@extends('layouts.admin')

@section('content')
<!-- Modal Edit User (langsung tampil) -->
<div class="modal fade show" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-modal="true" style="display: block;">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit User – {{ $user->name }}</h5>
          <a href="{{ url()->previous() }}" class="btn-close"></a>
        </div>
        <div class="modal-body">
          {{-- Name --}}
          <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input
              type="text"
              id="name"
              name="name"
              class="form-control"
              value="{{ old('name', $user->name) }}"
              required
            >
          </div>

          {{-- Email --}}
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              class="form-control"
              value="{{ old('email', $user->email) }}"
              required
            >
          </div>

          {{-- Password --}}
          <div class="mb-3">
            <label for="password" class="form-label">
              Password <small class="text-muted">(kosongkan jika tidak diubah)</small>
            </label>
            <input
              type="password"
              id="password"
              name="password"
              class="form-control"
            >
          </div>

          {{-- Password Confirmation --}}
          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input
              type="password"
              id="password_confirmation"
              name="password_confirmation"
              class="form-control"
            >
          </div>

          {{-- Role --}}
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select
              id="role"
              name="role"
              class="form-select"
              onchange="toggleRoleFields()"
              required
            >
              <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
              <option value="perusahaan" {{ $user->role == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
              <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
          </div>

          {{-- Mahasiswa Fields --}}
          <div id="mahasiswa-fields" style="display: {{ $user->role == 'mahasiswa' ? 'block' : 'none' }};">
            <div class="mb-3">
              <label for="nim" class="form-label">NIM</label>
              <input
                type="text"
                id="nim"
                name="nim"
                class="form-control"
                value="{{ old('nim', $user->mahasiswaProfile->nim ?? '') }}"
              >
            </div>
          </div>

          {{-- Perusahaan Fields --}}
          <div id="perusahaan-fields" style="display: {{ $user->role == 'perusahaan' ? 'block' : 'none' }};">
            <div class="mb-3">
              <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
              <input
                type="text"
                id="alamat_perusahaan"
                name="alamat_perusahaan"
                class="form-control"
                value="{{ old('alamat_perusahaan', $user->perusahaanProfile->alamat_perusahaan ?? '') }}"
              >
            </div>
            <div class="mb-3">
              <label for="no_hp" class="form-label">No HP</label>
              <input
                type="text"
                id="no_hp"
                name="no_hp"
                class="form-control"
                value="{{ old('no_hp', $user->no_hp) }}"
              >
            </div>
            <div class="mb-3">
              <label for="website" class="form-label">Website</label>
              <input
                type="text"
                id="website"
                name="website"
                class="form-control"
                value="{{ old('website', $user->perusahaanProfile->website ?? '') }}"
              >
            </div>
            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea
                id="deskripsi"
                name="deskripsi"
                class="form-control"
                rows="3"
              >{{ old('deskripsi', $user->perusahaanProfile->deskripsi ?? '') }}</textarea>
            </div>

            {{-- Foto Profil --}}
            <div class="mb-3">
              <label for="foto" class="form-label">Profile</label>
              <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
              @if(!empty($user->perusahaanProfile->foto))
                <div class="mt-2">
                  <img src="{{ asset('storage/' . $user->perusahaanProfile->foto) }}" alt="Foto Profil" width="100">
                </div>
              @endif
            </div>
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

{{-- Script untuk toggle role-dependent fields --}}
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
