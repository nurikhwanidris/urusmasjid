<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .mosque-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .mosque-address {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        .event-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .status-badges {
            margin-bottom: 20px;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 5px;
        }
        .badge-blue {
            background-color: #e6f2ff;
            color: #0066cc;
        }
        .badge-green {
            background-color: #e6ffee;
            color: #009933;
        }
        .badge-yellow {
            background-color: #ffffcc;
            color: #cc9900;
        }
        .badge-red {
            background-color: #ffe6e6;
            color: #cc0000;
        }
        .badge-gray {
            background-color: #f2f2f2;
            color: #666666;
        }
        .qr-section {
            text-align: center;
            margin: 30px 0;
        }
        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }
        .qr-url {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
            word-break: break-all;
        }
        /* Replace CSS grid with table layout for better compatibility */
        .details-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .details-column {
            width: 48%;
            float: left;
        }
        .details-column:first-child {
            margin-right: 4%;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        .details-section {
            margin-bottom: 20px;
        }
        .details-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .details-item {
            margin-bottom: 10px;
        }
        .details-label {
            font-weight: bold;
            color: #555;
        }
        .description-section {
            margin-bottom: 30px;
            clear: both;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            clear: both;
        }
        @page {
            margin: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="mosque-name">{{ $mosque->name }}</div>
            <div class="mosque-address">{{ $mosque->street_address }}, {{ $mosque->city }}, {{ $mosque->state }}</div>
            <h1 class="event-title">{{ $event->title }}</h1>
        </div>

        <!-- Status Badges -->
        <div class="status-badges">
            @php
                $statusClass = '';
                switch($eventStatus) {
                    case 'Dibatalkan':
                        $statusClass = 'badge-red';
                        break;
                    case 'Ditangguhkan':
                        $statusClass = 'badge-yellow';
                        break;
                    case 'Selesai':
                    case 'Tamat':
                        $statusClass = 'badge-gray';
                        break;
                    case 'Sedang Berlangsung':
                        $statusClass = 'badge-green';
                        break;
                    default:
                        $statusClass = 'badge-blue';
                }

                $categoryClass = '';
                switch($event->category) {
                    case 'religious':
                        $categoryClass = 'badge-blue';
                        break;
                    case 'educational':
                        $categoryClass = 'badge-green';
                        break;
                    case 'community':
                        $categoryClass = 'badge-yellow';
                        break;
                    case 'charity':
                        $categoryClass = 'badge-red';
                        break;
                    default:
                        $categoryClass = 'badge-gray';
                }
            @endphp
            <span class="badge {{ $statusClass }}">{{ $eventStatus }}</span>
            @if($event->category)
                <span class="badge {{ $categoryClass }}">{{ $categoryName }}</span>
            @endif
        </div>

        <!-- QR Code Section (if registration is required) -->
        @if($event->registration_required)
            <div class="qr-section">
                <h2>QR Code Pendaftaran</h2>
                <div class="qr-code">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
                </div>
                <p>Imbas QR code ini untuk mendaftar ke acara ini</p>
                <div class="qr-url">{{ $registrationUrl }}</div>
            </div>
        @endif

        <!-- Event Details -->
        <div class="details-grid clearfix">
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
                <h3 class="details-title">Penerangan</h3>
                <p>{{ $event->description }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Dicetak pada {{ $currentDate }}</p>
        </div>
    </div>
</body>
</html>
