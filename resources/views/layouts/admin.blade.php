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

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
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
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Toast Notification Container --}}
    <div id="toastContainer" class="toast-container"></div>

    {{-- Toast Notification Styles --}}
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">

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

        // Logout Modal Functions are now handled inside sidebar.blade.php


        // ============================================
        // TOAST NOTIFICATION SYSTEM
        // ============================================
        
        /**
         * Show a toast notification
         * @param {string} message - The message to display
         * @param {string} type - 'success', 'error', 'warning', 'info', 'primary'
         * @param {string} title - Optional title
         * @param {number} duration - Duration in ms (default 5000)
         */
        function showToast(message, type = 'info', title = null, duration = 5000) {
            const container = document.getElementById('toastContainer');
            
            // Auto-set title based on type if not provided
            if (!title) {
                switch(type) {
                    case 'success': title = 'Berhasil'; break;
                    case 'error': title = 'Error'; break;
                    case 'warning': title = 'Perhatian'; break;
                    case 'info': title = 'Informasi'; break;
                    case 'primary': title = 'Info'; break;
                    default: title = 'Notifikasi';
                }
            }
            
            // Get icon based on type
            let icon = '';
            switch(type) {
                case 'success': icon = '✓'; break;
                case 'error': icon = '✕'; break;
                case 'warning': icon = '⚠'; break;
                case 'info': icon = 'ℹ'; break;
                case 'primary': icon = '📢'; break;
                default: icon = 'ℹ';
            }
            
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
                <div class="toast-progress"></div>
            `;
            
            container.appendChild(toast);
            
            // Auto remove after duration
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'toastSlideOut 0.4s ease-out forwards';
                    setTimeout(() => toast.remove(), 400);
                }
            }, duration);
            
            return toast;
        }
        
        function closeToast(btn) {
            const toast = btn.closest('.toast');
            toast.style.animation = 'toastSlideOut 0.4s ease-out forwards';
            setTimeout(() => toast.remove(), 400);
        }
        
        // Shorthand functions
        function toastSuccess(message, title = null) { return showToast(message, 'success', title); }
        function toastError(message, title = null) { return showToast(message, 'error', title); }
        function toastWarning(message, title = null) { return showToast(message, 'warning', title); }
        function toastInfo(message, title = null) { return showToast(message, 'info', title); }
        function toastPrimary(message, title = null) { return showToast(message, 'primary', title); }
    </script>

    @stack('scripts')
</body>

</html>
