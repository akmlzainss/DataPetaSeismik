<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\GridKotak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GridKotakController extends Controller
{
    /**
     * Display grid map dengan semua kotak dan data survei yang belum di-assign
     */
    public function index()
    {
        // Ambil semua grid kotak dengan data survei yang sudah ter-assign
        $grids = GridKotak::with(['dataSurvei' => function($query) {
            $query->with('pengunggah');
        }])->get();

        // Ambil data survei yang belum di-assign ke grid manapun
        $surveisBelumAssign = DataSurvei::whereDoesntHave('gridKotak')
            ->orderByDesc('created_at')
            ->get();

        // Statistics
        $stats = [
            'total_grid' => GridKotak::count(),
            'grid_terisi' => GridKotak::filled()->count(),
            'grid_kosong' => GridKotak::empty()->count(),
            'total_data_survei' => DataSurvei::count(),
            'data_belum_assign' => $surveisBelumAssign->count(),
            'persentase_terisi' => GridKotak::count() > 0 
                ? round((GridKotak::filled()->count() / GridKotak::count()) * 100, 1) 
                : 0,
        ];

        return view('admin.grid_kotak.index', compact('grids', 'surveisBelumAssign', 'stats'));
    }

    /**
     * Get grid data sebagai JSON untuk AJAX request
     */
    public function getGridData()
    {
        try {
            $grids = GridKotak::with(['dataSurvei'])->get();

            return response()->json([
                'success' => true,
                'grids' => $grids->map(function($grid) {
                    return [
                        'id' => $grid->id,
                        'nomor_kotak' => $grid->nomor_kotak,
                        'bounds' => $grid->bounds_array,
                        'center' => $grid->center_array,
                        'status' => $grid->status,
                        'total_data' => $grid->total_data,
                        'data_survei' => $grid->dataSurvei->map(function($survei) {
                            return [
                                'id' => $survei->id,
                                'judul' => $survei->judul,
                                'tahun' => $survei->tahun,
                                'tipe' => $survei->tipe,
                                'wilayah' => $survei->wilayah,
                            ];
                        }),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Assign data survei ke grid kotak
     */
    public function assign(Request $request)
    {
        $request->validate([
            'grid_kotak_id' => 'required|exists:grid_kotak,id',
            'data_survei_id' => 'required|exists:data_survei,id',
        ]);

        $gridKotak = GridKotak::findOrFail($request->grid_kotak_id);
        $dataSurvei = DataSurvei::findOrFail($request->data_survei_id);

        // Check if already assigned
        if ($gridKotak->dataSurvei()->where('data_survei_id', $dataSurvei->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Data survei sudah ter-assign ke kotak grid ini!',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Attach data survei ke grid kotak
            $gridKotak->dataSurvei()->attach($dataSurvei->id, [
                'assigned_by' => Auth::guard('admin')->id(),
                'assigned_at' => now(),
            ]);

            // Update counter dan status grid
            $gridKotak->increment('total_data');
            $gridKotak->update(['status' => 'filled']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data survei berhasil di-assign ke grid kotak ' . $gridKotak->nomor_kotak,
                'grid' => [
                    'id' => $gridKotak->id,
                    'nomor_kotak' => $gridKotak->nomor_kotak,
                    'total_data' => $gridKotak->total_data,
                    'status' => $gridKotak->status,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal assign data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Unassign (remove) data survei dari grid kotak
     */
    public function unassign($gridKotakId, $dataSurveiId)
    {
        $gridKotak = GridKotak::findOrFail($gridKotakId);
        $dataSurvei = DataSurvei::findOrFail($dataSurveiId);

        // Check if actually assigned
        if (!$gridKotak->dataSurvei()->where('data_survei_id', $dataSurvei->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Data survei tidak ter-assign ke kotak grid ini!',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Detach data survei dari grid kotak
            $gridKotak->dataSurvei()->detach($dataSurvei->id);

            // Update counter dan status grid
            $gridKotak->decrement('total_data');
            
            if ($gridKotak->total_data <= 0) {
                $gridKotak->update([
                    'status' => 'empty',
                    'total_data' => 0,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data survei berhasil di-remove dari grid kotak ' . $gridKotak->nomor_kotak,
                'grid' => [
                    'id' => $gridKotak->id,
                    'nomor_kotak' => $gridKotak->nomor_kotak,
                    'total_data' => $gridKotak->total_data,
                    'status' => $gridKotak->status,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal remove data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get detail grid kotak beserta semua data survei di dalamnya
     */
    public function show($id)
    {
        $gridKotak = GridKotak::with(['dataSurvei.pengunggah'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'grid' => [
                'id' => $gridKotak->id,
                'nomor_kotak' => $gridKotak->nomor_kotak,
                'bounds' => $gridKotak->bounds_array,
                'center' => $gridKotak->center_array,
                'status' => $gridKotak->status,
                'total_data' => $gridKotak->total_data,
            ],
            'data_survei' => $gridKotak->dataSurvei->map(function($survei) {
                return [
                    'id' => $survei->id,
                    'judul' => $survei->judul,
                    'tahun' => $survei->tahun,
                    'tipe' => $survei->tipe,
                    'wilayah' => $survei->wilayah,
                    'deskripsi' => $survei->deskripsi,
                    'pengunggah' => $survei->pengunggah ? $survei->pengunggah->name : 'N/A',
                    'assigned_at' => $survei->pivot->assigned_at,
                ];
            }),
        ]);
    }
}
