{{-- resources/views/katalog.blade.php --}}
@extends('layouts.app')

@section('title', 'Katalog Data Survei Seismik')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/public-katalog.css') }}">
@endpush

@section('content')

<section class="katalog-section">
    <div class="katalog-container">
        
        {{-- Page Header --}}
        <div class="katalog-header">
            <h1 class="katalog-title">Katalog Data Survei</h1>
            <p class="katalog-subtitle">
                Jelajahi, saring, dan unduh data survei geologi kelautan yang tersedia untuk umum.
            </p>
        </div>

        {{-- Search and Filter Bar --}}
        <div class="search-filter-bar">
            <div class="search-filter-wrapper">
                <input 
                    type="text" 
                    class="search-input" 
                    placeholder="Cari berdasarkan judul, wilayah, atau tahun..." 
                    aria-label="Pencarian"
                >
                
                <select class="filter-select" aria-label="Filter Tipe Survei">
                    <option value="">Filter Tipe Survei</option>
                    <option value="seismik">Seismik Refleksi</option>
                    <option value="magnetik">Magnetik</option>
                    <option value="gravitasi">Gravitasi</option>
                </select>
                
                <button class="search-button">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
            </div>
        </div>

        {{-- Data Cards Grid --}}
        @if(empty($surveys))
            <div class="data-cards-grid">
                
                {{-- Card Dummy 1 - Seismik --}}
                <div class="data-card">
                    <div class="data-card-content">
                        <span class="survey-badge badge-seismik">Seismik 2D</span>
                        <h3 class="data-card-title">Survei Perairan Jawa Barat</h3>
                        <div class="data-card-meta">
                            <span>Tahun: 2024</span>
                            <span class="meta-divider"></span>
                            <span>Area: Laut Jawa</span>
                        </div>
                        <p class="data-card-description">
                            Pengambilan data seismik resolusi tinggi untuk pemetaan struktur geologi dangkal 
                            di zona subduksi. Data mencakup interpretasi stratigrafi dan identifikasi potensi hidrokarbon.
                        </p>
                        <a href="#" class="data-card-link">
                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Card Dummy 2 - Magnetik --}}
                <div class="data-card">
                    <div class="data-card-content">
                        <span class="survey-badge badge-magnetik">Magnetik</span>
                        <h3 class="data-card-title">Pemetaan Anomali Sulawesi</h3>
                        <div class="data-card-meta">
                            <span>Tahun: 2023</span>
                            <span class="meta-divider"></span>
                            <span>Area: Sulawesi Tengah</span>
                        </div>
                        <p class="data-card-description">
                            Data anomali magnetik yang digunakan untuk mengidentifikasi batuan dasar dan potensi mineral. 
                            Survei mencakup area seluas 2.500 km² dengan resolusi tinggi.
                        </p>
                        <a href="#" class="data-card-link">
                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Card Dummy 3 - Gravitasi --}}
                <div class="data-card">
                    <div class="data-card-content">
                        <span class="survey-badge badge-gravitasi">Gravitasi</span>
                        <h3 class="data-card-title">Survei Selat Sunda</h3>
                        <div class="data-card-meta">
                            <span>Tahun: 2022</span>
                            <span class="meta-divider"></span>
                            <span>Area: Selat Sunda</span>
                        </div>
                        <p class="data-card-description">
                            Data free-air dan Bouguer anomaly untuk pemodelan densitas bawah permukaan. 
                            Hasil survei mendukung pemetaan cekungan sedimen dan struktur tektonik regional.
                        </p>
                        <a href="#" class="data-card-link">
                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Card Dummy 4 - Seismik --}}
                <div class="data-card">
                    <div class="data-card-content">
                        <span class="survey-badge badge-seismik">Seismik 3D</span>
                        <h3 class="data-card-title">Blok Natuna Timur</h3>
                        <div class="data-card-meta">
                            <span>Tahun: 2024</span>
                            <span class="meta-divider"></span>
                            <span>Area: Laut Natuna</span>
                        </div>
                        <p class="data-card-description">
                            Survei seismik 3D komprehensif mencakup 1.200 km² untuk eksplorasi minyak dan gas bumi. 
                            Data berkualitas tinggi dengan pengolahan full-stack migration.
                        </p>
                        <a href="#" class="data-card-link">
                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Card Dummy 5 - Magnetik --}}
                <div class="data-card">
                    <div class="data-card-content">
                        <span class="survey-badge badge-magnetik">Magnetik</span>
                        <h3 class="data-card-title">Eksplorasi Maluku Utara</h3>
                        <div class="data-card-meta">
                            <span>Tahun: 2023</span>
                            <span class="meta-divider"></span>
                            <span>Area: Halmahera</span>
                        </div>
                        <p class="data-card-description">
                            Pemetaan magnetik untuk identifikasi zona mineralisasi dan struktur geologi kompleks 
                            di wilayah vulkanik aktif dengan potensi deposit logam mulia.
                        </p>
                        <a href="#" class="data-card-link">
                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Card Dummy 6 - Gravitasi --}}
                <div class="data-card">
                    <div class="data-card-content">
                        <span class="survey-badge badge-gravitasi">Gravitasi</span>
                        <h3 class="data-card-title">Cekungan Kalimantan Timur</h3>
                        <div class="data-card-meta">
                            <span>Tahun: 2023</span>
                            <span class="meta-divider"></span>
                            <span>Area: Kutai Basin</span>
                        </div>
                        <p class="data-card-description">
                            Data gravitasi regional untuk pemodelan cekungan sedimen dan estimasi ketebalan sedimen. 
                            Mendukung evaluasi potensi hidrokarbon di area prospektif.
                        </p>
                        <a href="#" class="data-card-link">
                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>

            {{-- Pagination Placeholder --}}
            <div class="pagination-wrapper">
                <div class="pagination-placeholder">
                    Menampilkan 6 dari 150+ data survei tersedia
                </div>
            </div>

        @else
            {{-- Empty State --}}
            <div class="empty-state">
                <i class="fas fa-database empty-state-icon"></i>
                <p class="empty-state-text">Tidak ada data survei yang ditemukan saat ini.</p>
            </div>
        @endif

    </div>
</section>

@endsection