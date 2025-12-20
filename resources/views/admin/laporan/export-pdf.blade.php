<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Survei Seismik</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
        }
        body {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #003366;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #003366;
            font-size: 18px;
            margin: 0 0 5px 0;
        }
        .header h2 {
            color: #666;
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }
        .meta {
            margin-bottom: 20px;
            color: #666;
        }
        .meta span {
            margin-right: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #003366;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-2d { background: #e3f2fd; color: #1565c0; }
        .badge-3d { background: #e8f5e9; color: #2e7d32; }
        .badge-hr { background: #fff3e0; color: #ef6c00; }
        .badge-lainnya { background: #f3e5f5; color: #7b1fa2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA SURVEI SEISMIK</h1>
        <h2>Balai Besar Survei dan Pemetaan Geologi Kelautan</h2>
    </div>

    <div class="meta">
        <span><strong>Tanggal Export:</strong> {{ $tanggalExport }}</span>
        @if($tahun)<span><strong>Tahun:</strong> {{ $tahun }}</span>@endif
        @if($bulan)<span><strong>Bulan:</strong> {{ $bulan }}</span>@endif
        @if($tipe)<span><strong>Tipe:</strong> {{ $tipe }}</span>@endif
        <span><strong>Total Data:</strong> {{ count($surveiTerbaru) }} survei</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 200px;">Judul Survei</th>
                <th style="width: 50px;">Tahun</th>
                <th style="width: 60px;">Tipe</th>
                <th style="width: 150px;">Wilayah</th>
                <th style="width: 100px;">Ketua Tim</th>
                <th style="width: 100px;">Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surveiTerbaru as $index => $survei)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $survei->judul }}</td>
                <td>{{ $survei->tahun }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($survei->tipe) }}">
                        {{ $survei->tipe }}
                    </span>
                </td>
                <td>{{ $survei->wilayah }}</td>
                <td>{{ $survei->ketua_tim ?? '-' }}</td>
                <td>{{ $survei->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #666;">
                    Tidak ada data survei ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi Data Peta Seismik BBSPGL</p>
        <p>© {{ date('Y') }} Balai Besar Survei dan Pemetaan Geologi Kelautan</p>
    </div>
</body>
</html>
