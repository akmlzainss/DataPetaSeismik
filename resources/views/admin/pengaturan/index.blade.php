@extends('layouts.admin')
@section('title', 'Pengaturan Sistem - Admin BBSPGL')

@section('content')
<div class="page-header">
    <h1>Pengaturan Sistem</h1>
    <p>Konfigurasi aplikasi BBSPGL</p>
</div>

<div class="welcome-section">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 max-w-5xl mx-auto">
        <!-- Info Sistem -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border">
            <h3 class="text-2xl font-bold text-[#003366] mb-6 flex items-center gap-3">
                Gear Icon Sistem Informasi
            </h3>
            <div class="space-y-4 text-gray-700">
                <p><strong>Aplikasi :</strong> Sistem Informasi Survei Seismik</p>
                <p><strong>Instansi :</strong> BBSPGL - Kementerian ESDM</p>
                <p><strong>Versi :</strong> 1.0.0</p>
                <p><strong>Laravel :</strong> {{ app()->version() }}</p>
                <p><strong>PHP :</strong> {{ phpversion() }}</p>
                <p><strong>Database :</strong> {{ config('database.default') }}</p>
                <p><strong>Total Admin :</strong> {{ \App\Models\Admin::count() }}</p>
            </div>
        </div>

        <!-- Fitur Mendatang -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-xl p-8 border">
            <h3 class="text-2xl font-bold text-[#003366] mb-6 flex items-center gap-3">
                Rocket Icon Fitur Mendatang
            </h3>
            <ul class="space-y-4 text-gray-700">
                <li class="flex items-center gap-3"><span class="text-green-600">Check</span> Backup Database Otomatis</li>
                <li class="flex items-center gap-3"><span class="text-green-600">Check</span> Log Aktivitas Admin</li>
                <li class="flex items-center gap-3"><span class="text-yellow-600">Clock</span> Ganti Logo & Tema</li>
                <li class="flex items-center gap-3"><span class="text-yellow-600">Clock</span> API Access Token</li>
                <li class="flex items-center gap-3"><span class="text-red-600">Cross</span> Multi-Factor Authentication</li>
            </ul>
        </div>
    </div>

    <div class="text-center mt-12">
        <p class="text-gray-500 text-sm">© {{ date('Y') }} BBSPGL - Kementerian Energi dan Sumber Daya Mineral</p>
    </div>
</div>
@endsection
