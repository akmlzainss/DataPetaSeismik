@extends('layouts.app')

@section('title', 'Beranda - BBSPGL')

@section('styles')
<style>
    .hero-bg {
        background-image: url('{{ asset('images/bg.jpg') }}');
        background-size: cover;
        background-position: center;
        position: relative;
        min-height: 70vh;
    }
    .hero-overlay {
        background: linear-gradient(to bottom, rgba(0, 74, 153, 0.45), rgba(0, 0, 0, 0.65));
    }
    /* line-clamp untuk deskripsi (Tailwind line-clamp memerlukan plugin; ini fallback CSS sederhana) */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-bg flex items-center justify-center text-white relative">
    <div class="hero-overlay absolute inset-0"></div>
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto py-16">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">
            Geologi Kelautan
        </h1>
        <p class="text-lg md:text-2xl mb-6 drop-shadow">
            Platform terpadu penyedia data dan informasi geologi kelautan
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-6">
            <a href="#data-survei" class="bg-[#004A99] hover:bg-[#003970] text-white font-semibold py-3 px-6 rounded-full text-lg transition">
                Jelajahi Data Survei
            </a>
            <a href="#" class="border-2 border-white hover:bg-white hover:text-[#004A99] text-white font-semibold py-3 px-6 rounded-full text-lg transition">
                Tentang Kami
            </a>
        </div>
    </div>

    <!-- Carousel nav (visual only) -->
    <button class="absolute left-6 top-1/2 transform -translate-y-1/2 text-white text-3xl opacity-80 hover:opacity-100">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="absolute right-6 top-1/2 transform -translate-y-1/2 text-white text-3xl opacity-80 hover:opacity-100">
        <i class="fas fa-chevron-right"></i>
    </button>
</section>

<!-- Section Data Survei -->
<section id="data-survei" class="py-16 bg-gray-100">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-10 text-[#004A99]">Data Survei Tersedia</h2>

        @if($surveis->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($surveis as $survei)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        @if($survei->gambar_pratinjau)
                            <img src="{{ Storage::url($survei->gambar_pratinjau) }}" alt="{{ $survei->judul }}" class="w-full h-44 object-cover">
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-t-lg w-full h-44 flex items-center justify-center">
                                <span class="text-gray-500">Tidak ada gambar</span>
                            </div>
                        @endif

                        <div class="p-5">
                            <h3 class="text-lg font-semibold mb-1 text-[#004A99]">{{ $survei->judul }}</h3>
                            <p class="text-gray-600 mb-3 text-sm">
                                <strong>Tahun:</strong> {{ $survei->tahun ?? '-' }} &nbsp;|&nbsp;
                                <strong>Tipe:</strong> {{ strtoupper($survei->tipe ?? '-') }} &nbsp;|&nbsp;
                                <strong>Wilayah:</strong> {{ $survei->wilayah ?? '-' }}
                            </p>
                            <p class="text-gray-700 text-sm mb-4 line-clamp-3">
                                {{ $survei->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <div class="flex justify-between items-center">
                                <a href="{{ route('survei.show', $survei->id) ?? '#' }}" class="text-[#004A99] hover:underline font-medium text-sm">Lihat Detail →</a>

                                @if($survei->tautan_file)
                                    <a href="{{ $survei->tautan_file }}" target="_blank"
                                       class="bg-[#FDB813] text-black px-3 py-2 rounded hover:bg-yellow-400 text-sm font-semibold">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{-- Pagination (jika menggunakan paginate pada controller) --}}
                {{ $surveis->links() }}
            </div>
        @else
            <p class="text-center text-gray-600 text-lg">Belum ada data survei yang diunggah.</p>
        @endif
    </div>
</section>
@endsection
