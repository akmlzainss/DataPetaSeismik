{{-- resources/views/peta.blade.php --}}
@extends('layouts.app')

@section('title', 'Peta Interaktif Sebaran Data - BBSPGL')

@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miMGUIK2E/Yc6VGxw=" 
      crossorigin=""/>
{{-- Custom Map CSS --}}
<link rel="stylesheet" href="{{ asset('css/public-peta.css') }}">
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<section class="map-section">
    <div class="map-container">
        
        {{-- Header --}}
        <div class="map-header">
            <h1>Peta Sebaran Data Survei Seismik</h1>
            <p>Gunakan peta interaktif ini untuk menjelajahi lokasi dan tipe data survei yang tersedia di seluruh Indonesia.</p>
        </div>

        {{-- Statistics Cards --}}
        <div class="map-stats-grid">
            <div class="map-stat-card">
                <h4>Total Lokasi</h4>
                <div class="map-stat-value" id="totalLocations">-</div>
            </div>
            <div class="map-stat-card">
                <h4>Seismik 2D</h4>
                <div class="map-stat-value" id="total2D">-</div>
            </div>
            <div class="map-stat-card">
                <h4>Seismik 3D</h4>
                <div class="map-stat-value" id="total3D">-</div>
            </div>
            <div class="map-stat-card">
                <h4>Lainnya</h4>
                <div class="map-stat-value" id="totalOther">-</div>
            </div>
        </div>

        {{-- Search Box --}}
        <div class="map-search-box">
            <i class="fas fa-search map-search-icon"></i>
            <input 
                type="text" 
                id="mapSearch" 
                class="map-search-input" 
                placeholder="Cari lokasi survei...">
        </div>

        {{-- Map Controls --}}
        <div class="map-controls-card">
            <div class="controls-grid">
                <div class="control-group">
                    <label for="filterType">
                        <i class="fas fa-filter"></i> Filter Tipe Survei
                    </label>
                    <select id="filterType">
                        <option value="all">Semua Tipe</option>
                        <option value="Seismik">Seismik</option>
                        <option value="Magnetik">Magnetik</option>
                        <option value="Gravitasi">Gravitasi</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="control-group">
                    <label for="filterYear">
                        <i class="fas fa-calendar"></i> Filter Tahun
                    </label>
                    <select id="filterYear">
                        <option value="all">Semua Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                    </select>
                </div>
                <div class="control-group">
                    <label for="filterWilayah">
                        <i class="fas fa-map-marked-alt"></i> Filter Wilayah
                    </label>
                    <select id="filterWilayah">
                        <option value="all">Semua Wilayah</option>
                        <option value="sumatera">Sumatera</option>
                        <option value="jawa">Jawa</option>
                        <option value="kalimantan">Kalimantan</option>
                        <option value="sulawesi">Sulawesi</option>
                        <option value="papua">Papua</option>
                    </select>
                </div>
                <div class="control-group">
                    <label>
                        <i class="fas fa-sync-alt"></i> Aksi
                    </label>
                    <button 
                        id="resetFilters" 
                        style="padding: 0.625rem 0.875rem; background: linear-gradient(135deg, #003366 0%, #0066cc 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,51,102,0.3)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <i class="fas fa-undo"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- Map Canvas --}}
        <div class="map-canvas-wrapper">
            <div id="interactiveMap"></div>
            {{-- Loading Overlay (initially hidden) --}}
            <div id="mapLoadingOverlay" class="map-loading-overlay" style="display: none;">
                <div class="map-loading-spinner"></div>
                <div class="map-loading-text">Memuat peta...</div>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="map-info-alert">
            <i class="fas fa-info-circle"></i>
            <p>
                <strong>Cara menggunakan:</strong> Klik pada marker untuk melihat detail survei. Gunakan filter di atas untuk mempersempit pencarian. 
                Zoom in/out menggunakan tombol <strong>+</strong> dan <strong>-</strong> atau scroll mouse.
            </p>
        </div>

        {{-- Legend --}}
        <div class="map-legend-card">
            <h3>Legenda Tipe Survei</h3>
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-icon seismik"></div>
                    <span class="legend-text">Seismik</span>
                </div>
                <div class="legend-item">
                    <div class="legend-icon magnetik"></div>
                    <span class="legend-text">Magnetik</span>
                </div>
                <div class="legend-item">
                    <div class="legend-icon gravitasi"></div>
                    <span class="legend-text">Gravitasi</span>
                </div>
                <div class="legend-item">
                    <div class="legend-icon lainnya"></div>
                    <span class="legend-text">Lainnya</span>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

