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
        <button class="settings-tab" data-tab="user">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            User
        </button>
        <button class="settings-tab" data-tab="approval">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            Approval Pegawai
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

    {{-- TAB 3: USER (Pegawai ESDM) - TABLE WITH CRUD --}}
    <div id="user" class="settings-tab-content">
        {{-- Pegawai ESDM Table --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <div class="settings-card-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                    Daftar Akun Pegawai ESDM
                </div>
                <div class="settings-card-subtitle">Manage akun Pegawai ESDM ESDM yang terdaftar di sistem ({{ $pegawaiInternal->total() }} total)</div>
            </div>
            <div class="settings-card-body">
                @if ($pegawaiInternal->count() > 0)
                    {{-- Table Responsive Wrapper --}}
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                            <thead>
                                <tr style="background: #f8f9fa; border-bottom: 2px solid #003366;">
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">#</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">Nama</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">Email</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">NIP</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">Jabatan</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #003366;">Status</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #003366;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pegawaiInternal as $index => $pegawai)
                                    <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                                        <td style="padding: 12px; color: #666;">{{ $pegawaiInternal->firstItem() + $index }}</td>
                                        <td style="padding: 12px;">
                                            <strong style="color: #003366;">{{ $pegawai->nama }}</strong>
                                        </td>
                                        <td style="padding: 12px;">
                                            <span style="color: #666; font-size: 14px;">
                                                <i class="fas fa-envelope" style="margin-right: 5px; color: #003366;"></i>
                                                {{ $pegawai->email }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px; color: #666;">{{ $pegawai->nip ?? '-' }}</td>
                                        <td style="padding: 12px; color: #666;">{{ $pegawai->jabatan ?? '-' }}</td>
                                        <td style="padding: 12px; text-align: center;">
                                            @if ($pegawai->is_approved)
                                                <span style="background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                    <i class="fas fa-check-circle"></i> Approved
                                                </span>
                                            @else
                                                <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                    <i class="fas fa-clock"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            <button onclick="openEditModal({{ $pegawai->id }}, '{{ addslashes($pegawai->nama) }}', '{{ $pegawai->email }}', '{{ $pegawai->nip }}', '{{ $pegawai->jabatan }}', {{ $pegawai->is_approved ? 'true' : 'false' }})" 
                                                    style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-right: 5px; font-size: 13px;" 
                                                    title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button onclick="confirmDeletePegawai({{ $pegawai->id }}, '{{ addslashes($pegawai->nama) }}')" 
                                                    style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 13px;" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="pagination-wrapper" style="margin-top: 20px;">
                        {{ $pegawaiInternal->links() }}
                        @if ($pegawaiInternal->hasPages())
                            <div class="pagination-info" style="text-align: center; color: #666; margin-top: 10px; font-size: 14px;">
                                Menampilkan {{ $pegawaiInternal->firstItem() }} - {{ $pegawaiInternal->lastItem() }} dari {{ $pegawaiInternal->total() }} pegawai
                            </div>
                        @endif
                    </div>
                @else
                    <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 8px;">
                        <i class="fas fa-users" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                        <h3 style="color: #666; margin-bottom: 10px;">Belum Ada Pegawai Terdaftar</h3>
                        <p style="color: #999;">Pegawai akan muncul di sini setelah mereka mendaftar di sistem.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Statistics Card --}}
        <div class="settings-card">
            <div class="settings-card-header">
                <div class="settings-card-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                    Statistik Pegawai
                </div>
                <div class="settings-card-subtitle">Ringkasan akun Pegawai ESDM</div>
            </div>
            <div class="settings-card-body">
                <div class="security-info-grid">
                    <div class="security-info-item">
                        <div class="security-info-icon" style="background: #d4edda; color: #155724;">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                            </svg>
                        </div>
                        <div class="security-info-content">
                            <div class="security-info-label">Pegawai Ter-Approve</div>
                            <div class="security-info-value">{{ \App\Models\PegawaiInternal::approved()->count() }}</div>
                        </div>
                    </div>

                    <div class="security-info-item">
                        <div class="security-info-icon" style="background: #fff3cd; color: #856404;">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                            </svg>
                        </div>
                        <div class="security-info-content">
                            <div class="security-info-label">Pending Approval</div>
                            <div class="security-info-value">{{ \App\Models\PegawaiInternal::pendingApproval()->count() }}</div>
                        </div>
                    </div>

                    <div class="security-info-item">
                        <div class="security-info-icon" style="background: #d1ecf1; color: #0c5460;">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </div>
                        <div class="security-info-content">
                            <div class="security-info-label">Total Pegawai</div>
                            <div class="security-info-value">{{ \App\Models\PegawaiInternal::count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 4: APPROVAL PEGAWAI - PENDING APPROVALS --}}
    <div id="approval" class="settings-tab-content">
        <div class="settings-card">
            <div class="settings-card-header">
                <div class="settings-card-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Approval Pegawai ESDM Pending
                </div>
                <div class="settings-card-subtitle">Daftar pegawai yang menunggu persetujuan admin ({{ \App\Models\PegawaiInternal::pendingApproval()->count() }} pending)</div>
            </div>
            <div class="settings-card-body">
                @php
                    $pendingPegawai = \App\Models\PegawaiInternal::pendingApproval()->latest()->paginate(10, ['*'], 'pending');
                @endphp

                @if ($pendingPegawai->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                            <thead>
                                <tr style="background: #fff3cd; border-bottom: 2px solid #856404;">
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #856404;">#</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #856404;">Nama</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #856404;">Email</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #856404;">NIP</th>
                                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #856404;">Jabatan</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #856404;">Tgl Daftar</th>
                                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #856404;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingPegawai as $index => $pegawai)
                                    <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.background='#fffbf0'" onmouseout="this.style.background='white'">
                                        <td style="padding: 12px; color: #666;">{{ $pendingPegawai->firstItem() + $index }}</td>
                                        <td style="padding: 12px;">
                                            <strong style="color: #856404;">{{ $pegawai->nama }}</strong>
                                        </td>
                                        <td style="padding: 12px;">
                                            <span style="color: #666; font-size: 14px;">
                                                <i class="fas fa-envelope"></i> {{ $pegawai->email }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px; color: #666;">{{ $pegawai->nip ?? '-' }}</td>
                                        <td style="padding: 12px; color: #666;">{{ $pegawai->jabatan ?? '-' }}</td>
                                        <td style="padding: 12px; text-align: center; color: #666; font-size: 13px;">
                                            {{ $pegawai->created_at->format('d M Y') }}
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            <div style="display: flex; gap: 8px; justify-content: center;">
                                                {{-- Approve Button --}}
                                                <form method="POST" action="{{ route('admin.pengaturan.pegawai.approve', $pegawai->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 500; transition: all 0.3s;">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>

                                                {{-- Reject Button --}}
                                                <form method="POST" action="{{ route('admin.pengaturan.pegawai.reject', $pegawai->id) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menolak pegawai ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 500; transition: all 0.3s;">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($pendingPegawai->hasPages())
                        <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-top: 1px solid #eee;">
                            <div style="color: #666; font-size: 14px;">
                                Menampilkan {{ $pendingPegawai->firstItem() }} - {{ $pendingPegawai->lastItem() }} dari {{ $pendingPegawai->total() }} pegawai pending
                            </div>
                            <div>
                                {{ $pendingPegawai->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
                        <p style="font-size: 16px;">Tidak ada pegawai yang menunggu approval.</p>
                        <p style="font-size: 14px; color: #999; margin-top: 8px;">Semua pendaftaran sudah diproses.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL EDIT PEGAWAI --}}
    <div id="editPegawaiModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
        <div class="modal-container" style="background: white; width: 90%; max-width: 500px; border-radius: 12px; padding: 30px; position: relative;">
            <button onclick="closeEditModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">×</button>
            
            <h3 style="color: #003366; margin-bottom: 20px; font-size: 20px;">
                <i class="fas fa-user-edit"></i> Edit Data Pegawai
            </h3>
            
            <form id="editPegawaiForm" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Nama Lengkap <span style="color: red;">*</span></label>
                    <input type="text" name="nama" id="edit_nama" required class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Email ESDM <span style="color: red;">*</span></label>
                    <input type="email" name="email" id="edit_email" required class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">NIP</label>
                    <input type="text" name="nip" id="edit_nip" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Jabatan</label>
                    <input type="text" name="jabatan" id="edit_jabatan" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Status Approval <span style="color: red;">*</span></label>
                    <select name="is_approved" id="edit_is_approved" required class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="1">✅ Approved (Aktif)</option>
                        <option value="0">⏳ Pending / Revoke</option>
                    </select>
                    <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> Ubah ke "Pending" untuk mencabut akses login pegawai
                    </small>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeEditModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        Batal
                    </button>
                    <button type="submit" style="background: #003366; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DELETE CONFIRMATION --}}
    <div id="deletePegawaiModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
        <div class="modal-container" style="background: white; width: 90%; max-width: 400px; border-radius: 12px; padding: 30px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #fee; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-exclamation-triangle" style="font-size: 30px; color: #dc3545;"></i>
            </div>
            
            <h3 style="color: #003366; margin-bottom: 10px; font-size: 18px;">Konfirmasi Hapus</h3>
            <p style="color: #666; margin-bottom: 20px;">
                Apakah Anda yakin ingin menghapus pegawai <strong id="deletePegawaiName"></strong>?<br>
                Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>.
            </p>
            
            <form id="deletePegawaiForm" method="POST" action="">
                @csrf
                @method('DELETE')
                
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="button" onclick="closeDeleteModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        Batal
                    </button>
                    <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JavaScript for Pegawai Modals --}}
    <script>
    function openEditModal(id, nama, email, nip, jabatan, isApproved) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_nip').value = nip || '';
        document.getElementById('edit_jabatan').value = jabatan || '';
        document.getElementById('edit_is_approved').value = isApproved ? '1' : '0';
        
        // Set form action
        document.getElementById('editPegawaiForm').action = `/bbspgl-admin/pengaturan/pegawai/${id}`;
        
        // Show modal
        document.getElementById('editPegawaiModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editPegawaiModal').style.display = 'none';
    }

    function confirmDeletePegawai(id, nama) {
        document.getElementById('deletePegawaiName').textContent = nama;
        
        // Set form action
        document.getElementById('deletePegawaiForm').action = `/bbspgl-admin/pengaturan/pegawai/${id}`;
        
        // Show modal
        document.getElementById('deletePegawaiModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deletePegawaiModal').style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('editPegawaiModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    document.getElementById('deletePegawaiModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
    </script>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabs = document.querySelectorAll('.settings-tab');
            const contents = document.querySelectorAll('.settings-tab-content');

            function switchTab(targetTab) {
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));

                // Add active class to target tab button
                const targetButton = document.querySelector(`.settings-tab[data-tab="${targetTab}"]`);
                if (targetButton) {
                    targetButton.classList.add('active');
                }

                // Add active class to target content
                const targetContent = document.getElementById(targetTab);
                if (targetContent) {
                    targetContent.classList.add('active');
                }

                // Update URL hash without page reload
                history.replaceState(null, null, `#${targetTab}`);
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;
                    switchTab(targetTab);
                });
            });

            // Handle URL hash on page load (for direct links like /pengaturan#keamanan)
            function handleHashChange() {
                const hash = window.location.hash.replace('#', '');
                if (hash && document.getElementById(hash)) {
                    switchTab(hash);
                }
            }

            // Check hash on load
            handleHashChange();

            // Listen for hash changes
            window.addEventListener('hashchange', handleHashChange);
        });
    </script>
@endpush

