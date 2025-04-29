@extends('mahasiswa.mahasiswa')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Perusahaan</h1>
    
    <div class="row">
        @forelse($companies as $company)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                @if($company->perusahaanProfile && $company->perusahaanProfile->foto)
                                    <img src="{{ asset('uploads/perusahaan/' . $company->perusahaanProfile->foto) }}" 
                                         alt="{{ $company->name }}" 
                                         class="rounded-circle border" 
                                         style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-circle border d-flex align-items-center justify-content-center" 
                                         style="width: 64px; height: 64px;">
                                        <i class="bi bi-building fs-4"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title mb-1">{{ $company->name }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $company->perusahaanProfile ? $company->perusahaanProfile->alamat ?? 'Alamat tidak tersedia' : 'Alamat tidak tersedia' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <span class="badge bg-primary">{{ $company->jobs_count }} Lowongan Aktif</span>
                        </div>
                        
                        <p class="card-text text-muted small" style="height: 60px; overflow: hidden; text-overflow: ellipsis;">
                            {{ $company->perusahaanProfile ? Str::limit($company->perusahaanProfile->deskripsi ?? 'Tidak ada deskripsi', 100) : 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('company.profile', $company->id) }}" class="btn btn-outline-primary w-100">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Tidak ada perusahaan yang tersedia saat ini.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
