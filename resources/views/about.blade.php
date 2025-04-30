<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPA PKK - Portal Karir</title>
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" style="height: 40px; vertical-align: middle; margin-right: 10px;">
            UPA <span class="highlight">PKK</span>
        </div>
        <nav>
            <ul>
                <li><a href="/">Beranda</a></li>
                <li><a href="/about">Tentang</a></li>
                <li><a href="/contact">Kontak</a></li>
                <li><a href="/login" class="btn-login">Login</a></li>
            </ul>
        </nav>
    </header>
    <!-- BANNER -->
    <section class="hero">
        <h2>Hubungin kami</h2>
    </section>
    <section class="about-section">
    <div class="about-content">
        <h2>Polinema Career Center</h2>
        <h3>Profil Singkat</h3>
        <p>
            Polinema Career Center adalah portal website yang berisi informasi layanan pengembangan karir dan lowongan kerja 
            yang ditujukan untuk mahasiswa Polinema serta masyarakat umum.
        </p>
        <p>
            Portal ini dikelola oleh Direktorat Pengembangan Karir dan Alumni (DPKA) Politeknik Negeri Malang yang merupakan 
            unit kerja yang memiliki tugas melaksanakan dan mengembangkan program kerja yang sesuai dengan perencanaan 
            pengembangan karir dan alumni, serta melaksanakan tracer study.
        </p>
    </div>
    <div class="about-image">
        <img src="{{ asset('assets/img/illustration.png') }}" alt="Ilustrasi Karir">
    </div>
</section>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>UPA <span class="highlight">PKK</span></h3>
                <p>Portal Polinema Career Center</p>
                <p>Direktorat Pengembangan Karier dan </p>
                <p>Mahasiswa Politeknik Negeri Malang </p>
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