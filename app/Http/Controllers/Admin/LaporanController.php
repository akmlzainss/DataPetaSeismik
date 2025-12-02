<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\LokasiMarker;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter berdasarkan request
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan');
        $tipe = $request->input('tipe');

        // ========== LAPORAN PER TIPE SURVEI ==========
        
        $laporanPerTipe = DataSurvei::select('tipe', DB::raw('COUNT(*) as total'))
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->groupBy('tipe')
            ->get();

        // ========== LAPORAN PER TAHUN (5 Tahun Terakhir) ==========
        
        $laporanPerTahun = DataSurvei::select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN tipe = "2D" THEN 1 ELSE 0 END) as tipe_2d'),
                DB::raw('SUM(CASE WHEN tipe = "3D" THEN 1 ELSE 0 END) as tipe_3d'),
                DB::raw('SUM(CASE WHEN tipe = "HR" THEN 1 ELSE 0 END) as tipe_hr'),
                DB::raw('SUM(CASE WHEN tipe = "Lainnya" THEN 1 ELSE 0 END) as tipe_lainnya')
            )
            ->whereYear('created_at', '>=', date('Y') - 4)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('tahun', 'DESC')
            ->get();

        // ========== LAPORAN PER WILAYAH ==========
        
        $laporanPerWilayah = DataSurvei::select('wilayah', DB::raw('COUNT(*) as total'))
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })
            ->groupBy('wilayah')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get();

        // ========== LAPORAN AKTIVITAS ADMIN ==========
        
        $aktivitasAdmin = Admin::select(
                'admin.id',
                'admin.nama',
                'admin.email',
                DB::raw('COUNT(data_survei.id) as total_upload')
            )
            ->leftJoin('data_survei', 'admin.id', '=', 'data_survei.diunggah_oleh')
            ->groupBy('admin.id', 'admin.nama', 'admin.email')
            ->orderBy('total_upload', 'DESC')
            ->get();

        // ========== LAPORAN SURVEI TERBARU ==========
        
        $surveiTerbaru = DataSurvei::with(['pengunggah', 'lokasi'])
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();

        // ========== STATISTIK BULANAN (12 Bulan Terakhir) ==========
        
        $statistikBulanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subMonths($i);
            $jumlah = DataSurvei::whereYear('created_at', $tanggal->year)
                ->whereMonth('created_at', $tanggal->month)
                ->count();
            
            $statistikBulanan[] = [
                'bulan' => $tanggal->format('M Y'),
                'jumlah' => $jumlah
            ];
        }

        // ========== SURVEI DENGAN MARKER vs TANPA MARKER ==========
        
        $totalSurvei = DataSurvei::count(); // Digunakan untuk perhitungan persentase
        $surveiDenganMarker = DataSurvei::has('lokasi')->count();
        $surveiTanpaMarker = DataSurvei::doesntHave('lokasi')->count();

        // ========== DATA UNTUK FILTER ==========
        
        $tahunTersedia = DataSurvei::select(DB::raw('YEAR(created_at) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'DESC')
            ->pluck('tahun');
            
        $tipeSurvei = ['2D', '3D', 'HR', 'Lainnya'];

        return view('admin.laporan.index', compact(
            'laporanPerTipe',
            'laporanPerTahun',
            'laporanPerWilayah',
            'aktivitasAdmin',
            'surveiTerbaru',
            'statistikBulanan',
            'totalSurvei', // Untuk perhitungan persentase marker
            'surveiDenganMarker',
            'surveiTanpaMarker',
            'tahunTersedia',
            'tipeSurvei',
            'tahun',
            'bulan',
            'tipe'
        ));
    }

    /**
     * Export laporan (untuk pengembangan selanjutnya)
     */
    public function export(Request $request)
    {
        // Placeholder untuk fitur export PDF/Excel
        return response()->json([
            'message' => 'Fitur export sedang dalam pengembangan',
            'status' => 'coming_soon'
        ]);
    }
}