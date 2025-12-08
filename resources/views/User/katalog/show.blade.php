@extends('layouts.app')

@section('title', 'Detail Survei - ' . $survey->judul)

@push('styles')
    <!-- OpenSeadragon CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.css">
    <link rel="stylesheet" href="{{ asset('css/public-katalog.css') }}">
@endpush

@section('content')
    <div class="detail-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                @if (request()->query('from_peta'))
                    <li class="breadcrumb-item"><a href="{{ route('peta') }}">Peta</a></li>
                @else
                    <li class="breadcrumb-item"><a href="{{ route('katalog') }}">Katalog</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">Detail Survei</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="detail-header">
            <h1 class="detail-title">{{ $survey->judul }}</h1>
        </div>

        <!-- Detail Information Table -->
        <div class="detail-info-section">
            <h2 class="section-title">Informasi Detail</h2>
            <table class="detail-table">
                <tr>
                    <td class="table-label">Nama</td>
                    <td class="table-value">{{ $survey->judul }}</td>
                </tr>
                <tr>
                    <td class="table-label">Tahun</td>
                    <td class="table-value">{{ $survey->tahun }}</td>
                </tr>
                <tr>
                    <td class="table-label">Tipe Survei</td>
                    <td class="table-value">{{ $survey->tipe }}</td>
                </tr>
                <tr>
                    <td class="table-label">Wilayah / Blok</td>
                    <td class="table-value">{{ $survey->wilayah }}</td>
                </tr>
                @if ($survey->ketua_tim)
                    <tr>
                        <td class="table-label">Ketua Tim</td>
                        <td class="table-value">{{ $survey->ketua_tim }}</td>
                    </tr>
                @endif
                @if ($survey->tautan_file)
                    <tr>
                        <td class="table-label">Tautan</td>
                        <td class="table-value">
                            <a href="{{ $survey->tautan_file }}" target="_blank" class="file-link-simple">
                                <i class="fas fa-external-link-alt"></i> Buka File
                            </a>
                        </td>
                    </tr>
                @endif
                @if ($survey->pengunggah)
                    <tr>
                        <td class="table-label">Diunggah Oleh</td>
                        <td class="table-value">{{ $survey->pengunggah->nama }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="table-label">Update Terakhir</td>
                    <td class="table-value">{{ $survey->updated_at->format('d M Y - H:i') }} WIB</td>
                </tr>
            </table>
        </div>

        <!-- Description -->
        @if ($survey->deskripsi)
            <div class="detail-description">
                <h2 class="section-title">Keterangan</h2>
                <div class="description-content">{!! $survey->deskripsi !!}</div>
            </div>
        @endif

        <!-- Image Section -->
        @if ($survey->gambar_pratinjau)
            <div class="image-section">
                <h2 class="section-title">Gambar Pratinjau</h2>
                <div class="image-viewer-container">
                    <div id="image-viewer"></div>
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-spinner"></div>
                        <div class="loading-text">Memuat gambar...</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="actions-section">
            <a href="{{ request()->query('from_peta') ? route('peta') : route('katalog') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($survey->gambar_pratinjau)
                const viewer = OpenSeadragon({
                    id: "image-viewer",
                    prefixUrl: "https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/images/",
                    tileSources: {
                        type: 'image',
                        url: '{{ asset('storage/' . $survey->gambar_pratinjau) }}'
                    },
                    showNavigationControl: true,
                    navigationControlAnchor: OpenSeadragon.ControlAnchor.TOP_RIGHT,
                    gestureSettingsMouse: {
                        scrollToZoom: true,
                        clickToZoom: false,
                        dblClickToZoom: true,
                        dragToPan: true
                    }
                });

                viewer.addHandler('open', function() {
                    document.getElementById('loading-overlay').style.display = 'none';
                });

                viewer.addHandler('open-failed', function() {
                    document.getElementById('loading-overlay').innerHTML =
                        '<div class="loading-text" style="color: #dc3545;">Gagal memuat gambar</div>';
                });
            @endif
        });
    </script>
@endpush
