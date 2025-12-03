@extends('layouts.app')

@section('title', 'Profil Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL)')

@section('content')
<section class="py-16 bg-white">
    <div class="container mx-auto px-6 lg:px-8 max-w-5xl">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6 border-b pb-3">Tentang BBSPGL</h1>
        
        <div class="prose max-w-none text-gray-700 space-y-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Sejarah Singkat</h2>
            <p class="lead text-xl font-medium text-gray-600">
                Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL) merupakan unit pelaksana teknis di bawah Badan Geologi, Kementerian Energi dan Sumber Daya Mineral (ESDM). Berdasarkan Peraturan Menteri ESDM Nomor 8 Tahun 2022, BBSPGL resmi bergabung dengan Badan Geologi, bertujuan untuk memperkuat tugas dan fungsi penyelidikan serta pelayanan geologi darat dan laut secara terintegrasi.
            </p>

            {{-- Visi Section --}}
            <div class="bg-blue-50 p-6 rounded-xl border-l-4 border-blue-600 shadow-sm">
                <h2 class="text-2xl font-bold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-eye mr-3"></i> Visi
                </h2>
                <p class="italic text-lg text-blue-700">
                    "Terwujudnya Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL) sebagai <span class="font-semibold">Center of Excellence (CoE)</span> di bidang geologi kelautan yang profesional, unggul, berdaya saing, dan bertaraf Internasional di bidang sumber daya energi, sumber daya mineral, infrastruktur, dan mitigasi kebencanaan kelautan."
                </p>
            </div>

            {{-- Misi Section --}}
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4 mt-8">Misi Kami</h2>
                <ul class="list-none space-y-4 pl-0">
                    <li class="flex items-start">
                        <i class="fas fa-chevron-right text-blue-600 mt-1 mr-3 text-lg"></i>
                        <span>Melaksanakan survei dan pemetaan geologi kelautan di bidang potensi sumber daya energi, sumber daya mineral, lingkungan, dan mitigasi kebencanaan geologi kelautan.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-chevron-right text-blue-600 mt-1 mr-3 text-lg"></i>
                        <span>Membantu merumuskan rekomendasi teknis di bidang sumber daya energi kelautan, mineral kelautan, lingkungan, mitigasi dan infrastruktur kelautan.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-chevron-right text-blue-600 mt-1 mr-3 text-lg"></i>
                        <span>Meningkatkan kualitas jasa survei dan pemetaan geologi kelautan yang mampu mendukung industri kelautan.</span>
                    </li>
                </ul>
            </div>
            
            {{-- Tugas Pokok & Fungsi --}}
            <div class="pt-4">
                <h2 class="text-3xl font-bold text-gray-800 mb-4 border-t pt-4">Tugas Pokok & Fungsi Utama</h2>

                <div class="bg-yellow-50 p-6 rounded-xl border-l-4 border-yellow-600 shadow-sm mb-6">
                    <h3 class="text-xl font-bold text-yellow-800 mb-2 flex items-center">
                        <i class="fas fa-tasks mr-3"></i> Tugas Pokok
                    </h3>
                    <p class="text-lg text-yellow-700">
                        Melaksanakan survei dan pemetaan di bidang geologi kelautan.
                    </p>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 mb-4">Fungsi Pelaksanaan:</h3>
                <ul class="list-decimal space-y-3 pl-6 text-gray-700">
                    <li>Pelaksanaan survei dan pemetaan di bidang geologi kelautan.</li>
                    <li>Pengelolaan data dan informasi teknis geologi kelautan.</li>
                    <li>Pelayanan jasa survei dan pemetaan di bidang geologi kelautan.</li>
                    <li>Pengelolaan sarana dan prasarana survei dan pemetaan di bidang geologi kelautan.</li>
                    <li>Pelaksanaan urusan hukum dan kerja sama.</li>
                    <li>Pelaksanaan ketatausahaan dan administrasi kelembagaan.</li>
                </ul>
            </div>
            
            {{-- Struktur Organisasi --}}
            <div class="pt-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4 border-t pt-4">Struktur Organisasi</h2>
                <div class="p-6 bg-gray-100 rounded-xl text-center border border-gray-300">
                    <i class="fas fa-sitemap text-5xl text-gray-500 mb-3"></i>
                    <p class="text-gray-700">Struktur organisasi Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL).</p>
                                    </div>
            </div>
        </div>
    </div>
</section>
@endsection