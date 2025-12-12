<?php

namespace App\Jobs;

use App\Models\DataSurvei;
use App\Services\ImageOptimizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $imageService = new ImageOptimizationService();

        try {
            // Create thumbnail path
            $thumbDir = 'gambar_pratinjau/thumb/';
            $thumbPath = $thumbDir . "{$fileName}_thumb.webp";

            // Create medium path
            $mediumDir = 'gambar_pratinjau/medium/';
            $mediumPath = $mediumDir . "{$fileName}_medium.jpg";

            // Process thumbnail using service
            $thumbSuccess = $imageService->createThumbnail($originalPath, $thumbPath, 400, 300, 80);

            // Process medium image using service
            $mediumSuccess = $imageService->createMedium($originalPath, $mediumPath, 4000, 5000, 85);

            // Update database only if both processes succeeded
            if ($thumbSuccess && $mediumSuccess) {
                $survei->update([
                    'gambar_thumbnail' => $thumbPath,
                    'gambar_medium' => $mediumPath,
                ]);

                Log::info("Successfully processed images for survey ID {$this->surveiId}. Thumbnail: {$thumbPath}, Medium: {$mediumPath}");
            } else {
                Log::warning("Partial failure processing images for survey ID {$this->surveiId}. Thumb: " . ($thumbSuccess ? 'OK' : 'FAILED') . ", Medium: " . ($mediumSuccess ? 'OK' : 'FAILED'));
            }
        } catch (\Exception $e) {
            Log::error("Failed to process images for survey ID {$this->surveiId}: " . $e->getMessage());

            // Clean up any partial files
            Storage::disk('public')->delete([
                $thumbPath ?? null,
                $mediumPath ?? null
            ]);
        }
    }
}
