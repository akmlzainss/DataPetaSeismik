{{-- resources/views/admin/data_survei/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Data Survei Baru - Admin BBSPGL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1>Tambah Data Survei</h1>
    <p>Lengkapi semua kolom di bawah ini untuk menambahkan data survei seismik baru</p>
</div>

<div class="welcome-section">
    <div class="form-container">
        <!-- Alert Success -->
        @if(session('success'))
            <div class="alert-success-form">
                ✓ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.data_survei.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <!-- Judul -->
                <div class="form-group">
                    <label class="form-label">
                        Judul Survei <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           class="form-input" 
                           value="{{ old('judul') }}"
                           placeholder="Contoh: Survei Seismik 2D" 
                           required>
                    @error('judul')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Ketua Tim -->
                <div class="form-group">
                    <label class="form-label">
                        Ketua Tim <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="ketua_tim" 
                           class="form-input" 
                           value="{{ old('ketua_tim') }}"
                           placeholder="Contoh: Dr. Ahmad Santoso" 
                           required>
                    @error('ketua_tim')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tipe Survei -->
                <div class="form-group">
                    <label class="form-label">
                        Tipe Survei <span class="required">*</span>
                    </label>
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

                <!-- Tahun -->
                <div class="form-group">
                    <label class="form-label">
                        Tahun Pelaksanaan <span class="required">*</span>
                    </label>
                    <input type="number" 
                           name="tahun" 
                           class="form-input" 
                           value="{{ old('tahun', date('Y')) }}" 
                           min="1950" 
                           max="{{ date('Y') + 5 }}"
                           placeholder="{{ date('Y') }}" 
                           required>
                    @error('tahun')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Wilayah -->
                <div class="form-group full-width">
                    <label class="form-label">
                        Wilayah / Blok <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="wilayah" 
                           class="form-input" 
                           value="{{ old('wilayah') }}"
                           placeholder="Contoh: Blok Cepu, Jawa Timur" 
                           required>
                    @error('wilayah')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Deskripsi (Full Width) -->
                <div class="form-group full-width">
                    <label class="form-label">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" 
                              class="form-textarea"
                              placeholder="Jelaskan secara singkat tentang survei ini...">{{ old('deskripsi') }}</textarea>
                    <span class="help-text">Deskripsi akan ditampilkan pada detail survei</span>
                    @error('deskripsi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Gambar Pratinjau (Full Width) -->
                <div class="form-group full-width">
                    <label class="form-label">Gambar Pratinjau (Opsional)</label>
                    <input type="file" 
                           name="gambar_pratinjau" 
                           class="form-file" 
                           accept="image/jpeg,image/png,image/jpg">
                    <span class="help-text">Format: JPG, PNG, JPEG. Maksimal 2MB</span>
                    @error('gambar_pratinjau')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    Simpan Data Survei
                </button>
                <a href="{{ route('admin.data_survei.index') }}" class="btn-cancel">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection