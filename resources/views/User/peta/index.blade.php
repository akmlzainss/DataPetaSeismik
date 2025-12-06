@extends('layouts.app')

@section('title', 'Peta Interaktif Survei Geologi Kelautan')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/public-peta.css') }}">
@endpush

@section('content')
    <div class="peta-container">
        <!-- Header -->
        <div class="peta-header">
            <h1 class="peta-title">Peta Interaktif Survei Geologi Kelautan</h1>
            <p class="peta-subtitle">Lokasi real-time survei geologi kelautan BBSPGL di seluruh Indonesia</p>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_marker'] }}</div>
                <p class="stat-label">Total Marker</p>
            </div>

            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_survei'] }}</div>
                <p class="stat-label">Survei Terdata</p>
            </div>

            <div class="stat-card">
                <div class="stat-number">{{ $stats['tahun_terbaru'] }}</div>
                <p class="stat-label">Tahun Terakhir</p>
            </div>

            <div class="stat-card">
                <div class="stat-breakdown">
                    <div class="stat-breakdown-item">
                        <div class="stat-breakdown-number">{{ $stats['tipe_data']['2D'] }}</div>
                        <div class="stat-breakdown-label">2D</div>
                    </div>
                    <div class="stat-breakdown-item">
                        <div class="stat-breakdown-number">{{ $stats['tipe_data']['3D'] }}</div>
                        <div class="stat-breakdown-label">3D</div>
                    </div>
                    <div class="stat-breakdown-item">
                        <div class="stat-breakdown-number">{{ $stats['tipe_data']['HR'] }}</div>
                        <div class="stat-breakdown-label">HR</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <div class="map-wrapper">
                <div id="map">
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-spinner"></div>
                        <div class="loading-text">Memuat peta interaktif...</div>
                    </div>
                </div>


            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <h3 class="info-title">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                </svg>
                Panduan Penggunaan
            </h3>
            <ul class="info-list">
                <li><strong>Zoom & Navigasi:</strong> Gunakan scroll mouse atau kontrol zoom untuk memperbesar/memperkecil
                    peta</li>
                <li><strong>Filter Marker:</strong> Klik tombol filter (Semua, 2D, 3D, HR) untuk menampilkan jenis survei
                    tertentu</li>
                <li><strong>Info Marker:</strong> Klik pada marker untuk melihat detail singkat survei dan akses tombol
                    "Lihat Detail"</li>
                <li><strong>View Detail:</strong> Tombol "Lihat Detail" akan mengarahkan ke halaman detail survei dengan
                    breadcrumb "Peta"</li>
                <li><strong>Data Real-time:</strong> Marker menampilkan lokasi survei yang telah ditambahkan oleh tim admin
                    BBSPGL</li>
            </ul>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create custom blue icon like admin
                const blueIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                console.log('Blue marker icon configured successfully');

                // Data markers dari database
                const markersData = @json($markers);

                console.log('Markers data loaded:', markersData);
                console.log('Markers count:', markersData.length);

                // BATAS PETA INDONESIA
                const indonesiaBounds = L.latLngBounds(
                    L.latLng(-11.5, 94.0),
                    L.latLng(7.0, 142.0)
                );

                // Initialize map - same as admin
                const map = L.map('map', {
                    maxBounds: indonesiaBounds,
                    maxBoundsViscosity: 1.0,
                    minZoom: 5,
                    maxZoom: 18
                }).fitBounds(indonesiaBounds);

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Hide loading overlay after map is initialized
                setTimeout(() => {
                    document.getElementById('loading-overlay').style.display = 'none';
                }, 300);

                // Store markers
                let allMarkers = [];

                // Create markers
                function createMarker(markerData) {
                    const {
                        pusat_lintang,
                        pusat_bujur,
                        judul,
                        tahun,
                        tipe,
                        wilayah,
                        deskripsi,
                        gambar_pratinjau,
                        id_data_survei
                    } = markerData;

                    // FIXED: Convert string coordinates to numbers
                    const lat = parseFloat(pusat_lintang);
                    const lng = parseFloat(pusat_bujur);

                    // Validate coordinates (check after conversion to number)
                    if (isNaN(lat) || isNaN(lng) || lat === null || lng === null) {
                        console.error('Invalid coordinates for marker:', markerData);
                        console.error('Converted values:', {
                            lat,
                            lng
                        });
                        return null;
                    }

                    console.log('Creating marker for:', judul, 'at', lat, lng);

                    // Create marker with blue icon
                    const marker = L.marker([lat, lng], {
                        icon: blueIcon,
                        title: judul
                    });

                    // Create popup content with proper escaping
                    const safeJudul = judul ? judul.replace(/</g, '&lt;').replace(/>/g, '&gt;') :
                        'Judul tidak tersedia';
                    const safeDeskripsi = deskripsi ? deskripsi.substring(0, 150).replace(/</g, '&lt;').replace(/>/g,
                        '&gt;') : 'Deskripsi tidak tersedia';

                    const popupContent = `
<div class="popup-content">
<h4 class="popup-title">${safeJudul}</h4>

<div class="popup-meta">
<span class="popup-badge tipe-${tipe.toLowerCase()}">${tipe}</span>
<span class="popup-badge">${tahun}</span>
</div>

<p class="popup-description">${safeDeskripsi}</p>

<div class="popup-actions">
<span class="popup-coordinates">${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
<a href="/user/katalog/${id_data_survei}?from_peta=1" class="popup-detail-btn" target="_blank">
Lihat Detail
</a>
</div>
</div>
`;

                    // Bind popup with options
                    marker.bindPopup(popupContent, {
                        maxWidth: 350,
                        minWidth: 250,
                        autoPan: true,
                        closeButton: true,
                        autoClose: false,
                        closeOnClick: false
                    });

                    // Add click event to toggle popup (open/close)
                    marker.on('click', function(e) {
                        // Toggle popup: close if open, open if closed
                        if (this.isPopupOpen()) {
                            this.closePopup();
                        } else {
                            // Close all popups on map first
                            map.eachLayer(function(layer) {
                                if (layer instanceof L.Marker && layer.isPopupOpen()) {
                                    layer.closePopup();
                                }
                            });
                            this.openPopup();
                        }
                    });

                    // Add to map immediately
                    marker.addTo(map);

                    // Add to markers array with filter property
                    allMarkers.push({
                        marker: marker,
                        tipe: tipe,
                        id: id_data_survei
                    });

                    console.log('Marker added to map successfully:', judul);

                    return marker;
                }

                // Create all markers
                if (markersData.length > 0) {
                    console.log('Creating markers:', markersData.length);

                    markersData.forEach(markerData => {
                        try {
                            const createdMarker = createMarker(markerData);
                            if (createdMarker) {
                                console.log('Marker created and added successfully');
                            } else {
                                console.error('Failed to create marker');
                            }
                        } catch (error) {
                            console.error('Error creating marker:', error, markerData);
                        }
                    });

                    console.log('Total markers created:', allMarkers.length);

                    // TIDAK auto zoom ke marker - biarkan user yang zoom sendiri
                    // Peta akan tetap menampilkan seluruh Indonesia seperti saat init
                } else {
                    console.log('No marker data available');
                }



                // Error handling for map
                map.on('tileerror', function(e) {
                    console.warn('Map tile error:', e);
                });

                // Add resize listener for responsive map
                window.addEventListener('resize', function() {
                    setTimeout(() => {
                        map.invalidateSize();
                    }, 100);
                });

                // Additional debug: Check if markers are actually on the map
                setTimeout(() => {
                    console.log('All markers array length:', allMarkers.length);
                    console.log('Markers successfully loaded on map');
                }, 1000);
            });
        </script>
    @endpush
