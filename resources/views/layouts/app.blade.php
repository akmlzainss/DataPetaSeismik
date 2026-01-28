{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'BBSPGL - Sistem Informasi Survei Seismik')</title>

    {{-- Favicon untuk semua platform --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/logo-esdm2.png') }}">

    {{-- Apple Touch Icon (iOS) --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('storage/logo-esdm2.png') }}">

    {{-- Android Chrome --}}
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('storage/logo-esdm2.png') }}">

    {{-- Microsoft Tiles (Windows) --}}
    <meta name="msapplication-TileImage" content="{{ asset('storage/logo-esdm2.png') }}">
    <meta name="msapplication-TileColor" content="#ffed00">
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">
    <meta name="theme-color" content="#ffed00">

    {{-- Web App Manifest (Android, Chrome) --}}
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Public Home CSS (untuk navbar) --}}
    <link rel="stylesheet" href="{{ asset('css/public-home.css') }}?v={{ time() }}">

    {{-- Inline CSS to ensure navbar is visible --}}
    <style>
        /* Force navbar to be visible */
        .navbar {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1000 !important;
            background: #ffed00 !important;
            border-bottom: 3px solid #ffd500 !important;
        }

        .navbar-container {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .navbar-menu {
            display: flex !important;
            gap: 36px !important;
            list-style: none !important;
        }

        /* Profile Dropdown Styles */
        .profile-dropdown {
            position: relative;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: none;
            color: #003366;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .profile-trigger:hover {
            background: rgba(0, 51, 102, 0.1);
        }

        .profile-trigger .fa-user-circle {
            font-size: 24px;
        }

        .dropdown-arrow {
            font-size: 12px;
            transition: transform 0.3s;
        }

        .profile-trigger.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 250px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1001;
        }

        .profile-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
        }

        .profile-header .fa-user-circle {
            font-size: 40px;
            color: #003366;
        }

        .profile-header strong {
            display: block;
            color: #003366;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .profile-header small {
            display: block;
            color: #666;
            font-size: 12px;
        }

        .profile-divider {
            height: 1px;
            background: #e0e0e0;
            margin: 0 16px;
        }

        .profile-logout {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: transparent;
            border: none;
            color: #dc3545;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 0 0 8px 8px;
        }

        .profile-logout:hover {
            background: #fee;
        }

        .profile-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: transparent;
            border: none;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            text-align: left;
        }

        .profile-menu-item:hover {
            background: #f5f5f5;
            color: #003366;
        }

        .profile-menu-item i {
            color: #003366;
            width: 16px;
            text-align: center;
        }

        /* Responsive Profile Dropdown for Mobile */
        @media (max-width: 968px) {
            .profile-dropdown {
                width: 100%;
            }

            .profile-trigger {
                width: 100%;
                justify-content: space-between;
                padding: 12px 16px;
                background: rgba(0, 51, 102, 0.05);
                border-radius: 8px;
            }

            .profile-trigger .fa-user-circle {
                font-size: 20px;
            }

            .profile-menu {
                position: static;
                width: 100%;
                margin-top: 12px;
                transform: none;
            }

            .profile-menu.show {
                transform: none;
            }

            .profile-name {
                flex: 1;
                text-align: left;
            }
        }
    </style>

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
                
                {{-- Login/Logout Pegawai ESDM --}}
                <li style="position: relative;">
                    @auth('pegawai')
                        {{-- Profile Dropdown Trigger --}}
                        <div class="profile-dropdown">
                            <button class="profile-trigger" id="profileTrigger" onclick="toggleProfileDropdown()">
                                <i class="fas fa-user-circle"></i>
                                <span class="profile-name">{{ Auth::guard('pegawai')->user()->nama }}</span>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </button>
                            
                            {{-- Dropdown Menu --}}
                            <div class="profile-menu" id="profileMenu">
                                <div class="profile-header">
                                    <i class="fas fa-user-circle"></i>
                                    <div>
                                        <strong>{{ Auth::guard('pegawai')->user()->nama }}</strong>
                                        <small>{{ Auth::guard('pegawai')->user()->email }}</small>
                                    </div>
                                </div>
                                <div class="profile-divider"></div>
                                
                                {{-- Menu Items --}}
                                <button type="button" class="profile-menu-item" onclick="showPasswordModal()">
                                    <i class="fas fa-key"></i>
                                    <span>Ubah Password</span>
                                </button>
                                
                                <div class="profile-divider"></div>
                                <form action="{{ route('pegawai.logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="profile-logout">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('pegawai.login') }}" class="{{ Request::routeIs('pegawai.login') ? 'active' : '' }}">
                            Login
                        </a>
                    @endauth
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
                    <p>Balai Besar Survei dan Pemetaan Geologi Kelautan adalah unit pelaksana teknis yang bertanggung jawab dalam
                        survei dan pemetaan geologi kelautan Indonesia untuk mendukung pengembangan sumber daya mineral
                        nasional.</p>
                    <div class="social-links">
                        {{-- Tautan Sosial Media BARU --}}
                        <a href="https://www.facebook.com/people/Badan-Geologi/100068349101047/" class="social-link"
                            aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="https://twitter.com/badangeologi_" class="social-link" aria-label="Twitter"
                            target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/geologi_kelautan/" class="social-link"
                            aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                            </svg>
                        </a>
                        <a href="https://www.youtube.com/@BadanGeologiBG" class="social-link" aria-label="YouTube"
                            target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
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
                        <li><a href="{{ route('panduan') }}">Panduan Pengguna</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('privasi') }}">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('syarat') }}">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('bantuan') }}">Bantuan</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="footer-section">
                    <h3>Kontak Kami</h3>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                        </svg>
                        <span>Jl. Dr. Djunjunan No.236, Husen Sastranegara,<br>Kec. Cicendo, Kota Bandung, Jawa Barat 40174</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                        </svg>
                        <span>(022) 7272606</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                        <span>info@bbspgl.esdm.go.id</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                        </svg>
                        <span>Senin - Jumat<br>08.00 - 16.30 WIB</span>
                    </div>
                     {{-- Explicit Link untuk Test Bot --}}
                    <div class="footer-contact-item" style="margin-top: 10px;">
                        <a href="{{ route('kontak') }}" style="color: white; text-decoration: underline; font-weight: bold;">
                            Buka Formulir Kontak / Contact Us
                        </a>
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
                    <a href="#" onclick="event.preventDefault(); document.getElementById('sitemapModal').style.display='flex'">Peta Situs</a>
                    <a href="{{ route('privasi') }}">Kebijakan Privasi</a>
                    <a href="{{ route('syarat') }}">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ========== SITEMAP MODAL ========== --}}
    <div id="sitemapModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:10000; justify-content:center; align-items:center; opacity:0; transition: opacity 0.3s ease;">
        <div class="sitemap-content" style="background:white; width:90%; max-width:500px; border-radius:15px; padding:30px; position:relative; transform:scale(0.9); transition: transform 0.3s ease;">
            <button onclick="closeSitemap()" style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:24px; color:#666; cursor:pointer;">&times;</button>
            
            <h3 style="color:#003366; border-bottom:2px solid #ffed00; padding-bottom:10px; marginBottom:20px; display:inline-block;">Peta Situs</h3>
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:20px;">
                <div>
                    <h4 style="font-size:16px; color:#333; margin-bottom:10px;">Menu Utama</h4>
                    <ul style="list-style:none; padding:0; margin:0;">
                        <li style="margin-bottom:8px;"><a href="{{ route('beranda') }}" style="text-decoration:none; color:#0056b3; display:flex; align-items:center;"><i class="fas fa-home" style="width:20px;"></i> Beranda</a></li>
                        <li style="margin-bottom:8px;"><a href="{{ route('katalog') }}" style="text-decoration:none; color:#0056b3; display:flex; align-items:center;"><i class="fas fa-database" style="width:20px;"></i> Katalog Data</a></li>
                        <li style="margin-bottom:8px;"><a href="{{ route('peta') }}" style="text-decoration:none; color:#0056b3; display:flex; align-items:center;"><i class="fas fa-map-marked-alt" style="width:20px;"></i> Peta Sebaran</a></li>
                        <li style="margin-bottom:8px;"><a href="{{ route('kontak') }}" style="text-decoration:none; color:#0056b3; display:flex; align-items:center;"><i class="fas fa-envelope" style="width:20px;"></i> Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size:16px; color:#333; margin-bottom:10px;">Informasi</h4>
                    <ul style="list-style:none; padding:0; margin:0;">
                        <li style="margin-bottom:8px;"><a href="{{ route('panduan') }}" style="text-decoration:none; color:#666; display:flex; align-items:center;"><i class="fas fa-book" style="width:20px;"></i> Panduan</a></li>
                        <li style="margin-bottom:8px;"><a href="{{ route('faq') }}" style="text-decoration:none; color:#666; display:flex; align-items:center;"><i class="fas fa-question-circle" style="width:20px;"></i> FAQ</a></li>
                        <li style="margin-bottom:8px;"><a href="{{ route('bantuan') }}" style="text-decoration:none; color:#666; display:flex; align-items:center;"><i class="fas fa-life-ring" style="width:20px;"></i> Bantuan</a></li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        function closeSitemap() {
            const modal = document.getElementById('sitemapModal');
            const content = modal.querySelector('.sitemap-content');
            modal.style.opacity = '0';
            content.style.transform = 'scale(0.9)';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
        
        // Open animation
        document.querySelector('a[onclick*="sitemapModal"]').addEventListener('click', function() {
            const modal = document.getElementById('sitemapModal');
            const content = modal.querySelector('.sitemap-content');
            setTimeout(() => {
                modal.style.opacity = '1';
                content.style.transform = 'scale(1)';
            }, 10);
        });
        
        // Close on outside click
        document.getElementById('sitemapModal').addEventListener('click', function(e) {
            if (e.target === this) closeSitemap();
        });
    </script>

    {{-- ========== MODAL UBAH PASSWORD ========== --}}
    @auth('pegawai')
    <div id="passwordModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:10001; justify-content:center; align-items:center;">
        <div class="password-modal-content" style="background:white; width:90%; max-width:420px; border-radius:16px; overflow:hidden; transform:scale(0.9); transition: transform 0.3s ease;">
            {{-- Header --}}
            <div style="background: linear-gradient(135deg, #003366 0%, #004080 100%); color:white; padding:24px; text-align:center;">
                <i class="fas fa-key" style="font-size:36px; margin-bottom:12px; opacity:0.9;"></i>
                <h3 style="margin:0 0 4px 0; font-size:20px;">Ubah Password</h3>
                <p style="margin:0; font-size:13px; opacity:0.85;">Perbarui kata sandi akun Anda</p>
            </div>
            
            {{-- Body --}}
            <div style="padding:24px;">
                {{-- Alert container --}}
                <div id="passwordAlert" style="display:none; padding:12px; border-radius:8px; margin-bottom:16px; font-size:14px;"></div>
                
                <form id="passwordForm" class="no-loading">
                    @csrf
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:14px;">
                            <i class="fas fa-lock" style="color:#003366; margin-right:6px;"></i>
                            Password Saat Ini
                        </label>
                        <input type="password" name="current_password" id="currentPassword" 
                            placeholder="Masukkan password saat ini" required
                            style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px; font-size:14px; box-sizing:border-box;">
                    </div>
                    
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:14px;">
                            <i class="fas fa-key" style="color:#003366; margin-right:6px;"></i>
                            Password Baru
                        </label>
                        <input type="password" name="new_password" id="newPassword" 
                            placeholder="Masukkan password baru" required
                            style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px; font-size:14px; box-sizing:border-box;">
                        <p style="font-size:11px; color:#666; margin:6px 0 0 0;">Minimal 8 karakter</p>
                    </div>
                    
                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:6px; font-size:14px;">
                            <i class="fas fa-check-double" style="color:#003366; margin-right:6px;"></i>
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" name="new_password_confirmation" id="newPasswordConfirm" 
                            placeholder="Ulangi password baru" required
                            style="width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:8px; font-size:14px; box-sizing:border-box;">
                    </div>
                    
                    <div style="display:flex; gap:12px;">
                        <button type="button" onclick="closePasswordModal()" 
                            style="flex:1; padding:12px; background:#f5f5f5; color:#666; border:none; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;">
                            Batal
                        </button>
                        <button type="submit" id="savePasswordBtn"
                            style="flex:1; padding:12px; background:linear-gradient(135deg, #003366 0%, #004080 100%); color:white; border:none; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endauth

    {{-- ========== GLOBAL LOADING OVERLAY (TC016 FIX) ========== --}}
    <div id="globalLoadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
        <div style="background:white; padding:30px 50px; border-radius:12px; text-align:center; box-shadow:0 10px 40px rgba(0,0,0,0.3);">
            <div style="width:40px; height:40px; border:4px solid #f3f3f3; border-top:4px solid #003366; border-radius:50%; animation:spin 1s linear infinite; margin:0 auto 15px;"></div>
            <p id="loadingMessage" style="margin:0; font-size:16px; color:#333; font-weight:500;">Memproses...</p>
        </div>
    </div>
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

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

        // Global loading functions (TC016)
        window.showLoading = function(message = 'Memproses...') {
            const overlay = document.getElementById('globalLoadingOverlay');
            const msg = document.getElementById('loadingMessage');
            if (overlay && msg) {
                msg.textContent = message;
                overlay.style.display = 'flex';
            }
        };
        
        window.hideLoading = function() {
            const overlay = document.getElementById('globalLoadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        };

        // Auto show loading on form submissions (not marked with no-loading class)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form.classList.contains('no-loading') && !form.dataset.noLoading) {
                showLoading('Mengirim data...');
            }
        });

        // Auto show loading on navigation to internal links (optional, for slow connections)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && link.href.startsWith(window.location.origin) && !link.target && !link.classList.contains('no-loading')) {
                // Don't show for hash links
                if (link.getAttribute('href').startsWith('#')) return;
                // Show loading after small delay to allow for instant navigations
                setTimeout(function() {
                    // Only show if still on same page (navigation pending)
                    if (document.hidden === false) {
                        showLoading('Memuat halaman...');
                    }
                }, 500);
            }
        });

        // Hide loading when page becomes visible again (back/forward navigation)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                hideLoading();
            }
        });

        // Hide loading on page load complete
        window.addEventListener('load', function() {
            hideLoading();
        });

        // Profile Dropdown Toggle
        function toggleProfileDropdown() {
            const menu = document.getElementById('profileMenu');
            const trigger = document.getElementById('profileTrigger');
            
            menu.classList.toggle('show');
            trigger.classList.toggle('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.profile-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                const menu = document.getElementById('profileMenu');
                const trigger = document.getElementById('profileTrigger');
                if (menu) menu.classList.remove('show');
                if (trigger) trigger.classList.remove('active');
            }
        });

        // ========== PASSWORD MODAL FUNCTIONS ==========
        function showPasswordModal() {
            const modal = document.getElementById('passwordModal');
            if (modal) {
                // Close profile dropdown first
                const profileMenu = document.getElementById('profileMenu');
                const profileTrigger = document.getElementById('profileTrigger');
                if (profileMenu) profileMenu.classList.remove('show');
                if (profileTrigger) profileTrigger.classList.remove('active');
                
                // Show modal
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.querySelector('.password-modal-content').style.transform = 'scale(1)';
                }, 10);
                
                // Focus first input
                document.getElementById('currentPassword')?.focus();
            }
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            if (modal) {
                modal.querySelector('.password-modal-content').style.transform = 'scale(0.9)';
                setTimeout(() => {
                    modal.style.display = 'none';
                    // Reset form
                    document.getElementById('passwordForm')?.reset();
                    hidePasswordAlert();
                }, 300);
            }
        }

        function showPasswordAlert(message, isSuccess) {
            const alert = document.getElementById('passwordAlert');
            if (alert) {
                alert.textContent = message;
                alert.style.display = 'block';
                alert.style.background = isSuccess ? '#d4edda' : '#f8d7da';
                alert.style.color = isSuccess ? '#155724' : '#721c24';
                alert.style.borderLeft = isSuccess ? '4px solid #28a745' : '4px solid #dc3545';
            }
        }

        function hidePasswordAlert() {
            const alert = document.getElementById('passwordAlert');
            if (alert) {
                alert.style.display = 'none';
            }
        }

        // Handle password form submit
        document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('savePasswordBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('{{ route("pegawai.password.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    current_password: formData.get('current_password'),
                    new_password: formData.get('new_password'),
                    new_password_confirmation: formData.get('new_password_confirmation')
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                if (data.success) {
                    showPasswordAlert(data.message || 'Password berhasil diubah!', true);
                    document.getElementById('passwordForm').reset();
                    setTimeout(() => {
                        closePasswordModal();
                    }, 1500);
                } else {
                    showPasswordAlert(data.message || 'Gagal mengubah password', false);
                }
            })
            .catch(error => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                showPasswordAlert('Terjadi kesalahan saat mengubah password', false);
            });
        });

        // Close modal on outside click
        document.getElementById('passwordModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closePasswordModal();
            }
        });
    </script>

    {{-- Custom Scripts dari child pages --}}
    @stack('scripts')
</body>

</html>


