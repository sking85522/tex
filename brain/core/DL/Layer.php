<?php
namespace Core\DL;

if (file_exists(dirname(__DIR__, 2) . '/modules/numphp/autoload.php')) {
    require_once dirname(__DIR__, 2) . '/modules/numphp/autoload.php';
}

use NumPHP\NumPHP;

class Layer {
    public array $weights;
    public array $biases;
    public array $lastInput;
    public array $lastOutput;
    public string $activation;
    public float $dropoutRate = 0.0;
    public array $dropoutMask = [];

    public function __construct(int $inputSize, int $outputSize, string $activation = 'sigmoid', float $dropoutRate = 0.0) {
        $this->activation = $activation;
        $this->dropoutRate = $dropoutRate;
        
        // Xavier/Glorot Initialization for weights
        $scale = sqrt(2.0 / ($inputSize + $outputSize));
        $this->weights = [];
        for ($i = 0; $i < $outputSize; $i++) {
            for ($j = 0; $j < $inputSize; $j++) {
                $this->weights[$i][$j] = (rand(-100, 100) / 100.0) * $scale;
            }
            $this->biases[$i] = 0.0;
        }
    }

    /**
     * Performs forward pass for a single input vector.
     */
    public function forward(array $input): array {
        $this->lastInput = $input;
        $output = [];

        foreach ($this->weights as $i => $row) {
            $sum = $this->biases[$i];
            foreach ($row as $j => $weight) {
                $sum += $weight * $input[$j];
            }
            
            // Apply activation
            if ($this->activation === 'sigmoid') {
                $output[$i] = Activation::sigmoid($sum);
            } elseif ($this->activation === 'relu') {
                $output[$i] = Activation::relu($sum);
            } elseif ($this->activation === 'softmax') {
                $output[$i] = exp($sum); // Partial softmax: exponents
            } else {
                $output[$i] = $sum;
            }
        }

        // Complete softmax normalization
        if ($this->activation === 'softmax') {
            $sumExp = array_sum($output);
            foreach ($output as $i => $val) {
                $output[$i] = $val / ($sumExp ?: 1);
            }
        }

        // Apply Dropout (Training only simulated)
        if ($this->dropoutRate > 0) {
            foreach ($output as $i => $val) {
                if ((mt_rand(1, 100) / 100) < $this->dropoutRate) {
                    $output[$i] = 0;
                }
            }
        }

        $this->lastOutput = $output;
        return $output;
    }
}
