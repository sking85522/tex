<?php
namespace Core\TrainingProcess\LossCalculation;

class CrossEntropy {
    /**
     * Calculates categorical cross-entropy loss.
     */
    public function calculate(array $yTrue, array $yPred): float {
        $loss = 0.0;
        foreach ($yTrue as $i => $val) {
            // Log with epsilon to avoid division by zero
            $loss -= $val * log($yPred[$i] + 1e-15);
        }
        return $loss / max(1, count($yTrue));
    }
}
