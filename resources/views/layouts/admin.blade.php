<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin BBSPGL')</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    @stack('styles')
</head>
<body>
    @include('layouts.sidebar')

    <main class="main-content">
        @yield('content')
    </main>

    <!-- LOADING OVERLAY & MODAL HAPUS DIPINDAH KE SINI (SELALU ADA DI SEMUA HALAMAN) -->
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div id="loadingText" class="loading-text">Memproses...</div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <h3 class="modal-title">Konfirmasi Hapus</h3>
            <p class="modal-message">
                Apakah Anda yakin ingin menghapus data survei ini?<br>
                Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>.
            </p>
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="cancelDelete()">Batal</button>
                <button type="button" class="modal-btn modal-btn-delete" onclick="confirmDelete()">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Script tetap satu kali di sini -->
    <script src="{{ asset('js/data-survei.js') }}"></script>
    @stack('scripts')
</body>
</html>