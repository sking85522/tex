<?php
namespace Core\Matrix;

// Load NumPHP if not loaded
if (file_exists(dirname(__DIR__, 2) . '/modules/numphp/NumPHP.php')) {
    require_once dirname(__DIR__, 2) . '/modules/numphp/NumPHP.php';
}

use NumPHP\NumPHP;
use NumPHP\Core\NDArray;

class MatrixOps {
    
    /**
     * Create an NDArray
     */
    public static function create(array $data) {
        return NumPHP::array($data);
    }

    /**
     * Matrix Dot Product / Multiplication
     */
    public static function dot(NDArray $a, NDArray $b): NDArray {
        return NumPHP::matmul($a, $b);
    }

    /**
     * Matrix Transpose
     */
    public static function transpose(NDArray $a): NDArray {
        return NumPHP::transpose($a);
    }

    /**
     * Matrix Inverse
     */
    public static function inverse(NDArray $a): NDArray {
        return NumPHP::inv($a);
    }

    /**
     * Add arrays element-wise
     */
    public static function add(NDArray $a, NDArray $b): NDArray {
        return NumPHP::add($a, $b);
    }
}
