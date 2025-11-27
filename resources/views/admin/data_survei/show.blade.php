{{-- resources/views/admin/data_survei/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail: ' . $dataSurvei->judul)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/openseadragon@4.1.0/build/openseadragon/openseadragon.min.css">
    <style>
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
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Detail Data Survei</h1>
        <p>Informasi lengkap survei seismik</p>
    </div>

    <div class="welcome-section">
        <div class="detail-container">
            <!-- Header -->
            <div class="detail-header">
                <h2 class="detail-title">{{ $dataSurvei->judul }}</h2>
                <span class="detail-badge">{{ $dataSurvei->tipe }}</span>
            </div>

            <!-- OPEN-SEADRAGON VIEWER (INI YANG BIKIN KEREN!) -->
            @if ($dataSurvei->gambar_pratinjau)
                <div style="position: relative;">
                    <div id="openseadragon-viewer"></div>
                    <div class="osd-toolbar">
                        <button class="osd-btn" id="zoomIn" title="Zoom In">+</button>
                        <button class="osd-btn" id="zoomOut" title="Zoom Out">−</button>
                        <button class="osd-btn" id="home" title="Reset View">⟳</button>
                        <button class="osd-btn" id="fullPage" title="Fullscreen">⛶</button>
                    </div>
                </div>
            @else
                <div class="empty-state" style="padding: 60px 0;">
                    <p>Tidak ada gambar pratinjau.</p>
                </div>
            @endif

            <!-- Detail Table (tetap ada) -->
            <table class="detail-table">
                <tr>
                    <td>Ketua Tim</td>
                    <td>{{ $dataSurvei->ketua_tim }}</td>
                </tr>
                <tr>
                    <td>Tahun Pelaksanaan</td>
                    <td>{{ $dataSurvei->tahun }}</td>
                </tr>
                <tr>
                    <td>Wilayah / Blok</td>
                    <td>{{ $dataSurvei->wilayah }}</td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td>{{ $dataSurvei->deskripsi ?? '-' }}</td>
                </tr>
            </table>

            <!-- Metadata & Actions tetap sama -->
            <div class="detail-metadata">
                <div class="detail-metadata-item">
                    <span class="detail-metadata-label">Diunggah oleh:</span>
                    <span>{{ $dataSurvei->pengunggah->nama ?? 'Admin' }}</span>
                </div>
                <div class="detail-metadata-item">
                    <span class="detail-metadata-label">Tanggal Upload:</span>
                    <span>{{ $dataSurvei->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>

            <div class="detail-actions">
                <a href="{{ route('admin.data_survei.edit', $dataSurvei) }}" class="btn-edit-detail">Edit Data</a>
                <a href="{{ route('admin.data_survei.index') }}" class="btn-back-detail">Kembali</a>
                <form action="{{ route('admin.data_survei.destroy', $dataSurvei) }}" method="POST" style="display:inline;"
                    onsubmit="return confirm('Yakin ingin menghapus data survei ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete-detail">Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/openseadragon@4.1.0/build/openseadragon/openseadragon.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil URL gambar yang benar
    const imageUrl = @json(
        $dataSurvei->gambar_medium
            ? asset('storage/' . $dataSurvei->gambar_medium)
            : asset('storage/' . $dataSurvei->gambar_pratinjau)
    );

    console.log('OpenSeadragon loading image:', imageUrl); // CEK DI CONSOLE!

    const viewer = OpenSeadragon({
        id: "openseadragon-viewer",
        prefixUrl: "https://cdn.jsdelivr.net/npm/openseadragon@4.1.0/build/openseadragon/images/",
        tileSources: {
            type: 'image',
            url: imageUrl,
            // Tambahan penting biar gambar besar langsung muncul
            crossOriginPolicy: false,
            ajaxWithCredentials: false
        },
        gestureSettingsMouse: {
            scrollToZoom: true,
            clickToZoom: true,
            dblClickToZoom: true
        },
        animationTime: 0.5,
        blendTime: 0.1,
        constrainDuringPan: true,
        maxZoomPixelRatio: 10,
        minZoomLevel: 0.5,
        visibilityRatio: 1,
        zoomPerScroll: 1.4,
        showNavigationControl: false
    });

    // Tombol manual
    document.getElementById('zoomIn').onclick = () => viewer.viewport.zoomBy(1.5);
    document.getElementById('zoomOut').onclick = () => viewer.viewport.zoomBy(0.7);
    document.getElementById('home').onclick = () => viewer.viewport.goHome();
    document.getElementById('fullPage').onclick = () => {
        if (!document.fullscreenElement) {
            document.getElementById('openseadragon-viewer').requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    };

    // Debug: kalau masih kosong
    viewer.addHandler('open-failed', function(event) {
        console.error('Gagal load gambar OpenSeadragon:', event);
        alert('Gambar gagal dimuat. Cek URL di Console (F12)');
    });

    viewer.addHandler('open', function() {
        console.log('Gambar berhasil dimuat di OpenSeadragon!');
    });
});
</script>
@endpush