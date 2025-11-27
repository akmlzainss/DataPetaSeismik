{{-- resources/views/admin/data_survei/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail: ' . $dataSurvei->judul)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1>Detail Data Survei</h1>
    <p>Informasi lengkap survei seismik</p>
</div>

<div class="welcome-section">
    <div class="detail-container">
        <!-- Header -->
        <div class="detail-header">
            <h2 class="detail-title">{{ $dataSurvei->judul }}</h2>
            <span class="detail-badge">{{ $dataSurvei->tipe }}</span>
        </div>

        <!-- Detail Table -->
        <table class="detail-table">
            <tr>
                <td>Ketua Tim</td>
                <td>{{ $dataSurvei->ketua_tim }}</td>
            </tr>
            <tr>
                <td>Tahun Pelaksanaan</td>
                <td>{{ $dataSurvei->tahun }}</td>
            </tr>
            <tr>
                <td>Wilayah / Blok</td>
                <td>{{ $dataSurvei->wilayah }}</td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td>{{ $dataSurvei->deskripsi ?? '-' }}</td>
            </tr>
            @if($dataSurvei->gambar_pratinjau)
            <tr>
                <td>Gambar Pratinjau</td>
                <td>
                    <img src="{{ asset('storage/' . $dataSurvei->gambar_pratinjau) }}" 
                         alt="Gambar Pratinjau" 
                         class="detail-image">
                </td>
            </tr>
            @endif
        </table>

        <!-- Metadata -->
        <div class="detail-metadata">
            <div class="detail-metadata-item">
                <span class="detail-metadata-label">Diunggah oleh:</span>
                <span>{{ $dataSurvei->pengunggah->nama ?? 'Admin' }}</span>
            </div>
            <div class="detail-metadata-item">
                <span class="detail-metadata-label">Tanggal Upload:</span>
                <span>{{ $dataSurvei->created_at->format('d M Y H:i') }}</span>
            </div>
            @if($dataSurvei->updated_at != $dataSurvei->created_at)
            <div class="detail-metadata-item">
                <span class="detail-metadata-label">Terakhir Diperbarui:</span>
                <span>{{ $dataSurvei->updated_at->format('d M Y H:i') }}</span>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="detail-actions">
            <a href="{{ route('admin.data_survei.edit', $dataSurvei) }}" class="btn-edit-detail">
                Edit Data
            </a>
            <a href="{{ route('admin.data_survei.index') }}" class="btn-back-detail">
                Kembali ke Daftar
            </a>
            <form action="{{ route('admin.data_survei.destroy', $dataSurvei) }}" 
                  method="POST" 
                  style="display:inline;"
                  onsubmit="return confirm('Yakin ingin menghapus data survei ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-detail">
                    Hapus Data
                </button>
            </form>
        </div>
    </div>
</div>
@endsection