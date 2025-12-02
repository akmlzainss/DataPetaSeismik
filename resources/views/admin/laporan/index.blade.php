{{-- resources/views/admin/laporan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan & Statistik - Admin BBSPGL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-laporan.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1>Laporan & Statistik</h1>
    <p>Laporan komprehensif data survei seismik dan aktivitas sistem</p>
</div>

{{-- ========== FILTER SECTION ========== --}}
<div class="filter-section">
    <h3 style="font-size: 16px; font-weight: 700; color: #003366; margin-bottom: 4px;">Filter Laporan</h3>
    <p style="font-size: 13px; color: #666;">Filter data berdasarkan tahun, bulan, dan tipe survei</p>
    
    <form method="GET" action="{{ route('admin.laporan.index') }}">
        <div class="filter-grid">
            <div class="filter-item">
                <label>Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunTersedia as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-item">
                <label>Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="filter-item">
                <label>Tipe Survei</label>
                <select name="tipe" class="form-select">
                    <option value="">Semua Tipe</option>
                    @foreach($tipeSurvei as $t)
                        <option value="{{ $t }}" {{ $tipe == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-item" style="display: flex; align-items: flex-end;">
                <button type="submit" class="btn-filter" style="width: 100%;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="display: inline; margin-right: 6px;">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                    Terapkan Filter
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ========== INFO BANNER ========== --}}
@if($bulan || $tipe)
<div class="info-banner">
    <h3>Filter Aktif</h3>
    <p>
        Menampilkan data untuk 
        @if($bulan) <strong>{{ DateTime::createFromFormat('!m', $bulan)->format('F') }}</strong> @endif
        @if($tahun) <strong>{{ $tahun }}</strong> @endif
        @if($tipe) - Tipe: <strong>{{ $tipe }}</strong> @endif
        <a href="{{ route('admin.laporan.index') }}" style="color: #003366; text-decoration: underline; margin-left: 8px;">Reset Filter</a>
    </p>
</div>
@endif

{{-- ========== TWO COLUMN REPORTS ========== --}}
<div class="report-grid-2">
    {{-- LAPORAN PER TIPE SURVEI --}}
    <div class="report-card">
        <div class="report-card-header">
            <div>
                <div class="report-card-title">Distribusi Per Tipe Survei</div>
                <div class="report-card-subtitle">Breakdown berdasarkan jenis survei</div>
            </div>
            <div class="report-card-actions">
                <button class="btn-export" disabled>
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>
        <div class="report-card-body">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Tipe Survei</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th style="text-align: right;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalFiltered = $laporanPerTipe->sum('total'); @endphp
                    @forelse($laporanPerTipe as $item)
                    <tr>
                        <td>
                            <span class="badge-report {{ strtolower($item->tipe) == '2d' ? 'blue' : (strtolower($item->tipe) == '3d' ? 'purple' : (strtolower($item->tipe) == 'hr' ? 'orange' : 'teal')) }}">
                                {{ $item->tipe }}
                            </span>
                        </td>
                        <td style="text-align: center; font-weight: 600;">{{ number_format($item->total) }}</td>
                        <td style="text-align: right;">
                            <strong>{{ $totalFiltered > 0 ? number_format(($item->total / $totalFiltered) * 100, 1) : 0 }}%</strong>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $totalFiltered > 0 ? ($item->total / $totalFiltered) * 100 : 0 }}%;"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999; padding: 40px;">
                            Tidak ada data untuk filter yang dipilih
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($laporanPerTipe->count() > 0)
                <tfoot>
                    <tr class="summary-row">
                        <td><strong>Total</strong></td>
                        <td style="text-align: center;"><strong>{{ number_format($totalFiltered) }}</strong></td>
                        <td style="text-align: right;"><strong>100%</strong></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- LAPORAN PER WILAYAH (TOP 10) --}}
    <div class="report-card">
        <div class="report-card-header">
            <div>
                <div class="report-card-title">Top 10 Wilayah Survei</div>
                <div class="report-card-subtitle">Wilayah dengan jumlah survei terbanyak</div>
            </div>
            <div class="report-card-actions">
                <button class="btn-export" disabled>
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>
        <div class="report-card-body">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Wilayah</th>
                        <th style="text-align: right;">Jumlah Survei</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanPerWilayah as $index => $item)
                    <tr>
                        <td style="width: 50px; font-weight: 600; color: #003366;">{{ $index + 1 }}</td>
                        <td>{{ $item->wilayah }}</td>
                        <td style="text-align: right; font-weight: 600;">{{ number_format($item->total) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999; padding: 40px;">
                            Tidak ada data untuk filter yang dipilih
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========== LAPORAN PER TAHUN ========== --}}
<div class="report-card">
    <div class="report-card-header">
        <div>
            <div class="report-card-title">Distribusi Survei Per Tahun</div>
            <div class="report-card-subtitle">Data 5 tahun terakhir dengan breakdown per tipe</div>
        </div>
        <div class="report-card-actions">
            <button class="btn-export" disabled>
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                </svg>
                Export Excel
            </button>
        </div>
    </div>
    <div class="report-card-body">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th style="text-align: center;">2D</th>
                    <th style="text-align: center;">3D</th>
                    <th style="text-align: center;">HR</th>
                    <th style="text-align: center;">Lainnya</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporanPerTahun as $item)
                <tr>
                    <td><strong>{{ $item->tahun }}</strong></td>
                    <td style="text-align: center;">{{ number_format($item->tipe_2d) }}</td>
                    <td style="text-align: center;">{{ number_format($item->tipe_3d) }}</td>
                    <td style="text-align: center;">{{ number_format($item->tipe_hr) }}</td>
                    <td style="text-align: center;">{{ number_format($item->tipe_lainnya) }}</td>
                    <td style="text-align: right; font-weight: 700; color: #003366;">{{ number_format($item->total) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 40px;">
                        Belum ada data survei
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($laporanPerTahun->count() > 0)
            <tfoot>
                <tr class="summary-row">
                    <td><strong>Total Keseluruhan</strong></td>
                    <td style="text-align: center;"><strong>{{ number_format($laporanPerTahun->sum('tipe_2d')) }}</strong></td>
                    <td style="text-align: center;"><strong>{{ number_format($laporanPerTahun->sum('tipe_3d')) }}</strong></td>
                    <td style="text-align: center;"><strong>{{ number_format($laporanPerTahun->sum('tipe_hr')) }}</strong></td>
                    <td style="text-align: center;"><strong>{{ number_format($laporanPerTahun->sum('tipe_lainnya')) }}</strong></td>
                    <td style="text-align: right;"><strong>{{ number_format($laporanPerTahun->sum('total')) }}</strong></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ========== AKTIVITAS ADMIN ========== --}}
<div class="report-card">
    <div class="report-card-header">
        <div>
            <div class="report-card-title">Aktivitas Admin</div>
            <div class="report-card-subtitle">Total upload data survei per admin</div>
        </div>
        <div class="report-card-actions">
            <button class="btn-export" disabled>
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                </svg>
                Export
            </button>
        </div>
    </div>
    <div class="report-card-body">
        <table class="report-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Admin</th>
                    <th>Email</th>
                    <th style="text-align: right;">Total Upload</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aktivitasAdmin as $index => $admin)
                <tr>
                    <td style="width: 50px; font-weight: 600;">{{ $index + 1 }}</td>
                    <td><strong>{{ $admin->nama }}</strong></td>
                    <td>{{ $admin->email }}</td>
                    <td style="text-align: right; font-weight: 700; color: #003366;">{{ number_format($admin->total_upload) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999; padding: 40px;">
                        Belum ada data admin
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ========== SURVEI TERBARU ========== --}}
<div class="report-card">
    <div class="report-card-header">
        <div>
            <div class="report-card-title">10 Survei Terbaru</div>
            <div class="report-card-subtitle">Data survei yang baru diunggah</div>
        </div>
    </div>
    <div class="report-card-body">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Tanggal Upload</th>
                    <th>Judul Survei</th>
                    <th>Tipe</th>
                    <th>Wilayah</th>
                    <th style="text-align: center;">Marker</th>
                    <th>Diunggah Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surveiTerbaru as $survei)
                <tr>
                    <td style="white-space: nowrap;">{{ $survei->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ Str::limit($survei->judul, 40) }}</strong></td>
                    <td>
                        <span class="badge-report {{ strtolower($survei->tipe) == '2d' ? 'blue' : (strtolower($survei->tipe) == '3d' ? 'purple' : (strtolower($survei->tipe) == 'hr' ? 'orange' : 'teal')) }}">
                            {{ $survei->tipe }}
                        </span>
                    </td>
                    <td>{{ Str::limit($survei->wilayah, 30) }}</td>
                    <td style="text-align: center;">
                        @if($survei->lokasi)
                            <span style="color: #28a745; font-weight: 600;">✓ Ada</span>
                        @else
                            <span style="color: #999;">— Belum</span>
                        @endif
                    </td>
                    <td>{{ $survei->pengunggah->nama ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 40px;">
                        Tidak ada data survei untuk filter yang dipilih
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ========== STATISTIK MARKER ========== --}}
<div class="report-grid-2">
    <div class="report-card">
        <div class="report-card-header">
            <div>
                <div class="report-card-title">Status Lokasi Marker</div>
                <div class="report-card-subtitle">Survei dengan dan tanpa marker</div>
            </div>
        </div>
        <div class="report-card-body">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th style="text-align: right;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Dengan Marker</strong></td>
                        <td style="text-align: center; color: #28a745; font-weight: 700;">{{ number_format($surveiDenganMarker) }}</td>
                        <td style="text-align: right;">
                            <strong>{{ $totalSurvei > 0 ? number_format(($surveiDenganMarker / $totalSurvei) * 100, 1) : 0 }}%</strong>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $totalSurvei > 0 ? ($surveiDenganMarker / $totalSurvei) * 100 : 0 }}%; background: linear-gradient(90deg, #28a745 0%, #5cb85c 100%);"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tanpa Marker</strong></td>
                        <td style="text-align: center; color: #dc3545; font-weight: 700;">{{ number_format($surveiTanpaMarker) }}</td>
                        <td style="text-align: right;">
                            <strong>{{ $totalSurvei > 0 ? number_format(($surveiTanpaMarker / $totalSurvei) * 100, 1) : 0 }}%</strong>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $totalSurvei > 0 ? ($surveiTanpaMarker / $totalSurvei) * 100 : 0 }}%; background: linear-gradient(90deg, #dc3545 0%, #e74a3b 100%);"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="summary-row">
                        <td><strong>Total</strong></td>
                        <td style="text-align: center;"><strong>{{ number_format($totalSurvei) }}</strong></td>
                        <td style="text-align: right;"><strong>100%</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="report-card">
        <div class="report-card-header">
            <div>
                <div class="report-card-title">Grafik Trend</div>
                <div class="report-card-subtitle">Visualisasi data (Coming Soon)</div>
            </div>
        </div>
        <div class="report-card-body">
            <div class="chart-placeholder">
                <div class="chart-placeholder-content">
                    <svg viewBox="0 0 24 24">
                        <path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/>
                    </svg>
                    <h3>Chart & Visualisasi</h3>
                    <p>Fitur grafik interaktif sedang dalam pengembangan</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection