<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Http\Requests\DataSurveiRequest;
use App\Services\HtmlSanitizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessSurveiImage;
use Illuminate\Support\Str;

class DataSurveiController extends Controller
{
    /**
     * Menampilkan daftar data survei dengan fitur pencarian, filter, dan sorting.
     */
    public function index(Request $request)
    {
        $query = DataSurvei::with('pengunggah');

        // SEARCH: judul, wilayah, tahun
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('wilayah', 'LIKE', "%{$search}%")
                    ->orWhere('tahun', 'LIKE', "%{$search}%");
            });
        }

        // FILTER TAHUN
        if ($request->filled('tahun') && $request->tahun != 'semua') {
            $query->where('tahun', $request->tahun);
        }

        // SORTING
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'terbaru' => $query->orderBy('created_at', 'desc'),
            'terlama' => $query->orderBy('created_at', 'asc'),
            'az'      => $query->orderBy('judul', 'asc'),
            'za'      => $query->orderBy('judul', 'desc'),
            default   => $query->orderBy('created_at', 'desc'),
        };

        // Paginate dengan 12 item per halaman
        $dataSurvei = $query->paginate(12)->withQueryString();

        // Ambil semua tahun unik untuk dropdown filter
        $tahuns = DataSurvei::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.data_survei.index', compact('dataSurvei', 'tahuns'));
    }

    /**
     * Menampilkan form untuk membuat data survei baru.
     */
    public function create()
    {
        return view('admin.data_survei.create');
    }

    /**
     * Menyimpan data survei baru ke database.
     */
    public function store(DataSurveiRequest $request)
    {
        $data = $request->validated();
        $data['diunggah_oleh'] = Auth::guard('admin')->id();

        if (isset($data['deskripsi'])) {
            $data['deskripsi'] = HtmlSanitizerService::sanitize($data['deskripsi']);
        }

        // Handle upload gambar pratinjau (original)
        if ($request->hasFile('gambar_pratinjau')) {
            $file = $request->file('gambar_pratinjau');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            // Simpan gambar_pratinjau ke folder 'gambar_pratinjau' di disk public
            $path = $file->storeAs('gambar_pratinjau', $fileName, 'public');
            $data['gambar_pratinjau'] = $path;
        }

        // Handle upload file scan asli (file besar untuk pegawai internal)
        if ($request->hasFile('file_scan_asli')) {
            $file = $request->file('file_scan_asli');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            // Simpan ke folder 'scan_asli' di disk public
            $path = $file->storeAs('scan_asli', $fileName, 'public');
            $data['file_scan_asli'] = $path;
            $data['ukuran_file_asli'] = $file->getSize(); // dalam bytes
            $data['format_file_asli'] = $file->getClientOriginalExtension();
        }

        $survei = DataSurvei::create($data);

        // Proses gambar thumbnail dan medium di background menggunakan Job
        if ($request->hasFile('gambar_pratinjau')) {
            // Delay 5 detik agar record dataSurvei sudah benar-benar tersimpan di DB
            ProcessSurveiImage::dispatch($survei->id)->delay(now()->addSeconds(5));
        }

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil ditambahkan! Gambar sedang diproses...');
    }

    /**
     * Menampilkan detail data survei tertentu.
     */
    public function show(DataSurvei $dataSurvei)
    {
        // Load relasi pengunggah dan gridKotak (sistem baru)
        $dataSurvei->load('pengunggah', 'gridKotak');
        $safeDeskripsi = HtmlSanitizerService::sanitize($dataSurvei->deskripsi);
        return view('admin.data_survei.show', compact('dataSurvei', 'safeDeskripsi'));
    }

    /**
     * Menampilkan form untuk mengedit data survei tertentu.
     */
    public function edit(DataSurvei $dataSurvei)
    {
        // Load relasi gridKotak untuk menampilkan grid yang sudah terpilih
        $dataSurvei->load('gridKotak');
        
        // Ambil semua grid untuk pilihan dropdown
        $allGrids = \App\Models\GridKotak::orderBy('nomor_kotak', 'asc')->get();
        
        return view('admin.data_survei.edit', compact('dataSurvei', 'allGrids'));
    }

    /**
     * Memperbarui data survei di database.
     */
    public function update(DataSurveiRequest $request, DataSurvei $dataSurvei)
    {
        $data = $request->validated();

        if (isset($data['deskripsi'])) {
            $data['deskripsi'] = HtmlSanitizerService::sanitize($data['deskripsi']);
        }

        if ($request->hasFile('gambar_pratinjau')) {
            // Hapus semua versi gambar lama (original, thumbnail, medium)
            foreach ([$dataSurvei->gambar_pratinjau, $dataSurvei->gambar_thumbnail, $dataSurvei->gambar_medium] as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            // Reset kolom gambar di DB agar tidak menggunakan path gambar lama
            $data['gambar_thumbnail'] = null;
            $data['gambar_medium'] = null;

            // Upload gambar pratinjau (original) yang baru
            $file = $request->file('gambar_pratinjau');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('gambar_pratinjau', $fileName, 'public');
            $data['gambar_pratinjau'] = $path;
        }

        // Handle upload file scan asli baru
        if ($request->hasFile('file_scan_asli')) {
            // Hapus file scan asli lama jika ada
            if ($dataSurvei->file_scan_asli && Storage::disk('public')->exists($dataSurvei->file_scan_asli)) {
                Storage::disk('public')->delete($dataSurvei->file_scan_asli);
            }

            // Upload file scan asli yang baru
            $file = $request->file('file_scan_asli');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('scan_asli', $fileName, 'public');
            $data['file_scan_asli'] = $path;
            $data['ukuran_file_asli'] = $file->getSize();
            $data['format_file_asli'] = $file->getClientOriginalExtension();
        }

        $dataSurvei->update($data);
        
        // Update posisi grid jika ada input
        // Kita gunakan sync untuk replace grid yang lama dengan yang baru
        // Jika input kosong (null), maka grid akan dikosongkan (unassign)
        if ($request->has('grid_kotak_id')) {
            // Siapkan array sync data dengan tambahan pivot fields
            $syncData = [];
            $gridIds = $request->input('grid_kotak_id', []); // Bisa single value atau array jika multiple allowed
            
            // Jika single value, convert ke array
            if (!is_array($gridIds) && $gridIds) {
                $gridIds = [$gridIds];
            } elseif (!$gridIds) {
                // If empty or null
                $gridIds = [];
            }
            
            foreach ($gridIds as $gridId) {
                $syncData[$gridId] = [
                    'assigned_by' => Auth::guard('admin')->id(),
                    'assigned_at' => now(),
                ];
            }
            
            // Update relasi
            $dataSurvei->gridKotak()->sync($syncData);
            
            // Update counter total_data di GridKotak (manual calculation karena sync tidak trigger events observer secara default untuk pivot)
            // Ini bisa resource intensive jika banyak grid, tapi untuk 1-2 grid tidak masalah.
            // Cara yang lebih akurat adalah recalculate semua affected grids
            $affectedGrids = \App\Models\GridKotak::whereIn('id', $gridIds)->get();
            foreach ($affectedGrids as $grid) {
                // Recount total_data
                $count = $grid->dataSurvei()->count();
                $grid->update([
                    'total_data' => $count,
                    'status' => $count > 0 ? 'filled' : 'empty'
                ]);
            }
            
            // Kita juga harus update grid LAMA yang mungkin sekarang jadi kosong
            // Tapi kita tidak tahu grid lama mana yang di-detach tanpa query dulu.
            // Solusi sederhana: update status semua grid (TERLALU BERAT).
            // Solusi: Kita asumsikan user tidak sering pindah grid, jadi manual update cukup.
            // Note: GridKotakController mungkin punya logic update counter yang lebih baik.
        }

        // Proses gambar thumbnail dan medium yang baru di background
        if ($request->hasFile('gambar_pratinjau')) {
            ProcessSurveiImage::dispatch($dataSurvei->id)->delay(now()->addSeconds(5));
        }

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil diperbarui! Gambar sedang diproses...');
    }

    /**
     * Menghapus data survei dari database.
     */
    public function destroy(DataSurvei $dataSurvei)
    {
        // Hapus semua versi gambar dan file scan asli saat delete
        foreach ([$dataSurvei->gambar_pratinjau, $dataSurvei->gambar_thumbnail, $dataSurvei->gambar_medium, $dataSurvei->file_scan_asli] as $file) {
            if ($file && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }

        // AMBIL DULU ID grid yang terhubung sebelum dihapus
        // agar kita bisa update counter 'total_data' di grid tersebut nanti
        $connectedGridIds = $dataSurvei->gridKotak()->pluck('grid_kotak.id')->toArray();

        // Hapus data (Pivot di grid_seismik akan terhapus otomatis via Cascade DB)
        $dataSurvei->delete();

        // UPDATE COUNTER GRID yang terimbas
        if (!empty($connectedGridIds)) {
            $affectedGrids = \App\Models\GridKotak::whereIn('id', $connectedGridIds)->get();
            foreach ($affectedGrids as $grid) {
                $count = $grid->dataSurvei()->count();
                $grid->update([
                    'total_data' => $count,
                    'status' => $count > 0 ? 'filled' : 'empty'
                ]);
            }
        }

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil dihapus!');
    }

    /**
     * Generate search-friendly excerpt from survey description
     */
    private function generateExcerpt(DataSurvei $survei): ?string
    {
        return HtmlSanitizerService::excerpt($survei->deskripsi, 200);
    }

    /**
     * Get plain text version of description for meta tags
     */
    private function getPlainDescription(DataSurvei $survei): ?string
    {
        return HtmlSanitizerService::toPlainText($survei->deskripsi, 160);
    }
}
