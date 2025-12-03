<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /**
     * Menampilkan halaman Katalog Survei (User).
     */
    public function index()
    {
        // Logika untuk mengambil data survei dari database
        $surveys = []; 

        return view('User.katalog.index', compact('surveys'));
    }
}