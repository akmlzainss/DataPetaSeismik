{{-- resources/views/user/peta.blade.php --}}
@extends('layouts.app') {{-- atau layouts.user kalau kamu punya --}}

@section('title', 'Peta Survei Seismik Nasional - BBSPGL')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-white py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-bold text-[#003366] mb-4">
                Peta Interaktif Survei Seismik Indonesia
            </h1>
            <p class="text-xl text-gray-700">Badan Geologi - BBSPGL Energi dan Sumber Daya Mineral</p>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl p-6">
            <div id="map" class="h-96 md:h-screen rounded-2xl"></div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Total <strong>{{ $markers->count() }}</strong> lokasi survei seismik telah ditandai
                    <span class="text-green-600">• Update real-time</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
    #map { height: 80vh; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('map').setView([-2.5, 118], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const markers = L.markerClusterGroup({
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        maxClusterRadius: 50
    });

    @foreach($markers as $m)
        const marker = L.marker([{{ $m->pusat_lintang }}, {{ $m->pusat_bujur }}], {
            icon: L.divIcon({
                html: '<div style="background:#003366;color:#FFD700;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold;border:3px solid #FFD700;">{{ $m->survei->tipe }}</div>',
                className: 'custom-marker',
                iconSize: [32, 32]
            })
        });

        marker.bindPopup(`
            <div class="text-center p-2">
                <h3 class="font-bold text-[#003366] text-lg">{{ addslashes($m->survei->judul) }}</h3>
                <p class="text-sm"><strong>Tahun:</strong> {{ $m->survei->tahun }}</p>
                <p class="text-sm"><strong>Tipe:</strong> {{ $m->survei->tipe }}</p>
                <p class="text-sm"><strong>Wilayah:</strong> {{ $m->survei->wilayah }}</p>
                <hr class="my-2">
                <a href="{{ route('survei.show', $m->survei->id) }}" 
                   class="inline-block bg-[#003366] text-[#FFD700] px-4 py-2 rounded-lg font-bold hover:bg-[#002244] transition">
                   Lihat Detail Survei →
                </a>
            </div>
        `);

        markers.addLayer(marker);
    @endforeach

    map.addLayer(markers);
    map.fitBounds(markers.getBounds().pad(0.1));
});
</script>
@endpush