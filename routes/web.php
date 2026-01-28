<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataSurveiController;
use App\Http\Controllers\Admin\GridKotakController; // SISTEM GRID BARU
// LokasiMarkerController removed - sistem marker lama sudah dihapus
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
    Route::post('/bbspgl-admin/masuk', [AdminAuthController::class, 'login'])
        ->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:5,1'));

    // Forgot Password Routes
    Route::get('/bbspgl-admin/lupa-password', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/bbspgl-admin/lupa-password', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('admin.password.email')
        ->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:3,1'));
    Route::get('/bbspgl-admin/reset-password/{token}', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    // Also handle query string token for testing bots: /reset-password?token=xxx
    Route::get('/bbspgl-admin/reset-password', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'showResetFormFromQuery'])->name('admin.password.reset.query');
    Route::post('/bbspgl-admin/reset-password', [App\Http\Controllers\Admin\ForgotPasswordController::class, 'reset'])
        ->name('admin.password.update')
        ->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:3,1'));
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

    // ==========================================
    // GRID KOTAK (SISTEM PETA SEISMIK BARU)
    // ==========================================
    Route::get('/grid-kotak', [GridKotakController::class, 'index'])->name('grid_kotak.index');
    Route::get('/grid-kotak/data', [GridKotakController::class, 'getGridData'])->name('grid_kotak.data');
    Route::post('/grid-kotak/assign', [GridKotakController::class, 'assign'])->name('grid_kotak.assign');
    Route::delete('/grid-kotak/unassign/{gridKotak}/{dataSurvei}', [GridKotakController::class, 'unassign'])->name('grid_kotak.unassign');
    Route::get('/grid-kotak/{id}', [GridKotakController::class, 'show'])->name('grid_kotak.show');

    // Lokasi Marker routes dihapus - sistem sudah diganti dengan Grid Kotak

    // Laporan
    Route::resource('laporan', LaporanController::class)->names('laporan');

    // Export Routes (Laporan)
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');

    // Pengaturan
    Route::controller(PengaturanController::class)
        ->prefix('pengaturan')
        ->name('pengaturan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/profile', 'updateProfile')->name('update.profile');
            Route::put('/password', 'updatePassword')->name('update.password');
            Route::post('/clear-cache', 'clearCache')->name('clear.cache');
            
            // CRUD Pegawai Internal
            Route::put('/pegawai/{id}', 'updatePegawai')->name('pegawai.update');
            Route::delete('/pegawai/{id}', 'deletePegawai')->name('pegawai.delete');
            
            // Approval Pegawai
            Route::post('/pegawai/{id}/approve', 'approvePegawai')->name('pegawai.approve');
            Route::delete('/pegawai/{id}/reject', 'rejectPegawai')->name('pegawai.reject');
        });

    // Approval Pegawai Internal (Backup jika email tidak masuk)
    Route::controller(App\Http\Controllers\Admin\PegawaiApprovalController::class)
        ->prefix('pegawai-approval')
        ->name('pegawai.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/approve', 'approve')->name('approve');
            Route::post('/{id}/reject', 'reject')->name('reject');
            Route::post('/{id}/revoke', 'revoke')->name('revoke');
        });

    // Logout (with /keluar alias for testing bots)
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::post('/keluar', [AdminAuthController::class, 'logout'])->name('keluar'); // Alias for testing
});


/*
|--------------------------------------------------------------------------
| PEGAWAI INTERNAL ROUTES
|--------------------------------------------------------------------------
*/

// Guest Pegawai (belum login)
Route::middleware('guest:pegawai')->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/daftar', [App\Http\Controllers\Pegawai\PegawaiAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/daftar', [App\Http\Controllers\Pegawai\PegawaiAuthController::class, 'register'])
        ->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:3,1'));
    
    Route::get('/masuk', [App\Http\Controllers\Pegawai\PegawaiAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/masuk', [App\Http\Controllers\Pegawai\PegawaiAuthController::class, 'login'])
        ->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:5,1'));

    // Forgot Password Routes
    Route::get('/lupa-password', [App\Http\Controllers\Pegawai\PegawaiForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-password', [App\Http\Controllers\Pegawai\PegawaiForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email')
        ->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:3,1'));
    Route::get('/reset-password/{token}', [App\Http\Controllers\Pegawai\PegawaiForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::get('/reset-password', [App\Http\Controllers\Pegawai\PegawaiForgotPasswordController::class, 'showResetFormFromQuery'])->name('password.reset.query');
    Route::post('/reset-password', [App\Http\Controllers\Pegawai\PegawaiForgotPasswordController::class, 'reset'])
        ->name('password.update.reset')
        ->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:3,1'));
});

// Email verification (tidak perlu login)
Route::get('/pegawai/verify/{token}', [App\Http\Controllers\Pegawai\PegawaiAuthController::class, 'verifyEmail'])
    ->name('pegawai.verify');

// Authenticated Pegawai
Route::middleware(['auth:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::post('/keluar', [App\Http\Controllers\Pegawai\PegawaiAuthController::class, 'logout'])->name('logout');
    
    // Ubah Password (AJAX)
    Route::put('/ubah-password', [App\Http\Controllers\Pegawai\PegawaiAuthController::class, 'updatePassword'])->name('password.update');
});

// Protected Download - Hanya untuk pegawai internal yang sudah login & verified
Route::get('/download-scan/{id}', [App\Http\Controllers\ScanDownloadController::class, 'download'])
    ->middleware(['auth:pegawai'])
    ->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:10,1')) // Max 10 download per menit
    ->name('scan.download');

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
Route::post('/kontak', [KontakController::class, 'submit'])->name('kontak.submit')->when(!app()->environment('local', 'testing'), fn($route) => $route->middleware('throttle:5,1'));

// Alias routes for compatibility
Route::redirect('/contact', '/kontak', 301);

// Halaman Informasi (Footer Links)
Route::get('/panduan-pengguna', [InfoController::class, 'panduan'])->name('panduan');
Route::get('/faq', [InfoController::class, 'faq'])->name('faq');
Route::get('/kebijakan-privasi', [InfoController::class, 'privasi'])->name('privasi');
Route::get('/syarat-ketentuan', [InfoController::class, 'syarat'])->name('syarat');
Route::get('/bantuan', [InfoController::class, 'bantuan'])->name('bantuan');

// Catatan: Setelah ini didefinisikan, Anda harus mengganti '#' 
// dengan route() yang sesuai di layouts/app.blade.php.


