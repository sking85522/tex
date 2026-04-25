<?php
namespace Core\TrainingProcess\Optimizers;

require_once dirname(__DIR__) . '/Optimizer.php';
use Core\TrainingProcess\Optimizer;

class SGD extends Optimizer {
    /**
     * Simple Stochastic Gradient Descent: w = w - learningRate * gradient
     */
    public function update(array &$weights, array $gradients): void {
        foreach ($weights as $i => &$row) {
            if (is_array($row)) {
                foreach ($row as $j => &$weight) {
                    $weight -= $this->learningRate * ($gradients[$i][$j] ?? 0);
                }
            } else {
                $row -= $this->learningRate * ($gradients[$i] ?? 0);
            }
        }
    }
}
