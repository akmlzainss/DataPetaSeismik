@extends('layouts.admin')
@section('title', 'Lokasi Marker - Admin BBSPGL')

@section('content')
<div class="page-header">
    <h1>Lokasi Marker</h1>
    <p>Kelola titik koordinat pusat survei seismik</p>
</div>

<div class="welcome-section">
    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-10 text-center shadow-lg border">
        <div class="text-8xl mb-6">Map</div>
        <h2 class="text-3xl font-bold text-[#003366] mb-4">Peta Interaktif Sedang Dibangun</h2>
        <p class="text-lg text-gray-700 mb-8">
            Fitur peta Leaflet dengan semua marker dari database akan segera hadir.
        </p>
        <a href="{{ route('admin.dashboard') }}" class="quick-link-btn text-lg px-8 py-4">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection