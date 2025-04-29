<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPA PKK - Portal Karir</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" style="height: 40px; vertical-align: middle; margin-right: 10px;">
            UPA <span class="highlight">PKK</span>
          </div>
        <nav>
            <ul>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#kontak">Kontak</a></li>
                <li><a href="/login" class="btn-login">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="tentang" class="hero">
            <div class="hero-content">
                <h1>Peluang Mahasiswa Untuk Dapat Mengembangkan Karir yang Dimiliki</h1>
                <a href="#lowongan" class="btn-primary">Selengkapnya</a>
            </div>
            <div class="hero-image"></div>
                <img src="{{ asset('assets/img/illustration.png') }}" alt="Ilustrasi Karir" width="250px">
            </div>
        </section>

        <section id="lowongan" class="content">
            <h2>Mitra Perusahaan</h2>
            <div class="image-grid">
                <img src="{{ asset('assets/img/company1.png') }}" alt="Company 1">
                <img src="{{ asset('assets/img/company2.png') }}" alt="Company 2">
                <img src="{{ asset('assets/img/company3.png') }}" alt="Company 3">
            </div>
        </section>

        <section id="perusahaan" class="content">
            <h2>Artikel Terbaru</h2>
            <div class="image-grid">
                <img src="{{ asset('assets/img/article1.jpg') }}" alt="Artikel 1">
                <img src="{{ asset('assets/img/article2.jpg') }}" alt="Artikel 2">
                <img src="{{ asset('assets/img/article3.jpg') }}" alt="Artikel 3">
            </div>
        </section>
    </main>

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