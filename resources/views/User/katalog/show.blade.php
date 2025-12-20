@extends('layouts.app')

@section('title', 'Detail Survei - ' . $survey->judul)

@push('styles')
    <!-- OpenSeadragon CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.css">
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/public-katalog.css') }}">
@endpush

@section('content')
    <div class="container-custom" style="padding-top: 2rem;">
        <!-- Breadcrumb & Header -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                @if (request()->query('from_peta'))
                    <li class="breadcrumb-item"><a href="{{ route('peta') }}">Peta</a></li>
                @else
                    <li class="breadcrumb-item"><a href="{{ route('katalog') }}">Katalog</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($survey->judul, 40) }}</li>
            </ol>
        </nav>

        <div class="detail-header mb-5">
            <h1 class="detail-title" style="color: #003366; font-weight: 700; font-size: 2.5rem; margin-bottom: 0.5rem;">
                {{ $survey->judul }}</h1>
            <div class="survey-meta-info">
                <span class="meta-badge">{{ $survey->tipe }}</span>
                <span class="meta-badge">{{ $survey->tahun }}</span>
                <span class="meta-badge">{{ $survey->wilayah }}</span>
            </div>
        </div>

        <!-- Main Content (Article Style) -->
        <div class="article-layout" style="margin-top: 0;">
            <!-- Left Column: Main Content -->
            <div class="article-main">
                <!-- Image Viewer Section -->
                @if ($survey->gambar_pratinjau)
                    <div class="image-viewer-box">
                        <div class="image-viewer-container" style="position: relative;">
                            <div id="image-viewer"></div>

                            <!-- Custom Toolbar -->
                            <div class="osd-toolbar">
                                <button class="osd-btn" id="zoomIn" title="Zoom In">+</button>
                                <button class="osd-btn" id="zoomOut" title="Zoom Out">−</button>
                                <button class="osd-btn" id="home" title="Reset View">⟳</button>
                                <button class="osd-btn" id="fullPage" title="Fullscreen">⛶</button>
                            </div>

                            <div class="loading-overlay" id="loading-overlay">
                                <div class="loading-spinner"></div>
                                <div class="loading-text">Memuat gambar...</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Description Section -->
                @if ($survey->deskripsi)
                    <div class="article-content">
                        <h2 class="article-section-title">Keterangan</h2>
                        <div class="article-text">
                            {!! strip_tags($survey->deskripsi, '<p><br><strong><b><em><i><u><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><a><span><div>') !!}
                        </div>
                    </div>
                @else
                    <div class="article-content">
                        <p class="text-muted text-center">Tidak ada keterangan detail untuk survei ini.</p>
                    </div>
                @endif
            </div>

            <!-- Right Column: Sidebar -->
            <aside class="article-sidebar">
                {{-- Location Map Section --}}
                @if ($survey->lokasi)
                    <div class="location-map-card">
                        <h3 class="filters-title"
                            style="margin-bottom: 1.5rem; border-bottom: 2px solid #ffed00; padding-bottom: 0.5rem; display: inline-block;">
                            Lokasi Survei</h3>

                        <div class="location-info">
                            <div class="location-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>{{ $survey->lokasi->nama_lokasi ?? $survey->wilayah }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ number_format($survey->lokasi->pusat_lintang, 6) }}°,
                                        {{ number_format($survey->lokasi->pusat_bujur, 6) }}°
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div id="surveyLocationMap" class="survey-location-map"></div>

                        @if ($survey->lokasi->keterangan)
                            <div class="location-description">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    {{ $survey->lokasi->keterangan }}
                                </small>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="meta-card" style="margin-top: 24px;">
                    <h3 class="filters-title"
                        style="margin-bottom: 1.5rem; border-bottom: 2px solid #ffed00; padding-bottom: 0.5rem; display: inline-block;">
                        Informasi Detail</h3>

                    <ul class="meta-list">
                        <li class="meta-item">
                            <span class="meta-label">Judul Survei</span>
                            <span class="meta-value">{{ $survey->judul }}</span>
                        </li>
                        <li class="meta-item">
                            <span class="meta-label">Tahun Pelaksanaan</span>
                            <span class="meta-value">{{ $survey->tahun }}</span>
                        </li>
                        <li class="meta-item">
                            <span class="meta-label">Tipe Survei</span>
                            <span class="meta-value">{{ $survey->tipe }}</span>
                        </li>
                        <li class="meta-item">
                            <span class="meta-label">Wilayah / Blok</span>
                            <span class="meta-value">{{ $survey->wilayah }}</span>
                        </li>
                        @if ($survey->ketua_tim)
                            <li class="meta-item">
                                <span class="meta-label">Ketua Tim</span>
                                <span class="meta-value">{{ $survey->ketua_tim }}</span>
                            </li>
                        @endif
                        <li class="meta-item">
                            <span class="meta-label">Update Terakhir</span>
                            <span class="meta-value">{{ $survey->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>

                    @if ($survey->tautan_file)
                        <div class="download-section">
                            <a href="{{ $survey->tautan_file }}" target="_blank" class="btn-download-large">
                                <i class="fas fa-file-download"></i> Unduh / Buka File
                            </a>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- OpenSeadragon JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.js"></script>
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($survey->gambar_pratinjau)
                var viewer = OpenSeadragon({
                    id: "image-viewer",
                    prefixUrl: "https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/images/",
                    tileSources: {
                        type: 'image',
                        url: "{{ asset('storage/' . $survey->gambar_pratinjau) }}"
                    },
                    showNavigationControl: false,
                    // Animation settings
                    animationTime: 1.2,
                    blendTime: 0.1,
                    constrainDuringPan: true,
                    maxZoomPixelRatio: 2,
                    minZoomImageRatio: 0.8,
                    visibilityRatio: 1,
                    zoomPerScroll: 1.2
                });

                // Custom Toolbar Events
                document.getElementById('zoomIn')?.addEventListener('click', () => viewer.viewport.zoomBy(1.5));
                document.getElementById('zoomOut')?.addEventListener('click', () => viewer.viewport.zoomBy(1 /
                    1.5));
                document.getElementById('home')?.addEventListener('click', () => viewer.viewport.goHome());
                document.getElementById('fullPage')?.addEventListener('click', () => viewer.setFullPage(!viewer
                    .isFullPage()));

                // Handle loading state
                viewer.addHandler('open', function() {
                    document.getElementById('loading-overlay').style.display = 'none';
                });

                viewer.addHandler('open-failed', function() {
                    document.getElementById('loading-overlay').innerHTML =
                        '<div class="text-danger"><i class="fas fa-exclamation-circle"></i> Gagal memuat gambar</div>';
                });
            @endif

            // ============================================================
            // LOCATION MAP INITIALIZATION
            // ============================================================
            @if ($survey->lokasi)
                // Initialize location map
                const locationMap = L.map('surveyLocationMap', {
                    zoomControl: true,
                    scrollWheelZoom: true,
                    doubleClickZoom: true,
                    boxZoom: false,
                    keyboard: false,
                    dragging: true,
                    touchZoom: true
                }).setView([{{ $survey->lokasi->pusat_lintang }}, {{ $survey->lokasi->pusat_bujur }}], 6);

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 18,
                    minZoom: 5
                }).addTo(locationMap);

                // Create blue icon same as main map page
                const blueIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                // Add marker for survey location
                const surveyMarker = L.marker([{{ $survey->lokasi->pusat_lintang }},
                    {{ $survey->lokasi->pusat_bujur }}
                ], {
                    icon: blueIcon,
                    title: '{{ $survey->judul }}'
                }).addTo(locationMap);

                // Create popup content
                const popupContent = `
                    <div style="text-align: center; min-width: 200px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        <h4 style="margin: 0 0 8px 0; color: #003366; font-size: 16px;">
                            <i class="fas fa-map-marker-alt" style="color: #ffed00; margin-right: 6px;"></i>
                            {{ $survey->lokasi->nama_lokasi ?? $survey->wilayah }}
                        </h4>
                        <div style="margin: 8px 0; padding: 8px; background: #f8f9fa; border-radius: 6px;">
                            <strong style="color: #003366;">{{ $survey->judul }}</strong><br>
                            <small style="color: #666;">{{ $survey->tipe }} • {{ $survey->tahun }}</small>
                        </div>
                        <div style="margin: 8px 0; font-size: 13px; color: #666;">
                            <i class="fas fa-crosshairs" style="margin-right: 4px;"></i>
                            <strong>Koordinat:</strong><br>
                            {{ number_format($survey->lokasi->pusat_lintang, 6) }}°, {{ number_format($survey->lokasi->pusat_bujur, 6) }}°
                        </div>
                        @if ($survey->lokasi->keterangan)
                            <div style="margin-top: 8px; padding: 6px; background: #e8f5e8; border-radius: 4px; font-size: 12px; color: #666;">
                                <i class="fas fa-info-circle" style="color: #28a745; margin-right: 4px;"></i>
                                {{ $survey->lokasi->keterangan }}
                            </div>
                        @endif
                    </div>
                `;

                // Bind popup to marker
                surveyMarker.bindPopup(popupContent, {
                    maxWidth: 300,
                    className: 'survey-location-popup'
                });

                // Auto-open popup after a short delay
                setTimeout(() => {
                    surveyMarker.openPopup();
                }, 1000);

                // Fit map bounds to show marker with more area around it
                const bounds = L.latLngBounds([surveyMarker.getLatLng()]);
                locationMap.fitBounds(bounds, {
                    padding: [50, 50],
                    maxZoom: 7 // Limit maximum zoom level
                });
            @endif
        });
    </script>
@endpush
