<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function panduan()
    {
        // Tampilkan halaman panduan pengguna
        return view('User.info.panduan');
    }

    public function faq()
    {
        // Tampilkan halaman FAQ
        return view('User.info.faq');
    }

    public function privasi()
    {
        // Tampilkan kebijakan privasi
        return view('User.info.privasi');
    }

    public function syarat()
    {
        // Tampilkan syarat dan ketentuan
        return view('User.info.syarat');
    }

    public function bantuan()
    {
        // Tampilkan halaman bantuan
        return view('User.info.bantuan');
    }
}
