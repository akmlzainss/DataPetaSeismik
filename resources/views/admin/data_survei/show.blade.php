{{-- resources/views/admin/data_survei/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail: ' . $dataSurvei->judul)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/openseadragon@4.1.0/build/openseadragon/openseadragon.min.css">
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
        .quill-content .ql-align-center { text-align: center; }
        .quill-content .ql-align-right { text-align: right; }
        .quill-content .ql-align-justify { text-align: justify; }

        /* List Styling */
        .quill-content ul, .quill-content ol { 
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
        .quill-content h1, .quill-content h2, .quill-content h3, .quill-content h4, .quill-content h5, .quill-content h6 {
            font-weight: bold;
            margin: 1em 0 0.5em;
            line-height: 1.2;
        }
        .quill-content h1 { font-size: 2em; }
        .quill-content h2 { font-size: 1.5em; }
        .quill-content h3 { font-size: 1.17em; }
        .quill-content h4 { font-size: 1em; }
        .quill-content h5 { font-size: 0.83em; }
        .quill-content h6 { font-size: 0.67em; }

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
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Detail Data Survei</h1>
        <p>Informasi lengkap survei seismik</p>
    </div>

    <div class="welcome-section">
        <div class="detail-container">
            <div class="detail-header">
                <h2 class="detail-title">{{ $dataSurvei->judul }}</h2>
                <span class="detail-badge">{{ $dataSurvei->tipe }}</span>
            </div>

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
                    <p>Tidak ada gambar pratinjau yang tersedia untuk ditampilkan.</p>
                </div>
            @endif

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
                    {{--
                    |--------------------------------------------------------------------------
                    | BAGIAN 2: MEMBUNGKUS OUTPUT DESKRIPSI DENGAN CLASS 'quill-content'
                    |--------------------------------------------------------------------------
                    | Class ini akan menggunakan CSS yang ditambahkan di Bagian 1.
                    --}}
                    <td>
                        <div class="quill-content">
                            {!! $safeDeskripsi ?? '-' !!}
                        </div>
                    </td>
                </tr>
            </table>

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

                {{-- Tombol Hapus: Sekarang akan memicu modal --}}
                <button type="button" class="btn-delete-detail" id="triggerDeleteModal"
                    data-judul="{{ $dataSurvei->judul }}"
                    data-action="{{ route('admin.data_survei.destroy', $dataSurvei) }}">
                    Hapus Data
                </button>
            </div>

            {{-- Form Hapus yang disembunyikan, akan disubmit oleh JavaScript --}}
            <form id="deleteForm" action="{{ route('admin.data_survei.destroy', $dataSurvei) }}" method="POST"
                style="display:none;">
                @csrf @method('DELETE')
            </form>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/openseadragon@4.1.0/build/openseadragon/openseadragon.min.js"></script>

    <script>
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
    </script>
@endpush