<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DataSurvei;

class SystemOptimizationService
{
    /**
     * Get system statistics with caching
     */
    public function getSystemStats(): array
    {
        return Cache::remember('system_stats', 3600, function () {
            return [
                'total_surveys' => DataSurvei::count(),
                'surveys_with_images' => DataSurvei::whereNotNull('gambar_pratinjau')->count(),
                'surveys_with_markers' => DataSurvei::has('lokasi')->count(),
                'total_storage_used' => $this->getTotalStorageUsed(),
                'database_size' => $this->getDatabaseSize(),
                'cache_size' => $this->getCacheSize(),
            ];
        });
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        return Cache::remember('performance_metrics', 1800, function () {
            $start = microtime(true);

            // Test database query performance
            DB::table('data_survei')->limit(1)->get();
            $dbTime = microtime(true) - $start;

            // Test file system performance
            $start = microtime(true);
            Storage::disk('public')->exists('test');
            $fsTime = microtime(true) - $start;

            return [
                'database_response_time' => round($dbTime * 1000, 2), // ms
                'filesystem_response_time' => round($fsTime * 1000, 2), // ms
                'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2), // MB
                'peak_memory' => round(memory_get_peak_usage(true) / 1024 / 1024, 2), // MB
            ];
        });
    }

    /**
     * Optimize database queries with proper indexing suggestions
     */
    public function getDatabaseOptimizationSuggestions(): array
    {
        $suggestions = [];

        // Check for missing indexes
        $slowQueries = $this->getSlowQueries();
        if (!empty($slowQueries)) {
            $suggestions[] = [
                'type' => 'index',
                'message' => 'Consider adding indexes for frequently queried columns',
                'details' => $slowQueries
            ];
        }

        // Check for large tables without pagination
        $largeTables = $this->getLargeTables();
        if (!empty($largeTables)) {
            $suggestions[] = [
                'type' => 'pagination',
                'message' => 'Large tables detected, ensure pagination is implemented',
                'details' => $largeTables
            ];
        }

        return $suggestions;
    }

    /**
     * Get storage optimization suggestions
     */
    public function getStorageOptimizationSuggestions(): array
    {
        $suggestions = [];

        // Check for large images
        $largeImages = $this->getLargeImages();
        if (!empty($largeImages)) {
            $suggestions[] = [
                'type' => 'image_optimization',
                'message' => 'Large images detected, consider optimization',
                'details' => $largeImages
            ];
        }

        // Check for unused files
        $unusedFiles = $this->getUnusedFiles();
        if (!empty($unusedFiles)) {
            $suggestions[] = [
                'type' => 'cleanup',
                'message' => 'Unused files detected',
                'details' => count($unusedFiles) . ' files can be cleaned up'
            ];
        }

        return $suggestions;
    }

    /**
     * Optimize system automatically
     */
    public function autoOptimize(): array
    {
        $results = [];

        // Clear expired cache entries
        $results['cache_cleared'] = $this->clearExpiredCache();

        // Optimize images
        $results['images_optimized'] = $this->optimizeImages();

        // Clean up temporary files
        $results['temp_files_cleaned'] = $this->cleanupTempFiles();

        return $results;
    }

    private function getTotalStorageUsed(): float
    {
        $totalSize = 0;
        $files = Storage::disk('public')->allFiles();

        foreach ($files as $file) {
            $totalSize += Storage::disk('public')->size($file);
        }

        return round($totalSize / 1024 / 1024, 2); // MB
    }

    private function getDatabaseSize(): float
    {
        try {
            $result = DB::select("SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()");

            return $result[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCacheSize(): float
    {
        try {
            $cacheDir = storage_path('framework/cache');
            if (!is_dir($cacheDir)) return 0;

            $size = 0;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($cacheDir)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            return round($size / 1024 / 1024, 2); // MB
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getSlowQueries(): array
    {
        // This would require slow query log analysis
        // For now, return common slow query patterns
        return [
            'data_survei without index on created_at',
            'lokasi_marker without spatial index'
        ];
    }

    private function getLargeTables(): array
    {
        try {
            $result = DB::select("SELECT 
                table_name, 
                table_rows,
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
                FROM information_schema.tables 
                WHERE table_schema = DATABASE() 
                AND table_rows > 10000
                ORDER BY size_mb DESC");

            return collect($result)->map(function ($table) {
                return [
                    'name' => $table->table_name,
                    'rows' => $table->table_rows,
                    'size_mb' => $table->size_mb
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getLargeImages(): array
    {
        $largeImages = [];
        $files = Storage::disk('public')->allFiles('gambar_pratinjau');

        foreach ($files as $file) {
            $size = Storage::disk('public')->size($file);
            if ($size > 5 * 1024 * 1024) { // > 5MB
                $largeImages[] = [
                    'file' => $file,
                    'size_mb' => round($size / 1024 / 1024, 2)
                ];
            }
        }

        return $largeImages;
    }

    private function getUnusedFiles(): array
    {
        $usedImages = DataSurvei::whereNotNull('gambar_pratinjau')
            ->pluck('gambar_pratinjau', 'gambar_thumbnail', 'gambar_medium')
            ->flatten()
            ->filter()
            ->toArray();

        $allFiles = Storage::disk('public')->allFiles('gambar_pratinjau');

        return array_diff($allFiles, $usedImages);
    }

    private function clearExpiredCache(): int
    {
        // Laravel automatically handles expired cache entries
        // This is a placeholder for custom cache clearing logic
        return 0;
    }

    private function optimizeImages(): int
    {
        $imageService = new ImageOptimizationService();
        $surveys = DataSurvei::whereNotNull('gambar_pratinjau')->limit(10)->get();

        $optimized = 0;
        foreach ($surveys as $survey) {
            if ($imageService->optimize($survey->gambar_pratinjau)) {
                $optimized++;
            }
        }

        return $optimized;
    }

    private function cleanupTempFiles(): int
    {
        $tempDirs = ['temp', 'tmp'];
        $cleaned = 0;

        foreach ($tempDirs as $dir) {
            if (Storage::disk('public')->exists($dir)) {
                $files = Storage::disk('public')->allFiles($dir);
                foreach ($files as $file) {
                    Storage::disk('public')->delete($file);
                    $cleaned++;
                }
            }
        }

        return $cleaned;
    }
}
