<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan - BBSPGL</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            color: white;
            max-width: 600px;
            padding: 2rem;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .error-message {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .error-description {
            font-size: 1rem;
            margin-bottom: 2rem;
            opacity: 0.8;
        }

        .back-button {
            display: inline-block;
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-message">Terjadi Kesalahan Server</div>
        <div class="error-description">Mohon maaf, terjadi kesalahan pada sistem. Tim teknis kami sedang menangani
            masalah ini.</div>
        <a href="{{ route('beranda') }}" class="back-button">Kembali ke Beranda</a>
    </div>
</body>

</html>
