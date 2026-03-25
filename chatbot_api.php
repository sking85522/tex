<?php
session_start();
include 'includes/db.php';
require_once 'includes/ai_engine.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action'];

// Initialize AI Engine
$aiEngine = new AIEngine($pdo);


// Initialize SciPHP/NLPHP for Ticket Categorization
function categorizeTicket($question) {
    // We simulate the NLPHP Sentiment/Keyword classifier
    $question = strtolower($question);
    if (strpos($question, 'urgent') !== false || strpos($question, 'asap') !== false || strpos($question, 'down') !== false || strpos($question, 'error') !== false) {
        return 'Urgent';
    }
    if (strpos($question, 'price') !== false || strpos($question, 'buy') !== false || strpos($question, 'quote') !== false) {
        return 'Sales Lead';
    }
    if (strpos($question, 'seo') !== false || strpos($question, 'rank') !== false) {
        return 'Marketing';
    }
    return 'General'; // Default
}

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


        // Neural NLP Parameter Extraction
        $msgLower = strtolower($userMessage);

        // Ensure Neural Engine is active
        require_once 'includes/neural_engine.php';
        $neural = new NeuralEngine();

        // 1. Detect Interest (What they want to build)
        if (strpos($msgLower, 'app') !== false || strpos($msgLower, 'mobile') !== false || strpos($msgLower, 'ios') !== false || strpos($msgLower, 'android') !== false) {
            $neural->updateProfile('interest', 'app');
        } elseif (strpos($msgLower, 'ecommerce') !== false || strpos($msgLower, 'store') !== false || strpos($msgLower, 'shop') !== false) {
            $neural->updateProfile('interest', 'ecommerce');
        } elseif (strpos($msgLower, 'web') !== false || strpos($msgLower, 'site') !== false) {
            $neural->updateProfile('interest', 'web');
        } elseif (strpos($msgLower, 'ai') !== false || strpos($msgLower, 'bot') !== false) {
            $neural->updateProfile('interest', 'ai');
        }

        // 2. Detect Budget Constraint
        if (strpos($msgLower, 'cheap') !== false || strpos($msgLower, 'sasta') !== false || strpos($msgLower, 'low budget') !== false || strpos($msgLower, 'discount') !== false) {
            $neural->updateProfile('budget', 'low');
        } elseif (strpos($msgLower, 'premium') !== false || strpos($msgLower, 'best') !== false || strpos($msgLower, 'enterprise') !== false) {
            $neural->updateProfile('budget', 'high');
        }

        // 3. Detect Urgency (Fast-tracking)
        if (strpos($msgLower, 'urgent') !== false || strpos($msgLower, 'fast') !== false || strpos($msgLower, 'jaldi') !== false || strpos($msgLower, 'asap') !== false) {
            $neural->updateProfile('urgency', 'urgent');
        }

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
            $category = categorizeTicket($question); // NLPHP auto-categorization
            $question = "[$category] " . $question;

            try {
                $stmt = $pdo->prepare("INSERT INTO support_tickets (user_email, question) VALUES (?, ?)");
                $stmt->execute([$email, $question]);
                $response = ['success' => true, 'message' => "Support ticket created successfully under category [$category]. Our team will email you at {$email} shortly!"];

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
