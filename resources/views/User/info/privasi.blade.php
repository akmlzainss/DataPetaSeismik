@extends('layouts.app')

@section('title', 'Kebijakan Privasi - BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-info.css') }}">
@endpush

@section('content')
    <div class="info-container">
        <div class="info-hero">
            <div class="info-hero-content">
                <h1 class="info-hero-title">Kebijakan Privasi</h1>
                <p class="info-hero-subtitle">Komitmen kami dalam melindungi privasi Anda</p>
            </div>
        </div>

        <div class="info-content">
            <section class="info-section">
                <h2 class="section-title">Pendahuluan</h2>
                <p>Balai Besar Survei dan Pemetaan Geologi (BBSPGL) berkomitmen untuk melindungi privasi pengguna Sistem
                    Informasi Survei Seismik. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan,
                    dan melindungi informasi Anda.</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Informasi yang Kami Kumpulkan</h2>
                <div class="content-box">
                    <h3>Informasi Penggunaan</h3>
                    <ul>
                        <li>Alamat IP dan informasi browser</li>
                        <li>Halaman yang dikunjungi dan waktu akses</li>
                        <li>Data pencarian dan filter yang digunakan</li>
                        <li>Interaksi dengan fitur sistem</li>
                    </ul>
                </div>
            </section>

            <section class="info-section">
                <h2 class="section-title">Penggunaan Informasi</h2>
                <p>Informasi yang kami kumpulkan digunakan untuk:</p>
                <ul>
                    <li>Meningkatkan kualitas layanan sistem</li>
                    <li>Menganalisis pola penggunaan untuk pengembangan fitur</li>
                    <li>Memastikan keamanan dan integritas sistem</li>
                    <li>Mematuhi kewajiban hukum dan regulasi</li>
                </ul>
            </section>

            <section class="info-section">
                <h2 class="section-title">Keamanan Data</h2>
                <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang sesuai untuk melindungi informasi
                    Anda dari akses, penggunaan, atau pengungkapan yang tidak sah.</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Cookies</h2>
                <p>Sistem ini menggunakan cookies untuk meningkatkan pengalaman pengguna. Cookies adalah file kecil yang
                    disimpan di perangkat Anda yang membantu kami mengingat preferensi Anda.</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Hak Pengguna</h2>
                <p>Anda memiliki hak untuk:</p>
                <ul>
                    <li>Mengakses informasi yang kami miliki tentang Anda</li>
                    <li>Meminta koreksi informasi yang tidak akurat</li>
                    <li>Meminta penghapusan informasi pribadi Anda</li>
                    <li>Menolak penggunaan cookies melalui pengaturan browser</li>
                </ul>
            </section>

            <section class="info-section">
                <h2 class="section-title">Perubahan Kebijakan</h2>
                <p>Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Perubahan akan diumumkan di halaman ini
                    dengan tanggal efektif yang baru.</p>
                <p><strong>Terakhir diperbarui:</strong> {{ date('d F Y') }}</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Hubungi Kami</h2>
                <p>Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami:</p>
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
                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                        </svg>
                        <span>Jl. Dr. Djunjunan No.236, Husen Sastranegara, Kec. Cicendo, Kota Bandung, Jawa Barat 40174</span>
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
