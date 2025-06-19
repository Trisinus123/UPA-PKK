@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Kategori Pekerjaan</h4>
        <a href="{{ route('category-job.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%;">ID Kategori</th>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <th>Nama Kategori</th>
                            <td>{{ $category->nama_category }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Lowongan</th>
                            <td>{{ $category->jobs_count ?? 0 }} lowongan</td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $category->created_at->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Diupdate Pada</th>
                            <td>{{ $category->updated_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <h5 class="mb-3">Lowongan Terkait</h5>
                
                @if($category->jobs && $category->jobs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Judul Lowongan</th>
                                <th>Perusahaan</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->jobs as $job)
                            <tr>
                                <td>{{ $job->judul }}</td>
                                <td>{{ $job->company->nama_perusahaan ?? '-' }}</td>
                                <td>{{ $job->lokasi }}</td>
                                <td>
                                    <span class="badge bg-{{ $job->status ? 'success' : 'secondary' }}">
                                        {{ $job->status ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    Tidak ada lowongan yang terkait dengan kategori ini.
                </div>
                @endif
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('category-job.edit', $category->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <form action="{{ route('category-job.destroy', $category->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                    <i class="fas fa-trash-alt me-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // SweetAlert untuk konfirmasi delete
        $('.btn-danger').click(function(e) {
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