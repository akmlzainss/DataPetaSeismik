{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ time() }}">
@endpush

@push('scripts')
    {{-- Chart.js with fallback --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js CDN failed, using local fallback');
            document.write('<script src="{{ asset("assets/js/chart.min.js") }}"><\/script>');
        }
    </script>
    
    {{-- jQuery with fallback --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        if (typeof jQuery === 'undefined') {
            console.warn('jQuery CDN failed, using local fallback');
            document.write('<script src="{{ asset("assets/js/jquery-3.6.0.min.js") }}"><\/script>');
        }
    </script>
@endpush

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
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
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
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>Grid Terisi</h3>
                <div class="stat-value">{{ number_format($totalGridTerisi ?? 0) }}</div>
                <div class="stat-label">
                    <span
                        style="color: {{ $persentaseGrid >= 75 ? '#28a745' : ($persentaseGrid >= 50 ? '#ffc107' : '#dc3545') }}; font-weight: 700;">
                        {{ $persentaseGrid }}%
                    </span> dari 313 kotak total
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #f3e5f5;">
                <svg viewBox="0 0 24 24" style="fill: #7b1fa2;">
                    <path
                        d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
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
                    <path
                        d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
                </svg>
            </div>
            <div class="stat-info">
                <h3>Survei Bulan Ini</h3>
                <div class="stat-value">{{ number_format($surveiBulanIni ?? 0) }}</div>
                <div class="stat-label">
                    @if ($pertumbuhanBulanan == 0)
                        <span style="color: #6c757d; font-weight: 600;">
                            = 0% vs bulan lalu
                        </span>
                    @else
                        <span style="color: {{ $pertumbuhanBulanan >= 0 ? '#28a745' : '#dc3545' }}; font-weight: 600;">
                            {{ $pertumbuhanBulanan >= 0 ? '↑' : '↓' }} {{ abs($pertumbuhanBulanan) }}%
                        </span> vs bulan lalu
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ========== CHARTS SECTION ========== --}}
    <div class="stats-grid">
        {{-- TREND CHART --}}
        <div class="chart-card">
            <div class="chart-title">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z" />
                </svg>
                Trend Upload Survei
            </div>
            <div class="chart-subtitle">6 bulan terakhir</div>
            <div class="chart-container">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- PIE CHART TIPE SURVEI --}}
        <div class="chart-card">
            <div class="chart-title">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path
                        d="M11 2v20c-5.07-.5-9-4.79-9-10s3.93-9.5 9-10zm2.03 0v8.99H22c-.47-4.74-4.24-8.52-8.97-8.99zm0 11.01V22c4.74-.47 8.5-4.25 8.97-8.99h-8.97z" />
                </svg>
                Distribusi Tipe Survei
            </div>
            <div class="chart-subtitle">Persentase per tipe</div>
            <div class="chart-container">
                <canvas id="tipeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ========== BAR CHART TIMELINE DEKADE ========== --}}
    <div class="chart-card">
        <div class="chart-title">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
            </svg>
            Timeline Survei Per Dekade
        </div>
        <div class="chart-subtitle">Distribusi data survei seismik berdasarkan periode dekade</div>
        <div class="chart-container">
            <canvas id="dekadeChart"></canvas>
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
                                <td colspan="3" style="text-align: center; color: #999; padding: 30px;">Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- STATUS PEGAWAI ESDM --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Status Pegawai ESDM</h3>
                <span class="card-subtitle">Ringkasan pengguna pegawai internal</span>
            </div>
            <div class="card-body">
                @php
                    $totalPegawai = \App\Models\PegawaiInternal::count();
                    $pegawaiApproved = \App\Models\PegawaiInternal::approved()->count();
                    $pegawaiPending = \App\Models\PegawaiInternal::pendingApproval()->count();
                    $pegawaiVerified = \App\Models\PegawaiInternal::whereNotNull('email_verified_at')->count();
                @endphp
                
                {{-- Summary Stats --}}
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 20px;">
                    <div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); padding: 16px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 28px; font-weight: 700; color: #2e7d32;">{{ number_format($pegawaiApproved) }}</div>
                        <div style="font-size: 13px; color: #388e3c; font-weight: 500; margin-top: 4px;">✓ Approved</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); padding: 16px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 28px; font-weight: 700; color: #e65100;">{{ number_format($pegawaiPending) }}</div>
                        <div style="font-size: 13px; color: #f57c00; font-weight: 500; margin-top: 4px;">⏳ Pending</div>
                    </div>
                </div>

                {{-- Detail Stats --}}
                <table class="simple-table">
                    <tbody>
                        <tr>
                            <td style="display: flex; align-items: center; gap: 10px;">
                                <span style="width: 32px; height: 32px; background: #e3f2fd; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#1976d2">
                                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                    </svg>
                                </span>
                                Total Pegawai Terdaftar
                            </td>
                            <td style="text-align: right; font-weight: 700; font-size: 18px; color: #003366;">{{ number_format($totalPegawai) }}</td>
                        </tr>
                        <tr>
                            <td style="display: flex; align-items: center; gap: 10px;">
                                <span style="width: 32px; height: 32px; background: #e8f5e9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#388e3c">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </span>
                                Email Terverifikasi
                            </td>
                            <td style="text-align: right; font-weight: 600; color: #388e3c;">{{ number_format($pegawaiVerified) }}</td>
                        </tr>
                        <tr>
                            <td style="display: flex; align-items: center; gap: 10px;">
                                <span style="width: 32px; height: 32px; background: #f3e5f5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#7b1fa2">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                    </svg>
                                </span>
                                Disetujui
                            </td>
                            <td style="text-align: right; font-weight: 600; color: #7b1fa2;">
                                {{ $totalPegawai > 0 ? number_format(($pegawaiApproved / $totalPegawai) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    </tbody>
                </table>

                @if($pegawaiPending > 0)
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0; text-align: center;">
                        <a href="{{ route('admin.pengaturan.index') }}#approval" 
                           style="color: #f57c00; font-weight: 600; text-decoration: none; font-size: 14px;">
                            <i class="fas fa-bell" style="margin-right: 6px;"></i>
                            {{ $pegawaiPending }} pegawai menunggu approval →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== AKTIVITAS TERBARU (5 ITEMS) ========== --}}
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Aktivitas Terbaru</h3>
            <span class="card-subtitle">10 data survei terakhir yang diunggah</span>
        </div>
        <div class="card-body">
            <div class="activity-list">
                @forelse($surveiTerbaru as $survei)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                            </svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">{{ Str::limit($survei->judul, 50) }}</div>
                            <div class="activity-meta">
                                <span
                                    class="badge badge-sm badge-{{ strtolower($survei->tipe) }}">{{ $survei->tipe }}</span>
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

            @if ($surveiTerbaru->count() >= 10)
                <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
                    <a href="{{ route('admin.laporan.index') }}"
                        style="color: #003366; font-weight: 600; text-decoration: none;">
                        Lihat Semua Aktivitas →
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ========== SURVEI PER TAHUN (DENGAN PAGINATION) ========== --}}
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Ringkasan Data Per Tahun</h3>
            <span class="card-subtitle">Perbandingan data survei berdasarkan tahun ({{ $surveiPerTahun->total() }} tahun
                total)</span>
        </div>
        <div class="card-body">
            {{-- Loading indicator --}}
            <div id="tahun-loading" class="loading-indicator" style="display: none;">
                <div class="spinner"></div>
                <span>Memuat data...</span>
            </div>

            {{-- Table container --}}
            <div id="tahun-table-container">
                @include('admin.partials.tahun-pagination', ['surveiPerTahun' => $surveiPerTahun])
            </div>

            {{-- AJAX PAGINATION --}}
            @if ($surveiPerTahun->hasPages())
                <div style="margin-top: 20px; display: flex; justify-content: center;" id="tahun-pagination-container">
                    @include('admin.partials.pagination-controls', [
                        'paginator' => $surveiPerTahun,
                        'pageParam' => 'tahun_page',
                    ])
                </div>
            @endif

            <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
                <a href="{{ route('admin.laporan.index') }}"
                    style="color: #003366; font-weight: 600; text-decoration: none;">
                    Lihat Laporan Lengkap →
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
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                </svg>
                <span>Tambah Survei</span>
            </a>
            <a href="{{ route('admin.data_survei.index') }}" class="quick-action-btn">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                </svg>
                <span>Lihat Semua Data</span>
            </a>
            <a href="{{ route('admin.grid_kotak.index') }}" class="quick-action-btn">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M3 3v8h8V3H3zm6 6H5V5h4v4zm-6 4v8h8v-8H3zm6 6H5v-4h4v4zm4-16v8h8V3h-8zm6 6h-4V5h4v4zm-6 4v8h8v-8h-8zm6 6h-4v-4h4v4z" />
                </svg>
                <span>Kelola Grid Peta</span>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="quick-action-btn">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                </svg>
                <span>Lihat Laporan</span>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari PHP
            const trendData = @json($trendBulanan);
            const tipeData = @json($surveiPerTipe);

            // Chart 1: Trend Line Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(item => item.bulan),
                    datasets: [{
                        label: 'Jumlah Survei',
                        data: trendData.map(item => item.jumlah),
                        borderColor: '#003366',
                        backgroundColor: 'rgba(0, 51, 102, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#003366',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Chart 2: Pie Chart Tipe Survei
            const tipeCtx = document.getElementById('tipeChart').getContext('2d');
            const colors = ['#003366', '#0066cc', '#ff6b35', '#28a745'];
            new Chart(tipeCtx, {
                type: 'doughnut',
                data: {
                    labels: tipeData.map(item => item.tipe),
                    datasets: [{
                        data: tipeData.map(item => item.total),
                        backgroundColor: colors.slice(0, tipeData.length),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Chart 3: Bar Chart Timeline Dekade
            const dekadeData = @json($surveiPerDekade);
            const dekadeCtx = document.getElementById('dekadeChart').getContext('2d');
            
            // Generate gradient colors based on decade (older = lighter, newer = darker)
            const dekadeColors = dekadeData.map((item, index) => {
                const intensity = 0.4 + (index / dekadeData.length) * 0.5;
                return `rgba(0, 51, 102, ${intensity})`;
            });
            
            new Chart(dekadeCtx, {
                type: 'bar',
                data: {
                    labels: dekadeData.map(item => item.dekade),
                    datasets: [{
                        label: 'Jumlah Survei',
                        data: dekadeData.map(item => item.total),
                        backgroundColor: dekadeColors,
                        borderColor: '#003366',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 50
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    const item = dekadeData[context[0].dataIndex];
                                    return `Tahun ${item.tahun_awal} - ${item.tahun_awal + 9}`;
                                },
                                label: function(context) {
                                    return `${context.raw} data survei`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            title: {
                                display: true,
                                text: 'Jumlah Survei',
                                font: { weight: 'bold' }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Periode Dekade',
                                font: { weight: 'bold' }
                            }
                        }
                    }
                }
            });

            // AJAX Pagination for Dashboard
            $(document).on('click', '.ajax-page-btn', function(e) {
                e.preventDefault();

                const page = $(this).data('page');
                const pageParam = $(this).closest('.ajax-pagination').data('page-param');
                const isYearPagination = pageParam === 'tahun_page';

                // Show loading
                if (isYearPagination) {
                    $('#tahun-loading').show();
                    $('#tahun-table-container').hide();
                } else {
                    $('#survei-loading').show();
                    $('#survei-table-container').hide();
                }

                // Build URL with current filters
                const url = new URL(window.location.href);
                url.searchParams.set(pageParam, page);

                $.ajax({
                    url: url.toString(),
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (isYearPagination) {
                            $('#tahun-table-container').html(response.html).show();
                            $('#tahun-pagination-container').html(response.pagination);
                            $('#tahun-loading').hide();
                        } else {
                            $('#survei-table-container').html(response.html).show();
                            $('#survei-pagination-container').html(response.pagination);
                            $('#survei-loading').hide();
                        }

                        // Update URL without page reload
                        window.history.pushState({}, '', url.toString());
                    },
                    error: function() {
                        toastError('Terjadi kesalahan saat memuat data', 'Error');
                        if (isYearPagination) {
                            $('#tahun-loading').hide();
                            $('#tahun-table-container').show();
                        } else {
                            $('#survei-loading').hide();
                            $('#survei-table-container').show();
                        }
                    }
                });
            });
        });
    </script>
@endsection
