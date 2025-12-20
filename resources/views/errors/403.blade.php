@extends('layouts.app')

@section('title', 'Akses Ditolak - BBSPGL')

@push('styles')
    <style>
        .error-page {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
            background-color: #f9f9f9;
        }

        .error-content {
            max-width: 600px;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: #d63031;
            margin-bottom: 0;
            line-height: 1;
            text-shadow: 2px 2px 0px #eee;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #333;
        }

        .error-message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-block;
            background-color: #ffed00;
            color: #000;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 2px solid #ffed00;
        }

        .btn-home:hover {
            background-color: transparent;
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .error-illustration {
            font-size: 5rem;
            color: #d63031;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="error-page">
        <div class="error-content">
            <div class="error-illustration">
                <i class="fa-solid fa-ban fa-beat-fade"></i>
            </div>
            <div class="error-code">403</div>
            <h1 class="error-title">Akses Ditolak</h1>
            <p class="error-message">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. <br>
                Silakan hubungi administrator jika Anda merasa ini kesalahan.
            </p>
            <a href="{{ route('beranda') }}" class="btn-home">
                <i class="fa-solid fa-house"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
