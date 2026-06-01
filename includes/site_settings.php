<?php
require_once 'db.php';

$settings = [];

// 1. Load from JSON Config (Primary Detailing)
$configPath = __DIR__ . '/../site_config.json';
if (file_exists($configPath)) {
    $jsonContent = json_decode(file_get_contents($configPath), true);
    if ($jsonContent) {
        $settings = $jsonContent;
    }
}

// 2. Override with DB Settings (Dynamic overrides)
// try {
//     $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
//     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//         $settings[$row['setting_key']] = $row['setting_value'];
//     }
// } catch(Exception $e) {}

function get_setting($key, $default = '') {
    global $settings;
    return isset($settings[$key]) ? $settings[$key] : $default;
}
?>
