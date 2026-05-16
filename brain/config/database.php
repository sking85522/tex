<?php

// Load environment variables if not already loaded
if (file_exists(__DIR__ . '/../../includes/env_loader.php')) {
    require_once __DIR__ . '/../../includes/env_loader.php';
}

return [
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'user' => $_ENV['DB_USERNAME'] ?? 'root',
    'pass' => $_ENV['DB_PASSWORD'] ?? '',
    'db'   => $_ENV['DB_DATABASE'] ?? 'ai_db',
    'charset' => 'utf8mb4',
];
