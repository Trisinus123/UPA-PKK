@extends('layouts.master-without-nav')

@section('title') UPA landing @endsection

@section('css')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<style>
/* Google Maps Styling */

body {
        padding-top: 100px;
    }

.maps iframe {
    width: 100%;
    height: 450px;
    border: 0;
}

</style>
@endsection

@section('body')
<body data-bs-spy="scroll" data-bs-target="#navbar-example">
@endsection

@section('content')

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-landing fixed-top shadow-sm py-3" id="navbar" style="background-color: #ffffff;">
    <div class="container-fluid custom-container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="UPA PKK Logo" height="60" class="me-2">
            <span class="fs-3 fw-bold text-primary">UPA <span class="text-danger">PKK</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <i class="mdi mdi-menu fs-2 text-primary"></i>
        </button>
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
            <div class="d-flex justify-content-center">
                <a href="/login" class="btn btn-primary rounded-pill px-4 py-2 fs-5 fw-bold">
                    <i class="ri-user-3-line me-2 fs-5 align-middle"></i>
                    <span class="align-middle">Login / Register</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- HEADER TEXT -->
<div class="text-center mt-5 pt-5 ">
    <h2 class="fw-bold text-primary">KONTAK KAMI</h2>
    <p class="text-muted fs-5">contact us to get started</p>
</div>

<!-- GOOGLE MAPS -->
<section class="maps mb-3">
    <div class="container">
        <div class="rounded overflow-hidden shadow-sm" style="height: 350px;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1975.755619628022!2d112.6127622007872!3d-7.946002425066522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78827687d272e7%3A0x789ce9a636cd3aa2!2sPoliteknik%20Negeri%20Malang!5e0!3m2!1sid!2sid!4v1741852118683!5m2!1sid!2sid"
                width="50%"
                height="50%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

<!-- INFO KOTAK 3 KOLOM -->
<div class="container mt-4 mb-5">
    <div class="row text-center bg-light py-4 rounded shadow-sm">
        <div class="col-md-4 mb-3 mb-md-0">
            <h5 class="fw-bold">Lokasi :</h5>
            <p class="mb-0">Jl. Soekarno-Hatta, Malang</p>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <h5 class="fw-bold">Email :</h5>
            <p class="mb-0">karir@polinema.ac.id</p>
        </div>
        <div class="col-md-4">
            <h5 class="fw-bold">Panggil :</h5>
            <p class="mb-0">0341 123456</p>
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

<!-- Back to Top -->
<button onclick="topFunction()" class="btn btn-info btn-icon landing-back-top" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/job-lading.init.js') }}"></script>
@endsection
