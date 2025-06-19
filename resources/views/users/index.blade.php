@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- Judul & Tombol Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Pengguna</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1 text-white"></i> Tambah
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Tabel Pengguna --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Telepon</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Admin</span>
                                        @break
                                    @case('perusahaan')
                                        <span class="badge bg-primary">Perusahaan</span>
                                        @break
                                    @default
                                        <span class="badge bg-success">Mahasiswa</span>
                                @endswitch
                            </td>
                            <td>{{ $user->no_hp ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-light" title="Lihat">
                                        <i class="fas fa-eye text-info"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-light" title="Ubah">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light" title="Hapus">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-end">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Jika perlu, tambahkan skrip kustom di sini
</script>
@endsection
