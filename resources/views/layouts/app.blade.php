{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'BBSPGL - Sistem Informasi Survei Seismik')</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Public Home CSS (untuk navbar) --}}
    <link rel="stylesheet" href="{{ asset('css/public-home.css') }}">
    
    {{-- Custom Styles dari child pages --}}
    @stack('styles')
</head>
<body>
    {{-- ========== NAVBAR ========== --}}
    <nav class="navbar">
        <div class="navbar-container">
            {{-- ROUTE BERANDA --}}
            <a href="{{ route('beranda') }}" class="navbar-logo">
                <img src="{{ asset('storage/logo_bbspgl2.png') }}" alt="BBSPGL Logo">
            </a>
            
            <ul class="navbar-menu" id="navbarMenu">
                <li>
                    {{-- ROUTE BERANDA --}}
                    <a href="{{ route('beranda') }}" class="{{ Request::routeIs('beranda') ? 'active' : '' }}">
                        Beranda
                    </a>
                </li>
                <li>
                    {{-- KOREKSI FINAL: Menggunakan route('katalog') --}}
                    <a href="{{ route('katalog') }}" class="{{ Request::routeIs('katalog*') ? 'active' : '' }}">
                        Katalog
                    </a>
                </li>
                <li>
                    {{-- KOREKSI FINAL: Menggunakan route('peta') --}}
                    <a href="{{ route('peta') }}" class="{{ Request::routeIs('peta') ? 'active' : '' }}">
                        Peta
                    </a>
                </li>
                <li>
                    {{-- KOREKSI FINAL: Menggunakan route('tentang') --}}
                    <a href="{{ route('tentang') }}" class="{{ Request::routeIs('tentang') ? 'active' : '' }}">
                        Tentang
                    </a>
                </li>
                <li>
                    {{-- KOREKSI FINAL: Menggunakan route('kontak') --}}
                    <a href="{{ route('kontak') }}" class="{{ Request::routeIs('kontak') ? 'active' : '' }}">
                        Kontak
                    </a>
                </li>
            </ul>
            
            <button class="navbar-toggle" id="navbarToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    {{-- ========== MAIN CONTENT ========== --}}
    <main>
        @yield('content')
    </main>

    {{-- ========== FOOTER ========== --}}
    <footer class="footer">
        <div class="footer-main">
            <div class="footer-content">
                {{-- About Section --}}
                <div class="footer-section footer-about">
                    <div class="footer-logo">
                        <img src="{{ asset('storage/blubbspgl.jpg') }}" alt="BBSPGL Logo">
                    </div>
                    <p>Balai Besar Survei dan Pemetaan Geologi adalah unit pelaksana teknis yang bertanggung jawab dalam survei dan pemetaan geologi kelautan Indonesia untuk mendukung pengembangan sumber daya mineral nasional.</p>
                    <div class="social-links">
                        {{-- Tautan Sosial Media BARU --}}
                        <a href="https://www.facebook.com/people/Badan-Geologi/100068349101047/" class="social-link" aria-label="Facebook" target="_blank">
                             <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://twitter.com/badangeologi_" class="social-link" aria-label="Twitter" target="_blank">
                             <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/geologi_kelautan/" class="social-link" aria-label="Instagram" target="_blank">
                             <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/@BadanGeologiBG" class="social-link" aria-label="YouTube" target="_blank">
                             <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="footer-section">
                    <h3>Link Cepat</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('beranda') }}">Beranda</a></li>
                        {{-- KOREKSI FINAL --}}
                        <li><a href="{{ route('katalog') }}">Katalog Survei</a></li>
                        <li><a href="{{ route('peta') }}">Peta Interaktif</a></li>
                        <li><a href="{{ route('tentang') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('kontak') }}">Kontak</a></li>
                    </ul>
                </div>

                {{-- Resources --}}
                <div class="footer-section">
                    <h3>Sumber Daya</h3>
                    <ul class="footer-links">
                        <li><a href="#">Panduan Pengguna</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Bantuan</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="footer-section">
                    <h3>Kontak Kami</h3>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        <span>Jl. Diponegoro No.57<br>Bandung 40122, Indonesia</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        <span>(022) 7272606</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        <span>info@bbspgl.esdm.go.id</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                        <span>Senin - Jumat<br>08.00 - 16.00 WIB</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>
                    © {{ date('Y') }} BBSPGL - Balai Besar Survei dan Pemetaan Geologi Kelautan. 
                    <a href="https://www.esdm.go.id" target="_blank">Kementerian ESDM</a>
                </p>
                <div class="footer-bottom-links">
                    <a href="#">Peta Situs</a>
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Disclaimer</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ========== SCRIPTS ========== --}}
    {{-- Navbar Mobile Toggle --}}
    <script>
        // Navbar Mobile Toggle
        const navbarToggle = document.getElementById('navbarToggle');
        const navbarMenu = document.getElementById('navbarMenu');

        navbarToggle?.addEventListener('click', () => {
            navbarMenu.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (navbarToggle && navbarMenu) {
                if (!navbarToggle.contains(e.target) && !navbarMenu.contains(e.target)) {
                    navbarMenu.classList.remove('active');
                }
            }
        });

        // Close menu when clicking a link (mobile)
        const navLinks = document.querySelectorAll('.navbar-menu a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 968) {
                    navbarMenu.classList.remove('active');
                }
            });
        });
    </script>

    {{-- Custom Scripts dari child pages --}}
    @stack('scripts')
</body>
</html>