<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pilih Jenis Pendaftaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <style>
    .register-option {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      margin: 15px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .register-option:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .register-icon {
      font-size: 64px;
      margin-bottom: 20px;
      color: #0d6efd;
    }
    .option-title {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 15px;
    }
    .option-desc {
      color: #666;
      margin-bottom: 25px;
    }
  </style>
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

  <!-- Konten Pilihan Registrasi -->
  <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh">
    <div class="row w-100">
      <div class="col-12 text-center mb-5">
        <h2>Pilih Jenis Pendaftaran</h2>
        <p class="text-muted">Silakan pilih jenis akun yang ingin Anda daftarkan</p>
      </div>
      
      <div class="col-md-6">
        <div class="register-option">
          <div class="register-icon">👨‍🎓</div>
          <h3 class="option-title">Mahasiswa</h3>
          <p class="option-desc">Daftar sebagai pencari kerja dan akses lowongan pekerjaan yang tersedia.</p>
          <a href="{{ route('register.mahasiswa') }}" class="btn btn-primary btn-lg">Daftar Mahasiswa</a>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="register-option">
          <div class="register-icon">🏢</div>
          <h3 class="option-title">Perusahaan</h3>
          <p class="option-desc">Daftar sebagai perusahaan dan pasang lowongan pekerjaan.</p>
          <a href="{{ route('register.perusahaan') }}" class="btn btn-primary btn-lg">Daftar Perusahaan</a>
        </div>
      </div>
      
      <div class="col-12 text-center mt-4">
        <p>Sudah memiliki akun? <a href="{{ route('login') }}">Login di sini</a></p>
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