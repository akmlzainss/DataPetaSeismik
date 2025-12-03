{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Beranda - BBSPGL Sistem Informasi Survei Seismik')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/public-home.css') }}">
@endpush

@section('content')

{{-- ========== HERO SECTION ========== --}}
<section class="hero-section">
    <div class="hero-content">
        <div class="hero-badge">Balai Besar Survei dan Pemetaan Geologi</div>
        <h1 class="hero-title">Sistem Informasi<br>Survei Seismik Nasional</h1>
        <p class="hero-description">
            Platform digital terpadu untuk akses data survei seismik di seluruh wilayah Indonesia. 
            Mendukung riset, perencanaan, dan pengembangan sumber daya mineral nasional.
        </p>
        <div class="hero-buttons">
            <a href="{{ route('katalog') }}" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                </svg>
                Jelajahi Data Survei
            </a>
            <a href="{{ route('peta') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                Lihat Peta
            </a>
        </div>
    </div>
    <div class="hero-image">
        <img src="{{ asset('images/hero-illustration.png') }}" alt="Survei Seismik Illustration">
    </div>
</section>

{{-- ========== TENTANG SECTION ========== --}}
<section class="about-section">
    <div class="container-custom">
        <div class="about-content">
            <div class="about-text">
                <h2 class="section-title">Tentang BBSPGL</h2>
                <p class="section-description">
                    Balai Besar Survei dan Pemetaan Geologi (BBSPGL) merupakan unit pelaksana teknis di bawah 
                    Kementerian Energi dan Sumber Daya Mineral yang bertanggung jawab dalam kegiatan survei dan 
                    pemetaan geologi di Indonesia.
                </p>
                <p class="section-description">
                    Melalui platform ini, kami menyediakan akses terbuka terhadap data hasil survei seismik yang 
                    telah dilakukan di berbagai wilayah Indonesia. Data ini dapat digunakan untuk kepentingan riset, 
                    perencanaan, dan pengembangan sektor energi dan sumber daya mineral.
                </p>
            </div>
            <div class="about-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($totalSurvei) }}+</div>
                    <div class="stat-label">Data Survei</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($totalWilayah) }}+</div>
                    <div class="stat-label">Wilayah</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $tahunBeroperasi }}+</div>
                    <div class="stat-label">Tahun</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== FITUR/LAYANAN SECTION ========== --}}
<section class="features-section">
    <div class="container-custom">
        <div class="section-header">
            <h2 class="section-title">Layanan Kami</h2>
            <p class="section-subtitle">Fitur dan kemudahan yang kami sediakan untuk Anda</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Katalog Data Lengkap</h3>
                <p class="feature-description">
                    Akses ribuan data survei seismik yang tersimpan rapi dengan sistem katalog yang mudah dicari dan difilter.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Peta Interaktif</h3>
                <p class="feature-description">
                    Visualisasi lokasi survei dalam peta Indonesia yang interaktif untuk memudahkan pencarian berdasarkan wilayah.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Download Mudah</h3>
                <p class="feature-description">
                    Unduh data survei dalam berbagai format untuk kebutuhan riset dan analisis Anda dengan proses yang simpel.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Pencarian Canggih</h3>
                <p class="feature-description">
                    Temukan data yang Anda butuhkan dengan cepat menggunakan fitur pencarian dan filter berdasarkan berbagai kriteria.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Data Terverifikasi</h3>
                <p class="feature-description">
                    Semua data telah melalui proses verifikasi dan validasi untuk menjamin akurasi dan kualitas informasi.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Informasi Lengkap</h3>
                <p class="feature-description">
                    Setiap data dilengkapi dengan informasi detail meliputi lokasi, tahun, tipe survei, dan metadata lainnya.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ========== CTA SECTION ========== --}}
<section class="cta-section">
    <div class="container-custom">
        <div class="cta-content">
            <h2 class="cta-title">Siap Menjelajahi Data Survei Seismik?</h2>
            <p class="cta-description">
                Akses ribuan data survei berkualitas untuk mendukung riset dan pengembangan Anda
            </p>
            <div class="cta-buttons">
                <a href="{{ route('katalog') }}" class="btn-cta-primary">
                    Mulai Sekarang
                </a>
                <a href="#" class="btn-cta-secondary">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

@endsection