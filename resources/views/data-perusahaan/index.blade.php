@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Perusahaan</h4>
        <a href="{{ route('data-perusahaan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1 text-white"></i> Tambah
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if(count($perusahaans) > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 100px;">Foto</th>
                            <th>Nama Perusahaan</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perusahaans as $perusahaan)
                        <tr>
                            <td>
                                @if($perusahaan->foto)
                                <img src="{{ asset('storage/' . $perusahaan->foto) }}" alt="Foto Perusahaan"
                                    class="img-thumbnail" width="80">
                                @else
                                <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </td>
                            <td>{{ $perusahaan->user->name }}</td>
                            <td>{{ Str::limit($perusahaan->deskripsi, 60) }}</td>
                            <td>
                                @if($perusahaan->status_perusahaan == 1)
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('data-perusahaan.edit', $perusahaan->id) }}"
                                        class="btn btn-sm btn-light" title="Edit">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <form action="{{ route('data-perusahaan.destroy', $perusahaan->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light" title="Hapus">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info mb-0">Belum ada data perusahaan yang tersedia.</div>
            @endif
        </div>
    </div>
</div>
@endsection
