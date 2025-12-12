<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Services\HtmlSanitizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Export laporan ke Excel
     */
    public function exportExcel(Request $request)
    {
        // Handle new export range parameters
        $exportRange = $request->input('export_range');
        $exportTipe = $request->input('export_tipe');

        // Legacy parameters for backward compatibility
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tipe = $request->input('tipe') ?: $exportTipe;

        // Build query with time range filter
        $query = DataSurvei::with(['pengunggah', 'lokasi']);

        // Apply time range filter
        if ($exportRange) {
            $dateRange = $this->getDateRangeFromString($exportRange);
            if ($dateRange) {
                $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            }
        } else {
            // Legacy filters
            $query->when($tahun, function ($q) use ($tahun) {
                return $q->whereYear('created_at', $tahun);
            })
                ->when($bulan, function ($q) use ($bulan) {
                    return $q->whereMonth('created_at', $bulan);
                });
        }

        // Apply type filter
        $query->when($tipe, function ($q) use ($tipe) {
            return $q->where('tipe', $tipe);
        });

        $surveiData = $query->orderBy('created_at', 'desc')->get();

        // Statistik
        $totalSurvei = $surveiData->count();
        $surveiPerTipe = $surveiData->groupBy('tipe')->map->count();
        $surveiDenganMarker = $surveiData->filter(function ($item) {
            return $item->lokasi !== null;
        })->count();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header perusahaan
        $this->addCompanyHeader($sheet);

        // Generate filter text
        if ($exportRange) {
            $filterText = $this->getExportRangeDescription($exportRange, $tipe);
        } else {
            $filterText = $this->getFilterText($tahun, $bulan, $tipe);
        }
        $sheet->setCellValue('A8', 'LAPORAN DATA SURVEI SEISMIK');
        $sheet->setCellValue('A9', $filterText);
        $sheet->mergeCells('A8:H8');
        $sheet->mergeCells('A9:H9');

        // Style judul
        $sheet->getStyle('A8:A9')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Ringkasan statistik
        $row = 11;
        $sheet->setCellValue('A' . $row, 'RINGKASAN STATISTIK');
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']]
        ]);
        $sheet->mergeCells('A' . $row . ':H' . $row);

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Survei:');
        $sheet->setCellValue('B' . $row, $totalSurvei);
        $sheet->setCellValue('D' . $row, 'Survei dengan Marker:');
        $sheet->setCellValue('E' . $row, $surveiDenganMarker);
        $sheet->setCellValue('G' . $row, 'Persentase Marker:');
        $sheet->setCellValue('H' . $row, $totalSurvei > 0 ? round(($surveiDenganMarker / $totalSurvei) * 100, 1) . '%' : '0%');

        // Distribusi per tipe
        $row += 2;
        $sheet->setCellValue('A' . $row, 'DISTRIBUSI PER TIPE SURVEI');
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E9']]
        ]);
        $sheet->mergeCells('A' . $row . ':H' . $row);

        $row++;
        $col = 'A';
        foreach ($surveiPerTipe as $tipe => $jumlah) {
            $sheet->setCellValue($col . $row, $tipe . ':');
            $sheet->setCellValue(chr(ord($col) + 1) . $row, $jumlah);
            $col = chr(ord($col) + 2);
        }

        // Header tabel data
        $row += 3;
        $headers = ['No', 'Tanggal Upload', 'Judul Survei', 'Tipe', 'Tahun', 'Wilayah', 'Ketua Tim', 'Status Marker'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '003366']],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            $col++;
        }

        // Data survei
        $row++;
        $no = 1;
        foreach ($surveiData as $survei) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $survei->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('C' . $row, $survei->judul);
            $sheet->setCellValue('D' . $row, $survei->tipe);
            $sheet->setCellValue('E' . $row, $survei->tahun);
            $sheet->setCellValue('F' . $row, $survei->wilayah);
            $sheet->setCellValue('G' . $row, $survei->ketua_tim);
            $sheet->setCellValue('H' . $row, $survei->lokasi ? 'Ada Marker' : 'Belum Ada');

            // Style data rows
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true]
            ]);

            $row++;
        }

        // Auto width columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Footer
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Dicetak pada: ' . Carbon::now()->format('d F Y H:i:s'));
        $sheet->setCellValue('F' . $row, 'Balai Besar Survei dan Pemetaan Geologi');
        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
            'font' => ['italic' => true, 'size' => 10]
        ]);

        // Set properties
        $spreadsheet->getProperties()
            ->setCreator('BBSPGL Admin')
            ->setTitle('Laporan Data Survei Seismik')
            ->setSubject('Laporan Survei')
            ->setDescription('Laporan data survei seismik BBSPGL');

        $filename = 'Laporan_Survei_' . ($tahun ?? 'Semua') . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf(Request $request)
    {
        // Handle new export range parameters
        $exportRange = $request->input('export_range');
        $exportTipe = $request->input('export_tipe');

        // Legacy parameters for backward compatibility
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tipe = $request->input('tipe') ?: $exportTipe;

        // Build query with time range filter
        $query = DataSurvei::with(['pengunggah', 'lokasi']);

        // Apply time range filter
        if ($exportRange) {
            $dateRange = $this->getDateRangeFromString($exportRange);
            if ($dateRange) {
                $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            }
        } else {
            // Legacy filters
            $query->when($tahun, function ($q) use ($tahun) {
                return $q->whereYear('created_at', $tahun);
            })
                ->when($bulan, function ($q) use ($bulan) {
                    return $q->whereMonth('created_at', $bulan);
                });
        }

        // Apply type filter
        $query->when($tipe, function ($q) use ($tipe) {
            return $q->where('tipe', $tipe);
        });

        $surveiData = $query->orderBy('created_at', 'desc')
            ->limit(100) // Batasi untuk PDF
            ->get();

        // Statistik
        $totalSurvei = DataSurvei::when($tahun, function ($query) use ($tahun) {
            return $query->whereYear('created_at', $tahun);
        })
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })->count();

        $surveiPerTipe = DataSurvei::select('tipe', DB::raw('COUNT(*) as total'))
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })
            ->groupBy('tipe')
            ->get();

        $surveiDenganMarker = DataSurvei::has('lokasi')
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })->count();

        // Generate filter text
        if ($exportRange) {
            $filterText = $this->getExportRangeDescription($exportRange, $tipe);
        } else {
            $filterText = $this->getFilterText($tahun, $bulan, $tipe);
        }

        $data = [
            'surveiData' => $surveiData,
            'totalSurvei' => $totalSurvei,
            'surveiPerTipe' => $surveiPerTipe,
            'surveiDenganMarker' => $surveiDenganMarker,
            'filterText' => $filterText,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tipe' => $tipe,
            'tanggalCetak' => Carbon::now()->format('d F Y H:i:s')
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf-template', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Laporan_Survei_' . ($tahun ?? 'Semua') . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Tambahkan header perusahaan ke Excel
     */
    private function addCompanyHeader($sheet)
    {
        // Logo (jika ada)
        $logoPath = public_path('storage/logo-esdm2.png');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo ESDM');
            $drawing->setDescription('Logo ESDM');
            $drawing->setPath($logoPath);
            $drawing->setHeight(60);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }

        // Header text
        $sheet->setCellValue('B1', 'KEMENTERIAN ENERGI DAN SUMBER DAYA MINERAL');
        $sheet->setCellValue('B2', 'BADAN GEOLOGI');
        $sheet->setCellValue('B3', 'BALAI BESAR SURVEI DAN PEMETAAN GEOLOGI');
        $sheet->setCellValue('B4', 'Jl. Diponegoro No. 57 Bandung 40122');
        $sheet->setCellValue('B5', 'Telp. (022) 7272606, Fax. (022) 7206965');

        // Style header
        $sheet->getStyle('B1:B5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
        ]);

        $sheet->getStyle('B1')->applyFromArray(['font' => ['size' => 12, 'bold' => true]]);
    }

    /**
     * Generate filter text
     */
    private function getFilterText($tahun, $bulan, $tipe)
    {
        $filters = [];

        if ($tahun) {
            $filters[] = "Tahun: $tahun";
        }

        if ($bulan) {
            $bulanNama = Carbon::createFromFormat('m', $bulan)->format('F');
            $filters[] = "Bulan: $bulanNama";
        }

        if ($tipe) {
            $filters[] = "Tipe: $tipe";
        }

        return empty($filters) ? 'Semua Data' : implode(' | ', $filters);
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

    /**
     * Get human readable description for export range
     */
    private function getExportRangeDescription($rangeString, $tipe = null)
    {
        $descriptions = [
            '1_week' => '1 Minggu Terakhir',
            '2_weeks' => '2 Minggu Terakhir',
            '3_weeks' => '3 Minggu Terakhir',
            '1_month' => '1 Bulan Terakhir',
            '2_months' => '2 Bulan Terakhir',
            '3_months' => '3 Bulan Terakhir',
            '4_months' => '4 Bulan Terakhir',
            '5_months' => '5 Bulan Terakhir',
            '6_months' => '6 Bulan Terakhir',
            '7_months' => '7 Bulan Terakhir',
            '8_months' => '8 Bulan Terakhir',
            '9_months' => '9 Bulan Terakhir',
            '10_months' => '10 Bulan Terakhir',
            '11_months' => '11 Bulan Terakhir',
            '1_year' => '1 Tahun Terakhir',
            '2_years' => '2 Tahun Terakhir',
            '3_years' => '3 Tahun Terakhir',
            '4_years' => '4 Tahun Terakhir',
            '5_years' => '5 Tahun Terakhir'
        ];

        $description = $descriptions[$rangeString] ?? 'Semua Data';

        if ($tipe) {
            $description .= " - Tipe: $tipe";
        }

        return $description;
    }
}
