<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    | Mengganti default dari 'web'/'users' menjadi 'admin'/'admins'.
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'admin'), // DIGANTI: Default Guard adalah 'admin'
        'passwords' => env('AUTH_PASSWORD_BROKER', 'admins'), // DIGANTI: Broker default adalah 'admins'
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | Kita hanya membutuhkan guard 'admin' dan 'web' (untuk kompatibilitas Laravel)
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'admins', // DIGANTI: Walaupun namanya 'web', kita arahkan ke provider 'admins'
        ],

        // --- GUARD BARU UNTUK ADMIN ---
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins', // Menggunakan provider yang sama (admins)
        ],

        // --- GUARD UNTUK PEGAWAI INTERNAL ---
        'pegawai' => [
            'driver' => 'session',
            'provider' => 'pegawai_internal',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | Kita hanya mendefinisikan provider 'admins' yang mengarah ke Model Admin
    */

    'providers' => [
        // Hapus atau abaikan provider 'users' jika Anda tidak memiliki tabel 'users'

        // --- PROVIDER UNTUK ADMIN ---
        'admins' => [
            'driver' => 'eloquent',
            // Pastikan ini menunjuk ke Model Admin yang Anda buat
            'model' => App\Models\Admin::class, 
        ],

        // --- PROVIDER UNTUK PEGAWAI INTERNAL ---
        'pegawai_internal' => [
            'driver' => 'eloquent',
            'model' => App\Models\PegawaiInternal::class,
        ],

        // Jika Anda ingin tetap menggunakan 'users' sebagai nama provider default
        // 'users' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\Admin::class, 
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    | Konfigurasi reset password menggunakan provider 'admins'
    */

    'passwords' => [
        // Mengganti 'users' menjadi 'admins'
        'admins' => [
            'provider' => 'admins',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];