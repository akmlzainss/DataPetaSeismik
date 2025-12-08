{{-- resources/views/admin/lokasi_marker/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Lokasi Marker Survei Seismik - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        <li style="margin-bottom: 12px;">
                            Gunakan format: <strong>Nama Kota/Kabupaten, Provinsi</strong><br>
                            <small style="color: #28a745; margin-top: 2px; display: inline-block;">✓ Contoh: "Bandung, Jawa
                                Barat" atau "Surabaya, Jawa Timur"</small>
                        </li>
                        <li style="margin-bottom: 12px;">
                            Untuk wilayah laut: <strong>Nama Laut/Selat + Provinsi Terdekat</strong><br>
                            <small style="color: #28a745; margin-top: 2px; display: inline-block;">✓ Contoh: "Selat Sunda,
                                Banten" atau "Laut Jawa, Jawa Tengah"</small>
                        </li>
                        <li style="margin-bottom: 12px;">
                            Untuk blok survei: <strong>Nama Blok + Wilayah Laut</strong><br>
                            <small style="color: #28a745; margin-top: 2px; display: inline-block;">✓ Contoh: "Blok Masela,
                                Laut Arafura" atau "Blok Cepu, Jawa Timur"</small>
                        </li>
                        <li style="margin-bottom: 12px;">
                            Hindari singkatan atau kode yang tidak umum<br>
                            <small style="color: #dc3545; margin-top: 2px; display: inline-block;">✗ Contoh: "BDG" atau
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
                        <strong>Petunjuk:</strong> Pilih survei → Isi koordinat manual atau klik di peta → Klik tombol
                        "Simpan Marker Manual Permanen"
                    </div>
                </div>

                <!-- Tips Koordinat -->
                <div class="helper-box" style="margin-bottom: 16px;">
                    <strong>💡 Tips Input Koordinat Manual:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        <li style="margin-bottom: 8px;"><strong>Lintang (Latitude):</strong> Nilai antara -11 hingga 6 untuk
                            wilayah Indonesia (negatif = Selatan)</li>
                        <li style="margin-bottom: 8px;"><strong>Bujur (Longitude):</strong> Nilai antara 95 hingga 141 untuk
                            wilayah Indonesia (positif = Timur)</li>
                        <li style="margin-bottom: 8px;">Format desimal: -6.2088, 106.8456 (gunakan titik, bukan koma)</li>
                        <li>Klik langsung di peta untuk mengisi koordinat secara otomatis</li>
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

                    <p class="text-muted text-small">
                        💡 Anda dapat mengklik langsung di peta untuk mengisi koordinat secara otomatis
                    </p>

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
                <li><strong>Tab Input Manual:</strong> Pilih survei, isi koordinat manual atau klik di peta, lalu klik
                    tombol "Simpan Marker Manual Permanen"</li>
                <li><strong>Marker Preview:</strong> Marker berwarna orange adalah preview sementara yang belum tersimpan
                </li>
                <li><strong>Marker Permanen:</strong> Marker berwarna biru adalah marker yang sudah tersimpan dan tidak
                    dapat dipindahkan</li>
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
@endsection

@push('scripts')
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

        function showDeleteModal(title, surveiId, marker) {
            currentDeleteData = {
                title,
                surveiId,
                marker
            };
            document.getElementById('deleteMarkerTitle').textContent = title;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteData = null;
        }

        // Confirm Delete Handler
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
            if (!currentDeleteData) return;

            const {
                title,
                surveiId,
                marker
            } = currentDeleteData;

            closeDeleteModal();
            showLoadingOverlay('Menghapus marker...');

            fetch(`/admin/lokasi-marker/${surveiId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                hideLoadingOverlay();
                if (response.ok) {
                    map.removeLayer(marker);
                    alert('Marker berhasil dihapus! Halaman akan dimuat ulang.');
                    location.reload();
                } else {
                    alert('Gagal menghapus marker!');
                }
            }).catch(error => {
                hideLoadingOverlay();
                alert('Terjadi error saat menghapus marker!');
                console.error('Error:', error);
            });
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            // BATAS PETA INDONESIA
            const indonesiaBounds = L.latLngBounds(
                L.latLng(-11.5, 94.0),
                L.latLng(7.0, 142.0)
            );

            const map = L.map('map', {
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
            const orangeIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Elemen Global
            const surveiSelect = document.getElementById('surveiSelect');
            const autoApplyBtn = document.getElementById('autoApplyBtn');
            const geoLatInput = document.getElementById('geoLat');
            const geoLngInput = document.getElementById('geoLng');

            // Elemen Manual
            const manualSurveiSelect = document.getElementById('manualSurveiSelect');
            const manualJudulInput = document.getElementById('manualJudul');
            const manualLatInput = document.getElementById('manualLat');
            const manualLngInput = document.getElementById('manualLng');
            const saveManualBtn = document.getElementById('saveManualBtn');

            /* ============================================================
                1. LOAD MARKER DARI DATABASE & EVENT HAPUS (CONTEXTMENU)
                ============================================================ */
            function createPermanentMarker(lat, lng, title, surveiId) {
                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup(`<b>${title}</b><br><small>Klik kanan untuk hapus</small>`);

                marker.on('contextmenu', (e) => {
                    L.DomEvent.preventDefault(e);
                    if (!surveiId) return;

                    showDeleteModal(title, surveiId, marker);
                });
                return marker;
            }

            markersData.forEach(m => {
                createPermanentMarker(m.pusat_lintang, m.pusat_bujur, m.survei.judul, m.id_data_survei);
            });

            /* ============================================================
                2. UTILITAS GEOCODING & CLEAR MARKER
                ============================================================ */
            async function geocodeWilayah(wilayah) {
                const url = `/admin/geocode?lokasi=${encodeURIComponent(wilayah)}`;
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

            surveiSelect?.addEventListener('change', async function() {
                const surveiId = this.value;
                const selectedOption = this.options[this.selectedIndex];

                if (!surveiId) {
                    clearAutoMarker();
                    return;
                }

                const wilayah = selectedOption.dataset.wilayah;
                const judul = selectedOption.dataset.judul;

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
                            surveiSelect.remove(selectedOption.index);
                            const manualOption = manualSurveiSelect.querySelector(
                                `option[value="${surveiId}"]`);
                            if (manualOption) manualSurveiSelect.remove(manualOption.index);
                            alert(data.message);
                            surveiSelect.value = "";
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
                5. UPDATE STATUS TOMBOL SIMPAN MANUAL
                ============================================================ */
            const updateManualSaveButtonState = () => {
                const surveiSelected = !!manualSurveiSelect.value;
                const latValid = !isNaN(parseFloat(manualLatInput.value)) && manualLatInput.value.trim() !== "";
                const lngValid = !isNaN(parseFloat(manualLngInput.value)) && manualLngInput.value.trim() !== "";

                if (surveiSelected && latValid && lngValid) {
                    saveManualBtn.disabled = false;
                } else {
                    saveManualBtn.disabled = true;
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
                            manualSurveiSelect.remove(selectedOption.index);
                            const autoOption = surveiSelect.querySelector(
                                `option[value="${surveiId}"]`);
                            if (autoOption) surveiSelect.remove(autoOption.index);
                            alert(data.message);
                            manualSurveiSelect.value = "";
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
                8. EVENT: KLIK PADA PETA
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

                    manualLatInput.value = e.latlng.lat.toFixed(6);
                    manualLngInput.value = e.latlng.lng.toFixed(6);
                    showManualPreview();
                    return;
                }
            });

            /* ============================================================
                9. EVENT LISTENERS
                ============================================================ */
            [manualLatInput, manualLngInput].forEach(element => {
                element.addEventListener('input', () => {
                    showManualPreview();
                });
            });

            manualSurveiSelect.addEventListener('change', () => {
                manualLatInput.value = "";
                manualLngInput.value = "";
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
        });
    </script>
@endpush
