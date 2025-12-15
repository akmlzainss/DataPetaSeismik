<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     * Menggunakan guard 'admin' untuk otentikasi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah pengguna dengan guard 'admin' telah terautentikasi
        if (! Auth::guard('admin')->check()) {

            // Jika ini adalah permintaan API atau AJAX
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Admin login required.'], 401);
            }

            // Redirect ke halaman login admin yang sudah kita definisikan
            return redirect()->route('admin.login');
        }

        // Regenerate session ID periodically for security
        if (
            ! $request->session()->has('last_regenerated') ||
            time() - $request->session()->get('last_regenerated') > 300
        ) { // 5 minutes
            $request->session()->regenerate();
            $request->session()->put('last_regenerated', time());
        }

        // Additional role check - ensure user is still active admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->exists) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            return redirect()->route('admin.login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        return $next($request);
    }
}
