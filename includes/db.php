<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Initialize HRITIK AI Autoloader FIRST
$autoloader = __DIR__ . '/../brain/core/Autoloader.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

// Load the main classes manually since the autoloader might not cover them all immediately
if (file_exists(__DIR__ . '/../brain/core/AIEngine.php')) {
    require_once __DIR__ . '/../brain/core/AIEngine.php';
}
if (file_exists(__DIR__ . '/../brain/core/Engine.php')) {
    require_once __DIR__ . '/../brain/core/Engine.php';
}

// Load environment variables
if (file_exists(__DIR__ . '/env_loader.php')) {
    require_once __DIR__ . '/env_loader.php';
}

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbname = $_ENV['DB_DATABASE'] ?? 'tech_elevate_x';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 2. Arm AI Self-Healing Engine (Now within the Brain Core)
    if (class_exists('Core\AIRepairEngine')) {
        new \Core\AIRepairEngine($pdo);
    }
    
} catch (PDOException $e) {

    if (!class_exists('MockPDO')) {
        class MockPDO {
            public function query($sql) { return new class { public function fetchAll() { return []; } public function fetchColumn() { return 0; } public function fetch() { return false; } }; }
            public function prepare($sql) { return new class { public function execute($params = []) { return true; } public function fetchAll() { return []; } public function fetch() { return false; } public function fetchColumn() { return false; } }; }
            public function exec($sql) { return 0; }
            public function lastInsertId() { return 1; }
        }
    }
    $pdo = new MockPDO();
}
?>
