@extends('layouts.app')

@section('title', 'Katalog Data Survei Seismik')

@push('styles')
{{-- Tambahan CSS jika diperlukan --}}
@endpush

@section('content')
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 border-b-4 border-blue-500 inline-block pb-1">Katalog Data Survei</h1>
        <p class="text-xl text-gray-600 mb-12">Jelajahi, saring, dan unduh data survei geologi kelautan yang tersedia untuk umum.</p>

        {{-- Search and Filter Bar --}}
        <div class="mb-10 p-6 bg-white border border-gray-200 rounded-xl shadow-lg flex flex-col md:flex-row gap-4">
            <input type="text" placeholder="Cari berdasarkan judul, wilayah, atau tahun..." class="flex-grow p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150" aria-label="Search">
            
            <select class="p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 md:w-1/4 transition duration-150">
                <option value="">Filter Tipe Survei</option>
                <option value="seismik">Seismik Refleksi</option>
                <option value="magnetik">Magnetik</option>
                <option value="graviti">Gravitasi</option>
            </select>
            
            <button class="p-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition duration-150 shadow-md">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </div>

        {{-- Data Cards Grid --}}
        @if(empty($surveys)) 
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                {{-- Card Dummy 1 --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="p-5">
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded-full mb-2">Seismik 2D</span>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Survei Perairan Jawa Barat</h3>
                        <p class="text-sm text-gray-500 mb-4">Tahun: 2024 | Area: Laut Jawa</p>
                        <p class="text-gray-700 text-sm mb-4 line-clamp-3">Pengambilan data seismik resolusi tinggi untuk pemetaan struktur geologi dangkal di zona subduksi.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium flex items-center mt-2">
                            Lihat Detail 
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- Card Dummy 2 --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="p-5">
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-yellow-600 rounded-full mb-2">Magnetik</span>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pemetaan Anomali Sulawesi</h3>
                        <p class="text-sm text-gray-500 mb-4">Tahun: 2023 | Area: Sulawesi Tengah</p>
                        <p class="text-gray-700 text-sm mb-4 line-clamp-3">Data anomali magnetik yang digunakan untuk mengidentifikasi batuan dasar dan potensi mineral.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium flex items-center mt-2">
                            Lihat Detail 
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- Card Dummy 3 --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="p-5">
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded-full mb-2">Gravitasi</span>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Survei Selat Sunda</h3>
                        <p class="text-sm text-gray-500 mb-4">Tahun: 2022 | Area: Selat Sunda</p>
                        <p class="text-gray-700 text-sm mb-4 line-clamp-3">Data *free-air* dan *Bouguer anomaly* untuk pemodelan densitas bawah permukaan.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium flex items-center mt-2">
                            Lihat Detail 
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Placeholder Paginasi --}}
            <div class="mt-12 text-center">
                <p class="text-gray-500 text-sm">Placeholder Paginasi</p>
            </div>
        @else
            <div class="text-center py-20 bg-white border border-gray-200 rounded-xl shadow-inner">
                <i class="fas fa-database text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-xl font-medium">Tidak ada data survei yang ditemukan saat ini.</p>
            </div>
        @endif
    </div>
</section>
@endsection