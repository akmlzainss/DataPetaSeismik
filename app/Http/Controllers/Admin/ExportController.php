<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\Admin;
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
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tipe = $request->input('tipe');

        // Ambil data survei dengan filter
        $surveiData = DataSurvei::with(['pengunggah', 'lokasi'])
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })
            ->orderBy('created_at', 'desc')
            ->get();

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

        // Judul laporan
        $filterText = $this->getFilterText($tahun, $bulan, $tipe);
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
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tipe = $request->input('tipe');

        // Ambil data survei dengan filter dan pagination untuk PDF
        $surveiData = DataSurvei::with(['pengunggah', 'lokasi'])
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('tipe', $tipe);
            })
            ->orderBy('created_at', 'desc')
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

        $filterText = $this->getFilterText($tahun, $bulan, $tipe);

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
}
