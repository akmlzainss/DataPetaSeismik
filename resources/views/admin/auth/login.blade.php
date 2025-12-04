{{-- resources/views/admin/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - BBSPGL</title>
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <!-- Logo Section - Konsisten dengan Sidebar -->
        <div class="logo-section">
            <div class="logo-content">
                <img src="{{ asset('images/logo-bbspgl.png') }}" alt="Logo BBSPGL">
                <div class="logo-text">
                    <span>Sistem Informasi</span>
                    <span>Hasil Survei Seismik</span>
                </div>
            </div>
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
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        placeholder="admin@bbspgl.esdm.go.id" 
                        required 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="kata_sandi">Kata Sandi</label>
                    <input 
                        type="password" 
                        name="kata_sandi" 
                        id="kata_sandi" 
                        placeholder="Masukkan kata sandi" 
                        required
                    >
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