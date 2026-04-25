<?php
namespace NeuralPHP\Optimizers;

class RMSprop {
    private $lr;
    private $decay;
    private $epsilon;
    private $cache = [];

    public function __construct(float $lr = 0.001, float $decay = 0.9, float $epsilon = 1e-8) {
        $this->lr = $lr;
        $this->decay = $decay;
        $this->epsilon = $epsilon;
    }

    public function update(string $paramKey, array &$params, array $gradients): void {
        if (!isset($this->cache[$paramKey])) {
            $this->cache[$paramKey] = array_fill(0, count($params), 0.0);
        }

        for ($i = 0; $i < count($params); $i++) {
            $this->cache[$paramKey][$i] = $this->decay * $this->cache[$paramKey][$i] + (1 - $this->decay) * ($gradients[$i] ** 2);
            $params[$i] -= $this->lr * $gradients[$i] / (sqrt($this->cache[$paramKey][$i]) + $this->epsilon);
        }
    }
}
