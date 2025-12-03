@extends('layouts.app')

@section('title', 'Peta Interaktif Sebaran Data')

@push('styles')
{{-- Leaflet CSS diperlukan untuk peta interaktif --}}
<link rel="stylesheet" href="[https://unpkg.com/leaflet@1.9.4/dist/leaflet.css](https://unpkg.com/leaflet@1.9.4/dist/leaflet.css)" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/SJPMe+KoTsw/yU/2y/" crossorigin=""/>
<style>
    #interactiveMap { 
        height: 80vh; 
        width: 100%; 
        border-radius: 12px; 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        z-index: 0;
    }
</style>
@endpush

@section('content')
<section class="py-16 bg-white">
    <div class="container mx-auto px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Peta Sebaran Data Survei</h1>
        <p class="text-xl text-gray-600 mb-10">Gunakan peta interaktif ini untuk menjelajahi lokasi dan tipe data yang tersedia.</p>

        {{-- Map Container --}}
        <div id="interactiveMap" class="mb-8"></div>
        
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-800 flex items-center">
            <i class="fas fa-info-circle mr-3 text-lg"></i> 
            <p class="text-sm">Untuk menampilkan peta, fungsi JavaScript di bawah perlu dimuat (menggunakan Leaflet.js).</p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
{{-- Leaflet JS --}}
<script src="[https://unpkg.com/leaflet@1.9.4/dist/leaflet.js](https://unpkg.com/leaflet@1.9.4/dist/leaflet.js)" integrity="sha256-20n6Xy+FR2/p5E5T3h1/i3yC4f8y0n0N0qX8qN0n0qE=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Peta (Contoh: Menuju pusat Indonesia, Zoom 5)
        const map = L.map('interactiveMap').setView([-2.5489, 118.0149], 5); 

        // Tile Layer (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="[https://www.openstreetmap.org/copyright](https://www.openstreetmap.org/copyright)">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);

        // Data Marker Contoh
        const surveyLocations = [
            { lat: -6.1751, lon: 106.8650, title: 'Survei Jakarta', type: 'Seismik', year: 2024 },
            { lat: -7.2575, lon: 112.7521, title: 'Survei Surabaya', type: 'Magnetik', year: 2023 },
            { lat: -3.3167, lon: 114.5902, title: 'Survei Banjarmasin', type: 'Gravitasi', year: 2022 },
        ];

        // Loop untuk menambahkan Marker
        surveyLocations.forEach(location => {
            L.marker([location.lat, location.lon])
                .addTo(map)
                .bindPopup(`
                    <div class="font-sans">
                        <h4 class="font-bold text-base">${location.title}</h4>
                        <p class="text-sm">Tipe: ${location.type}</p>
                        <p class="text-sm">Tahun: ${location.year}</p>
                        <a href="#" class="text-blue-600 text-sm font-medium">Lihat Detail</a>
                    </div>
                `);
        });
    });
</script>
@endpush