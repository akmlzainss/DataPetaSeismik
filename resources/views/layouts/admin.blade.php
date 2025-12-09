{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin BBSPGL')</title>

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

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    @stack('styles')
</head>

<body>
    {{-- Hamburger Button (Mobile Only) --}}
    <button class="hamburger-btn" id="hamburgerBtn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    {{-- Sidebar Overlay (Mobile Only) --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-content">
                <img src="{{ asset('storage/logo-esdm2.png') }}" alt="Logo ESDM">
                <div class="logo-text">
                    <span>Balai Besar Survei dan</span>
                    <span>Pemetaan Geologi Kelautan</span>
                </div>
            </div>
        </div>

        <ul class="sidebar-menu">
            {{-- DASHBOARD --}}
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- DATA SURVEI --}}
            <li>
                <a href="{{ route('admin.data_survei.index') }}"
                    class="{{ request()->routeIs('admin.data_survei.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                    </svg>
                    <span>Data Survei</span>
                </a>
            </li>

            {{-- LOKASI MARKER --}}
            <li>
                <a href="{{ route('admin.lokasi_marker.index') }}"
                    class="{{ request()->routeIs('admin.lokasi_marker.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                    </svg>
                    <span>Lokasi Marker</span>
                </a>
            </li>

            {{-- LAPORAN --}}
            <li>
                <a href="{{ route('admin.laporan.index') }}"
                    class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                    </svg>
                    <span>Laporan</span>
                </a>
            </li>

            {{-- PENGATURAN --}}
            <li>
                <a href="{{ route('admin.pengaturan.index') }}"
                    class="{{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13-.57-1.62-.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>

        <div class="logout-btn">
            <form action="{{ route('admin.logout') }}" method="POST" id="logoutFormAdmin">
                @csrf
                <button type="button" onclick="showLogoutModalAdmin()">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Logout Confirmation Modal --}}
    <div id="logoutModalAdmin" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Konfirmasi Logout</h3>
                <button class="modal-close" onclick="closeLogoutModalAdmin()">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-icon-warning">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="currentColor">
                        <path
                            d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
                    </svg>
                </div>
                <p class="modal-message">Apakah Anda yakin ingin keluar dari sistem?</p>
                <p class="modal-warning">Anda akan diarahkan ke halaman login.</p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeLogoutModalAdmin()">Batal</button>
                <button class="btn-delete" onclick="confirmLogoutAdmin()">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                        <path
                            d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
                    </svg>
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- JavaScript untuk Toggle Sidebar --}}
    <script>
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Toggle Sidebar
        function toggleSidebar() {
            hamburgerBtn.classList.toggle('active');
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }

        // Event Listeners
        hamburgerBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking menu item (mobile)
        const menuLinks = document.querySelectorAll('.sidebar-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });

        // Close sidebar on window resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                hamburgerBtn.classList.remove('active');
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            }
        });

        // Logout Modal Functions
        function showLogoutModalAdmin() {
            document.getElementById('logoutModalAdmin').style.display = 'flex';
            setTimeout(() => {
                document.querySelector('#logoutModalAdmin .modal-container').style.animation =
                    'slideUp 0.3s ease-out';
            }, 10);
        }

        function closeLogoutModalAdmin() {
            const modal = document.getElementById('logoutModalAdmin');
            const container = modal.querySelector('.modal-container');
            container.style.animation = 'slideDown 0.3s ease-out';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        function confirmLogoutAdmin() {
            document.getElementById('logoutFormAdmin').submit();
        }

        // Close modal when clicking outside
        document.getElementById('logoutModalAdmin')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModalAdmin();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
