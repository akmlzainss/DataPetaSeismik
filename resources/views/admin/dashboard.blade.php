{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - BBSPGL')

@section('content')
<div class="page-header">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <strong>{{ Auth::guard('admin')->user()->nama ?? 'Admin' }}</strong></p>
</div>

{{-- ========== STATISTIK UTAMA (4 CARDS) ========== --}}
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background: #e3f2fd;">
            <svg viewBox="0 0 24 24" style="fill: #1976d2;">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>Total Data Survei</h3>
            <div class="stat-value">{{ number_format($totalSurvei ?? 0) }}</div>
            <div class="stat-label">Data survei seismik nasional</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fff3e0;">
            <svg viewBox="0 0 24 24" style="fill: #f57c00;">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>Lokasi Marker</h3>
            <div class="stat-value">{{ number_format($totalMarker ?? 0) }}</div>
            <div class="stat-label">
                <span style="color: {{ $persentaseMarker >= 75 ? '#28a745' : ($persentaseMarker >= 50 ? '#ffc107' : '#dc3545') }}; font-weight: 700;">
                    {{ $persentaseMarker }}%
                </span> dari total survei
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #f3e5f5;">
            <svg viewBox="0 0 24 24" style="fill: #7b1fa2;">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>Pengguna Admin</h3>
            <div class="stat-value">{{ number_format($totalAdmin ?? 0) }}</div>
            <div class="stat-label">Admin terdaftar & aktif</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #e8f5e9;">
            <svg viewBox="0 0 24 24" style="fill: #388e3c;">
                <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>Survei Bulan Ini</h3>
            <div class="stat-value">{{ number_format($surveiBulanIni ?? 0) }}</div>
            <div class="stat-label">
                <span style="color: {{ $pertumbuhanBulanan >= 0 ? '#28a745' : '#dc3545' }}; font-weight: 600;">
                    {{ $pertumbuhanBulanan >= 0 ? '↑' : '↓' }} {{ abs($pertumbuhanBulanan) }}%
                </span> vs bulan lalu
            </div>
        </div>
    </div>
</div>

{{-- ========== TREND 6 BULAN TERAKHIR (CHART SIMPLE) ========== --}}
<div class="dashboard-card">
    <div class="card-header">
        <h3>Trend Upload Survei (6 Bulan Terakhir)</h3>
        <span class="card-subtitle">Grafik perkembangan data survei</span>
    </div>
    <div class="card-body">
        <div style="display: flex; align-items: flex-end; justify-content: space-around; height: 200px; gap: 12px; padding: 20px 0;">
            @foreach($trendBulanan as $data)
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                    <div style="font-weight: 700; font-size: 18px; color: #003366;">{{ $data['jumlah'] }}</div>
                    <div style="
                        width: 100%; 
                        background: linear-gradient(180deg, #003366 0%, #0066cc 100%); 
                        border-radius: 6px 6px 0 0;
                        height: {{ $data['jumlah'] > 0 ? ($data['jumlah'] / max(array_column($trendBulanan, 'jumlah'))) * 140 : 5 }}px;
                        min-height: 5px;
                        transition: all 0.3s ease;
                    "></div>
                    <div style="font-size: 12px; color: #666; font-weight: 600;">{{ $data['bulan'] }}</div>
                </div>
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
            <a href="{{ route('admin.laporan.index') }}" style="color: #003366; font-weight: 600; text-decoration: none;">
                Lihat Laporan Lengkap →
            </a>
        </div>
    </div>
</div>

{{-- ========== ROW 2 KOLOM ========== --}}
<div class="dashboard-row">
    {{-- STATISTIK PER TIPE --}}
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Ringkasan Per Tipe Survei</h3>
            <span class="card-subtitle">Distribusi data survei seismik</span>
        </div>
        <div class="card-body">
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Tipe Survei</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th style="text-align: right;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surveiPerTipe ?? [] as $tipe)
                    <tr>
                        <td>
                            <span class="badge badge-{{ strtolower($tipe->tipe) }}">{{ $tipe->tipe }}</span>
                        </td>
                        <td style="text-align: center; font-weight: 600;">{{ number_format($tipe->total) }}</td>
                        <td style="text-align: right;">
                            <strong>{{ number_format(($tipe->total / ($totalSurvei ?: 1)) * 100, 1) }}%</strong>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999; padding: 30px;">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOP 5 WILAYAH --}}
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Top 5 Wilayah Survei</h3>
            <span class="card-subtitle">Wilayah dengan survei terbanyak</span>
        </div>
        <div class="card-body">
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Wilayah</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topWilayah ?? [] as $index => $wilayah)
                    <tr>
                        <td style="width: 50px; font-weight: 700; color: #003366;">
                            #{{ $index + 1 }}
                        </td>
                        <td>{{ Str::limit($wilayah->wilayah, 35) }}</td>
                        <td style="text-align: right; font-weight: 600;">{{ number_format($wilayah->total) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999; padding: 30px;">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========== AKTIVITAS TERBARU (5 ITEMS) ========== --}}
<div class="dashboard-card">
    <div class="card-header">
        <h3>Aktivitas Terbaru</h3>
        <span class="card-subtitle">5 data survei terakhir yang diunggah</span>
    </div>
    <div class="card-body">
        <div class="activity-list">
            @forelse($surveiTerbaru as $survei)
            <div class="activity-item">
                <div class="activity-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                    </svg>
                </div>
                <div class="activity-content">
                    <div class="activity-title">{{ Str::limit($survei->judul, 50) }}</div>
                    <div class="activity-meta">
                        <span class="badge badge-sm badge-{{ strtolower($survei->tipe) }}">{{ $survei->tipe }}</span>
                        <span>{{ $survei->created_at->diffForHumans() }}</span>
                        <span>• {{ $survei->pengunggah->nama ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px; color: #999;">
                <p>Belum ada aktivitas</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ========== SURVEI PER TAHUN (3 TAHUN TERAKHIR) ========== --}}
<div class="dashboard-card">
    <div class="card-header">
        <h3>Ringkasan 3 Tahun Terakhir</h3>
        <span class="card-subtitle">Perbandingan data per tahun</span>
    </div>
    <div class="card-body">
        <table class="simple-table">
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
                @forelse($surveiPerTahun ?? [] as $tahun)
                <tr>
                    <td><strong>{{ $tahun->tahun }}</strong></td>
                    <td style="text-align: center;">{{ number_format($tahun->tipe_2d ?? 0) }}</td>
                    <td style="text-align: center;">{{ number_format($tahun->tipe_3d ?? 0) }}</td>
                    <td style="text-align: center;">{{ number_format($tahun->tipe_hr ?? 0) }}</td>
                    <td style="text-align: center;">{{ number_format($tahun->tipe_lainnya ?? 0) }}</td>
                    <td style="text-align: right; font-weight: 700; color: #003366;">{{ number_format($tahun->total) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 30px;">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
            <a href="{{ route('admin.laporan.index') }}" style="color: #003366; font-weight: 600; text-decoration: none;">
                Lihat Data 5 Tahun Terakhir →
            </a>
        </div>
    </div>
</div>

{{-- ========== QUICK ACTIONS ========== --}}
<div class="quick-actions">
    <h3>Aksi Cepat</h3>
    <div class="quick-actions-grid">
        <a href="{{ route('admin.data_survei.create') }}" class="quick-action-btn">
            <svg viewBox="0 0 24 24">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            <span>Tambah Survei</span>
        </a>
        <a href="{{ route('admin.data_survei.index') }}" class="quick-action-btn">
            <svg viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
            <span>Lihat Semua Data</span>
        </a>
        <a href="{{ route('admin.lokasi_marker.index') }}" class="quick-action-btn">
            <svg viewBox="0 0 24 24">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
            <span>Kelola Marker</span>
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="quick-action-btn">
            <svg viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
            <span>Lihat Laporan</span>
        </a>
    </div>
</div>
@endsection