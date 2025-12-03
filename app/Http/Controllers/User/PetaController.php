<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    /**
     * Menampilkan halaman Peta Interaktif (User).
     */
    public function index()
    {
        // Logika untuk mengambil data geografis (marker, GeoJSON)
        return view('User.peta.index');
    }
}