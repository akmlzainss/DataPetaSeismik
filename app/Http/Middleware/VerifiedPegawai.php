<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedPegawai
{
    /**
     * Handle an incoming request.
     * Memastikan pegawai sudah verified atau approved sebelum mengakses resource
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login sebagai pegawai
        if (!Auth::guard('pegawai')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Login pegawai required.'], 401);
            }
            return redirect()->route('pegawai.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pegawai = Auth::guard('pegawai')->user();

        // Cek apakah email sudah diverifikasi atau sudah di-approve admin
        if (!$pegawai->canLogin()) {
            Auth::guard('pegawai')->logout();
            $request->session()->invalidate();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Email belum diverifikasi. Silakan cek email Anda atau hubungi administrator.'
                ], 403);
            }

            return redirect()->route('pegawai.login')
                ->with('error', 'Akun Anda belum diverifikasi. Silakan cek email atau hubungi administrator.');
        }

        return $next($request);
    }
}
