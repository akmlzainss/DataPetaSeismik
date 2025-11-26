<?php

namespace App\Http\Controllers;

use App\Models\DataSurvei;
use Illuminate\Http\Request;

class DataSurveiController extends Controller
{
    public function index()
    {
        $surveis = DataSurvei::with('lokasi')->latest()->get();
        return view('user.dashboard', compact('surveis'));
    }

    public function show(DataSurvei $dataSurvei)
    {
        $dataSurvei->load('lokasi');
        return view('survei.show', compact('dataSurvei'));
    }
public function peta()
{
    $surveis = DataSurvei::with('lokasi')->get();
    return view('user.peta', compact('surveis'));
}

public function katalog()
{
    return view('user.katalog'); // nanti kita buat view-nya
}
}