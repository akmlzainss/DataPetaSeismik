<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TentangKamiController extends Controller
{
    /**
     * Menampilkan halaman Tentang Kami (User).
     */
    public function index()
    {
        return view('User.tentang_kami.index');
    }
}