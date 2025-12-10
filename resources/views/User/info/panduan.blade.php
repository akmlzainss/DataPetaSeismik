@extends('layouts.app')

@section('title', 'Panduan Pengguna - BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-info.css') }}">
@endpush

@section('content')
    <div class="info-container">
        <!-- Hero Section -->
        <div class="info-hero">
            <div class="info-hero-content">
                <h1 class="info-hero-title">Panduan Pengguna</h1>
                <p class="info-hero-subtitle">Petunjuk lengkap menggunakan Sistem Informasi Survei Seismik</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="info-content">
            <!-- Pengenalan -->
            <section class="info-section">
                <h2 class="section-title">Pengenalan Sistem</h2>
                <p>Sistem Informasi Survei Seismik BBSPGL adalah platform digital yang menyediakan akses ke data survei
                    seismik yang telah dilakukan di wilayah Indonesia. Sistem ini dirancang untuk memudahkan pengguna dalam
                    mencari, melihat, dan mengakses informasi survei seismik.</p>
            </section>

            <!-- Fitur Utama -->
            <section class="info-section">
                <h2 class="section-title">Fitur Utama</h2>

                <div class="features-container">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3>Peta Interaktif</h3>
                            <p>Visualisasi lokasi survei seismik di peta Indonesia dengan marker interaktif. Klik marker
                                untuk
                                melihat detail survei.</p>
                        </div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3>Katalog Data Survei</h3>
                            <p>Daftar lengkap data survei dengan fitur pencarian dan filter berdasarkan tahun, tipe, dan
                                wilayah.</p>
                        </div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3>Gambar Pratinjau</h3>
                            <p>Lihat gambar hasil survei dengan viewer interaktif yang mendukung zoom dan pan.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cara Menggunakan -->
            <section class="info-section">
                <h2 class="section-title">Cara Menggunakan</h2>

                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>Akses Halaman Beranda</h3>
                            <p>Buka website dan Anda akan melihat halaman beranda dengan informasi umum tentang BBSPGL dan
                                statistik data survei.</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>Jelajahi Katalog</h3>
                            <p>Klik menu "Katalog" untuk melihat daftar semua data survei. Gunakan fitur pencarian dan
                                filter
                                untuk menemukan data yang Anda butuhkan.</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>Lihat Detail Survei</h3>
                            <p>Klik tombol "Lihat Detail" pada kartu survei untuk melihat informasi lengkap, gambar
                                pratinjau,
                                dan mengakses file survei.</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3>Gunakan Peta Interaktif</h3>
                            <p>Klik menu "Peta" untuk melihat visualisasi lokasi survei di peta Indonesia. Klik marker untuk
                                melihat informasi survei.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tips -->
            <section class="info-section">
                <h2 class="section-title">Tips & Trik</h2>
                <div class="tips-box">
                    <ul>
                        <li>Gunakan fitur filter untuk mempersempit pencarian data survei</li>
                        <li>Klik marker di peta untuk langsung melihat detail survei</li>
                        <li>Gunakan viewer gambar untuk zoom in/out pada gambar pratinjau</li>
                        <li>Bookmark halaman detail survei yang sering Anda akses</li>
                        <li>Hubungi kami jika mengalami kesulitan atau membutuhkan bantuan</li>
                    </ul>
                </div>
            </section>

            <!-- Bantuan -->
            <section class="info-section">
                <h2 class="section-title">Butuh Bantuan?</h2>
                <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, silakan hubungi kami melalui:</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                        <span>info@bbspgl.esdm.go.id</span>
                    </div>
                    <div class="contact-item">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                        </svg>
                        <span>(022) 7272606</span>
                    </div>
                </div>
            </section>

            <!-- Back Button -->
            <div class="back-button-container">
                <a href="{{ route('beranda') }}" class="back-button">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
