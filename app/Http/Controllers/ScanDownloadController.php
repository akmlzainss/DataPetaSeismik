<?php

namespace App\Http\Controllers;

use App\Models\DataSurvei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ScanDownloadController extends Controller
{
    /**
     * Download file scan asli (protected - hanya untuk pegawai internal)
     */
    public function download(Request $request, $id)
    {
        // WAJIB: Cek apakah user adalah pegawai internal yang sudah login
        if (!Auth::guard('pegawai')->check()) {
            abort(403, 'Akses ditolak. Hanya pegawai internal ESDM yang dapat mengunduh file scan asli.');
        }

        $pegawai = Auth::guard('pegawai')->user();

        // WAJIB: Cek apakah email sudah diverifikasi atau sudah di-approve
        if (!$pegawai->canLogin()) {
            abort(403, 'Akun Anda belum diverifikasi. Silakan verifikasi email atau hubungi administrator.');
        }

        // Cari data survei
        $dataSurvei = DataSurvei::findOrFail($id);

        // Cek apakah file scan asli ada
        if (!$dataSurvei->file_scan_asli) {
            abort(404, 'File scan asli tidak tersedia untuk data survei ini.');
        }

        // Cek apakah file exists di storage
        if (!Storage::disk('public')->exists($dataSurvei->file_scan_asli)) {
            abort(404, 'File scan asli tidak ditemukan di server.');
        }

        // Log download activity untuk audit
        Log::info('File scan asli downloaded', [
            'pegawai_id' => $pegawai->id,
            'pegawai_email' => $pegawai->email,
            'pegawai_nama' => $pegawai->nama,
            'data_survei_id' => $dataSurvei->id,
            'judul_survei' => $dataSurvei->judul,
            'file_path' => $dataSurvei->file_scan_asli,
            'file_size' => $dataSurvei->ukuran_file_asli,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'downloaded_at' => now(),
        ]);

        // Get file path
        $filePath = Storage::disk('public')->path($dataSurvei->file_scan_asli);
        
        // Generate filename yang user-friendly
        $downloadName = sprintf(
            'BBSPGL_%s_%s_%s.%s',
            str_replace(' ', '_', $dataSurvei->judul),
            $dataSurvei->wilayah,
            $dataSurvei->tahun,
            $dataSurvei->format_file_asli
        );

        // Return file untuk download
        return response()->download($filePath, $downloadName, [
            'Content-Type' => Storage::disk('public')->mimeType($dataSurvei->file_scan_asli),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
