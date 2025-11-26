@extends('layouts.app')

@section('title', 'Peta Interaktif - BBSPGL')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 80vh; width: 100%; border: 4px solid #ca8a04; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .sidebar { width: 340px; background: rgba(0,0,0,0.92); backdrop-filter: blur(12px); color: #fbbf24; padding: 2rem; border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.6); border: 2px solid #facc15; }
    .search-input { background: rgba(255,255,255,0.1); border: 2px solid #facc15; color: white; border-radius: 12px; padding: 1rem; }
    .search-input::placeholder { color: #fcd34d; }
    .search-input:focus { outline: none; background: rgba(255,255,255,0.2); box-shadow: 0 0 15px #facc15; }
    .province-item { padding: 1rem; margin: 0.5rem 0; border-radius: 12px; cursor: pointer; transition: all 0.3s; background: rgba(255,255,255,0.05); }
    .province-item:hover { background: #facc15 !important; color: black !important; font-weight: bold; transform: translateY(-2px); }
    .legend { background: rgba(0,0,0,0.7); padding: 1rem; border-radius: 12px; border: 1px solid #facc15; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-6 py-10">
    <h1 class="text-5xl font-bold text-yellow-500 text-center mb-10 drop-shadow-2xl">
        Peta Wilayah Survei BBSPGL
    </h1>

    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2 class="text-3xl font-bold mb-6 text-yellow-400 flex items-center gap-3">
                <i class="fas fa-search"></i> Cari Provinsi
            </h2>
            <input type="text" id="searchProvinsi" class="w-full search-input text-lg" placeholder="Ketik nama provinsi...">

            <div id="daftarProvinsi" class="mt-6 space-y-2 max-h-96 overflow-y-auto"></div>

            <div class="legend mt-10 text-lg">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full border-2 border-black"></div>
                    <span>Wilayah pernah disurvei</span>
                </div>
            </div>
        </div>

        <!-- Peta -->
        <div class="flex-1">
            <div id="map"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// === DATA SURVEI – DIOLAH DULU DI PHP AGAR AMAN ===
@php
    $markers = $surveis->filter(function($s) {
        return $s->lokasi && $s->lokasi->pusat_lintang && $s->lokasi->pusat_bujur;
    })->map(function($s) {
        return [
            'judul'   => $s->judul,
            'wilayah' => $s->wilayah,
            'tahun'   => $s->tahun,
            'lat'     => (float)$s->lokasi->pusat_lintang,
            'lng'     => (float)$s->lokasi->pusat_bujur,
        ];
    })->values()->all();
@endphp

const dataSurvei = @json($markers);

// === INIT PETA ===
const map = L.map('map').setView([-2.5, 118], 5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap | BBSPGL'
}).addTo(map);

const markerLayer = L.layerGroup().addTo(map);

// Tambah marker
dataSurvei.forEach(item => {
    L.circleMarker([item.lat, item.lng], {
        radius: 12,
        fillColor: "#facc15",
        color: "#000",
        weight: 3,
        fillOpacity: 0.95
    }).addTo(markerLayer)
      .bindPopup(`<b class="text-black text-lg">${item.judul}</b><br>Wilayah: ${item.wilayah}<br>Tahun: ${item.tahun}`);
});

// Load GeoJSON Provinsi
fetch('https://raw.githubusercontent.com/superpikar/indonesia-geojson/master/indonesia-province.geojson')
    .then(r => r.json())
    .then(geojson => {
        L.geoJSON(geojson, {
            style: { color: "#666", weight: 1.5, fillOpacity: 0.08 },
            onEachFeature: function(feature, layer) {
                const namaProv = feature.properties.Propinsi || 
                                feature.properties.PROVINSI || 
                                feature.properties.province || 
                                "Tidak Diketahui";

                const adaSurvei = dataSurvei.some(s => 
                    s.wilayah && s.wilayah.toLowerCase().includes(namaProv.toLowerCase())
                );

                if (adaSurvei) {
                    layer.setStyle({
                        fillColor: '#facc15',
                        fillOpacity: 0.55,
                        color: '#ca8a04',
                        weight: 4
                    });
                }

                layer.bindTooltip(namaProv, { direction: "center" });

                layer.on('click', () => map.fitBounds(layer.getBounds()));

                // Tambah ke sidebar
                const div = document.createElement('div');
                div.className = 'province-item text-lg';
                if (adaSurvei) div.classList.add('bg-yellow-600', 'text-black', 'font-bold');
                div.textContent = namaProv;
                div.onclick = () => {
                    map.fitBounds(layer.getBounds());
                    if (adaSurvei) {
                        layer.setStyle({ fillOpacity: 0.8, weight: 6 });
                        setTimeout(() => layer.setStyle({ fillOpacity: 0.55, weight: 4 }), 2000);
                    }
                };
                document.getElementById('daftarProvinsi').appendChild(div);
            }
        }).addTo(map);
    });

// Pencarian provinsi
document.getElementById('searchProvinsi').addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.province-item').forEach(el => {
        el.style.display = el.textContent.toLowerCase().includes(q) ? 'block' : 'none';
    });
});
</script>
@endsection