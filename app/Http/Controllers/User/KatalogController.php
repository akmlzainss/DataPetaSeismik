<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSurvei;

class KatalogController extends Controller
{
    /**
     * Menampilkan halaman Katalog Survei (User).
     */
    public function index(Request $request)
    {
        // Ambil data survei dari database dengan pagination
        $query = DataSurvei::with(['pengunggah', 'lokasi'])
            ->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc');

        // Search Keyword (Judul, Deskripsi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', '%' . $search . '%')
                    ->orWhere('deskripsi', 'LIKE', '%' . $search . '%');
            });
        }

        // Filter berdasarkan tahun jika ada
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan tipe jika ada
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan wilayah jika ada
        if ($request->filled('wilayah')) {
            $query->where('wilayah', 'LIKE', '%' . $request->wilayah . '%');
        }

        $surveys = $query->paginate(12)->withQueryString();

        // Ambil data unik untuk filter dropdown
        $tahun_options = DataSurvei::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $tipe_options = DataSurvei::distinct()->orderBy('tipe')->pluck('tipe');
        $wilayah_options = DataSurvei::distinct()->orderBy('wilayah')->pluck('wilayah');

        return view('User.katalog.index', compact(
            'surveys',
            'tahun_options',
            'tipe_options',
            'wilayah_options'
        ));
    }

    /**
     * Menampilkan detail survei.
     */
    public function show($id)
    {
        $survey = DataSurvei::with(['pengunggah', 'lokasi'])
            ->findOrFail($id);

        // Check if request comes from peta (for custom breadcrumb)
        $fromPeta = request()->has('from_peta') && request('from_peta') === '1';
        $showFromPeta = $fromPeta;

        return view('user.katalog.show', compact('survey', 'showFromPeta'));
    }
}
