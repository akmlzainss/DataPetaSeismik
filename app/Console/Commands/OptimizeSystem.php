<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Models\DataSurvei;
use App\Services\ImageOptimizationService;

class OptimizeSystem extends Command
{
    protected $signature = 'system:optimize {--images : Optimize existing images} {--cleanup : Clean up unused files} {--cache : Clear and rebuild cache}';
    protected $description = 'Optimize the survey system by cleaning up files, optimizing images, and rebuilding cache';

    public function handle()
    {
        $this->info('🚀 Starting system optimization...');

        if ($this->option('cleanup') || !$this->hasOptions()) {
            $this->cleanupUnusedFiles();
        }

        if ($this->option('images') || !$this->hasOptions()) {
            $this->optimizeImages();
        }

        if ($this->option('cache') || !$this->hasOptions()) {
            $this->optimizeCache();
        }

        $this->info('✅ System optimization completed!');
    }

    private function hasOptions(): bool
    {
        return $this->option('images') || $this->option('cleanup') || $this->option('cache');
    }

    private function cleanupUnusedFiles()
    {
        $this->info('🧹 Cleaning up unused files...');

        // Clean up orphaned image files
        $this->cleanupOrphanedImages();

        // Clean up temporary files
        $this->cleanupTempFiles();

        // Clean up old log files
        $this->cleanupOldLogs();

        $this->info('✅ File cleanup completed');
    }

    private function cleanupOrphanedImages()
    {
        $this->info('   Checking for orphaned images...');

        // Get all image paths from database
        $usedImages = DataSurvei::whereNotNull('gambar_pratinjau')
            ->pluck('gambar_pratinjau', 'gambar_thumbnail', 'gambar_medium')
            ->flatten()
            ->filter()
            ->toArray();

        // Get all files in storage
        $allFiles = collect(Storage::disk('public')->allFiles('gambar_pratinjau'))
            ->filter(fn($file) => !in_array($file, $usedImages));

        $deletedCount = 0;
        foreach ($allFiles as $file) {
            if (Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
                $deletedCount++;
            }
        }

        $this->info("   Deleted {$deletedCount} orphaned image files");
    }

    private function cleanupTempFiles()
    {
        $this->info('   Cleaning temporary files...');

        $tempDirs = ['temp', 'tmp', 'cache'];
        $deletedCount = 0;

        foreach ($tempDirs as $dir) {
            if (Storage::disk('public')->exists($dir)) {
                $files = Storage::disk('public')->allFiles($dir);
                foreach ($files as $file) {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                }
            }
        }

        $this->info("   Deleted {$deletedCount} temporary files");
    }

    private function cleanupOldLogs()
    {
        $this->info('   Cleaning old log files...');

        $logPath = storage_path('logs');
        $files = glob($logPath . '/laravel-*.log');
        $deletedCount = 0;

        foreach ($files as $file) {
            if (filemtime($file) < strtotime('-30 days')) {
                unlink($file);
                $deletedCount++;
            }
        }

        $this->info("   Deleted {$deletedCount} old log files");
    }

    private function optimizeImages()
    {
        $this->info('🖼️  Optimizing images...');

        $imageService = new ImageOptimizationService();
        $surveys = DataSurvei::whereNotNull('gambar_pratinjau')->get();

        $optimizedCount = 0;
        $bar = $this->output->createProgressBar($surveys->count());

        foreach ($surveys as $survey) {
            if ($imageService->optimize($survey->gambar_pratinjau, 85)) {
                $optimizedCount++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("   Optimized {$optimizedCount} images");
    }

    private function optimizeCache()
    {
        $this->info('💾 Optimizing cache...');

        // Clear all caches
        Cache::flush();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        // Rebuild optimized caches
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        $this->info('✅ Cache optimization completed');
    }
}
