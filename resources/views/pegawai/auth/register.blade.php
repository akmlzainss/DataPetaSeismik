{{-- resources/views/pegawai/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pegawai ESDM - BBSPGL</title>

    {{-- Favicon untuk semua platform --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/logo-esdm2.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/logo-esdm2.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('storage/logo-esdm2.png') }}">
    <meta name="msapplication-TileColor" content="#ffed00">
    <meta name="theme-color" content="#003366">

    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
</head>

<body>
    <div class="login-page">
        <div class="login-card" style="max-width: 1000px;">
            {{-- Left Side - Logo & Info --}}
            <div class="login-left">
                <div class="login-left-content">
                    <div class="logo-container">
                        <img src="{{ asset('storage/logo-esdm2.png') }}" alt="Logo ESDM">
                    </div>
                    <h1 class="brand-title">BBSPGL</h1>
                    <p class="brand-subtitle">Balai Besar Survei dan<br>Pemetaan Geologi</p>
                    <div class="brand-divider"></div>
                    <p class="brand-description">Daftar Pegawai ESDM ESDM</p>
                    <p class="brand-ministry">Kementerian Energi dan Sumber Daya Mineral</p>
                </div>
                <div class="login-left-footer">
                    <p>©{{ date('Y') }} BBSPGL - Kementerian ESDM</p>
                </div>
            </div>

            {{-- Right Side - Registration Form --}}
            <div class="login-right">
                <div class="login-form-container">
                    <div class="login-header">
                        <h2>Daftar Akun Pegawai ESDM</h2>
                        <p>Gunakan email @esdm.go.id untuk mendaftar</p>
                    </div>

                    {{-- Error Alert --}}
                    @if ($errors->any())
                        <div class="alert-error">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pegawai.register') }}" class="login-form">
                        @csrf

                        <div class="form-group">
                            <label for="nama">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                                Nama Lengkap
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                placeholder="Masukkan nama lengkap" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                                Email ESDM
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                placeholder="nama.anda@esdm.go.id" required>
                            <small style="color: #666; font-size: 13px; display: block; margin-top: 5px;">
                                Harus menggunakan email resmi @esdm.go.id
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="nip">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12z" />
                                    <path d="M6.5 11h2v2h-2zm0-3h2v2h-2zm5 3h2v2h-2zm0-3h2v2h-2zm5 3h2v2h-2zm0-3h2v2h-2z" />
                                </svg>
                                NIP (Opsional)
                            </label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                                placeholder="Nomor Induk Pegawai">
                        </div>

                        <div class="form-group">
                            <label for="jabatan">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z" />
                                </svg>
                                Jabatan (Opsional)
                            </label>
                            <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}"
                                placeholder="Jabatan Anda">
                        </div>

                        <div class="form-group">
                            <label for="kata_sandi">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                                Kata Sandi
                            </label>
                            <input type="password" name="kata_sandi" id="kata_sandi"
                                placeholder="Minimal 8 karakter" required>
                        </div>

                        <div class="form-group">
                            <label for="kata_sandi_confirmation">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" />
                                </svg>
                                Konfirmasi Kata Sandi
                            </label>
                            <input type="password" name="kata_sandi_confirmation" id="kata_sandi_confirmation"
                                placeholder="Ulangi kata sandi" required>
                        </div>

                        <button type="submit" class="btn-login">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                            Daftar Sekarang
                        </button>
                    </form>

                    <div class="login-help">
                        <p>
                            Sudah punya akun?
                            <a href="{{ route('pegawai.login') }}" style="color: #003366; text-decoration: none;">
                                Login di sini
                            </a>
                        </p>
                        <p style="margin-top: 10px; font-size: 13px; color: #666;">
                            Setelah mendaftar, Anda akan menerima email verifikasi di inbox @esdm.go.id Anda.
                            Link verifikasi berlaku 1 jam.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

