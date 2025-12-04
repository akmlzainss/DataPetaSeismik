<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Http\Requests\DataSurveiRequest;
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
            $data['deskripsi'] = $this->sanitizeHtml($data['deskripsi']);
        }

        // Handle upload gambar pratinjau (original)
        if ($request->hasFile('gambar_pratinjau')) {
            $file = $request->file('gambar_pratinjau');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            // Simpan gambar_pratinjau ke folder 'gambar_pratinjau' di disk public
            $path = $file->storeAs('gambar_pratinjau', $fileName, 'public');
            $data['gambar_pratinjau'] = $path;
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
        // Pastikan relasi pengunggah dan lokasi dimuat
        $dataSurvei->load('pengunggah', 'lokasi');
        $safeDeskripsi = $this->sanitizeHtml($dataSurvei->deskripsi);
        return view('admin.data_survei.show', compact('dataSurvei', 'safeDeskripsi'));
    }

    /**
     * Mengembalikan detail data survei tertentu dalam format JSON.
     * Digunakan oleh fitur geocoding di halaman Lokasi Marker.
     */
    public function showJson(DataSurvei $dataSurvei)
    {
        return response()->json($dataSurvei);
    }

    /**
     * Menampilkan form untuk mengedit data survei tertentu.
     */
    public function edit(DataSurvei $dataSurvei)
    {
        return view('admin.data_survei.edit', compact('dataSurvei'));
    }

    /**
     * Memperbarui data survei di database.
     */
    public function update(DataSurveiRequest $request, DataSurvei $dataSurvei)
    {
        $data = $request->validated();

        if (isset($data['deskripsi'])) {
            $data['deskripsi'] = $this->sanitizeHtml($data['deskripsi']);
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

        $dataSurvei->update($data);

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
        // Hapus semua versi gambar saat delete
        foreach ([$dataSurvei->gambar_pratinjau, $dataSurvei->gambar_thumbnail, $dataSurvei->gambar_medium] as $file) {
            if ($file && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }

        $dataSurvei->delete();

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil dihapus!');
    }

    private function sanitizeHtml(?string $html): ?string
    {
        if ($html === null) return null;
        $allowed = '<p><br><strong><b><em><i><u><span><ol><ul><li><blockquote><h1><h2><h3><h4><h5><h6><a><table><thead><tbody><tr><td><th><colgroup><col><img>';
        $clean = strip_tags($html, $allowed);
        $clean = preg_replace('/on\\w+\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $clean);
        $clean = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*(\1)/i', 'href="#"', $clean);
        $clean = preg_replace('/<a\s+([^>]*?)target=("|\')_blank(\2)([^>]*?)>/i', '<a $1 target="_blank" rel="noopener noreferrer" $4>', $clean);
        $clean = preg_replace_callback('/style\s*=\s*("([^"]*)"|\'([^\']*)\')/i', function ($m) {
            $style = $m[2] ?? $m[3] ?? '';
            $allowedProps = ['color', 'background-color', 'text-align'];
            $rules = array_filter(array_map('trim', explode(';', $style)));
            $kept = [];
            foreach ($rules as $r) {
                [$prop, $val] = array_map('trim', explode(':', $r, 2));
                if (in_array(strtolower($prop), $allowedProps) && $val !== '') {
                    $kept[] = $prop . ':' . $val;
                }
            }
            return count($kept) ? 'style="' . implode(';', $kept) . '"' : '';
        }, $clean);
        $clean = preg_replace_callback('/<img\s+[^>]*>/i', function ($m) {
            $tag = $m[0];
            if (preg_match('/src\s*=\s*("([^"]*)"|\'([^\']*)\')/i', $tag, $sm)) {
                $src = $sm[2] ?? $sm[3] ?? '';
                $ok = preg_match('/^(https?:\/\/|\/)/i', $src) || preg_match('/^data:image\/(png|jpeg|jpg|gif);/i', $src);
                if (!$ok) return '';
            }
            $tag = preg_replace('/on\\w+\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $tag);
            $tag = preg_replace_callback('/style\s*=\s*("([^"]*)"|\'([^\']*)\')/i', function ($m2) {
                $style = $m2[2] ?? $m2[3] ?? '';
                $allowedProps = ['width', 'height'];
                $rules = array_filter(array_map('trim', explode(';', $style)));
                $kept = [];
                foreach ($rules as $r) {
                    [$prop, $val] = array_map('trim', explode(':', $r, 2));
                    if (in_array(strtolower($prop), $allowedProps) && $val !== '') {
                        $kept[] = $prop . ':' . $val;
                    }
                }
                return count($kept) ? 'style="' . implode(';', $kept) . '"' : '';
            }, $tag);
            return $tag;
        }, $clean);
        return $clean;
    }
}
