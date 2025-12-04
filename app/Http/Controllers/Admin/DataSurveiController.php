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

    // Izinkan semua tag yang dihasilkan Quill
    $allowedTags = '<div><p><br><strong><b><em><i><u><s><strike><span><ul><li><ol><blockquote><h1><h2><h3><h4><h5><h6><a><table><thead><tbody><tr><td><th><code><pre><img>';
    
    // Hapus tag yang tidak diizinkan
    $clean = strip_tags($html, $allowedTags);

    // Hapus event handlers (XSS prevention) dan javascript links
    $clean = preg_replace('/on\w+\s*=\s*(".*?"|\'.*?\')/i', '', $clean);
    $clean = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*(\1)/i', 'href="#"', $clean);
    
    // Perbaikan PENTING: Proses atribut CLASS, STYLE, SRC, ALT, dan HREF.
    // Quill.js sangat bergantung pada CLASS (misalnya ql-align-center, ql-syntax)
    
    // Regex callback untuk memproses atribut hanya pada tag yang relevan
    $clean = preg_replace_callback('/<(\w+)\s*([^>]*?)>/i', function ($m) {
        $tag = $m[1];
        $attributes = $m[2]; 

        // Tag yang atributnya harus diperiksa
        $tagsToCheck = ['div', 'p', 'span', 'img', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'blockquote', 'pre', 'code'];
        if (!in_array(strtolower($tag), $tagsToCheck)) {
            return "<$tag>"; // Kembali tanpa atribut (kecuali sudah di-strip_tags)
        }
        
        $new_attributes = [];
        // Regex untuk menangkap atribut yang diizinkan (class, style, href, src, alt, width, height)
        if (preg_match_all('/(class|style|href|src|alt|width|height)\s*=\s*("([^"]*)"|\'([^\']*)\')/i', $attributes, $matches, PREG_SET_ORDER)) {
            
            foreach ($matches as $match) {
                $attr_name = strtolower($match[1]);
                // Ambil nilai dari double quote (match[3]) atau single quote (match[4])
                $attr_value = $match[3] ?? $match[4] ?? ''; 

                if ($attr_name === 'style') {
                    // Sanitasi properti CSS yang diizinkan (penting untuk warna dan alignment)
                    $allowedProps = ['color', 'background-color', 'text-align', 'font-size', 'line-height', 'width', 'height', 'border', 'padding', 'margin']; 
                    $rules = array_filter(array_map('trim', explode(';', $attr_value)));
                    $kept = [];
                    foreach ($rules as $r) {
                        if (strpos($r, ':') !== false) {
                            [$prop, $val] = array_map('trim', explode(':', $r, 2));
                            if (in_array(strtolower($prop), $allowedProps) && $val !== '') {
                                $kept[] = $prop . ':' . $val;
                            }
                        }
                    }
                    if (count($kept)) {
                        $new_attributes[] = 'style="' . implode(';', $kept) . '"';
                    }
                } elseif (in_array($attr_name, ['class', 'src', 'alt', 'width', 'height'])) {
                    // Izinkan class (untuk Quill styles), src/alt (untuk image)
                    $new_attributes[] = $match[0];
                } elseif ($attr_name === 'href') {
                    // Izinkan href
                    $new_attributes[] = $match[0];
                }
            }
        }
        
        // Tambah target blank ke tag <a> jika ada href, atau jika belum ada
        if (strtolower($tag) === 'a') {
            if (!preg_match('/target=["\']_blank["\']/i', $attributes)) {
                 $new_attributes[] = 'target="_blank"';
            }
            if (!preg_match('/rel=["\']noopener noreferrer["\']/i', $attributes)) {
                 $new_attributes[] = 'rel="noopener noreferrer"';
            }
        }
        
        // Gabungkan kembali tag dengan atribut yang aman
        if (empty($new_attributes)) {
            return "<$tag>";
        } else {
            // Hapus duplikasi atribut dan gabungkan
            $new_attributes = array_unique($new_attributes);
            return "<$tag " . implode(' ', $new_attributes) . ">";
        }
    }, $clean);


    // Hapus tag yang kosong, kecuali <br> dan <img>
    $clean = preg_replace('/<(\w+)\s*(?:\/|[^>]*?)>[\s|&nbsp;]*<\/\1>/', '', $clean);

    return $clean;
}
}
