{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - BBSPGL')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 700;
            color: #003366;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        /* Dashboard Pagination Styles */
        .dashboard-pagination {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            min-width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 6px;
            color: #003366;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            background: white;
        }

        .pagination-btn:hover:not(.disabled):not(.active) {
            background: #f8f9fa;
            border-color: #003366;
            color: #003366;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .pagination-btn.active {
            background: #003366;
            border-color: #003366;
            color: white;
            font-weight: 600;
        }

        .pagination-btn.disabled {
            background: #f8f9fa;
            border-color: #e9ecef;
            color: #999;
            cursor: not-allowed;
        }

        .pagination-btn svg {
            width: 16px;
            height: 16px;
        }

        /* Loading Indicator */
        .loading-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: #666;
            font-size: 14px;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #003366;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* AJAX Pagination */
        .ajax-pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: center;
        }

        .ajax-page-btn {
            background: none;
            border: none;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <h3>Lokasi Marker</h3>
                <div class="stat-value">{{ number_format($totalMarker ?? 0) }}</div>
                <div class="stat-label">
                    <span
                        style="color: {{ $persentaseMarker >= 75 ? '#28a745' : ($persentaseMarker >= 50 ? '#ffc107' : '#dc3545') }}; font-weight: 700;">
                        {{ $persentaseMarker }}%
                    </span> dari total survei
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
                    <span style="color: {{ $pertumbuhanBulanan >= 0 ? '#28a745' : '#dc3545' }}; font-weight: 600;">
                        {{ $pertumbuhanBulanan >= 0 ? '↑' : '↓' }} {{ abs($pertumbuhanBulanan) }}%
                    </span> vs bulan lalu
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

    {{-- ========== BAR CHART WILAYAH ========== --}}
    <div class="chart-card">
        <div class="chart-title">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                <path
                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
            </svg>
            Top 10 Wilayah Survei
        </div>
        <div class="chart-subtitle">Wilayah dengan survei terbanyak</div>
        <div class="chart-container">
            <canvas id="wilayahChart"></canvas>
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

        {{-- TOP 10 WILAYAH --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Top 10 Wilayah Survei</h3>
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
                                <td colspan="3" style="text-align: center; color: #999; padding: 30px;">Belum ada data
                                </td>
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
            <a href="{{ route('admin.lokasi_marker.index') }}" class="quick-action-btn">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                </svg>
                <span>Kelola Marker</span>
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
            const wilayahData = @json($topWilayah);

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

            // Chart 3: Bar Chart Wilayah
            const wilayahCtx = document.getElementById('wilayahChart').getContext('2d');
            new Chart(wilayahCtx, {
                type: 'bar',
                data: {
                    labels: wilayahData.map(item => item.wilayah.length > 20 ? item.wilayah.substring(0,
                        20) + '...' : item.wilayah),
                    datasets: [{
                        label: 'Jumlah Survei',
                        data: wilayahData.map(item => item.total),
                        backgroundColor: 'rgba(0, 51, 102, 0.8)',
                        borderColor: '#003366',
                        borderWidth: 1
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
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
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
                        alert('Terjadi kesalahan saat memuat data');
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
