<?php
namespace Core\ComputerVision;

require_once __DIR__ . '/VisionIngestor.php';

/**
 * VisionEngine
 * Core Computer Vision processing engine.
 * Uses PHP GD library for native image manipulation and
 * connects to modules/visionphp for advanced operations.
 */
class VisionEngine {
    
    private $ingestor;

    public function __construct() {
        $this->ingestor = new VisionIngestor();
    }

    /**
     * Full image analysis pipeline
     */
    public function processImage(string $imagePath, $originalName = null): array {
        // Step 1: Ingest and validate
        $meta = $this->ingestor->ingest($imagePath, $originalName);
        if ($meta['status'] !== 'success') {
            return $meta;
        }

        // Step 2: Get grayscale matrix
        $grayMatrix = $this->ingestor->toGrayscaleMatrix($imagePath);
        
        // Step 3: Run analysis
        $stats = $this->analyzePixelDistribution($grayMatrix);
        $edges = $this->detectEdgesBasic($grayMatrix);
        
        return [
            'status' => 'success',
            'metadata' => $meta,
            'analysis' => [
                'mean_brightness' => $stats['mean'],
                'std_brightness' => $stats['std'],
                'min_brightness' => $stats['min'],
                'max_brightness' => $stats['max'],
                'edge_pixel_count' => $edges['edge_count'],
                'edge_density' => $edges['edge_density'],
                'dominant_zone' => $stats['dominant_zone'],
            ]
        ];
    }

    /**
     * Analyze pixel intensity distribution
     */
    private function analyzePixelDistribution(array $grayMatrix): array {
        $allPixels = [];
        foreach ($grayMatrix as $row) {
            foreach ($row as $val) {
                $allPixels[] = $val;
            }
        }

        $count = count($allPixels);
        $sum = array_sum($allPixels);
        $mean = $sum / $count;

        // Standard Deviation
        $variance = 0;
        foreach ($allPixels as $p) {
            $variance += ($p - $mean) ** 2;
        }
        $std = sqrt($variance / $count);

        // Dominant brightness zone
        $zone = 'mid-tone';
        if ($mean < 85) $zone = 'dark';
        elseif ($mean > 170) $zone = 'bright';

        return [
            'mean' => round($mean, 2),
            'std' => round($std, 2),
            'min' => min($allPixels),
            'max' => max($allPixels),
            'dominant_zone' => $zone,
        ];
    }

    /**
     * Basic Sobel-like edge detection on grayscale matrix
     * Uses simple gradient magnitude thresholding
     */
    private function detectEdgesBasic(array $grayMatrix): array {
        $height = count($grayMatrix);
        $width = count($grayMatrix[0]);
        $edgeCount = 0;
        $totalPixels = ($height - 2) * ($width - 2);
        $threshold = 50;

        // Sobel Kernels
        $gx = [[-1, 0, 1], [-2, 0, 2], [-1, 0, 1]];
        $gy = [[-1, -2, -1], [0, 0, 0], [1, 2, 1]];

        for ($y = 1; $y < $height - 1; $y++) {
            for ($x = 1; $x < $width - 1; $x++) {
                $sumX = 0;
                $sumY = 0;
                for ($ky = -1; $ky <= 1; $ky++) {
                    for ($kx = -1; $kx <= 1; $kx++) {
                        $pixel = $grayMatrix[$y + $ky][$x + $kx];
                        $sumX += $pixel * $gx[$ky + 1][$kx + 1];
                        $sumY += $pixel * $gy[$ky + 1][$kx + 1];
                    }
                }
                $magnitude = sqrt($sumX * $sumX + $sumY * $sumY);
                if ($magnitude > $threshold) {
                    $edgeCount++;
                }
            }
        }

        return [
            'edge_count' => $edgeCount,
            'edge_density' => $totalPixels > 0 ? round($edgeCount / $totalPixels, 4) : 0,
        ];
    }

    /**
     * Generate a histogram of pixel intensities
     * Returns array of 256 bins [0-255]
     */
    public function histogram(string $imagePath): array {
        $grayMatrix = $this->ingestor->toGrayscaleMatrix($imagePath);
        $bins = array_fill(0, 256, 0);
        foreach ($grayMatrix as $row) {
            foreach ($row as $val) {
                $bins[$val]++;
            }
        }
        return $bins;
    }

    /**
     * Resize an image using GD
     */
    public function resizeImage(string $inputPath, string $outputPath, int $newWidth, int $newHeight): bool {
        $info = getimagesize($inputPath);
        if (!$info) return false;

        $src = $this->loadGdImage($inputPath);
        $dst = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $info[0], $info[1]);

        $ext = strtolower(pathinfo($outputPath, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg': case 'jpeg': imagejpeg($dst, $outputPath, 90); break;
            case 'png': imagepng($dst, $outputPath); break;
            default: imagepng($dst, $outputPath); break;
        }

        imagedestroy($src);
        imagedestroy($dst);
        return true;
    }

    /**
     * Apply Grayscale filter and save
     */
    public function grayscale(string $inputPath, string $outputPath): bool {
        $src = $this->loadGdImage($inputPath);
        imagefilter($src, IMG_FILTER_GRAYSCALE);
        imagepng($src, $outputPath);
        imagedestroy($src);
        return true;
    }

    private function loadGdImage(string $path) {
        $info = getimagesize($path);
        if (!$info) throw new \Exception("Cannot identify image format.");

        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg': return imagecreatefromjpeg($path);
            case 'image/png': return imagecreatefrompng($path);
            case 'image/gif': return imagecreatefromgif($path);
            case 'image/bmp': return imagecreatefrombmp($path);
            case 'image/webp': return imagecreatefromwebp($path);
            default: throw new \Exception("Cannot load format: {$mime}");
        }
    }
}
