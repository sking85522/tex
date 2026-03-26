<?php
$host = 'localhost';
$dbname = 'tech_elevate_x';
$username = 'root'; // Change if using a different DB user
$password = ''; // Change if using a different DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected to Database Successfully!"; // For debugging
} catch (PDOException $e) {
    // For local mockup/testing where mysql is not available
    // Mock the PDO object minimally to not crash
    class MockPDO {
        public function query($sql) {
            return new class {
                public function fetchAll() { return []; }
                public function fetchColumn() { return 0; }
            };
        }
        public function prepare($sql) {
            return new class {
                public function execute($params = []) { return true; }
                public function fetchAll() { return []; }
                public function fetch() { return false; }
            };
        }
    }
    $pdo = new MockPDO();
}
?>
