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
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
                    finfo_close($finfo);

                    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                    if (in_array($mimeType, $allowedTypes) && in_array($extension, $allowedExtensions)) {
                        $uploadDir = __DIR__ . '/assets/uploads/ai_vision/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                        // Generate a completely safe, random filename
                        $newName = uniqid('cv_', true) . '.' . $extension;
                        $targetFile = $uploadDir . $newName;

                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                            require_once __DIR__ . '/brain/core/ComputerVision/ComputerVisionAssistant.php';
                            $cv = new \Core\ComputerVision\ComputerVisionAssistant();
                            $visionAnalysis = $cv->analyze($targetFile);

                            $userMessage = $userMessage . "\n\n[AI Vision Scan Report: " . $visionAnalysis . "]";

                            // Give direct CV response if no prompt was given
                            if (empty(trim($_POST['message'] ?? ''))) {
                                $response = ['success' => true, 'message' => $visionAnalysis, 'sentiment' => 'Neutral'];
                                ob_clean();
                                echo json_encode($response);
                                die(); // Use die instead of exit to prevent blocking sandbox
                            }
                        }
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

                case 'live_poll': // Live Chat Poll (Disabled to prevent background connection limit errors)
            $response = ['success' => false, 'message' => 'Polling disabled in current hosting environment.'];
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
