<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Job Portal') }} - Admin Panel</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background-color: #0b3558;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .sidebar h4 {
            padding-left: 15px;
            margin-top: 15px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 10px 15px;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .main-content {
            padding: 20px 20px 15px 5px;
            background-color: #f8f9fa;
            flex-grow: 1;
            margin-left: -5px;
        }

        .card h2 {
            font-size: 2.5rem;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 0.9rem;
            color: #888;
        }

        footer a {
            color: #0b3558;
            text-decoration: none;
        }

        /* New styles for admin dropdown */
        .admin-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            color: #333;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 20px;
            transition: all 0.3s;
        }

        .admin-dropdown .dropdown-toggle:hover {
            text-decoration: none;
            background-color: rgba(0, 123, 255, 0.1);
        }

        .admin-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #0b3558;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }

        .admin-dropdown .dropdown-menu {
            right: 0;
            left: auto;
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .admin-dropdown .dropdown-item {
            padding: 8px 16px;
            color: #333;
            transition: all 0.2s;
        }

        .admin-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0b3558;
        }

        .admin-dropdown .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
            color: #0b3558;
        }

        /* Active menu item style */
        .sidebar .nav-item.active .nav-link {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
        }

    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
       <nav class="sidebar text-white px-3 pt-3" style="background-color: #0d3c61; width: 250px; min-height: 100vh;">
    <!-- Logo + Judul -->
    <div class="d-flex align-items-center mb-2" style="gap: -1px;">
        <img src="{{ asset('assets/img/Admin.png') }}" alt="Logo UPA PKK" height="80" width="80">
        <h5 class="text-white font-weight-bold mb-0">UPA PKK</h5>
    </div>

    <!-- Label Menu -->
    <div class="text-white-50 mb-2" style="font-size: 0.95rem; font-weight: 600;">
        Menu
    </div>

    <!-- Daftar Menu -->
    <ul class="nav flex-column">
        <li class="nav-item mb-2 @if(Request::routeIs('admin.dashboard')) active @endif">
            <a class="nav-link @if(Request::routeIs('admin.dashboard')) text-white @else text-white-50 @endif" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2 @if(Request::routeIs('admin.jobs.approval')) active @endif">
            <a class="nav-link @if(Request::routeIs('admin.jobs.approval')) text-white @else text-white-50 @endif" href="{{ route('admin.jobs.approval') }}">
                <i class="fas fa-check-circle mr-2"></i> Job Approval
            </a>
        </li>
        <li class="nav-item mb-2 @if(Request::routeIs('artikel.index')) active @endif">
            <a class="nav-link @if(Request::routeIs('artikel.index')) text-white @else text-white-50 @endif" href="{{ route('artikel.index') }}">
                <i class="fas fa-newspaper mr-2"></i> Artikel
            </a>
        </li>
        <li class="nav-item mb-2 @if(Request::routeIs('data-perusahaan.index')) active @endif">
            <a class="nav-link @if(Request::routeIs('data-perusahaan.index')) text-white @else text-white-50 @endif" href="{{ route('data-perusahaan.index') }}">
                <i class="fas fa-building mr-2"></i> Perusahaan
            </a>
        </li>
        <li class="nav-item mb-2 @if(Request::routeIs('category-job.index')) active @endif">
            <a class="nav-link @if(Request::routeIs('category-job.index')) text-white @else text-white-50 @endif" href="{{ route('category-job.index') }}">
                <i class="fas fa-tags mr-2"></i> Kategori Pekerjaan
            </a>
        </li>
        <li class="nav-item mb-2 @if(Request::routeIs('users.index')) active @endif">
            <a class="nav-link @if(Request::routeIs('users.index')) text-white @else text-white-50 @endif" href="{{ route('users.index') }}">
                <i class="fas fa-users mr-2"></i> Manage User
            </a>
        </li>
    </ul>
</nav>


        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar with admin dropdown -->
            <nav class="navbar navbar-light mb-4">
                <div class="ml-auto admin-dropdown">
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="adminDropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <div class="admin-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            Admin
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminDropdown">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <div>
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Highlight active menu item on click
            $('.sidebar .nav-link').click(function() {
                $('.sidebar .nav-link').removeClass('text-white').addClass('text-white-50');
                $(this).removeClass('text-white-50').addClass('text-white');

                $('.sidebar .nav-item').removeClass('active');
                $(this).closest('.nav-item').addClass('active');
            });
        });
    </script>
</body>

</html>
