<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

  <!-- ✅ Navbar dari halaman home -->
  <header>
    <div class="logo">
        <img src="{{ asset('assets/img/logo.png') }}" style="height: 40px; vertical-align: middle; margin-right: 10px;">
        UPA <span class="highlight">PKK</span>
    </div>
    <nav>
        <ul>
            <li><a href="/">Beranda</a></li>
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#kontak">Kontak</a></li>
            <li><a href="/login" class="btn-login">Login</a></li>
        </ul>
    </nav>
  </header>

  <!-- ✅ Konten Form Login -->
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="row login-container w-100">
      <!-- Gambar Kiri -->
      <div class="col-md-6 login-image d-none d-md-block"></div>

      <!-- Form Login -->
      <div class="col-md-6 login-form">
        <h4>Login</h4>
        <p>Selamat Datang</p>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3">
            <label for="login" class="form-label">Email/Nim</label>
            <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-login mt-3">Login</button>
        </form>
      </div>
    </div>
  </div>

  <!-- ✅ Footer dari halaman home -->
  <footer id="kontak">
    <div class="footer-container">
      <div class="footer-section">
        <h3>UPA <span class="highlight">PKK</span></h3>
        <p>Portal Polinema Career Center</p>
        <p>Direktorat Pengembangan Karier dan Mahasiswa Politeknik Negeri Malang</p>
      </div>
      <div class="footer-section">
        <h3>Pranala Terkait</h3>
        <ul>
          <li><a href="#">Polinema Official</a></li>
          <li><a href="#">Praseya Online</a></li>
          <li><a href="#">Polinema TV</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Umpan Balik</h3>
        <ul>
          <li><a href="#">Polinema Care</a></li>
          <li><a href="#">Unit Pengendalian Gratifikasi</a></li>
          <li><a href="#">Whistleblowing System</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-line"></div>
    <p class="copyright">@polinema joss!</p>
  </footer>

</body>
</html>