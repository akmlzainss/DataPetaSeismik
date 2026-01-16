<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Pegawai - BBSPGL</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #003366;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #FFD700;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #003366;
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .verify-button {
            display: inline-block;
            background-color: #003366;
            color: #ffffff !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }

        .verify-button:hover {
            background-color: #002244;
        }

        .security-notice {
            background-color: #fff3cd;
            border-left: 4px solid #FFD700;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 4px 4px 0;
        }

        .security-notice h4 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .security-notice p {
            color: #856404;
            margin: 5px 0;
            font-size: 14px;
        }

        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #003366;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }

        .info-box strong {
            color: #003366;
        }

        .alternative-link {
            background-color: #f8f9fa;
            border-left: 4px solid #FFD700;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
            font-size: 14px;
            color: #666;
        }

        .alternative-link strong {
            color: #003366;
        }

        .footer {
            background-color: #f4f7f6;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Verifikasi Email Pegawai ESDM</h1>
            <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.9;">Sistem Informasi Survei Seismik BBSPGL</p>
        </div>

        <div class="content">
            <div class="greeting">
                Halo, {{ $pegawai->nama }}
            </div>

            <div class="message">
                <p>Terima kasih telah mendaftar sebagai <strong>Pegawai ESDM ESDM</strong> di Sistem Informasi
                    Survei Seismik BBSPGL.</p>

                <p>Untuk mengaktifkan akun Anda dan mendapatkan akses ke file scan asli survei seismik, silakan
                    verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    ✓ Verifikasi Email Sekarang
                </a>
            </div>

            <div class="info-box">
                <strong>📧 Email Terdaftar:</strong> {{ $pegawai->email }}<br>
                @if($pegawai->nip)
                    <strong>🆔 NIP:</strong> {{ $pegawai->nip }}<br>
                @endif
                @if($pegawai->jabatan)
                    <strong>💼 Jabatan:</strong> {{ $pegawai->jabatan }}
                @endif
            </div>

            <div class="security-notice">
                <h4>⚠️ Penting untuk Keamanan</h4>
                <p>• Link verifikasi ini hanya berlaku selama <strong>1 jam</strong> setelah email ini dikirim</p>
                <p>• Jika Anda tidak mendaftar akun ini, abaikan email ini</p>
                <p>• Jangan bagikan link ini kepada siapa pun</p>
            </div>

            <div class="alternative-link">
                <strong>Tidak bisa mengklik tombol di atas?</strong><br>
                Salin dan tempel URL berikut ke browser Anda:<br>
                <span style="word-break: break-all; color: #003366;">{{ $verificationUrl }}</span>
            </div>

            <div class="message">
                <p><strong>Email tidak diterima?</strong><br>
                    Jika Anda tidak menerima email verifikasi atau link sudah kadaluarsa, silakan hubungi
                    administrator sistem untuk approval manual.</p>

                <p>Setelah email Anda terverifikasi, Anda dapat:</p>
                <ul>
                    <li>Login ke sistem dengan email ESDM Anda</li>
                    <li>Mengakses dan mengunduh file scan asli peta seismik</li>
                    <li>Melihat data survei lengkap</li>
                </ul>

                <p>Terima kasih,<br>
                    <strong>Tim BBSPGL</strong>
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Balai Besar Survei dan Pemetaan Geologi</strong></p>
            <p>Kementerian Energi dan Sumber Daya Mineral</p>
            <p>Jl. Diponegoro No. 57 Bandung 40122</p>
            <p style="margin-top: 15px;">© {{ date('Y') }} BBSPGL - Email otomatis, mohon tidak membalas</p>
        </div>
    </div>
</body>

</html>

