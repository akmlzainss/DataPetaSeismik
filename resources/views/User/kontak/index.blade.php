@extends('layouts.app')

@section('title', 'Kontak Kami - BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-kontak.css') }}">
@endpush

@section('content')
    <div class="contact-page">
        <!-- Hero Section -->
        <div class="contact-hero">
            <div class="contact-hero-content">
                <h1 class="contact-title">Hubungi Kami</h1>
                <p class="contact-subtitle">
                    Pusat Pelayanan Informasi Survei & Pemetaan Geologi Kelautan.<br>
                    Kami siap mendengar masukan, saran, dan pertanyaan Anda.
                </p>
            </div>
        </div>

        <div class="contact-container">
            <!-- Left Side: Contact Info -->
            <div class="contact-info-card">
                <div class="info-section">
                    <h3 class="info-title">Informasi Kantor</h3>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Alamat</h4>
                            <p>Jl. Dr. Djunjunan No. 236<br>Bandung 40174, Jawa Barat<br>Indonesia</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-fax"></i>
                        </div>
                        <div class="info-content">
                            <h4>Kantor Penunjang</h4>
                            <p>Jl. Kalijaga No. 101 Cirebon - 45113</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Telepon</h4>
                            <p><a href="tel:+62226032020">+62-022-6032020,</a></p>
                            <p><a href="tel:+62226032201">+62-022-6032201</a></p>
                        </div>
                    </div>



                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h4>Email</h4>
                            <p><a href="mailto:bbspgl@esdm.go.id">bbspgl@esdm.go.id</a></p>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h3 class="info-title">Jam Operasional</h3>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h4>Senin - Kamis</h4>
                            <p>08:00 - 16:00 WIB</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h4>Jumat</h4>
                            <p>08:00 - 16:30 WIB</p>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h3 class="info-title">Media Sosial</h3>
                    <div style="display: flex; gap: 15px; margin-top: 15px;">
                        <a href="https://www.facebook.com/people/Badan-Geologi/100068349101047/" target="_blank" rel="noopener noreferrer"
                            style="color: #3b5998; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                        <a href="https://twitter.com/badangeologi_" target="_blank" rel="noopener noreferrer"
                            style="color: #1da1f2; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/geologi_kelautan/" target="_blank" rel="noopener noreferrer"
                            style="color: #e1306c; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@BadanGeologiBG" target="_blank" rel="noopener noreferrer"
                            style="color: #ff0000; font-size: 1.5rem;"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Contact Form -->
            <div class="contact-form-wrapper">
                <div class="form-header">
                    <h2>Kirim Pesan</h2>
                    <p>Punya pertanyaan, saran, atau kritik? Silakan isi formulir di bawah ini. Kami akan membalas pesan
                        Anda melalui email secepatnya.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('kontak.submit') }}" method="POST" id="contactForm">
                    @csrf

                    <div class="form-group">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap"
                            name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Contoh: Budi Santoso" required>
                        @error('nama_lengkap')
                            <div style="color: var(--error-color); font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email <span class="required">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="Contoh: email@instansi.com" required>
                        @error('email')
                            <div style="color: var(--error-color); font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subjek" class="form-label">Subjek Pesan <span class="required">*</span></label>
                        <select class="form-control @error('subjek') is-invalid @enderror" id="subjek" name="subjek"
                            required>
                            <option value="" disabled {{ old('subjek') ? '' : 'selected' }}>Pilih Topik Pesan</option>
                            <option value="Permintaan Data" {{ old('subjek') == 'Permintaan Data' ? 'selected' : '' }}>
                                Permintaan Data Survei</option>
                            <option value="Kerjasama" {{ old('subjek') == 'Kerjasama' ? 'selected' : '' }}>Penawaran
                                Kerjasama</option>
                            <option value="Layanan Publik" {{ old('subjek') == 'Layanan Publik' ? 'selected' : '' }}>
                                Layanan Publik</option>
                            <option value="Saran & Kritik" {{ old('subjek') == 'Saran & Kritik' ? 'selected' : '' }}>Saran
                                & Kritik</option>
                            <option value="Lainnya" {{ old('subjek') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('subjek')
                            <div style="color: var(--error-color); font-size: 0.85rem; margin-top: 0.3rem;">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pesan" class="form-label">Isi Pesan <span class="required">*</span></label>
                        <textarea class="form-control @error('pesan') is-invalid @enderror" id="pesan" name="pesan"
                            placeholder="Tuliskan detail pesan, pertanyaan, atau masukan Anda di sini..." required>{{ old('pesan') }}</textarea>
                        @error('pesan')
                            <div style="color: var(--error-color); font-size: 0.85rem; margin-top: 0.3rem;">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn" id="submitButton">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Simple submit animation
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            var btn = document.getElementById('submitButton');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            btn.disabled = true;
            btn.style.opacity = '0.7';
        });
    </script>
@endpush
