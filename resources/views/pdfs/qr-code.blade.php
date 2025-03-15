<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kod QR Pendaftaran Kariah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            border-bottom: 1px solid #e5e7eb;
            padding: 16px;
            text-align: center;
        }
        .title {
            color: #059669;
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        .subtitle {
            color: #4b5563;
            margin-top: 4px;
            font-size: 14px;
        }
        .content {
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .qr-container {
            border: 3px solid #059669;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
            display: inline-block;
            margin-left: auto;
            margin-right: auto;
        }
        .qr-code {
            width: 250px;
            height: 250px;
            display: block;
        }
        .instructions {
            text-align: center;
            margin-bottom: 16px;
        }
        .instructions h3 {
            color: #1f2937;
            font-size: 16px;
            margin: 0 0 8px 0;
            font-weight: 700;
        }
        .instructions ol {
            color: #4b5563;
            text-align: left;
            margin: 0;
            padding-left: 20px;
            font-size: 14px;
        }
        .mosque-details {
            background: #f9fafb;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            width: 100%;
            font-size: 14px;
        }
        .mosque-details p {
            margin: 2px 0;
        }
        .mosque-name {
            color: #1f2937;
            font-weight: 500;
        }
        .mosque-contact {
            color: #4b5563;
        }
        .footer {
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            padding: 12px;
            text-align: center;
            color: #4b5563;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="title">{{ $mosque->name }}</h1>
            <p class="subtitle">Pendaftaran Ahli Kariah</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- QR Code -->
            <div class="qr-container">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" class="qr-code" alt="QR Code Pendaftaran">
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h3>Cara Pendaftaran:</h3>
                <ol>
                    <li>Imbas kod QR menggunakan telefon pintar anda</li>
                    <li>Isi maklumat yang diperlukan</li>
                    <li>Hantar borang pendaftaran</li>
                </ol>
            </div>

            <!-- Mosque Details -->
            <div class="mosque-details">
                <p class="mosque-name">{{ $mosque->street_address }}</p>
                @if($mosque->address_line_2)
                    <p class="mosque-name">{{ $mosque->address_line_2 }}</p>
                @endif
                <p class="mosque-name">{{ $mosque->postal_code }} {{ $mosque->city }}</p>
                <p class="mosque-name">{{ $mosque->state }}</p>
                <p class="mosque-contact">{{ $mosque->contact_number }}</p>
                <p class="mosque-contact">{{ $mosque->email }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Sistem Pengurusan Masjid & Surau</p>
        </div>
    </div>
</body>
</html>
