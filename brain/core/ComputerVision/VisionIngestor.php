<?php
namespace Core\ComputerVision;

/**
 * VisionIngestor
 * Handles loading and initial pre-processing of image files
 * before passing them to VisionEngine or other CV modules.
 */
class VisionIngestor {
    
    private array $supportedFormats = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

    /**
     * Validate and load an image file path
     */
    public function ingest(string $imagePath): array {
        $info = getimagesize($imagePath);
        if (!$info) {
            return ['status' => 'error', 'message' => 'Could not read image metadata.'];
        }

        $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        
        // Handle PHP temp files (.tmp) by checking MIME type
        if ($ext === 'tmp' || empty($ext)) {
            $mimeMap = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                'image/bmp' => 'bmp'
            ];
            $ext = $mimeMap[$info['mime']] ?? 'jpg';
        }

        if (!in_array($ext, $this->supportedFormats)) {
            return ['status' => 'error', 'message' => "Unsupported format: {$ext}. Supported: " . implode(', ', $this->supportedFormats)];
        }

        return [
            'status' => 'success',
            'path' => $imagePath,
            'width' => $info[0],
            'height' => $info[1],
            'mime' => $info['mime'],
            'extension' => $ext,
            'filesize_kb' => round(filesize($imagePath) / 1024, 2)
        ];
    }

    public function toGrayscaleMatrix(string $imagePath): array {
        if (class_exists('VisionPHP\VisionPHP')) {
            $img = \VisionPHP\VisionPHP::imread($imagePath);
            $gray = \VisionPHP\VisionPHP::cvtColor($img, 'GRAY');
            return $gray->getPixels();
        }
        
        // Fallback to manual GD if module missing
        $info = getimagesize($imagePath);
        if (!$info) throw new \Exception("Cannot read image.");
        $width = $info[0]; $height = $info[1];
        $img = $this->loadGdImage($imagePath);
        $matrix = [];
        for ($y = 0; $y < $height; $y++) {
            $row = [];
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $row[] = intval(0.299 * (($rgb >> 16) & 0xFF) + 0.587 * (($rgb >> 8) & 0xFF) + 0.114 * ($rgb & 0xFF));
            }
            $matrix[] = $row;
        }
        imagedestroy($img);
        return $matrix;
    }

    public function toRGBMatrix(string $imagePath): array {
        if (class_exists('VisionPHP\VisionPHP')) {
            $img = \VisionPHP\VisionPHP::imread($imagePath);
            return $img->getPixels();
        }

        $info = getimagesize($imagePath);
        if (!$info) throw new \Exception("Cannot read image.");
        $width = $info[0]; $height = $info[1];
        $img = $this->loadGdImage($imagePath);
        $matrix = [];
        for ($y = 0; $y < $height; $y++) {
            $row = [];
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $row[] = ['r' => ($rgb >> 16) & 0xFF, 'g' => ($rgb >> 8) & 0xFF, 'b' => $rgb & 0xFF];
            }
            $matrix[] = $row;
        }
        imagedestroy($img);
        return $matrix;
    }

    /**
     * Load GD image resource from file
     */
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
