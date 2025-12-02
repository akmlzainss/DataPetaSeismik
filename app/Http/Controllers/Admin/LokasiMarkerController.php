<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Models\LokasiMarker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class LokasiMarkerController extends Controller
{
    public function index()
    {
        // 1. Ambil SEMUA marker yang sudah ada untuk ditampilkan di peta
        $markers = LokasiMarker::with('survei')->get();
        
        // 2. Filter data survei: Hanya ambil yang BELUM punya lokasi marker
        // Menggunakan whereDoesntHave('lokasi')
        $surveis = DataSurvei::whereDoesntHave('lokasi')
            ->orderByDesc('created_at')
            ->get(); 
        
        // Catatan: Variabel $surveis sekarang hanya berisi data yang BELUM ditandai.
        // Option disabled di blade akan dihapus.

        return view('admin.lokasi_marker.index', compact('markers', 'surveis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_data_survei' => 'required|exists:data_survei,id',
            'pusat_lintang'  => 'required|numeric',
            'pusat_bujur'    => 'required|numeric',
        ]);

        // Simpan atau update marker berdasarkan id_data_survei
        LokasiMarker::updateOrCreate(
            ['id_data_survei' => $request->id_data_survei],
            [
                'pusat_lintang' => $request->pusat_lintang,
                'pusat_bujur'  => $request->pusat_bujur,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Marker berhasil disimpan!']);
    }

    public function destroy($id)
    {
        // Mencari marker berdasarkan id_data_survei karena marker terikat 1-1 dengan survei
        $marker = LokasiMarker::where('id_data_survei', $id)->firstOrFail();
        $marker->delete();

        return response()->json(['success' => true]);
    }


    // ============================================
    //         🔥 AUTO GEOCODING BARU 🔥
    // ============================================
    public function geocode(Request $request)
    {
        $lokasi = $request->lokasi;

        if (!$lokasi) {
            return response()->json(['error' => 'Lokasi tidak valid', 'message' => 'Lokasi tidak valid'], 400);
        }

        // ==============================
        // ✔ CACHE LOCAL (storage/app/geocache.json)
        // ==============================
        $cachePath = storage_path('app/geocache.json');
        $cache = [];

        if (File::exists($cachePath)) {
            $cache = json_decode(File::get($cachePath), true);
        }

        // Jika sudah ada di cache → langsung kembalikan
        if (isset($cache[$lokasi])) {
            return response()->json($cache[$lokasi]);
        }

        // ==============================
        // ✔ REQUEST GEOCODING (Nominatim)
        // ==============================
        $url = "https://nominatim.openstreetmap.org/search";

        $response = Http::withHeaders([
            // Tambahkan User-Agent, WAJIB untuk Nominatim Public
            'User-Agent' => 'BBSPGL-Seismic-Admin/1.0 (admin@bbspgl.esdm.go.id)'
        ])->get($url, [
            'q' => $lokasi . ', Indonesia',
            'format' => 'json',
            'limit' => 1,
        ]);

        $json = $response->json();

        // ==============================
        // ✔ FALLBACK jika lokasi tidak ditemukan atau response error
        // ==============================
        if (!$json || count($json) == 0 || $response->failed()) {
            // fallback Indonesia (di tengah)
            $fallback = [
                'lat' => -2.5489,
                'lon' => 118.0149,
                'fallback' => true,
                'message' => 'Geocoding gagal/lokasi tidak spesifik. Menggunakan koordinat default Indonesia.'
            ];

            $cache[$lokasi] = $fallback;
            File::put($cachePath, json_encode($cache, JSON_PRETTY_PRINT));

            return response()->json($fallback);
        }

        $lat = (float) $json[0]['lat'];
        $lon = (float) $json[0]['lon'];

        // Simpan ke cache
        $result = [
            'lat' => $lat,
            'lon' => $lon,
            'fallback' => false,
            'display_name' => $json[0]['display_name'] ?? $lokasi,
        ];

        $cache[$lokasi] = $result;

        File::put($cachePath, json_encode($cache, JSON_PRETTY_PRINT));

        return response()->json($result);
    }
}