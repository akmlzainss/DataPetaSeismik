<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\GridKotak;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ========== STATISTIK UTAMA (4 CARDS) ==========
        $totalSurvei = DataSurvei::count();
        $totalGridTerisi = GridKotak::where('status', 'filled')->count();
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

        // ========== AKTIVITAS TERBARU (10 ITEMS) ==========
        $surveiTerbaru = DataSurvei::with('pengunggah')
            ->latest()
            ->take(10)
            ->get();

        // ========== SURVEI PER TAHUN (DENGAN PAGINATION) ==========
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
            ->paginate(5, ['*'], 'tahun_page'); // 5 tahun per halaman

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

        // ========== TOP 10 WILAYAH ==========
        $topWilayah = DataSurvei::select('wilayah', DB::raw('COUNT(*) as total'))
            ->groupBy('wilayah')
            ->orderBy('total', 'DESC')
            ->take(10)
            ->get();

        // ========== PERBANDINGAN BULAN INI VS BULAN LALU ==========
        $bulanLalu = now()->subMonth();
        $surveiBulanLalu = DataSurvei::whereMonth('created_at', $bulanLalu->month)
            ->whereYear('created_at', $bulanLalu->year)
            ->count();

        $pertumbuhanBulanan = $surveiBulanLalu > 0
            ? round((($surveiBulanIni - $surveiBulanLalu) / $surveiBulanLalu) * 100, 1)
            : ($surveiBulanIni > 0 ? 100 : 0);

        // ========== STATUS GRID (QUICK INFO) ==========
        $surveiDenganGrid = DataSurvei::has('gridKotak')->count();
        $surveiTanpaGrid = DataSurvei::doesntHave('gridKotak')->count();
        $persentaseGrid = $totalSurvei > 0 ? round(($surveiDenganGrid / $totalSurvei) * 100, 1) : 0;

        // Jika request AJAX untuk pagination
        if ($request->ajax() && $request->has('tahun_page')) {
            return response()->json([
                'html' => view('admin.partials.tahun-pagination', compact('surveiPerTahun'))->render(),
                'pagination' => view('admin.partials.pagination-controls', ['paginator' => $surveiPerTahun, 'pageParam' => 'tahun_page'])->render()
            ]);
        }

        return view('admin.dashboard', compact(
            'totalSurvei',
            'totalGridTerisi',
            'totalAdmin',
            'surveiBulanIni',
            'surveiPerTipe',
            'surveiTerbaru',
            'surveiPerTahun',
            'trendBulanan',
            'topWilayah',
            'pertumbuhanBulanan',
            'surveiDenganGrid',
            'surveiTanpaGrid',
            'persentaseGrid'
        ));
    }
}
