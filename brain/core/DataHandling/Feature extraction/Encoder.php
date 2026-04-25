<?php
namespace Core\DataHandling\FeatureExtraction;

if (file_exists(dirname(__DIR__, 2) . '/modules/datasetphp/autoload.php')) {
    require_once dirname(__DIR__, 2) . '/modules/datasetphp/autoload.php';
}

use DatasetPHP\DatasetPHP;

class Encoder {

    /**
     * Label Encoding: Converts text labels to unique integers
     * Using the LabelEncoder from the DatasetPHP module
     */
    public function labelEncode(array $labels): array {
        if (!class_exists('DatasetPHP\DatasetPHP')) {
            // Fallback: manual label encoding
            $uniqueInts = array_values(array_unique($labels));
            $map = array_flip($uniqueInts);
            return array_map(fn($l) => $map[$l], $labels);
        }

        $encoder = DatasetPHP::LabelEncoder();
        return $encoder->fit_transform($labels);
    }

    /**
     * One-Hot Encoding: Converts labels to a binary matrix
     */
    public function oneHotEncode(array $labels): array {
        if (!class_exists('DatasetPHP\DatasetPHP')) {
             return []; // Stub for now if module missing
        }

        $encoder = DatasetPHP::OneHotEncoder();
        return $encoder->fit_transform($labels);
    }
}
