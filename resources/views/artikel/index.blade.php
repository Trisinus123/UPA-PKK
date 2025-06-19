@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Artikel</h4>
        <a href="{{ route('artikel.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1 text-white"></i> Tambah
        </a>
    </div>
        <div class="card-body">
            @if(count($artikel) > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 120px;">Gambar</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($artikel as $item)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/gambar_artikel/' . $item->gambar) }}" alt="Gambar Artikel"
                                    class="img-thumbnail" width="100">
                            </td>
                            <td>{{ $item->judul_artikel }}</td>
                            <td>{{ Str::limit($item->deskripsi, 60) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('artikel.show', $item->id) }}" class="btn btn-sm btn-light"
                                        title="Lihat">
                                        <i class="fas fa-eye text-info"></i>
                                    </a>
                                    <a href="{{ route('artikel.edit', $item->id) }}" class="btn btn-sm btn-light"
                                        title="Edit">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <form action="{{ route('artikel.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
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
            <div class="alert alert-info mb-0">Belum ada artikel yang tersedia.</div>
            @endif
        </div>
    </div>
</div>
@endsection
