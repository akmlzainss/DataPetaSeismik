{{-- resources/views/admin/grid_kotak/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Grid Peta Seismik - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin-grid.css') }}">
    
    {{-- Custom Styles yang mungkin belum tercover --}}
    <style>
        /* Placeholder jika ada style tambahan khusus */
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

    {{-- Custom Confirmation Modal for Assign --}}
    <div id="confirmAssignModal" class="confirm-modal-overlay">
        <div class="confirm-modal">
            <div class="confirm-modal-header">
                <h3><i class="fas fa-map-marker-alt"></i> Konfirmasi Assign Data</h3>
            </div>
            <div class="confirm-modal-body">
                <p>Apakah Anda yakin ingin meng-assign data survei ini ke grid?</p>
                <div class="highlight-box">
                    <strong>Grid:</strong> <span id="modalGridNomor">-</span><br>
                    <strong>Data Survei:</strong> <span id="modalSurveiJudul">-</span>
                </div>
            </div>
            <div class="confirm-modal-footer">
                <button type="button" class="confirm-modal-btn confirm-modal-btn-cancel" onclick="closeConfirmModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="confirm-modal-btn confirm-modal-btn-confirm" id="confirmAssignBtn">
                    <i class="fas fa-check"></i> Ya, Assign
                </button>
            </div>
        </div>
    </div>

    {{-- Custom Confirmation Modal for Unassign/Delete --}}
    <div id="confirmUnassignModal" class="confirm-modal-overlay">
        <div class="confirm-modal">
            <div class="confirm-modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-bottom-color: #ffc107;">
                <h3><i class="fas fa-trash-alt"></i> Konfirmasi Hapus dari Grid</h3>
            </div>
            <div class="confirm-modal-body">
                <p>Apakah Anda yakin ingin menghapus data survei ini dari grid?</p>
                <div class="highlight-box">
                    <strong>Grid:</strong> <span id="modalUnassignGridNomor">-</span><br>
                    <strong>Data Survei:</strong> <span id="modalUnassignSurveiJudul">-</span>
                </div>
                <div class="warning-text">
                    <i class="fas fa-info-circle"></i> Data survei akan tetap ada di sistem, hanya dihapus dari grid ini.
                </div>
            </div>
            <div class="confirm-modal-footer">
                <button type="button" class="confirm-modal-btn confirm-modal-btn-cancel" onclick="closeUnassignModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="confirm-modal-btn confirm-modal-btn-danger" id="confirmUnassignBtn">
                    <i class="fas fa-trash-alt"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
    $(document).ready(function() {
        // Initialize Select2 with proper clear functionality
        $('#surveiSelect').select2({
            placeholder: '-- Pilih Data Survei --',
            allowClear: true,
            width: '100%'
        });

        // Store grid rectangles and labels
        let gridRectangles = {};
        let gridLabels = [];
        window.selectedSurveiId = null;
        window.selectedSurveiJudul = null;
        
        // Pending assign data (for modal confirmation)
        window.pendingAssign = {
            gridId: null,
            surveiId: null,
            rectangle: null,
            grid: null
        };
        
        // Pending unassign data (for modal confirmation)
        window.pendingUnassign = {
            gridId: null,
            surveiId: null,
            nomorKotak: null,
            judulSurvei: null
        };

        // Listen to survei selection change
        $('#surveiSelect').on('change', function() {
            window.selectedSurveiId = $(this).val();
            if (window.selectedSurveiId) {
                const selectedOption = $(this).find('option:selected');
                window.selectedSurveiJudul = selectedOption.data('judul') || selectedOption.text();
                toastInfo('Data survei dipilih! Sekarang klik kotak grid di peta untuk assign data.', 'Survei Dipilih');
            } else {
                window.selectedSurveiJudul = null;
            }
        });
        
        // Handle Select2 clear event (tombol X)
        $('#surveiSelect').on('select2:clear', function(e) {
            window.selectedSurveiId = null;
            window.selectedSurveiJudul = null;
            toastInfo('Pilihan data survei dikosongkan.', 'Dibatalkan');
        });
        
        // Also handle select2:unselecting for better UX
        $('#surveiSelect').on('select2:unselecting', function(e) {
            $(this).data('unselecting', true);
        }).on('select2:opening', function(e) {
            if ($(this).data('unselecting')) {
                $(this).removeData('unselecting');
                e.preventDefault();
            }
        });

        // Initialize Leaflet Map - Asia Tenggara bounds
        const seaBounds = L.latLngBounds(
            L.latLng(-20.0, 75.0), // South-West
            L.latLng(25.0, 155.0) // North-East
        );
        
        const map = L.map('map', {
            maxBounds: seaBounds,
            maxBoundsViscosity: 1.0,
            minZoom: 4,
            maxZoom: 18
        }).setView([-2.5, 118], 5);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
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
                    // FIX: Ensure total_data matches the actual loaded survey list
                    // This fixes the issue where DB might say 1 but actual list is empty
                    if (grid.data_survei && Array.isArray(grid.data_survei)) {
                        grid.total_data = grid.data_survei.length;
                    }

                    // Create rectangle - sangat transparan supaya peta terlihat
                    const isFilled = grid.total_data > 0;
                    const rectangle = L.rectangle(grid.bounds, {
                        color: '#999',
                        fillColor: isFilled ? '#ffd700' : '#fff', // Changed to Yellow (#ffd700)
                        fillOpacity: isFilled ? 0.5 : 0.15,
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

                    // Popup content - IMPROVED VERSION WITH DELETE
                    const total_data = grid.total_data || 0;
                    const dataLabel = total_data > 1 ? `${total_data} Data Survei` : (total_data === 1 ? '1 Data Survei' : 'Belum ada data');
                    const statusIcon = total_data > 0 ? '<i class="fas fa-database"></i>' : '<i class="fas fa-inbox"></i>';
                    
                    let popupContent = `
                        <div class="grid-popup-content" data-grid-id="${grid.id}">
                            <div class="grid-popup-header">
                                <h4><i class="fas fa-th-large"></i> Grid ${grid.nomor_kotak}</h4>
                                <div class="grid-popup-status">${statusIcon} ${dataLabel}</div>
                            </div>
                    `;

                    if (grid.data_survei && grid.data_survei.length > 0) {
                        popupContent += '<div class="grid-survei-list">';
                        grid.data_survei.forEach((survei, index) => {
                            const safeJudul = survei.judul ? survei.judul.replace(/</g, '&lt;').replace(/>/g, '&gt;') : 'N/A';
                            const safeWilayah = survei.wilayah ? (survei.wilayah.length > 25 ? survei.wilayah.substring(0, 25) + '...' : survei.wilayah) : 'N/A';
                            popupContent += `
                                <div class="grid-survei-item" id="survei-item-${survei.id}">
                                    <div class="grid-survei-title">${safeJudul}</div>
                                    <div class="grid-survei-meta">
                                        <span><i class="fas fa-calendar-alt"></i> ${survei.tahun || 'N/A'}</span>
                                        <span><i class="fas fa-layer-group"></i> ${survei.tipe || 'N/A'}</span>
                                    </div>
                                    <div class="grid-survei-meta" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
                                        <span><i class="fas fa-map-marker-alt"></i> ${safeWilayah}</span>
                                    </div>
                                    <div class="grid-popup-actions">
                                        <a href="/bbspgl-admin/data-survei/${survei.id}?from=grid_kotak" class="grid-popup-btn">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="/bbspgl-admin/data-survei/${survei.id}/edit" class="grid-popup-btn grid-popup-btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="grid-popup-btn grid-popup-btn-delete" onclick="unassignSurvei(${grid.id}, ${survei.id}, '${grid.nomor_kotak}', '${safeJudul.replace(/'/g, "\\'")}')">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </div>
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

                    rectangle.bindPopup(popupContent, {
                        maxWidth: 420,
                        minWidth: 340,
                        autoPan: true,
                        closeButton: true
                    });

                    // Click event untuk assign - menggunakan modal
                    rectangle.on('click', function() {
                        if (!window.selectedSurveiId) {
                            toastWarning('Pilih data survei terlebih dahulu dari dropdown!', 'Belum Memilih Survei');
                            return;
                        }

                        // Set pending data dan tampilkan modal
                        window.pendingAssign = {
                            gridId: grid.id,
                            surveiId: window.selectedSurveiId,
                            rectangle: rectangle,
                            grid: grid
                        };
                        
                        // Update modal content
                        document.getElementById('modalGridNomor').textContent = grid.nomor_kotak;
                        document.getElementById('modalSurveiJudul').textContent = window.selectedSurveiJudul || 'Data Survei';
                        
                        // Show modal
                        document.getElementById('confirmAssignModal').classList.add('show');
                    });

                    gridRectangles[grid.id] = rectangle;
                });
            })
            .catch(error => {
                console.error('Error loading grids:', error);
                toastError('Gagal memuat data grid!', 'Error');
            });

        // Function to assign survei to grid
        window.assignSurveiToGrid = function(gridId, surveiId, rectangle, grid) {
            // Debug logging
            console.log('=== ASSIGN DEBUG ===');
            console.log('gridId:', gridId, 'type:', typeof gridId);
            console.log('surveiId:', surveiId, 'type:', typeof surveiId);
            
            // Validate before sending
            if (!gridId || !surveiId) {
                console.error('Missing gridId or surveiId!');
                toastError('Data tidak lengkap! gridId atau surveiId kosong.', 'Error');
                return;
            }
            
            fetch('{{ route('admin.grid_kotak.assign') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    grid_kotak_id: parseInt(gridId),
                    data_survei_id: parseInt(surveiId)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastSuccess(data.message, 'Berhasil');
                    
                    // Update rectangle color
                    rectangle.setStyle({
                        color: '#999',
                        fillColor: '#ffd700', // Changed to Yellow
                        fillOpacity: 0.5
                    });

                    // Remove option from dropdown
                    $('#surveiSelect option[value="' + surveiId + '"]').remove();
                    $('#surveiSelect').val('').trigger('change');
                    window.selectedSurveiId = null;

                    // Reload page to refresh stats
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    toastError(data.message, 'Gagal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastError('Terjadi error saat assign data!', 'Error');
            });
        };

        // Function to unassign/remove survei from grid - menggunakan modal
        window.unassignSurvei = function(gridId, surveiId, nomorKotak, judulSurvei) {
            // Set pending data untuk modal
            window.pendingUnassign = {
                gridId: gridId,
                surveiId: surveiId,
                nomorKotak: nomorKotak,
                judulSurvei: judulSurvei
            };
            
            // Update modal content
            document.getElementById('modalUnassignGridNomor').textContent = nomorKotak;
            document.getElementById('modalUnassignSurveiJudul').textContent = judulSurvei;
            
            // Show modal
            document.getElementById('confirmUnassignModal').classList.add('show');
        };

        // ============================================
        // MODAL FUNCTIONS
        // ============================================
        
        // Close Assign Modal
        window.closeConfirmModal = function() {
            document.getElementById('confirmAssignModal').classList.remove('show');
            window.pendingAssign = { gridId: null, surveiId: null, rectangle: null, grid: null };
        };
        
        // Close Unassign Modal  
        window.closeUnassignModal = function() {
            document.getElementById('confirmUnassignModal').classList.remove('show');
            window.pendingUnassign = { gridId: null, surveiId: null, nomorKotak: null, judulSurvei: null };
        };
        
        // Confirm Assign Button Handler
        document.getElementById('confirmAssignBtn').addEventListener('click', function() {
            if (window.pendingAssign.gridId && window.pendingAssign.surveiId) {
                // PENTING: Simpan nilai ke variabel lokal SEBELUM close modal
                // karena closeConfirmModal() akan mereset pendingAssign ke null
                const gridId = window.pendingAssign.gridId;
                const surveiId = window.pendingAssign.surveiId;
                const rectangle = window.pendingAssign.rectangle;
                const grid = window.pendingAssign.grid;
                
                closeConfirmModal();
                assignSurveiToGrid(gridId, surveiId, rectangle, grid);
            }
        });
        
        // Confirm Unassign Button Handler
        document.getElementById('confirmUnassignBtn').addEventListener('click', function() {
            if (window.pendingUnassign.gridId && window.pendingUnassign.surveiId) {
                // PENTING: Simpan nilai ke variabel lokal SEBELUM close modal
                const gridId = window.pendingUnassign.gridId;
                const surveiId = window.pendingUnassign.surveiId;
                
                closeUnassignModal();
                executeUnassign(gridId, surveiId);
            }
        });
        
        // Execute the actual unassign API call

        window.executeUnassign = function(gridId, surveiId) {
            toastInfo('Menghapus data survei dari grid...', 'Processing');

            fetch(`/bbspgl-admin/grid-kotak/unassign/${gridId}/${surveiId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastSuccess(data.message, 'Berhasil');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastError(data.message, 'Gagal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastError('Terjadi error saat menghapus data dari grid!', 'Error');
            });
        };
        
        // Close modals when clicking outside
        document.getElementById('confirmAssignModal').addEventListener('click', function(e) {
            if (e.target === this) closeConfirmModal();
        });
        
        document.getElementById('confirmUnassignModal').addEventListener('click', function(e) {
            if (e.target === this) closeUnassignModal();
        });
        
        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmModal();
                closeUnassignModal();
            }
        });
    }); // End of document.ready
    </script>
@endpush
