<?php

namespace App\Jobs;

use App\Models\DataSurvei;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessSurveiImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 600;
    public $backoff = [10, 30, 60];

    protected $surveiId;

    public function __construct($surveiId)
    {
        $this->surveiId = $surveiId;
    }

    public function handle(): void
    {
        $survei = DataSurvei::find($this->surveiId);
        if (!$survei || !$survei->gambar_pratinjau) return;

        $originalPath = $survei->gambar_pratinjau;
        // Ganti ke cara Laravel Storage
        $fullPath = Storage::disk('public')->path($originalPath); 
        
        if (!Storage::disk('public')->exists($originalPath)) {
             Log::warning("File gambar asli tidak ditemukan: {$originalPath}");
             return;
        }

        $fileName = pathinfo($originalPath, PATHINFO_FILENAME);
        $manager = new ImageManager(new Driver());

        try {
            // --- 1. PROSES THUMBNAIL (400x300, cepat load di Index) ---
            $thumbDir = 'gambar_pratinjau/thumb/';
            Storage::disk('public')->makeDirectory($thumbDir); // Pastikan folder ada
            $thumbPath = $thumbDir . "{$fileName}_thumb.webp"; // Ganti ke WEBP agar lebih kecil
            $thumbFullPath = Storage::disk('public')->path($thumbPath);

            $thumb = $manager->read($fullPath);
            // Resize ke max 400x300, lalu di-canvas. Ini sudah OK.
            $thumb->resize(380, 280, fn($c) => $c->aspectRatio()->upsize());
            $thumb->resizeCanvas(400, 300, 'center', false, '#0f172a');
            $thumb->toWebp(75)->save($thumbFullPath); // Kompresi WEBP 75

            // --- 2. PROSES MEDIUM (Untuk OpenSeadragon: Max Height 5000px, Max Quality) ---
            $mediumDir = 'gambar_pratinjau/medium/';
            Storage::disk('public')->makeDirectory($mediumDir); // Pastikan folder ada
            $mediumPath = $mediumDir . "{$fileName}_medium.jpg"; // Tetap JPG agar lebih kompatibel dengan OSD
            $mediumFullPath = Storage::disk('public')->path($mediumPath);

            $medium = $manager->read($fullPath);

            // Perbaikan: Batasi TINGGI maksimal 5000px DAN LEBAR maksimal 4000px (atau sesuaikan)
            // Tujuannya agar gambar tidak terlalu tinggi/lebar sehingga OpenSeadragon bisa memprosesnya
            $medium->resize(4000, 5000, function ($constraint) {
                 $constraint->aspectRatio();
                 $constraint->upsize(); // Jangan pernah naikkan ukuran
            });

            // Kompresi JPEG dengan kualitas 80 (cukup baik, tapi ukuran file lebih kecil)
            $medium->toJpeg(80)->save($mediumFullPath); 

            $survei->update([
                'gambar_thumbnail' => $thumbPath,
                'gambar_medium'    => $mediumPath,
            ]);

            Log::info("Sukses proses gambar ID {$this->surveiId}. Thumbnail: {$thumbPath}, Medium: {$mediumPath}");

        } catch (\Exception $e) {
            Log::error("Gagal proses gambar ID {$this->surveiId}: " . $e->getMessage());
            // Optional: Hapus file yang mungkin setengah jadi jika terjadi error
             Storage::disk('public')->delete([$thumbPath ?? null, $mediumPath ?? null]);
        }
    }
}