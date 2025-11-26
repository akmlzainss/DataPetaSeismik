@extends('layouts.app')

@section('title', 'Peta Interaktif - BBSPGL')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .peta-container {
        margin-top: 6rem;
        padding: 1rem;
        min-height: 100vh;
        background: #f5f7fa;
    }

    #map {
        height: 85vh;
        border-radius: 20px;
        border: 6px solid #e91e63;
        box-shadow: 0 15px 40px rgba(233, 30, 99, 0.35);
        overflow: hidden;
    }

    .map-title {
        text-align: center;
        font-size: 2.3rem;
        font-weight: bold;
        color: #e91e63;
        margin-bottom: 20px;
        text-shadow: 0 0 10px rgba(0,0,0,0.35);
    }

    /* Tombol modern */
    .zoom-toggle {
        position: absolute;
        bottom: 30px;
        right: 30px;
        z-index: 2000;
        padding: 12px 22px;
        background: #e91e63;
        color: white;
        border-radius: 40px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        box-shadow: 0 0 25px rgba(233, 30, 99, 0.5);
        transition: .25s;
    }
    .zoom-toggle:hover {
        transform: scale(1.05);
        box-shadow: 0 0 35px rgba(233, 30, 99, 0.8);
    }

</style>
@endsection



@section('content')
<div class="peta-container">
    
    <h1 class="map-title">PETA INDONESIA - BBSPGL</h1>

    <div class="relative">
        <div id="map"></div>

        <button id="zoomBtn" class="zoom-toggle">
            🔒 Zoom Mode OFF
        </button>
    </div>

</div>
@endsection



@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
@php
    $markers = $surveis->filter(fn($s) => $s->lokasi && $s->lokasi->pusat_lintang && $s->lokasi->pusat_bujur)
        ->map(fn($s) => [
            'judul' => $s->judul,
            'lat'   => (float)$s->lokasi->pusat_lintang,
            'lng'   => (float)$s->lokasi->pusat_bujur,
        ])->values()->all();
@endphp
const markers = @json($markers);

// ====================================
// 1. INISIALISASI PETA NORMAL (CERAH)
// ====================================
const map = L.map('map', {
    zoomControl: true,
    attributionControl: false,
    minZoom: 5,
    maxZoom: 10,
    maxBounds: [[10, 90], [-15, 145]],
    maxBoundsViscosity: 1.0
}).setView([-2.5, 118], 5);

// Basemap CERAH / NORMAL (Stadia Maps Light)
L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png', {
    maxZoom: 10
}).addTo(map);


// ====================================
// 2. GRID Lintang Bujur
// ====================================
L.latlngGraticule({
    showLabel: true,
    color: '#e91e63',
    weight: 2,
    opacity: 0.7,
    font: "14px Arial",
    fontColor: "#e91e63",
    zoomInterval: [{start: 5, end: 10, interval: 5}]
}).addTo(map);


// ====================================
// 3. GEOJSON INDONESIA
// ====================================
fetch('https://raw.githubusercontent.com/superpikar/indonesia-geojson/master/indonesia-province.geojson')
    .then(r => r.json())
    .then(data => {
        L.geoJSON(data, {
            style: {
                color: "#e91e63",
                weight: 4,
                fillColor: "#ffe5f0",
                fillOpacity: 0.4
            }
        }).addTo(map);
    });


// ====================================
// 4. TITIK SURVEI
// ====================================
markers.forEach(m => {
    L.circleMarker([m.lat, m.lng], {
        radius: 8,
        fillColor: "#ff1744",
        color: "#000",
        weight: 2,
        fillOpacity: 1
    }).addTo(map)
      .bindPopup(`<b class="text-red-600 text-lg">${m.judul}</b>`);
});


// ====================================
// 5. LABEL LAUT BESAR
// ====================================
const laut = [
    {t: "LAUT JAWA", lat: -6.5, lng: 110},
    {t: "LAUT BALI", lat: -8.4, lng: 115.5},
    {t: "LAUT FLORES", lat: -8.5, lng: 121},
    {t: "LAUT BANDA", lat: -4.5, lng: 129},
    {t: "LAUT SULAWESI", lat: 2.5, lng: 123},
    {t: "LAUT MALUKU", lat: 0, lng: 127},
    {t: "LAUT TIMOR", lat: -10, lng: 125},
    {t: "LAUT ARAFURA", lat: -7, lng: 135},
    {t: "SAMUDRA HINDIA", lat: -12, lng: 105}
];

laut.forEach(l => {
    L.marker([l.lat, l.lng], {
        icon: L.divIcon({
            className: "text-xl font-bold text-blue-900",
            html: l.t,
            iconSize: [200, 40],
            iconAnchor: [100, 20]
        })
    }).addTo(map);
});


// ====================================
// ⭐ LOGIKA ZOOM MODE (ANTI NAVBAR KETUTUP)
// ====================================
let zoomActive = false;

const zoomBtn = document.getElementById("zoomBtn");

// AWAL: Zoom Scroll Disabled
map.scrollWheelZoom.disable();

function setZoomMode(active) {
    zoomActive = active;

    if (active) {
        map.scrollWheelZoom.enable();
        zoomBtn.textContent = "🔓 Zoom Mode ON";
    } else {
        map.scrollWheelZoom.disable();
        zoomBtn.textContent = "🔒 Zoom Mode OFF";
    }
}

// Toggle tombol
zoomBtn.addEventListener("click", () => {
    setZoomMode(!zoomActive);
});

// Auto nonaktif saat mouse keluar map
map.on("mouseout", () => setZoomMode(false));

</script>
@endsection
