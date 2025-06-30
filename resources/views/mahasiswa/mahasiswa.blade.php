@extends('layouts.master-without-nav')
@section('title') Dashboard Mahasiswa @endsection

@section('css')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('body')
<body data-bs-spy="scroll" data-bs-target="#navbar-example">
@endsection

@section('content')

<nav class="navbar navbar-expand-lg navbar-landing fixed-top shadow-sm py-3" id="navbar" style="background-color: #ffffff;">
    <div class="container-fluid custom-container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center">
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
                    <a class="nav-link fw-semibold fs-4 {{ Request::is('student/jobs') ? 'text-primary' : '' }}" href="/student/jobs">Lowongan Kerja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold fs-4 {{ Request::is('student/applications') ? 'text-primary' : '' }}" href="{{ route('student.applications') }}">Lamaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold fs-4 {{ Request::is('companies/browse') ? 'text-primary' : '' }}" href="{{ route('companies.browse') }}">Perusahaan</a>
                </li>
            </ul>

            <!-- User dropdown and logout -->
            <div class="d-flex align-items-center">
                <div class="dropdown me-2">
                    <a class="d-flex align-items-center text-black text-decoration-none " href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <strong>{{ Auth::user()->name }}</strong>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" alt="profile" class="ms-2 rounded-circle" width="40" height="40">
                    </a>

                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-flex ms-2">
                        @csrf
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fs-5 fw-bold">
                            <span class="align-middle">Logout</span>
                        </button>

                    </form>
            </div>
        </div>
    </div>
</nav>

<!-- Your main content sections would go here -->

<!-- Start footer -->
<footer class="bg-dark text-white mt-5">


            <hr style="border-color: white;">

            <div class="text-center mt-3">
                <p class="mb-0">copyright @UPAPKK 2025 | Trisinus Gulo</p>
            </div>
        </div>
    </div>
</footer>
<!-- End footer -->



@section('script')
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/job-lading.init.js') }}"></script>
@endsection
