{{-- resources/views/admin/data_survei/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail: ' . $dataSurvei->judul)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-marker.css') }}">
    {{-- Preload critical resources --}}
    <link rel="preload" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" as="style">
    <link rel="preload" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" as="script">
    {{-- OpenSeadragon CSS - using unpkg instead --}}
    <link rel="stylesheet" href="https://unpkg.com/openseadragon@4.1.0/build/openseadragon/openseadragon.min.css">
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    {{-- Fallback CSS jika CDN gagal --}}
    <style>
        /* Leaflet fallback styles */
        .leaflet-container {
            font: 12px/1.5 "Helvetica Neue", Arial, Helvetica, sans-serif;
            background: #ddd;
            outline: 0;
        }
        .leaflet-container a {
            color: #0078A8;
        }
        .leaflet-zoom-box {
            border: 2px dotted #38f;
            background: rgba(255,255,255,0.5);
        }
        .leaflet-popup-content-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 14px rgba(0,0,0,0.4);
        }
        .leaflet-popup-content {
            margin: 13px 19px;
            line-height: 1.4;
        }
        .leaflet-popup-tip {
            background: white;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .leaflet-control-zoom {
            box-shadow: 0 1px 5px rgba(0,0,0,0.4);
            border-radius: 5px;
        }
        .leaflet-control-zoom a {
            background-color: #fff;
            border-bottom: 1px solid #ccc;
            width: 26px;
            height: 26px;
            line-height: 26px;
            display: block;
            text-align: center;
            text-decoration: none;
            color: black;
        }
        .leaflet-control-zoom a:hover {
            background-color: #f4f4f4;
        }
        .leaflet-control-zoom-in {
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .leaflet-control-zoom-out {
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }
        .leaflet-marker-icon {
            margin-left: -12px;
            margin-top: -41px;
        }
        .leaflet-marker-shadow {
            margin-left: -12px;
            margin-top: -41px;
        }
    </style>
    <style>
        /* Gaya khusus untuk OpenSeadragon (TIDAK BERUBAH) */
        #openseadragon-viewer {
            width: 100%;
            height: 80vh;
            background: #0a1628;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin: 20px 0;
        }

        .osd-toolbar {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
            background: rgba(0, 0, 0, 0.6);
            padding: 8px;
            border-radius: 8px;
            display: flex;
            gap: 8px;
        }

        .osd-btn {
            background: #FFD700;
            color: #003366;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.2s;
            line-height: 1;
            /* Penting agar simbol di tengah */
        }

        .osd-btn:hover {
            transform: scale(1.1);
            background: #ffed4e;
        }

        .detail-title {
            font-size: 28px;
            margin-bottom: 8px;
            color: #003366;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .detail-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-table tr td:first-child {
            font-weight: bold;
            width: 200px;
            color: #555;
        }

        /*
                                |--------------------------------------------------------------------------
                                | BAGIAN 1: PENAMBAHAN CSS KHUSUS UNTUK MENAMPILKAN OUTPUT QUILL
                                |--------------------------------------------------------------------------
                                | Ini diperlukan agar class-class seperti ql-align-center, daftar, dan blockquote
                                | yang dihasilkan Quill dapat ditampilkan dengan benar.
                                */
        .quill-content {
            /* Agar style tidak bocor keluar */
            line-height: 1.6;
            font-size: 1em;
            color: #333;
        }

        /* Text Alignment */
        .quill-content .ql-align-center {
            text-align: center;
        }

        .quill-content .ql-align-right {
            text-align: right;
        }

        .quill-content .ql-align-justify {
            text-align: justify;
        }

        /* List Styling */
        .quill-content ul,
        .quill-content ol {
            padding-left: 2em;
            margin: 0.5em 0;
        }

        .quill-content li {
            margin: 0.2em 0;
            padding-left: 0.3em;
        }

        /* Blockquote */
        .quill-content blockquote {
            border-left: 4px solid #ccc;
            margin: 1em 0;
            padding: 5px 0 5px 15px;
            font-style: italic;
            color: #666;
        }

        /* Code Block */
        .quill-content pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            white-space: pre-wrap;
            overflow-x: auto;
            margin: 1em 0;
        }

        /* Header Styles (agar terlihat seperti header) */
        .quill-content h1,
        .quill-content h2,
        .quill-content h3,
        .quill-content h4,
        .quill-content h5,
        .quill-content h6 {
            font-weight: bold;
            margin: 1em 0 0.5em;
            line-height: 1.2;
        }

        .quill-content h1 {
            font-size: 2em;
        }

        .quill-content h2 {
            font-size: 1.5em;
        }

        .quill-content h3 {
            font-size: 1.17em;
        }

        .quill-content h4 {
            font-size: 1em;
        }

        .quill-content h5 {
            font-size: 0.83em;
        }

        .quill-content h6 {
            font-size: 0.67em;
        }

        /* Style untuk coretan (strikethrough) - sudah ada di kode lama, tidak diubah */
        .quill-content s,
        .quill-content strike,
        .quill-content [style*="line-through"],
        .quill-content span[style*="line-through"] {
            text-decoration: line-through !important;
            color: inherit;
        }

        .quill-content span[style] {
            display: inline !important;
        }

        /* ====================== ADMIN LOCATION MAP STYLES ====================== */
        .admin-location-map-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e0e0e0;
            margin-bottom: 24px;
        }

        .admin-location-info {
            margin-bottom: 16px;
        }

        .admin-location-detail {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #003366;
        }

        .admin-location-detail svg {
            color: #003366;
            margin-top: 2px;
        }

        .admin-location-detail strong {
            color: #003366;
            font-size: 15px;
        }

        .admin-survey-location-map {
            height: 280px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background: #f8f9fa;
            position: relative;
        }

        /* Ensure Leaflet controls are visible */
        .leaflet-control-zoom {
            border: none !important;
            border-radius: 4px !important;
        }
        
        .leaflet-control-zoom a {
            background-color: white !important;
            color: #333 !important;
            border: 1px solid #ccc !important;
        }
        
        .leaflet-control-zoom a:hover {
            background-color: #f4f4f4 !important;
        }

        .admin-location-description {
            margin-top: 12px;
            padding: 10px 14px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #28a745;
        }

        .admin-location-description svg {
            color: #28a745;
            margin-right: 4px;
        }

        /* Admin Location Popup Styling */
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .admin-location-popup .leaflet-popup-content {
            margin: 12px 16px;
            line-height: 1.4;
        }

        .admin-location-popup .leaflet-popup-content h4 {
            border-bottom: 1px solid #eee;
            padding-bottom: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Detail Data Survei</h1>
        <p>Informasi lengkap survei seismik</p>
    </div>

    {{-- Breadcrumb Navigation --}}
    <nav class="admin-breadcrumb" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    Beranda
                </a>
            </li>
            @if(request()->get('from') === 'grid_kotak')
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.grid_kotak.index') }}">Grid Peta</a>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.data_survei.index') }}">Data Survei</a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">
                {{ Str::limit($dataSurvei->judul, 40) }}
            </li>
        </ol>
    </nav>

    <div class="welcome-section">
        <!-- Header Section -->
        <div class="detail-header-section">
            <h2 class="detail-main-title">{{ $dataSurvei->judul }}</h2>
            <div class="detail-meta-badges">
                <span class="detail-badge">{{ $dataSurvei->tipe }}</span>
                <span class="detail-badge secondary">{{ $dataSurvei->tahun }}</span>
                <span class="detail-badge secondary">{{ $dataSurvei->wilayah }}</span>
            </div>
        </div>

        <!-- Main Content Layout -->
        <div class="admin-article-layout">
            <!-- Left Column: Main Content -->
            <div class="admin-article-main">
                @php
                    // Gunakan gambar medium, jika tidak ada, fallback ke gambar pratinjau (original)
                    $imageViewerUrl = null;
                    if ($dataSurvei->gambar_medium && Storage::disk('public')->exists($dataSurvei->gambar_medium)) {
                        $imageViewerUrl = asset('storage/' . $dataSurvei->gambar_medium);
                    } elseif (
                        $dataSurvei->gambar_pratinjau &&
                        Storage::disk('public')->exists($dataSurvei->gambar_pratinjau)
                    ) {
                        $imageViewerUrl = asset('storage/' . $dataSurvei->gambar_pratinjau);
                    }
                @endphp

                @if ($imageViewerUrl)
                    <div class="admin-image-viewer-section">
                        <h3 class="admin-section-title">Pratinjau Gambar</h3>
                        <div style="position: relative;">
                            <div id="openseadragon-viewer"></div>
                            <div class="osd-toolbar">
                                <button class="osd-btn" id="zoomIn" title="Zoom In">+</button>
                                <button class="osd-btn" id="zoomOut" title="Zoom Out">−</button>
                                <button class="osd-btn" id="home" title="Reset View">⟳</button>
                                <button class="osd-btn" id="fullPage" title="Fullscreen">⛶</button>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($safeDeskripsi)
                    <div class="admin-description-section">
                        <h3 class="admin-section-title">Deskripsi Survei</h3>
                        <div class="admin-content-box">
                            <div class="quill-content">
                                {!! $safeDeskripsi !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Sidebar -->
            <aside class="admin-article-sidebar">
            {{-- Location Map Section - SISTEM GRID BARU --}}
                @if ($dataSurvei->gridKotak->count() > 0)
                    @php
                        $grid = $dataSurvei->gridKotak->first();
                    @endphp
                    <div class="admin-location-map-card">
                        <h3 class="admin-card-title">Lokasi Grid</h3>

                        <div class="admin-location-info">
                            <div class="admin-location-detail">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
                                </svg>
                                <div>
                                    <strong>Grid {{ $grid->nomor_kotak }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ number_format($grid->bounds_sw_lat, 2) }}° - {{ number_format($grid->bounds_ne_lat, 2) }}° LS,
                                        {{ number_format($grid->bounds_sw_lng, 2) }}° - {{ number_format($grid->bounds_ne_lng, 2) }}° BT
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div id="adminSurveyLocationMap" class="admin-survey-location-map"
                             data-grid-sw-lat="{{ $grid->bounds_sw_lat }}"
                             data-grid-ne-lat="{{ $grid->bounds_ne_lat }}"
                             data-grid-sw-lng="{{ $grid->bounds_sw_lng }}"
                             data-grid-ne-lng="{{ $grid->bounds_ne_lng }}"
                             data-grid-nomor="{{ $grid->nomor_kotak }}">
                            <div style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                                Memuat peta...
                            </div>
                        </div>

                        @if($dataSurvei->gridKotak->count() > 1)
                            <div class="admin-location-description" style="margin-top: 12px;">
                                <small class="text-muted">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                    </svg>
                                    Data ini juga ditemukan di grid: 
                                    @foreach($dataSurvei->gridKotak->skip(1) as $otherGrid)
                                        <strong>{{ $otherGrid->nomor_kotak }}</strong>{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </small>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Lokasi belum ditentukan --}}
                    <div class="admin-location-map-card">
                        <h3 class="admin-card-title">Lokasi Grid</h3>
                        <div class="admin-location-info">
                            <div class="admin-location-detail" style="color: #94a3b8;">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" style="opacity: 0.6;">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 1.74.5 3.37 1.41 4.84.95 1.54 2.2 2.86 3.16 4.4.47.75.81 1.45 1.17 2.26.26.55.47 1.5 1.26 1.5s1-.95 1.26-1.5c.37-.81.7-1.51 1.17-2.26.96-1.53 2.21-2.86 3.16-4.4C18.5 12.37 19 10.74 19 9c0-3.87-3.13-7-7-7zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                    <path d="M14.59 8.59L12 11.17 9.41 8.59 8 10l4 4 4-4z"/>
                                </svg>
                                <div>
                                    <strong style="color: #64748b;">{{ $dataSurvei->wilayah }}</strong>
                                    <br>
                                    <small style="color: #94a3b8;">
                                        <em>Lokasi grid belum ditentukan</em>
                                    </small>
                                    <br>
                                    <a href="{{ route('admin.grid_kotak.index') }}" 
                                       style="color: #3b82f6; font-size: 12px; text-decoration: none;">
                                        → Assign ke Grid
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div style="padding: 40px 20px; text-align: center; background: #f8fafc; border-radius: 8px; margin-top: 12px;">
                            <svg viewBox="0 0 24 24" width="48" height="48" fill="#cbd5e1" style="margin-bottom: 12px;">
                                <path d="M20.5 3l-.16.03L15 5.1 9 3 3.36 4.9c-.21.07-.36.25-.36.48V20.5c0 .28.22.5.5.5l.16-.03L9 18.9l6 2.1 5.64-1.9c.21-.07.36-.25.36-.48V3.5c0-.28-.22-.5-.5-.5zM15 19l-6-2.11V5l6 2.11V19z"/>
                            </svg>
                            <p style="color: #94a3b8; font-size: 13px; margin: 0;">
                                Peta tidak tersedia.<br>
                                Data belum di-assign ke grid peta seismik.
                            </p>
                        </div>
                    </div>
                @endif

                <div class="admin-meta-card" style="margin-top: 24px;">
                    <h3 class="admin-card-title">Informasi Detail</h3>

                    <div class="admin-meta-list">
                        <div class="admin-meta-item">
                            <span class="admin-meta-label">Ketua Tim</span>
                            <span class="admin-meta-value">{{ $dataSurvei->ketua_tim }}</span>
                        </div>
                        <div class="admin-meta-item">
                            <span class="admin-meta-label">Tahun Pelaksanaan</span>
                            <span class="admin-meta-value">{{ $dataSurvei->tahun }}</span>
                        </div>
                        <div class="admin-meta-item">
                            <span class="admin-meta-label">Tipe Survei</span>
                            <span class="admin-meta-value">{{ $dataSurvei->tipe }}</span>
                        </div>
                        <div class="admin-meta-item">
                            <span class="admin-meta-label">Wilayah / Blok</span>
                            <span class="admin-meta-value">{{ $dataSurvei->wilayah }}</span>
                        </div>
                        <div class="admin-meta-item">
                            <span class="admin-meta-label">Diunggah oleh</span>
                            <span class="admin-meta-value">{{ $dataSurvei->pengunggah->nama ?? 'Admin' }}</span>
                        </div>
                        <div class="admin-meta-item">
                            <span class="admin-meta-label">Tanggal Upload</span>
                            <span class="admin-meta-value">{{ $dataSurvei->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    @if ($dataSurvei->tautan_file)
                        <div class="admin-file-section">
                            <h4 class="admin-file-title">File Survei</h4>
                            <a href="{{ $dataSurvei->tautan_file }}" target="_blank" class="admin-file-link">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path
                                        d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z" />
                                </svg>
                                Buka File
                            </a>
                        </div>
                    @endif

                    <div class="admin-actions-section">
                        <h4 class="admin-actions-title">Aksi</h4>
                        <div class="admin-action-buttons">
                            <a href="{{ route('admin.data_survei.edit', $dataSurvei) }}" class="admin-btn admin-btn-edit">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path
                                        d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                </svg>
                                Edit Data
                            </a>
                            <button type="button" class="admin-btn admin-btn-delete" id="triggerDeleteModal"
                                data-judul="{{ $dataSurvei->judul }}"
                                data-action="{{ route('admin.data_survei.destroy', $dataSurvei) }}">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path
                                        d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                </svg>
                                Hapus Data
                            </button>
                            <a href="{{ route('admin.data_survei.index') }}" class="admin-btn admin-btn-back">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        {{-- Form Hapus yang disembunyikan, akan disubmit oleh JavaScript --}}
        <form id="deleteForm" action="{{ route('admin.data_survei.destroy', $dataSurvei) }}" method="POST"
            style="display:none;">
            @csrf @method('DELETE')
        </form>
    </div>

    {{-- MODAL DAN OVERLAY DITAMBAHKAN DI SINI (TIDAK BERUBAH) --}}

    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div id="loadingText" class="loading-text">Memproses...</div>
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
            </div>
            <h3 class="modal-title">Konfirmasi Hapus</h3>
            <p class="modal-message">
                Apakah Anda yakin ingin menghapus data survei <strong id="deleteItemTitle"></strong>?<br>
                Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>.
            </p>
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="cancelDelete()">Batal</button>
                <button type="button" class="modal-btn modal-btn-delete" id="confirmDeleteBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/openseadragon@4.1.0/build/openseadragon/openseadragon.min.js"></script>
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Wait for all resources to load
        window.addEventListener('load', function() {
            console.log('Page fully loaded, initializing components...');
        });

        // FUNGSI UNTUK OPENSEADRAGON (TIDAK BERUBAH)
        document.addEventListener("DOMContentLoaded", function() {
            const viewerElement = document.getElementById("openseadragon-viewer");
            const imageUrl = @json($imageViewerUrl);

            if (viewerElement && imageUrl) {
                console.log("OpenSeadragon loading image:", imageUrl);

                const viewer = OpenSeadragon({
                    id: "openseadragon-viewer",
                    prefixUrl: "https://cdn.jsdelivr.net/npm/openseadragon@4.1.0/build/openseadragon/images/",
                    tileSources: {
                        type: 'image',
                        url: imageUrl,
                    },
                    gestureSettingsMouse: {
                        scrollToZoom: true,
                        clickToZoom: true,
                        dblClickToZoom: true
                    },
                    showNavigationControl: false
                });

                // Event listener untuk tombol toolbar kustom
                document.getElementById('zoomIn')?.addEventListener('click', () => viewer.viewport.zoomBy(1.5));
                document.getElementById('zoomOut')?.addEventListener('click', () => viewer.viewport.zoomBy(1 /
                    1.5));
                document.getElementById('home')?.addEventListener('click', () => viewer.viewport.goHome());
                document.getElementById('fullPage')?.addEventListener('click', () => viewer.setFullPage(!viewer
                    .isFullPage()));

                viewer.addHandler('open', () => {
                    console.log("Gambar berhasil dimuat!");
                    viewer.viewport.fitVertically(true);
                });

                viewer.addHandler('open-failed', (e) => {
                    console.error("Gagal load OpenSeadragon:", e);
                    viewerElement.innerHTML =
                        "<p style='color:#c62828;padding:50px;text-align:center;'>Gagal memuat gambar. Pastikan file gambar ada dan dapat diakses.</p>";
                });
            }
        });

        // FUNGSI UNTUK MODAL KONFIRMASI HAPUS (TIDAK BERUBAH)
        let deleteForm = document.getElementById('deleteForm');

        // 1. Menangani klik tombol Hapus Data untuk menampilkan modal
        document.getElementById('triggerDeleteModal')?.addEventListener('click', function(e) {
            e.preventDefault();

            // Ambil judul dari data-attribute tombol
            const title = this.dataset.judul;

            // Update teks di modal
            document.getElementById('deleteItemTitle').textContent = `"${title}"`;

            // Update action form tersembunyi (jika diperlukan, walau di kasus ini sudah terisi)
            const actionUrl = this.dataset.action;
            if (actionUrl) {
                deleteForm.action = actionUrl;
            }

            // Tampilkan modal
            document.getElementById('deleteModal').classList.add('active');
        });

        // 2. Fungsi untuk membatalkan penghapusan
        function cancelDelete() {
            document.getElementById('deleteModal').classList.remove('active');
            // deleteForm tidak di-null-kan karena formnya tetap di DOM
        }

        // 3. Menangani klik tombol Ya, Hapus untuk submit form
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
            if (deleteForm) {
                // Tampilkan loading overlay sebelum submit
                document.getElementById('loadingOverlay').classList.add('active');
                document.getElementById('loadingText').textContent = 'Menghapus data...';

                deleteForm.submit();
            }
        });

        // ============================================================
        // ADMIN LOCATION MAP INITIALIZATION - SISTEM GRID BARU
        // ============================================================
        @if ($dataSurvei->gridKotak->count() > 0)
            @php
                $grid = $dataSurvei->gridKotak->first();
            @endphp
            
            function initAdminLocationMap() {
                console.log('Initializing admin grid location map...');
                
                const mapElement = document.getElementById('adminSurveyLocationMap');
                if (!mapElement) {
                    console.error('Map element not found!');
                    return;
                }
                
                // Get grid bounds from data attributes
                const swLat = parseFloat(mapElement.dataset.gridSwLat);
                const neLat = parseFloat(mapElement.dataset.gridNeLat);
                const swLng = parseFloat(mapElement.dataset.gridSwLng);
                const neLng = parseFloat(mapElement.dataset.gridNeLng);
                const gridNomor = mapElement.dataset.gridNomor;
                
                // Check if Leaflet is loaded
                if (typeof L === 'undefined') {
                    console.error('Leaflet library not loaded!');
                    mapElement.innerHTML = `
                        <div style="padding: 20px; text-align: center; background: #f8f9fa; border-radius: 8px;">
                            <p style="color: #666;">Library peta tidak tersedia.</p>
                            <p style="color: #333; font-weight: bold;">Grid ${gridNomor}</p>
                        </div>
                    `;
                    return;
                }
                
                try {
                    // Calculate center of grid
                    const centerLat = (swLat + neLat) / 2;
                    const centerLng = (swLng + neLng) / 2;
                    
                    // Initialize map
                    const adminLocationMap = L.map('adminSurveyLocationMap', {
                        zoomControl: true,
                        scrollWheelZoom: true,
                        doubleClickZoom: true,
                        dragging: true,
                        touchZoom: true
                    }).setView([centerLat, centerLng], 7);

                    // Add tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                        maxZoom: 18,
                        minZoom: 4
                    }).addTo(adminLocationMap);

                    // Create grid rectangle - STYLE SAMA SEPERTI HALAMAN GRID PETA
                    // Hijau muda transparan untuk grid terisi
                    const bounds = [[swLat, swLng], [neLat, neLng]];
                    const rectangle = L.rectangle(bounds, {
                        color: '#999',           // Border abu-abu
                        fillColor: '#8fbc8f',    // Hijau muda (light green) untuk terisi
                        fillOpacity: 0.4,
                        weight: 1                // Border tipis
                    }).addTo(adminLocationMap);

                    // Add label in center - STYLE SAMA SEPERTI HALAMAN GRID PETA
                    // Plain text tanpa background, dengan text-shadow untuk readability
                    const labelIcon = L.divIcon({
                        className: 'grid-label',
                        html: `<span style="
                            font-size: 11px;
                            font-weight: 600;
                            color: #333;
                            text-shadow: 
                                -1px -1px 0 #fff,
                                1px -1px 0 #fff,
                                -1px 1px 0 #fff,
                                1px 1px 0 #fff,
                                0 0 3px #fff;
                            pointer-events: none;
                        ">${gridNomor}</span>`,
                        iconSize: [40, 20],
                        iconAnchor: [20, 10]
                    });
                    
                    L.marker([centerLat, centerLng], {
                        icon: labelIcon,
                        interactive: false
                    }).addTo(adminLocationMap);

                    // Fit map to grid bounds with padding - AUTO ZOOM KE GRID INI
                    adminLocationMap.fitBounds(bounds, { 
                        padding: [50, 50],
                        maxZoom: 8  // Batasi zoom agar tidak terlalu dekat
                    });

                    setTimeout(() => {
                        adminLocationMap.invalidateSize();
                    }, 200);
                    
                    console.log('Admin grid map initialized successfully!');
                } catch (error) {
                    console.error('Error initializing admin location map:', error);
                    mapElement.innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Terjadi kesalahan saat memuat peta.</div>';
                }
            }

            // Try to initialize map with multiple attempts
            let attempts = 0;
            const maxAttempts = 5;
            
            function tryInitMap() {
                attempts++;
                if (typeof L !== 'undefined') {
                    initAdminLocationMap();
                } else if (attempts < maxAttempts) {
                    console.log(`Waiting for Leaflet... attempt ${attempts}/${maxAttempts}`);
                    setTimeout(tryInitMap, 500);
                } else {
                    console.error('Failed to load Leaflet after multiple attempts');
                    const mapElement = document.getElementById('adminSurveyLocationMap');
                    if (mapElement) {
                        mapElement.innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Gagal memuat peta.</div>';
                    }
                }
            }
            
            // Start trying to initialize map
            setTimeout(tryInitMap, 500);
        @else
            console.log('No grid location assigned to this survey');
        @endif
    </script>
@endpush
