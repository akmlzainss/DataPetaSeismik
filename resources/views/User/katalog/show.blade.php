@extends('layouts.app')

@section('title', 'Detail Survei - ' . $survey->judul)

@push('styles')
    <!-- OpenSeadragon CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.css">
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
            <p class="text-muted" style="font-size: 1.1rem;">
                {{ $survey->tipe }} &bull; {{ $survey->tahun }} &bull; {{ $survey->wilayah }}
            </p>
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
                            {!! strip_tags($survey->deskripsi, '<p><br><strong><em><ul><ol><li><h1><h2><h3>') !!}
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
                <div class="meta-card">
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
        });
    </script>
@endpush
