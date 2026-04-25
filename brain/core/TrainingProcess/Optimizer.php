<?php
namespace Core\TrainingProcess;

abstract class Optimizer {
    protected float $learningRate;

    public function __construct(float $learningRate = 0.01) {
        $this->learningRate = $learningRate;
    }

    /**
     * Update weights based on gradients.
     */
    abstract public function update(array &$weights, array $gradients): void;
}
