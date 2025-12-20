@extends('layouts.app')

@section('title', 'Sedang Maintenance - BBSPGL')

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
            color: #636e72;
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

        .error-illustration {
            font-size: 5rem;
            color: #636e72;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="error-page">
        <div class="error-content">
            <div class="error-illustration">
                <i class="fa-solid fa-screwdriver-wrench fa-bounce" style="--fa-animation-duration: 3s;"></i>
            </div>
            <div class="error-code">503</div>
            <h1 class="error-title">Sedang Dalam Pemeliharaan</h1>
            <p class="error-message">
                Kami sedang melakukan peningkatan sistem untuk pelayanan yang lebih baik. <br>
                Silakan kembali lagi beberapa saat nanti.
            </p>
        </div>
    </div>
@endsection
