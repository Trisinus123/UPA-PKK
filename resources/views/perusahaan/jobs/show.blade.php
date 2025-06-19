@extends('layouts.perusahaan')

@section('title', $job->title . ' - Detail Lowongan')

@section('content')
<div class="card mx-auto my-4" style="max-width: 900px;">
    <div class="card-header text-black d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Detail Lowongan</h4>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="fw-bold">{{ $job->title }}</h2>
                <div class="d-flex flex-wrap gap-3 mb-3 align-items-center">
                    <span class="badge bg-secondary">
                        <i class="bi bi-geo-alt-fill me-1"></i>{{ $job->location }}
                    </span>

                    @if($job->salary_min || $job->salary_max)
                        <span class="badge bg-info text-dark">
                            <i class="bi bi-currency-dollar me-1"></i>{{ $job->formatted_salary_range }}
                        </span>
                    @endif

                    @if($job->status == 'pending')
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-clock-history me-1"></i>Menunggu
                        </span>
                    @elseif($job->status == 'approved')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Disetujui
                        </span>
                    @elseif($job->status == 'rejected')
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle-fill me-1"></i>Ditolak
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-md-4 text-md-end">
                <span class="badge bg-primary">
                    <i class="bi bi-tags-fill me-1"></i>{{ $job->categoryJob->nama_category ?? 'Tidak ada kategori' }}
                </span>
            </div>

            <div class="col-12 mt-3">
                @if($job->gambar)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $job->gambar) }}" 
                             alt="Gambar Lowongan" 
                             class="img-fluid rounded shadow-sm mx-auto d-block" 
                             style="max-width: 400px; max-height: 500px; object-fit: cover;">
                    </div>
                @else
                    <p class="text-muted">Diposting pada: {{ $job->created_at->format('d M Y') }}</p>
                    @if($job->deadline)
                        <p class="text-muted">Batas Waktu: {{ date('d M Y', strtotime($job->deadline)) }}</p>
                    @endif
                @endif
            </div>
        </div>

        <!-- Deskripsi Pekerjaan dengan Format Poin-poin -->
        <div class="mb-4">
            <h5>Deskripsi Pekerjaan</h5>
            <div class="p-3 bg-light rounded">
                @php
                    // Membersihkan dan memformat deskripsi
                    $descriptionPoints = array_filter(
                        explode("\n", str_replace("\r", "", $job->description)),
                        function($point) {
                            return trim($point) !== '';
                        }
                    );
                @endphp

                @foreach($descriptionPoints as $point)
                <div class="d-flex mb-2">
                    <div class="me-2">•</div>
                    <div>{{ trim($point) }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Kualifikasi/Persyaratan dengan Format Poin-poin -->
        @if($job->requirements)
        <div class="mb-4">
            <h5>Persyaratan</h5>
            <div class="p-3 bg-light rounded">
                @php
                    // Membersihkan dan memformat persyaratan
                    $requirementPoints = array_filter(
                        explode("\n", str_replace("\r", "", $job->requirements)),
                        function($point) {
                            return trim($point) !== '';
                        }
                    );
                @endphp

                @foreach($requirementPoints as $point)
                <div class="d-flex mb-2">
                    <div class="me-2">•</div>
                    <div>{{ trim($point) }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <div>
                <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm text-white" style="background-color: #0b3558;">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah kamu yakin ingin menghapus lowongan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</div>
@endsection
