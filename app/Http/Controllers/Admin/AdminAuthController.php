<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin; // Pastikan Model Admin di-import

class AdminAuthController extends Controller
{
    // --- 1. LOGIN METHODS ---

    public function showLoginForm()
    {
        // KOREKSI PATH VIEW
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'kata_sandi' => ['required'],
        ], [
            'email.required' => 'Kolom Email wajib diisi.',
            'email.email' => 'Format Email tidak valid.',
            'kata_sandi.required' => 'Kolom Kata Sandi wajib diisi.',
        ]);

        if (Auth::guard('admin')->attempt(['email' => $credentials['email'], 'password' => $credentials['kata_sandi']], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard')); 
        }

        throw ValidationException::withMessages([
            'email' => 'Kombinasi email dan kata sandi tidak valid.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login'); 
    }

    // --- 2. REGISTRATION METHODS (DAFTAR AKUN) ---

    public function showRegistrationForm()
    {
        // KOREKSI PATH VIEW
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admin,email'],
            'kata_sandi' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar sebagai Admin lain.'
        ]);

        Admin::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'kata_sandi' => Hash::make($validatedData['kata_sandi']),
        ]);

        if (Auth::guard('admin')->attempt(['email' => $validatedData['email'], 'password' => $request->kata_sandi])) {
             $request->session()->regenerate();
        }

        return redirect()->route('admin.dashboard')->with('status', 'Akun Administrator baru berhasil didaftarkan dan Anda telah masuk.');
    }
}