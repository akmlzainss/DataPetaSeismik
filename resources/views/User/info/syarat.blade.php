@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-info.css') }}">
@endpush

@section('content')
    <div class="info-container">
        <div class="info-hero">
            <div class="info-hero-content">
                <h1 class="info-hero-title">Syarat & Ketentuan</h1>
                <p class="info-hero-subtitle">Ketentuan penggunaan Sistem Informasi Survei Seismik</p>
            </div>
        </div>

        <div class="info-content">
            <section class="info-section">
                <h2 class="section-title">Penerimaan Syarat</h2>
                <p>Dengan mengakses dan menggunakan Sistem Informasi Survei Seismik BBSPGL, Anda setuju untuk terikat dengan
                    syarat dan ketentuan berikut. Jika Anda tidak setuju dengan syarat ini, mohon untuk tidak menggunakan
                    sistem ini.</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Penggunaan Sistem</h2>
                <div class="content-box">
                    <h3>Anda Diperbolehkan:</h3>
                    <ul>
                        <li>Mengakses dan melihat data survei untuk tujuan yang sah</li>
                        <li>Mengunduh dan menggunakan data untuk keperluan penelitian dan pendidikan</li>
                        <li>Membagikan link ke halaman sistem ini</li>
                    </ul>
                </div>
                <div class="content-box">
                    <h3>Anda Tidak Diperbolehkan:</h3>
                    <ul>
                        <li>Menggunakan data untuk tujuan komersial tanpa izin tertulis</li>
                        <li>Memodifikasi, mendistribusikan ulang, atau menjual data tanpa izin</li>
                        <li>Melakukan tindakan yang dapat merusak atau mengganggu sistem</li>
                        <li>Mengakses sistem dengan cara yang tidak sah atau melanggar hukum</li>
                    </ul>
                </div>
            </section>

            <section class="info-section">
                <h2 class="section-title">Hak Kekayaan Intelektual</h2>
                <p>Semua konten, data, dan materi yang tersedia di sistem ini adalah milik BBSPGL dan dilindungi oleh hukum
                    hak cipta Indonesia. Penggunaan data harus mencantumkan sumber dari BBSPGL.</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Keakuratan Data</h2>
                <p>Kami berusaha untuk menyediakan data yang akurat dan terkini. Namun, kami tidak menjamin keakuratan,
                    kelengkapan, atau keandalan data. Pengguna bertanggung jawab untuk memverifikasi data sebelum
                    menggunakannya.</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Pembatasan Tanggung Jawab</h2>
                <p>BBSPGL tidak bertanggung jawab atas kerugian atau kerusakan yang timbul dari penggunaan atau
                    ketidakmampuan menggunakan sistem ini, termasuk namun tidak terbatas pada kehilangan data atau
                    keuntungan.</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Perubahan Syarat</h2>
                <p>Kami berhak untuk mengubah syarat dan ketentuan ini kapan saja. Perubahan akan berlaku segera setelah
                    dipublikasikan di halaman ini. Penggunaan sistem setelah perubahan berarti Anda menerima syarat yang
                    diperbarui.</p>
                <p><strong>Terakhir diperbarui:</strong> {{ date('d F Y') }}</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Hukum yang Berlaku</h2>
                <p>Syarat dan ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Republik Indonesia. Setiap
                    sengketa yang timbul akan diselesaikan di pengadilan yang berwenang di Indonesia.</p>
            </section>

            <section class="info-section">
                <h2 class="section-title">Kontak</h2>
                <p>Untuk pertanyaan tentang syarat dan ketentuan ini, silakan hubungi:</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                        <span>info@bbspgl.esdm.go.id</span>
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
