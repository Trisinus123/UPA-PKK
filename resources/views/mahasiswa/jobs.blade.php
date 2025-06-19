{{-- Layout Utama --}}
@extends('mahasiswa.mahasiswa')

@section('content')
@php
    $user = Auth::user();
    $mahasiswa = \App\Models\MahasiswaProfile::where('user_id', $user->id)->first();
    $hasCV = $mahasiswa && $mahasiswa->cv;
@endphp

<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Lowongan Kerja</h4>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown"
                aria-expanded="false">
                Urutkan: {{ $sort == 'newest' ? 'Terbaru' : 'Terlama' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                <li><a class="dropdown-item {{ $sort == 'newest' ? 'active' : '' }}"
                    href="{{ route('jobs.browse', ['sort' => 'newest']) }}">Terbaru</a></li>
                <li><a class="dropdown-item {{ $sort == 'oldest' ? 'active' : '' }}"
                    href="{{ route('jobs.browse', ['sort' => 'oldest']) }}">Terlama</a></li>
            </ul>
        </div>
    </div>

    @if(count($jobs) > 0)
    <div class="row">
        @foreach($jobs as $job)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($job->gambar)
                    <img src="{{ asset('storage/'.$job->gambar) }}" class="card-img-top object-fit-cover" style="height: 150px;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="ri-briefcase-line fs-1 text-muted"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $job->title }}</h5>
                    <p class="text-muted">{{ $job->company->name }} • {{ $job->location }}</p>
                    <p class="text-primary">{{ $job->formatted_salary_range }}</p>
                    <p class="mb-2"><small class="text-muted">Diposting {{ $job->created_at->diffForHumans() }}</small></p>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                        @if(in_array($job->id, $appliedJobIds))
                            <button class="btn btn-sm btn-outline-secondary" disabled>Sudah Dilamar</button>
                        @else
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#applyJobModal-{{ $job->id }}">
                                Lamar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Lamar Pekerjaan --}}
        <div class="modal fade" id="applyJobModal-{{ $job->id }}" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lamar Pekerjaan: {{ $job->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('applyJob.store', $job->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            @foreach($job->documentRequirements as $requirement)
                                <div class="mb-3">
                                    <label for="document-{{ $requirement->id }}" class="form-label">
                                        {{ $requirement->document_name }}
                                        @if($requirement->is_required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <input type="file"
                                           class="form-control"
                                           id="document-{{ $requirement->id }}"
                                           name="documents[{{ $requirement->id }}]"
                                           accept=".pdf"
                                           @if($requirement->is_required) required @endif>
                                    @if($requirement->description)
                                        <small class="text-muted">{{ $requirement->description }}</small>
                                    @endif
                                </div>
                            @endforeach

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Kirim Lamaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-info">Belum ada lowongan yang tersedia saat ini.</div>
    @endif
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
