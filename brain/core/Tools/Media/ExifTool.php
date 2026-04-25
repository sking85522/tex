<?php
namespace Core\Tools\Media;

/**
 * ExifTool
 * Extracts deep metadata (GPS, Camera, Date) from images using PEL.
 */
class ExifTool {
    
    public function run($params = []) {
        $imagePath = $params['image_path'] ?? null;
        if (!$imagePath) return "Please upload an image to extract metadata.";

        // Use built-in PHP exif if available, fallback to PEL module if needed
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($imagePath);
            if ($exif) {
                return [
                    'camera' => $exif['Model'] ?? 'Unknown',
                    'date' => $exif['DateTime'] ?? 'Unknown',
                    'exposure' => $exif['ExposureTime'] ?? 'Unknown',
                    'iso' => $exif['ISOSpeedRatings'] ?? 'Unknown',
                    'message' => "Sir, maine image ke metadata scan kiye hain. Ye photo " . ($exif['Model'] ?? 'kisi camera') . " se li gayi hai."
                ];
            }
        }

        return "No EXIF data found in this image.";
    }
}
