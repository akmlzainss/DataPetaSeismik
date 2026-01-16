<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
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
        $exportRange = $request->input('export_range');

        // Fix: Jika user memilih filter manual (Tahun/Bulan), abaikan export_range
        // Ini mengatasi masalah di mana filter bulan tidak berfungsi jika export_range tertinggal di URL
        if ($request->has('bulan') || $request->has('tahun')) {
            $exportRange = null;
        }

        // ========== LAPORAN PER TIPE SURVEI ==========

        $laporanPerTipe = DataSurvei::select('tipe', DB::raw('COUNT(*) as total'))
            ->when($exportRange, function ($query) use ($exportRange) {
                $dateRange = $this->getDateRangeFromString($exportRange);
                if ($dateRange) {
                    return $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                }
                return $query;
            })
            ->when(!$exportRange && $tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when(!$exportRange && $bulan, function ($query) use ($bulan) {
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
            ->when($exportRange, function ($query) use ($exportRange) {
                $dateRange = $this->getDateRangeFromString($exportRange);
                if ($dateRange) {
                    return $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                }
                return $query;
            })
            ->when(!$exportRange && $tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when(!$exportRange && $bulan, function ($query) use ($bulan) {
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

        // ========== LAPORAN SURVEI TERBARU (DENGAN PAGINATION) ==========

        $surveiTerbaru = DataSurvei::with(['pengunggah', 'lokasi'])
            ->when($exportRange, function ($query) use ($exportRange) {
                $dateRange = $this->getDateRangeFromString($exportRange);
                if ($dateRange) {
                    return $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                }
                return $query;
            })
            ->when(!$exportRange && $tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when(!$exportRange && $bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(8, ['*'], 'survei_page');

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

        // ========== SURVEI DENGAN GRID vs TANPA GRID (STATISTIK KESELURUHAN) ==========
        // Statistik grid dihitung dari SELURUH data (tanpa filter tahun)
        // karena ini adalah ringkasan kelengkapan data secara keseluruhan

        $totalSurvei = DataSurvei::count();
        $surveiDenganGrid = DataSurvei::has('gridKotak')->count();
        $surveiTanpaGrid = DataSurvei::doesntHave('gridKotak')->count();

        // ========== DATA UNTUK FILTER ==========

        $tahunTersedia = DataSurvei::select(DB::raw('YEAR(created_at) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'DESC')
            ->pluck('tahun');

        $tipeSurvei = ['2D', '3D', 'HR', 'Lainnya'];

        // Jika request AJAX untuk pagination survei terbaru
        if ($request->ajax() && $request->has('survei_page')) {
            return response()->json([
                'html' => view('admin.partials.survei-terbaru-table', compact('surveiTerbaru'))->render(),
                'pagination' => view('admin.partials.pagination-controls', ['paginator' => $surveiTerbaru, 'pageParam' => 'survei_page'])->render()
            ]);
        }

        return view('admin.laporan.index', compact(
            'laporanPerTipe',
            'laporanPerTahun',
            'laporanPerWilayah',
            'aktivitasAdmin',
            'surveiTerbaru',
            'statistikBulanan',
            'totalSurvei',
            'surveiDenganGrid',
            'surveiTanpaGrid',
            'tahunTersedia',
            'tipeSurvei',
            'tahun',
            'bulan',
            'tipe',
            'exportRange'
        ));
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.export-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        
        $filename = 'laporan-survei-' . date('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
 * Export laporan ke Excel (CSV format)
 */
public function exportExcel(Request $request)
{
    $data = $this->getReportData($request);
    
    $filename = 'laporan-survei-' . date('Y-m-d-His') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];
    
    $callback = function() use ($data) {
        $file = fopen('php://output', 'w');
        
        // Header row - dengan kolom Status Grid
        fputcsv($file, ['No', 'Judul', 'Tahun', 'Tipe', 'Wilayah', 'Ketua Tim', 'Tanggal Upload', 'Status Grid']);
        
        // Data rows
        $no = 1;
        foreach ($data['surveiTerbaru'] as $survei) {
            // Cek apakah survei memiliki grid
            $statusGrid = $survei->gridKotak->count() > 0 
                ? 'Grid ' . $survei->gridKotak->first()->nomor_kotak 
                : 'Belum di-assign';
            
            fputcsv($file, [
                $no++,
                $survei->judul,
                $survei->tahun,
                $survei->tipe,
                $survei->wilayah,
                $survei->ketua_tim ?? '-',
                $survei->created_at->format('d/m/Y H:i'),
                $statusGrid,
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

    /**
     * Get report data for export
     */
    private function getReportData(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tipe = $request->input('tipe');
        $exportRange = $request->input('export_range');

        $query = DataSurvei::with(['pengunggah', 'gridKotak'])
            ->when($exportRange, function ($query) use ($exportRange) {
                $dateRange = $this->getDateRangeFromString($exportRange);
                if ($dateRange) {
                    return $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                }
                return $query;
            })
            ->when(!$exportRange && $tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when(!$exportRange && $bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        return [
            'surveiTerbaru' => $query,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tipe' => $tipe,
            'tanggalExport' => Carbon::now()->format('d F Y H:i'),
        ];
    }

    /**
     * Convert export range string to date range
     */
    private function getDateRangeFromString($rangeString)
    {
        $now = Carbon::now();
        $start = null;
        $end = $now->copy();

        switch ($rangeString) {
            // Weeks
            case '1_week':
                $start = $now->copy()->subWeek();
                break;
            case '2_weeks':
                $start = $now->copy()->subWeeks(2);
                break;
            case '3_weeks':
                $start = $now->copy()->subWeeks(3);
                break;

            // Months
            case '1_month':
                $start = $now->copy()->subMonth();
                break;
            case '2_months':
                $start = $now->copy()->subMonths(2);
                break;
            case '3_months':
                $start = $now->copy()->subMonths(3);
                break;
            case '4_months':
                $start = $now->copy()->subMonths(4);
                break;
            case '5_months':
                $start = $now->copy()->subMonths(5);
                break;
            case '6_months':
                $start = $now->copy()->subMonths(6);
                break;
            case '7_months':
                $start = $now->copy()->subMonths(7);
                break;
            case '8_months':
                $start = $now->copy()->subMonths(8);
                break;
            case '9_months':
                $start = $now->copy()->subMonths(9);
                break;
            case '10_months':
                $start = $now->copy()->subMonths(10);
                break;
            case '11_months':
                $start = $now->copy()->subMonths(11);
                break;

            // Years
            case '1_year':
                $start = $now->copy()->subYear();
                break;
            case '2_years':
                $start = $now->copy()->subYears(2);
                break;
            case '3_years':
                $start = $now->copy()->subYears(3);
                break;
            case '4_years':
                $start = $now->copy()->subYears(4);
                break;
            case '5_years':
                $start = $now->copy()->subYears(5);
                break;

            default:
                return null;
        }

        return [
            'start' => $start,
            'end' => $end
        ];
    }
}
