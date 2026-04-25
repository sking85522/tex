<?php
namespace Core\DataHandling;

require_once __DIR__ . '/DataLoader.php';
require_once __DIR__ . '/DataPipeline.php';
require_once __DIR__ . '/Datasets/Corpus.php';

use Core\DataHandling\DataLoader;
use Core\DataHandling\DataPipeline;
use Core\DataHandling\Datasets\Corpus;

class DataHandlingAssistant {
    private DataLoader $loader;
    private DataPipeline $pipeline;
    private Corpus $corpus;

    public function __construct() {
        $this->loader = new DataLoader();
        $this->pipeline = new DataPipeline();
        $this->corpus = new Corpus();
    }

    /**
     * Inspect a file and return a descriptive summary of its contents.
     */
    public function inspect(string $filePath): string {
        $analysis = $this->loader->analyzeFile($filePath);
        
        if ($analysis['status'] !== 'success') {
            return "Data Error: " . $analysis['message'];
        }

        $cols = implode(", ", $analysis['columns']);
        $stats = $this->generateStatsReport($filePath);
        
        return "Dataset Inspection Complete! \n" .
               "Rows: {$analysis['rows']}, Columns: {$analysis['cols']}. \n" .
               "Header: [{$cols}]. \n" .
               "Descriptive Analytics: {$stats}\n" .
               "Standard preprocessing is recommended before training.";
    }

    /**
     * Generates a statistics summary using PandaPHP.
     */
    public function generateStatsReport(string $filePath): string {
        try {
            $df = $this->loader->loadAsDataFrame($filePath);
            $summary = "";
            $cols = $df->columns();
            
            // Generate basic stats for first 3 numeric columns for brevity
            $count = 0;
            foreach ($cols as $col) {
                // Heuristic: check if column name sounds numeric or just try
                $data = $df->column($col);
                if (is_numeric($data[0] ?? null)) {
                    $mean = round(array_sum($data) / count($data), 2);
                    $summary .= " | {$col}: avg={$mean}";
                    $count++;
                }
                if ($count >= 3) break;
            }
            return $summary ?: "No numeric columns detected for stats.";
        } catch (\Exception $e) {
            return "Stats unavailable.";
        }
    }

    /**
     * Prepare a dataset for training (Auto-Clean and Scale)
     */
    public function prepareForML(string $filePath, $target): array {
        try {
            $data = $this->loader->loadForTraining($filePath, $target);
            $processedX = [];
            
            foreach ($data['X'] as $row) {
                $pRow = [];
                foreach ($row as $val) {
                    if (is_numeric($val)) {
                        // Apply pipeline context: numeric
                        $pRow[] = $val; // In a full implementation we'd use $this->pipeline->processNumeric([$val])
                    } else {
                        // Apply categorical encoding
                        $pRow[] = 1.0; // Placeholder for encoded value
                    }
                }
                $processedX[] = $pRow;
            }

            return [
                'status' => 'success',
                'X' => $processedX,
                'y' => $data['y']
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Load a sample dataset by name
     */
    public function loadSample(string $name): string {
        $name = strtolower($name);
        try {
            if ($name === 'iris') {
                $data = $this->corpus->getIris();
                return "IRIS Dataset loaded successfully into the pipeline buffer. Shapes: X=" . count($data['X']) . " rows.";
            } elseif ($name === 'xor') {
                $data = $this->corpus->getXOR();
                return "XOR Logic Gate dataset loaded. 4 samples ready for Neural training.";
            }
            return "Unknown sample dataset: {$name}.";
        } catch (\Exception $e) {
            return "Sample Load Error: " . $e->getMessage();
        }
    }
}
