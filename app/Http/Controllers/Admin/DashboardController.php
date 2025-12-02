<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\LokasiMarker;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== STATISTIK UTAMA (4 CARDS) ==========
        $totalSurvei = DataSurvei::count();
        $totalMarker = LokasiMarker::count();
        $totalAdmin = Admin::count();
        $surveiBulanIni = DataSurvei::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();

        // ========== STATISTIK PER TIPE (SIMPLE) ==========
        $surveiPerTipe = DataSurvei::select(
                            'tipe',
                            DB::raw('COUNT(*) as total')
                        )
                        ->groupBy('tipe')
                        ->orderBy('total', 'desc')
                        ->get();

        // ========== AKTIVITAS TERBARU (5 ITEMS) ==========
        $surveiTerbaru = DataSurvei::with('pengunggah')
                                   ->latest()
                                   ->take(5)
                                   ->get();

        // ========== SURVEI PER TAHUN (3 TAHUN TERAKHIR SAJA) ==========
        $surveiPerTahun = DataSurvei::select(
                            'tahun',
                            DB::raw('COUNT(*) as total'),
                            DB::raw('SUM(CASE WHEN tipe = "2D" THEN 1 ELSE 0 END) as tipe_2d'),
                            DB::raw('SUM(CASE WHEN tipe = "3D" THEN 1 ELSE 0 END) as tipe_3d'),
                            DB::raw('SUM(CASE WHEN tipe = "HR" THEN 1 ELSE 0 END) as tipe_hr'),
                            DB::raw('SUM(CASE WHEN tipe NOT IN ("2D", "3D", "HR") THEN 1 ELSE 0 END) as tipe_lainnya')
                        )
                        ->whereYear('created_at', '>=', now()->year - 2) // Hanya 3 tahun terakhir
                        ->groupBy('tahun')
                        ->orderBy('tahun', 'desc')
                        ->get();

        // ========== GRAFIK TREND BULANAN (6 BULAN TERAKHIR) ==========
        $trendBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subMonths($i);
            $jumlah = DataSurvei::whereYear('created_at', $tanggal->year)
                ->whereMonth('created_at', $tanggal->month)
                ->count();
            
            $trendBulanan[] = [
                'bulan' => $tanggal->format('M'),
                'bulan_lengkap' => $tanggal->format('F Y'),
                'jumlah' => $jumlah
            ];
        }

        // ========== TOP 5 WILAYAH ==========
        $topWilayah = DataSurvei::select('wilayah', DB::raw('COUNT(*) as total'))
            ->groupBy('wilayah')
            ->orderBy('total', 'DESC')
            ->take(5)
            ->get();

        // ========== PERBANDINGAN BULAN INI VS BULAN LALU ==========
        $surveiBulanLalu = DataSurvei::whereMonth('created_at', now()->subMonth()->month)
                                     ->whereYear('created_at', now()->subMonth()->year)
                                     ->count();
        
        $pertumbuhanBulanan = $surveiBulanLalu > 0 
            ? round((($surveiBulanIni - $surveiBulanLalu) / $surveiBulanLalu) * 100, 1) 
            : 0;

        // ========== STATUS MARKER (QUICK INFO) ==========
        $surveiDenganMarker = DataSurvei::has('lokasi')->count();
        $surveiTanpaMarker = DataSurvei::doesntHave('lokasi')->count();
        $persentaseMarker = $totalSurvei > 0 ? round(($surveiDenganMarker / $totalSurvei) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'totalSurvei',
            'totalMarker',
            'totalAdmin',
            'surveiBulanIni',
            'surveiPerTipe',
            'surveiTerbaru',
            'surveiPerTahun',
            'trendBulanan',
            'topWilayah',
            'pertumbuhanBulanan',
            'surveiDenganMarker',
            'surveiTanpaMarker',
            'persentaseMarker'
        ));
    }
}