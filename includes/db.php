<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$host = 'localhost';
$dbname = 'tech_elevate_x';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Initialize HRITIK AI Autoloader
    $autoloader = __DIR__ . '/../brain/core/Autoloader.php';
    if (file_exists($autoloader)) {
        require_once $autoloader;
    }
    
    // 2. Arm AI Self-Healing Engine (Now within the Brain Core)
    if (class_exists('Core\AIRepairEngine')) {
        new \Core\AIRepairEngine($pdo);
    }
    
} catch (PDOException $e) {
    class MockPDO {
        public function query($sql) { return new class { public function fetchAll() { return []; } public function fetchColumn() { return 0; } public function fetch() { return false; } }; }
        public function prepare($sql) { return new class { public function execute($params = []) { return true; } public function fetchAll() { return []; } public function fetch() { return false; } }; }
        public function exec($sql) { return 0; }
    }
    $pdo = new MockPDO();
}
?>
