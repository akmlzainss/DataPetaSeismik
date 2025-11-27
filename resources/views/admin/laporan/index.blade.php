@extends('layouts.admin')
@section('title', 'Laporan - Admin BBSPGL')

@section('content')
<div class="page-header">
    <h1>Laporan & Statistik</h1>
    <p>Laporan bulanan, tahunan, dan ekspor data</p>
</div>

<div class="welcome-section">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <div class="stat-card text-center">
            <h3 class="text-5xl mb-3">Report</h3>
            <h3 class="text-2xl font-bold text-[#003366]">12</h3>
            <p class="text-gray-600">Laporan Bulan Ini</p>
        </div>
        <div class="stat-card text-center">
            <h3 class="text-5xl mb-3">Chart</h3>
            <h3 class="text-2xl font-bold text-[#003366]">248</h3>
            <p class="text-gray-600">Total Data Survei</p>
        </div>
        <div class="stat-card text-center">
            <h3 class="text-5xl mb-3">Download</h3>
            <h3 class="text-2xl font-bold text-[#003366]">89</h3>
            <p class="text-gray-600">File Diunduh</p>
        </div>
    </div>

    <div class="text-center py-12 bg-yellow-50 rounded-2xl border-2 border-yellow-200">
        <h2 class="text-3xl font-bold text-[#003366] mb-4">Fitur Export PDF & Excel</h2>
        <p class="text-lg text-gray-700">Sedang dalam tahap pengembangan tahap akhir.</p>
    </div>
</div>
@endsection