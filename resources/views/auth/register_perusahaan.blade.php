@extends('layouts.master-without-nav')
@section('title') Register Perusahaan @endsection
@section('css')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('body')

<body data-bs-spy="scroll" data-bs-target="#navbar-example">
    @endsection
    @section('content')

    <nav class="navbar navbar-expand-lg navbar-landing fixed-top shadow-sm py-3" id="navbar"
        style="background-color: #ffffff;">
        <div class="container-fluid custom-container">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/logo.png') }}" alt="UPA PKK Logo" height="60" class="me-2">
                <span class="fs-3 fw-bold text-primary">UPA <span class="text-danger">PKK</span></span>
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
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
    <!-- Konten Form Pendaftaran Perusahaan -->
    <div class="container py-5" style="margin-top: 100px;">
        <!-- Tambahkan margin-top di sini -->
        <div class="row align-items-center justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4 btn-primary w-100">Daftar Akun</h2>

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('register.perusahaan.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Nama Perusahaan"
                                    value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email"
                                    value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" name="no_hp" placeholder="Nomor Telepon"
                                    value="{{ old('no_hp') }}" required>
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" name="alamat_perusahaan"
                                    placeholder="Alamat Perusahaan" value="{{ old('alamat_perusahaan') }}" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password"
                                    required>
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="Konfirmasi Password" required>
                            </div>

                            <button type="submit" class="btn btn-secondary w-100">Daftar</button>

                            <div class="text-center mt-3">
                                <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}"
                                        class="text-primary">Login Sekarang</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-5" style="background-color: #0b3558; color: white;">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold" style="color: #ccc;">UPA <span style="color: red;">PKK</span></h5>
                    <p class="mb-1 mt-3">Portal Polinema Career Center</p>
                    <p>Direktorat Pengembangan Karier dan Mahasiswa<br>Politeknik Negeri Malang</p>
                </div>

                <div class="col-md-3 mb-4">
                    <h6 class="text-white fw-semibold fs-5">Pranala Terkait</h6>
                    <ul class="list-unstyled mt-3">
                        <li><a href="#" class="text-white text-decoration-none">Polinema Official</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Prasetya Online</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Polinema TV</a></li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h6 class="text-white fw-semibold fs-5">Umpan Balik</h6>
                    <ul class="list-unstyled mt-3">
                        <li><a href="#" class="text-white text-decoration-none">Polinema Care</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Unit Pengendalian Gratifikasi</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Whistleblowing System</a></li>
                    </ul>
                </div>
            </div>

            <hr style="border-color: white;">

            <div class="text-center mt-3">
                <p class="mb-0">@polinema joss !</p>
            </div>
        </div>
    </footer>

    <!-- Back to top -->
    <button onclick="topFunction()" class="btn btn-info btn-icon landing-back-top" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>

    @endsection

    @section('script')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/job-lading.init.js') }}"></script>
    @endsection
