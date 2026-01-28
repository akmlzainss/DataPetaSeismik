@extends('layouts.app')

@section('title', 'Peta Sebaran Survei - Data Peta Seismik')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/public-peta.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Grid Label - Properly centered */
        .grid-label {
            background: none !important;
            border: none !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 40px !important;
            height: 20px !important;
        }
        
        .grid-label span {
            font-size: 9px;
            font-weight: 600;
            color: #333;
            white-space: nowrap;
            text-shadow: 
                1px 1px 0 #fff,
                -1px -1px 0 #fff,
                1px -1px 0 #fff,
                -1px 1px 0 #fff;
        }
        
        /* Hidden state saat zoom out */
        .grid-label.hidden {
            display: none !important;
        }
        
        /* ============================================= */
        /* GRID POPUP STYLING - IMPROVED VERSION */
        /* ============================================= */
        
        .leaflet-popup-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .leaflet-popup-content-wrapper {
            padding: 0 !important;
            border-radius: 10px !important;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
        }
        
        .grid-popup-content {
            min-width: 300px;
            max-width: 360px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Header Popup */
        .grid-popup-header {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: white;
            padding: 14px 18px;
            border-bottom: 3px solid #ffd700;
        }
        
        .grid-popup-header h4 {
            margin: 0 0 4px 0;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .grid-popup-status {
            font-size: 13px;
            opacity: 0.95;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .grid-popup-status i {
            font-size: 11px;
        }
        
        /* Survei List Container */
        .grid-survei-list {
            max-height: 280px;
            overflow-y: auto;
            padding: 12px;
            background: #f8fafc;
        }
        
        /* Custom Scrollbar untuk list */
        .grid-survei-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .grid-survei-list::-webkit-scrollbar-track {
            background: #e9ecef;
            border-radius: 3px;
        }
        
        .grid-survei-list::-webkit-scrollbar-thumb {
            background: #003366;
            border-radius: 3px;
        }
        
        .grid-survei-list::-webkit-scrollbar-thumb:hover {
            background: #004d99;
        }
        
        /* Individual Survey Item */
        .grid-survei-item {
            padding: 12px 14px;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            margin-bottom: 10px;
            background: white;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .grid-survei-item:last-child {
            margin-bottom: 0;
        }
        
        .grid-survei-item:hover {
            background: #f0f7ff;
            border-color: #0077cc;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }
        
        /* Survey Title */
        .grid-survei-title {
            font-weight: 700;
            color: #003366;
            font-size: 14px;
            margin-bottom: 10px;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Survey Meta Info */
        .grid-survei-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 10px;
            font-size: 12px;
            color: #5a6c7d;
            margin-bottom: 12px;
        }
        
        .grid-survei-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0f4f8;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: #495057;
        }
        
        .grid-survei-meta i {
            font-size: 11px;
            color: #003366;
        }
        
        /* Detail Button - Fixed Blue Background */
        .grid-popup-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 16px;
            background: #003366 !important;
            color: white !important;
            text-decoration: none !important;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            margin-top: 4px;
        }
        
        .grid-popup-btn:hover {
            background: #004d99 !important;
            color: white !important;
            text-decoration: none !important;
        }
        
        .grid-popup-btn:visited {
            color: white !important;
        }
        
        .grid-popup-btn i {
            font-size: 12px;
        }
        
        /* Empty State */
        .grid-empty-message {
            color: #6c757d;
            font-style: italic;
            text-align: center;
            padding: 24px 16px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        
        .grid-empty-message i {
            font-size: 24px;
            color: #adb5bd;
        }
        
        /* Multiple data indicator badge */
        .survei-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffd700;
            color: #003366;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            margin-left: 8px;
        }

        /* Leaflet Popup Close Button Styling */
        .leaflet-container a.leaflet-popup-close-button {
            top: 12px;
            right: 12px;
            color: white !important;
            font-size: 24px;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            z-index: 999;
            transition: all 0.2s ease;
        }

        .leaflet-container a.leaflet-popup-close-button:hover {
            color: #ffd700 !important; /* Kuning emas saat hover */
            transform: scale(1.1);
        }
    </style>
@endpush

@section('content')
    <div class="peta-hero">
        <div class="container-custom">
            <h1 class="hero-title">Peta Sebaran Survei Geologi</h1>
            <p class="hero-subtitle">Visualisasi interaktif lokasi dan data hasil survei geologi kelautan di seluruh wilayah
                Indonesia menggunakan sistem grid.</p>
        </div>
    </div>

    <div class="peta-content-wrapper">
        <div class="container-custom">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-blue">
                        <i class="fas fa-th"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['total_grid'] }}</div>
                        <div class="stat-label">Total Grid</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-green">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['total_survei'] }}</div>
                        <div class="stat-label">Data Survei</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-orange">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['survei_terpetakan'] ?? 0 }}</div>
                        <div class="stat-label">Survei Terpetakan</div>
                    </div>
                </div>
            </div>

            <!-- Map & Info Layout -->
            <div class="map-layout">
                <!-- Main Map -->
                <div class="map-container-box">
                    <div class="map-header">
                        <h2 class="section-title"><i class="fas fa-globe-asia"></i> Eksplorasi Peta</h2>
                        <div class="map-legend">
                            <span class="legend-item"><span class="dot" style="background: #8fbc8f;"></span> Grid Terisi</span>
                            <span class="legend-item"><span class="dot" style="background: #fff; border: 1px solid #999;"></span> Grid Kosong</span>
                        </div>
                    </div>
                    
                    {{-- Filter Controls --}}
                    <div class="map-filter-controls" style="padding: 12px 16px; background: #f8f9fa; border-bottom: 1px solid #e9ecef; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                        <label style="font-weight: 600; font-size: 14px; color: #003366;">
                            <i class="fas fa-filter"></i> Filter:
                        </label>
                        <select id="filterTipe" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-width: 120px;">
                            <option value="">Semua Tipe</option>
                            <option value="2D">2D</option>
                            <option value="3D">3D</option>
                            <option value="HR">HR</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <select id="filterStatus" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-width: 140px;">
                            <option value="">Semua Grid</option>
                            <option value="filled">Grid Terisi</option>
                            <option value="empty">Grid Kosong</option>
                        </select>
                        <button type="button" id="applyFilterBtn" style="padding: 8px 16px; background: #003366; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer;">
                            <i class="fas fa-search"></i> Terapkan
                        </button>
                        <button type="button" id="resetFilterBtn" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer;">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <span id="filterStatusText" style="font-size: 13px; color: #666;"></span>
                    </div>
                    <div class="map-frame">
                        <div id="map">
                            <div class="loading-overlay" id="loading-overlay">
                                <div class="loading-spinner"></div>
                                <div class="loading-text">Memuat data geospasial...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="map-sidebar">
                    <div class="info-card">
                        <h3 class="info-card-title">Tentang Peta Ini</h3>
                        <p class="info-text">
                            Peta ini menyajikan distribusi spasial dari kegiatan survei seismik dan geologi kelautan yang
                            telah dilaksanakan oleh BBSPGL.
                        </p>
                        <p class="info-text">
                            Setiap kotak grid pada peta merepresentasikan area spesifik di mana data survei tersedia.
                        </p>
                    </div>

                    <div class="info-card">
                        <h3 class="info-card-title">Panduan Interaksi</h3>
                        <ul class="guide-list">
                            <li>
                                <div class="guide-icon"><i class="fas fa-mouse-pointer"></i></div>
                                <span><strong>Klik Grid</strong> untuk melihat daftar survei dalam area tersebut.</span>
                            </li>
                            <li>
                                <div class="guide-icon"><i class="fas fa-external-link-alt"></i></div>
                                <span><strong>Lihat Detail</strong> pada popup untuk mengakses data lengkap.</span>
                            </li>
                            <li>
                                <div class="guide-icon"><i class="fas fa-search-plus"></i></div>
                                <span><strong>Zoom In/Out</strong> untuk melihat detail lokasi yang lebih spesifik.</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="info-card">
                        <h3 class="info-card-title">Statistik Tipe Data</h3>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="display: flex; justify-content: space-between; padding: 8px; background: #e3f2fd; border-radius: 4px;">
                                <span><strong>2D</strong></span>
                                <span>{{ $stats['tipe_data']['2D'] ?? 0 }} survei</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px; background: #e8f5e9; border-radius: 4px;">
                                <span><strong>3D</strong></span>
                                <span>{{ $stats['tipe_data']['3D'] ?? 0 }} survei</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 8px; background: #fff3e0; border-radius: 4px;">
                                <span><strong>HR</strong></span>
                                <span>{{ $stats['tipe_data']['HR'] ?? 0 }} survei</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Grid-based map initializing...');

            // Data grids dari controller
            const gridsData = @json($grids);

            console.log('Grids data loaded:', gridsData);
            console.log('Grids count:', gridsData.length);

            // BATAS PETA ASIA TENGGARA (MAKSIMAL ZOOM OUT)
            // Mencakup: Myanmar, Thailand, Vietnam, Filipina, Malaysia, Indonesia, Australia Utara
            const seaBounds = L.latLngBounds(
                L.latLng(-20.0, 75.0), // South-West (mencakup Sri Lanka/India selatan hingga Australia utara)
                L.latLng(25.0, 155.0) // North-East (mencakup China selatan, Jepang selatan, Papua)
            );

            // Initialize map - Default view di Indonesia, tapi bisa zoom out ke Asia Tenggara
            const map = L.map('map', {
                maxBounds: seaBounds,
                maxBoundsViscosity: 1.0,
                minZoom: 4,  // Zoom out sedang (tidak terlalu jauh)
                maxZoom: 18,
                zoomControl: true
            }).setView([-2.5, 118], 5); // Default: Pusat Indonesia, zoom level 5

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Hide loading overlay after map is initialized
            setTimeout(() => {
                document.getElementById('loading-overlay').style.display = 'none';
            }, 300);

            // Store grid rectangles and labels
            let gridRectangles = [];
            let gridLabels = [];
            
            // Zoom threshold untuk tampilkan label
            const LABEL_ZOOM_THRESHOLD = 5;
            
            // Function untuk toggle label visibility berdasarkan zoom
            function updateLabelsVisibility() {
                const currentZoom = map.getZoom();
                const showLabels = currentZoom >= LABEL_ZOOM_THRESHOLD;
                
                gridLabels.forEach(marker => {
                    const el = marker.getElement();
                    if (el) {
                        if (showLabels) {
                            el.classList.remove('hidden');
                        } else {
                            el.classList.add('hidden');
                        }
                    }
                });
            }
            
            // Listen to zoom changes
            map.on('zoomend', updateLabelsVisibility);

            // Create grid rectangles
            function createGridRectangle(gridData) {
                const { id, nomor_kotak, bounds, center, total_data, is_filled, survei_list } = gridData;
                
                if (!bounds || bounds.length !== 2) {
                    console.error('Invalid bounds for grid:', gridData);
                    return null;
                }

                // Create rectangle
                const rectangle = L.rectangle(bounds, {
                    color: '#999',
                    fillColor: is_filled ? '#8fbc8f' : '#fff',
                    fillOpacity: is_filled ? 0.4 : 0.15,
                    weight: 1
                }).addTo(map);

                // Label nomor di center
                if (center && center.length === 2) {
                    const labelIcon = L.divIcon({
                        className: 'grid-label',
                        html: `<span>${nomor_kotak}</span>`,
                        iconSize: [40, 20],
                        iconAnchor: [20, 10]
                    });
                    
                    const labelMarker = L.marker(center, {
                        icon: labelIcon,
                        interactive: false
                    }).addTo(map);
                    
                    gridLabels.push(labelMarker);
                }

                // Create popup content
                const dataLabel = total_data > 1 ? `${total_data} Data Survei` : (total_data === 1 ? '1 Data Survei' : 'Belum ada data');
                const statusIcon = is_filled ? '<i class="fas fa-database"></i>' : '<i class="fas fa-inbox"></i>';
                
                let popupContent = `
                    <div class="grid-popup-content">
                        <div class="grid-popup-header">
                            <h4><i class="fas fa-th-large"></i> Grid ${nomor_kotak}</h4>
                            <div class="grid-popup-status">${statusIcon} ${dataLabel}</div>
                        </div>
                `;

                if (survei_list && survei_list.length > 0) {
                    popupContent += '<div class="grid-survei-list">';
                    survei_list.forEach((survei, index) => {
                        const safeJudul = survei.judul ? survei.judul.replace(/</g, '&lt;').replace(/>/g, '&gt;') : 'N/A';
                        const safeWilayah = survei.wilayah ? (survei.wilayah.length > 25 ? survei.wilayah.substring(0, 25) + '...' : survei.wilayah) : 'N/A';
                        popupContent += `
                            <div class="grid-survei-item">
                                <div class="grid-survei-title">${safeJudul}</div>
                                <div class="grid-survei-meta">
                                    <span><i class="fas fa-calendar-alt"></i> ${survei.tahun || 'N/A'}</span>
                                    <span><i class="fas fa-layer-group"></i> ${survei.tipe || 'N/A'}</span>
                                    <span><i class="fas fa-map-marker-alt"></i> ${safeWilayah}</span>
                                </div>
                                <a href="/katalog/${survei.id}?from_peta=1" class="grid-popup-btn">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        `;
                    });
                    popupContent += '</div>';
                } else {
                    popupContent += `
                        <div class="grid-empty-message">
                            <i class="fas fa-folder-open"></i>
                            <span>Belum ada data survei di grid ini</span>
                        </div>
                    `;
                }

                popupContent += '</div>';

                // Bind popup
                rectangle.bindPopup(popupContent, {
                    maxWidth: 400,
                    minWidth: 320,
                    autoPan: true,
                    closeButton: true
                });

                // Store reference
                gridRectangles.push({
                    rectangle: rectangle,
                    data: gridData
                });

                return rectangle;
            }

            // Create all grids
            if (gridsData.length > 0) {
                console.log('Creating grid rectangles:', gridsData.length);

                gridsData.forEach(gridData => {
                    try {
                        createGridRectangle(gridData);
                    } catch (error) {
                        console.error('Error creating grid rectangle:', error, gridData);
                    }
                });

                console.log('Total grids created:', gridRectangles.length);
            } else {
                console.log('No grid data available');
            }

            // Initial label visibility
            updateLabelsVisibility();

            // Error handling for map
            map.on('tileerror', function(e) {
                console.warn('Map tile error:', e);
            });

            // Add resize listener for responsive map
            window.addEventListener('resize', function() {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            });

            // ============ FILTER FUNCTIONALITY ============
            const filterTipe = document.getElementById('filterTipe');
            const filterStatusSelect = document.getElementById('filterStatus');
            const applyFilterBtn = document.getElementById('applyFilterBtn');
            const resetFilterBtn = document.getElementById('resetFilterBtn');
            const filterStatusText = document.getElementById('filterStatusText');

            function applyFilter() {
                const selectedTipe = filterTipe.value;
                const selectedStatus = filterStatusSelect.value;
                let visibleCount = 0;

                gridRectangles.forEach(item => {
                    const { rectangle, data } = item;
                    let show = true;
                    
                    // Filter by status
                    if (selectedStatus === 'filled' && !data.is_filled) {
                        show = false;
                    } else if (selectedStatus === 'empty' && data.is_filled) {
                        show = false;
                    }
                    
                    // Filter by tipe (hanya jika grid terisi)
                    if (show && selectedTipe && data.survei_list) {
                        const hasTipe = data.survei_list.some(s => s.tipe === selectedTipe);
                        if (!hasTipe && data.is_filled) {
                            show = false;
                        }
                    }
                    
                    if (show) {
                        rectangle.addTo(map);
                        visibleCount++;
                    } else {
                        map.removeLayer(rectangle);
                    }
                });

                let statusMsg = `Menampilkan ${visibleCount} dari ${gridRectangles.length} grid`;
                if (selectedTipe) statusMsg += ` (Tipe: ${selectedTipe})`;
                if (selectedStatus) statusMsg += ` (${selectedStatus === 'filled' ? 'Terisi' : 'Kosong'})`;
                filterStatusText.textContent = statusMsg;
                
                console.log('Filter applied:', { selectedTipe, selectedStatus, visibleCount });
            }

            function resetFilter() {
                filterTipe.value = '';
                filterStatusSelect.value = '';
                gridRectangles.forEach(item => {
                    item.rectangle.addTo(map);
                });
                filterStatusText.textContent = `Menampilkan semua ${gridRectangles.length} grid`;
                console.log('Filter reset');
            }

            // Event listeners for filter
            applyFilterBtn?.addEventListener('click', applyFilter);
            resetFilterBtn?.addEventListener('click', resetFilter);
            filterTipe?.addEventListener('change', applyFilter);
            filterStatusSelect?.addEventListener('change', applyFilter);

            // Initial status
            filterStatusText.textContent = `Menampilkan semua ${gridRectangles.length} grid`;
            
            console.log('Grid map initialization complete');
        });
    </script>
@endpush
