{{-- resources/views/admin/pengaturan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pengaturan Sistem - Admin BBSPGL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-pengaturan.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1>Pengaturan Sistem</h1>
    <p>Kelola profil, keamanan, dan konfigurasi sistem</p>
</div>

{{-- Alert Messages --}}
@if(session('success'))
<div class="alert-message success">
    <svg viewBox="0 0 24 24" fill="currentColor">
        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="alert-message error">
    <svg viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
    </svg>
    <span>{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="alert-message error">
    <svg viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
    </svg>
    <div>
        <strong>Terjadi kesalahan:</strong>
        <ul style="margin: 8px 0 0 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

{{-- Tabs Navigation --}}
<div class="settings-tabs">
    <button class="settings-tab active" data-tab="profil">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        Profil Saya
    </button>
    <button class="settings-tab" data-tab="keamanan">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
        </svg>
        Keamanan
    </button>
    <button class="settings-tab" data-tab="sistem">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
        </svg>
        Informasi Sistem
    </button>
</div>

{{-- TAB 1: PROFIL SAYA --}}
<div id="profil" class="settings-tab-content active">
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-title">Informasi Profil</div>
            <div class="settings-card-subtitle">Perbarui informasi akun admin Anda</div>
        </div>
        <div class="settings-card-body">
            <!-- Profile Avatar -->
            <div class="profile-avatar-section">
                <div class="profile-avatar">
                    {{ strtoupper(substr($admin->nama, 0, 2)) }}
                </div>
                <div class="profile-avatar-info">
                    <h3>{{ $admin->nama }}</h3>
                    <p>{{ $admin->email }}</p>
                    <p style="font-size: 12px; color: #999; margin-top: 4px;">
                        Terdaftar sejak {{ $admin->created_at->format('d F Y') }}
                    </p>
                </div>
            </div>

            <!-- Form Edit Profil -->
            <form action="{{ route('admin.pengaturan.update.profile') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="nama" class="form-label required">Nama Lengkap</label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        class="form-input" 
                        value="{{ old('nama', $admin->nama) }}"
                        required
                    >
                    <span class="form-help">Nama lengkap yang akan ditampilkan di sistem</span>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label required">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        value="{{ old('email', $admin->email) }}"
                        required
                    >
                    <span class="form-help">Email untuk login dan notifikasi sistem</span>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-save">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- TAB 2: KEAMANAN --}}
<div id="keamanan" class="settings-tab-content">
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-title">Ubah Password</div>
            <div class="settings-card-subtitle">Perbarui password untuk keamanan akun Anda</div>
        </div>
        <div class="settings-card-body">
            <div class="info-box-settings warning">
                <p><strong>⚠️ Perhatian:</strong> Pastikan password baru Anda kuat dan berbeda dari password sebelumnya. Password minimal 8 karakter.</p>
            </div>

            <form action="{{ route('admin.pengaturan.update.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="current_password" class="form-label required">Password Saat Ini</label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="form-input"
                        required
                    >
                    <span class="form-help">Masukkan password Anda saat ini untuk verifikasi</span>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label required">Password Baru</label>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        class="form-input"
                        required
                    >
                    <span class="form-help">Minimal 8 karakter, gunakan kombinasi huruf, angka, dan simbol</span>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label required">Konfirmasi Password Baru</label>
                    <input 
                        type="password" 
                        id="new_password_confirmation" 
                        name="new_password_confirmation" 
                        class="form-input"
                        required
                    >
                    <span class="form-help">Ketik ulang password baru untuk konfirmasi</span>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-save">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Security Info --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-title">Informasi Keamanan</div>
            <div class="settings-card-subtitle">Detail sesi dan aktivitas login</div>
        </div>
        <div class="settings-card-body">
            <div class="setting-item">
                <div class="setting-item-info">
                    <div class="setting-item-title">Terakhir Login</div>
                    <div class="setting-item-desc">
                        {{ $admin->updated_at->diffForHumans() }} 
                        ({{ $admin->updated_at->format('d F Y, H:i') }})
                    </div>
                </div>
            </div>

            <div class="setting-item">
                <div class="setting-item-info">
                    <div class="setting-item-title">Browser & Perangkat</div>
                    <div class="setting-item-desc">{{ request()->userAgent() }}</div>
                </div>
            </div>

            <div class="setting-item">
                <div class="setting-item-info">
                    <div class="setting-item-title">Alamat IP</div>
                    <div class="setting-item-desc">{{ request()->ip() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TAB 3: INFORMASI SISTEM --}}
<div id="sistem" class="settings-tab-content">
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-title">Informasi Aplikasi</div>
            <div class="settings-card-subtitle">Detail versi dan konfigurasi sistem</div>
        </div>
        <div class="settings-card-body">
            <div class="system-info-grid">
                <div class="system-info-item">
                    <div class="system-info-label">Nama Aplikasi</div>
                    <div class="system-info-value">{{ $systemInfo['app_name'] }}</div>
                </div>

                <div class="system-info-item">
                    <div class="system-info-label">Versi Aplikasi</div>
                    <div class="system-info-value">{{ $systemInfo['app_version'] }}</div>
                </div>

                <div class="system-info-item">
                    <div class="system-info-label">Laravel Version</div>
                    <div class="system-info-value">{{ $systemInfo['laravel_version'] }}</div>
                </div>

                <div class="system-info-item">
                    <div class="system-info-label">PHP Version</div>
                    <div class="system-info-value">{{ $systemInfo['php_version'] }}</div>
                </div>

                <div class="system-info-item">
                    <div class="system-info-label">Database</div>
                    <div class="system-info-value">{{ strtoupper($systemInfo['database']) }}</div>
                </div>

                <div class="system-info-item">
                    <div class="system-info-label">Total Admin</div>
                    <div class="system-info-value">{{ $systemInfo['total_admin'] }} Admin</div>
                </div>

                <div class="system-info-item">
                    <div class="system-info-label">Total Data Survei</div>
                    <div class="system-info-value">{{ number_format($systemInfo['total_survei']) }} Data</div>
                </div>

                <div class="system-info-item">
                    <div class="system-info-label">Disk Usage</div>
                    <div class="system-info-value">
                        {{ $systemInfo['disk_usage']['used'] }} / {{ $systemInfo['disk_usage']['total'] }}
                        <div style="font-size: 12px; color: #666; margin-top: 4px;">
                            ({{ $systemInfo['disk_usage']['percentage'] }}%)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance Actions --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-title">Maintenance & Optimasi</div>
            <div class="settings-card-subtitle">Tools untuk pemeliharaan sistem</div>
        </div>
        <div class="settings-card-body">
            <div class="info-box-settings">
                <p><strong>ℹ️ Info:</strong> Fitur maintenance dapat membantu meningkatkan performa sistem. Gunakan dengan bijak.</p>
            </div>

            <form action="{{ route('admin.pengaturan.clear.cache') }}" method="POST">
                @csrf
                <div class="setting-item">
                    <div class="setting-item-info">
                        <div class="setting-item-title">Clear Application Cache</div>
                        <div class="setting-item-desc">Bersihkan cache aplikasi, konfigurasi, route, dan view</div>
                    </div>
                    <button type="submit" class="btn-secondary" onclick="return confirm('Yakin ingin membersihkan cache?')">
                        Bersihkan Cache
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- About Section --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-title">Tentang Sistem</div>
            <div class="settings-card-subtitle">Informasi institusi dan hak cipta</div>
        </div>
        <div class="settings-card-body">
            <div style="text-align: center; padding: 20px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #003366; margin-bottom: 8px;">
                    Balai Besar Survei dan Pemetaan Geologi
                </h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 16px;">
                    Kementerian Energi dan Sumber Daya Mineral
                </p>
                <p style="color: #999; font-size: 13px; line-height: 1.6;">
                    Sistem Informasi Survei Seismik Nasional<br>
                    © {{ date('Y') }} BBSPGL - Kementerian ESDM<br>
                    Hak Cipta Dilindungi Undang-Undang
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('.settings-tab');
    const contents = document.querySelectorAll('.settings-tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
});
</script>
@endpush