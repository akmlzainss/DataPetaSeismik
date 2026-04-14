<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\PegawaiInternal;
use App\Mail\PegawaiPasswordResetMail;

class PegawaiForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showLinkRequestForm()
    {
        // Tampilkan form permintaan reset password pegawai
        return view('pegawai.auth.forgot-password');
    }

    /**
     * Send reset password link via email
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi email pegawai
        $request->validate([
            'email' => 'required|email|exists:pegawai_internal,email'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.'
        ]);

        // Generate token reset
        $token = Str::random(64);

        // Hapus token lama untuk email ini
        DB::table('pegawai_password_resets')->where('email', $request->email)->delete();

        // Simpan token baru
        DB::table('pegawai_password_resets')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Ambil data pegawai untuk email
        $pegawai = PegawaiInternal::where('email', $request->email)->first();

        // Kirim email reset password
        try {
            Mail::to($request->email)->send(new PegawaiPasswordResetMail($pegawai, $token));

            return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi nanti.');
        }
    }

    /**
     * Show reset password form
     */
    public function showResetForm($token)
    {
        // Tampilkan form reset password dengan token dari URL
        return view('pegawai.auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle reset password form when token is passed as query string
     */
    public function showResetFormFromQuery(Request $request)
    {
        // Ambil token dari query string
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('pegawai.password.request')
                ->with('error', 'Token reset password tidak ditemukan. Silakan request ulang.');
        }

        return view('pegawai.auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle password reset
     */
    public function reset(Request $request)
    {
        // Validasi input reset password
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:pegawai_internal,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek token dan masa berlaku (maks 60 menit)
        $resetRecord = DB::table('pegawai_password_resets')
            ->where('email', $request->email)
            ->where('created_at', '>', now()->subMinutes(60))
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'Token reset password tidak valid atau sudah kedaluwarsa.');
        }

        // Update password pegawai
        $pegawai = PegawaiInternal::where('email', $request->email)->first();
        $pegawai->update([
            'kata_sandi' => Hash::make($request->password)
        ]);

        // Hapus token agar tidak bisa dipakai ulang
        DB::table('pegawai_password_resets')->where('email', $request->email)->delete();

        return redirect()->route('pegawai.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
