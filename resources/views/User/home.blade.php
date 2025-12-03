{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Beranda - BBSPGL Sistem Informasi Survei Seismik')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/public-home.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

{{-- ========== HERO CAROUSEL ========== --}}
<section class="hero-bg flex items-center justify-center text-white relative">
    <div class="carousel-item active" style="background-image: url('{{ asset('images/bg.jpg') }}');"></div>
    <div class="carousel-item" style="background-image: url('{{ asset('images/loct.jpg') }}');"></div>
    
    <div class="hero-overlay"></div>
    
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto py-16">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">
            Survei dan Pemetaan Geologi
        </h1>
        <p class="text-lg md:text-2xl mb-6 drop-shadow">
            Platform terpadu penyedia data dan informasi geologi kelautan nasional
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-6">
            <a href="{{ route('katalog.index') }}" class="bg-[#004A99] hover:bg-[#003970] text-white font-semibold py-3 px-6 rounded-full text-lg transition">
                Jelajahi Data Survei
            </a>
            <a href="#tentang" class="border-2 border-white hover:bg-white hover:text-[#004A99] text-white font-semibold py-3 px-6 rounded-full text-lg transition">
                Tentang Kami
            </a>
        </div>
    </div>

    <button onclick="changeSlide(-1)" class="carousel-nav absolute left-6 top-1/2 transform -translate-y-1/2 text-white text-2xl">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button onclick="changeSlide(1)" class="carousel-nav absolute right-6 top-1/2 transform -translate-y-1/2 text-white text-2xl">
        <i class="fas fa-chevron-right"></i>
    </button>
    
    <div class="carousel-indicators">
        <span class="indicator active" onclick="goToSlide(0)"></span>
        <span class="indicator" onclick="goToSlide(1)"></span>
    </div>
</section>

{{-- ========== STATISTIK CARDS ========== --}}
<section class="stats-section">
    <div class="container mx-auto px-6">
        <div class="stats-grid">
            <div class="stat-card-home">
                <div class="stat-icon-home">
                    <svg viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ number_format($totalSurvei) }}</div>
                <div class="stat-label-home">Total Data Survei</div>
            </div>

            <div class="stat-card-home">
                <div class="stat-icon-home">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ number_format($totalWilayah) }}</div>
                <div class="stat-label-home">Wilayah Tercakup</div>
            </div>

            <div class="stat-card-home">
                <div class="stat-icon-home">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ number_format($surveiBulanIni) }}</div>
                <div class="stat-label-home">Data Bulan Ini</div>
            </div>

            <div class="stat-card-home">
                <div class="stat-icon-home">
                    <svg viewBox="0 0 24 24">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ $tahunBeroperasi }}+</div>
                <div class="stat-label-home">Tahun Beroperasi</div>
            </div>
        </div>
    </div>
</section>

{{-- ========== FEATURED SURVEYS ========== --}}
<section class="featured-section">
    <div class="container mx-auto px-6">
        <h2 class="section-title">Survei Terbaru</h2>
        <p class="section-subtitle">Data survei seismik terkini yang telah dipublikasikan</p>

        @if($featuredSurveys->count() > 0)
        <div class="featured-grid">
            @foreach($featuredSurveys as $survei)
            <div class="survey-card">
                @if($survei->gambar_pratinjau)
                    <img src="{{ Storage::url($survei->gambar_pratinjau) }}" alt="{{ $survei->judul }}" class="survey-card-image">
                @else
                    <div class="survey-card-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                        <svg viewBox="0 0 24 24" width="48" height="48" style="fill: #1976d2;">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                    </div>
                @endif

                <div class="survey-card-body">
                    <h3 class="survey-card-title">{{ Str::limit($survei->judul, 60) }}</h3>
                    
                    <div class="survey-card-meta">
                        <span class="survey-badge badge-{{ strtolower($survei->tipe) }}">{{ $survei->tipe }}</span>
                        <span class="survey-badge" style="background: #f5f5f5; color: #666;">{{ $survei->tahun }}</span>
                    </div>

                    <p class="survey-card-desc">{{ $survei->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>

                    <div class="survey-card-footer">
                        <a href="{{ route('survei.show', $survei->id) }}" class="btn-detail">
                            Lihat Detail →
                        </a>
                        @if($survei->tautan_file)
                        <a href="{{ $survei->tautan_file }}" target="_blank" style="color: #f57c00; font-weight: 600; font-size: 14px;">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align: center;">
            <a href="{{ route('katalog.index') }}" class="btn-view-all">
                Lihat Semua Survei ({{ number_format($totalSurvei) }}) →
            </a>
        </div>
        @else
        <p style="text-align: center; color: #999; font-size: 16px;">Belum ada data survei tersedia.</p>
        @endif
    </div>
</section>

{{-- ========== MAP PREVIEW ========== --}}
<section class="map-preview-section">
    <div class="container mx-auto px-6">
        <div class="map-preview-container">
            <div class="map-preview-header">
                <h2>Peta Sebaran Survei</h2>
                <p>Jelajahi lokasi survei seismik di seluruh Indonesia</p>
            </div>
            
            <div id="mapPreview"></div>
            
            <div class="map-preview-footer">
                <a href="{{ route('peta.index') }}" class="btn-view-all">
                    Buka Peta Interaktif Lengkap →
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ========== WHY CHOOSE US ========== --}}
<section class="why-choose-section" id="tentang">
    <div class="container mx-auto px-6">
        <h2 class="section-title">Mengapa Memilih BBSPGL?</h2>
        <p class="section-subtitle">Keunggulan layanan data survei seismik kami</p>

        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 11.75c-.69 0-1.25.56-1.25 1.25s.56 1.25 1.25 1.25 1.25-.56 1.25-1.25-.56-1.25-1.25-1.25zm6 0c-.69 0-1.25.56-1.25 1.25s.56 1.25 1.25 1.25 1.25-.56 1.25-1.25-.56-1.25-1.25-1.25zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8 0-.29.02-.58.05-.86 2.36-1.05 4.23-2.98 5.21-5.37C11.07 8.33 14.05 10 17.42 10c.78 0 1.53-.09 2.25-.26.21.71.33 1.47.33 2.26 0 4.41-3.59 8-8 8z"/>
                    </svg>
                </div>
                <h3>Data Akurat & Terpercaya</h3>
                <p>Semua data survei melalui verifikasi ketat dan standar nasional untuk memastikan akurasi tinggi</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                    </svg>
                </div>
                <h3>Keamanan Data Terjamin</h3>
                <p>Sistem keamanan berlapis untuk melindungi integritas dan kerahasiaan data survei</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
                    </svg>
                </div>
                <h3>Mudah Diakses</h3>
                <p>Platform user-friendly dengan fitur pencarian canggih untuk akses data yang cepat dan efisien</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Terupdate Berkala</h3>
                <p>Database selalu diperbaharui dengan data survei terbaru dari berbagai wilayah Indonesia</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== LATEST UPDATES TIMELINE ========== --}}
<section class="timeline-section">
    <div class="container mx-auto px-6">
        <h2 class="section-title">Pembaruan Terkini</h2>
        <p class="section-subtitle">Aktivitas terbaru dalam penambahan data survei</p>

        <div class="timeline-container">
            @forelse($latestUpdates as $update)
            <div class="timeline-item">
                <div class="timeline-date">
                    <div class="timeline-date-day">{{ $update->created_at->format('d') }}</div>
                    <div class="timeline-date-month">{{ $update->created_at->format('M Y') }}</div>
                </div>
                <div class="timeline-content">
                    <h4>{{ Str::limit($update->judul, 60) }}</h4>
                    <p>{{ $update->wilayah }} • {{ $update->tipe }} • {{ $update->tahun }}</p>
                </div>
            </div>
            @empty
            <p style="text-align: center; color: #999;">Belum ada pembaruan terbaru</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ========== CTA BANNER ========== --}}
<section class="cta-banner">
    <div class="container mx-auto px-6">
        <h2>Butuh Data Survei Seismik?</h2>
        <p>Akses ribuan data survei berkualitas untuk riset dan pengembangan Anda</p>
        
        <div class="cta-buttons">
            <a href="{{ route('katalog.index') }}" class="btn-cta-primary">
                <i class="fas fa-search"></i> Jelajahi Katalog
            </a>
            <a href="{{ route('kontak.index') }}" class="btn-cta-secondary">
                <i class="fas fa-envelope"></i> Hubungi Kami
            </a>
        </div>
    </div>
</section>

{{-- ========== FOOTER ========== --}}
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Tentang BBSPGL</h3>
            <p>Balai Besar Survei dan Pemetaan Geologi adalah unit pelaksana teknis di bawah Kementerian ESDM yang bertanggung jawab dalam survei dan pemetaan geologi Indonesia.</p>
        </div>

        <div class="footer-section">
            <h3>Link Cepat</h3>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('katalog.index') }}">Katalog Survei</a></li>
                <li><a href="{{ route('peta.index') }}">Peta Interaktif</a></li>
                <li><a href="{{ route('tentang.index') }}">Tentang Kami</a></li>
                <li><a href="{{ route('kontak.index') }}">Kontak</a></li>
            </ul>
        </div>

        <div class="footer-section footer-contact">
            <h3>Kontak Kami</h3>
            <p>
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                Jl. Diponegoro No.57, Bandung 40122, Indonesia
            </p>
            <p>
                <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                (022) 7272606
            </p>
            <p>
                <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                info@bbspgl.esdm.go.id
            </p>
            <p>
                <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                Senin - Jumat: 08.00 - 16.00 WIB
            </p>
        </div>

        <div class="footer-section">
            <h3>Ikuti Kami</h3>
            <div class="social-links">
                <a href="#" class="social-link" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="#" class="social-link" aria-label="Twitter">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
                <a href="#" class="social-link" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                </a>
                <a href="#" class="social-link" aria-label="YouTube">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>
            © {{ date('Y') }} BBSPGL - Balai Besar Survei dan Pemetaan Geologi. 
            <a href="https://www.esdm.go.id" target="_blank">Kementerian Energi dan Sumber Daya Mineral</a>
        </p>
    </div>
</footer>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ========== HERO CAROUSEL ==========
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-item');
const indicators = document.querySelectorAll('.indicator');
const totalSlides = slides.length;
let autoplayInterval;

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    indicators.forEach(indicator => indicator.classList.remove('active'));
    
    slides[index].classList.add('active');
    indicators[index].classList.add('active');
}

function changeSlide(direction) {
    currentSlide += direction;
    
    if (currentSlide >= totalSlides) {
        currentSlide = 0;
    } else if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    }
    
    showSlide(currentSlide);
    resetAutoplay();
}

function goToSlide(index) {
    currentSlide = index;
    showSlide(currentSlide);
    resetAutoplay();
}

function autoplay() {
    autoplayInterval = setInterval(() => {
        changeSlide(1);
    }, 5000);
}

function resetAutoplay() {
    clearInterval(autoplayInterval);
    autoplay();
}

document.addEventListener('DOMContentLoaded', () => {
    autoplay();
});

const heroSection = document.querySelector('.hero-bg');
heroSection.addEventListener('mouseenter', () => {
    clearInterval(autoplayInterval);
});

heroSection.addEventListener('mouseleave', () => {
    autoplay();
});

// ========== MAP PREVIEW ==========
document.addEventListener('DOMContentLoaded', function() {
    // Bounds Indonesia
    const indonesiaBounds = L.latLngBounds(
        L.latLng(-11.5, 94.0),
        L.latLng(7.0, 142.0)
    );

    const map = L.map('mapPreview', {
        maxBounds: indonesiaBounds,
        maxBoundsViscosity: 1.0,
        minZoom: 5,
        maxZoom: 10,
        zoomControl: true,
        scrollWheelZoom: false
    }).fitBounds(indonesiaBounds);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Data markers dari controller
    const markersData = @json($markers);

    // Tambahkan markers ke peta
    markersData.forEach(marker => {
        const m = L.marker([marker.pusat_lintang, marker.pusat_bujur])
            .addTo(map)
            .bindPopup(`
                <div style="min-width: 200px;">
                    <strong style="color: #003366; font-size: 14px;">${marker.survei.judul}</strong><br>
                    <span style="font-size: 12px; color: #666;">
                        ${marker.survei.wilayah}<br>
                        ${marker.survei.tipe} • ${marker.survei.tahun}
                    </span>
                </div>
            `);
    });

    // Enable scroll wheel zoom on click
    map.on('click', function() {
        map.scrollWheelZoom.enable();
    });

    // Disable when mouse leaves map
    map.on('mouseout', function() {
        map.scrollWheelZoom.disable();
    });
});
</script>
@endpush