<?php
session_start();
include 'includes/db.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['success' => false, 'error' => 'Invalid action'];

// Determine if user or staff
$is_staff = isset($_SESSION['admin_id']);
$staff_id = $is_staff ? $_SESSION['admin_id'] : null;

// Ensure session exists for users
if (!$is_staff) {
    if (!isset($_SESSION['chat_token'])) {
        $_SESSION['chat_token'] = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("INSERT INTO chat_sessions (session_token, user_name) VALUES (?, ?)");
        $stmt->execute([$_SESSION['chat_token'], 'Guest']);
        $_SESSION['chat_session_id'] = $pdo->lastInsertId();
    }
}

if ($action === 'send') {
    $message = $_POST['message'] ?? '';
    $session_id = $_POST['session_id'] ?? ($_SESSION['chat_session_id'] ?? null);

    if ($message && $session_id) {
        $sender_type = $is_staff ? 'staff' : 'user';
        $sender_id = $is_staff ? $staff_id : null;

        $stmt = $pdo->prepare("INSERT INTO chat_messages (session_id, sender_type, sender_id, message) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$session_id, $sender_type, $sender_id, $message])) {
            $response = ['success' => true];
        } else {
            $response['error'] = 'Database error';
        }
    } else {
        $response['error'] = 'Empty message or no session';
    }
} elseif ($action === 'poll') {
    $session_id = $_GET['session_id'] ?? ($_SESSION['chat_session_id'] ?? null);
    $last_id = $_GET['last_id'] ?? 0;

    if ($session_id) {
        $stmt = $pdo->prepare("SELECT * FROM chat_messages WHERE session_id = ? AND id > ? ORDER BY id ASC");
        $stmt->execute([$session_id, $last_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = ['success' => true, 'messages' => $messages];
    } else {
        $response['error'] = 'No session';
    }
} elseif ($action === 'get_sessions' && $is_staff) {
    $stmt = $pdo->query("SELECT * FROM chat_sessions WHERE status = 'open' ORDER BY id DESC");
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = ['success' => true, 'sessions' => $sessions];
}

echo json_encode($response);
