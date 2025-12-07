{{-- resources/views/tentang.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL)')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/public-tentang.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="container-custom">
        <h1 class="hero-title">Tentang BBSPGL</h1>
        <p class="hero-subtitle">Profil, Visi Misi, dan Struktur Organisasi Balai Besar Survei dan Pemetaan Geologi Kelautan</p>
    </div>
</div>

<section class="tentang-section" style="position: relative; z-index: 2;">
    <div class="tentang-container">
        
        {{-- Main Content --}}
        <div class="tentang-content">
            
            {{-- Sejarah Singkat --}}
            <div class="content-section">
                <h2 class="section-heading">Sejarah Singkat</h2>
                <p class="lead-text">
                    Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL) merupakan unit pelaksana teknis 
                    di bawah Badan Geologi, Kementerian Energi dan Sumber Daya Mineral (ESDM). Berdasarkan 
                    Peraturan Menteri ESDM Nomor 8 Tahun 2022, BBSPGL resmi bergabung dengan Badan Geologi, 
                    bertujuan untuk memperkuat tugas dan fungsi penyelidikan serta pelayanan geologi darat dan 
                    laut secara terintegrasi.
                </p>
            </div>

            {{-- Visi Section --}}
            <div class="content-section">
                <div class="visi-box">
                    <h2 class="visi-heading">
                        <i class="fas fa-eye"></i>
                        Visi
                    </h2>
                    <p class="visi-text">
                        "Terwujudnya Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL) sebagai 
                        <span class="highlight">Center of Excellence (CoE)</span> di bidang geologi kelautan 
                        yang profesional, unggul, berdaya saing, dan bertaraf Internasional di bidang sumber 
                        daya energi, sumber daya mineral, infrastruktur, dan mitigasi kebencanaan kelautan."
                    </p>
                </div>
            </div>

            {{-- Misi Section --}}
            <div class="content-section">
                <h2 class="section-heading">Misi Kami</h2>
                <ul class="misi-list">
                    <li class="misi-item">
                        <div class="misi-icon">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="misi-text">
                            Melaksanakan survei dan pemetaan geologi kelautan di bidang potensi sumber daya energi, 
                            sumber daya mineral, lingkungan, dan mitigasi kebencanaan geologi kelautan.
                        </div>
                    </li>
                    <li class="misi-item">
                        <div class="misi-icon">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="misi-text">
                            Membantu merumuskan rekomendasi teknis di bidang sumber daya energi kelautan, 
                            mineral kelautan, lingkungan, mitigasi dan infrastruktur kelautan.
                        </div>
                    </li>
                    <li class="misi-item">
                        <div class="misi-icon">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="misi-text">
                            Meningkatkan kualitas jasa survei dan pemetaan geologi kelautan yang mampu 
                            mendukung industri kelautan.
                        </div>
                    </li>
                </ul>
            </div>
            
            {{-- Tugas Pokok & Fungsi --}}
            <div class="content-section">
                <h2 class="section-heading divider-top">Tugas Pokok & Fungsi Utama</h2>

                {{-- Tugas Pokok Box --}}
                <div class="tugas-pokok-box">
                    <h3 class="tugas-heading">
                        <i class="fas fa-tasks"></i>
                        Tugas Pokok
                    </h3>
                    <p class="tugas-text">
                        Melaksanakan survei dan pemetaan di bidang geologi kelautan.
                    </p>
                </div>

                {{-- Fungsi Pelaksanaan --}}
                <h3 class="fungsi-heading">Fungsi Pelaksanaan:</h3>
                <ul class="fungsi-list">
                    <li class="fungsi-item">
                        Pelaksanaan survei dan pemetaan di bidang geologi kelautan.
                    </li>
                    <li class="fungsi-item">
                        Pengelolaan data dan informasi teknis geologi kelautan.
                    </li>
                    <li class="fungsi-item">
                        Pelayanan jasa survei dan pemetaan di bidang geologi kelautan.
                    </li>
                    <li class="fungsi-item">
                        Pengelolaan sarana dan prasarana survei dan pemetaan di bidang geologi kelautan.
                    </li>
                    <li class="fungsi-item">
                        Pelaksanaan urusan hukum dan kerja sama.
                    </li>
                    <li class="fungsi-item">
                        Pelaksanaan ketatausahaan dan administrasi kelembagaan.
                    </li>
                </ul>
            </div>
            
            {{-- Struktur Organisasi --}}
            <div class="content-section">
                <h2 class="section-heading divider-top">Struktur Organisasi</h2>
                <div class="struktur-box">
                    <i class="fas fa-sitemap struktur-icon"></i>
                    <p class="struktur-text">
                        Struktur organisasi Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL).
                    </p>
                    <span class="struktur-placeholder">
                        Diagram akan ditampilkan di sini
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection