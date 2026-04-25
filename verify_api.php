<?php
require_once __DIR__ . '/includes/db.php';
$stmt = $pdo->prepare("SELECT * FROM ai_knowledge");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($results);
