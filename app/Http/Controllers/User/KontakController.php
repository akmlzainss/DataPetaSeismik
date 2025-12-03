<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    /**
     * Menampilkan halaman Kontak (User).
     */
    public function index()
    {
        return view('User.kontak.index');
    }
    
    // Opsional: method untuk menangani POST request dari form kontak
    // public function submit(Request $request) { ... }
}