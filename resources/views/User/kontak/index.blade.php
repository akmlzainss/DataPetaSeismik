@extends('layouts.app')

@section('title', 'Kontak Kami - BBSPGL')

@push('styles')
    <style>
        /* Kontak Page Styles - Professional & Clean */
        :root {
            --primary-blue: #003366;
            --primary-light: #005599;
            --accent-yellow: #FFD700;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --border-color: #e9ecef;
            --success-color: #28a745;
            --error-color: #dc3545;
        }

        .contact-page {
            padding-bottom: 4rem;
        }

        /* Hero Section */
        .contact-hero {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            /* Deep Ocean Theme */
            padding: 4rem 1rem;
            text-align: center;
            color: white;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .contact-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 237, 0, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .contact-hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .contact-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .contact-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Main Content Grid */
        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 3rem;
        }

        /* Info Card (Left Side) */
        .contact-info-card {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .info-section {
            margin-bottom: 2.5rem;
        }

        .info-section:last-child {
            margin-bottom: 0;
        }

        .info-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--accent-yellow);
            display: inline-block;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.2rem;
            color: var(--text-dark);
        }

        .info-icon {
            background: rgba(0, 51, 102, 0.08);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            margin-right: 1rem;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .info-content h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 0.3rem;
            color: var(--primary-blue);
        }

        .info-content p {
            margin: 0;
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .info-content a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .info-content a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        /* Contact Form (Right Side) */
        .contact-form-wrapper {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-size: 1.8rem;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .form-header p {
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .required {
            color: var(--error-color);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            outline: 0;
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        .submit-btn {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            width: 100%;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .submit-btn:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.2);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .contact-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .contact-info-card {
                position: static;
                order: 2;
            }

            .contact-form-wrapper {
                order: 1;
            }
        }

        @media (max-width: 576px) {
            .contact-hero {
                padding: 3rem 1rem;
            }

            .contact-title {
                font-size: 2rem;
            }

            .contact-info-card,
            .contact-form-wrapper {
                padding: 1.5rem;
            }
        }
    </style>
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
                        <a href="https://www.facebook.com/people/Badan-Geologi/100068349101047/" target="_blank"
                            style="color: #3b5998; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                        <a href="https://twitter.com/badangeologi_" target="_blank"
                            style="color: #1da1f2; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/geologi_kelautan/" target="_blank"
                            style="color: #e1306c; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@BadanGeologiBG" target="_blank"
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
                        <label for="nama" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso" required>
                        @error('nama')
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
