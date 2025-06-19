@extends('layouts.master-without-nav')
@section('title') Register Mahasiswa @endsection
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

    <!-- Form Registrasi -->
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

                        <form method="POST" action="{{ route('register.mahasiswa.submit') }}" id="registerForm">
                            @csrf

                            <div class="mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Nama Lengkap"
                                    value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" name="nim" placeholder="NIM"
                                    value="{{ old('nim') }}" required>
                            </div>

                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email Aktif"
                                    value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="tel" class="form-control" id="no_hp" name="no_hp"
                                        placeholder="8123456789"
                                        value="{{ old('no_hp') ? str_replace('+62', '', old('no_hp')) : '' }}" required
                                        pattern="[0-9]{9,13}" title="Masukkan 9-13 digit nomor setelah +62">
                                </div>
                                <small class="text-muted">Contoh: +628123456789</small>
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control" name="password"
                                    placeholder="Password (minimal 8 karakter)" required>
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="Ulangi Password" required>
                            </div>

                            <button type="submit" class="btn btn-secondary w-100 py-2">Daftar</button>

                            <div class="text-center mt-3">
                                <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}"
                                        class="text-primary fw-semibold">Login disini</a></p>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto format nomor HP
            const phoneInput = document.getElementById('no_hp');

            phoneInput.addEventListener('input', function (e) {
                // Hapus semua karakter non-angka
                let value = e.target.value.replace(/\D/g, '');

                // Batasi panjang nomor
                if (value.length > 13) {
                    value = value.substring(0, 13);
                }

                e.target.value = value;
            });

            // Validasi sebelum submit
            document.getElementById('registerForm').addEventListener('submit', function (e) {
                if (phoneInput.value.length < 9) {
                    alert('Nomor HP harus minimal 9 digit setelah +62');
                    e.preventDefault();
                }
            });

            // Back to top button
            window.addEventListener('scroll', function () {
                var backToTop = document.getElementById('back-to-top');
                if (window.pageYOffset > 300) {
                    backToTop.style.display = 'block';
                } else {
                    backToTop.style.display = 'none';
                }
            });
        });

    </script>
    @endsection
