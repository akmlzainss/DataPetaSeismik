<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Http\Requests\DataSurveiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk handle file
use App\Jobs\ProcessSurveiImage;
use Illuminate\Support\Str;

class DataSurveiController extends Controller
{
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
            'terbaru'  => $query->orderBy('created_at', 'desc'),
            'terlama'  => $query->orderBy('created_at', 'asc'),
            'az'       => $query->orderBy('judul', 'asc'),
            'za'       => $query->orderBy('judul', 'desc'),
            default    => $query->orderBy('created_at', 'desc'),
        };

        $dataSurvei = $query->paginate(12)->withQueryString();

        // Ambil semua tahun unik untuk dropdown filter
        $tahuns = DataSurvei::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.data_survei.index', compact('dataSurvei', 'tahuns'));
    }

    public function create()
    {
        return view('admin.data_survei.create');
    }


    public function store(DataSurveiRequest $request)
    {
        $data = $request->validated();
        $data['diunggah_oleh'] = Auth::guard('admin')->id();

        if ($request->hasFile('gambar_pratinjau')) {
            $file = $request->file('gambar_pratinjau');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('gambar_pratinjau', $fileName, 'public');
            $data['gambar_pratinjau'] = $path;
        }

        $survei = DataSurvei::create($data);

        // Proses gambar di background
        if ($request->hasFile('gambar_pratinjau')) {
            ProcessSurveiImage::dispatch($survei->id)->delay(now()->addSeconds(5));
        }

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil ditambahkan! Gambar sedang diproses...');
    }

    public function show(DataSurvei $dataSurvei)
    {
        $dataSurvei->load('pengunggah', 'lokasi');
        return view('admin.data_survei.show', compact('dataSurvei'));
    }

    public function edit(DataSurvei $dataSurvei)
    {
        return view('admin.data_survei.edit', compact('dataSurvei'));
    }

    public function update(DataSurveiRequest $request, DataSurvei $dataSurvei)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar_pratinjau')) {
            // Hapus semua versi lama
            foreach ([$dataSurvei->gambar_pratinjau, $dataSurvei->gambar_thumbnail, $dataSurvei->gambar_medium] as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            $file = $request->file('gambar_pratinjau');
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('gambar_pratinjau', $fileName, 'public');
            $data['gambar_pratinjau'] = $path;
        }

        $dataSurvei->update($data);

        if ($request->hasFile('gambar_pratinjau')) {
            ProcessSurveiImage::dispatch($dataSurvei->id)->delay(now()->addSeconds(5));
        }

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil diperbarui! Gambar sedang diproses...');
    }

    public function destroy(DataSurvei $dataSurvei)
    {
        // Hapus gambar saat delete
        if ($dataSurvei->gambar_pratinjau && Storage::disk('public')->exists($dataSurvei->gambar_pratinjau)) {
            Storage::disk('public')->delete($dataSurvei->gambar_pratinjau);
        }

        $dataSurvei->delete();

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil dihapus!');
    }
}
