<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataSurveiController;

// Halaman utama (dashboard publik)
Route::get('/', [DataSurveiController::class, 'index'])->name('dashboard');

// Detail satu data survei (opsional untuk nanti)
Route::get('/survei/{dataSurvei}', [DataSurveiController::class, 'show'])
     ->name('survei.show');