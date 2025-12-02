<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\DataSurvei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class PengaturanController extends Controller
{
    /**
     * Display pengaturan page
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // System Info
        $systemInfo = [
            'app_name' => 'Sistem Informasi Survei Seismik',
            'app_version' => '1.0.0',
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'database' => config('database.default'),
            'total_admin' => Admin::count(),
            'total_survei' => DataSurvei::count(),
            'disk_usage' => $this->getDiskUsage(),
        ];
        
        return view('admin.pengaturan.index', compact('admin', 'systemInfo'));
    }

    /**
     * Update profil admin
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admin,email,' . $admin->id,
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan admin lain',
        ]);
        
        try {
            $admin->update([
                'nama' => $request->nama,
                'email' => $request->email,
            ]);
            
            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            'new_password.min' => 'Password minimal 8 karakter',
        ]);
        
        $admin = Auth::guard('admin')->user();
        
        // Cek password lama
        if (!Hash::check($request->current_password, $admin->kata_sandi)) {
            return redirect()->back()->with('error', 'Password saat ini tidak sesuai!');
        }
        
        try {
            $admin->update([
                'kata_sandi' => Hash::make($request->new_password)
            ]);
            
            return redirect()->back()->with('success', 'Password berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }

    /**
     * Get disk usage info
     */
    private function getDiskUsage()
    {
        $path = public_path();
        
        if (function_exists('disk_free_space') && function_exists('disk_total_space')) {
            $free = disk_free_space($path);
            $total = disk_total_space($path);
            $used = $total - $free;
            
            return [
                'used' => $this->formatBytes($used),
                'total' => $this->formatBytes($total),
                'percentage' => round(($used / $total) * 100, 1)
            ];
        }
        
        return [
            'used' => 'N/A',
            'total' => 'N/A',
            'percentage' => 0
        ];
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Clear cache (optional)
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            
            return redirect()->back()->with('success', 'Cache berhasil dibersihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }
}