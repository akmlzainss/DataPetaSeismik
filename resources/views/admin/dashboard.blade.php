{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - BBSPGL')

@section('content')
<div class="page-header">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <strong>{{ Auth::guard('admin')->user()->nama ?? 'Admin' }}</strong></p>
</div>

<!-- Statistik Utama -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background: #e3f2fd;">
            <svg viewBox="0 0 24 24" style="fill: #1976d2;">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>Total Data Survei</h3>
            <div class="stat-value">{{ $totalSurvei ?? 0 }}</div>
            <div class="stat-label">Data survei seismik</div>
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
            <div class="stat-value">{{ $totalMarker ?? 0 }}</div>
            <div class="stat-label">Titik marker aktif</div>
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
            <div class="stat-value">{{ $totalAdmin ?? 0 }}</div>
            <div class="stat-label">Admin terdaftar</div>
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
            <div class="stat-value">{{ $surveiBulanIni ?? 0 }}</div>
            <div class="stat-label">Data baru</div>
        </div>
    </div>
</div>

<!-- Row 2 Kolom -->
<div class="dashboard-row">
    <!-- Statistik Per Tipe -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Statistik Per Tipe Survei</h3>
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
                        <td style="text-align: center;">{{ $tipe->total }}</td>
                        <td style="text-align: right;">
                            <strong>{{ number_format(($tipe->total / ($totalSurvei ?: 1)) * 100, 1) }}%</strong>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999;">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Aktivitas Terbaru</h3>
            <span class="card-subtitle">10 data survei terakhir</span>
        </div>
        <div class="card-body">
            <div class="activity-list">
                @forelse($surveiTerbaru ?? [] as $survei)
                <div class="activity-item">
                    <div class="activity-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">{{ Str::limit($survei->judul, 40) }}</div>
                        <div class="activity-meta">
                            <span class="badge badge-sm badge-{{ strtolower($survei->tipe) }}">{{ $survei->tipe }}</span>
                            <span>{{ $survei->created_at->diffForHumans() }}</span>
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
</div>

<!-- Survei Per Tahun -->
<div class="dashboard-card">
    <div class="card-header">
        <h3>Distribusi Survei Per Tahun</h3>
        <span class="card-subtitle">5 tahun terakhir</span>
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
                    <td style="text-align: center;">{{ $tahun->tipe_2d ?? 0 }}</td>
                    <td style="text-align: center;">{{ $tahun->tipe_3d ?? 0 }}</td>
                    <td style="text-align: center;">{{ $tahun->tipe_hr ?? 0 }}</td>
                    <td style="text-align: center;">{{ $tahun->tipe_lainnya ?? 0 }}</td>
                    <td style="text-align: right;"><strong>{{ $tahun->total }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
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
        <a href="{{ route('admin.pengaturan.index') }}" class="quick-action-btn">
            <svg viewBox="0 0 24 24">
                <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
            </svg>
            <span>Pengaturan</span>
        </a>
    </div>
</div>
@endsection