<?php
namespace Core\DataHandling\Datasets;

if (file_exists(dirname(__DIR__, 2) . '/modules/datasetphp/autoload.php')) {
    require_once dirname(__DIR__, 2) . '/modules/datasetphp/autoload.php';
}

use DatasetPHP\DatasetPHP;

class Corpus {

    /**
     * Loads the IRIS dataset using DatasetPHP
     */
    public function getIris(): array {
        if (!class_exists('DatasetPHP\DatasetPHP')) {
            throw new \Exception("DatasetPHP module missing.");
        }
        return DatasetPHP::load_iris();
    }

    /**
     * Loads the XOR dataset
     */
    public function getXOR(): array {
        if (!class_exists('DatasetPHP\DatasetPHP')) {
            throw new \Exception("DatasetPHP module missing.");
        }
        return DatasetPHP::load_xor();
    }

    /**
     * Loads linear regression sample data
     */
    public function getLinearData(): array {
         if (!class_exists('DatasetPHP\DatasetPHP')) {
            throw new \Exception("DatasetPHP module missing.");
        }
        return DatasetPHP::load_linear();
    }
}
