<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak Baru</title>
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
            padding: 20px;
            text-align: center;
            border-bottom: 4px solid #FFD700;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px;
        }

        .info-group {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            letter-spacing: 1px;
            margin-bottom: 5px;
            display: block;
        }

        .info-value {
            font-size: 16px;
            color: #003366;
            font-weight: 500;
        }

        .message-box {
            background-color: #f8f9fa;
            border-left: 4px solid #FFD700;
            padding: 15px;
            margin-top: 10px;
            border-radius: 0 4px 4px 0;
            font-style: italic;
            color: #444;
        }

        .footer {
            background-color: #f4f7f6;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #003366;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Pesan Kontak Baru</h1>
            <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.9;">Dari Website Sistem Informasi Survei Seismik</p>
        </div>

        <div class="content">
            <p>Halo Admin,</p>
            <p>Anda telah menerima pesan baru melalui formulir kontak website. Berikut adalah detail pesannya:</p>

            <div class="info-group">
                <span class="info-label">Pengirim</span>
                <div class="info-value">{{ $data['nama_lengkap'] }}</div>
                <div style="font-size: 14px; color: #666; margin-top: 2px;">
                    <a href="mailto:{{ $data['email'] }}"
                        style="color: #003366; text-decoration: none;">{{ $data['email'] }}</a>
                </div>
            </div>

            <div class="info-group">
                <span class="info-label">Subjek</span>
                <div class="info-value">{{ $data['subjek'] }}</div>
            </div>

            <div class="info-group" style="border-bottom: none;">
                <span class="info-label">Isi Pesan</span>
                <div class="message-box">
                    "{{ nl2br(e($data['pesan'])) }}"
                </div>
            </div>

            <div style="text-align: center;">
                <a href="mailto:{{ $data['email'] }}?subject=Re: {{ $data['subjek'] }}" class="button">Balas Email</a>
            </div>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh Sistem Informasi Survei Seismik BBSPGL.</p>
            <p>&copy; {{ date('Y') }} Balai Besar Survei dan Pemetaan Geologi Kelautan.</p>
        </div>
    </div>
</body>

</html>
