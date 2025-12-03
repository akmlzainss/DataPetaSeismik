@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-5" style="max-width: 1320px;">
    
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2">Katalog Data BBSPGL</h2>
            <p class="text-muted">Temukan dan eksplorasi data Peta Indone</p>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <form action="#" method="GET" class="search-wrapper">
                <div class="input-group input-group-lg shadow">
                    <span class="input-group-text border-0 bg-white ps-4">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-0" 
                           placeholder="Cari data di katalog geoportal Badan Informasi Geospasial"
                           aria-label="Pencarian peta">
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="bi bi-search me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Filter Tabs with Count --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-semibold">Filter Kategori</h5>
                <span class="badge bg-light text-dark">16 Data Tersedia</span>
            </div>
            <div class="filter-tabs d-flex flex-wrap gap-2">
                <button class="filter-btn active" data-filter="semua">
                    <i class="bi bi-grid-3x3-gap me-1"></i> Semua
                </button>
                <button class="filter-btn" data-filter="sungai">
                    <i class="bi bi-water me-1"></i> Sungai
                </button>
                <button class="filter-btn" data-filter="jalan">
                    <i class="bi bi-signpost-2 me-1"></i> Jalan
                </button>
                <button class="filter-btn" data-filter="ctsrt">
                    <i class="bi bi-diagram-3 me-1"></i> CTSRT
                </button>
                <button class="filter-btn" data-filter="imagery">
                    <i class="bi bi-image me-1"></i> Imagery
                </button>
                <button class="filter-btn" data-filter="batas">
                    <i class="bi bi-border-all me-1"></i> Batas
                </button>
                <button class="filter-btn" data-filter="atlas">
                    <i class="bi bi-book me-1"></i> Atlas
                </button>
            </div>
        </div>
    </div>

    {{-- Sorting and View Options --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2 align-items-center">
                    <span class="text-muted small">Urutkan:</span>
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>Terbaru</option>
                        <option>A-Z</option>
                        <option>Z-A</option>
                        <option>Paling Populer</option>
                    </select>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary active">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Card Section --}}
    <div class="row g-4">

        {{-- Card 1 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <span class="badge-category">Atlas</span>
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-pins_23-2147815236.jpg" 
                         class="card-img-top katalog-img" 
                         alt="Peta GPS Navigation">
                    <div class="img-overlay">
                        <button class="btn-quick-view">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 flex-grow-1">
                            <a href="#" class="title-link">AAtlas_Sebaran_GunungApi</a>
                        </h6>
                        <button class="btn-bookmark" title="Simpan">
                            <i class="bi bi-bookmark"></i>
                        </button>
                    </div>
                    <p class="card-text text-muted small flex-grow-1">
                        Peta Persebaran Gunungapi ini dibangun menggunakan data dari berbagai sumber yang telah diverifikasi untuk memberikan informasi yang akurat dan terpercaya.
                    </p>
                    <div class="card-meta">
                        <span class="meta-item">
                            <i class="bi bi-calendar3"></i> 2024
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-download"></i> 245
                        </span>
                    </div>
                    <a href="#" class="btn-detail mt-2">
                        LIHAT DETAIL <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <span class="badge-category">Administrasi</span>
                    <img src="https://img.freepik.com/free-vector/indonesia-map-infographic-gradient-style_23-2149015999.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta Topografi">
                    <div class="img-overlay">
                        <button class="btn-quick-view">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 flex-grow-1">
                            <a href="#" class="title-link">ADMINISTRASI_AR_DESAKEL</a>
                        </h6>
                        <button class="btn-bookmark" title="Simpan">
                            <i class="bi bi-bookmark"></i>
                        </button>
                    </div>
                    <p class="card-text text-muted small flex-grow-1">
                        Geodatabase data batas wilayah administrasi desa/kelurahan di seluruh Indonesia yang mencakup informasi geografis lengkap untuk keperluan analisis dan perencanaan.
                    </p>
                    <div class="card-meta">
                        <span class="meta-item">
                            <i class="bi bi-calendar3"></i> 2024
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-download"></i> 532
                        </span>
                    </div>
                    <a href="#" class="btn-detail mt-2">
                        LIHAT DETAIL <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <span class="badge-category">Administrasi</span>
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-administrative-divisions_23-2148715632.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta Isometrik">
                    <div class="img-overlay">
                        <button class="btn-quick-view">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 flex-grow-1">
                            <a href="#" class="title-link">ADMINSITRASI_AR_KABKOTA</a>
                        </h6>
                        <button class="btn-bookmark" title="Simpan">
                            <i class="bi bi-bookmark"></i>
                        </button>
                    </div>
                    <p class="card-text text-muted small flex-grow-1">
                        Geodatabase data batas wilayah administrasi kabupaten/kota dengan detail lengkap mencakup koordinat geografis dan metadata terkait untuk berbagai keperluan pemetaan.
                    </p>
                    <div class="card-meta">
                        <span class="meta-item">
                            <i class="bi bi-calendar3"></i> 2024
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-download"></i> 421
                        </span>
                    </div>
                    <a href="#" class="btn-detail mt-2">
                        LIHAT DETAIL <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <span class="badge-category">Atlas</span>
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-pins_23-2147815236.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                    <div class="img-overlay">
                        <button class="btn-quick-view">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 flex-grow-1">
                            <a href="#" class="title-link">Atlas_250K_BaluBara</a>
                        </h6>
                        <button class="btn-bookmark" title="Simpan">
                            <i class="bi bi-bookmark"></i>
                        </button>
                    </div>
                    <p class="card-text text-muted small flex-grow-1">
                        Data ini diperuntukkan sebagai materi web kartografi pada skala 1:250.000 yang menampilkan informasi persebaran batu bara di wilayah Indonesia dengan detail komprehensif.
                    </p>
                    <div class="card-meta">
                        <span class="meta-item">
                            <i class="bi bi-calendar3"></i> 2024
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-download"></i> 189
                        </span>
                    </div>
                    <a href="#" class="btn-detail mt-2">
                        LIHAT DETAIL <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Cards 5-16 dengan struktur yang sama --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <span class="badge-category">Atlas</span>
                    <img src="https://img.freepik.com/free-vector/indonesia-map-infographic-gradient-style_23-2149015999.jpg"
                         class="card-img-top katalog-img" alt="Peta">
                    <div class="img-overlay">
                        <button class="btn-quick-view"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 flex-grow-1">
                            <a href="#" class="title-link">Atlas_250K_MasaDemokrasiPar</a>
                        </h6>
                        <button class="btn-bookmark" title="Simpan"><i class="bi bi-bookmark"></i></button>
                    </div>
                    <p class="card-text text-muted small flex-grow-1">
                        Selepas dari masa RIS, Indonesia memasuki sistem demokrasi parlementer. Atlas ini menggambarkan kondisi geografis dan administratif Indonesia pada periode tersebut.
                    </p>
                    <div class="card-meta">
                        <span class="meta-item"><i class="bi bi-calendar3"></i> 2024</span>
                        <span class="meta-item"><i class="bi bi-download"></i> 156</span>
                    </div>
                    <a href="#" class="btn-detail mt-2">LIHAT DETAIL <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        {{-- Repeat similar structure for remaining cards 6-16 --}}
        {{-- Simplified for brevity - apply same pattern with appropriate data --}}

    </div>

    {{-- Pagination --}}
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Navigasi halaman">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</div>

{{-- Enhanced CSS Styling --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --border-radius: 12px;
        --transition: all 0.3s ease;
    }

    /* Search Wrapper */
    .search-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    .search-wrapper .input-group {
        border-radius: 50px;
        overflow: hidden;
        background: white;
    }

    .search-wrapper .input-group-text {
        border-right: none;
    }

    .search-wrapper .form-control {
        padding: 14px 0;
        font-size: 0.95rem;
        border-left: none;
        border-right: none;
    }

    .search-wrapper .form-control:focus {
        box-shadow: none;
    }

    .search-wrapper .btn-primary {
        border-radius: 0 50px 50px 0;
        font-weight: 500;
    }

    /* Filter Tabs */
    .filter-tabs {
        justify-content: center;
    }

    .filter-btn {
        padding: 10px 20px;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #495057;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
    }

    .filter-btn:hover {
        background: #f8f9fa;
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.15);
    }

    .filter-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    /* Card Styling */
    .katalog-card {
        transition: var(--transition);
        border-radius: var(--border-radius);
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
    }

    .katalog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-color);
    }

    /* Image Wrapper */
    .katalog-img-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 200px;
    }

    .badge-category {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(13, 110, 253, 0.9);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
        backdrop-filter: blur(10px);
    }

    .katalog-img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .katalog-card:hover .katalog-img {
        transform: scale(1.1) rotate(1deg);
    }

    .img-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
    }

    .katalog-card:hover .img-overlay {
        opacity: 1;
    }

    .btn-quick-view {
        background: white;
        border: none;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--primary-color);
        cursor: pointer;
        transform: scale(0.8);
        transition: var(--transition);
    }

    .katalog-card:hover .btn-quick-view {
        transform: scale(1);
    }

    .btn-quick-view:hover {
        transform: scale(1.1);
        background: var(--primary-color);
        color: white;
    }

    /* Card Body */
    .katalog-card .card-body {
        padding: 1.25rem;
        background: #fff;
    }

    .katalog-card .card-title {
        font-size: 0.95rem;
        line-height: 1.4;
        font-weight: 600;
    }

    .katalog-card .card-title .title-link {
        color: #1e293b;
        text-decoration: none;
        transition: var(--transition);
    }

    .katalog-card .card-title .title-link:hover {
        color: var(--primary-color);
    }

    .btn-bookmark {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 1.2rem;
        cursor: pointer;
        transition: var(--transition);
        padding: 0;
        margin-left: 8px;
    }

    .btn-bookmark:hover {
        color: #f59e0b;
        transform: scale(1.2);
    }

    .katalog-card .card-text {
        font-size: 0.85rem;
        line-height: 1.6;
        color: #64748b;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1rem;
        min-height: 4rem;
    }

    /* Card Meta */
    .card-meta {
        display: flex;
        gap: 16px;
        padding: 10px 0;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
    }

    .meta-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #64748b;
    }

    .meta-item i {
        font-size: 0.9rem;
        color: #94a3b8;
    }

    /* Button Detail */
    .btn-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 10px 16px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%);
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: 8px;
        transition: var(--transition);
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(13, 110, 253, 0.4);
        color: white;
    }

    .btn-detail i {
        transition: var(--transition);
    }

    .btn-detail:hover i {
        transform: translateX(4px);
    }

    /* Pagination */
    .pagination .page-link {
        border-radius: 8px;
        margin: 0 4px;
        border: 1px solid #e5e7eb;
        color: #64748b;
        font-weight: 500;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .pagination .page-link:hover {
        background: #f8f9fa;
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .katalog-img-wrapper {
            height: 180px;
        }
    }

    @media (max-width: 767px) {
        .katalog-img-wrapper {
            height: 160px;
        }

        .filter-tabs {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .filter-btn {
            white-space: nowrap;
        }

        .search-wrapper .form-control {
            font-size: 0.9rem;
            padding: 12px 0;
        }
    }

    @media (max-width: 576px) {
        .katalog-img-wrapper {
            height: 200px;
        }

        .card-meta {
            gap: 12px;
        }
    }
</style>

{{-- Optional JavaScript for Interactive Features --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter buttons functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Bookmark functionality
    const bookmarkBtns = document.querySelectorAll('.btn-bookmark');
    bookmarkBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-bookmark');
            icon.classList.toggle('bi-bookmark-fill');
        });
    });
});
</script>

@endsection