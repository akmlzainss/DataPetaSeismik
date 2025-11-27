@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-5" style="max-width: 1320px;">
    
    {{-- Search Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <form action="#" method="GET" class="search-wrapper">
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" 
                           name="search" 
                           class="form-control border-0" 
                           placeholder="Cari data di katalog geoportal Badan Informasi Geospasial"
                           aria-label="Pencarian peta">
                    <button class="btn btn-light border-0" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-tabs d-flex flex-wrap gap-2">
                <button class="filter-btn active">Semua</button>
                <button class="filter-btn">Sungai</button>
                <button class="filter-btn">Jalan</button>
                <button class="filter-btn">CTSRT</button>
                <button class="filter-btn">Imagery</button>
                <button class="filter-btn">Batas</button>
            </div>
        </div>
    </div>

    {{-- Grid Card Section --}}
    <div class="row g-3">

        {{-- Card 1 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-pins_23-2147815236.jpg" 
                         class="card-img-top katalog-img" 
                         alt="Peta GPS Navigation">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">AAtlas_Sebaran_GunungApi</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Peta Persebaran Gunungapi ini dibangun menggunakan data dari berbagai sumber yang telah diverifikasi untuk memberikan informasi yang akurat dan terpercaya.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-infographic-gradient-style_23-2149015999.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta Topografi">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">ADMINISTRASI_AR_DESAKEL</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Geodatabase data batas wilayah administrasi desa/kelurahan di seluruh Indonesia yang mencakup informasi geografis lengkap untuk keperluan analisis dan perencanaan.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-administrative-divisions_23-2148715632.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta Isometrik">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">ADMINSITRASI_AR_KABKOTA</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Geodatabase data batas wilayah administrasi kabupaten/kota dengan detail lengkap mencakup koordinat geografis dan metadata terkait untuk berbagai keperluan pemetaan.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-pins_23-2147815236.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">Atlas_250K_BaluBara</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Data ini diperuntukkan sebagai materi web kartografi pada skala 1:250.000 yang menampilkan informasi persebaran batu bara di wilayah Indonesia dengan detail komprehensif.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 5 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-infographic-gradient-style_23-2149015999.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">Atlas_250K_MasaDemokrasiPar</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Selepas dari masa RIS, Indonesia memasuki sistem demokrasi parlementer. Atlas ini menggambarkan kondisi geografis dan administratif Indonesia pada periode tersebut.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 6 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-administrative-divisions_23-2148715632.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">Atlas_250K_MasaIndonesiaKlas</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Atlas masa Indonesia klasik. Data ini diperuntukkan sebagai referensi historis geospasial yang menampilkan kondisi geografis kepulauan Indonesia pada masa klasik.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 7 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-pins_23-2147815236.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">Atlas_250K_MasaKerajaanIslam</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Dunia pelayaran dan perdagangan yang berkembang pesat membawa pengaruh Islam ke Nusantara. Atlas ini menampilkan peta kerajaan-kerajaan Islam di Indonesia.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 8 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-infographic-gradient-style_23-2149015999.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">Atlas_250K_MasaKolonialEropa</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Atlas Kolonial Eropa. Peta waktu orang-orang Eropa masuk ke Indonesia dan mendirikan koloni di berbagai wilayah kepulauan Nusantara dengan detail geografis lengkap.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 9 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-administrative-divisions_23-2148715632.jpg" 
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">Atlas_250K_MasaPendudukan</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Peta masa pendudukan Jepang di Indonesia tahun 1942-1945 yang menggambarkan kondisi geografis dan administratif selama periode pendudukan tersebut berlangsung.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 10 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-pins_23-2147815236.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">Atlas_250K_PerkebunanNusantara</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Data perkebunan dan hasil bumi di wilayah Nusantara yang mencakup informasi detail tentang jenis tanaman, luas area, dan lokasi geografis perkebunan di Indonesia.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 11 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-infographic-gradient-style_23-2149015999.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">Atlas_250K_JalurPerdagangan</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Jalur perdagangan maritim dan darat di kepulauan Indonesia sejak zaman dahulu hingga modern, menampilkan rute-rute penting yang menghubungkan berbagai wilayah Nusantara.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 12 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-administrative-divisions_23-2148715632.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">BATAS_LN_LAUTLEPAS</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Geodatabase batas laut lepas wilayah Indonesia berdasarkan konvensi hukum laut internasional yang mencakup koordinat lengkap dan informasi batas maritim negara.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 13 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-pins_23-2147815236.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">BATAS_LN_ZEE</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Zona Ekonomi Eksklusif Indonesia sesuai hukum laut internasional yang mencakup wilayah maritim dengan hak kedaulatan untuk eksplorasi dan eksploitasi sumber daya alam.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 14 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-infographic-gradient-style_23-2149015999.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">INFRASTRUKTUR_LN_JALAN</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Jaringan jalan nasional, provinsi, dan kabupaten di Indonesia yang mencakup informasi detail tentang klasifikasi jalan, kondisi, dan konektivitas antar wilayah di seluruh Indonesia.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 15 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-administrative-divisions_23-2148715632.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">INFRASTRUKTUR_LN_SUNGAI</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Data sungai-sungai utama dan anak sungai di seluruh Indonesia dengan informasi lengkap tentang panjang sungai, daerah aliran, dan karakteristik hidrogeologi masing-masing sungai.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 16 --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card h-100 katalog-card">
                <div class="katalog-img-wrapper">
                    <img src="https://img.freepik.com/free-vector/indonesia-map-with-pins_23-2147815236.jpg"
                         class="card-img-top katalog-img" 
                         alt="Peta">
                </div>

                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2">
                        <a href="#" class="title-link">TOPONIMI_PT_IBUPROVINSI</a>
                    </h6>
                    <p class="card-text text-muted small flex-grow-1">
                        Lokasi ibukota provinsi seluruh Indonesia dengan koordinat geografis lengkap, informasi administratif, dan data pendukung lainnya untuk keperluan referensi dan analisis spasial.
                    </p>
                    <a href="#" class="btn-detail">
                        <i class="bi bi-bookmark"></i> LIHAT DETAIL
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- Enhanced CSS Styling --}}
<style>
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

    .search-wrapper .form-control {
        padding: 14px 24px;
        font-size: 0.95rem;
    }

    .search-wrapper .form-control:focus {
        box-shadow: none;
    }

    .search-wrapper .btn {
        padding: 0 24px;
        color: #6c757d;
    }

    /* Filter Tabs */
    .filter-tabs {
        justify-content: center;
    }

    .filter-btn {
        padding: 8px 20px;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #495057;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        background: #f8f9fa;
        border-color: #0d6efd;
    }

    .filter-btn.active {
        background: #e7f1ff;
        color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Card Styling */
    .katalog-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .katalog-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        border-color: #cbd5e1;
    }

    /* Image Wrapper */
    .katalog-img-wrapper {
        position: relative;
        overflow: hidden;
        background: #f1f5f9;
        height: 180px;
    }

    .katalog-img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .katalog-card:hover .katalog-img {
        transform: scale(1.08);
    }

    /* Card Body */
    .katalog-card .card-body {
        padding: 1.25rem;
        background: #fff;
    }

    .katalog-card .card-title {
        font-size: 0.95rem;
        margin-bottom: 0.65rem;
        line-height: 1.4;
        font-weight: 600;
    }

    .katalog-card .card-title .title-link {
        color: #2563eb;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .katalog-card .card-title .title-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    .katalog-card .card-text {
        font-size: 0.8rem;
        line-height: 1.5;
        color: #64748b;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.85rem;
        min-height: 3.6rem;
    }

    /* Button Detail */
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #2563eb;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        padding: 0.35rem 0;
    }

    .btn-detail:hover {
        color: #1d4ed8;
        gap: 8px;
    }

    .btn-detail i {
        font-size: 0.9rem;
    }

    /* Responsive Grid */
    @media (min-width: 1400px) {
        .col-xl-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }

    @media (max-width: 1399px) and (min-width: 992px) {
        .col-lg-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }

    @media (max-width: 991px) and (min-width: 768px) {
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 767px) {
        .katalog-img-wrapper {
            height: 150px;
        }

        .filter-tabs {
            justify-content: flex-start;
        }

        .search-wrapper .form-control {
            font-size: 0.9rem;
            padding: 12px 20px;
        }

        .col-sm-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 576px) {
        .col-sm-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .katalog-img-wrapper {
            height: 160px;
        }
    }
</style>

@endsection