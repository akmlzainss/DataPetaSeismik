<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\LokasiMarker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // ========== STATISTIK UNTUK HOMEPAGE ==========
        
        // Total data survei
        $totalSurvei = DataSurvei::count();
        
        // Jumlah wilayah unik
        $totalWilayah = DataSurvei::distinct('wilayah')->count('wilayah');
        
        // Data survei bulan ini
        $surveiBulanIni = DataSurvei::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
        
        // Tahun beroperasi (dari survei pertama)
        $tahunPertama = DataSurvei::min('tahun');
        $tahunBeroperasi = $tahunPertama ? (date('Y') - $tahunPertama + 1) : 0;
        
        // ========== FEATURED SURVEYS (6 TERBARU) ==========
        
        $featuredSurveys = DataSurvei::with(['pengunggah', 'lokasi'])
                                     ->latest()
                                     ->take(6)
                                     ->get();
        
        // ========== LATEST UPDATES (5 TERKINI UNTUK TIMELINE) ==========
        
        $latestUpdates = DataSurvei::latest()
                                   ->take(5)
                                   ->get();
        
        // ========== SURVEI PER TIPE (UNTUK CHART/VISUAL) ==========
        
        $surveiPerTipe = DataSurvei::select('tipe', DB::raw('COUNT(*) as total'))
                                   ->groupBy('tipe')
                                   ->orderBy('total', 'desc')
                                   ->get();
        
        // ========== DATA UNTUK MAP PREVIEW ==========
        
        // Ambil semua marker untuk ditampilkan di peta
        $markers = LokasiMarker::with('survei')
                               ->take(50) // Limit untuk performa
                               ->get();
        
        // Count total marker
        $totalMarker = LokasiMarker::count();
        
        // ========== TOP 5 WILAYAH ==========
        
        $topWilayah = DataSurvei::select('wilayah', DB::raw('COUNT(*) as total'))
                                ->groupBy('wilayah')
                                ->orderBy('total', 'DESC')
                                ->take(5)
                                ->get();
        
        return view('user.homepage.index', compact(
            'totalSurvei',
            'totalWilayah',
            'surveiBulanIni',
            'tahunBeroperasi',
            'featuredSurveys',
            'latestUpdates',
            'surveiPerTipe',
            'markers',
            'totalMarker',
            'topWilayah'
        ));
    }
}