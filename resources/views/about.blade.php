@extends('layouts.master-without-nav')

@section('title') UPA landing @endsection

@section('css')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    /* Custom Styling for the About Section */
    .about-section {
        padding: 200px 0 200px 0; /* Added top padding to account for fixed navbar */
    }

    .about-section .about-content {
        padding-right: 50px;
    }


</style>
@endsection

@section('body')
<body data-bs-spy="scroll" data-bs-target="#navbar-example">
@endsection

@section('content')

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
                <a href="/login" class="btn btn-primary rounded-pill px-4">
                    <i class="ri-user-3-line me-1"></i> Login / Register
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Start About Section -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <!-- Text Section -->
            <div class="col-md-6">
                <div class="about-content">
                    <h2><strong>Polinema Career Center</strong></h2>
                    <p>
                        Polinema Career Center adalah portal website yang berisi informasi layanan pengembangan
                        karir dan lowongan kerja yang ditujukan untuk mahasiswa Polinema serta masyarakat umum.
                    </p>
                    <p>
                        Portal ini dikelola oleh Direktorat Pengembangan Karir dan Alumni (DPKA) Politeknik Negeri
                        Malang yang merupakan unit kerja yang memiliki tugas melaksanakan dan mengembangkan program
                        kerja yang sesuai dengan perencanaan pengembangan karir dan alumni, serta melaksanakan tracer study.
                    </p>
                    <p>
                        Dengan adanya portal ini, diharapkan mahasiswa Polinema dapat dengan mudah mengakses informasi
                        terkait pengembangan karir, lowongan kerja, dan berbagai layanan yang mendukung persiapan
                        memasuki dunia kerja.
                    </p>
                </div>
            </div>

            <!-- Image Section -->
            <div class="col-md-6">
                <div class="about-image ms-md-4 mt-4 mt-md-0">
                    <img src="{{ asset('assets/img/karir.jpg') }}" alt="Ilustrasi Karir" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End About Section -->

<!-- Footer -->
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

<!-- Back to Top -->
<button onclick="topFunction()" class="btn btn-info btn-icon landing-back-top" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>

@endsection

@section('script')
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/job-lading.init.js') }}"></script>
<script>
    // Back to top button functionality
    function topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
</script>
@endsection
