<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\PegawaiInternal;
use App\Mail\PegawaiVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PegawaiAuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        // Tampilkan form registrasi pegawai
        return view('pegawai.auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        // Validasi input registrasi pegawai
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:pegawai_internal,email',
                'regex:/^[a-zA-Z0-9._%+-]+@esdm\.go\.id$/', // Harus @esdm.go.id
            ],
            'kata_sandi' => ['required', 'string', 'min:8', 'confirmed'],
            'nip' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'email.regex' => 'Alamat Email yang diizinkan hanya @esdm.go.id',
            'kata_sandi.min' => 'Kata sandi minimal 8 karakter.',
            'kata_sandi.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Simpan data pegawai baru
        $pegawai = PegawaiInternal::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'kata_sandi' => Hash::make($validatedData['kata_sandi']),
            'nip' => $validatedData['nip'] ?? null,
            'jabatan' => $validatedData['jabatan'] ?? null,
        ]);

        // Generate token verifikasi email
        $token = $pegawai->generateVerificationToken();

        // Kirim email verifikasi
        try {
            Mail::to($pegawai->email)->send(new PegawaiVerificationMail($pegawai, $token));

            return redirect()
                ->route('pegawai.login')
                ->with('success', 'Akun berhasil dibuat! Silakan cek email ' . $pegawai->email . ' untuk verifikasi. Link verifikasi berlaku 1 jam.');
        } catch (\Exception $e) {
            // Jika email gagal dikirim, beri tahu user untuk hubungi admin
            return redirect()
                ->route('pegawai.login')
                ->with('warning', 'Akun berhasil dibuat, namun email verifikasi gagal dikirim. Silakan hubungi administrator untuk approval manual.');
        }
    }

    /**
     * Verify email with token
     */
    public function verifyEmail(string $token)
    {
        // Cari pegawai yang tokennya valid
        // Cari pegawai dengan token yang cocok
        $pegawai = PegawaiInternal::whereNotNull('verification_token')
            ->whereNotNull('verification_token_expires_at')
            ->get()
            ->first(function ($p) use ($token) {
                return $p->isValidVerificationToken($token);
            });

        if (!$pegawai) {
            return redirect()
                ->route('pegawai.login')
                ->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa. Silakan hubungi administrator.');
        }

        // Tandai email sebagai terverifikasi
        $pegawai->markEmailAsVerified();

        return redirect()
            ->route('pegawai.login')
            ->with('success', 'Email berhasil diverifikasi! Anda sekarang dapat login.');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Tampilkan form login pegawai
        return view('pegawai.auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'kata_sandi' => ['required'],
        ], [
            'email.required' => 'Kolom Email wajib diisi.',
            'email.email' => 'Format Email tidak valid.',
            'kata_sandi.required' => 'Kolom Kata Sandi wajib diisi.',
        ]);

        // Pastikan pegawai terdaftar
        $pegawai = PegawaiInternal::where('email', $credentials['email'])->first();

        if (!$pegawai) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak terdaftar sebagai pegawai internal.',
            ]);
        }

        // Pastikan akun sudah verified/approved
        if (!$pegawai->canLogin()) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda belum diverifikasi. Silakan cek email atau hubungi administrator.',
            ]);
        }

        // Autentikasi dengan guard pegawai
        if (Auth::guard('pegawai')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['kata_sandi']
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('katalog'));
        }

        throw ValidationException::withMessages([
            'email' => 'Kombinasi email dan kata sandi tidak valid.',
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // Logout dan bersihkan session
        Auth::guard('pegawai')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }

    /**
     * Handle password update (AJAX)
     */
    public function updatePassword(Request $request)
    {
        try {
            // Validasi password lama dan baru
            $request->validate([
                'current_password' => ['required'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'current_password.required' => 'Password saat ini wajib diisi.',
                'new_password.required' => 'Password baru wajib diisi.',
                'new_password.min' => 'Password baru minimal 8 karakter.',
                'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            ]);

            $pegawai = Auth::guard('pegawai')->user();

            // Pastikan password lama cocok
            if (!Hash::check($request->current_password, $pegawai->kata_sandi)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini tidak valid.',
                ], 422);
            }

            // Update password baru
            $pegawai->update([
                'kata_sandi' => Hash::make($request->new_password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah!',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()[array_key_first($e->errors())][0],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah password.',
            ], 500);
        }
    }
}
