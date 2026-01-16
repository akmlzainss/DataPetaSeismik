{{-- resources/views/admin/data_survei/create.blade.php --}}

@extends('layouts.admin')
@section('title', 'Tambah Data Survei Baru - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
    <link href="{{ asset('assets/quill/quill.snow.css') }}" rel="stylesheet">
    <style>
        #quill-editor {
            background: white;
            border-radius: 8px;
            min-height: 380px;
        }

        .ql-container {
            font-size: 14.5px;
        }

        .ql-editor {
            line-height: 1.7;
            min-height: 340px;
        }

        .ql-toolbar {
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Tambah Data Survei</h1>
        <p>Lengkapi semua kolom di bawah ini untuk menambahkan data survei seismik baru</p>
    </div>

    <div class="welcome-section">
        <div class="form-container">
            @if (session('success'))
                <div class="alert-success-form">✓ {{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.data_survei.store') }}" method="POST" enctype="multipart/form-data"
                id="uploadForm">
                @csrf
                <div class="form-grid">
                    <!-- Field lain tetap sama -->
                    <div class="form-group">
                        <label class="form-label">Judul Survei <span class="required">*</span></label>
                        <input type="text" name="judul" class="form-input" value="{{ old('judul') }}"
                            placeholder="Contoh: Survei Seismik 2D" required>
                        @error('judul')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ketua Tim <span class="required">*</span></label>
                        <input type="text" name="ketua_tim" class="form-input" value="{{ old('ketua_tim') }}"
                            placeholder="Contoh: Dr. Ahmad Santoso" required>
                        @error('ketua_tim')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Survei <span class="required">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <option value="">-- Pilih Tipe Survei --</option>
                            <option value="2D" {{ old('tipe') == '2D' ? 'selected' : '' }}>2D</option>
                            <option value="3D" {{ old('tipe') == '3D' ? 'selected' : '' }}>3D</option>
                            <option value="HR" {{ old('tipe') == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="Lainnya" {{ old('tipe') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('tipe')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tahun Pelaksanaan <span class="required">*</span></label>
                        <input type="number" name="tahun" class="form-input" value="{{ old('tahun') }}"
                            placeholder="Contoh: 2024" min="1950" required>

                        @error('tahun')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Wilayah / Blok <span class="required">*</span></label>
                        <input type="text" name="wilayah" class="form-input" value="{{ old('wilayah') }}"
                            placeholder="Contoh: Blok Cepu, Jawa Timur" required>
                        @error('wilayah')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- QUILL EDITOR -->
                    <div class="form-group full-width">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <div id="quill-editor">
                            {!! old('deskripsi') !!}
                        </div>
                        <textarea name="deskripsi" id="quill-hidden" style="display:none;"></textarea>
                        @error('deskripsi')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Gambar Pratinjau tetap sama -->
                    <div class="form-group full-width">
                        <label class="form-label">Gambar Pratinjau (Opsional)</label>
                        <input type="file" name="gambar_pratinjau" class="form-file" accept=".jpeg,.jpg,.png">
                        <span class="help-text">Format: JPEG, JPG, PNG • Maksimal <strong>5MB</strong></span>
                        @error('gambar_pratinjau')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tautan File -->
                    <div class="form-group full-width">
                        <label class="form-label">Tautan File Survei (Opsional)</label>
                        <input type="url" name="tautan_file" class="form-input" value="{{ old('tautan_file') }}"
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
                        <input type="file" name="file_scan_asli" class="form-file" 
                               accept=".pdf,.tiff,.tif,.png,.jpeg,.jpg,.zip,.rar"
                               id="fileScanAsli">
                        <span class="help-text">
                            <i class="fas fa-info-circle"></i> 
                            Format: PDF, TIFF, PNG, JPEG, ZIP, RAR • Maksimal <strong>600MB</strong><br>
                            File ini hanya bisa diunduh oleh Pegawai ESDM ESDM yang sudah login.
                        </span>
                        @error('file_scan_asli')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div id="fileScanInfo" style="margin-top: 10px; display: none;"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span id="submitText">Simpan Data Survei</span>
                        <span id="loadingText" style="display:none;">Sedang memproses...</span>
                    </button>
                    <a href="{{ route('admin.data_survei.index') }}" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/quill/quill.js') }}"></script>
    <script>
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
                placeholder: 'Penjelasan untuk hasil survei ini...'
            });

            // File input validation (TC014 fix)
            const fileInput = document.querySelector('input[name="gambar_pratinjau"]');
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = this.files[0];
                    // Remove existing error messages
                    let existingError = this.parentElement.querySelector('.file-error-js');
                    if (existingError) existingError.remove();
                    
                    if (file) {
                        let errors = [];
                        
                        // Check file type
                        if (!allowedTypes.includes(file.type)) {
                            errors.push('Format file tidak didukung. Hanya JPEG, JPG, atau PNG.');
                        }
                        
                        // Check file size
                        if (file.size > maxSize) {
                            errors.push('Ukuran file terlalu besar. Maksimal 5MB. File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
                        }
                        
                        if (errors.length > 0) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'file-error-js error-message';
                            errorDiv.style.cssText = 'color: #dc3545; background: #fff5f5; padding: 10px; border-radius: 4px; margin-top: 8px; border: 1px solid #f5c2c7;';
                            errorDiv.innerHTML = '⚠️ ' + errors.join('<br>⚠️ ');
                            this.parentElement.appendChild(errorDiv);
                            this.value = ''; // Clear the input
                        } else {
                            // Show success info
                            const successDiv = document.createElement('div');
                            successDiv.className = 'file-error-js';
                            successDiv.style.cssText = 'color: #198754; background: #f0fff4; padding: 10px; border-radius: 4px; margin-top: 8px; border: 1px solid #c3e6cb;';
                            successDiv.innerHTML = '✓ File siap diupload: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + 'KB)';
                            this.parentElement.appendChild(successDiv);
                        }
                    }
                });
            }

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

            // Form submit with loading state (TC016 fix)
            const form = document.getElementById('uploadForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');
            
            form.onsubmit = function(e) {
                // Check for file validation errors
                const fileError = document.querySelector('.file-error-js.error-message');
                if (fileError) {
                    e.preventDefault();
                    alert('Mohon perbaiki error file upload sebelum melanjutkan.');
                    return false;
                }
                
                // Set Quill content to hidden textarea
                document.getElementById('quill-hidden').value = quill.root.innerHTML;

                // Show loading state
                submitText.style.display = 'none';
                loadingText.style.display = 'inline';
                loadingText.innerHTML = '<svg class="spinner" width="16" height="16" viewBox="0 0 24 24" style="animation: spin 1s linear infinite; margin-right: 8px;"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="31.416" stroke-dashoffset="10"></circle></svg> Menyimpan data...';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.7';
                submitBtn.style.cursor = 'wait';
            };
        });
    </script>
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spinner {
            display: inline-block;
            vertical-align: middle;
        }
    </style>
@endpush

