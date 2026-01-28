<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\GridKotak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PetaController extends Controller
{
    /**
     * Menampilkan halaman Peta Interaktif (User) dengan data real-time.
     * Menggunakan sistem Grid Kotak
     */
    public function index()
    {
        // Ambil semua grid kotak dengan relasi data survei
        $gridsData = GridKotak::with(['dataSurvei' => function ($query) {
            $query->with('pengunggah');
        }])->get();

        // Format data grid untuk peta
        $grids = $gridsData->map(function ($grid) {
            return [
                'id' => $grid->id,
                'nomor_kotak' => $grid->nomor_kotak,
                'bounds' => $grid->bounds_array,
                'center' => $grid->center_array,
                'status' => $grid->status,
                'total_data' => $grid->total_data,
                'is_filled' => $grid->total_data > 0,
                // Data survei dalam grid ini
                'survei_list' => $grid->dataSurvei->map(function ($survei) {
                    return [
                        'id' => $survei->id,
                        'judul' => $survei->judul,
                        'tahun' => $survei->tahun,
                        'tipe' => $survei->tipe,
                        'wilayah' => $survei->wilayah,
                        'deskripsi' => $survei->deskripsi,
                        'gambar_pratinjau' => $survei->gambar_pratinjau,
                    ];
                })->toArray(),
            ];
        });

        // Debug: Log data yang akan dikirim ke view
        Log::info('Grids data for view:', ['count' => $grids->count()]);

        // Statistik untuk tampilan
        $stats = [
            'total_grid' => $grids->count(),
            'grid_terisi' => $grids->where('is_filled', true)->count(),
            'grid_kosong' => $grids->where('is_filled', false)->count(),
            'total_survei' => DataSurvei::count(),
            'survei_terpetakan' => DataSurvei::has('gridKotak')->count(),
            'tipe_data' => [
                '2D' => DataSurvei::where('tipe', '2D')->count(),
                '3D' => DataSurvei::where('tipe', '3D')->count(),
                'HR' => DataSurvei::where('tipe', 'HR')->count()
            ]
        ];

        // Debug: Log stats
        Log::info('Stats data for view:', $stats);

        return view('User.peta.index', compact('grids', 'stats'));
    }
}
