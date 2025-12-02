<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller; // ← WAJIB, ini yang hilang
use App\Models\DataSurvei;
use App\Models\LokasiMarker;
use Illuminate\Http\Request;

class UserDataSurveiController extends Controller
{
    public function index()
    {
        $surveis = DataSurvei::with('lokasi')->latest()->paginate(6);

        return view('user.dashboard', compact('surveis'));
    }

    public function show(DataSurvei $dataSurvei)
    {
        $dataSurvei->load('lokasi');
        return view('survei.show', compact('dataSurvei'));
    }

    public function peta()
{
    $markers = LokasiMarker::with('survei')->get();
    return view('user.peta', compact('markers'));
}

    public function katalog()
    {
        return view('user.katalog');
    }


}
