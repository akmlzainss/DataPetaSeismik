<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\Admin;
use App\Models\GridKotak;
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

        // ========== DISTRIBUSI GRID (dengan pagination) ==========

        $distribusiGrid = GridKotak::withCount('dataSurvei')
            ->orderBy('data_survei_count', 'DESC')
            ->orderBy('nomor_kotak', 'ASC')
            ->paginate(10, ['*'], 'grid_page');

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

        $surveiTerbaru = DataSurvei::with(['pengunggah', 'gridKotak'])
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

        // Jika request AJAX untuk pagination distribusi grid
        if ($request->ajax() && $request->has('grid_page')) {
            return response()->json([
                'html' => view('admin.partials.distribusi-grid-table', compact('distribusiGrid'))->render(),
                'pagination' => view('admin.partials.pagination-controls', ['paginator' => $distribusiGrid, 'pageParam' => 'grid_page'])->render()
            ]);
        }

        return view('admin.laporan.index', compact(
            'laporanPerTipe',
            'laporanPerTahun',
            'distribusiGrid',
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
     * Export laporan ke Excel (XLSX format dengan styling)
     */
    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Survei');
        
        // ========== HEADER SECTION ==========
        // Title
        $sheet->setCellValue('A1', 'LAPORAN DATA SURVEI SEISMIK');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '003366']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Subtitle
        $sheet->setCellValue('A2', 'Balai Besar Survei dan Pemetaan Geologi Kelautan');
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 12, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Export info
        $filterInfo = 'Tanggal Unduh: ' . $data['tanggalExport'];
        if ($data['tahun']) $filterInfo .= ' | Tahun: ' . $data['tahun'];
        if ($data['bulan']) $filterInfo .= ' | Bulan: ' . $data['bulan'];
        if ($data['tipe']) $filterInfo .= ' | Tipe: ' . $data['tipe'];
        $filterInfo .= ' | Total Data: ' . count($data['surveiTerbaru']) . ' survei';
        
        $sheet->setCellValue('A3', $filterInfo);
        $sheet->mergeCells('A3:H3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['size' => 10, 'italic' => true, 'color' => ['rgb' => '888888']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        
        // ========== TABLE HEADER ==========
        $headerRow = 5;
        $headers = ['No', 'Judul Survei', 'Tahun', 'Tipe', 'Wilayah', 'Ketua Tim', 'Tanggal Upload', 'Status Grid'];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $col++;
        }
        
        // Header styling
        $sheet->getStyle('A' . $headerRow . ':H' . $headerRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '003366'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);
        
        // ========== DATA ROWS ==========
        $row = $headerRow + 1;
        $no = 1;
        
        foreach ($data['surveiTerbaru'] as $survei) {
            $statusGrid = $survei->gridKotak->count() > 0 
                ? '✓ Grid ' . $survei->gridKotak->first()->nomor_kotak 
                : '— Belum di-assign';
            
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $survei->judul);
            $sheet->setCellValue('C' . $row, $survei->tahun);
            $sheet->setCellValue('D' . $row, $survei->tipe);
            $sheet->setCellValue('E' . $row, $survei->wilayah);
            $sheet->setCellValue('F' . $row, $survei->ketua_tim ?? '-');
            $sheet->setCellValue('G' . $row, $survei->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('H' . $row, $statusGrid);
            
            // Alternate row colors
            if ($no % 2 == 0) {
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA'],
                    ],
                ]);
            }
            
            // Status Grid coloring
            if ($survei->gridKotak->count() > 0) {
                $sheet->getStyle('H' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '28A745'], 'bold' => true],
                ]);
            } else {
                $sheet->getStyle('H' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '999999']],
                ]);
            }
            
            $row++;
        }
        
        // Data borders
        $lastRow = $row - 1;
        if ($lastRow >= $headerRow + 1) {
            $sheet->getStyle('A' . ($headerRow + 1) . ':H' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']],
                ],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]);
        }
        
        // ========== COLUMN WIDTHS ==========
        $sheet->getColumnDimension('A')->setWidth(6);   // No
        $sheet->getColumnDimension('B')->setWidth(40);  // Judul
        $sheet->getColumnDimension('C')->setWidth(10);  // Tahun
        $sheet->getColumnDimension('D')->setWidth(12);  // Tipe
        $sheet->getColumnDimension('E')->setWidth(35);  // Wilayah
        $sheet->getColumnDimension('F')->setWidth(25);  // Ketua Tim
        $sheet->getColumnDimension('G')->setWidth(18);  // Tanggal Upload
        $sheet->getColumnDimension('H')->setWidth(20);  // Status Grid
        
        // Center align specific columns
        $sheet->getStyle('A' . ($headerRow + 1) . ':A' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C' . ($headerRow + 1) . ':D' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G' . ($headerRow + 1) . ':H' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // ========== FOOTER ==========
        $footerRow = $lastRow + 2;
        $sheet->setCellValue('A' . $footerRow, 'Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi Data Peta Seismik BBSPGL');
        $sheet->mergeCells('A' . $footerRow . ':H' . $footerRow);
        $sheet->getStyle('A' . $footerRow)->applyFromArray([
            'font' => ['size' => 9, 'italic' => true, 'color' => ['rgb' => '888888']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        
        // ========== OUTPUT ==========
        $filename = 'laporan-survei-' . date('Y-m-d-His') . '.xlsx';
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
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
