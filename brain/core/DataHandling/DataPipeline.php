<?php
namespace Core\DataHandling;

require_once __DIR__ . '/Preprocessing/Scaler.php';
require_once __DIR__ . '/Preprocessing/Imputer.php';
require_once __DIR__ . '/Feature extraction/Encoder.php';

use Core\DataHandling\Preprocessing\Scaler;
use Core\DataHandling\Preprocessing\Imputer;
use Core\DataHandling\FeatureExtraction\Encoder;

class DataPipeline {
    private Scaler $scaler;
    private Imputer $imputer;
    private Encoder $encoder;

    public function __construct() {
        $this->scaler = new Scaler();
        $this->imputer = new Imputer();
        $this->encoder = new Encoder();
    }

    /**
     * Executes a full pipeline on a numeric column.
     * Steps: Impute -> Scale
     */
    public function processNumeric(array $data, string $scaleType = 'minmax'): array {
        $data = $this->imputer->impute($data, 'mean');
        
        if ($scaleType === 'minmax') {
            return $this->scaler->minMaxScale($data);
        } else {
            return $this->scaler->standardScale($data);
        }
    }

    /**
     * Executes a feature extraction pipeline on a categorical column.
     */
    public function processCategorical(array $data): array {
        return $this->encoder->labelEncode($data);
    }

    /**
     * Automatically transforms a column based on its data type detection.
     */
    public function autoTransform(array $data): array {
        if (empty($data)) return [];
        
        $sample = $data[0] ?? null;
        if (is_numeric($sample)) {
            return $this->processNumeric($data);
        } else {
            return $this->processCategorical($data);
        }
    }
}
