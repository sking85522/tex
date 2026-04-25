<?php
namespace Core\ML;

class NeuralDatabase {
    private \PDO $db;

    public function __construct() {
        $dbPath = __DIR__ . '/../../storage/training/neural_knowledge.db';
        $this->db = new \PDO("sqlite:$dbPath");
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->initialize();
    }

    private function initialize() {
        $this->db->exec("PRAGMA journal_mode = WAL"); // High performance, low locking
        $this->db->exec("PRAGMA busy_timeout = 5000"); // Wait up to 5s for lock
        $this->db->exec("CREATE TABLE IF NOT EXISTS knowledge (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT,
            answer TEXT,
            source TEXT,
            timestamp INTEGER
        )");
        $this->db->exec("CREATE INDEX IF NOT EXISTS idx_question ON knowledge(question)");
    }

    public function saveFact(string $q, string $a, string $source = 'massive_dataset'): bool {
        $stmt = $this->db->prepare("INSERT INTO knowledge (question, answer, source, timestamp) VALUES (?, ?, ?, ?)");
        return $stmt->execute([strtolower(trim($q)), $a, $source, time()]);
    }

    public function findAnswer(string $prompt): ?string {
        $prompt = strtolower(trim($prompt));
        
        // 1. Try Exact Match (Ultra Fast using Index)
        $stmt = $this->db->prepare("SELECT answer FROM knowledge WHERE question = ? LIMIT 1");
        $stmt->execute([$prompt]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result) return $result['answer'];

        // 2. Try Prefix Match (Faster than full LIKE)
        $stmt = $this->db->prepare("SELECT answer FROM knowledge WHERE question LIKE ? LIMIT 1");
        $stmt->execute([$prompt . "%"]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result) return $result['answer'];

        return null; // No good match found
    }

    public function getCount(): int {
        return $this->db->query("SELECT COUNT(*) FROM knowledge")->fetchColumn();
    }
}
