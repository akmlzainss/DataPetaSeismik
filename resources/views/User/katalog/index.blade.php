@extends('layouts.app')

@section('title', 'Katalog Survei Geologi Kelautan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-katalog.css') }}">
@endpush

@section('content')
    <div class="catalog-container">
        <!-- Header -->
        <div class="catalog-header">
            <h1 class="catalog-title">Katalog Survei Geologi Kelautan</h1>
            <p class="catalog-subtitle">Koleksi data survei dan pemetaan geologi kelautan BBSPGL</p>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <h3 class="filters-title">Filter Data</h3>
            <form method="GET" action="{{ route('katalog') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Tahun</label>
                        <select name="tahun" class="filter-select">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahun_options as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Tipe Survei</label>
                        <select name="tipe" class="filter-select">
                            <option value="">Semua Tipe</option>
                            @foreach ($tipe_options as $tipe)
                                <option value="{{ $tipe }}" {{ request('tipe') == $tipe ? 'selected' : '' }}>
                                    {{ $tipe }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Wilayah</label>
                        <select name="wilayah" class="filter-select">
                            <option value="">Semua Wilayah</option>
                            @foreach ($wilayah_options as $wilayah)
                                <option value="{{ $wilayah }}" {{ request('wilayah') == $wilayah ? 'selected' : '' }}>
                                    {{ $wilayah }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">&nbsp;</label>
                        <button type="submit" class="filter-button">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results -->
        @if ($surveys->count() > 0)
            <div class="surveys-grid">
                @foreach ($surveys as $survey)
                    <div class="survey-card">
                        <div class="survey-content">
                            <h3 class="survey-title">{{ $survey->judul }}</h3>

                            <div class="survey-meta">
                                <span class="meta-badge tipe-{{ strtolower($survey->tipe) }}">{{ $survey->tipe }}</span>
                                <span class="meta-badge">{{ $survey->wilayah }}</span>
                            </div>

                            <p class="survey-description">
                                {{ Str::limit($survey->deskripsi, 150) }}
                            </p>

                            <div class="survey-actions">
                                <span class="survey-year">{{ $survey->tahun }}</span>
                                <a href="{{ route('katalog.show', $survey->id) }}" class="view-detail-btn">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $surveys->links() }}
            </div>
        @else
            <div class="no-results">
                <div class="no-results-icon">📋</div>
                <h3>Tidak ada data survei ditemukan</h3>
                <p>Silakan ubah filter pencarian Anda</p>
            </div>
        @endif
    </div>
@endsection
