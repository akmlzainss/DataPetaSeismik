{{-- resources/views/admin/data_survei/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Survei - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Data Survei Seismik</h1>
        <p>Kelola semua data survei seismik yang telah diunggah</p>
    </div>

    <div class="welcome-section">
        <!-- Header + Tombol Tambah -->
        <div class="survei-header">
            <h2>Daftar Data Survei</h2>
            <a href="{{ route('admin.data_survei.create') }}" class="btn-tambah-data">
                + Tambah Data Survei
            </a>
        </div>

        <!-- Search & Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('admin.data_survei.index') }}" class="filter-form">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Cari judul, wilayah, tahun..."
                        value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="search-btn">Search</button>
                </div>

                <div class="filters-group">
                    <!-- Filter Tahun (otomatis dari database) -->
                    <select name="tahun" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahuns as $th)
                            <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                {{ $th }}</option>
                        @endforeach
                    </select>

                    <!-- Urutkan -->
                    <select name="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="terbaru" {{ request('sort') == 'terbaru' || !request('sort') ? 'selected' : '' }}>
                            Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A → Z</option>
                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z → A</option>
                    </select>
                </div>

                @if (request()->hasAny(['search', 'tahun', 'sort']))
                    <a href="{{ route('admin.data_survei.index') }}" class="reset-filter">Reset</a>
                @endif
            </form>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Grid -->
        @if ($dataSurvei->count() > 0)
            <div class="survei-grid">
                @foreach ($dataSurvei as $item)
                    <div class="survei-card">
                        <!-- Gambar Pratinjau -->
                        <div class="survei-card-image">
                            @if ($item->gambar_pratinjau)
                                <img src="{{ asset('storage/' . $item->gambar_pratinjau) }}" alt="{{ $item->judul }}">
                            @else
                                <div class="no-image">
                                    <svg viewBox="0 0 24 24" style="width:60px;height:60px;fill:rgba(255,255,255,0.3);">
                                        <path
                                            d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                    </svg>
                                </div>
                            @endif
                            <span class="survei-badge">{{ $item->tipe }}</span>
                        </div>

                        <!-- Konten Kartu -->
                        <div class="survei-card-content">
                            <h3 class="survei-card-title">
                                <a href="{{ route('admin.data_survei.show', $item) }}">
                                    {{ $item->judul }}
                                </a>
                            </h3>

                            <!-- TAMBahan: Deskripsi singkat (maks 120 karakter + ...) -->
                            <!-- TAMBahan: Deskripsi singkat (maks 2 baris + ...) -->
                            @if ($item->deskripsi)
                                <p class="survei-card-description">
                                    {{ Str::limit(strip_tags($item->deskripsi), 140, '...') }}
                                </p>
                            @endif

                            <!-- Tombol Aksi (TETAP SAMA 100%) -->
                            <div class="survei-card-actions">
                                <a href="{{ route('admin.data_survei.show', $item) }}" class="btn-detail">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                    </svg>
                                    Lihat Detail
                                </a>
                                <a href="{{ route('admin.data_survei.edit', $item) }}" class="btn-edit" title="Edit">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.data_survei.destroy', $item) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $dataSurvei->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                </svg>
                <h3>
                    @if (request()->filled('search') || request()->filled('tahun'))
                        Tidak Ada Data yang Cocok
                    @else
                        Belum Ada Data Survei
                    @endif
                </h3>
                <p>
                    @if (request()->filled('search') || request()->filled('tahun'))
                        Coba ubah kata kunci atau filter yang digunakan.
                    @else
                        Mulai tambahkan data survei seismik pertama Anda.
                    @endif
                </p>
                <a href="{{ route('admin.data_survei.create') }}" class="btn-tambah-data">
                    + Tambah Data Survei
                </a>
            </div>
        @endif
    </div>
@endsection
