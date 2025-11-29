<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\LokasiMarker;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total statistik
        $totalSurvei = DataSurvei::count();
        $totalMarker = LokasiMarker::count();
        $totalAdmin = Admin::count();
        $surveiBulanIni = DataSurvei::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();

        // Statistik per tipe survei dengan CASE WHEN untuk match exact value
        $surveiPerTipe = DataSurvei::select(
                            'tipe',
                            DB::raw('COUNT(*) as total')
                        )
                        ->groupBy('tipe')
                        ->orderBy('total', 'desc')
                        ->get();

        // 10 survei terbaru
        $surveiTerbaru = DataSurvei::with('pengunggah')
                                   ->latest()
                                   ->take(10)
                                   ->get();

        // Survei per tahun (5 tahun terakhir) dengan breakdown per tipe
        $surveiPerTahun = DataSurvei::select(
                            'tahun',
                            DB::raw('COUNT(*) as total'),
                            DB::raw('SUM(CASE WHEN tipe = "2D" THEN 1 ELSE 0 END) as tipe_2d'),
                            DB::raw('SUM(CASE WHEN tipe = "3D" THEN 1 ELSE 0 END) as tipe_3d'),
                            DB::raw('SUM(CASE WHEN tipe = "HR" THEN 1 ELSE 0 END) as tipe_hr'),
                            DB::raw('SUM(CASE WHEN tipe NOT IN ("2D", "3D", "HR") THEN 1 ELSE 0 END) as tipe_lainnya')
                        )
                        ->groupBy('tahun')
                        ->orderBy('tahun', 'desc')
                        ->take(5)
                        ->get();

        return view('admin.dashboard', compact(
            'totalSurvei',
            'totalMarker',
            'totalAdmin',
            'surveiBulanIni',
            'surveiPerTipe',
            'surveiTerbaru',
            'surveiPerTahun'
        ));
    }
}