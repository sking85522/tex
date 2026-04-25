<?php
namespace Core;

/**
 * HRITIK AI Repair Engine
 * Self-healing core for Tech Elevate X
 */
class AIRepairEngine {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->checkAndFix();
    }

    private function checkAndFix() {
        try {
            // 1. Ensure ai_logs table exists
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS ai_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                action_type VARCHAR(100),
                thought_process TEXT,
                result_data LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

            // 2. Ensure ai_knowledge table exists
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS ai_knowledge (
                id INT AUTO_INCREMENT PRIMARY KEY,
                topic VARCHAR(255),
                learned_content LONGTEXT,
                confidence_score INT DEFAULT 80,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

            // 3. Ensure ai_sync_knowledge table exists
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS ai_sync_knowledge (
                id INT AUTO_INCREMENT PRIMARY KEY,
                original_id INT,
                question TEXT,
                answer TEXT,
                source VARCHAR(50),
                synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

            // 4. Ensure ai_leads table exists
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS ai_leads (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_ip VARCHAR(50),
                detected_language VARCHAR(10),
                interest_topic TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;");

        } catch (\Exception $e) {}
    }
}
