<?php
namespace Core\TrainingProcess;

require_once __DIR__ . '/Optimizers/SGD.php';
require_once __DIR__ . '/Loss calculation/CrossEntropy.php';

class TrainingOrchestrator {
    private array $logs = [];

    /**
     * Executes a training run on a model with specified data.
     */
    public function trainModel($model, array $dataset, int $epochs = 10, float $lr = 0.01): array {
        $optimizer = new Optimizers\SGD($lr);
        $lossFn = new LossCalculation\CrossEntropy();
        
        $this->logs[] = "Starting Training: {$epochs} Epochs, Learning Rate: {$lr}";

        for ($e = 1; $e <= $epochs; $e++) {
            $totalLoss = 0;
            $correct = 0;
            
            foreach ($dataset as $sample) {
                // Forward Pass
                $prediction = $model->forward($sample['input']);
                
                // Calculate Loss
                $loss = $lossFn->calculate($sample['target'], $prediction);
                $totalLoss += $loss;
                
                // Backpropagation (Mocked connection to target weights)
                $gradients = $this->calculateGradients($sample['target'], $prediction);
                
                // Optimizer Step: Update weights
                if (method_exists($model, 'getLayers')) {
                    foreach ($model->getLayers() as $layer) {
                        $optimizer->update($layer->weights, $gradients);
                    }
                }
                
                // Accuracy check
                if ($this->isCorrect($sample['target'], $prediction)) $correct++;
            }
            
            $avgLoss = $totalLoss / count($dataset);
            $accuracy = ($correct / count($dataset)) * 100;
            $this->logs[] = "Epoch {$e}/{$epochs} | Loss: " . round($avgLoss, 4) . " | Acc: " . round($accuracy, 1) . "%";
        }

        return [
            'status' => 'Training Completed',
            'accuracy' => ($correct / count($dataset)) * 100,
            'logs' => $this->logs
        ];
    }

    private function calculateGradients(array $target, array $prediction): array {
        // Simple Gradient calculation (Target - Prediction)
        $grads = [];
        foreach ($target as $i => $t) {
            $grads[] = $t - ($prediction[$i] ?? 0);
        }
        return $grads;
    }

    private function isCorrect(array $target, array $prediction): bool {
        $tIdx = array_search(max($target), $target);
        $pIdx = array_search(max($prediction), $prediction);
        return $tIdx === $pIdx;
    }
}
