<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    <style>
        @page {
            margin: 15mm 10mm; /* Reduced margins */
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 10pt; /* Smaller base font size */
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }
        .mosque-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .mosque-address {
            font-size: 9pt;
            color: #666;
            margin-bottom: 10px;
        }
        .event-title {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .status-badges {
            margin-bottom: 15px;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
            margin-right: 5px;
        }
        .badge-blue { background-color: #e6f2ff; color: #0066cc; }
        .badge-green { background-color: #e6ffee; color: #009933; }
        .badge-yellow { background-color: #ffffcc; color: #cc9900; }
        .badge-red { background-color: #ffe6e6; color: #cc0000; }
        .badge-gray { background-color: #f2f2f2; color: #666666; }

        .qr-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .qr-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .qr-code {
            width: 150px;
            height: 150px;
            margin: 0 auto;
            padding: 10px;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        .qr-code img {
            width: 100%;
            height: auto;
        }
        .qr-caption {
            font-size: 8pt;
            color: #666;
            margin-top: 8px;
        }
        .qr-url {
            font-size: 7pt;
            color: #666;
            margin-top: 5px;
            word-break: break-all;
        }

        .details-grid {
            display: flex;
            width: 100%;
            margin-bottom: auto;
        }
        .details-column {
            flex: 1;
            padding-right: 15px;
        }
        .details-section {
            margin-bottom: auto;
        }
        .details-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
        }
        .details-item {
            margin-bottom: 5px;
            font-size: 9pt;
        }
        .details-label {
            font-weight: bold;
            color: #555;
        }
        .description-section {
            margin-bottom: 20px;
        }
        .description-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
        }
        .description-content {
            font-size: 9pt;
            white-space: pre-line;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            color: #999;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Mosque and Event Header -->
        <div class="header">
            <div class="mosque-name">{{ $mosque->name }}</div>
            <div class="mosque-address">{{ $mosque->street_address }}, {{ $mosque->city }}, {{ $mosque->state }}</div>
            <h1 class="event-title">{{ $event->title }}</h1>
        </div>

        <!-- Event Status and Category -->
        <div class="status-badges">
            @php
                $statusClass = '';
                switch($eventStatus) {
                    case 'Dibatalkan': $statusClass = 'badge-red'; break;
                    case 'Ditangguhkan': $statusClass = 'badge-yellow'; break;
                    case 'Selesai': case 'Tamat': $statusClass = 'badge-gray'; break;
                    case 'Sedang Berlangsung': $statusClass = 'badge-green'; break;
                    default: $statusClass = 'badge-blue';
                }

                $categoryClass = '';
                switch($event->category) {
                    case 'religious': $categoryClass = 'badge-blue'; break;
                    case 'educational': $categoryClass = 'badge-green'; break;
                    case 'community': $categoryClass = 'badge-yellow'; break;
                    case 'charity': $categoryClass = 'badge-red'; break;
                    default: $categoryClass = 'badge-gray';
                }
            @endphp
            <span class="badge {{ $statusClass }}">{{ $eventStatus }}</span>
            @if($event->category)
                <span class="badge {{ $categoryClass }}">{{ $categoryName }}</span>
            @endif
        </div>

        <!-- QR Code (if registration is required) -->
        @if($event->registration_required)
            <div class="qr-section">
                <h3 class="qr-title">QR Code Pendaftaran</h3>
                <div class="qr-code">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
                </div>
                <p class="qr-caption">Imbas QR code ini untuk mendaftar ke acara ini</p>
                <p class="qr-url">{{ $registrationUrl }}</p>
            </div>
        @endif

        <!-- Event Details -->
        <div class="details-grid">
            <!-- Left Column -->
            <div class="details-column">
                <div class="details-section">
                    <h3 class="details-title">Maklumat Acara</h3>
                    <div class="details-item">
                        <span class="details-label">Tarikh:</span>
                        {{ $startDate }}
                        @if($startDate != $endDate)
                            - {{ $endDate }}
                        @endif
                    </div>
                    @if($startTime)
                        <div class="details-item">
                            <span class="details-label">Masa:</span>
                            {{ $startTime }}
                            @if($endTime)
                                - {{ $endTime }}
                            @endif
                        </div>
                    @endif
                    @if($event->location)
                        <div class="details-item">
                            <span class="details-label">Lokasi:</span>
                            {{ $event->location }}
                        </div>
                    @endif
                    @if($event->address)
                        <div class="details-item">
                            <span class="details-label">Alamat:</span>
                            {{ $event->address }}
                        </div>
                    @endif
                    @if($event->is_online)
                        <div class="details-item">
                            <span class="details-label">Jenis Acara:</span>
                            Dalam Talian
                        </div>
                        @if($event->online_url)
                            <div class="details-item">
                                <span class="details-label">URL:</span>
                                {{ $event->online_url }}
                            </div>
                        @endif
                    @endif
                </div>

                @if($event->registration_required)
                    <div class="details-section">
                        <h3 class="details-title">Maklumat Pendaftaran</h3>
                        @if($event->registration_deadline)
                            <div class="details-item">
                                <span class="details-label">Tarikh Tutup Pendaftaran:</span>
                                {{ \Carbon\Carbon::parse($event->registration_deadline)->format('d F Y') }}
                            </div>
                        @endif
                        @if($event->max_participants)
                            <div class="details-item">
                                <span class="details-label">Bilangan Peserta Maksimum:</span>
                                {{ $event->max_participants }}
                            </div>
                            <div class="details-item">
                                <span class="details-label">Pendaftaran Semasa:</span>
                                {{ $registrationCount }} peserta
                            </div>
                            @if($remainingSlots !== null)
                                <div class="details-item">
                                    <span class="details-label">Slot Tersedia:</span>
                                    {{ $remainingSlots }}
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="details-column">
                @if($event->speaker)
                    <div class="details-section">
                        <h3 class="details-title">Penceramah / Pembentang</h3>
                        <div class="details-item">
                            <span class="details-label">Nama:</span>
                            {{ $event->speaker }}
                        </div>
                        @if($event->speaker_bio)
                            <div class="details-item">
                                <span class="details-label">Biodata:</span>
                                {{ $event->speaker_bio }}
                            </div>
                        @endif
                    </div>
                @endif

                <div class="details-section">
                    <h3 class="details-title">Hubungi</h3>
                    @if($event->contact_person)
                        <div class="details-item">
                            <span class="details-label">Pegawai Hubungan:</span>
                            {{ $event->contact_person }}
                        </div>
                    @endif
                    @if($event->contact_phone)
                        <div class="details-item">
                            <span class="details-label">Nombor Telefon:</span>
                            {{ $event->contact_phone }}
                        </div>
                    @endif
                    @if($event->contact_email)
                        <div class="details-item">
                            <span class="details-label">Emel:</span>
                            {{ $event->contact_email }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Event Description -->
        @if($event->description)
            <div class="description-section">
                <h3 class="description-title">Penerangan</h3>
                <div class="description-content">{{ $event->description }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Dicetak pada {{ $currentDate }}</p>
        </div>
    </div>
</body>
</html>