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

        // Query Database directly using NeuralDatabase connection if available
        $dbAnswer = $this->db->findAnswer($prompt);
        if ($dbAnswer) {
            return $dbAnswer;
        }

        // Try direct PDO connection as fallback
        try {
            global $pdo;
            if (isset($pdo)) {
                $stmt = $pdo->prepare("SELECT learned_content FROM ai_knowledge WHERE topic LIKE ? OR ? LIKE CONCAT('%', topic, '%') ORDER BY confidence_score DESC LIMIT 1");
                $searchPrompt = '%' . $prompt . '%';
                $stmt->execute([$searchPrompt, $prompt]);
                $result = $stmt->fetchColumn();
                if ($result) {
                    return $result;
                }
            }
        } catch (\Exception $e) {}

        return null;
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
