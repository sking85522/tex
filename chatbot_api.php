<?php
session_start();
include 'includes/db.php';
require_once 'includes/ai_engine.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action'];

// Initialize AI Engine
$aiEngine = new AIEngine($pdo);

if ($action === 'chat') {
    $userMessage = trim($_POST['message'] ?? '');
    $userIP = $_SERVER['REMOTE_ADDR'];
    $detectedLang = 'en';

    if (!empty($userMessage)) {
        try {

        // Track Context in Session
        if (!isset($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
        }
        // Keep only last 5 interactions to prevent token bloat
        if (count($_SESSION['chat_history']) > 5) {
            array_shift($_SESSION['chat_history']);
        }
        $_SESSION['chat_history'][] = "Client: " . $userMessage;

        // Track lead interest & behavior silently

            $stmt = $pdo->prepare("INSERT INTO ai_leads (user_ip, detected_language, interest_topic) VALUES (?, ?, ?)");
            $stmt->execute([$userIP, $detectedLang, substr($userMessage, 0, 50)]);
        } catch(Exception $e) {}

        // Get Smart Response from Engine (Combines Local DB, Self-Learning, and API)
        $aiReply = $aiEngine->getChatbotResponse($userMessage, $detectedLang);


        if ($aiReply) {
            $_SESSION['chat_history'][] = "Alex (Sales): " . $aiReply;

            // Check if user shows strong buying intent for auto-deal closing
 for auto-deal closing
            $isBuyingIntent = (strpos(strtolower($userMessage), 'buy') !== false ||
                               strpos(strtolower($userMessage), 'price') !== false ||
                               strpos(strtolower($userMessage), 'cost') !== false ||
                               strpos(strtolower($userMessage), 'start') !== false ||
                               strpos(strtolower($userMessage), 'hire') !== false);

            if ($isBuyingIntent) {
                $dealMsg = $aiReply . "<br><br><b>🎯 Special Deal Detected!</b> Because you are interested today, I can offer you a <b>10% instant discount</b> and 3 months of free maintenance if you start your project now. <br><a href='contact.php?offer=10percent' style='color:#1cc88a; font-weight:bold; text-decoration:underline;'>Click here to claim your discount and start!</a>";
                $response = ['success' => true, 'message' => $dealMsg];

                // Mark lead as "Converted" to a hot lead
                try {
                    $stmt = $pdo->prepare("UPDATE ai_leads SET converted = 1 WHERE user_ip = ? ORDER BY id DESC LIMIT 1");
                    $stmt->execute([$userIP]);
                } catch(Exception $e) {}
            } else {
                $response = ['success' => true, 'message' => $aiReply];
            }
        } else {
            // No match found anywhere. Prompt to create a support ticket.
            $response = [
                'success' => true,
                'message' => 'I am still learning and do not understand that yet. Would you like me to create a support ticket for a human to answer? Reply with "ticket: your email" (e.g., ticket: name@email.com).',
                'action_required' => 'create_ticket',
                'pending_question' => $userMessage
            ];
            $_SESSION['pending_ticket_question'] = $userMessage;
        }
    }
} elseif ($action === 'ticket') {
    $userMessage = trim($_POST['message'] ?? '');
    if (strpos(strtolower($userMessage), 'ticket:') === 0) {
        $email = trim(substr($userMessage, 7));
        $question = $_SESSION['pending_ticket_question'] ?? 'Unknown question';

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO support_tickets (user_email, question) VALUES (?, ?)");
                $stmt->execute([$email, $question]);
                $response = ['success' => true, 'message' => "Support ticket created successfully. Our team will email you at {$email} shortly!"];
                unset($_SESSION['pending_ticket_question']);
            } catch(PDOException $e) {
                $response['message'] = 'Failed to create ticket.';
            }
        } else {
            $response['message'] = 'Invalid email format. Please reply like "ticket: name@email.com".';
        }
    }
}

echo json_encode($response);
