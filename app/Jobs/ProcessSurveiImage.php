<?php

namespace App\Jobs;

use App\Models\DataSurvei;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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

        if (!$survei || !$survei->gambar_pratinjau) {
            return;
        }

        $originalPath = $survei->gambar_pratinjau;
        $fullPath = storage_path("app/public/{$originalPath}");

        if (!file_exists($fullPath)) {
            Log::warning("File gambar tidak ditemukan: {$fullPath}");
            return;
        }

        $fileName = pathinfo($originalPath, PATHINFO_FILENAME);
        $manager = new ImageManager(new Driver());

        try {
            // === THUMBNAIL 400x300 — KHUSUS GAMBAR PANJANG (PAKAI LETTERBOX) ===
            $thumbPath = "gambar_pratinjau/thumb/{$fileName}_thumb.jpg";
            $thumbFullPath = storage_path("app/public/{$thumbPath}");

            $thumb = $manager->read($fullPath);
            // TIDAK ADA orientate() di v3 → kita pakai cara manual kalau perlu
            // Tapi 99% gambar seismik sudah benar orientasinya, jadi cukup ini:
            $thumb->resize(380, 280, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            // Tambah padding biru tua biar tetap kotak 400x300 (letterbox)
            $thumb->resizeCanvas(400, 300, 'center', false, '#0f172a');
            $thumb->toJpeg(85)->save($thumbFullPath);

            // === MEDIUM — BATASI TINGGI 4000px (BIAR OPEN-SEADRAGON CEPAT) ===
            $mediumPath = "gambar_pratinjau/medium/{$fileName}_medium.jpg";
            $mediumFullPath = storage_path("app/public/{$mediumPath}");

            $medium = $manager->read($fullPath);
            $medium->resize(null, 4000, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $medium->toJpeg(88)->save($mediumFullPath);

            // Update database
            $survei->update([
                'gambar_thumbnail' => $thumbPath,
                'gambar_medium'    => $mediumPath,
            ]);

            Log::info("Sukses proses gambar survei ID {$this->surveiId}");

        } catch (\Exception $e) {
            Log::error("Gagal proses gambar survei ID {$this->surveiId}: " . $e->getMessage());
        }
    }
}