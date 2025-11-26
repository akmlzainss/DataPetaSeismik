<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataSurveiController;

Route::get('/', [DataSurveiController::class, 'index'])->name('beranda');     // ← Beranda
Route::get('/peta', [DataSurveiController::class, 'peta'])->name('peta');    // nanti kita buat
Route::get('/katalog', [DataSurveiController::class, 'katalog'])->name('katalog'); // nanti kita buat