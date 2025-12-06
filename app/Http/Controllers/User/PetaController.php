<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\LokasiMarker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PetaController extends Controller
{
    /**
     * Menampilkan halaman Peta Interaktif (User) dengan data real-time.
     */
    public function index()
    {
        // FIXED: Gunakan eager loading dengan properly defined relationships
        // Ambil data marker dengan relasi survei melalui defined Eloquent relationships

        $markers = LokasiMarker::with('survei')
            ->has('survei') // Pastikan hanya yang ada relasi survei
            ->get()
            ->map(function ($marker) {
                // Manual mapping data yang diperlukan untuk view
                return [
                    'id' => $marker->id,
                    'id_data_survei' => $marker->id_data_survei,
                    'pusat_lintang' => (float) $marker->pusat_lintang,
                    'pusat_bujur' => $marker->pusat_bujur,
                    'nama_lokasi' => $marker->nama_lokasi,
                    'keterangan' => $marker->keterangan,
                    // Data dari relasi survei
                    'judul' => $marker->survei->judul ?? 'N/A',
                    'tahun' => $marker->survei->tahun ?? 'N/A',
                    'tipe' => $marker->survei->tipe ?? 'N/A',
                    'wilayah' => $marker->survei->wilayah ?? 'N/A',
                    'deskripsi' => $marker->survei->deskripsi ?? 'N/A',
                    'gambar_pratinjau' => $marker->survei->gambar_pratinjau ?? null,
                ];
            });

        // Debug: Log data yang akan dikirim ke view
        Log::info('Markers data for view:', $markers->toArray());

        // Tambah counter untuk statistik (FIXED: menggunakan data dari mapped collection)
        $stats = [
            'total_marker' => $markers->count(),
            'total_survei' => $markers->pluck('id_data_survei')->unique()->count(),
            'tahun_terbaru' => $markers->max('tahun'),
            'tipe_data' => [
                '2D' => $markers->where('tipe', '2D')->count(),
                '3D' => $markers->where('tipe', '3D')->count(),
                'HR' => $markers->where('tipe', 'HR')->count()
            ]
        ];

        // Debug: Log stats
        Log::info('Stats data for view:', $stats);

        return view('User.peta.index', compact('markers', 'stats'));
    }
}
