<?php
namespace NeuralPHP\Optimizers;

/**
 * Adam Optimizer — Adaptive Moment Estimation.
 * Combines RMSprop + Momentum for faster convergence.
 */
class Adam {
    private $lr;
    private $beta1;
    private $beta2;
    private $epsilon;
    private $m = []; // First moment (mean)
    private $v = []; // Second moment (variance)
    private $t = 0;

    public function __construct(float $lr = 0.001, float $beta1 = 0.9, float $beta2 = 0.999, float $epsilon = 1e-8) {
        $this->lr = $lr;
        $this->beta1 = $beta1;
        $this->beta2 = $beta2;
        $this->epsilon = $epsilon;
    }

    public function update(string $paramKey, array &$params, array $gradients): void {
        $this->t++;

        if (!isset($this->m[$paramKey])) {
            $this->m[$paramKey] = array_fill(0, count($params), 0.0);
            $this->v[$paramKey] = array_fill(0, count($params), 0.0);
        }

        for ($i = 0; $i < count($params); $i++) {
            // Update biased first moment
            $this->m[$paramKey][$i] = $this->beta1 * $this->m[$paramKey][$i] + (1 - $this->beta1) * $gradients[$i];
            // Update biased second moment
            $this->v[$paramKey][$i] = $this->beta2 * $this->v[$paramKey][$i] + (1 - $this->beta2) * ($gradients[$i] ** 2);

            // Bias correction
            $mHat = $this->m[$paramKey][$i] / (1 - $this->beta1 ** $this->t);
            $vHat = $this->v[$paramKey][$i] / (1 - $this->beta2 ** $this->t);

            // Update params
            $params[$i] -= $this->lr * $mHat / (sqrt($vHat) + $this->epsilon);
        }
    }

    public function getLearningRate(): float { return $this->lr; }
}
