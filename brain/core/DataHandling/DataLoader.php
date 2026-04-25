<?php
namespace Core\DataHandling;

// Include Modules
if (file_exists(dirname(__DIR__, 2) . '/modules/pandaphp/autoload.php')) {
    require_once dirname(__DIR__, 2) . '/modules/pandaphp/autoload.php';
}
if (file_exists(dirname(__DIR__, 2) . '/modules/datasetphp/autoload.php')) {
    require_once dirname(__DIR__, 2) . '/modules/datasetphp/autoload.php';
}

use PandaPHP\PandaPHP;
use DatasetPHP\DatasetPHP;

class DataLoader {

    private bool $pandaLoaded = false;
    private bool $datasetLoaded = false;

    public function __construct() {
        $this->pandaLoaded = class_exists('PandaPHP\PandaPHP');
        $this->datasetLoaded = class_exists('DatasetPHP\DatasetPHP');
    }

    /**
     * Load CSV and return as a raw array X and y.
     */
    public function loadForTraining(string $filepath, $targetColumn): array {
        if (!$this->datasetLoaded) throw new \Exception("DatasetPHP module not installed.");
        
        return DatasetPHP::load_csv($filepath, $targetColumn);
    }

    /**
     * Load CSV as a PandaPHP DataFrame for manipulation.
     */
    public function loadAsDataFrame(string $filepath) {
        if (!$this->pandaLoaded) throw new \Exception("PandaPHP module not installed.");
        
        return PandaPHP::read_csv($filepath);
    }

    /**
     * Analyze an uploaded file for UI summary.
     */
    public function analyzeFile(string $tmpFilePath): array {
        $ext = strtolower(pathinfo($tmpFilePath, PATHINFO_EXTENSION));

        if (!$this->pandaLoaded) {
            return ['status' => 'error', 'message' => 'PandaPHP missing.'];
        }

        try {
            $df = PandaPHP::read_csv($tmpFilePath);
            $shape = $df->shape();
            return [
                'status' => 'success',
                'columns' => $df->columns(),
                'rows' => $shape[0],
                'cols' => $shape[1],
                'memory_kb' => round(filesize($tmpFilePath) / 1024, 2)
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
