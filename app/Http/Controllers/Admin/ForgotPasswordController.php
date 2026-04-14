<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin;
use App\Mail\AdminPasswordResetMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        // Tampilkan form permintaan reset password
        return view('admin.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validasi email admin
        $request->validate([
            'email' => 'required|email|exists:admin,email'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.'
        ]);

        // Generate token reset baru
        $token = Str::random(64);

        // Hapus token lama untuk email ini
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Simpan token baru
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Ambil data admin untuk email
        $admin = Admin::where('email', $request->email)->first();

        // Kirim email reset password
        try {
            Mail::to($request->email)->send(new AdminPasswordResetMail($admin, $token));

            return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi nanti.');
        }
    }

    public function showResetForm($token)
    {
        // Tampilkan form reset password dengan token dari URL
        return view('admin.auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle reset password form when token is passed as query string.
     * This helps testing bots that may use ?token=xxx format.
     */
    public function showResetFormFromQuery(Request $request)
    {
        // Ambil token dari query string
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Token reset password tidak ditemukan. Silakan request ulang.');
        }

        return view('admin.auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        // Validasi input reset password
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:admin,email',
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
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('created_at', '>', now()->subMinutes(60))
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'Token reset password tidak valid atau sudah kedaluwarsa.');
        }

        // Update password admin
        $admin = Admin::where('email', $request->email)->first();
        $admin->update([
            'kata_sandi' => Hash::make($request->password)
        ]);

        // Hapus token agar tidak bisa dipakai ulang
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
