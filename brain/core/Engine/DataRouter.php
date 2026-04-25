<?php
namespace Core\Engine;

/**
 * HRITIK AI - DATA ROUTER
 * Handles Visual Analysis and Data Inspections.
 */
class DataRouter {
    private $dataAi;

    public function __construct($dataAi) {
        $this->dataAi = $dataAi;
    }

    public function handleFile(?string $datasetFile, ?string $originalName): ?array {
        if (!$datasetFile) return null;

        $ext = strtolower(pathinfo($originalName ?? $datasetFile, PATHINFO_EXTENSION));
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $imageExts)) {
            require_once __DIR__ . '/../Computer Vision/ComputerVisionAssistant.php';
            $cv = new \Core\ComputerVision\ComputerVisionAssistant();
            return [
                'response' => $cv->analyze($datasetFile, $originalName),
                'intent' => 'vision'
            ];
        }

        return [
            'response' => $this->dataAi->inspect($datasetFile),
            'intent' => 'data_analysis'
        ];
    }
}
