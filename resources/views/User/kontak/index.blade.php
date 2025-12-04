{{-- resources/views/kontak.blade.php --}}
@extends('layouts.app')

@section('title', 'Kontak Kami - BBSPGL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/public-kontak.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<section class="contact-section">
    <div class="contact-container">
        
        {{-- Header --}}
        <div class="contact-header">
            <h1>Hubungi Balai Besar Survei dan Pemetaan Geologi</h1>
            <p>Sampaikan pertanyaan, permintaan data, atau masukan Anda melalui formulir berikut atau informasi kontak di samping.</p>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin: 0.5rem 0 0 1.25rem; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Main Grid --}}
        <div class="contact-grid">
            
            {{-- Contact Information --}}
            <div class="contact-info-card">
                <h2>Detail Kontak</h2>
                
                {{-- Alamat --}}
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Alamat Kantor</h3>
                        <p>Jl. Diponegoro No.57<br>Bandung 40122, Indonesia</p>
                    </div>
                </div>
                
                {{-- Telepon --}}
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Telepon/Fax</h3>
                        <p>(022) 7272606</p>
                    </div>
                </div>
                
                {{-- Email --}}
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Email Resmi</h3>
                        <p>info@bbspgl.esdm.go.id</p>
                    </div>
                </div>
                
                {{-- Jam Operasional --}}
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Jam Layanan</h3>
                        <p>Senin - Jumat<br>08.00 - 16.00 WIB</p>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="contact-form-card">
                <h2>Kirim Pesan</h2>
                
                {{-- Sesuaikan action dengan route yang sesuai --}}
                <form action="{{ route('kontak') }}" method="POST" class="contact-form">
                    @csrf
                    
                    {{-- Nama & Email --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span style="color: #dc3545;">*</span></label>
                            <input 
                                type="text" 
                                id="nama" 
                                name="nama" 
                                value="{{ old('nama') }}"
                                required 
                                placeholder="Masukkan nama lengkap Anda">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Anda <span style="color: #dc3545;">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                placeholder="nama@email.com">
                        </div>
                    </div>
                    
                    {{-- Subjek --}}
                    <div class="form-group">
                        <label for="subjek">Subjek Pesan <span style="color: #dc3545;">*</span></label>
                        <input 
                            type="text" 
                            id="subjek" 
                            name="subjek" 
                            value="{{ old('subjek') }}"
                            required 
                            placeholder="Topik pesan Anda">
                    </div>
                    
                    {{-- Pesan --}}
                    <div class="form-group">
                        <label for="pesan">Pesan Anda <span style="color: #dc3545;">*</span></label>
                        <textarea 
                            id="pesan" 
                            name="pesan" 
                            required 
                            placeholder="Tuliskan pesan atau pertanyaan Anda di sini...">{{ old('pesan') }}</textarea>
                    </div>
                    
                    {{-- Submit Button --}}
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        <span>Kirim Pesan</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Map Section (Optional) --}}
        <div class="map-section">
            <h2>Lokasi Kantor Kami</h2>
            <div class="map-container">
                {{-- Ganti dengan embed map yang sesuai --}}
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.7999999999997!2d107.6191!3d-6.9147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTQnNTMuMCJTIDEwN8KwMzcnMDguOCJF!5e0!3m2!1sen!2sid!4v1234567890"
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
// Form validation and loading state
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.contact-form');
    const submitBtn = form.querySelector('.submit-btn');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        submitBtn.querySelector('span').textContent = 'Mengirim...';
    });
});
</script>
@endpush