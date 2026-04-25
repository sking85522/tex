<?php
include 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start(); 
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action'];

// Initialize AI Engine from Brain Core
$aiEngine = null;
if (class_exists('Core\AIEngine')) {
    $aiEngine = new \Core\AIEngine($pdo);
}

// Ensure chat session for live chat
function ensureChatSession($pdo) {
    if (!isset($_SESSION['chat_token'])) {
        $_SESSION['chat_token'] = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("INSERT INTO chat_sessions (session_token, user_name) VALUES (?, ?)");
        $stmt->execute([$_SESSION['chat_token'], 'Guest']);
        $_SESSION['chat_session_id'] = $pdo->lastInsertId();
    }
    return $_SESSION['chat_session_id'];
}

try {
    switch ($action) {
        case 'chat': // AI Chatbot Logic
            $userMessage = trim($_POST['message'] ?? '');
            $userIP = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            
            if (!empty($userMessage) || isset($_FILES['image'])) {
                // Vision processing...
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/assets/uploads/ai_vision/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $newName = time() . '_' . basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName)) {
                        $userMessage .= " [Analyzed Sketch: $newName]";
                    }
                }

                // AI Response
                $aiReply = $aiEngine ? $aiEngine->getChatbotResponse($userMessage) : "Engine Offline.";
                $response = ['success' => true, 'message' => $aiReply, 'sentiment' => 'Neutral'];
            }
            break;

        case 'live_send': // Live Chat Send
            $message = $_POST['message'] ?? '';
            $session_id = $_POST['session_id'] ?? ensureChatSession($pdo);
            $is_staff = isset($_SESSION['admin_id']);
            
            if ($message && $session_id) {
                $stmt = $pdo->prepare("INSERT INTO chat_messages (session_id, sender_type, sender_id, message) VALUES (?, ?, ?, ?)");
                $stmt->execute([$session_id, $is_staff ? 'staff' : 'user', $is_staff ? $_SESSION['admin_id'] : null, $message]);
                $response = ['success' => true];
            }
            break;

        case 'live_poll': // Live Chat Poll
            $session_id = $_GET['session_id'] ?? ($_SESSION['chat_session_id'] ?? null);
            $last_id = $_GET['last_id'] ?? 0;
            if ($session_id) {
                $stmt = $pdo->prepare("SELECT * FROM chat_messages WHERE session_id = ? AND id > ? ORDER BY id ASC");
                $stmt->execute([$session_id, $last_id]);
                $response = ['success' => true, 'messages' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
            }
            break;

        case 'get_sessions': // Admin: Get open sessions
            if (isset($_SESSION['admin_id'])) {
                $stmt = $pdo->query("SELECT * FROM chat_sessions WHERE status = 'open' ORDER BY id DESC");
                $response = ['success' => true, 'sessions' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
            }
            break;
    }
} catch (Exception $e) {
    $response = ['success' => false, 'message' => 'System error: ' . $e->getMessage()];
}

if (ob_get_length()) ob_clean();
echo json_encode($response);
