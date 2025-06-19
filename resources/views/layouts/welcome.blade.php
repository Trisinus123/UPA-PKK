@extends('layouts.master-without-nav')
@section('title') UPA landing @endsection

@section('css')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('body')
<body data-bs-spy="scroll" data-bs-target="#navbar-example">
@endsection

@section('content')

{{-- Navbar --}}
 <nav class="navbar navbar-expand-lg navbar-landing fixed-top shadow-sm py-3" id="navbar" style="background-color: #ffffff;">
    <div class="container-fluid custom-container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="UPA PKK Logo" height="60" class="me-2">
            <span class="fs-3 fw-bold text-primary">UPA <span class="text-danger">PKK</span></span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <i class="mdi mdi-menu fs-2 text-primary"></i>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto text-center">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold fs-4 {{ Request::is('/') ? 'text-primary' : '' }}"
                            href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold fs-4 {{ Request::is('about') ? 'text-primary' : '' }}"
                            href="{{ url('/about') }}">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold fs-4 {{ Request::is('contact') ? 'text-primary' : '' }}"
                            href="{{ url('/contact') }}">Kontak</a>
                    </li>
                </ul>
            </div>

            <!-- Login Button -->
            <div class="d-flex justify-content-center">
                <a href="/login" class="btn btn-primary rounded-pill px-4 py-2 fs-5 fw-bold">
                    <i class="ri-user-3-line me-2 fs-5 align-middle"></i>
                    <span class="align-middle">Login / Register</span>
                </a>
            </div>
        </div>
    </div>
</nav>
{{-- Hero Section --}}
<section class="section job-hero-section bg-light pb-0" id="hero">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6">
                <div>
                    <h1 class="display-9 fw-semibold text-capitalize mb-3 lh-base">
                        PLATFORM INFORMASI LOWONGAN PEKERJAAN UPA PKK UNTUK MAHASISWA POLITEKNIK NEGERI MALANG
                    </h1>
                    <p class="lead text-muted lh-base mb-4">
                        Unit Pengembangan Akademik dan Pusat Karir (UPA PKK) Adalah unit pengembangan karir mahasiswa yang bertujuan memberikan layanan pengembangan karir sejak dini supaya mahasiswa dapat membuat Keputusan akademis yang menentukan keseuksesan karirnya
                    </p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="position-relative home-img text-center mt-5 mt-lg-0">
                    <img src="{{ asset('build/images/illustration.png') }}" alt="Ilustrasi Mahasiswa"
                        style="width: 600%; max-width: 600px;" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mitra Perusahaan --}}
<section class="section bg-light pt-4 pb-2" id="mitra">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="mb-2 ff-secondary fw-semibold text-capitalize lh-base fs-3">Mitra Perusahaan</h1>
        <div style="width: 60px; height: 6px; background-color: #a7aab3; margin: 0 auto; border-radius: 3px;">
                </div>
            </div>

        <div class="row">
            @foreach($perusahaan as $data)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                           <img src="{{ $data->foto ? asset('storage/' . $data->foto) : asset('build/images/companies/default.png') }}"
                            alt="{{ $data->nama_perusahaan }}" class="img-fluid rounded mb-3" style="height: 150px; width: 80%; object-fit: cover;">
                        <ul class="list-inline fs-14 text-muted">
                            <li class="list-inline-item">
                                <i class="ri-building-line align-bottom me-1"></i>
                                {{ $data->nama_perusahaan }}
                            </li>
                            <li class="list-inline-item">
                                <i class="ri-map-pin-2-line align-bottom me-1"></i>
                                {{ $data->alamat_perusahaan }}
                            </li>
                        </ul>
                        <h5>{{ $data->nama_perusahaan }}</h5>
                        <p class="text-muted fs-14">
                            {{ \Illuminate\Support\Str::limit(strip_tags($data->deskripsi ?? 'Belum ada deskripsi'), 20) }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Kategori Pekerjaan --}}
<section class="section bg-light" id="categories">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base fs-3">Kategori Pekerjaan</h1>
            <div style="width: 60px; height: 6px; background-color: #a7aab3; margin: 0 auto; border-radius: 3px;"></div>
        </div>
        <div class="row justify-content-center">
            @foreach($categories->filter(function($category) { return $category->jobs_count > 0; }) as $category)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-none text-center py-3 h-100">
                    <div class="card-body py-4">
                        <a href="{{ route('jobs.index', ['category' => $category->id]) }}" class="stretched-link">
                            <h5 class="fs-17 pt-1">{{ $category->nama_category }}</h5>
                        </a>
                        <p class="mb-0 text-muted">{{ $category->jobs_count }} Lowongan</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
{{-- Artikel --}}
<section class="section bg-light" id="artikel">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base fs-3">Informasi Artikel</h1>
            <div style="width: 60px; height: 6px; background-color: #a7aab3; margin: 0 auto; border-radius: 3px;"></div>
        </div>

        @if($artikel->isEmpty())
            <div class="alert alert-info text-center">
                Tidak ada artikel tersedia saat ini.
            </div>
        @else
            <div class="row">
                @foreach($artikel as $item)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            @if($item->gambar)
                            <img src="{{ asset('storage/gambar_artikel/' . $item->gambar) }}"
                                alt="{{ $item->judul_artikel }}"
                                class="img-fluid rounded mb-3"
                                style="height: 200px; width: 100%; object-fit: cover;">
                            @else
                            <div class="bg-secondary rounded mb-3"
                                 style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-image-line fs-1 text-white"></i>
                            </div>
                            @endif

                            <ul class="list-inline fs-14 text-muted">
                                <li class="list-inline-item">
                                    <i class="ri-calendar-line align-bottom me-1"></i>
                                    {{ $item->created_at->format('d M, Y') }}
                                </li>
                            </ul>

                            <a href="{{ route('artikel.show', $item->id) }}" class="text-decoration-none">
                                <h5 class="text-dark">{{ $item->judul_artikel }}</h5>
                            </a>

                            <p class="text-muted fs-14">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 100) }}
                            </p>

                            <a href="{{ route('articlee.show', $item->id) }}"
                               class="btn btn-sm btn-outline-primary mt-2">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

        </div>
        </div>
    </section>
    <!-- Start footer -->
    <footer class="py-5 position-relative" style="background-color: #0b3558; color: white;">
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

    <!-- end footer -->

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-info btn-icon landing-back-top" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    </div>
    <!-- end layout wrapper -->

    @endsection
    @section('script')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/job-lading.init.js') }}"></script>
    @endsection
