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
    die("Database Connection Failed: " . $e->getMessage());
}
?>
