{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - BBSPGL')

@section('content')
<div class="page-header">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang kembali, <strong>{{ Auth::guard('admin')->user()->nama ?? 'Administrator' }}</strong> 👋</p>
</div>

@php
    use App\Models\DataSurvei;
    use App\Models\LokasiMarker;

    $totalSurvei     = DataSurvei::count();
    $totalMarker     = LokasiMarker::count();
    $surveiTahunIni  = DataSurvei::whereYear('created_at', date('Y'))->count();
    $survei2D        = DataSurvei::where('tipe', '2D')->count();
    $survei3D        = DataSurvei::where('tipe', '3D')->count();
    $surveiHR        = DataSurvei::where('tipe', 'HR')->count();
@endphp

<div class="stats-container">
    <div class="stat-card">
        <h3>Total Data Survei</h3>
        <div class="stat-value">{{ number_format($totalSurvei) }}</div>
        <div class="stat-label">Total entri survei seismik</div>
    </div>
    <div class="stat-card">
        <h3>Lokasi Marker</h3>
        <div class="stat-value">{{ number_format($totalMarker) }}</div>
        <div class="stat-label">Titik koordinat aktif</div>
    </div>
    <div class="stat-card">
        <h3>Survei Tahun {{ date('Y') }}</h3>
        <div class="stat-value">{{ $surveiTahunIni }}</div>
        <div class="stat-label">Data baru tahun ini</div>
    </div>
    <div class="stat-card">
        <h3>Aktivitas Admin</h3>
        <div class="stat-value">{{ \App\Models\Admin::count() }}</div>
        <div class="stat-label">Admin terdaftar</div>
    </div>
</div>

<div class="welcome-section">
    <h2>Panel Administrasi BBSPGL</h2>
    <p>Sistem Informasi Survei Seismik Nasional — Kementerian ESDM</p>
    
    <div class="quick-links">
        <a href="{{ route('admin.data_survei.create') }}" class="quick-link-btn">
            + Tambah Data Survei Baru
        </a>
        <a href="{{ route('admin.data_survei.index') }}" class="quick-link-btn">
            Kelola Data Survei
        </a>
        <a href="#" class="quick-link-btn">
            Peta Lokasi Marker (Segera)
        </a>
        <a href="#" class="quick-link-btn">
            Export Laporan Bulanan
        </a>
    </div>

    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 12px; font-size: 14px; color: #555;">
        <strong>Statistik Tipe Survei:</strong><br>
        2D: <strong>{{ $survei2D }}</strong> | 
        3D: <strong>{{ $survei3D }}</strong> | 
        HR: <strong>{{ $surveiHR }}</strong> | 
        Lainnya: <strong>{{ $totalSurvei - ($survei2D + $survei3D + $surveiHR) }}</strong>
    </div>
</div>
@endsection