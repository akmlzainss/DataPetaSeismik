<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PegawaiInternal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiApprovalController extends Controller
{
    /**
     * Menampilkan daftar pegawai yang pending approval
     */
    public function index()
    {
        $pendingPegawai = PegawaiInternal::pendingApproval()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $approvedPegawai = PegawaiInternal::approved()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.pegawai.index', compact('pendingPegawai', 'approvedPegawai'));
    }

    /**
     * Approve pegawai secara manual (backup jika email tidak masuk)
     */
    public function approve(Request $request, $id)
    {
        $pegawai = PegawaiInternal::findOrFail($id);

        // Jika sudah approved, redirect
        if ($pegawai->is_approved) {
            return redirect()
                ->route('admin.pegawai.index')
                ->with('info', 'Pegawai ini sudah di-approve sebelumnya.');
        }

        // Approve pegawai
        $pegawai->is_approved = true;
        $pegawai->approved_by = Auth::guard('admin')->id();
        $pegawai->approved_at = now();
        
        // Jika belum verified via email, mark as verified juga
        if (!$pegawai->hasVerifiedEmail()) {
            $pegawai->email_verified_at = now();
        }
        
        $pegawai->save();

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', "Pegawai {$pegawai->nama} ({$pegawai->email}) berhasil di-approve!");
    }

    /**
     * Reject/Delete pegawai
     */
    public function reject(Request $request, $id)
    {
        $pegawai = PegawaiInternal::findOrFail($id);
        $nama = $pegawai->nama;
        $email = $pegawai->email;

        $pegawai->delete();

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', "Pendaftaran pegawai {$nama} ({$email}) berhasil ditolak dan dihapus.");
    }

    /**
     * Revoke approval (optional)
     */
    public function revoke(Request $request, $id)
    {
        $pegawai = PegawaiInternal::findOrFail($id);

        $pegawai->is_approved = false;
        $pegawai->approved_by = null;
        $pegawai->approved_at = null;
        $pegawai->save();

        return redirect()
            ->route('admin.pegawai.index')
            ->with('warning', "Approval untuk {$pegawai->nama} telah dicabut.");
    }
}
