<?php
namespace NeuralPHP\Losses;

class CrossEntropy {
    /**
     * Categorical cross-entropy loss.
     */
    public function compute(array $predicted, array $target): float {
        $loss = 0.0;
        $epsilon = 1e-15;
        for ($i = 0; $i < count($target); $i++) {
            $p = max($epsilon, min(1 - $epsilon, $predicted[$i]));
            $loss -= $target[$i] * log($p);
        }
        return $loss;
    }

    public function gradient(array $predicted, array $target): array {
        $grad = [];
        $epsilon = 1e-15;
        for ($i = 0; $i < count($target); $i++) {
            $p = max($epsilon, min(1 - $epsilon, $predicted[$i]));
            $grad[] = -$target[$i] / $p;
        }
        return $grad;
    }
}
