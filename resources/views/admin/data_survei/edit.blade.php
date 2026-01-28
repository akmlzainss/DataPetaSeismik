{{-- resources/views/admin/data_survei/edit.blade.php --}}

@extends('layouts.admin')
@section('title', 'Edit Data Survei - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
    <link href="{{ asset('assets/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        #quill-editor {
            background: white;
            border-radius: 8px;
            min-height: 380px;
            border: 1px solid #ddd;
        }

        .ql-container {
            font-size: 14.5px;
        }

        .ql-editor {
            line-height: 1.7;
            min-height: 340px;
            padding: 12px 14px;
        }

        .ql-toolbar {
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Edit Data Survei</h1>
        <p>Perbarui informasi survei seismik</p>
    </div>

    <div class="welcome-section">
        <div class="form-container">

            @if (session('success'))
                <div class="alert-success-form">✓ {{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.data_survei.update', $dataSurvei) }}" method="POST" enctype="multipart/form-data"
                id="uploadForm">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Judul Survei <span class="required">*</span></label>
                        <input type="text" name="judul" class="form-input"
                            value="{{ old('judul', $dataSurvei->judul) }}" required>
                        @error('judul')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ketua Tim <span class="required">*</span></label>
                        <input type="text" name="ketua_tim" class="form-input"
                            value="{{ old('ketua_tim', $dataSurvei->ketua_tim) }}" required>
                        @error('ketua_tim')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Survei <span class="required">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <option value="">-- Pilih Tipe Survei --</option>
                            <option value="2D" {{ old('tipe', $dataSurvei->tipe) == '2D' ? 'selected' : '' }}>2D
                            </option>
                            <option value="3D" {{ old('tipe', $dataSurvei->tipe) == '3D' ? 'selected' : '' }}>3D
                            </option>
                            <option value="HR" {{ old('tipe', $dataSurvei->tipe) == 'HR' ? 'selected' : '' }}>HR
                            </option>
                            <option value="Lainnya" {{ old('tipe', $dataSurvei->tipe) == 'Lainnya' ? 'selected' : '' }}>
                                Lainnya</option>
                        </select>
                        @error('tipe')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tahun Pelaksanaan <span class="required">*</span></label>
                        <input type="number" name="tahun" class="form-input"
                            value="{{ old('tahun', $dataSurvei->tahun) }}" min="1950" required>
                        @error('tahun')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Wilayah / Blok <span class="required">*</span></label>
                        <input type="text" name="wilayah" class="form-input"
                            value="{{ old('wilayah', $dataSurvei->wilayah) }}" required>
                        @error('wilayah')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Posisi Grid Peta --}}
                    <div class="form-group full-width">
                        <label class="form-label">Posisi Grid Peta (Opsional)</label>
                        <select name="grid_kotak_id[]" id="gridSelect" class="form-select" multiple>
                            @foreach($allGrids as $grid)
                                <option value="{{ $grid->id }}" 
                                    {{ $dataSurvei->gridKotak->contains('id', $grid->id) ? 'selected' : '' }}>
                                    Grid {{ $grid->nomor_kotak }} 
                                    ({{ $grid->status == 'filled' ? 'Terisi' : 'Kosong' }})
                                </option>
                            @endforeach
                        </select>
                        <span class="help-text">Pilih satu atau lebih kotak grid untuk lokasi survei ini.</span>
                    </div>

                    <!-- QUILL EDITOR -->
                    <div class="form-group full-width">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <div id="quill-editor">
                            {!! old('deskripsi', $dataSurvei->deskripsi) !!}
                        </div>
                        <textarea name="deskripsi" id="quill-hidden" style="display:none;"></textarea>
                        @error('deskripsi')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Gambar Pratinjau -->
                    <div class="form-group full-width">
                        <label class="form-label">Gambar Pratinjau (Opsional)</label>
                        <input type="file" name="gambar_pratinjau" class="form-file" accept=".jpeg,.jpg,.png">

                        @if ($dataSurvei->gambar_pratinjau)
                            <div class="image-preview" style="margin-top:16px;">
                                <span class="image-preview-label">Gambar saat ini:</span><br>
                                <img src="{{ asset('storage/' . $dataSurvei->gambar_pratinjau) }}"
                                    style="max-height:200px; border-radius:8px;">
                            </div>
                        @endif

                        <span class="help-text">Format: JPEG, JPG, PNG • Maksimal
                            <strong>5MB</strong></span>
                        @error('gambar_pratinjau')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tautan File -->
                    <div class="form-group full-width">
                        <label class="form-label">Tautan File Survei (Opsional)</label>
                        <input type="url" name="tautan_file" class="form-input"
                            value="{{ old('tautan_file', $dataSurvei->tautan_file) }}"
                            placeholder="Contoh: https://drive.google.com/file/d/xxx atau https://dropbox.com/xxx">
                        <span class="help-text">Masukkan link Google Drive, Dropbox, atau layanan cloud storage
                            lainnya</span>
                        @error('tautan_file')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- File Scan Asli (untuk Pegawai ESDM) --}}
                    <div class="form-group full-width" style="background: #f0f8ff; padding: 20px; border-radius: 8px; border-left: 4px solid #003366;">
                        <label class="form-label">
                            <i class="fas fa-file-archive"></i> File Scan Asli (Opsional - Untuk Pegawai ESDM)
                        </label>

                        @if ($dataSurvei->file_scan_asli)
                            <div class="current-file-info" style="background: #e7f3ff; padding: 15px; border-radius: 6px; margin-bottom: 15px; border-left: 4px solid #003366;">
                                <strong style="color: #003366;"><i class="fas fa-file-check"></i> File Saat Ini:</strong><br>
                                <span style="color: #666; font-size: 14px;">
                                    {{ basename($dataSurvei->file_scan_asli) }}
                                    @if($dataSurvei->ukuran_file_asli)
                                        ({{ number_format($dataSurvei->ukuran_file_asli / 1048576, 2) }} MB -  {{ strtoupper($dataSurvei->format_file_asli) }})
                                    @endif
                                </span>
                            </div>
                        @endif

                        <input type="file" name="file_scan_asli" class="form-file" 
                               accept=".pdf,.tiff,.tif,.png,.jpeg,.jpg,.zip,.rar"
                               id="fileScanAsli">
                        <span class="help-text">
                            <i class="fas fa-info-circle"></i> 
                            Format: PDF, TIFF, PNG, JPEG, ZIP, RAR • Maksimal <strong>600MB</strong><br>
                            @if ($dataSurvei->file_scan_asli)
                                Upload file baru akan menggantikan file yang ada.
                            @else
                                File ini hanya bisa diunduh oleh Pegawai ESDM ESDM yang sudah login.
                            @endif
                        </span>
                        @error('file_scan_asli')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div id="fileScanInfo" style="margin-top: 10px; display: none;"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span id="submitText">Update Data Survei</span>
                        <span id="loadingText" style="display:none;">Sedang memproses...</span>
                    </button>
                    <a href="{{ route('admin.data_survei.index') }}" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/quill/quill.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#gridSelect').select2({
                placeholder: 'Pilih Grid (Cari nomor grid...)',
                allowClear: true,
                width: '100%'
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, 3, 4, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            color: []
                        }, {
                            background: []
                        }],
                        [{
                            align: []
                        }],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        ['link', 'image'],
                        ['blockquote', 'code-block'],
                        ['clean']
                    ]
                },
                placeholder: 'Perbarui penjelasan survei ini...'
            });

            // File Scan Asli validation
            const fileScanInput = document.getElementById('fileScanAsli');
            const maxScanSize = 600 * 1024 * 1024; // 600MB

            if (fileScanInput) {
                fileScanInput.addEventListener('change', function(e) {
                    const file = this.files[0];
                    const infoDiv = document.getElementById('fileScanInfo');
                    
                    if (file) {
                        let errors = [];
                        
                        // Check file size
                        if (file.size > maxScanSize) {
                            errors.push('Ukuran file terlalu besar. Maksimal 600MB. File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
                        }
                        
                        if (errors.length > 0) {
                            infoDiv.style.cssText = 'color: #dc3545; background: #fff5f5; padding: 10px; border-radius: 4px; border: 1px solid #f5c2c7; display: block;';
                            infoDiv.innerHTML = '⚠️ ' + errors.join('<br>⚠️ ');
                            this.value = ''; // Clear the input
                        } else {
                            // Show success info
                            const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                            infoDiv.style.cssText = 'color: #198754; background: #f0fff4; padding: 10px; border-radius: 4px; border: 1px solid #c3e6cb; display: block;';
                            infoDiv.innerHTML = '✓ File siap diupload: <strong>' + file.name + '</strong> (' + sizeMB + ' MB)';
                        }
                    } else {
                        infoDiv.style.display = 'none';
                    }
                });
            }

            // Submit: simpan isi Quill ke textarea tersembunyi
            document.getElementById('uploadForm').onsubmit = function() {
                document.getElementById('quill-hidden').value = quill.root.innerHTML;

                // Loading button
                document.getElementById('submitText').style.display = 'none';
                document.getElementById('loadingText').style.display = 'inline';
                document.getElementById('submitBtn').disabled = true;
            };
        });
    </script>
@endpush

