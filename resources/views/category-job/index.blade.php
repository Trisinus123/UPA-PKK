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
        <h4 class="mb-0">Data Kategori Pekerjaan</h4>
        <a href="{{ route('category-job.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1 text-white"></i> Tambah
        </a>

    </div>

    <div class="card">
        <div class="card-body">
            @if(count($categories) > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            {{-- <th>Jumlah Lowongan</th> --}}
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $startNumber = ($categories->currentPage() - 1) * $categories->perPage() + 1;
                        @endphp
                        @foreach($categories as $index => $category)
                        <tr>
                            <td>{{ $startNumber + $index }}</td>
                            <td>{{ $category->nama_category }}</td>
                            <td>
                                <a href="{{ route('category-job.edit', $category->id) }}" class="btn btn-sm btn-warning"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('category-job.destroy', $category->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <a href="{{ route('category-job.show', $category->id) }}" class="btn btn-sm btn-info"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                Belum ada data kategori pekerjaan.
            </div>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-end">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Inisialisasi tooltip
        $('[title]').tooltip();

        // SweetAlert untuk konfirmasi delete
        $('.btn-danger').click(function (e) {
            e.preventDefault();
            var form = $(this).closest('form');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus kategori ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

</script>
@endsection
