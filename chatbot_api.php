<?php
include 'includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $user_message = strtolower(trim($_POST['message']));

    if (empty($user_message)) {
        echo json_encode(['reply' => 'Please type a message.']);
        die();
    }

    try {
        // Fetch FAQs from DB
        $stmt = $pdo->query("SELECT * FROM chatbot_faqs");
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $best_match = null;
        $highest_score = 0;

        foreach ($faqs as $faq) {
            $keywords = explode(',', strtolower($faq['keywords']));
            $score = 0;

            // Basic keyword matching
            foreach ($keywords as $keyword) {
                if (strpos($user_message, trim($keyword)) !== false) {
                    $score++;
                }
            }

            if ($score > $highest_score) {
                $highest_score = $score;
                $best_match = $faq['answer'];
            }
        }

        if ($best_match) {
            echo json_encode(['reply' => $best_match]);
        } else {
            echo json_encode(['reply' => "I'm sorry, I don't understand that. Please use our contact form for detailed inquiries!"]);
        }

    } catch (PDOException $e) {
        echo json_encode(['reply' => "Database error occurred."]);
    }
} else {
    echo json_encode(['reply' => "Invalid request."]);
}
?>
