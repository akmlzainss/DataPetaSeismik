@extends('layouts.app')

@section('title', 'Detail Survei - ' . $survey->judul)

@push('styles')
    <!-- OpenSeadragon CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.css">
    <link rel="stylesheet" href="{{ asset('css/public-katalog.css') }}">
    <style>
        .image-viewer-container {
            position: relative;
            width: 100%;
            height: 600px;
            background: #0a1628;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #image-viewer {
            width: 100%;
            height: 100%;
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
        }

        .osd-btn:hover {
            transform: scale(1.1);
            background: #ffed4e;
        }
    </style>
@endpush

@section('content')
    <div class="detail-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                @if (request()->query('from_peta'))
                    <li class="breadcrumb-item"><a href="{{ route('peta') }}">Peta</a></li>
                @else
                    <li class="breadcrumb-item"><a href="{{ route('katalog') }}">Katalog Survei</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($survey->judul, 50) }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="detail-header">
            <h1 class="detail-title">{{ $survey->judul }}</h1>
            <div class="detail-meta">
                <div class="meta-item">
                    <div class="meta-label">Tahun Survei</div>
                    <div class="meta-value">{{ $survey->tahun }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Tipe Survei</div>
                    <div class="meta-value">{{ $survey->tipe }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Wilayah</div>
                    <div class="meta-value">{{ $survey->wilayah }}</div>
                </div>
                @if ($survey->ketua_tim)
                    <div class="meta-item">
                        <div class="meta-label">Ketua Tim</div>
                        <div class="meta-value">{{ $survey->ketua_tim }}</div>
                    </div>
                @endif
                @if ($survey->pengunggah)
                    <div class="meta-item">
                        <div class="meta-label">Diunggah Oleh</div>
                        <div class="meta-value">{{ $survey->pengunggah->nama }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Image Section -->
        @if ($survey->gambar_pratinjau)
            <div class="image-section">
                <h2 class="image-title">Gambar Pratinjau</h2>
                <div class="image-viewer-container">
                    <div id="image-viewer"></div>
                    <div class="osd-toolbar">
                        <button class="osd-btn" id="zoomIn" title="Zoom In">+</button>
                        <button class="osd-btn" id="zoomOut" title="Zoom Out">−</button>
                        <button class="osd-btn" id="home" title="Reset View">⟳</button>
                        <button class="osd-btn" id="fullPage" title="Fullscreen">⛶</button>
                    </div>
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-spinner"></div>
                        <div class="loading-text">Memuat viewer...</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Description -->
        @if ($survey->deskripsi)
            <div class="detail-description">
                <h2 class="description-title">Deskripsi</h2>
                <div class="description-content">{!! $survey->deskripsi !!}</div>
            </div>
        @endif

        <!-- Files Section -->
        <div class="file-section">
            <h2 class="file-title">File Survei</h2>
            <div class="file-info">
                <div class="file-icon">
                    <i class="fas fa-file-archive"></i>
                </div>
                <div class="file-details">
                    <div class="file-name">{{ $survey->nama_file_original ?? 'File Survei' }}</div>
                    <div class="file-description">
                        Ukuran: {{ $survey->ukuran_file ? number_format($survey->ukuran_file / 1024, 2) . ' KB' : '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-section">
            <a href="{{ request()->query('from_peta') ? route('peta') : route('katalog') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if ($survey->file_path)
                <!-- NOTE: Route download logic needs to be verified. Using a generic asset link or route if available.
                                  Checking routes showed 'katalog.show' but no specific download route.
                                  Using direct storage link for now as per typical Laravel behavior if public. -->
                <a href="{{ asset('storage/' . $survey->file_path) }}" class="download-btn" download>
                    <i class="fas fa-download"></i> Download Data
                </a>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($survey->gambar_pratinjau)
                // Initialize OpenSeadragon
                const viewer = OpenSeadragon({
                    id: "image-viewer",
                    prefixUrl: "https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/images/",
                    tileSources: {
                        type: 'image',
                        url: '{{ asset('storage/' . $survey->gambar_pratinjau) }}'
                    },
                    showNavigationControl: false,
                    gestureSettingsMouse: {
                        scrollToZoom: true,
                        clickToZoom: true,
                        dblClickToZoom: true,
                        dragToPan: true,
                        flickEnabled: true,
                        pinchRotate: false
                    }
                });

                // Custom Toolbar Event Listeners
                document.getElementById('zoomIn')?.addEventListener('click', () => viewer.viewport.zoomBy(1.5));
                document.getElementById('zoomOut')?.addEventListener('click', () => viewer.viewport.zoomBy(1 /
                1.5));
                document.getElementById('home')?.addEventListener('click', () => viewer.viewport.goHome());
                document.getElementById('fullPage')?.addEventListener('click', () => viewer.setFullPage(!viewer
                    .isFullPage()));

                // Hide loading overlay when image is loaded
                viewer.addHandler('open', function() {
                    document.getElementById('loading-overlay').style.display = 'none';
                    viewer.viewport.fitVertically(true);
                });

                viewer.addHandler('open-failed', function() {
                    document.getElementById('loading-overlay').innerHTML =
                        '<div class="loading-text" style="color: #dc3545;">Gagal memuat gambar</div>';
                });
            @endif
        });
    </script>
@endpush
