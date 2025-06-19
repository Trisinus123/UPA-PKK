@extends('mahasiswa.mahasiswa')

@section('content')
<div class="container py-4" style="margin-top: 80px;">
    <h4 class="mb-4 fw-semibold">Perusahaan</h4>

    <div class="row">
        @forelse($companies as $company)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                @if($company->perusahaanProfile && $company->perusahaanProfile->foto)
                                    <img src="{{ asset('storage/' . $company->perusahaanProfile->foto) }}"
                                         alt="{{ $company->name }}"
                                         class="rounded-circle border"
                                         style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-circle border d-flex align-items-center justify-content-center"
                                         style="width: 64px; height: 64px;">
                                        <i class="bi bi-building fs-4 text-primary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title mb-1 fw-semibold">{{ $company->name }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-geo-alt-fill me-1"></i>
                                    {{ $company->perusahaanProfile ? $company->perusahaanProfile->alamat_perusahaan ?? 'Alamat tidak tersedia' : 'Alamat tidak tersedia' }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-soft-success text-success">
                                {{ $company->jobs_count }} Lowongan Aktif
                            </span>
                        </div>

                        <p class="card-text text-muted small" style="height: 60px; overflow: hidden; text-overflow: ellipsis;">
                            {{ $company->perusahaanProfile ? Str::limit($company->perusahaanProfile->deskripsi ?? 'Tidak ada deskripsi', 100) : 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0 px-3 py-2">
                        <a href="{{ route('company.profile', $company->id) }}" class="btn btn-outline-info btn-sm w-10">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Tidak ada perusahaan yang tersedia saat ini.
                </div>
            </div>
        @endforelse
    </div>
</div>
<footer class="py-5 position-relative mt-5" style="background-color: #0b3558; color: white;">
        <div class="container">
            <div class="row justify-content-between">
                <!-- Kolom 1 -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold" style="color: #ccc;">UPA <span style="color: red;">PKK</span></h5>
                    <p class="mb-1 mt-3">Portal Polinema Career Center</p>
                    <p>Direktorat Pengembangan Karier dan Mahasiswa<br>Politeknik Negeri Malang</p>
                </div>

                <!-- Kolom 2 -->
                <div class="col-md-3 mb-4">
                    <h6 class="text-white fw-semibold fs-5">Pranala Terkait</h6>
                    <ul class="list-unstyled mt-3">
                        <li><a href="#" class="text-white text-decoration-none">Polinema Official</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Prasetya Online</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Polinema TV</a></li>
                    </ul>
                </div>

                <!-- Kolom 3 -->
                <div class="col-md-3 mb-4">
                    <h6 class="text-white fw-semibold fs-5">Umpan Balik</h6>
                    <ul class="list-unstyled mt-3">
                        <li><a href="#" class="text-white text-decoration-none">Polinema Care</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Unit Pengendalian Gratifikasi</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Whistleblowing System</a></li>
                    </ul>
                </div>


                <hr style="border-color: white);">

                <div class="text-center mt-3">
                    <p class="mb-0">copyright @UPAPKK 2025 | Trisinus Gulo</p>
                </div>
            </div>
    </footer>
@endsection
