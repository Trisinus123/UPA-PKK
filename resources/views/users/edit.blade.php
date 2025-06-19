@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit User</h1>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (leave blank to keep current)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required onchange="toggleRoleFields()">
                <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="perusahaan" {{ $user->role == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <!-- Mahasiswa Fields -->
        <div id="mahasiswa-fields" style="display: {{ $user->role == 'mahasiswa' ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="nim" name="nim" 
                       value="{{ $user->mahasiswaProfile->nim ?? '' }}">
            </div>
        </div>

        <!-- Perusahaan Fields -->
        <div id="perusahaan-fields" style="display: {{ $user->role == 'perusahaan' ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
                <input type="text" class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" 
                       value="{{ $user->perusahaanProfile->alamat_perusahaan ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="text" class="form-control" id="website" name="website" 
                       value="{{ $user->perusahaanProfile->website ?? '' }}">
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi">{{ $user->perusahaanProfile->deskripsi ?? '' }}</textarea>
            </div>
        </div>

        <div class="mb-3">
            <label for="no_hp" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $user->no_hp }}">
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>

<script>
    function toggleRoleFields() {
        const role = document.getElementById('role').value;
        
        // Hide all role-specific fields first
        document.getElementById('mahasiswa-fields').style.display = 'none';
        document.getElementById('perusahaan-fields').style.display = 'none';
        
        // Show fields based on selected role
        if (role === 'mahasiswa') {
            document.getElementById('mahasiswa-fields').style.display = 'block';
        } else if (role === 'perusahaan') {
            document.getElementById('perusahaan-fields').style.display = 'block';
        }
    }

    // Initialize fields on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleRoleFields();
    });
</script>
@endsection