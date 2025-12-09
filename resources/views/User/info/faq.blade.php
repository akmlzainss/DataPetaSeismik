@extends('layouts.app')

@section('title', 'FAQ - BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-info.css') }}">
@endpush

@section('content')
    <div class="info-container">
        <!-- Hero Section -->
        <div class="info-hero">
            <div class="info-hero-content">
                <h1 class="info-hero-title">Pertanyaan yang Sering Diajukan (FAQ)</h1>
                <p class="info-hero-subtitle">Temukan jawaban atas pertanyaan umum tentang sistem kami</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="info-content">
            <!-- Umum -->
            <section class="info-section">
                <h2 class="section-title">Umum</h2>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Apa itu Sistem Informasi Survei Seismik BBSPGL?
                    </div>
                    <div class="faq-answer">
                        Sistem Informasi Survei Seismik BBSPGL adalah platform digital yang menyediakan akses ke data survei
                        seismik yang telah dilakukan oleh Balai Besar Survei dan Pemetaan Geologi di wilayah Indonesia.
                        Sistem ini memudahkan pengguna untuk mencari, melihat, dan mengakses informasi survei seismik.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Siapa yang dapat menggunakan sistem ini?
                    </div>
                    <div class="faq-answer">
                        Sistem ini dapat diakses oleh siapa saja yang membutuhkan informasi tentang survei seismik di
                        Indonesia, termasuk peneliti, akademisi, mahasiswa, dan masyarakat umum.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Apakah sistem ini gratis?
                    </div>
                    <div class="faq-answer">
                        Ya, akses ke sistem informasi ini sepenuhnya gratis untuk semua pengguna. Anda dapat melihat dan
                        mengakses data survei tanpa biaya apapun.
                    </div>
                </div>
            </section>

            <!-- Penggunaan -->
            <section class="info-section">
                <h2 class="section-title">Penggunaan Sistem</h2>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Bagaimana cara mencari data survei tertentu?
                    </div>
                    <div class="faq-answer">
                        Anda dapat menggunakan fitur pencarian dan filter di halaman Katalog. Masukkan kata kunci di kolom
                        pencarian atau gunakan filter berdasarkan tahun, tipe survei, dan wilayah untuk menemukan data yang
                        Anda butuhkan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Bagaimana cara melihat lokasi survei di peta?
                    </div>
                    <div class="faq-answer">
                        Klik menu "Peta" di navigasi atas. Anda akan melihat peta Indonesia dengan marker biru yang
                        menunjukkan lokasi survei. Klik marker untuk melihat informasi detail survei tersebut.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Bagaimana cara mengakses file survei?
                    </div>
                    <div class="faq-answer">
                        Buka halaman detail survei dengan mengklik tombol "Lihat Detail" pada kartu survei. Di halaman
                        detail, Anda akan menemukan tombol "Buka File" yang akan mengarahkan Anda ke file survei (jika
                        tersedia).
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Bagaimana cara menggunakan viewer gambar?
                    </div>
                    <div class="faq-answer">
                        Di halaman detail survei, gambar pratinjau dapat di-zoom dan di-pan. Gunakan scroll mouse untuk zoom
                        in/out, atau gunakan kontrol navigasi di pojok kanan atas gambar. Klik dan drag untuk menggeser
                        gambar.
                    </div>
                </div>
            </section>

            <!-- Data -->
            <section class="info-section">
                <h2 class="section-title">Tentang Data</h2>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Apa saja tipe survei yang tersedia?
                    </div>
                    <div class="faq-answer">
                        Sistem ini menyediakan data survei seismik dengan berbagai tipe, termasuk survei 2D, 3D, dan High
                        Resolution (HR). Setiap tipe survei memiliki karakteristik dan kegunaan yang berbeda.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Seberapa sering data diperbarui?
                    </div>
                    <div class="faq-answer">
                        Data survei diperbarui secara berkala sesuai dengan kegiatan survei yang dilakukan oleh BBSPGL.
                        Informasi tanggal update terakhir dapat dilihat di halaman detail setiap survei.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Apakah semua data survei memiliki file yang dapat diakses?
                    </div>
                    <div class="faq-answer">
                        Tidak semua data survei memiliki file yang dapat diakses secara publik. Ketersediaan file tergantung
                        pada kebijakan dan status data survei tersebut.
                    </div>
                </div>
            </section>

            <!-- Teknis -->
            <section class="info-section">
                <h2 class="section-title">Masalah Teknis</h2>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Browser apa yang didukung?
                    </div>
                    <div class="faq-answer">
                        Sistem ini mendukung browser modern seperti Google Chrome, Mozilla Firefox, Microsoft Edge, dan
                        Safari versi terbaru. Untuk pengalaman terbaik, kami merekomendasikan menggunakan Google Chrome.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Peta tidak muncul, apa yang harus dilakukan?
                    </div>
                    <div class="faq-answer">
                        Pastikan koneksi internet Anda stabil dan browser Anda mengizinkan JavaScript. Coba refresh halaman
                        atau clear cache browser Anda. Jika masalah berlanjut, hubungi kami.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Gambar tidak dapat di-zoom, kenapa?
                    </div>
                    <div class="faq-answer">
                        Pastikan gambar sudah selesai dimuat (loading selesai). Jika masih bermasalah, coba refresh halaman.
                        Beberapa gambar mungkin memerlukan waktu loading lebih lama tergantung ukuran file dan kecepatan
                        internet.
                    </div>
                </div>
            </section>

            <!-- Kontak -->
            <section class="info-section">
                <h2 class="section-title">Pertanyaan Lain?</h2>
                <p>Jika pertanyaan Anda tidak terjawab di sini, silakan hubungi kami melalui:</p>
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
