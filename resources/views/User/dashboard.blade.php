@extends('layouts.app')

@section('styles')
<style>
    .hero-bg {
        background-image: url('{{ asset('images/bg.jpg') }}');
        background-size: cover;
        background-position: center;
        position: relative;
    }
    .hero-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.7));
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-bg h-screen flex items-center justify-center text-white relative">
    <div class="hero-overlay absolute inset-0"></div>
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-6 drop-shadow-lg">
            Geologi Kelautan
        </h1>
        <p class="text-xl md:text-2xl mb-8 drop-shadow">
            Platform terpadu penyedia data dan informasi geologi kelautan
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-12">
            <a href="#data-survei" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-8 rounded-full text-lg transition">
                Jelajahi Data Survei
            </a>
            <a href="#" class="border-2 border-white hover:bg-white hover:text-gray-800 text-white font-semibold py-4 px-8 rounded-full text-lg transition">
                Tentang Kami
            </a>
        </div>
    </div>
    <button class="absolute left-6 top-1/2 transform -translate-y-1/2 text-white text-4xl opacity-70 hover:opacity-100">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="absolute right-6 top-1/2 transform -translate-y-1/2 text-white text-4xl opacity-70 hover:opacity-100">
        <i class="fas fa-chevron-right"></i>
    </button>
</section>

<!-- Section Data Survei -->
<section id="data-survei" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Data Survei Tersedia</h2>

        @if($surveis->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($surveis as $survei)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        @if($survei->gambar_pratinjau)
                            <img src="{{ Storage::url($survei->gambar_pratinjau) }}" alt="{{ $survei->judul }}" class="w-full h-48 object-cover">
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-t-lg w-full h-48 flex items-center justify-center">
                                <span class="text-gray-500">Tidak ada gambar</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ $survei->judul }}</h3>
                            <p class="text-gray-600 mb-3">
                                <strong>Tahun:</strong> {{ $survei->tahun }} | 
                                <strong>Tipe:</strong> {{ strtoupper($survei->tipe) }} | 
                                <strong>Wilayah:</strong> {{ $survei->wilayah }}
                            </p>
                            <p class="text-gray-700 text-sm mb-4 line-clamp-3">
                                {{ $survei->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>
                            <div class="flex justify-between items-center">
                                <a href="#" class="text-blue-600 hover:underline font-medium">Lihat Detail →</a>
                                @if($survei->tautan_file)
                                    <a href="{{ $survei->tautan_file }}" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-600 text-lg">Belum ada data survei yang diunggah.</p>
        @endif
    </div>
</section>
@endsection