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

{{-- Show Article --}}
<div class="container py-4" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Article Header --}}
            <div class="mb-4 text-center">
                <h1 class="display-5 fw-bold mb-2">{{ $artikel->judul_artikel }}</h1>
                <div class="d-flex justify-content-center text-muted mb-3">
                    <span class="me-3"><i class="ri-calendar-line me-1"></i> {{ $artikel->created_at->format('d F Y') }}</span>
                    <span><i class="ri-user-line me-1"></i> {{ $artikel->author ?? 'Admin UPA PKK' }}</span>
                </div>
            </div>

            {{-- Article Content --}}
            @if($artikel)
                <div class="row">
                    {{-- Image Column - made smaller and aligned left --}}
                    <div class="col-md-4 mb-4 ps-0">  <!-- Removed padding-left -->
                        <img src="{{ asset('storage/gambar_artikel/' . $artikel->gambar) }}"
                             class="img-fluid rounded shadow-sm"
                             alt="{{ $artikel->judul_artikel }}"
                             style="max-height: 400px; width: 400%; object-fit: cover;">
                    </div>

                    {{-- Text Column - made wider --}}
                    <div class="col-md-8 mb-4 pe-0">  <!-- Removed padding-right -->
                        <div style="color: #333; line-height: 1.8; font-size: 1.1rem; text-align: justify;">
                            {!! nl2br(e($artikel->deskripsi)) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
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
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>
@endsection

@section('script-bottom')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
