{{-- resources/views/admin/laporan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan & Statistik - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-laporan.css') }}">
    <style>
        .export-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 150px;
            z-index: 1000;
            margin-top: 4px;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            color: #003366;
        }

        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 4px;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a,
        .pagination span {
            display: block;
            padding: 8px 12px;
            color: #003366;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .pagination .active span {
            background: #003366;
            color: white;
            border-color: #003366;
        }

        .pagination a:hover {
            background: #f8f9fa;
            border-color: #003366;
        }

        .pagination .disabled span {
            color: #999;
            background: #f8f9fa;
        }
    </style>
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
                        @foreach ($tahunTersedia as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <label>Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @for ($m = 1; $m <= 12; $m++)
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
                        @foreach ($tipeSurvei as $t)
                            <option value="{{ $t }}" {{ $tipe == $t ? 'selected' : '' }}>{{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-filter" style="width: 100%;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"
                            style="display: inline; margin-right: 6px;">
                            <path
                                d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ========== EXPORT SECTION ========== --}}
    <div class="filter-section" style="background: #f8f9fa; border: 1px solid #e9ecef;">
        <h3 style="font-size: 16px; font-weight: 700; color: #003366; margin-bottom: 4px;">Export Laporan</h3>
        <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Download laporan dalam format Excel atau PDF</p>

        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.export.excel', request()->query()) }}" class="btn-export"
                style="text-decoration: none;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="margin-right: 6px;">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('admin.export.pdf', request()->query()) }}" class="btn-export"
                style="text-decoration: none; background: #dc3545; border-color: #dc3545;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="margin-right: 6px;">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- ========== INFO BANNER ========== --}}
    @if ($bulan || $tipe)
        <div class="info-banner">
            <h3>Filter Aktif</h3>
            <p>
                Menampilkan data untuk
                @if ($bulan)
                    <strong>{{ DateTime::createFromFormat('!m', $bulan)->format('F') }}</strong>
                @endif
                @if ($tahun)
                    <strong>{{ $tahun }}</strong>
                @endif
                @if ($tipe)
                    - Tipe: <strong>{{ $tipe }}</strong>
                @endif
                <a href="{{ route('admin.laporan.index') }}"
                    style="color: #003366; text-decoration: underline; margin-left: 8px;">Reset Filter</a>
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
                                    <span
                                        class="badge-report {{ strtolower($item->tipe) == '2d' ? 'blue' : (strtolower($item->tipe) == '3d' ? 'purple' : (strtolower($item->tipe) == 'hr' ? 'orange' : 'teal')) }}">
                                        {{ $item->tipe }}
                                    </span>
                                </td>
                                <td style="text-align: center; font-weight: 600;">{{ number_format($item->total) }}</td>
                                <td style="text-align: right;">
                                    <strong>{{ $totalFiltered > 0 ? number_format(($item->total / $totalFiltered) * 100, 1) : 0 }}%</strong>
                                    <div class="progress-bar">
                                        <div class="progress-fill"
                                            style="width: {{ $totalFiltered > 0 ? ($item->total / $totalFiltered) * 100 : 0 }}%;">
                                        </div>
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
                    @if ($laporanPerTipe->count() > 0)
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
                            <td style="text-align: right; font-weight: 700; color: #003366;">
                                {{ number_format($item->total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999; padding: 40px;">
                                Belum ada data survei
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($laporanPerTahun->count() > 0)
                    <tfoot>
                        <tr class="summary-row">
                            <td><strong>Total Keseluruhan</strong></td>
                            <td style="text-align: center;">
                                <strong>{{ number_format($laporanPerTahun->sum('tipe_2d')) }}</strong>
                            </td>
                            <td style="text-align: center;">
                                <strong>{{ number_format($laporanPerTahun->sum('tipe_3d')) }}</strong>
                            </td>
                            <td style="text-align: center;">
                                <strong>{{ number_format($laporanPerTahun->sum('tipe_hr')) }}</strong>
                            </td>
                            <td style="text-align: center;">
                                <strong>{{ number_format($laporanPerTahun->sum('tipe_lainnya')) }}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>{{ number_format($laporanPerTahun->sum('total')) }}</strong>
                            </td>
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
                            <td style="text-align: right; font-weight: 700; color: #003366;">
                                {{ number_format($admin->total_upload) }}</td>
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

    {{-- ========== SURVEI TERBARU DENGAN PAGINATION ========== --}}
    <div class="report-card">
        <div class="report-card-header">
            <div>
                <div class="report-card-title">Data Survei Terbaru</div>
                <div class="report-card-subtitle">Data survei yang baru diunggah ({{ $surveiTerbaru->total() }} total)
                </div>
            </div>
        </div>
        <div class="report-card-body">
            {{-- Loading indicator --}}
            <div id="survei-loading" class="loading-indicator" style="display: none;">
                <div class="spinner"></div>
                <span>Memuat data...</span>
            </div>

            {{-- Table container --}}
            <div id="survei-table-container">
                @include('admin.partials.survei-terbaru-table', ['surveiTerbaru' => $surveiTerbaru])
            </div>

            {{-- AJAX PAGINATION --}}
            @if ($surveiTerbaru->hasPages())
                <div class="pagination-wrapper" id="survei-pagination-container">
                    @include('admin.partials.pagination-controls', [
                        'paginator' => $surveiTerbaru,
                        'pageParam' => 'survei_page',
                    ])
                </div>
            @endif
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
                            <td style="text-align: center; color: #28a745; font-weight: 700;">
                                {{ number_format($surveiDenganMarker) }}</td>
                            <td style="text-align: right;">
                                <strong>{{ $totalSurvei > 0 ? number_format(($surveiDenganMarker / $totalSurvei) * 100, 1) : 0 }}%</strong>
                                <div class="progress-bar">
                                    <div class="progress-fill"
                                        style="width: {{ $totalSurvei > 0 ? ($surveiDenganMarker / $totalSurvei) * 100 : 0 }}%; background: linear-gradient(90deg, #28a745 0%, #5cb85c 100%);">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tanpa Marker</strong></td>
                            <td style="text-align: center; color: #dc3545; font-weight: 700;">
                                {{ number_format($surveiTanpaMarker) }}</td>
                            <td style="text-align: right;">
                                <strong>{{ $totalSurvei > 0 ? number_format(($surveiTanpaMarker / $totalSurvei) * 100, 1) : 0 }}%</strong>
                                <div class="progress-bar">
                                    <div class="progress-fill"
                                        style="width: {{ $totalSurvei > 0 ? ($surveiTanpaMarker / $totalSurvei) * 100 : 0 }}%; background: linear-gradient(90deg, #dc3545 0%, #e74a3b 100%);">
                                    </div>
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
                    <div class="report-card-title">Ringkasan Cepat</div>
                    <div class="report-card-subtitle">Informasi penting</div>
                </div>
            </div>
            <div class="report-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: center;">
                    <div style="padding: 20px; background: #e3f2fd; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: #1976d2;">{{ number_format($totalSurvei) }}
                        </div>
                        <div style="font-size: 12px; color: #666; margin-top: 4px;">Total Survei</div>
                    </div>
                    <div style="padding: 20px; background: #e8f5e9; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: #388e3c;">
                            {{ $totalSurvei > 0 ? number_format(($surveiDenganMarker / $totalSurvei) * 100, 1) : 0 }}%
                        </div>
                        <div style="font-size: 12px; color: #666; margin-top: 4px;">Kelengkapan Marker</div>
                    </div>
                </div>
                <div
                    style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <div style="font-size: 12px; color: #856404;">
                        <strong>Tips:</strong> Gunakan filter untuk melihat data spesifik dan export untuk mendapatkan
                        laporan lengkap.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdown = this.nextElementSibling;

                    // Close all other dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== dropdown) {
                            menu.classList.remove('show');
                        }
                    });

                    // Toggle current dropdown
                    dropdown.classList.toggle('show');
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.export-dropdown')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });

            // AJAX Pagination for Reports
            $(document).on('click', '.ajax-page-btn', function(e) {
                e.preventDefault();

                const page = $(this).data('page');
                const pageParam = $(this).closest('.ajax-pagination').data('page-param');

                // Show loading
                $('#survei-loading').show();
                $('#survei-table-container').hide();

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
                        $('#survei-table-container').html(response.html).show();
                        $('#survei-pagination-container').html(response.pagination);
                        $('#survei-loading').hide();

                        // Update URL without page reload
                        window.history.pushState({}, '', url.toString());
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat memuat data');
                        $('#survei-loading').hide();
                        $('#survei-table-container').show();
                    }
                });
            });
        });
    </script>
@endpush
