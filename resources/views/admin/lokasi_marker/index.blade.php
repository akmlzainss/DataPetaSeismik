{{-- resources/views/admin/lokasi_marker/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Lokasi Marker Survei Seismik - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin-marker.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Lokasi Marker Survei </h1>
        <p>Kelola titik lokasi survei seismik </p>
    </div>

    <div class="info-card">
        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="tab-dropdown">
                Pilih dari Data Survei (Auto Geocoding)
            </button>
            <button class="tab-btn" data-tab="tab-manual">
                Input Manual
            </button>
        </div>

        {{-- ========================================================= --}}
        {{-- TAB 1: AUTO GEOCODING --}}
        {{-- ========================================================= --}}
        <div id="tab-dropdown" class="tab-content active">
            <div class="tab-section">
                <!-- Alert Info -->
                <div class="alert-box alert-info">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                    </svg>
                    <div>
                        <strong>Cara Penggunaan:</strong> Pilih survei dari dropdown → Peta akan menampilkan marker preview
                        (orange) → Klik tombol "Terapkan Marker Otomatis" untuk menyimpan permanen
                    </div>
                </div>

                <!-- Dropdown Section -->
                <div class="dropdown-section">
                    <label class="form-label required-field">Pilih Data Survei</label>
                    <select id="surveiSelect" class="form-select">
                        <option value="">-- Pilih survei yang akan ditandai di peta --</option>
                        @foreach ($surveis as $s)
                            <option value="{{ $s->id }}" data-wilayah="{{ $s->wilayah }}"
                                data-judul="{{ $s->judul }} ({{ $s->tahun }} - {{ $s->tipe }})">
                                {{ $s->judul }} ({{ $s->tahun }} - {{ $s->tipe }})
                            </option>
                        @endforeach
                    </select>

                    <!-- Hidden inputs untuk koordinat geocoding -->
                    <input type="hidden" id="geoLat" value="">
                    <input type="hidden" id="geoLng" value="">

                    <!-- Button Apply -->
                    <button type="button" id="autoApplyBtn" class="btn-primary mt-4" disabled>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path
                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                        </svg>
                        Terapkan Marker Otomatis
                    </button>
                </div>

                <!-- Helper Text -->
                <div class="helper-box">
                    <strong>📍 Tips Pengisian Wilayah untuk Geocoding Otomatis:</strong>
                    <ul>
                        <li>
                            Gunakan format: <strong>Nama Kota/Kabupaten, Provinsi</strong><br>
                            <small style="color: #28a745;">✓ Contoh: "Bandung, Jawa
                                Barat" atau "Surabaya, Jawa Timur"</small>
                        </li>
                        <li>
                            Untuk wilayah laut: <strong>Nama Laut/Selat + Provinsi Terdekat</strong><br>
                            <small style="color: #28a745;">✓ Contoh: "Selat Sunda,
                                Banten" atau "Laut Jawa, Jawa Tengah"</small>
                        </li>
                        <li>
                            Untuk blok survei: <strong>Nama Blok + Wilayah Laut</strong><br>
                            <small style="color: #28a745;">✓ Contoh: "Blok Masela,
                                Laut Arafura" atau "Blok Cepu, Jawa Timur"</small>
                        </li>
                        <li>
                            Hindari singkatan atau kode yang tidak umum<br>
                            <small style="color: #dc3545;">✗ Contoh: "BDG" atau
                                "JKT" (gunakan nama lengkap)</small>
                        </li>
                        <li>
                            <strong>Catatan:</strong> Jika geocoding gagal, sistem akan menampilkan peta Indonesia dan Anda
                            dapat menggunakan Tab Input Manual
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- TAB 2: INPUT MANUAL --}}
        {{-- ========================================================= --}}
        <div id="tab-manual" class="tab-content">
            <div class="tab-section">
                <!-- Alert Info -->
                <div class="alert-box alert-warning">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                    <div>
                        <strong>Petunjuk:</strong> Pilih survei → Isi koordinat manual atau klik di peta → Klik "Cari
                        Koordinat" untuk preview (marker orange) → Klik "Simpan Marker Manual Permanen" untuk menyimpan
                        (marker biru)
                    </div>
                </div>

                <!-- Tips Koordinat -->
                <div class="helper-box" style="margin-bottom: 16px;">
                    <strong>💡 Tips Input Koordinat Manual:</strong>
                    <ul>
                        <li><strong>Lintang (Latitude):</strong> Nilai antara -11 hingga 6 untuk
                            wilayah Indonesia (negatif = Selatan)</li>
                        <li><strong>Bujur (Longitude):</strong> Nilai antara 95 hingga 141 untuk
                            wilayah Indonesia (positif = Timur)</li>
                        <li>Format desimal: -6.2088, 106.8456 (gunakan titik, bukan koma)</li>
                        <li><strong>Alur Kerja:</strong> Isi koordinat → Klik "Cari Koordinat"
                            untuk preview → Klik "Simpan" untuk permanen</li>
                        <li>Klik langsung di peta untuk mengisi koordinat secara otomatis (akan reset status preview)</li>
                    </ul>
                </div>

                <!-- Form Card: Pilih Survei -->
                <div class="form-card">
                    <div class="form-card-title">Data Survei</div>
                    <label class="form-label required-field">Pilih Data Survei</label>
                    <select id="manualSurveiSelect" class="form-select">
                        <option value="">-- Pilih survei yang akan ditandai manual --</option>
                        @foreach ($surveis as $s)
                            <option value="{{ $s->id }}"
                                data-judul="{{ $s->judul }} ({{ $s->tahun }} - {{ $s->tipe }})">
                                {{ $s->judul }} ({{ $s->tahun }} - {{ $s->tipe }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Form Card: Input Koordinat -->
                <div class="form-card">
                    <div class="form-card-title">Koordinat Lokasi</div>
                    <div class="manual-input-grid">
                        <div>
                            <label class="form-label">Judul Preview (Opsional)</label>
                            <input type="text" id="manualJudul" class="form-input"
                                placeholder="Nama lokasi untuk preview">
                        </div>
                        <div>
                            <label class="form-label required-field">Lintang (Latitude)</label>
                            <input type="text" id="manualLat" class="form-input" placeholder="-6.2088">
                        </div>
                        <div>
                            <label class="form-label required-field">Bujur (Longitude)</label>
                            <input type="text" id="manualLng" class="form-input" placeholder="106.8456">
                        </div>
                    </div>

                    <div class="coordinate-actions">
                        <button type="button" id="searchCoordinateBtn" class="btn-secondary" disabled>
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                            Cari Koordinat
                        </button>
                        <p class="text-muted text-small">
                            💡 <strong>Langkah:</strong> 1) Isi koordinat → 2) Klik "Cari Koordinat" untuk preview → 3) Klik
                            "Simpan Marker Manual Permanen"
                        </p>
                    </div>

                    <!-- Button Simpan -->
                    <button type="button" id="saveManualBtn" class="btn-primary mt-3" disabled>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path
                                d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z" />
                        </svg>
                        Simpan Marker Manual Permanen
                    </button>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- PETA INDONESIA --}}
        {{-- ========================================================= --}}
        <div class="section-divider"></div>

        <div class="map-wrapper">
            <div id="map"></div>
        </div>

        {{-- ========================================================= --}}
        {{-- INFO BOX --}}
        {{-- ========================================================= --}}
        <div class="info-box">
            <strong>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                </svg>
                Petunjuk Penggunaan Sistem Marker
            </strong>
            <ul>
                <li><strong>Tab Auto Geocoding:</strong> Pilih survei dari dropdown, sistem akan mencari koordinat otomatis
                    berdasarkan wilayah, lalu klik tombol "Terapkan Marker Otomatis" untuk menyimpan</li>
                <li><strong>Tab Input Manual:</strong> Pilih survei → Isi koordinat → Klik "Cari Koordinat" untuk preview →
                    Klik "Simpan Marker Manual Permanen" untuk menyimpan</li>
                <li><strong>Marker Preview:</strong> Marker berwarna orange adalah preview sementara yang belum tersimpan
                    (muncul setelah klik "Cari Koordinat")</li>
                <li><strong>Marker Permanen:</strong> Marker berwarna biru adalah marker yang sudah tersimpan dan tidak
                    dapat dipindahkan</li>
                <li><strong>Tombol "Simpan":</strong> Hanya aktif setelah tombol "Cari Koordinat" diklik dan marker preview
                    ditampilkan</li>
                <li><strong>Menghapus Marker:</strong> Klik kanan pada marker permanen (biru) untuk menghapusnya dari peta
                </li>
                <li><strong>Batasan Peta:</strong> Peta dibatasi hanya menampilkan wilayah Indonesia (zoom minimum level 5)
                </li>
            </ul>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p id="loadingText">Memproses...</p>
        </div>
    </div>

    {{-- Success Modal --}}
    <div id="successModal" class="modal-overlay" style="display: none;">
        <div class="modal-container modal-success">
            <div class="modal-header modal-header-success">
                <h3>✓ Geocoding Berhasil</h3>
                <button class="modal-close" onclick="closeSuccessModal()">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-icon-success">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="currentColor">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                </div>
                <p class="modal-message-success">Koordinat lokasi berhasil ditemukan!</p>
                <p class="modal-detail" id="successDetail"></p>
                <p class="modal-info">Marker preview (orange) telah ditampilkan di peta. Klik tombol "Terapkan Marker
                    Otomatis" untuk menyimpan.</p>
            </div>
            <div class="modal-footer">
                <button class="btn-success" onclick="closeSuccessModal()">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                    </svg>
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    {{-- Error Modal --}}
    <div id="errorModal" class="modal-overlay" style="display: none;">
        <div class="modal-container modal-error">
            <div class="modal-header modal-header-error">
                <h3>⚠ Geocoding Gagal</h3>
                <button class="modal-close" onclick="closeErrorModal()">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-icon-error">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="currentColor">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <p class="modal-message-error">Lokasi tidak ditemukan atau tidak spesifik!</p>
                <p class="modal-detail" id="errorDetail"></p>
                <p class="modal-info">Sistem menggunakan koordinat default Indonesia. Silakan gunakan <strong>Tab Input
                        Manual</strong> untuk memasukkan koordinat yang tepat.</p>
            </div>
            <div class="modal-footer">
                <button class="btn-error" onclick="closeErrorModal()">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                    </svg>
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Konfirmasi Hapus Marker</h3>
                <button class="modal-close" onclick="closeDeleteModal()">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-icon-warning">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="currentColor">
                        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
                    </svg>
                </div>
                <p class="modal-message">Apakah Anda yakin ingin menghapus marker survei ini?</p>
                <p class="modal-title" id="deleteMarkerTitle"></p>
                <p class="modal-warning">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
                <button class="btn-delete" id="confirmDeleteBtn">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                    </svg>
                    Hapus Marker
                </button>
            </div>
        </div>
    </div>

    {{-- Edit Marker Modal --}}
    <div id="editModal" class="modal-overlay" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Edit Lokasi Marker</h3>
                <button class="modal-close" onclick="closeEditModal()">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-icon-edit">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="currentColor">
                        <path
                            d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                    </svg>
                </div>
                <p class="modal-message">Edit koordinat untuk survei:</p>
                <p class="modal-warning" id="editMarkerTitle">Loading...</p>

                <div class="edit-form">
                    <div class="form-group">
                        <label for="editLatitude">Latitude (Lintang)</label>
                        <input type="number" id="editLatitude" step="any" placeholder="Contoh: -6.2088"
                            class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="editLongitude">Longitude (Bujur)</label>
                        <input type="number" id="editLongitude" step="any" placeholder="Contoh: 106.8456"
                            class="form-input">
                    </div>
                    <div class="helper-text">
                        <small>💡 Tip: Klik pada peta untuk mendapatkan koordinat, atau masukkan koordinat manual</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button class="btn-primary" id="confirmEditBtn">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- jQuery FIRST (required for Select2) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Loading Overlay Functions
        function showLoadingOverlay(message = 'Memproses...') {
            document.getElementById('loadingText').textContent = message;
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Success Modal Functions
        function showSuccessModal(detail) {
            document.getElementById('successDetail').textContent = detail;
            document.getElementById('successModal').style.display = 'flex';
        }

        function closeSuccessModal() {
            document.getElementById('successModal').style.display = 'none';
        }

        // Error Modal Functions
        function showErrorModal(detail) {
            document.getElementById('errorDetail').textContent = detail;
            document.getElementById('errorModal').style.display = 'flex';
        }

        function closeErrorModal() {
            document.getElementById('errorModal').style.display = 'none';
        }

        // Close modals when clicking outside
        document.getElementById('successModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeSuccessModal();
        });

        document.getElementById('errorModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeErrorModal();
        });

        // Delete Modal Functions
        let currentDeleteData = null;

        // Edit Modal Functions
        let currentEditData = null;
        let editMarkerPreview = null;

        function showDeleteModal(title, surveiId, marker) {
            // Validate input parameters
            if (!title || !surveiId || !marker) {
                alert('Data marker tidak lengkap untuk dihapus!');
                console.error('Invalid delete modal data:', {
                    title,
                    surveiId,
                    marker
                });
                return;
            }

            // Ensure surveiId is valid
            if (surveiId === 'undefined' || surveiId === 'null' || surveiId === '') {
                alert('ID survei tidak valid!');
                console.error('Invalid surveiId:', surveiId);
                return;
            }

            currentDeleteData = {
                title: title || 'Unknown Survey',
                surveiId: String(surveiId), // Ensure it's a string
                marker
            };

            document.getElementById('deleteMarkerTitle').textContent = title || 'Unknown Survey';
            document.getElementById('deleteModal').style.display = 'flex';

            console.log('Delete modal opened for:', currentDeleteData);
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteData = null;
        }

        // Confirm Delete Handler
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', async function() {
            if (!currentDeleteData) {
                alert('Data marker tidak ditemukan!');
                return;
            }

            const {
                title,
                surveiId,
                marker
            } = currentDeleteData;

            // Validate surveiId
            if (!surveiId || surveiId === 'undefined' || surveiId === 'null') {
                alert('ID survei tidak valid!');
                closeDeleteModal();
                return;
            }

            closeDeleteModal();
            showLoadingOverlay('Menghapus marker...');

            try {
                const response = await fetch(`/admin/lokasi-marker/${surveiId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                hideLoadingOverlay();

                if (response.ok) {
                    // Try to parse JSON response
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        // If not JSON, assume success
                        data = {
                            success: true,
                            message: 'Marker berhasil dihapus!'
                        };
                    }

                    if (data.success !== false) {
                        // Remove marker from map using tracking
                        const trackedMarker = permanentMarkers.get(String(surveiId));
                        if (trackedMarker) {
                            try {
                                map.removeLayer(trackedMarker);
                                permanentMarkers.delete(String(surveiId));
                                console.log('Marker removed successfully from map');
                            } catch (e) {
                                console.log('Error removing tracked marker:', e);
                            }
                        }

                        // Also try to remove the original marker if different
                        if (marker && marker !== trackedMarker) {
                            try {
                                map.removeLayer(marker);
                            } catch (e) {
                                console.log('Error removing original marker:', e);
                            }
                        }

                        // Add options back to Select2 dropdowns
                        const optionText = title || 'Data Survei';
                        $('#surveiSelect').append(new Option(optionText, surveiId));
                        $('#manualSurveiSelect').append(new Option(optionText, surveiId));

                        alert(data.message || 'Marker berhasil dihapus!');

                        // No need to reload since we properly removed the marker
                        console.log('Delete operation completed successfully');
                    } else {
                        alert('Gagal menghapus marker: ' + (data.message || 'Unknown error'));
                    }
                } else {
                    // Handle HTTP error status
                    let errorMessage = 'Gagal menghapus marker!';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = `HTTP Error ${response.status}: ${response.statusText}`;
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                hideLoadingOverlay();
                alert('Terjadi error saat menghapus marker: ' + error.message);
                console.error('Delete marker error:', error);
            }

            // Reset currentDeleteData
            currentDeleteData = null;
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // ============================================================
        // EDIT MODAL FUNCTIONS
        // ============================================================

        function showEditModal(title, surveiId, marker, currentLat, currentLng) {
            // Validate input parameters
            if (!title || !surveiId || !marker) {
                alert('Data marker tidak lengkap untuk diedit!');
                console.error('Invalid edit modal data:', {
                    title,
                    surveiId,
                    marker
                });
                return;
            }

            currentEditData = {
                title: title || 'Unknown Survey',
                surveiId: String(surveiId),
                marker,
                originalLat: currentLat,
                originalLng: currentLng
            };

            // Set modal content
            document.getElementById('editMarkerTitle').textContent = title || 'Unknown Survey';
            document.getElementById('editLatitude').value = currentLat || '';
            document.getElementById('editLongitude').value = currentLng || '';

            // Show modal
            document.getElementById('editModal').style.display = 'flex';

            console.log('Edit modal opened for:', currentEditData);
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';

            // Remove edit preview marker if exists
            if (editMarkerPreview) {
                map.removeLayer(editMarkerPreview);
                editMarkerPreview = null;
            }

            currentEditData = null;
        }

        // Confirm Edit Handler
        document.getElementById('confirmEditBtn')?.addEventListener('click', async function() {
            if (!currentEditData) {
                alert('Data marker tidak ditemukan!');
                return;
            }

            const newLat = parseFloat(document.getElementById('editLatitude').value);
            const newLng = parseFloat(document.getElementById('editLongitude').value);

            // Validate coordinates
            if (isNaN(newLat) || isNaN(newLng)) {
                alert('Koordinat tidak valid! Pastikan latitude dan longitude berupa angka.');
                return;
            }

            if (newLat < -11 || newLat > 6 || newLng < 95 || newLng > 141) {
                alert(
                    'Koordinat berada di luar wilayah Indonesia! Pastikan koordinat berada dalam batas Indonesia.'
                );
                return;
            }

            const {
                title,
                surveiId,
                marker
            } = currentEditData;

            closeEditModal();
            showLoadingOverlay('Mengupdate marker...');

            try {
                const response = await fetch(`/admin/lokasi-marker/${surveiId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        pusat_lintang: newLat,
                        pusat_bujur: newLng
                    })
                });

                hideLoadingOverlay();

                if (response.ok) {
                    const data = await response.json();

                    if (data.success !== false) {
                        // Update marker position on map
                        const trackedMarker = permanentMarkers.get(String(surveiId));
                        if (trackedMarker) {
                            trackedMarker.setLatLng([newLat, newLng]);
                        }

                        // Update original marker if different
                        if (marker && marker !== trackedMarker) {
                            marker.setLatLng([newLat, newLng]);
                        }

                        // Center map on updated marker
                        map.setView([newLat, newLng], 8);

                        alert('✓ Marker berhasil diupdate!');
                        console.log('Marker updated successfully');
                    } else {
                        throw new Error(data.message || 'Update gagal');
                    }
                } else {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
            } catch (error) {
                hideLoadingOverlay();
                console.error('Error updating marker:', error);
                alert('❌ Gagal mengupdate marker: ' + error.message);
            }

            // Reset currentEditData
            currentEditData = null;
        });

        // Close edit modal when clicking outside
        document.getElementById('editModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Map click handler for edit modal coordinate selection
        function enableEditMapClick() {
            if (!currentEditData) return;

            map.on('click', function(e) {
                if (currentEditData) {
                    const lat = e.latlng.lat.toFixed(6);
                    const lng = e.latlng.lng.toFixed(6);

                    // Update input fields
                    document.getElementById('editLatitude').value = lat;
                    document.getElementById('editLongitude').value = lng;

                    // Remove previous preview
                    if (editMarkerPreview) {
                        map.removeLayer(editMarkerPreview);
                    }

                    // Add preview marker
                    editMarkerPreview = L.marker([lat, lng], {
                        icon: orangeIcon
                    }).addTo(map).bindPopup(`
                        <strong>Preview Lokasi Baru</strong><br>
                        Lat: ${lat}<br>
                        Lng: ${lng}
                    `);
                }
            });
        }

        // GLOBAL VARIABLES - Accessible to all functions
        let map, permanentMarkers, orangeIcon;
        let surveiSelect, autoApplyBtn, geoLatInput, geoLngInput;
        let manualSurveiSelect, manualJudulInput, manualLatInput, manualLngInput, saveManualBtn, searchCoordinateBtn;
        let coordinateSearched = false; // Flag to track if "Cari Koordinat" has been clicked

        // GLOBAL FUNCTION - Create Permanent Marker
        function createPermanentMarker(lat, lng, title, surveiId) {
            // Convert to numbers
            lat = parseFloat(lat);
            lng = parseFloat(lng);

            // Validate input parameters
            if (isNaN(lat) || isNaN(lng) || !title || !surveiId) {
                console.error('Invalid marker data:', {
                    lat,
                    lng,
                    title,
                    surveiId
                });
                return null;
            }

            const marker = L.marker([lat, lng]).addTo(map);

            // Create popup with edit and delete buttons - styled like public peta
            const popupContent = `
                <div class="admin-popup-content">
                    <h4 class="admin-popup-title">${title}</h4>
                    <div class="admin-popup-coordinates">
                        ${lat.toFixed(6)}°, ${lng.toFixed(6)}°
                    </div>
                    <div class="admin-popup-actions">
                        <a href="/bbspgl-admin/data-survei/${surveiId}?from=lokasi_marker" class="admin-popup-btn admin-popup-btn-view">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                            Lihat Detail
                        </a>
                        <button class="admin-popup-btn admin-popup-btn-edit admin-popup-btn-icon marker-edit-btn" title="Edit Lokasi" onclick="showEditModal('${title.replace(/'/g, "\\'")}', '${surveiId}', this.marker, ${lat}, ${lng}); enableEditMapClick();">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                        </button>
                        <button class="admin-popup-btn admin-popup-btn-delete admin-popup-btn-icon marker-delete-btn" title="Hapus Marker" onclick="showDeleteModal('${title.replace(/'/g, "\\'")}', '${surveiId}', this.marker);">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;




            marker.bindPopup(popupContent);

            // Store reference to marker in popup buttons
            marker.on('popupopen', function() {
                const popup = marker.getPopup();
                const editBtn = popup.getElement().querySelector('.marker-edit-btn');
                const deleteBtn = popup.getElement().querySelector('.marker-delete-btn');
                if (editBtn) editBtn.marker = marker;
                if (deleteBtn) deleteBtn.marker = marker;
            });

            // Store surveiId in marker for reference
            marker.surveiId = surveiId;
            marker.surveyTitle = title;

            // Track this marker
            permanentMarkers.set(String(surveiId), marker);

            return marker;
        }

        document.addEventListener("DOMContentLoaded", function() {
            // ============================================================
            // INITIALIZE SELECT2 - Modern Dropdown with Search
            // ============================================================

            // Wait for jQuery and Select2 to be fully loaded
            function initializeSelect2() {
                if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
                    console.log('jQuery or Select2 not loaded yet, retrying...');
                    setTimeout(initializeSelect2, 100);
                    return;
                }

                try {
                    $('#surveiSelect').select2({
                        placeholder: '🔍 Cari atau pilih survei yang akan ditandai di peta...',
                        allowClear: true,
                        width: '100%',
                        theme: 'default',
                        language: {
                            noResults: function() {
                                return "Tidak ada data survei yang ditemukan";
                            },
                            searching: function() {
                                return "Mencari...";
                            }
                        }
                    });

                    $('#manualSurveiSelect').select2({
                        placeholder: '🔍 Cari atau pilih survei yang akan ditandai manual...',
                        allowClear: true,
                        width: '100%',
                        theme: 'default',
                        language: {
                            noResults: function() {
                                return "Tidak ada data survei yang ditemukan";
                            },
                            searching: function() {
                                return "Mencari...";
                            }
                        }
                    });

                    console.log('Select2 initialized successfully');
                } catch (error) {
                    console.error('Error initializing Select2:', error);
                }
            }

            // Initialize Select2 with retry mechanism
            initializeSelect2();

            // BATAS PETA INDONESIA
            const indonesiaBounds = L.latLngBounds(
                L.latLng(-11.5, 94.0),
                L.latLng(7.0, 142.0)
            );

            map = L.map('map', {
                maxBounds: indonesiaBounds,
                maxBoundsViscosity: 1.0,
                minZoom: 5,
                maxZoom: 18
            }).fitBounds(indonesiaBounds);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const markersData = @json($markers);

            // Marker Icon untuk Geocoding Preview (Orange)
            orangeIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Elemen Global
            surveiSelect = document.getElementById('surveiSelect');
            autoApplyBtn = document.getElementById('autoApplyBtn');
            geoLatInput = document.getElementById('geoLat');
            geoLngInput = document.getElementById('geoLng');

            // Elemen Manual
            manualSurveiSelect = document.getElementById('manualSurveiSelect');
            manualJudulInput = document.getElementById('manualJudul');
            manualLatInput = document.getElementById('manualLat');
            manualLngInput = document.getElementById('manualLng');
            saveManualBtn = document.getElementById('saveManualBtn');
            searchCoordinateBtn = document.getElementById('searchCoordinateBtn');

            // Debug: Check if elements are found
            console.log('Elements found:', {
                manualLatInput: !!manualLatInput,
                manualLngInput: !!manualLngInput,
                searchCoordinateBtn: !!searchCoordinateBtn,
                saveManualBtn: !!saveManualBtn
            });

            // Track all permanent markers for easier management
            permanentMarkers = new Map(); // surveiId -> marker

            /* ============================================================
                1. LOAD MARKER DARI DATABASE
                ============================================================ */
            markersData.forEach(m => {
                createPermanentMarker(m.pusat_lintang, m.pusat_bujur, m.survei.judul, m.id_data_survei);
            });

            /* ============================================================
                2. UTILITAS GEOCODING & CLEAR MARKER
                ============================================================ */
            async function geocodeWilayah(wilayah) {
                const url = `{{ route('admin.geocode') }}?lokasi=${encodeURIComponent(wilayah)}`;
                const response = await fetch(url, {
                    cache: 'no-cache'
                });
                return await response.json();
            }

            function clearAutoMarker() {
                if (window.autoMarker) {
                    map.removeLayer(window.autoMarker);
                    window.autoMarker = null;
                }
                geoLatInput.value = '';
                geoLngInput.value = '';
                autoApplyBtn.disabled = true;
            }

            function clearManualPreview() {
                map.eachLayer(layer => {
                    if (layer instanceof L.Marker && layer.options.manualPreview) {
                        map.removeLayer(layer);
                    }
                });
                updateManualSaveButtonState();
            }

            /* ============================================================
                3. FUNGSI UTAMA: TAMPILKAN PREVIEW LOKASI (Auto Geocoding)
                ============================================================ */
            async function handleGeocoding(surveiId, wilayah, judul) {
                clearAutoMarker();

                if (!surveiId) return;

                // Show loading state
                showLoadingOverlay('Mencari koordinat lokasi...');

                try {
                    const geo = await geocodeWilayah(wilayah);

                    hideLoadingOverlay();

                    // Jika geocoding gagal atau fallback
                    if (geo.error || geo.fallback) {
                        const errorMessage = `Wilayah: "${wilayah}"`;
                        showErrorModal(errorMessage);

                        if (geo.fallback) {
                            map.setView([geo.lat, geo.lon], 5);
                        }
                        return;
                    }

                    // Geocoding berhasil
                    const lat = parseFloat(geo.lat);
                    const lng = parseFloat(geo.lon);

                    map.setView([lat, lng], 8);

                    window.autoMarker = L.marker([lat, lng], {
                            icon: orangeIcon
                        }).addTo(map)
                        .bindPopup(`
                    <b>${judul}</b><br>
                    Wilayah: ${wilayah}<br>
                    <hr>
                    <small>${geo.display_name || 'Lokasi Ditemukan'}</small><br>
                    <small style="color: #f57c00;"><strong>Preview</strong> - Klik tombol untuk simpan</small>
                `).openPopup();

                    geoLatInput.value = lat;
                    geoLngInput.value = lng;
                    autoApplyBtn.disabled = false;

                    // Show success modal
                    const successMessage =
                        `${geo.display_name || wilayah}\nKoordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    showSuccessModal(successMessage);

                } catch (error) {
                    hideLoadingOverlay();
                    showErrorModal('Terjadi kesalahan sistem saat mencari lokasi.');
                    console.error('Geocoding error:', error);
                }
            }

            // Event handler untuk Select2 Auto Geocoding
            $('#surveiSelect').on('change', async function() {
                const surveiId = $(this).val();

                if (!surveiId) {
                    clearAutoMarker();
                    return;
                }

                const selectedOption = $(this).find('option:selected');
                const wilayah = selectedOption.data('wilayah');
                const judul = selectedOption.data('judul');

                if (!wilayah) {
                    alert("Data survei tidak memiliki wilayah!");
                    return;
                }

                await handleGeocoding(surveiId, wilayah, judul);
            });

            /* ============================================================
                4. SIMPAN MARKER OTOMATIS
                ============================================================ */
            autoApplyBtn?.addEventListener('click', async function() {
                const surveiId = surveiSelect.value;
                const lat = geoLatInput.value;
                const lng = geoLngInput.value;
                const selectedOption = surveiSelect.options[surveiSelect.selectedIndex];
                const title = selectedOption.dataset.judul;

                if (!surveiId || !lat || !lng) {
                    return alert('Harap pilih survei dan pastikan marker preview sudah muncul.');
                }

                autoApplyBtn.disabled = true;

                const latlng = L.latLng(parseFloat(lat), parseFloat(lng));
                clearAutoMarker();
                const newMarker = L.marker(latlng).addTo(map);
                newMarker.bindPopup(`<b>${title}</b><br>Menyimpan...`).openPopup();

                fetch('/admin/lokasi-marker', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_data_survei: surveiId,
                            pusat_lintang: lat,
                            pusat_bujur: lng
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            map.removeLayer(newMarker);
                            createPermanentMarker(lat, lng, title, surveiId);

                            // Remove option from both Select2 dropdowns
                            $('#surveiSelect option[value="' + surveiId + '"]').remove();
                            $('#manualSurveiSelect option[value="' + surveiId + '"]').remove();

                            // Trigger change to update Select2
                            $('#surveiSelect').val(null).trigger('change');
                            $('#manualSurveiSelect').val(null).trigger('change');

                            alert(data.message);
                        } else {
                            map.removeLayer(newMarker);
                            alert('Gagal menyimpan: ' + data.message);
                        }
                    }).catch(error => {
                        map.removeLayer(newMarker);
                        alert('Terjadi error saat menyimpan!');
                        console.error('Error:', error);
                    }).finally(() => {
                        autoApplyBtn.disabled = false;
                    });
            });

            /* ============================================================
                5. UPDATE STATUS TOMBOL SIMPAN MANUAL & SEARCH COORDINATE
                ============================================================ */
            const updateManualSaveButtonState = () => {
                const surveiSelected = !!manualSurveiSelect.value;

                // Debug input values
                const latValue = manualLatInput.value;
                const lngValue = manualLngInput.value;
                const latTrimmed = latValue.trim().replace(/[^\d.-]/g,
                    ''); // Remove non-numeric chars except . and -
                const lngTrimmed = lngValue.trim().replace(/[^\d.-]/g,
                    ''); // Remove non-numeric chars except . and -
                const latParsed = parseFloat(latTrimmed);
                const lngParsed = parseFloat(lngTrimmed);

                console.log('Input debug:', {
                    latValue: `"${latValue}"`,
                    lngValue: `"${lngValue}"`,
                    latTrimmed: `"${latTrimmed}"`,
                    lngTrimmed: `"${lngTrimmed}"`,
                    latParsed,
                    lngParsed,
                    latIsNaN: isNaN(latParsed),
                    lngIsNaN: isNaN(lngParsed)
                });

                const latValid = !isNaN(latParsed) && latTrimmed !== "";
                const lngValid = !isNaN(lngParsed) && lngTrimmed !== "";

                console.log('Button state update:', {
                    surveiSelected,
                    latValid,
                    lngValid,
                    coordinateSearched
                });

                // Save button only enabled if survei selected, coordinates valid, AND "Cari Koordinat" has been clicked
                if (surveiSelected && latValid && lngValid && coordinateSearched) {
                    saveManualBtn.disabled = false;
                } else {
                    saveManualBtn.disabled = true;
                }

                // Update search coordinate button state
                if (searchCoordinateBtn) {
                    if (latValid && lngValid) {
                        searchCoordinateBtn.disabled = false;
                        console.log('Search coordinate button enabled');
                    } else {
                        searchCoordinateBtn.disabled = true;
                        console.log('Search coordinate button disabled');
                    }
                } else {
                    console.error('searchCoordinateBtn not found!');
                }
            };

            /* ============================================================
                6. TAMPILKAN MARKER PREVIEW MANUAL
                ============================================================ */
            function showManualPreview() {
                const surveiId = manualSurveiSelect.value;
                const latStr = manualLatInput.value.trim();
                const lngStr = manualLngInput.value.trim();

                clearManualPreview();

                if (!surveiId) return;

                const lat = parseFloat(latStr);
                const lng = parseFloat(lngStr);

                if (isNaN(lat) || isNaN(lng) || latStr === "" || lngStr === "") {
                    updateManualSaveButtonState();
                    return;
                }

                const selectedOption = manualSurveiSelect.options[manualSurveiSelect.selectedIndex];
                const judulSurvei = selectedOption.dataset.judul;
                const judul = manualJudulInput.value.trim() || judulSurvei;

                const latlng = L.latLng(lat, lng);
                map.setView(latlng, 10);

                const tempManualMarker = L.marker(latlng, {
                    manualPreview: true,
                    icon: orangeIcon
                }).addTo(map);
                tempManualMarker.bindPopup(`
            <b>${judul}</b><br>
            Lat: ${lat.toFixed(6)}<br>
            Lng: ${lng.toFixed(6)}<br>
            <hr>
            <small style="color: #f57c00;"><strong>Preview Manual</strong> - Klik tombol untuk simpan</small>
        `).openPopup();

                updateManualSaveButtonState();
            }

            /* ============================================================
                7. SIMPAN MARKER MANUAL PERMANEN
                ============================================================ */
            saveManualBtn?.addEventListener('click', async function() {
                const surveiId = manualSurveiSelect.value;
                const lat = manualLatInput.value;
                const lng = manualLngInput.value;
                const selectedOption = manualSurveiSelect.options[manualSurveiSelect.selectedIndex];
                const title = selectedOption.dataset.judul;

                if (!surveiId) {
                    return alert('Harap pilih Data Survei terlebih dahulu.');
                }

                if (!lat || !lng || isNaN(parseFloat(lat)) || isNaN(parseFloat(lng))) {
                    return alert('Koordinat tidak valid.');
                }

                saveManualBtn.disabled = true;

                const latFloat = parseFloat(lat);
                const lngFloat = parseFloat(lng);
                const latlng = L.latLng(latFloat, lngFloat);

                clearManualPreview();
                const newMarker = L.marker(latlng).addTo(map);
                newMarker.bindPopup(`<b>${title}</b><br>Menyimpan...`).openPopup();

                fetch('/admin/lokasi-marker', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_data_survei: surveiId,
                            pusat_lintang: latFloat,
                            pusat_bujur: lngFloat
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            map.removeLayer(newMarker);
                            createPermanentMarker(latFloat, lngFloat, title, surveiId);

                            // Remove option from both Select2 dropdowns
                            $('#surveiSelect option[value="' + surveiId + '"]').remove();
                            $('#manualSurveiSelect option[value="' + surveiId + '"]').remove();

                            // Clear and trigger change to update Select2
                            $('#surveiSelect').val(null).trigger('change');
                            $('#manualSurveiSelect').val(null).trigger('change');

                            alert(data.message);
                            manualLatInput.value = "";
                            manualLngInput.value = "";
                            manualJudulInput.value = "";
                        } else {
                            map.removeLayer(newMarker);
                            alert('Gagal menyimpan: ' + data.message);
                        }
                    }).catch(error => {
                        map.removeLayer(newMarker);
                        alert('Terjadi error saat menyimpan!');
                        console.error('Error:', error);
                    }).finally(() => {
                        saveManualBtn.disabled = false;
                    });
            });

            /* ============================================================
                8. SEARCH COORDINATE BUTTON HANDLER
                ============================================================ */
            searchCoordinateBtn?.addEventListener('click', function() {
                const latStr = manualLatInput.value.trim().replace(/[^\d.-]/g, ''); // Clean input
                const lngStr = manualLngInput.value.trim().replace(/[^\d.-]/g, ''); // Clean input

                if (!latStr || !lngStr) {
                    alert('Harap isi koordinat Lintang dan Bujur terlebih dahulu!');
                    return;
                }

                const lat = parseFloat(latStr);
                const lng = parseFloat(lngStr);

                if (isNaN(lat) || isNaN(lng)) {
                    alert(
                        'Format koordinat tidak valid! Gunakan format desimal (contoh: -6.2088, 106.8456)'
                    );
                    return;
                }

                // Validate coordinates are within Indonesia bounds
                if (lat < -11.5 || lat > 7.0 || lng < 94.0 || lng > 142.0) {
                    alert(
                        'Koordinat di luar batas Indonesia!\nLintang: -11.5 hingga 7.0\nBujur: 94.0 hingga 142.0'
                    );
                    return;
                }

                // Show loading overlay for visual feedback
                showLoadingOverlay('Mencari koordinat...');

                // Set flag that coordinate search has been performed
                coordinateSearched = true;

                // Small delay to allow loading overlay to render
                setTimeout(function() {
                    // Move map to coordinates and show preview
                    map.setView([lat, lng], 12);
                    showManualPreview();

                    // Update button states after successful search
                    updateManualSaveButtonState();

                    // Hide loading overlay
                    hideLoadingOverlay();

                    // Show success message
                    alert(
                        `Peta berhasil diarahkan ke koordinat:\nLintang: ${lat.toFixed(6)}\nBujur: ${lng.toFixed(6)}\n\nSekarang Anda dapat menyimpan marker dengan tombol "Simpan Marker Manual Permanen"`
                    );
                }, 300);
            });

            /* ============================================================
                9. EVENT: KLIK PADA PETA
                ============================================================ */
            map.on('click', function(e) {
                const activeTab = document.querySelector('.tab-btn.active').dataset.tab;

                if (activeTab === 'tab-dropdown') {
                    const surveiId = surveiSelect.value;
                    if (surveiId) {
                        alert('Gunakan tombol "Terapkan Marker Otomatis" untuk menyimpan!');
                    } else {
                        alert('Pilih survei terlebih dahulu!');
                    }
                    return;
                } else {
                    const surveiId = manualSurveiSelect.value;
                    if (!surveiId) return alert('Pilih Data Survei terlebih dahulu!');

                    // Check if coordinates are already filled
                    const currentLat = manualLatInput.value.trim();
                    const currentLng = manualLngInput.value.trim();

                    if (currentLat && currentLng) {
                        // If coordinates are filled, ask user if they want to replace
                        if (confirm(
                                'Koordinat sudah diisi. Apakah Anda ingin mengganti dengan koordinat dari klik peta?'
                            )) {
                            manualLatInput.value = e.latlng.lat.toFixed(6);
                            manualLngInput.value = e.latlng.lng.toFixed(6);
                            coordinateSearched = false; // Reset flag when coordinates change
                            showManualPreview();
                        }
                    } else {
                        // If empty, set coordinates from map click
                        manualLatInput.value = e.latlng.lat.toFixed(6);
                        manualLngInput.value = e.latlng.lng.toFixed(6);
                        coordinateSearched = false; // Reset flag when coordinates change
                        showManualPreview();
                    }
                    return;
                }
            });

            /* ============================================================
                10. EVENT LISTENERS
                ============================================================ */
            [manualLatInput, manualLngInput].forEach(element => {
                // Multiple event listeners to catch all changes
                ['input', 'keyup', 'change', 'paste'].forEach(eventType => {
                    element.addEventListener(eventType, () => {
                        // Reset flag when coordinates are manually changed
                        coordinateSearched = false;

                        // Small delay to allow paste to complete
                        setTimeout(() => {
                            showManualPreview();
                            updateManualSaveButtonState();
                        }, 10);
                    });
                });
            });

            // Event handler untuk Select2 Manual Input
            $('#manualSurveiSelect').on('change', function() {
                manualLatInput.value = "";
                manualLngInput.value = "";
                coordinateSearched = false; // Reset flag when survei changes
                clearManualPreview();
                updateManualSaveButtonState();
            });

            updateManualSaveButtonState();

            // Tab Switch 
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.tab-btn, .tab-content').forEach(el => {
                        el.classList.remove('active');
                    });
                    btn.classList.add('active');
                    document.getElementById(btn.dataset.tab).classList.add('active');
                    clearAutoMarker();
                    clearManualPreview();
                });
            });

            // CRITICAL FIX: Initialize button state on page load
            setTimeout(() => {
                updateManualSaveButtonState();
                console.log('Initial button state check completed');
            }, 500);

            // CRITICAL FIX: Periodic check for test runner compatibility
            // This ensures button state updates even if events are missed
            setInterval(() => {
                updateManualSaveButtonState();
            }, 1000);
        });
    </script>
@endpush
