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

        /* Period Button Styles */
        .period-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            background: white;
            color: #333;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .period-btn:hover {
            background: #f8f9fa;
            border-color: #003366;
        }

        .period-btn.active {
            background: #003366;
            color: white;
            border-color: #003366;
        }

        .period-details {
            margin-top: 8px;
        }

        .period-details summary {
            padding: 4px 0;
            font-weight: 500;
        }

        .period-details summary:hover {
            color: #003366;
        }

        .period-details[open] summary {
            margin-bottom: 8px;
            color: #003366;
        }

        /* Export Period Button Styles */
        .export-period-btn {
            padding: 4px 8px;
            border: 1px solid #ddd;
            background: white;
            color: #333;
            border-radius: 3px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .export-period-btn:hover {
            background: #f8f9fa;
            border-color: #003366;
        }

        .export-period-btn.active {
            background: #003366;
            color: white;
            border-color: #003366;
        }

        /* Period Tab Styles */
        .period-tab-btn {
            padding: 8px 16px;
            border: 2px solid #ddd;
            background: white;
            color: #666;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .period-tab-btn:hover {
            background: #f8f9fa;
            border-color: #003366;
            color: #003366;
        }

        .period-tab-btn.active {
            background: #003366;
            color: white;
            border-color: #003366;
            box-shadow: 0 2px 4px rgba(0, 51, 102, 0.2);
        }

        .period-dropdown {
            width: 100%;
            transition: all 0.3s ease;
        }

        .period-dropdown:focus {
            border-color: #003366;
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
        }

        /* Export Tab Styles */
        .export-tab-btn {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background: white;
            color: #666;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .export-tab-btn:hover {
            background: #f8f9fa;
            border-color: #003366;
            color: #003366;
        }

        .export-tab-btn.active {
            background: #003366;
            color: white;
            border-color: #003366;
        }

        .export-dropdown {
            width: 100%;
            font-size: 12px;
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
                <div class="filter-item" style="grid-column: span 2;">
                    <label>Periode Waktu</label>

                    {{-- Period Category Tabs --}}
                    <div class="period-tabs" style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 12px;">
                        <button type="button" class="period-tab-btn" data-category="all"
                            onclick="setPeriodCategory('all')">
                            Semua
                        </button>
                        <button type="button" class="period-tab-btn" data-category="weekly"
                            onclick="setPeriodCategory('weekly')">
                            Mingguan
                        </button>
                        <button type="button" class="period-tab-btn" data-category="monthly"
                            onclick="setPeriodCategory('monthly')">
                            Bulanan
                        </button>
                        <button type="button" class="period-tab-btn" data-category="yearly"
                            onclick="setPeriodCategory('yearly')">
                            Tahunan
                        </button>
                        <button type="button" class="period-tab-btn" data-category="manual"
                            onclick="setPeriodCategory('manual')">
                            Manual
                        </button>
                    </div>

                    {{-- Contextual Period Dropdown --}}
                    <div id="periodDropdownContainer" style="margin-bottom: 8px;">
                        {{-- Weekly Options --}}
                        <select name="export_range" id="weeklyPeriod" class="form-select period-dropdown"
                            style="display: none;">
                            <option value="1_week" {{ request('export_range') == '1_week' ? 'selected' : '' }}>1 Minggu
                                Terakhir</option>
                            <option value="2_weeks" {{ request('export_range') == '2_weeks' ? 'selected' : '' }}>2 Minggu
                                Terakhir</option>
                            <option value="3_weeks" {{ request('export_range') == '3_weeks' ? 'selected' : '' }}>3 Minggu
                                Terakhir</option>
                        </select>

                        {{-- Monthly Options --}}
                        <select name="export_range" id="monthlyPeriod" class="form-select period-dropdown"
                            style="display: none;">
                            <option value="1_month" {{ request('export_range') == '1_month' ? 'selected' : '' }}>1 Bulan
                                Terakhir</option>
                            <option value="2_months" {{ request('export_range') == '2_months' ? 'selected' : '' }}>2 Bulan
                                Terakhir</option>
                            <option value="3_months" {{ request('export_range') == '3_months' ? 'selected' : '' }}>3 Bulan
                                Terakhir</option>
                            <option value="4_months" {{ request('export_range') == '4_months' ? 'selected' : '' }}>4 Bulan
                                Terakhir</option>
                            <option value="5_months" {{ request('export_range') == '5_months' ? 'selected' : '' }}>5 Bulan
                                Terakhir</option>
                            <option value="6_months" {{ request('export_range') == '6_months' ? 'selected' : '' }}>6 Bulan
                                Terakhir</option>
                            <option value="7_months" {{ request('export_range') == '7_months' ? 'selected' : '' }}>7 Bulan
                                Terakhir</option>
                            <option value="8_months" {{ request('export_range') == '8_months' ? 'selected' : '' }}>8 Bulan
                                Terakhir</option>
                            <option value="9_months" {{ request('export_range') == '9_months' ? 'selected' : '' }}>9 Bulan
                                Terakhir</option>
                            <option value="10_months" {{ request('export_range') == '10_months' ? 'selected' : '' }}>10
                                Bulan Terakhir</option>
                            <option value="11_months" {{ request('export_range') == '11_months' ? 'selected' : '' }}>11
                                Bulan Terakhir</option>
                        </select>

                        {{-- Yearly Options --}}
                        <select name="export_range" id="yearlyPeriod" class="form-select period-dropdown"
                            style="display: none;">
                            <option value="1_year" {{ request('export_range') == '1_year' ? 'selected' : '' }}>1 Tahun
                                Terakhir</option>
                            <option value="2_years" {{ request('export_range') == '2_years' ? 'selected' : '' }}>2 Tahun
                                Terakhir</option>
                            <option value="3_years" {{ request('export_range') == '3_years' ? 'selected' : '' }}>3 Tahun
                                Terakhir</option>
                            <option value="4_years" {{ request('export_range') == '4_years' ? 'selected' : '' }}>4 Tahun
                                Terakhir</option>
                            <option value="5_years" {{ request('export_range') == '5_years' ? 'selected' : '' }}>5 Tahun
                                Terakhir</option>
                        </select>
                    </div>

                    {{-- Hidden input for form submission --}}
                    <input type="hidden" name="export_range" id="selectedPeriod" value="{{ request('export_range') }}">
                </div>

                <div class="filter-item">
                    <label>Tahun</label>
                    <select name="tahun" class="form-select" {{ request('export_range') ? 'disabled' : '' }}>
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunTersedia as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <label>Bulan</label>
                    <select name="bulan" class="form-select" {{ request('export_range') ? 'disabled' : '' }}>
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
        <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Pilih periode waktu dan format export yang diinginkan
        </p>

        <form id="exportForm" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;">
            {{-- Periode Export --}}
            <div style="min-width: 250px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 4px;">Periode
                    Data</label>

                {{-- Export Category Tabs --}}
                <div class="export-tabs" style="display: flex; flex-wrap: wrap; gap: 3px; margin-bottom: 8px;">
                    <button type="button" class="export-tab-btn" data-export-category="all"
                        onclick="setExportCategory('all')">Semua</button>
                    <button type="button" class="export-tab-btn" data-export-category="weekly"
                        onclick="setExportCategory('weekly')">Mingguan</button>
                    <button type="button" class="export-tab-btn" data-export-category="monthly"
                        onclick="setExportCategory('monthly')">Bulanan</button>
                    <button type="button" class="export-tab-btn" data-export-category="yearly"
                        onclick="setExportCategory('yearly')">Tahunan</button>
                </div>

                {{-- Contextual Export Dropdown --}}
                <div id="exportDropdownContainer">
                    {{-- Weekly Export Options --}}
                    <select name="export_range" id="exportWeekly" class="form-select export-dropdown"
                        style="display: none; font-size: 12px;">
                        <option value="1_week">1 Minggu Terakhir</option>
                        <option value="2_weeks">2 Minggu Terakhir</option>
                        <option value="3_weeks">3 Minggu Terakhir</option>
                    </select>

                    {{-- Monthly Export Options --}}
                    <select name="export_range" id="exportMonthly" class="form-select export-dropdown"
                        style="display: none; font-size: 12px;">
                        <option value="1_month">1 Bulan Terakhir</option>
                        <option value="2_months">2 Bulan Terakhir</option>
                        <option value="3_months">3 Bulan Terakhir</option>
                        <option value="6_months">6 Bulan Terakhir</option>
                        <option value="9_months">9 Bulan Terakhir</option>
                        <option value="11_months">11 Bulan Terakhir</option>
                    </select>

                    {{-- Yearly Export Options --}}
                    <select name="export_range" id="exportYearly" class="form-select export-dropdown"
                        style="display: none; font-size: 12px;">
                        <option value="1_year">1 Tahun Terakhir</option>
                        <option value="2_years">2 Tahun Terakhir</option>
                        <option value="3_years">3 Tahun Terakhir</option>
                        <option value="5_years">5 Tahun Terakhir</option>
                    </select>

                    {{-- Hidden input for export range --}}
                    <input type="hidden" id="exportRange" value="">
                </div>
            </div>

            {{-- Tipe Survei untuk Export --}}
            <div style="min-width: 150px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 4px;">Tipe
                    Survei</label>
                <select name="export_tipe" id="exportTipe" class="form-select" style="width: 100%;">
                    <option value="">Semua Tipe</option>
                    @foreach ($tipeSurvei as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Export Buttons --}}
            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="exportData('excel')" class="btn-export"
                    style="border: none; cursor: pointer;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"
                        style="margin-right: 6px;">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                    </svg>
                    Export Excel
                </button>
                <button type="button" onclick="exportData('pdf')" class="btn-export"
                    style="border: none; cursor: pointer; background: #dc3545; border-color: #dc3545;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"
                        style="margin-right: 6px;">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                    </svg>
                    Export PDF
                </button>
            </div>
        </form>

        {{-- Preview Info --}}
        <div id="exportPreview"
            style="margin-top: 12px; padding: 10px; background: #e3f2fd; border-radius: 6px; font-size: 13px; display: none;">
            <strong>Preview Export:</strong> <span id="previewText"></span>
        </div>
    </div>

    {{-- ========== INFO BANNER ========== --}}
    @if ($bulan || $tipe || request('export_range'))
        <div class="info-banner">
            <h3>Filter Aktif</h3>
            <p>
                Menampilkan data untuk
                @if (request('export_range'))
                    @php
                        $rangeTexts = [
                            '1_week' => '1 Minggu Terakhir',
                            '2_weeks' => '2 Minggu Terakhir',
                            '3_weeks' => '3 Minggu Terakhir',
                            '1_month' => '1 Bulan Terakhir',
                            '2_months' => '2 Bulan Terakhir',
                            '3_months' => '3 Bulan Terakhir',
                            '4_months' => '4 Bulan Terakhir',
                            '5_months' => '5 Bulan Terakhir',
                            '6_months' => '6 Bulan Terakhir',
                            '7_months' => '7 Bulan Terakhir',
                            '8_months' => '8 Bulan Terakhir',
                            '9_months' => '9 Bulan Terakhir',
                            '10_months' => '10 Bulan Terakhir',
                            '11_months' => '11 Bulan Terakhir',
                            '1_year' => '1 Tahun Terakhir',
                            '2_years' => '2 Tahun Terakhir',
                            '3_years' => '3 Tahun Terakhir',
                            '4_years' => '4 Tahun Terakhir',
                            '5_years' => '5 Tahun Terakhir',
                        ];
                    @endphp
                    <strong>{{ $rangeTexts[request('export_range')] ?? request('export_range') }}</strong>
                @else
                    @if ($bulan)
                        <strong>{{ DateTime::createFromFormat('!m', $bulan)->format('F') }}</strong>
                    @endif
                    @if ($tahun)
                        <strong>{{ $tahun }}</strong>
                    @endif
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
        // Export functionality
        function exportData(format) {
            const exportRange = document.getElementById('exportRange').value;
            const exportTipe = document.getElementById('exportTipe').value;

            // Build URL with parameters
            const params = new URLSearchParams();
            if (exportRange) params.append('export_range', exportRange);
            if (exportTipe) params.append('export_tipe', exportTipe);

            // Add legacy parameters for backward compatibility
            const currentParams = new URLSearchParams(window.location.search);
            if (currentParams.get('tahun')) params.append('tahun', currentParams.get('tahun'));
            if (currentParams.get('bulan')) params.append('bulan', currentParams.get('bulan'));
            if (currentParams.get('tipe')) params.append('tipe', currentParams.get('tipe'));

            const baseUrl = format === 'excel' ?
                '{{ route('admin.export.excel') }}' :
                '{{ route('admin.export.pdf') }}';

            const fullUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');

            // Open in new window/tab for download
            window.open(fullUrl, '_blank');
        }

        // Handle export category selection
        function setExportCategory(category) {
            // Update tab states
            document.querySelectorAll('.export-tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Activate selected tab
            const activeTab = document.querySelector(`[data-export-category="${category}"]`);
            if (activeTab) {
                activeTab.classList.add('active');
            }

            // Hide all export dropdowns
            document.querySelectorAll('.export-dropdown').forEach(dropdown => {
                dropdown.style.display = 'none';
            });

            // Show relevant dropdown and set value
            let selectedValue = '';

            switch (category) {
                case 'all':
                    selectedValue = '';
                    break;
                case 'weekly':
                    document.getElementById('exportWeekly').style.display = 'block';
                    selectedValue = document.getElementById('exportWeekly').value || '1_week';
                    break;
                case 'monthly':
                    document.getElementById('exportMonthly').style.display = 'block';
                    selectedValue = document.getElementById('exportMonthly').value || '1_month';
                    break;
                case 'yearly':
                    document.getElementById('exportYearly').style.display = 'block';
                    selectedValue = document.getElementById('exportYearly').value || '1_year';
                    break;
            }

            // Update hidden input
            document.getElementById('exportRange').value = selectedValue;

            // Update preview
            updateExportPreview();
        }

        // Handle export dropdown changes
        function handleExportDropdownChange(dropdown) {
            document.getElementById('exportRange').value = dropdown.value;
            updateExportPreview();
        }

        // Update preview when selection changes
        function updateExportPreview() {
            const exportRange = document.getElementById('exportRange').value;
            const exportTipe = document.getElementById('exportTipe').value;
            const preview = document.getElementById('exportPreview');
            const previewText = document.getElementById('previewText');

            if (exportRange || exportTipe) {
                let text = '';

                if (exportRange) {
                    const rangeTexts = {
                        '1_week': '1 Minggu Terakhir',
                        '2_weeks': '2 Minggu Terakhir',
                        '3_weeks': '3 Minggu Terakhir',
                        '1_month': '1 Bulan Terakhir',
                        '2_months': '2 Bulan Terakhir',
                        '3_months': '3 Bulan Terakhir',
                        '4_months': '4 Bulan Terakhir',
                        '5_months': '5 Bulan Terakhir',
                        '6_months': '6 Bulan Terakhir',
                        '7_months': '7 Bulan Terakhir',
                        '8_months': '8 Bulan Terakhir',
                        '9_months': '9 Bulan Terakhir',
                        '10_months': '10 Bulan Terakhir',
                        '11_months': '11 Bulan Terakhir',
                        '1_year': '1 Tahun Terakhir',
                        '2_years': '2 Tahun Terakhir',
                        '3_years': '3 Tahun Terakhir',
                        '4_years': '4 Tahun Terakhir',
                        '5_years': '5 Tahun Terakhir'
                    };
                    text += rangeTexts[exportRange] || exportRange;
                }

                if (exportTipe) {
                    text += (text ? ' - ' : '') + 'Tipe: ' + exportTipe;
                }

                previewText.textContent = text;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }

        // Handle period category selection
        function setPeriodCategory(category) {
            // Update tab states
            document.querySelectorAll('.period-tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Activate selected tab
            const activeTab = document.querySelector(`[data-category="${category}"]`);
            if (activeTab) {
                activeTab.classList.add('active');
            }

            // Hide all dropdowns
            document.querySelectorAll('.period-dropdown').forEach(dropdown => {
                dropdown.style.display = 'none';
            });

            // Show relevant dropdown and set value
            let selectedValue = '';

            switch (category) {
                case 'all':
                    selectedValue = '';
                    break;
                case 'weekly':
                    document.getElementById('weeklyPeriod').style.display = 'block';
                    selectedValue = document.getElementById('weeklyPeriod').value || '1_week';
                    document.getElementById('weeklyPeriod').value = selectedValue;
                    break;
                case 'monthly':
                    document.getElementById('monthlyPeriod').style.display = 'block';
                    selectedValue = document.getElementById('monthlyPeriod').value || '1_month';
                    document.getElementById('monthlyPeriod').value = selectedValue;
                    break;
                case 'yearly':
                    document.getElementById('yearlyPeriod').style.display = 'block';
                    selectedValue = document.getElementById('yearlyPeriod').value || '1_year';
                    document.getElementById('yearlyPeriod').value = selectedValue;
                    break;
                case 'manual':
                    selectedValue = '';
                    break;
            }

            // Update hidden input
            document.getElementById('selectedPeriod').value = selectedValue;

            // Toggle manual filters
            toggleManualFilters();
        }

        // Handle dropdown changes
        function handlePeriodDropdownChange(dropdown) {
            const value = dropdown.value;
            document.getElementById('selectedPeriod').value = value;
        }

        // Initialize period category based on current selection
        function initializePeriodCategory() {
            const currentValue = document.getElementById('selectedPeriod').value;

            if (!currentValue) {
                setPeriodCategory('all');
                return;
            }

            // Determine category based on current value
            if (currentValue.includes('week')) {
                setPeriodCategory('weekly');
            } else if (currentValue.includes('month')) {
                setPeriodCategory('monthly');
            } else if (currentValue.includes('year')) {
                setPeriodCategory('yearly');
            } else {
                setPeriodCategory('manual');
            }
        }

        // Handle filter form interactions
        function toggleManualFilters() {
            const selectedPeriod = document.getElementById('selectedPeriod').value;
            const tahunFilter = document.querySelector('select[name="tahun"]');
            const bulanFilter = document.querySelector('select[name="bulan"]');

            if (tahunFilter && bulanFilter) {
                const isRangeSelected = selectedPeriod !== '';
                tahunFilter.disabled = isRangeSelected;
                bulanFilter.disabled = isRangeSelected;

                if (isRangeSelected) {
                    tahunFilter.style.opacity = '0.5';
                    bulanFilter.style.opacity = '0.5';
                } else {
                    tahunFilter.style.opacity = '1';
                    bulanFilter.style.opacity = '1';
                }
            }
        }

        // Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize filter state
            toggleManualFilters();

            // Add event listeners for period dropdowns
            document.getElementById('weeklyPeriod').addEventListener('change', function() {
                handlePeriodDropdownChange(this);
            });
            document.getElementById('monthlyPeriod').addEventListener('change', function() {
                handlePeriodDropdownChange(this);
            });
            document.getElementById('yearlyPeriod').addEventListener('change', function() {
                handlePeriodDropdownChange(this);
            });

            // Initialize period category based on current selection
            initializePeriodCategory();

            // Add event listeners for export dropdowns
            document.getElementById('exportWeekly').addEventListener('change', function() {
                handleExportDropdownChange(this);
            });
            document.getElementById('exportMonthly').addEventListener('change', function() {
                handleExportDropdownChange(this);
            });
            document.getElementById('exportYearly').addEventListener('change', function() {
                handleExportDropdownChange(this);
            });
            document.getElementById('exportTipe').addEventListener('change', updateExportPreview);

            // Initialize export category
            setExportCategory('all');

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
