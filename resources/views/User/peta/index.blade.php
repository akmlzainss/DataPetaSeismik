@extends('layouts.app')

@section('title', 'Peta Sebaran Survei - Data Peta Seismik')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/public-peta.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    <div class="peta-hero">
        <div class="container-custom">
            <h1 class="hero-title">Peta Sebaran Survei Geologi</h1>
            <p class="hero-subtitle">Visualisasi interaktif lokasi dan data hasil survei geologi kelautan di seluruh wilayah
                Indonesia.</p>
        </div>
    </div>

    <div class="peta-content-wrapper">
        <div class="container-custom">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-blue">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['total_marker'] }}</div>
                        <div class="stat-label">Titik Lokasi</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-green">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['total_survei'] }}</div>
                        <div class="stat-label">Data Survei</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-orange">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['tahun_terbaru'] }}</div>
                        <div class="stat-label">Data Tahun Terakhir</div>
                    </div>
                </div>
            </div>

            <!-- Map & Info Layout -->
            <div class="map-layout">
                <!-- Main Map -->
                <div class="map-container-box">
                    <div class="map-header">
                        <h2 class="section-title"><i class="fas fa-globe-asia"></i> Eksplorasi Peta</h2>
                        <div class="map-legend">
                            <span class="legend-item"><span class="dot dot-blue"></span> Area Survei</span>
                        </div>
                    </div>
                    <div class="map-frame">
                        <div id="map">
                            <div class="loading-overlay" id="loading-overlay">
                                <div class="loading-spinner"></div>
                                <div class="loading-text">Memuat data geospasial...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="map-sidebar">
                    <div class="info-card">
                        <h3 class="info-card-title">Tentang Peta Ini</h3>
                        <p class="info-text">
                            Peta ini menyajikan distribusi spasial dari kegiatan survei seismik dan geologi kelautan yang
                            telah dilaksanakan oleh BBSPGL.
                        </p>
                        <p class="info-text">
                            Setiap penanda (marker) pada peta merepresentasikan lokasi spesifik di mana pengambilan data
                            survei dilakukan.
                        </p>
                    </div>

                    <div class="info-card">
                        <h3 class="info-card-title">Panduan Interaksi</h3>
                        <ul class="guide-list">
                            <li>
                                <div class="guide-icon"><i class="fas fa-mouse-pointer"></i></div>
                                <span><strong>Klik Marker</strong> untuk melihat ringkasan informasi survei.</span>
                            </li>
                            <li>
                                <div class="guide-icon"><i class="fas fa-external-link-alt"></i></div>
                                <span><strong>Lihat Detail</strong> pada popup untuk mengakses data lengkap.</span>
                            </li>
                            <li>
                                <div class="guide-icon"><i class="fas fa-search-plus"></i></div>
                                <span><strong>Zoom In/Out</strong> untuk melihat detail lokasi yang lebih spesifik.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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

            // BATAS PETA INDONESIA (DIPERLUAS)
            // Diperluas agar wilayah laut (terutama Aceh/Barat dan Utara) tidak terpotong
            const indonesiaBounds = L.latLngBounds(
                L.latLng(-15.0, 90.0), // South-West (Lebih ke barat dan selatan)
                L.latLng(10.0, 145.0) // North-East (Lebih ke utara dan timur)
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

                // Create popup content with Quill editor support
                const safeJudul = judul ? judul.replace(/</g, '&lt;').replace(/>/g, '&gt;') :
                    'Judul tidak tersedia';

                // Process deskripsi to support Quill HTML content
                let processedDeskripsi = 'Deskripsi tidak tersedia';
                if (deskripsi) {
                    // Create a temporary div to parse HTML and extract text preview
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = deskripsi;

                    // Get text content and limit to 150 characters
                    const textContent = tempDiv.textContent || tempDiv.innerText || '';
                    processedDeskripsi = textContent.length > 150 ?
                        textContent.substring(0, 150) + '...' : textContent;
                }

                const popupContent = `
<div class="popup-content">
<h4 class="popup-title">${safeJudul}</h4>

<div class="popup-meta">
<span class="popup-badge tipe-${tipe.toLowerCase()}">${tipe}</span>
<span class="popup-badge">${tahun}</span>
</div>

<p class="popup-description">${processedDeskripsi}</p>

<div class="popup-actions">
<span class="popup-coordinates">${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
<a href="/katalog/${id_data_survei}?from_peta=1" class="popup-detail-btn">
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

                // VITAL: Remove the default click listener added by bindPopup
                // This prevents the conflict where Leaflet opens it and our custom handler closes it immediately
                marker.off('click');

                // Add custom click event to handle toggle logic properly
                marker.on('click', function(e) {
                    if (this.isPopupOpen()) {
                        // If open, close it
                        this.closePopup();
                    } else {
                        // If closed, close all other popups first
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.Marker && layer !== marker) {
                                layer.closePopup();
                            }
                        });
                        // Then open this one
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
