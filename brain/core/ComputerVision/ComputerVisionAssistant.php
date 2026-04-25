<?php
namespace Core\ComputerVision;

require_once __DIR__ . '/VisionEngine.php';
require_once __DIR__ . '/VisionIngestor.php';
require_once __DIR__ . '/OCRBridge.php';

/**
 * ComputerVisionAssistant
 * Top-level orchestrator that the Engine calls.
 * Takes an image file path, runs the full CV pipeline,
 * and returns a human-readable summary string.
 */
class ComputerVisionAssistant {
    
    private $visionEngine;
    private $ocr;

    public function __construct() {
        $this->visionEngine = new VisionEngine();
        $this->ocr = new OCRBridge();
    }

    /**
     * Analyze an uploaded image and return a natural language summary
     */
    public function analyze(string $imagePath): string {
        $result = $this->visionEngine->processImage($imagePath);

        if ($result['status'] !== 'success') {
            return "Vision Analysis Error: " . ($result['message'] ?? 'Unknown error');
        }

        $meta = $result['metadata'];
        $analysis = $result['analysis'];
        
        // Step 2: Grayscale text detection
        $grayMatrix = (new VisionIngestor())->toGrayscaleMatrix($imagePath);
        $ocrData = $this->ocr->detectTextRegions($grayMatrix);

        // Synthesis Logic (Hinglish)
        $intro = "Sir, maine image ko scan kiya hai. ";
        $dim = "Ye ek {$meta['width']}x{$meta['height']} pixel ki image hai. ";
        
        // Analyze Detail/Complexity
        $detail = "";
        if ($analysis['edge_density'] > 0.25) {
            $detail = "Isme kafi detail aur complex structures hain. ";
        } else {
            $detail = "Ye kafi simple aur smooth visual lag raha hai. ";
        }

        // Analyze Lighting/Mood
        $mood = "";
        if ($analysis['dominant_zone'] === 'bright') {
            $mood = "Image kafi bright aur radiant hai. ";
        } elseif ($analysis['dominant_zone'] === 'dark') {
            $mood = "Image kafi dark aur mysterious vibes de rahi hai. ";
        }

        // OCR Result
        $ocrResult = "";
        if ($ocrData['has_text_potential']) {
            $ocrResult = "Mujhe isme text patterns mile hain (Confidence: " . ($ocrData['text_confidence'] * 100) . "%). Lagta hai isme kuch likha hai.";
        } else {
            $ocrResult = "Isme koi clear text nahi dikh raha hai.";
        }

        return $intro . $dim . $detail . $mood . $ocrResult;
    }

    /**
     * Quick metadata-only analysis (no pixel processing)
     */
    public function quickInfo(string $imagePath): string {
        $ingestor = new VisionIngestor();
        $meta = $ingestor->ingest($imagePath);

        if ($meta['status'] !== 'success') {
            return "Error: " . ($meta['message'] ?? 'Unknown');
        }

        return "Image loaded: {$meta['width']}x{$meta['height']}px, " .
               "Format: {$meta['extension']}, Size: {$meta['filesize_kb']}KB.";
    }
}
