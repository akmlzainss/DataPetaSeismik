<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataSurveiController;
use App\Http\Controllers\Admin\LokasiMarkerController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PengaturanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataSurveiController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

// Guest (belum login)
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
});

// Authenticated Admin
Route::middleware('auth.admin')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Survei
    Route::resource('data-survei', DataSurveiController::class)
        ->parameters(['data-survei' => 'dataSurvei'])
        ->names('data_survei');

    // Lokasi Marker
    Route::resource('lokasi-marker', LokasiMarkerController::class)
        ->parameters(['lokasi-marker' => 'lokasiMarker'])
        ->names('lokasi_marker');

    // Laporan
    Route::resource('laporan', LaporanController::class)
        ->names('laporan');

    // Pengguna (Admin Only)
    Route::resource('pengguna', PenggunaController::class)
        ->names('pengguna');

    // Pengaturan
    Route::controller(PengaturanController::class)
        ->prefix('pengaturan')
        ->name('pengaturan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/', 'update')->name('update');
        });

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/', [DataSurveiController::class, 'index'])->name('beranda');     // ← Beranda
Route::get('/peta', [DataSurveiController::class, 'peta'])->name('peta');    // nanti kita buat
Route::get('/katalog', [DataSurveiController::class, 'katalog'])->name('katalog'); // nanti kita buat
