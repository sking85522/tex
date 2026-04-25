<?php
namespace Core\ML;

require_once __DIR__ . '/NeuralDatabase.php';
use Core\ML\NeuralDatabase;

class SupervisedLearner {
    private NeuralDatabase $db;
    private array $coreKnowledge = [];
    private string $jsonPath;
    private string $massivePath;

    public function __construct() {
        $this->db = new NeuralDatabase();
        $this->jsonPath = __DIR__ . '/../../storage/training/core_knowledge.json';
        $this->massivePath = __DIR__ . '/../../storage/training/massive_knowledge.json';
        $this->loadCore();
    }

    private function loadCore() {
        if (file_exists($this->jsonPath)) {
            $this->coreKnowledge = json_decode(file_get_contents($this->jsonPath), true) ?: [];
        }
    }

    public function getTaughtAnswer(string $prompt): ?string {
        $prompt = strtolower(trim($prompt));
        if (empty($prompt)) return null;

        // 1. Check Core Knowledge
        foreach ($this->coreKnowledge as $entry) {
            if (strtolower(trim($entry['question'] ?? '')) === $prompt) return $entry['answer'];
        }

        // 2. Check Massive Knowledge (DISABLED LINEAR SCAN FOR PERFORMANCE)
        /*
        if (file_exists($this->massivePath)) {
            $handle = fopen($this->massivePath, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $data = json_decode($line, true);
                    if (!$data) continue;
                    $storedQ = strtolower(trim($data['q'] ?? ''));
                    if ($storedQ === $prompt || str_contains($storedQ, $prompt) || str_contains($prompt, $storedQ)) {
                        fclose($handle);
                        return $data['a'];
                    }
                }
                fclose($handle);
            }
        }
        */

        return $this->db->findAnswer($prompt);
    }

    public function teach(string $prompt, string $answer, string $source = 'autonomous_learning'): void {
        $prompt = trim($prompt);
        if (empty($prompt)) return;
        
        if ($source === 'massive_dataset') {
            $entry = ['q' => $prompt, 'a' => $answer, 'ts' => time()];
            file_put_contents($this->massivePath, json_encode($entry) . PHP_EOL, FILE_APPEND);
            return;
        }

        $this->coreKnowledge[] = ['question' => $prompt, 'answer' => $answer];
        file_put_contents($this->jsonPath, json_encode($this->coreKnowledge, JSON_PRETTY_PRINT));
    }
}
