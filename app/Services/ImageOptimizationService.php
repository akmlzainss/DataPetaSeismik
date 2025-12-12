<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageOptimizationService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Create optimized thumbnail for fast loading
     */
    public function createThumbnail(string $originalPath, string $outputPath, int $width = 400, int $height = 300, int $quality = 80): bool
    {
        try {
            if (!Storage::disk('public')->exists($originalPath)) {
                return false;
            }

            $fullPath = Storage::disk('public')->path($originalPath);
            $outputFullPath = Storage::disk('public')->path($outputPath);

            // Ensure output directory exists
            Storage::disk('public')->makeDirectory(dirname($outputPath));

            $image = $this->manager->read($fullPath);

            // Resize maintaining aspect ratio
            $image->resize($width - 20, $height - 20, fn($c) => $c->aspectRatio()->upsize());

            // Add canvas background
            $image->resizeCanvas($width, $height, 'center', false, '#0f172a');

            // Apply optimizations
            $image->sharpen(10);

            // Save as WebP for better compression
            $image->toWebp($quality)->save($outputFullPath);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to create thumbnail: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create medium-sized image for detailed viewing
     */
    public function createMedium(string $originalPath, string $outputPath, int $maxWidth = 4000, int $maxHeight = 5000, int $quality = 85): bool
    {
        try {
            if (!Storage::disk('public')->exists($originalPath)) {
                return false;
            }

            $fullPath = Storage::disk('public')->path($originalPath);
            $outputFullPath = Storage::disk('public')->path($outputPath);

            // Ensure output directory exists
            Storage::disk('public')->makeDirectory(dirname($outputPath));

            $image = $this->manager->read($fullPath);

            // Resize with maximum dimensions
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Apply enhancements
            $image->sharpen(5);
            $image->brightness(2);
            $image->contrast(3);

            // Save as high-quality JPEG
            $image->toJpeg($quality)->save($outputFullPath);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to create medium image: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create watermarked version of image
     */
    public function addWatermark(string $imagePath, string $watermarkPath, string $outputPath, string $position = 'bottom-right', int $opacity = 50): bool
    {
        try {
            if (!Storage::disk('public')->exists($imagePath) || !Storage::disk('public')->exists($watermarkPath)) {
                return false;
            }

            $imageFullPath = Storage::disk('public')->path($imagePath);
            $watermarkFullPath = Storage::disk('public')->path($watermarkPath);
            $outputFullPath = Storage::disk('public')->path($outputPath);

            $image = $this->manager->read($imageFullPath);
            $watermark = $this->manager->read($watermarkFullPath);

            // Resize watermark to 10% of image width
            $watermarkWidth = (int)($image->width() * 0.1);
            $watermark->resize($watermarkWidth, null, fn($c) => $c->aspectRatio());

            // Position watermark
            $x = $y = 20; // Default padding
            switch ($position) {
                case 'top-left':
                    $x = 20;
                    $y = 20;
                    break;
                case 'top-right':
                    $x = $image->width() - $watermark->width() - 20;
                    $y = 20;
                    break;
                case 'bottom-left':
                    $x = 20;
                    $y = $image->height() - $watermark->height() - 20;
                    break;
                case 'bottom-right':
                default:
                    $x = $image->width() - $watermark->width() - 20;
                    $y = $image->height() - $watermark->height() - 20;
                    break;
                case 'center':
                    $x = ($image->width() - $watermark->width()) / 2;
                    $y = ($image->height() - $watermark->height()) / 2;
                    break;
            }

            // Apply watermark with opacity
            $image->place($watermark, 'top-left', (int)$x, (int)$y, $opacity);

            // Save result
            $image->toJpeg(90)->save($outputFullPath);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to add watermark: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Optimize existing image (reduce file size while maintaining quality)
     */
    public function optimize(string $imagePath, int $quality = 80): bool
    {
        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return false;
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $image = $this->manager->read($fullPath);

            // Apply optimizations
            $image->sharpen(3);

            // Determine format and save with optimization
            $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image->toJpeg($quality)->save($fullPath);
                    break;
                case 'png':
                    $image->toPng()->save($fullPath);
                    break;
                case 'webp':
                    $image->toWebp($quality)->save($fullPath);
                    break;
                default:
                    $image->toJpeg($quality)->save($fullPath);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to optimize image: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get image information
     */
    public function getImageInfo(string $imagePath): ?array
    {
        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return null;
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $image = $this->manager->read($fullPath);

            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'size' => Storage::disk('public')->size($imagePath),
                'mime_type' => $image->origin()->mediaType(),
                'aspect_ratio' => round($image->width() / $image->height(), 2)
            ];
        } catch (\Exception $e) {
            Log::error("Failed to get image info: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Create multiple sizes at once
     */
    public function createMultipleSizes(string $originalPath, array $sizes): array
    {
        $results = [];

        foreach ($sizes as $sizeName => $config) {
            $outputPath = str_replace(
                pathinfo($originalPath, PATHINFO_FILENAME),
                pathinfo($originalPath, PATHINFO_FILENAME) . "_{$sizeName}",
                $originalPath
            );

            if ($sizeName === 'thumbnail') {
                $results[$sizeName] = $this->createThumbnail(
                    $originalPath,
                    $outputPath,
                    $config['width'] ?? 400,
                    $config['height'] ?? 300,
                    $config['quality'] ?? 80
                );
            } else {
                $results[$sizeName] = $this->createMedium(
                    $originalPath,
                    $outputPath,
                    $config['width'] ?? 4000,
                    $config['height'] ?? 5000,
                    $config['quality'] ?? 85
                );
            }
        }

        return $results;
    }
}
