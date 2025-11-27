<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Admin</title>
    <!-- Ganti dengan link ke file CSS Anda atau gunakan Tailwind/Bootstrap -->
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .register-container { background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); width: 100%; max-width: 450px; }
        h2 { text-align: center; color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; background-color: #28a745; color: white; padding: 14px 20px; margin-top: 10px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s; }
        button:hover { background-color: #1e7e34; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Pendaftaran Akun Administrator</h2>

        <form method="POST" action="{{ url('/register') }}">
            @csrf

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required autofocus>
                @error('nama')
                    <div class="alert-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="alert-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="kata_sandi">Kata Sandi</label>
                <input type="password" id="kata_sandi" name="kata_sandi" required>
                @error('kata_sandi')
                    <div class="alert-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="kata_sandi_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" id="kata_sandi_confirmation" name="kata_sandi_confirmation" required>
            </div>

            <button type="submit">Daftar</button>
            <p style="text-align: center; margin-top: 20px;">
                Sudah punya akun? <a href="{{ route('admin.login') }}">Masuk di sini</a>
            </p>
        </form>
    </div>
</body>
</html>