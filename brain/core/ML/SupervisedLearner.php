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


    public function getSemanticAnswer(string $prompt): ?string {
        $cleanPrompt = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower(trim($prompt)));
        if (empty($cleanPrompt)) return null;

        $tokens = explode(' ', $cleanPrompt);
        $importantTokens = array_filter($tokens, function($word) {
            return strlen($word) > 3 && !in_array($word, ['what', 'when', 'where', 'how', 'who', 'this', 'that', 'with']);
        });

        if (empty($importantTokens)) return null;

        try {
            global $pdo;
            if (isset($pdo)) {
                // Build semantic search: We match ANY of the keywords and rank by how many matched.
                $conditions = [];
                $params = [];
                foreach ($importantTokens as $token) {
                    $conditions[] = "topic LIKE ? OR learned_content LIKE ?";
                    $params[] = '%' . $token . '%';
                    $params[] = '%' . $token . '%';
                }

                $sql = "SELECT topic, learned_content, confidence_score FROM ai_knowledge WHERE " . implode(' OR ', $conditions) . " LIMIT 5";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                if ($results) {
                    // Combine multiple fragments into a cohesive answer
                    $finalAnswer = "";
                    $contextGained = false;
                    foreach ($results as $row) {
                        // Skip noisy short answers if we already have something
                        if ($contextGained && strlen($row['learned_content']) < 20) continue;

                        // Avoid duplicates
                        if (!str_contains($finalAnswer, substr($row['learned_content'], 0, 30))) {
                            $finalAnswer .= ucfirst(trim($row['learned_content'])) . ". ";
                            $contextGained = true;
                        }
                    }

                    if ($finalAnswer) {
                        // Clean up multiple dots
                        $finalAnswer = preg_replace('/\.(\s*\.)+/', '.', $finalAnswer);
                        return trim($finalAnswer);
                    }
                }
            }
        } catch (\Exception $e) {}

        return null;
    }

}