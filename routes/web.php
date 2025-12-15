<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataSurveiController;
use App\Http\Controllers\Admin\LokasiMarkerController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\InfoController;
use App\Http\Controllers\User\KatalogController;
use App\Http\Controllers\User\KontakController;
use App\Http\Controllers\User\PetaController;
use App\Http\Controllers\User\TentangKamiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

// Guest Admin (belum login)
Route::middleware('guest:admin')->group(function () {
    Route::get('/bbspgl-admin/masuk', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/bbspgl-admin/masuk', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');

    // Forgot Password Routes
    Route::get('/bbspgl-admin/lupa-password', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/bbspgl-admin/lupa-password', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:3,1');
    Route::get('/bbspgl-admin/reset-password/{token}', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/bbspgl-admin/reset-password', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'reset'])->middleware('throttle:3,1');
});

// Authenticated Admin
Route::prefix('bbspgl-admin')->name('admin.')->middleware('auth.admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Survei (RESOURCE ROUTES)
    Route::resource('data-survei', DataSurveiController::class)
        ->parameters(['data-survei' => 'dataSurvei'])
        ->names([
            'index' => 'data_survei.index',
            'create' => 'data_survei.create',
            'store' => 'data_survei.store',
            'show' => 'data_survei.show',    // Route untuk menampilkan detail di browser
            'edit' => 'data_survei.edit',
            'update' => 'data_survei.update',
            'destroy' => 'data_survei.destroy',
        ]);

    // Tambahan Route untuk JS (mengembalikan JSON)
    // PENTING: Gunakan URI yang unik (e.g., /data-survei-json/ atau /data-survei/{id}/json)
    // agar TIDAK menimpa route 'show' di atas.
    Route::get('/data-survei-json/{dataSurvei}', [DataSurveiController::class, 'showJson'])
        ->name('data_survei.show_json');


    // Lokasi Marker
    Route::get('/lokasi-marker', [LokasiMarkerController::class, 'index'])->name('lokasi_marker.index');
    Route::post('/lokasi-marker', [LokasiMarkerController::class, 'store'])->name('lokasi_marker.store');
    Route::put('/lokasi-marker/{id}', [LokasiMarkerController::class, 'update'])->name('lokasi_marker.update');
    Route::delete('/lokasi-marker/{id}', [LokasiMarkerController::class, 'destroy'])->name('lokasi_marker.destroy');
    Route::get('/geocode', [LokasiMarkerController::class, 'geocode'])->name('geocode');


    // Laporan
    Route::resource('laporan', LaporanController::class)->names('laporan');

    // Export Routes
    Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');

    // Pengaturan
    Route::controller(PengaturanController::class)
        ->prefix('pengaturan')
        ->name('pengaturan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/profile', 'updateProfile')->name('update.profile');
            Route::put('/password', 'updatePassword')->name('update.password');
            Route::post('/clear-cache', 'clearCache')->name('clear.cache');
        });

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (USER UMUM)
|--------------------------------------------------------------------------
*/

// --- ROUTE HALAMAN PUBLIK (User) ---

// Beranda (Homepage)
Route::get('/', [HomeController::class, 'index'])->name('beranda');

// Katalog Data Survei
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

// Peta Interaktif
Route::get('/peta', [PetaController::class, 'index'])->name('peta');

// Tentang Kami
Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('tentang');

// Kontak
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'submit'])->name('kontak.submit')->middleware('throttle:5,1');

// Halaman Informasi (Footer Links)
Route::get('/panduan-pengguna', [InfoController::class, 'panduan'])->name('panduan');
Route::get('/faq', [InfoController::class, 'faq'])->name('faq');
Route::get('/kebijakan-privasi', [InfoController::class, 'privasi'])->name('privasi');
Route::get('/syarat-ketentuan', [InfoController::class, 'syarat'])->name('syarat');
Route::get('/bantuan', [InfoController::class, 'bantuan'])->name('bantuan');

// Catatan: Setelah ini didefinisikan, Anda harus mengganti '#' 
// dengan route() yang sesuai di layouts/app.blade.php.
