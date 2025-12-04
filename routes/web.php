<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataSurveiController;
use App\Http\Controllers\Admin\LokasiMarkerController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\User\HomeController;
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
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
});

// Authenticated Admin
Route::prefix('admin')->name('admin.')->middleware('auth.admin')->group(function () {

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


    // Lokasi Marker — DIPERBAIKI: nama route jadi sesuai yang dipakai di controller & view
    Route::resource('lokasi-marker', LokasiMarkerController::class)
        ->parameters(['lokasi-marker' => 'id'])  // pakai id langsung, lebih simpel
        ->except(['show'])                    // tidak perlu show
        ->names([
            'index'   => 'lokasi_marker.index',
            'store'   => 'lokasi_marker.store',
            'destroy' => 'lokasi_marker.destroy',
        ]);

    // Alternatif: Kalau mau pakai route manual (lebih aman & jelas)
    Route::get('/lokasi-marker', [LokasiMarkerController::class, 'index'])->name('lokasi_marker.index');
    Route::post('/lokasi-marker', [LokasiMarkerController::class, 'store'])->name('lokasi_marker.store');
    Route::delete('/lokasi-marker/{id}', [LokasiMarkerController::class, 'destroy'])->name('lokasi_marker.destroy');

    // Route untuk API Geocoding (dengan Server-side Cache)
    Route::get('/geocode', [LokasiMarkerController::class, 'geocode'])->name('geocode');


    // Laporan
    Route::resource('laporan', LaporanController::class)->names('laporan');



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



    // routes/web.php atau admin.php
    Route::post('/admin/upload-tinymce-image', function (Request $request) {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('tinymce', 'public');
            return response()->json(['location' => asset('storage/' . $path)]);
        }
        return response()->json(['error' => 'No file'], 400);
    })->middleware('auth:admin');

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

// Peta Interaktif
Route::get('/peta', [PetaController::class, 'index'])->name('peta');

// Tentang Kami
Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('tentang');

// Kontak
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');

// Catatan: Setelah ini didefinisikan, Anda harus mengganti '#' 
// dengan route() yang sesuai di layouts/app.blade.php.
