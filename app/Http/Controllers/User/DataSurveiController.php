<?php

namespace App\Http\Controllers;

use App\Models\DataSurvei;
use Illuminate\Http\Request;

class DataSurveiController extends Controller
{
    // Halaman utama dashboard publik
    public function index()
    {
        // Ambil semua data survei beserta lokasi marker-nya (eager loading)
        $surveis = DataSurvei::with('lokasi')->latest()->get();

        return view('dashboard', compact('surveis'));
    }

    // Jika nanti butuh halaman detail satu data survei
    public function show(DataSurvei $dataSurvei)
    {
        $dataSurvei->load('lokasi');
        return view('survei.show', compact('dataSurvei'));
    }
}