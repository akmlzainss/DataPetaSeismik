<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSurvei;
use App\Http\Requests\DataSurveiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk handle file

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

        // Simpan siapa yang upload
        $data['diunggah_oleh'] = Auth::guard('admin')->id();

        // Handle upload gambar
        if ($request->hasFile('gambar_pratinjau')) {
            $data['gambar_pratinjau'] = $request->file('gambar_pratinjau')->store('gambar_pratinjau', 'public');
        }

        DataSurvei::create($data);

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil ditambahkan!');
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

        // Handle upload gambar baru
        if ($request->hasFile('gambar_pratinjau')) {
            // Hapus gambar lama jika ada
            if ($dataSurvei->gambar_pratinjau && Storage::disk('public')->exists($dataSurvei->gambar_pratinjau)) {
                Storage::disk('public')->delete($dataSurvei->gambar_pratinjau);
            }
            $data['gambar_pratinjau'] = $request->file('gambar_pratinjau')->store('gambar_pratinjau', 'public');
        }

        $dataSurvei->update($data);

        return redirect()
            ->route('admin.data_survei.index')
            ->with('success', 'Data survei berhasil diperbarui!');
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
