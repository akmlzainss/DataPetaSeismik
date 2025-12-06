@extends('layouts.app')

@section('title', 'Detail Survei - ' . $survey->judul)

@push('styles')
    <!-- OpenSeadragon CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.css">
    <link rel="stylesheet" href="{{ asset('css/public-katalog.css') }}">
@endpush

{{-- REMOVED INLINE CSS - NOW USING public/css/public-katalog.css --}}
@push('styles-removed')
    <style>
        .detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .breadcrumb {
            margin-bottom: 2rem;
            padding: 0;
            list-style: none;
            background: none;
        }

        .breadcrumb-item {
            display: inline;
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: " / ";
            margin: 0 0.5rem;
            color: #adb5bd;
        }

        .breadcrumb-item:hover {
            color: #3779C9;
        }

        .breadcrumb-item.active {
            color: #2c3e50;
            font-weight: 500;
        }

        .detail-header {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .detail-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .detail-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .meta-item {
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #3779C9;
        }

        .meta-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .meta-value {
            font-size: 1rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .detail-description {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .description-title {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .description-content {
            color: #495057;
            line-height: 1.6;
            font-size: 1rem;
            white-space: pre-wrap;
        }

        .image-section {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .image-title {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .image-viewer {
            width: 100%;
            height: 600px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f8f9fa;
            position: relative;
            overflow: hidden;
        }

        .no-image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
            font-size: 1.1rem;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 6px;
        }

        .file-section {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .file-title {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .file-icon {
            font-size: 2rem;
            color: #3779C9;
        }

        .file-details {
            flex: 1;
        }

        .file-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .file-description {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .download-btn {
            padding: 0.75rem 1.5rem;
            background: #3779C9;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .download-btn:hover {
            background: #2c5aa0;
            color: white;
            text-decoration: none;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .back-btn:hover {
            background: #5a6268;
            color: white;
            text
        }

        .-decoration: none;

        actions-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(248, 249, 250, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
            z-index: 10;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e9ecef;
            border-top: 3px solid #3779C9;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: #6c757d;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .detail-container {
                padding: 1rem;
            }

            .detail-title {
                font-size: 1.5rem;
            }

            .detail-meta {
                grid-template-columns: 1fr;
            }

            .image-viewer {
                height: 400px;
            }

            .actions-section {
                flex-direction: column;
                gap: 1rem;
            }

            .back-btn,
            .download-btn {
                @section('content') <div class="detail-container">< !-- Breadcrumb --><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>@if (isset($showFromPeta) && $showFromPeta)<li class="breadcrumb-item"><a href="{{ route('peta') }}">Peta</a></li>@else <li class="breadcrumb-item"><a href="{{ route('katalog') }}">Katalog Survei</a></li>@endif<li class="breadcrumb-item active" aria-current="page">{{ Str::limit($survey->judul, 50) }} </li></ol></nav>< !-- Header --><div class="detail-header"><h1 class="detail-title">{{ $survey->judul }}</h1><div class="detail-meta"><div class="meta-item"><div class="meta-label">Tahun Survei</div><div class="meta-value">{{ $survey->tahun }}</div></div><div class="meta-item"><div class="meta-label">Tipe Survei</div><div class="meta-value">{{ $survey->tipe }}</div></div><div class="meta-item"><div class="meta-label">Wilayah</div><div class="meta-value">{{ $survey->wilayah }}</div></div>@if ($survey->ketua_tim)<div class="meta-item"><div class="meta-label">Ketua Tim</div><div class="meta-value">{{ $survey->ketua_tim }}</div></div>@endif@if ($survey->pengunggah)<div class="meta-item"><div class="meta-label">Diunggah Oleh</div><div class="meta-value">{{ $survey->pengunggah->nama }}</div></div>@endif</div></div>< !-- Image Section -->@if ($survey->gambar_pratinjau)<div class="image-section"><h2 class="image-title">Gambar Pratinjau</h2><div class="image-viewer" id="image-viewer"><div class="loading-overlay" id="loading-overlay"><div class="loading-spinner"></div><div class="loading-text">Memuat viewer...</div></div></div></div>@endif< !-- Description -->@if ($survey->deskripsi)<div class="detail-description"><h2 class="description-title">Deskripsi</h2><div class="description-content">{{ $survey->deskripsi }}</div></div>@endif< !-- Location Info -->@if ($survey->lokasi)<div class="detail-description"><h2 class="description-title">Informasi Lokasi</h2><div class="description-content">Koordinat: {{ $survey->lokasi->pusat_lintang }}, {{ $survey->lokasi->pusat_bujur }} @if ($survey->lokasi->alamat)<br>Alamat: {{ $survey->lokasi->alamat }} @endif</div></div>@endif< !-- File Section -->@if ($survey->tautan_file)<div class="file-section"><h2 class="file-title">File Data</h2><div class="file-info"><div class="file-icon">📁</div><div class="file-details"><div class="file-name">Data Survei {{ $survey->judul }}</div><div class="file-description">File data asli survei geologi kelautan</div></div><a href="{{ asset($survey->tautan_file) }}" download class="download-btn" target="_blank">📥 Unduh File </a></div></div>@endif< !-- Actions --><div class="actions-section">@if (isset($showFromPeta) && $showFromPeta)<a href="{{ route('peta') }}" class="back-btn">← Kembali ke Peta </a>@else <a href="{{ route('katalog') }}" class="back-btn">← Kembali ke Katalog </a>@endif@if ($survey->tautan_file)<a href="{{ asset($survey->tautan_file) }}" download class="download-btn">📥 Unduh Data </a>@endif</div></div>@push('scripts') <script src="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.js"></script><script>
                    document.addEventListener('DOMContentLoaded', function() {
                        @if ($survey->gambar_pratinjau)
                            // Initialize OpenSeadragon
                            const viewer = OpenSeadragon({
                                id: "image-viewer",
                                prefixUrl: "https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/images/",
                                tileSources: {
                                    type: 'image',
                                    url: '{{ asset($survey->gambar_pratinjau) }}'
                                },
                                showNavigator: true,
                                showZoomControl: true,
                                showHomeControl: true,
                                showFullPageControl: true,
                                showRotationControl: true,
                                showSequenceControl: false,
                                showDiscoveryControl: false,
                                visibilityRatio: 1.0,
                                minZoomImageRatio: 0.5,
                                maxZoomPixelRatio: 10,
                                gesture clickToZoomSettingsMouse: {
                                    : true,
                                    dblClickToZoom: true,
                                    dragToPan: true,
                                    flickEnabled: true,
                                    pinchRotate: false
                                }
                            });

                            // Hide loading overlay when image is loaded
                            viewer.addHandler('open', function() {
                                document.getElementById('loading-overlay').style.display = 'none';
                            });

                            viewer.addHandler('open-failed', function() {
                                document.getElementById('loading-overlay').innerHTML =
                                    '<div class="loading-text" style="color: #dc3545;">Gagal memuat gambar</div>';
                            });
                        @endif
                    });
                </script>@endpush @endsection
