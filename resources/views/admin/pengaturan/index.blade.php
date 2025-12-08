{{-- resources/views/admin/pengaturan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pengaturan - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-pengaturan.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Pengaturan</h1>
        <p>Kelola profil dan keamanan akun administrator</p>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="alert-message success">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-message error">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-message error">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
            </svg>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin: 8px 0 0 20px;">
                    @foreach ($errors->all() as $error)
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
                <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
            Profil Saya
        </button>
        <button class="settings-tab" data-tab="keamanan">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
            </svg>
            Keamanan
        </button>
    </div>

    {{-- TAB 1: PROFIL SAYA --}}
    <div id="profil" class="settings-tab-content active">
        {{-- Profile Header Card --}}
        <div class="settings-card profile-header-card">
            <div class="profile-header-content">
                <div class="profile-avatar-large">
                    {{ strtoupper(substr($admin->nama, 0, 2)) }}
                </div>
                <div class="profile-header-info">
                    <h2 class="profile-name">{{ $admin->nama }}</h2>
                    <p class="profile-email">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path
                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                        {{ $admin->email }}
                    </p>
                    <p class="profile-meta">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path
                                d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                        </svg>
                        Terdaftar sejak {{ $admin->created_at->format('d F Y') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Edit Profile Form --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <div class="settings-card-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path
                            d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                    </svg>
                    Edit Informasi Profil
                </div>
                <div class="settings-card-subtitle">Perbarui data akun administrator Anda</div>
            </div>
            <div class="settings-card-body">
                <form action="{{ route('admin.pengaturan.update.profile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama" class="form-label required">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            Nama Lengkap
                        </label>
                        <input type="text" id="nama" name="nama" class="form-input"
                            value="{{ old('nama', $admin->nama) }}" placeholder="Masukkan nama lengkap" required>
                        <span class="form-help">Nama lengkap yang akan ditampilkan di seluruh sistem</span>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label required">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path
                                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                            Alamat Email
                        </label>
                        <input type="email" id="email" name="email" class="form-input"
                            value="{{ old('email', $admin->email) }}" placeholder="admin@bbspgl.esdm.go.id" required>
                        <span class="form-help">Email untuk login dan menerima notifikasi sistem</span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
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
        {{-- Change Password Card --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <div class="settings-card-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path
                            d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                    </svg>
                    Ubah Password
                </div>
                <div class="settings-card-subtitle">Perbarui password untuk keamanan akun Anda</div>
            </div>
            <div class="settings-card-body">
                <div class="info-box-settings warning">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
                    </svg>
                    <div>
                        <strong>Perhatian Keamanan</strong>
                        <p>Pastikan password baru Anda kuat dan berbeda dari password sebelumnya. Minimal 8 karakter dengan
                            kombinasi huruf, angka, dan simbol.</p>
                    </div>
                </div>

                <form action="{{ route('admin.pengaturan.update.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password" class="form-label required">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path
                                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
                            </svg>
                            Password Saat Ini
                        </label>
                        <input type="password" id="current_password" name="current_password" class="form-input"
                            placeholder="Masukkan password saat ini" required>
                        <span class="form-help">Masukkan password Anda saat ini untuk verifikasi</span>
                    </div>

                    <div class="form-group">
                        <label for="new_password" class="form-label required">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                            </svg>
                            Password Baru
                        </label>
                        <input type="password" id="new_password" name="new_password" class="form-input"
                            placeholder="Masukkan password baru" required>
                        <span class="form-help">Minimal 8 karakter, gunakan kombinasi huruf, angka, dan simbol</span>
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation" class="form-label required">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                            </svg>
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            class="form-input" placeholder="Ketik ulang password baru" required>
                        <span class="form-help">Ketik ulang password baru untuk konfirmasi</span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                            </svg>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Security Info Card --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <div class="settings-card-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path
                            d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                    </svg>
                    Informasi Keamanan
                </div>
                <div class="settings-card-subtitle">Detail sesi dan aktivitas login</div>
            </div>
            <div class="settings-card-body">
                <div class="security-info-grid">
                    <div class="security-info-item">
                        <div class="security-info-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                            </svg>
                        </div>
                        <div class="security-info-content">
                            <div class="security-info-label">Terakhir Login</div>
                            <div class="security-info-value">
                                {{ $admin->updated_at->diffForHumans() }}
                            </div>
                            <div class="security-info-detail">
                                {{ $admin->updated_at->format('d F Y, H:i') }} WIB
                            </div>
                        </div>
                    </div>

                    <div class="security-info-item">
                        <div class="security-info-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M20.5 3l-.16.03L15 5.1 9 3 3.36 4.9c-.21.07-.36.25-.36.48V20.5c0 .28.22.5.5.5l.16-.03L9 18.9l6 2.1 5.64-1.9c.21-.07.36-.25.36-.48V3.5c0-.28-.22-.5-.5-.5zM15 19l-6-2.11V5l6 2.11V19z" />
                            </svg>
                        </div>
                        <div class="security-info-content">
                            <div class="security-info-label">Browser & Perangkat</div>
                            <div class="security-info-value">
                                {{ request()->userAgent() }}
                            </div>
                        </div>
                    </div>

                    <div class="security-info-item">
                        <div class="security-info-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                        </div>
                        <div class="security-info-content">
                            <div class="security-info-label">Alamat IP</div>
                            <div class="security-info-value">{{ request()->ip() }}</div>
                            <div class="security-info-detail">Lokasi akses saat ini</div>
                        </div>
                    </div>
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
