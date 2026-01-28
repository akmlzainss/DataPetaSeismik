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
                {{-- Location Grid Section (SISTEM BARU) --}}
                @if ($survey->gridKotak->count() > 0)
                    @php
                        $grid = $survey->gridKotak->first();
                    @endphp
                    <div class="location-map-card">
                        <h3 class="filters-title"
                            style="margin-bottom: 1.5rem; border-bottom: 2px solid #ffed00; padding-bottom: 0.5rem; display: inline-block;">
                            Lokasi Grid</h3>

                        <div class="location-info">
                            <div class="location-detail">
                                <i class="fas fa-th"></i>
                                <div>
                                    <strong>Grid {{ $grid->nomor_kotak }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $survey->wilayah }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div id="surveyLocationMap" class="survey-location-map"
                             data-grid-sw-lat="{{ $grid->bounds_sw_lat }}"
                             data-grid-ne-lat="{{ $grid->bounds_ne_lat }}"
                             data-grid-sw-lng="{{ $grid->bounds_sw_lng }}"
                             data-grid-ne-lng="{{ $grid->bounds_ne_lng }}"
                             data-grid-nomor="{{ $grid->nomor_kotak }}"></div>

                        @if($survey->gridKotak->count() > 1)
                            <div class="location-description">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Juga ditemukan di grid: 
                                    @foreach($survey->gridKotak->skip(1) as $otherGrid)
                                        <strong>{{ $otherGrid->nomor_kotak }}</strong>{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
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

                    {{-- Download File Scan Asli (Hanya untuk Pegawai ESDM) --}}
                    @if ($survey->file_scan_asli)
                        <div class="download-section" style="margin-top: 15px;">
                            @auth('pegawai')
                                {{-- Pegawai ESDM: Bisa download --}}
                                <a href="{{ route('scan.download', $survey->id) }}" 
                                   class="btn-download-large no-loading" 
                                   onclick="handleFileDownload(event)"
                                   style="background: #ffed00;
                                          border: none; box-shadow: 0 4px 15px rgba(255, 237, 0, 0.4); color: #003366; font-weight: 700;">
                                    <i class="fas fa-download"></i> 
                                    Download File Scan Asli
                                    @if($survey->ukuran_file_asli)
                                        <small style="display: block; font-size: 12px; opacity: 0.85; margin-top: 5px;">
                                            ({{ number_format($survey->ukuran_file_asli / 1048576, 2) }} MB - {{ strtoupper($survey->format_file_asli) }})
                                        </small>
                                    @endif
                                </a>
                            @else
                                {{-- User External: Tidak bisa download --}}
                                <button class="btn-download-large" disabled
                                        style="background: #6c757d; cursor: not-allowed; opacity: 0.6;">
                                    <i class="fas fa-lock"></i> 
                                    Download File Scan Asli
                                    @if($survey->ukuran_file_asli)
                                        <small style="display: block; font-size: 12px; opacity: 0.9; margin-top: 5px;">
                                            ({{ number_format($survey->ukuran_file_asli / 1048576, 2) }} MB - {{ strtoupper($survey->format_file_asli) }})
                                        </small>
                                    @endif
                                </button>
                                <p style="text-align: center; margin-top: 10px; font-size: 13px; color: #666;">
                                    <i class="fas fa-info-circle"></i> 
                                    <a href="{{ route('pegawai.login') }}" style="color: #003366; text-decoration: none;">
                                        Login sebagai Pegawai ESDM
                                    </a> untuk mengunduh file asli
                                </p>
                            @endauth
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
            // LOCATION MAP INITIALIZATION - GRID SYSTEM
            // ============================================================
            @if ($survey->gridKotak->count() > 0)
                // Initialize grid location map
                const mapElement = document.getElementById('surveyLocationMap');
                if (mapElement) {
                    const swLat = parseFloat(mapElement.dataset.gridSwLat);
                    const neLat = parseFloat(mapElement.dataset.gridNeLat);
                    const swLng = parseFloat(mapElement.dataset.gridSwLng);
                    const neLng = parseFloat(mapElement.dataset.gridNeLng);
                    const gridNomor = mapElement.dataset.gridNomor;
                    
                    // Calculate center
                    const centerLat = (swLat + neLat) / 2;
                    const centerLng = (swLng + neLng) / 2;
                    
                    const locationMap = L.map('surveyLocationMap', {
                        zoomControl: true,
                        scrollWheelZoom: true,
                        doubleClickZoom: true,
                        dragging: true,
                        touchZoom: true
                    }).setView([centerLat, centerLng], 7);

                    // Add tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                        minZoom: 4
                    }).addTo(locationMap);

                    // Create grid rectangle
                    const bounds = [[swLat, swLng], [neLat, neLng]];
                    const rectangle = L.rectangle(bounds, {
                        color: '#999',
                        fillColor: '#8fbc8f',
                        fillOpacity: 0.4,
                        weight: 1
                    }).addTo(locationMap);

                    // Add grid label
                    const labelIcon = L.divIcon({
                        className: 'grid-label',
                        html: `<span style="font-size: 11px; font-weight: 600; color: #333; text-shadow: 1px 1px 0 #fff, -1px -1px 0 #fff;">${gridNomor}</span>`,
                        iconSize: [40, 20],
                        iconAnchor: [20, 10]
                    });
                    L.marker([centerLat, centerLng], { icon: labelIcon, interactive: false }).addTo(locationMap);

                    // Create popup content
                    const popupContent = `
                        <div style="text-align: center; min-width: 180px;">
                            <h4 style="margin: 0 0 8px 0; color: #003366; font-size: 15px;">
                                <i class="fas fa-th" style="color: #ffed00; margin-right: 6px;"></i>
                                Grid ${gridNomor}
                            </h4>
                            <div style="margin: 8px 0; padding: 8px; background: #f8f9fa; border-radius: 6px;">
                                <strong style="color: #003366;">{{ $survey->judul }}</strong><br>
                                <small style="color: #666;">{{ $survey->tipe }} • {{ $survey->tahun }}</small>
                            </div>
                            <small style="color: #666;">{{ $survey->wilayah }}</small>
                        </div>
                    `;

                    rectangle.bindPopup(popupContent, {
                        maxWidth: 300,
                        className: 'survey-location-popup'
                    });

                    // Fit map to grid bounds
                    locationMap.fitBounds(bounds, {
                        padding: [20, 20],
                        maxZoom: 8
                    });
                }
            @endif
        });

        // Handle file download - show loading then hide after download starts
        function handleFileDownload(event) {
            // Show loading for download
            if (typeof showLoading === 'function') {
                showLoading('Memuat file...');
            }
            
            // Hide loading after a short delay (download has started)
            setTimeout(function() {
                if (typeof hideLoading === 'function') {
                    hideLoading();
                }
            }, 1500); // 1.5 detik cukup untuk download mulai
        }
    </script>
@endpush

