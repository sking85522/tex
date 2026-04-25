<?php
namespace Core;

/**
 * Tech Elevate X - Core AI Engine (HRITIK)
 * 100% INDEPENDENT Framework. No external APIs used.
 */
class AIEngine {
    private $pdo;
    private ?\Core\Engine $brain = null;
    private $dataSync;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        
        if (class_exists('Core\Engine')) {
            $this->brain = new \Core\Engine();
            $this->dataSync = new \Core\Engine\DataSyncManager($this->pdo);
        }

        if (!isset($_SESSION['ai_seeded'])) {
            $this->seedKnowledge();
            $_SESSION['ai_seeded'] = true;
        }
    }

    private function seedKnowledge() {
        try {
            if ($this->pdo->query("SELECT COUNT(*) FROM ai_knowledge")->fetchColumn() == 0) {
                $seedFile = dirname(__DIR__) . '/models/it_seed_data.json';
                if (file_exists($seedFile)) {
                    $data = json_decode(file_get_contents($seedFile), true);
                    foreach ($data as $item) {
                        $stmt = $this->pdo->prepare("INSERT INTO ai_knowledge (topic, learned_content, confidence_score) VALUES (?, ?, ?)");
                        $stmt->execute([$item['topic'], $item['learned_content'], $item['confidence_score']]);
                    }
                }
            }
        } catch (\Exception $e) {}
    }

    private function logThought($action, $thought, $data = null) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO ai_logs (action_type, thought_process, result_data) VALUES (?, ?, ?)");
            $stmt->execute([$action, $thought, json_encode($data)]);
        } catch (\Exception $e) {}
    }

    public function getChatbotResponse($userMessage, $detectedLang = 'en') {
        $this->logThought('HritikLocalProcess', "Processing: $userMessage");
        $lowerMsg = strtolower(trim($userMessage));

        if ($lowerMsg === 'sync data' || $lowerMsg === 'database sync') {
            $res = $this->dataSync->syncNeuralKnowledge();
            return "Sir, data synchronization complete. Status: {$res['status']}. Records synced: " . ($res['records_synced'] ?? 0);
        }

        if (isset($this->brain)) {
            try {
                if (str_contains($lowerMsg, 'price') || str_contains($lowerMsg, 'cost')) {
                    $pricing = new \Core\Tools\Finance\PricingTool();
                    return "Sir, is project ki estimated pricing ₹" . $pricing->calculate($userMessage) . " se shuru hogi. Ye HRITIK logic par based hai.";
                }

                $result = $this->brain->processPrompt($userMessage);
                
                if (($result['intent'] ?? '') === 'autonomous_learning') {
                    $this->learnAndSave($userMessage, $result['response']);
                }

                return $result['response'] ?? "I am still learning.";
            } catch (\Exception $e) {
                $this->logThought('Error', "Brain exception: " . $e->getMessage());
            }
        }

        return "Main abhi seekh raha hoon. Main ek independent AI hoon.";
    }

    private function learnAndSave($topic, $content) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO ai_knowledge (topic, learned_content, confidence_score) VALUES (?, ?, ?)");
            $stmt->execute([substr($topic, 0, 100), $content, 85]);
        } catch (\Exception $e) {}
    }
}
