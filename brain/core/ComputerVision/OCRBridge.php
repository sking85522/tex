<?php
namespace Core\ComputerVision;

/**
 * OCRBridge
 * Detects text regions and potential characters in images using pixel scanning.
 * This is a lightweight substitute in the absence of binary OCR engines like Tesseract.
 */
class OCRBridge {

    /**
     * Attempts to detect text areas in a grayscale matrix.
     * Looks for high-frequency horizontal changes (text alignment).
     */
    public function detectTextRegions(array $grayMatrix): array {
        $height = count($grayMatrix);
        $width = count($grayMatrix[0]);
        $textProbability = 0;
        $potentialLines = 0;

        // Scan every 5th row for horizontal variance
        for ($y = 0; $y < $height; $y += 5) {
            $row = $grayMatrix[$y];
            $variance = 0;
            $mean = array_sum($row) / $width;
            
            foreach ($row as $val) {
                $variance += abs($val - $mean);
            }
            $variance /= $width;

            // Text usually has high localized variance in grayscale
            if ($variance > 40) {
                $potentialLines++;
            }
        }

        $lineDensity = $potentialLines / ($height / 5);
        
        return [
            'has_text_potential' => $lineDensity > 0.2,
            'text_confidence' => round($lineDensity, 2),
            'detected_lines_estimate' => $potentialLines
        ];
    }
}
