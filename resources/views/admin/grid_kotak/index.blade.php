{{-- resources/views/admin/grid_kotak/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Grid Peta Seismik - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .grid-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border-left: 4px solid #667eea;
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .stat-card.success {
            border-left-color: #28a745;
        }
        
        .stat-card.warning {
            border-left-color: #ff9800;
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .stat-icon.primary {
            background: rgba(102, 126, 234, 0.12);
            color: #667eea;
        }
        
        .stat-icon.success {
            background: rgba(40, 167, 69, 0.12);
            color: #28a745;
        }
        
        .stat-icon.warning {
            background: rgba(255, 152, 0, 0.12);
            color: #ff9800;
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-content .title {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-content .value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }
        
        .stat-content .description {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }
        
        .stat-content .highlight {
            color: #28a745;
            font-weight: 600;
        }
        
        .stat-content .highlight.warning {
            color: #ff9800;
        }
        
        #map {
            height: 600px;
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        /* Select2 Custom Styling */
        .select-container {
            margin: 20px 0;
        }
        
        .select-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1a365d;
            font-size: 14px;
        }
        
        .select2-container--default .select2-selection--single {
            height: 46px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            transition: all 0.2s ease;
        }
        
        .select2-container--default .select2-selection--single:hover {
            border-color: #667eea;
            background: white;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            background: white;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px;
            padding-left: 16px;
            color: #2d3748;
            font-size: 14px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
            right: 12px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #a0aec0;
        }
        
        .select2-dropdown {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            margin-top: 4px;
        }
        
        .select2-results__option {
            padding: 12px 16px;
            font-size: 14px;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: #ffed00;
            color: #1a365d;
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #e8f4fd 0%, #dbeafe 100%);
            color: #1e40af;
            border-left-color: #3b82f6;
        }
        
        .alert-info strong {
            color: #1e3a8a;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border-left-color: #f59e0b;
        }
        
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
            /* Tidak perlu transform - flexbox sudah menangani centering */
        }
        
        /* Hidden state saat zoom out */
        .grid-label.hidden {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Grid Peta Seismik</h1>
        <p>Kelola data survei seismik dengan sistem grid</p>
    </div>

    <div class="grid-container">
        {{-- Statistics Cards --}}
        <div class="stats-grid">
            {{-- Total Grid --}}
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-th"></i>
                </div>
                <div class="stat-content">
                    <div class="title">Total Grid Kotak</div>
                    <div class="value">{{ $stats['total_grid'] }}</div>
                    <div class="description">Sistem grid peta seismik</div>
                </div>
            </div>
            
            {{-- Grid Terisi --}}
            <div class="stat-card success">
                <div class="stat-icon success">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="title">Grid Terisi</div>
                    <div class="value">{{ $stats['grid_terisi'] }}</div>
                    <div class="description">
                        <span class="highlight">{{ $stats['persentase_terisi'] }}%</span> dari {{ $stats['total_grid'] }} kotak
                    </div>
                </div>
            </div>
            
            {{-- Data Belum Assign --}}
            <div class="stat-card warning">
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="title">Belum Di-Assign</div>
                    <div class="value">{{ $stats['data_belum_assign'] }}</div>
                    <div class="description">Data survei menunggu</div>
                </div>
            </div>
        </div>

        {{-- Info Alert --}}
        @if($stats['total_grid'] == 0)
            <div class="alert alert-warning">
                <strong>⚠️ Grid Belum Ada!</strong><br>
                System grid belum di-seed. Silakan running seeder dulu:<br>
                <code>php artisan db:seed --class=GridKotakSeeder</code>
            </div>
        @else
            <div class="alert alert-info">
                <strong>📌 Cara Penggunaan:</strong><br>
               1. Pilih data survei dari dropdown<br>
                2. Klik kotak grid di peta<br>
                3. Data akan otomatis ter-assign ke kotak tersebut<br>
                4. Kotak akan berubah warna (hijau = terisi, abu-abu = kosong)
            </div>
        @endif

        {{-- Dropdown Data Survei --}}
        <div class="select-container">
            <label for="surveiSelect">
                📋 Pilih Data Survei yang Akan Di-Assign:
            </label>
            <select id="surveiSelect" style="width: 100%;">
                <option value="">-- Pilih Data Survei --</option>
                @foreach($surveisBelumAssign as $survei)
                    <option value="{{ $survei->id }}" 
                        data-judul="{{ $survei->judul }}" 
                        data-tahun="{{ $survei->tahun }}" 
                        data-tipe="{{ $survei->tipe }}">
                        {{ $survei->judul }} ({{ $survei->tahun }} - {{ $survei->tipe }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Map Container --}}
        <div id="map"></div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Initialize Select2
        $('#surveiSelect').select2({
            placeholder: '-- Pilih Data Survei --',
            allowClear: true
        });

        // Initialize Leaflet Map
        const map = L.map('map').setView([-2.5, 118], 5);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Store grid rectangles and labels
        let gridRectangles = {};
        let gridLabels = [];
        let selectedSurveiId = null;
        
        // Zoom threshold untuk tampilkan label (label muncul di zoom >= 7)
        const LABEL_ZOOM_THRESHOLD = 5;
        
        // Function untuk toggle label visibility berdasarkan zoom
        function updateLabelsVisibility() {
            const currentZoom = map.getZoom();
            const showLabels = currentZoom >= LABEL_ZOOM_THRESHOLD;
            
            gridLabels.forEach(marker => {
                if (showLabels) {
                    marker.getElement()?.classList.remove('hidden');
                } else {
                    marker.getElement()?.classList.add('hidden');
                }
            });
        }
        
        // Listen to zoom changes
        map.on('zoomend', updateLabelsVisibility);

        // Listen to survei selection
        $('#surveiSelect').on('change', function() {
            selectedSurveiId = $(this).val();
            if (selectedSurveiId) {
                alert('Data survei dipilih! Sekarang klik kotak grid di peta untuk assign data.');
            }
        });

        // Load all grids from server
        fetch('{{ route('admin.grid_kotak.data') }}')
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to load grid data');
                    return;
                }

                console.log('Loaded', data.grids.length, 'grids');

                data.grids.forEach(grid => {
                    // Create rectangle - sangat transparan supaya peta terlihat
                    const isFilled = grid.total_data > 0;
                    const rectangle = L.rectangle(grid.bounds, {
                        color: '#999',
                        fillColor: isFilled ? '#8fbc8f' : '#fff',
                        fillOpacity: isFilled ? 0.4 : 0.15,
                        weight: 1
                    }).addTo(map);

                    // Label nomor di center - posisi tepat tengah
                    const labelIcon = L.divIcon({
                        className: 'grid-label',
                        html: `<span>${grid.nomor_kotak}</span>`,
                        iconSize: [40, 20],    // Ukuran eksplisit
                        iconAnchor: [20, 10]   // Setengah dari iconSize = center
                    });
                    
                    const labelMarker = L.marker(grid.center, {
                        icon: labelIcon,
                        interactive: false
                    }).addTo(map);
                    
                    // Simpan label untuk kontrol visibility
                    gridLabels.push(labelMarker);

                    // Popup content
                    let popupContent = `
                        <div style="min-width: 200px;">
                            <h4 style="margin: 0 0 8px 0;">Grid ${grid.nomor_kotak}</h4>
                            <p style="margin: 4px 0;"><strong>Status:</strong> ${grid.total_data > 0 ? 'Terisi (' + grid.total_data + ' data)' : 'Kosong'}</p>
                    `;

                    if (grid.data_survei && grid.data_survei.length > 0) {
                        popupContent += '<hr style="margin: 8px 0;"><strong>Data Survei:</strong><ul style="margin: 4px 0; padding-left: 20px;">';
                        grid.data_survei.forEach(survei => {
                            popupContent += `<li>${survei.judul} (${survei.tahun})</li>`;
                        });
                        popupContent += '</ul>';
                    }

                    popupContent += '</div>';

                    rectangle.bindPopup(popupContent);

                    // Click event untuk assign
                    rectangle.on('click', function() {
                        if (!selectedSurveiId) {
                            alert('Pilih data survei terlebih dahulu dari dropdown!');
                            return;
                        }

                        if (confirm(`Assign data survei ke Grid ${grid.nomor_kotak}?`)) {
                            assignSurveiToGrid(grid.id, selectedSurveiId, rectangle, grid);
                        }
                    });

                    gridRectangles[grid.id] = rectangle;
                });
            })
            .catch(error => {
                console.error('Error loading grids:', error);
                alert('Gagal memuat data grid!');
            });

        // Function to assign survei to grid
        function assignSurveiToGrid(gridId, surveiId, rectangle, grid) {
            fetch('{{ route('admin.grid_kotak.assign') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    grid_kotak_id: gridId,
                    data_survei_id: surveiId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ ' + data.message);
                    
                    // Update rectangle color
                    rectangle.setStyle({
                        color: '#28a745',
                        fillColor: '#28a745',
                        fillOpacity: 0.5
                    });

                    // Remove option from dropdown
                    $('#surveiSelect option[value="' + surveiId + '"]').remove();
                    $('#surveiSelect').val('').trigger('change');
                    selectedSurveiId = null;

                    // Reload page to refresh stats
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi error saat assign data!');
            });
        }
    </script>
@endpush
