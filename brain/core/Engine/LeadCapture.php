<?php
namespace Core\Engine;

class LeadCapture {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->ensureTableExists();
    }

    private function ensureTableExists() {
        try {
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS ai_leads (
                id INT AUTO_INCREMENT PRIMARY KEY,
                client_email VARCHAR(255) NULL,
                client_phone VARCHAR(50) NULL,
                project_context TEXT NULL,
                captured_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
        } catch (\Exception $e) {
            // Ignore if mock or no permission
        }
    }

    public function extractAndSaveLead(string $input, string $context = ''): ?string {
        $email = null;
        $phone = null;

        // Simple Email Regex
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $input, $matches)) {
            $email = $matches[0];
        }

        // Simple Phone Regex (India/Generic 10+ digits)
        if (preg_match('/(\+91[\-\s]?)?[0-9]{10}/', $input, $matches)) {
            $phone = $matches[0];
        }

        if ($email || $phone) {
            try {
                $stmt = $this->pdo->prepare("INSERT INTO ai_leads (client_email, client_phone, project_context) VALUES (?, ?, ?)");
                $stmt->execute([$email, $phone, $context]);
                return "Thank you! I have securely recorded your contact details. Our senior tech team at Tech Elevate X will contact you shortly to finalize the deal.";
            } catch (\Exception $e) {
                return "I noted your details, but there was an error saving them to the database.";
            }
        }

        return null;
    }
}
