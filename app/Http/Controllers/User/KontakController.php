<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class KontakController extends Controller
{
    /**
     * Menampilkan halaman Kontak (User).
     */
    public function index()
    {
        return view('User.kontak.index');
    }
    
    /**
     * Menangani pengiriman form kontak.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subjek.required' => 'Subjek pesan wajib diisi.',
            'pesan.required' => 'Isi pesan wajib diisi.',
        ]);

        try {
            // Daftar penerima email
            $recipients = [
                'bbspgl@esdm.go.id',
                'akmalzains01@gmail.com'
            ];

            // Kirim email
            Mail::to($recipients)->send(new ContactFormMail($validated));

            return back()->with('success', 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi kami.');
        } catch (\Exception $e) {
            return back()->with('error', 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti. ' . $e->getMessage())->withInput();
        }
    }
}