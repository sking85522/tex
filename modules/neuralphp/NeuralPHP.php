<?php

namespace NeuralPHP;

use NeuralPHP\Models\Sequential;
use NeuralPHP\Layers\Dense;
use NeuralPHP\Activations\Sigmoid;
use NeuralPHP\Activations\ReLU;
use NeuralPHP\Losses\MSE;
use NeuralPHP\Optimizers\SGD;

class NeuralPHP
{
    /**
     * Create a Sequential Neural Network Model.
     */
    public static function Sequential(): Sequential
    {
        return new Sequential();
    }

    /**
     * Create a Fully Connected (Dense) Layer.
     */
    public static function Dense(int $input_size, int $output_size, $activation = null): Dense
    {
        return new Dense($input_size, $output_size, $activation);
    }

    // Helper to get activation instances
    public static function getActivation(string $name)
    {
        switch (strtolower($name)) {
            case 'sigmoid': return new Sigmoid();
            case 'relu': return new ReLU();
            default: throw new \Exception("Activation $name not found.");
        }
    }

    // Helper to get Loss instances
    public static function getLoss(string $name)
    {
        switch (strtolower($name)) {
            case 'mse': return new MSE();
            default: throw new \Exception("Loss $name not found.");
        }
    }

    // Helper to get Optimizer instances
    public static function getOptimizer(string $name, float $lr = 0.01)
    {
        switch (strtolower($name)) {
            case 'sgd': return new SGD($lr);
            default: throw new \Exception("Optimizer $name not found.");
        }
    }
}
