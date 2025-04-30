<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pendaftaran Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

  <!-- Navbar dari halaman home -->
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

  <!-- Konten Form Pendaftaran -->
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Pendaftaran Mahasiswa</h4>
          </div>
          <div class="card-body">
          
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            
            <form method="POST" action="{{ route('register.mahasiswa.submit') }}">
              @csrf
              
              <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
              </div>
              
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
              </div>
              
              <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
              </div>
              
              <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="nim" name="nim" value="{{ old('nim') }}" required>
              </div>
              
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              
              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
              </div>
              
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Daftar</button>
              </div>
              
              <div class="mt-3 text-center">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
                <p>Ingin mendaftar sebagai perusahaan? <a href="{{ route('register.perusahaan') }}">Daftar Perusahaan</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer dari halaman home -->
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