<?php
// API Endpoint for external systems to train the AI directly
// Example Usage via cURL (PHP/Python):
// curl -X POST -F "jsonl_file=@dataset.jsonl" https://yoursite.com/trainingbycomputertoserverapi.php
// OR POST raw JSON: {"prompt": "What is AI?", "completion": "Artificial Intelligence"}

header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';

$response = ['success' => false, 'message' => 'Invalid Request'];

// Check if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['jsonl_file'])) {
    if ($_FILES['jsonl_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['jsonl_file']['tmp_name'];
        $handle = fopen($file_tmp, "r");
        if ($handle) {
            $count = 0;
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("INSERT INTO ai_knowledge (topic, learned_content, confidence_score) VALUES (?, ?, ?)");
                while (($line = fgets($handle)) !== false) {
                    $data = json_decode($line, true);
                    if ($data) {
                        $topic = $data['prompt'] ?? $data['question'] ?? $data['topic'] ?? '';
                        $content = $data['completion'] ?? $data['answer'] ?? $data['learned_content'] ?? $data['response'] ?? '';
                        if (!empty($topic) && !empty($content)) {
                            $stmt->execute([$topic, $content, 100]);
                            $count++;
                        }
                    }
                }
                $pdo->commit();
                $response = ['success' => true, 'message' => "Successfully ingested $count neural pathways from API file upload."];
            } catch (Exception $e) {
                $pdo->rollBack();
                $response = ['success' => false, 'message' => "Error processing JSONL file: " . $e->getMessage()];
            }
            fclose($handle);
        } else {
            $response = ['success' => false, 'message' => "Failed to read the uploaded file."];
        }
    } else {
        $response = ['success' => false, 'message' => "File upload error code: " . $_FILES['jsonl_file']['error']];
    }
}
// Check if raw JSON was sent (for single entry training)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
        $topic = $data['prompt'] ?? $data['question'] ?? $data['topic'] ?? '';
        $content = $data['completion'] ?? $data['answer'] ?? $data['learned_content'] ?? $data['response'] ?? '';

        if (!empty($topic) && !empty($content)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO ai_knowledge (topic, learned_content, confidence_score) VALUES (?, ?, ?)");
                $stmt->execute([$topic, $content, 100]);
                $response = ['success' => true, 'message' => "Single neural pathway ingested via API."];
            } catch (Exception $e) {
                $response = ['success' => false, 'message' => "Database error: " . $e->getMessage()];
            }
        } else {
            $response = ['success' => false, 'message' => "Missing 'prompt' or 'completion' fields."];
        }
    } else {
         $response = ['success' => false, 'message' => "Invalid JSON payload or missing file."];
    }
}

echo json_encode($response);
