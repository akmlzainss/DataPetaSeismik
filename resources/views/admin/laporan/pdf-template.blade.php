<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Survei Seismik</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #003366;
            padding-bottom: 20px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin-right: 20px;
        }

        .company-info {
            text-align: left;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 3px;
        }

        .company-dept {
            font-size: 12px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 2px;
        }

        .company-address {
            font-size: 10px;
            color: #666;
            margin-bottom: 1px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #003366;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
        }

        .report-filter {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }

        .stats-section {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .stats-title {
            font-size: 12px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #003366;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .tipe-distribution {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }

        .tipe-item {
            text-align: center;
            flex: 1;
        }

        .tipe-value {
            font-size: 14px;
            font-weight: bold;
            color: #003366;
        }

        .tipe-label {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }

        .data-table th {
            background: #003366;
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #003366;
        }

        .data-table td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .data-table tr:hover {
            background: #e3f2fd;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-2d {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-3d {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .badge-hr {
            background: #fff3e0;
            color: #f57c00;
        }

        .badge-lainnya {
            background: #e8f5e9;
            color: #388e3c;
        }

        .status-ada {
            color: #28a745;
            font-weight: bold;
        }

        .status-belum {
            color: #dc3545;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    <div class="header">
        <div class="logo-section">
            @if (file_exists(public_path('storage/logo-esdm2.png')))
                <img src="{{ public_path('storage/logo-esdm2.png') }}" alt="Logo ESDM" class="logo">
            @endif
            <div class="company-info">
                <div class="company-name">KEMENTERIAN ENERGI DAN SUMBER DAYA MINERAL</div>
                <div class="company-dept">BADAN GEOLOGI</div>
                <div class="company-dept">BALAI BESAR SURVEI DAN PEMETAAN GEOLOGI</div>
                <div class="company-address">Jl. Diponegoro No. 57 Bandung 40122</div>
                <div class="company-address">Telp. (022) 7272606, Fax. (022) 7206965</div>
            </div>
        </div>

        <div class="report-title">Laporan Data Survei Seismik</div>
        <div class="report-filter">{{ $filterText }}</div>
    </div>

    {{-- STATISTIK --}}
    <div class="stats-section">
        <div class="stats-title">Ringkasan Statistik</div>

        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ number_format($totalSurvei) }}</div>
                <div class="stat-label">Total Survei</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($surveiDenganGrid) }}</div>
                <div class="stat-label">Dengan Grid</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">
                    {{ $totalSurvei > 0 ? number_format(($surveiDenganGrid / $totalSurvei) * 100, 1) : 0 }}%</div>
                <div class="stat-label">Persentase Grid</div>
            </div>
        </div>

        <div class="stats-title">Distribusi Per Tipe Survei</div>
        <div class="tipe-distribution">
            @foreach ($surveiPerTipe as $tipe)
                <div class="tipe-item">
                    <div class="tipe-value">{{ number_format($tipe->total) }}</div>
                    <div class="tipe-label">{{ $tipe->tipe }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- DATA TABLE --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Tanggal Upload</th>
                <th style="width: 25%;">Judul Survei</th>
                <th style="width: 8%;">Tipe</th>
                <th style="width: 8%;">Tahun</th>
                <th style="width: 25%;">Wilayah</th>
                <th style="width: 12%;">Status Grid</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surveiData as $index => $survei)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $survei->created_at->format('d/m/Y') }}</td>
                    <td>{{ $survei->judul }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ strtolower($survei->tipe) }}">{{ $survei->tipe }}</span>
                    </td>
                    <td class="text-center">{{ $survei->tahun }}</td>
                    <td>{{ Str::limit($survei->wilayah, 40) }}</td>
                    <td class="text-center">
                        @if ($survei->gridKotak->count() > 0)
                            <span class="status-ada">✓ Grid {{ $survei->gridKotak->first()->nomor_kotak }}</span>
                        @else
                            <span class="status-belum">✗ Belum</span>
                        @endif
                    </td>
                </tr>

                {{-- Page break setiap 25 data --}}
                @if (($index + 1) % 25 == 0 && !$loop->last)
        </tbody>
    </table>
    <div class="page-break"></div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Tanggal Upload</th>
                <th style="width: 25%;">Judul Survei</th>
                <th style="width: 8%;">Tipe</th>
                <th style="width: 8%;">Tahun</th>
                <th style="width: 25%;">Wilayah</th>
                <th style="width: 12%;">Status Grid</th>
            </tr>
        </thead>
        <tbody>
            @endif
        @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #999;">
                    Tidak ada data survei untuk filter yang dipilih
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <div>
            <strong>Dicetak pada:</strong> {{ $tanggalCetak }}
        </div>
        <div>
            <strong>Balai Besar Survei dan Pemetaan Geologi</strong>
        </div>
    </div>

    {{-- Catatan jika data terpotong --}}
    @if ($surveiData->count() >= 100)
        <div
            style="margin-top: 15px; padding: 10px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; font-size: 10px; color: #856404;">
            <strong>Catatan:</strong> Laporan PDF ini menampilkan maksimal 100 data terbaru. Untuk data lengkap, silakan
            gunakan export Excel.
        </div>
    @endif
</body>

</html>
