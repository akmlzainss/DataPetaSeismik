{{-- resources/views/admin/data_survei/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Data Survei - Admin BBSPGL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1>Edit Data Survei</h1>
    <p>Perbarui informasi survei seismik</p>
</div>

<div class="welcome-section">
    <div class="form-container">

        @if(session('success'))
            <div class="alert-success-form">✓ {{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.data_survei.update', $dataSurvei) }}" 
              method="POST" 
              enctype="multipart/form-data"
              id="uploadForm">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Semua field sama seperti create, hanya value dari model -->
                <div class="form-group">
                    <label class="form-label">Judul Survei <span class="required">*</span></label>
                    <input type="text" name="judul" class="form-input" value="{{ old('judul', $dataSurvei->judul) }}" required>
                    @error('judul') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Ketua Tim <span class="required">*</span></label>
                    <input type="text" name="ketua_tim" class="form-input" value="{{ old('ketua_tim', $dataSurvei->ketua_tim) }}" required>
                    @error('ketua_tim') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Survei <span class="required">*</span></label>
                    <select name="tipe" class="form-select" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="2D" {{ old('tipe', $dataSurvei->tipe)=='2D' ? 'selected':'' }}>2D</option>
                        <option value="3D" {{ old('tipe', $dataSurvei->tipe)=='3D' ? 'selected':'' }}>3D</option>
                        <option value="HR" {{ old('tipe', $dataSurvei->tipe)=='HR' ? 'selected':'' }}">HR</option>
                        <option value="Lainnya" {{ old('tipe', $dataSurvei->tipe)=='Lainnya' ? 'selected':'' }}>Lainnya</option>
                    </select>
                    @error('tipe') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tahun Pelaksanaan <span class="required">*</span></label>
                    <input type="number" name="tahun" class="form-input" value="{{ old('tahun', $dataSurvei->tahun) }}" min="1950" required>
                    @error('tahun') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Wilayah / Blok <span class="required">*</span></label>
                    <input type="text" name="wilayah" class="form-input" value="{{ old('wilayah', $dataSurvei->wilayah) }}" required>
                    @error('wilayah') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" class="form-textarea">{{ old('deskripsi', $dataSurvei->deskripsi) }}</textarea>
                    @error('deskripsi') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- GAMBAR + PROGRESS BAR -->
                <div class="form-group full-width">
                    <label class="form-label">Gambar Pratinjau (Opsional)</label>
                    <input type="file" name="gambar_pratinjau" class="form-file" accept="image/*" id="gambarInput">

                    <div id="progressContainer" class="progress-container" style="margin-top:16px; display:none;">
                        <div class="progress-bar">
                            <div id="progressFill" class="progress-fill">0%</div>
                        </div>
                        <small id="progressText" class="progress-text">Mengunggah... 0%</small>
                    </div>

                    @if($dataSurvei->gambar_pratinjau)
                        <div class="image-preview" style="margin-top:16px;">
                            <span class="image-preview-label">Gambar saat ini:</span><br>
                            <img src="{{ asset('storage/' . $dataSurvei->gambar_pratinjau) }}" style="max-height:200px; border-radius:8px;">
                        </div>
                    @endif

                    <span class="help-text">Upload gambar baru jika ingin mengganti • Maksimal <strong>500MB</strong></span>
                    @error('gambar_pratinjau') <span class="error-message">{{ $message }}</span> @enderror
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