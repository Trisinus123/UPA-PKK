<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Beranda | Job Portal')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Remix Icon CSS -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #0b3558;
            padding-top: 1.5rem;
            position: fixed;
            width: 240px;
            overflow-y: auto;
        }

        .sidebar h4 {
            color: #fff;
            text-align: center;
            margin-bottom: 2rem;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #d1dbe9;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #154b75;
            color: #ffffff;
        }

        .main-content {
            margin-left: 240px;
            padding: 2rem;
        }

        .logout-button {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }

        .logout-button form {
            display: inline;
        }

        .alert {
            margin-top: 1rem;
        }
    </style>

    @yield('styles')
</head>
<body>

    {{-- Sidebar --}}
<div class="sidebar">
    <h4></i>Perusahaan</h4>
    <a href="{{ route('jobs.index') }}" class="{{ request()->routeIs('jobs.index') ? 'active' : '' }}">
        <i class="fas fa-briefcase" style="margin-right: 8px;"></i>Kelola Pekerjaan
    </a>
    <a href="{{ route('applications.index') }}" class="{{ request()->routeIs('applications.*') ? 'active' : '' }}">
        <i class="fas fa-file-alt" style="margin-right: 8px;"></i>Lamaran Pekerjaan
    </a>
    <a href="{{ route('perusahaan.profile') }}" class="{{ request()->routeIs('perusahaan.profile') ? 'active' : '' }}">
        <i class="fas fa-user-circle" style="margin-right: 8px;"></i>Profil
    </a>
    <div class="logout-button">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm mt-3">
                <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>Logout
            </button>
        </form>
    </div>
</div>

    {{-- Main Content --}}
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
