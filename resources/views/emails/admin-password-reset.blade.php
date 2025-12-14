<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BBSPGL</title>
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

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
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

        .reset-button {
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

        .reset-button:hover {
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

            <h1>Reset Password Administrator</h1>
            <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.9;">Sistem Informasi Survei Seismik BBSPGL</p>
        </div>

        <div class="content">
            <div class="greeting">
                Halo, {{ $admin->nama }}
            </div>

            <div class="message">
                <p>Kami menerima permintaan untuk mereset password akun administrator Anda di Sistem Informasi Survei
                    Seismik BBSPGL.</p>

                <p>Untuk melanjutkan proses reset password, silakan klik tombol di bawah ini:</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Reset Password Sekarang
                </a>
            </div>

            <div class="security-notice">
                <h4>⚠️ Penting untuk Keamanan</h4>
                <p>• Link ini hanya berlaku selama <strong>60 menit</strong> setelah email ini dikirim</p>
                <p>• Jika Anda tidak meminta reset password, abaikan email ini</p>
                <p>• Jangan bagikan link ini kepada siapa pun</p>
            </div>

            <div class="alternative-link">
                <strong>Tidak bisa mengklik tombol di atas?</strong><br>
                Salin dan tempel URL berikut ke browser Anda:<br>
                <span style="word-break: break-all; color: #003366;">{{ $resetUrl }}</span>
            </div>

            <div class="message">
                <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, silakan hubungi administrator sistem.</p>

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
