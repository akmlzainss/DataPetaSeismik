@extends('layouts.app')

@section('title', 'Katalog Survei Geologi Kelautan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-katalog.css') }}">
@endpush

@section('content')
    <div class="page-hero">
        <div class="container-custom">
            <h1 class="hero-title">Katalog Hasil Survei Geologi Kelautan</h1>
            <p class="hero-subtitle">Data hasil survei dan pemetaan geologi kelautan BBSPGL</p>
        </div>
    </div>

    <div class="catalog-container" style="position: relative; z-index: 2;">
        <!-- Filters -->
        <div class="filters-section">
            <h3 class="filters-title">Cari & Filter Data</h3>
            <form method="GET" action="{{ route('katalog') }}">
                <!-- Search Bar -->
                <div class="search-container">
                    <input type="text" name="search" class="search-input"
                        placeholder="Cari judul atau deskripsi survei..." value="{{ request('search') }}">
                </div>

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

                    <div class="filter-group filter-actions">
                        <label class="filter-label">&nbsp;</label>
                        <div style="display: flex; gap: 0.5rem; width: 100%;">
                            <button type="submit" class="filter-button">Filter</button>
                            <a href="{{ route('katalog') }}" class="reset-button">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results -->
        @if ($surveys->count() > 0)
            <div class="surveys-grid">
                @foreach ($surveys as $survey)
                    <div class="survey-card">
                        {{-- Conditional Image Display --}}
                        @if ($survey->gambar_thumbnail || $survey->gambar_pratinjau)
                            <div class="survey-image-container">
                                <img src="{{ asset('storage/' . ($survey->gambar_thumbnail ?? $survey->gambar_pratinjau)) }}"
                                    alt="{{ $survey->judul }}">
                            </div>
                        @endif

                        <div class="survey-content">
                            <h3 class="survey-title">{{ $survey->judul }}</h3>

                            <div class="survey-meta">
                                <span class="meta-badge tipe-{{ strtolower($survey->tipe) }}">{{ $survey->tipe }}</span>
                                <span class="meta-badge">{{ $survey->wilayah }}</span>
                            </div>

                            <div class="survey-description">
                                {{ Str::limit(strip_tags($survey->deskripsi), 200) }}
                            </div>

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
                @if ($surveys->hasPages())
                    <div class="pagination-info">
                        {{ $surveys->firstItem() }} - {{ $surveys->lastItem() }} dari {{ $surveys->total() }}
                        survei
                    </div>
                @endif
            </div>
        @else
            <div class="no-results">
                <div class="no-results-icon">📋</div>
                <h3>Tidak ada data survei ditemukan</h3>
                <p>Silakan ubah kata kunci atau filter pencarian Anda</p>
                <a href="{{ route('katalog') }}" class="reset-button"
                    style="margin-top: 1rem; display: inline-block;">Reset Filter</a>
            </div>
        @endif
    </div>
@endsection
