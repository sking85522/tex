<?php

namespace MLPHP\Metrics;

use NumPHP\Core\NDArray;

class RegressionMetrics
{
    public static function mean_squared_error($y_true, $y_pred): float
    {
        $true_data = ($y_true instanceof NDArray) ? $y_true->getData() : $y_true;
        $pred_data = ($y_pred instanceof NDArray) ? $y_pred->getData() : $y_pred;

        $n = count($true_data);
        if ($n !== count($pred_data) || $n == 0) {
            throw new \InvalidArgumentException("y_true and y_pred must have the same length and not be empty.");
        }

        $sum = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $sum += pow($true_data[$i] - $pred_data[$i], 2);
        }

        return $sum / $n;
    }
}
