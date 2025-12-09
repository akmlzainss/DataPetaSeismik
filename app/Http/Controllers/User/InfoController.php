<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function panduan()
    {
        return view('User.info.panduan');
    }

    public function faq()
    {
        return view('User.info.faq');
    }

    public function privasi()
    {
        return view('User.info.privasi');
    }

    public function syarat()
    {
        return view('User.info.syarat');
    }

    public function bantuan()
    {
        return view('User.info.bantuan');
    }
}
