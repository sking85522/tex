<?php
namespace Core\Evaluation;

class Metrics {
    
    /**
     * Accuracy Score: (TP + TN) / Total
     */
    public static function accuracy(array $yTrue, array $yPred): float {
        $correct = 0;
        foreach ($yTrue as $i => $val) {
            if ($val === ($yPred[$i] ?? null)) $correct++;
        }
        return count($yTrue) > 0 ? $correct / count($yTrue) : 0.0;
    }

    /**
     * Precision Score: TP / (TP + FP)
     */
    public static function precision(array $yTrue, array $yPred, $posLabel = 1): float {
        $tp = 0; $fp = 0;
        foreach ($yPred as $i => $pred) {
            if ($pred === $posLabel) {
                if (($yTrue[$i] ?? null) === $posLabel) $tp++;
                else $fp++;
            }
        }
        return ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0.0;
    }

    /**
     * Recall Score: TP / (TP + FN)
     */
    public static function recall(array $yTrue, array $yPred, $posLabel = 1): float {
        $tp = 0; $fn = 0;
        foreach ($yTrue as $i => $true) {
            if ($true === $posLabel) {
                if (($yPred[$i] ?? null) === $posLabel) $tp++;
                else $fn++;
            }
        }
        return ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0.0;
    }

    /**
     * F1 Score: 2 * (P * R) / (P + R)
     */
    public static function f1Score(array $yTrue, array $yPred, $posLabel = 1): float {
        $p = self::precision($yTrue, $yPred, $posLabel);
        $r = self::recall($yTrue, $yPred, $posLabel);
        return ($p + $r) > 0 ? 2 * ($p * $r) / ($p + $r) : 0.0;
    }
}
