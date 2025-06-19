@extends('layouts.perusahaan')

@section('title', 'Kelola Pekerjaan')

@section('content')
@component('components.breadcrumb')
@slot('title')Kelola Pekerjaan @endslot
@slot('li_1') Dashboard @endslot
@slot('li_2') Pekerjaan @endslot
@endcomponent

<div class="row">
    <div class="col-lg-">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Daftar Pekerjaan</h5>
                <div>
                    <a href="{{ route('jobs.create') }}" class="btn text-white" style="background-color: rgb(43, 95, 179);">
                        <i class="ri-add-line align-bottom"></i> Tambah
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($jobs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 120px;">Gambar</th>
                                <th scope="col">Judul Pekerjaaan</th>
                                <th scope="col">Kategori Pekerjaan</th>
                                <th scope="col">Lokasi</th>
                                <th scope="col">Gaji</th>
                                <th scope="col">Status</th>
                                <th scope="col">Dibuat</th>
                                <th scope="col" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobs as $job)
                            <tr>
                                <td>
                                    @if($job->gambar)
                                    <img src="{{ asset('storage/'.$job->gambar) }}" alt="{{ $job->title }}"
                                        class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                                    @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                        style="width: 100px; height: 60px;">
                                        <i class="ri-image-line text-muted" style="font-size: 24px;"></i>
                                    </div>
                                    @endif
                                </td>

                                <td><strong>{{ $job->title }}</strong></td>
                                <td>
                                    @if($job->categoryJob)
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $job->categoryJob->nama_category }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $job->location }}</td>
                                <td>{{ $job->formatted_salary_range }}</td>
                                <td>
                                    @if($job->status == 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Menunggu</span>
                                    @elseif($job->status == 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success">Disetujui</span>
                                    @elseif($job->status == 'rejected')
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $job->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        {{-- View --}}
                                        <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-info"
                                            title="Lihat">
                                            <i class="ri-eye-line"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-warning"
                                            title="Ubah">
                                            <i class="ri-edit-2-line"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('jobs.destroy', $job->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Apakah Anda yakin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                style="padding: 6px 10px;">
                                                <i class="ri-delete-bin-line"></i>
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
                <div class="alert alert-info bg-info bg-opacity-10 border-0 text-center py-4">
                    <i class="ri-information-line fs-3 text-info"></i>
                    <h5 class="text-info mt-2">Tidak Ada Pekerjaan</h5>
                    <p class="mb-0">Anda belum memposting pekerjaan apapun. <a href="{{ route('jobs.create') }}"
                            class="text-decoration-underline">Buat lowongan pekerjaan pertama Anda</a>.</p>
                </div>
                @endif
            </div>

            {{-- Pagination Footer --}}
            @if(method_exists($jobs, 'links'))
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if($jobs->total() > 0)
                        Menampilkan {{ $jobs->firstItem() }} sampai {{ $jobs->lastItem() }} dari {{ $jobs->total() }} data
                        @else
                        Tidak ada data yang ditemukan
                        @endif
                    </div>
                    <div>
                        {{ $jobs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Inisialisasi tooltip
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
