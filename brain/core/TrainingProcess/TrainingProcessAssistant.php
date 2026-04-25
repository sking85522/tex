<?php
namespace Core\TrainingProcess;

require_once __DIR__ . '/TrainingOrchestrator.php';

class TrainingProcessAssistant {
    private TrainingOrchestrator $orchestrator;

    public function __construct() {
        $this->orchestrator = new TrainingOrchestrator();
    }

    /**
     * Start an automated training run.
     */
    public function startTraining(string $datasetPath, $model): array {
        if (!file_exists($datasetPath)) {
            return ['error' => 'Dataset not found at ' . $datasetPath];
        }

        $dataset = json_decode(file_get_contents($datasetPath), true);
        
        // Transform standard benchmark to training pairs if needed
        $trainingSet = $this->prepareData($dataset);

        return $this->orchestrator->trainModel($model, $trainingSet, 20, 0.05);
    }

    private function prepareData(array $data): array {
        // Custom logic to convert JSON benchmarks to Tensors/Arrays
        return $data['benchmarks'] ?? [];
    }
    
    public function getStatus(): string {
        return "Training Engine Online: Advanced Backpropagation and Optimizers ready.";
    }
}
