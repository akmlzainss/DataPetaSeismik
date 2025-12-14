{{-- resources/views/admin/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BBSPGL</title>

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
        <div class="login-card">
            {{-- Left Side - Logo & Info --}}
            <div class="login-left">
                <div class="login-left-content">
                    <div class="logo-container">
                        <img src="{{ asset('storage/logo-esdm2.png') }}" alt="Logo ESDM">
                    </div>
                    <h1 class="brand-title">BBSPGL</h1>
                    <p class="brand-subtitle">Balai Besar Survei dan<br>Pemetaan Geologi</p>
                    <div class="brand-divider"></div>
                    <p class="brand-description">Sistem Informasi Survei Seismik</p>
                    <p class="brand-ministry">Kementerian Energi dan Sumber Daya Mineral</p>
                </div>
                <div class="login-left-footer">
                    <p>© {{ date('Y') }} BBSPGL - Kementerian ESDM</p>
                </div>
            </div>

            {{-- Right Side - Reset Password Form --}}
            <div class="login-right">
                <div class="login-form-container">
                    <div class="login-header">
                        <h2>Reset Password</h2>
                        <p>Masukkan password baru untuk akun Anda</p>
                    </div>

                    {{-- Error Alert --}}
                    @if ($errors->any() || session('error'))
                        <div class="alert-error">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                            <span>{{ $errors->first() ?: session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.password.update') }}" class="login-form">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                                Alamat Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                placeholder="admin@bbspgl.esdm.go.id" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                                Password Baru
                            </label>
                            <input type="password" name="password" id="password"
                                placeholder="Masukkan password baru (min. 8 karakter)" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="Ulangi password baru" required>
                        </div>

                        <button type="submit" class="btn-login">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                            </svg>
                            Reset Password
                        </button>
                    </form>

                    <div class="login-help">
                        <p>
                            <a href="{{ route('admin.login') }}" style="color: #003366; text-decoration: none;">
                                ← Kembali ke Login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
