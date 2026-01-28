@extends('layouts.app')

@section('title', 'Bantuan - BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-info.css') }}">
@endpush

@section('content')
    <div class="info-container">
        <div class="info-hero">
            <div class="info-hero-content">
                <h1 class="info-hero-title">Pusat Bantuan</h1>
                <p class="info-hero-subtitle">Kami siap membantu Anda</p>
            </div>
        </div>

        <div class="info-content">
            <section class="info-section">
                <h2 class="section-title">Hubungi Kami</h2>
                <p>Jika Anda membutuhkan bantuan atau memiliki pertanyaan, jangan ragu untuk menghubungi kami melalui salah
                    satu cara berikut:</p>
            </section>

            <div class="help-grid">
                <div class="help-card">
                    <div class="help-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                    </div>
                    <h3>Email</h3>
                    <p>info@bbspgl.esdm.go.id</p>
                    <span class="help-time">Respons dalam 1-2 hari kerja</span>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                        </svg>
                    </div>
                    <h3>Telepon</h3>
                    <p>(022) 7272606</p>
                    <span class="help-time">Senin - Jumat, 08:00 - 16:30 WIB</span>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                        </svg>
                    </div>
                    <h3>Alamat Kantor</h3>
                    <p>Jl. Dr. Djunjunan No.236, Husen Sastranegara,<br>Kec. Cicendo, Kota Bandung, Jawa Barat 40174</p>
                    <span class="help-time">Kunjungan dengan perjanjian</span>
                </div>
            </div>

            <section class="info-section">
                <h2 class="section-title">Sumber Daya Lainnya</h2>
                <div class="resource-links">
                    <a href="{{ route('panduan') }}" class="resource-link">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                        </svg>
                        <div>
                            <h4>Panduan Pengguna</h4>
                            <p>Petunjuk lengkap menggunakan sistem</p>
                        </div>
                    </a>

                    <a href="{{ route('faq') }}" class="resource-link">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        <div>
                            <h4>FAQ</h4>
                            <p>Pertanyaan yang sering diajukan</p>
                        </div>
                    </a>
                </div>
            </section>

            <section class="info-section">
                <h2 class="section-title">Jam Operasional</h2>
                <div class="schedule-box">
                    <div class="schedule-item">
                        <span class="schedule-day">Senin - Jumat</span>
                        <span class="schedule-time">08:00 - 16:30 WIB</span>
                    </div>
                    <div class="schedule-item">
                        <span class="schedule-day">Sabtu - Minggu</span>
                        <span class="schedule-time">Tutup</span>
                    </div>
                    <div class="schedule-item">
                        <span class="schedule-day">Hari Libur Nasional</span>
                        <span class="schedule-time">Tutup</span>
                    </div>
                </div>
            </section>

            <section class="info-section">
                <h2 class="section-title">Formulir Kontak</h2>
                <p>Anda juga dapat mengirim pesan melalui <a href="{{ route('kontak') }}"
                        style="color: #003366; font-weight: 600;">formulir kontak</a> kami.</p>
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
