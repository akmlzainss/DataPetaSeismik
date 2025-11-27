{{-- resources/views/admin/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Informasi Geologi</title>

    {{-- Load CSS dari file terpisah --}}
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-circle">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <h1>Sistem Informasi Geologi</h1>
            <p>Kementerian ESDM - BBSPGL</p>
        </div>

        <!-- Login Container -->
        <div class="login-container">
            <div class="login-header">
                <h2>Login Administrator</h2>
                <p>Masukkan kredensial Anda untuk melanjutkan</p>
            </div>

            <!-- Error Alert -->
            @if($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                               placeholder="admin@bbspgl.esdm.go.id" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="kata_sandi">Kata Sandi</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        <input type="password" name="kata_sandi" id="kata_sandi" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="remember-group">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit">Masuk Sekarang</button>
            </form>

            <div class="login-footer">
                <p>© {{ date('Y') }} BBSPGL - Kementerian ESDM</p>
            </div>
        </div>
    </div>
</body>
</html>