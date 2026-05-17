<?php
// admin/modules/site_data/index.php
Auth::requireLogin();

$dataFile = BASE_PATH . '/../data/site_data.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
        die("CSRF token validation failed.");
    }

    $jsonData = $_POST['site_data'] ?? '{}';
    // Validate JSON
    json_decode($jsonData);
    if (json_last_error() === JSON_ERROR_NONE) {
        file_put_contents($dataFile, $jsonData);
        Session::setFlash('success', 'Site data updated successfully.');
    } else {
        Session::setFlash('error', 'Invalid JSON data provided.');
    }
    header('Location: ' . APP_URL . '/site_data');
    exit;
}

$currentData = file_exists($dataFile) ? file_get_contents($dataFile) : '{}';

// format JSON
$currentData = json_encode(json_decode($currentData), JSON_PRETTY_PRINT);
?>
<div class="bg-white p-6 rounded shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manage Site Data (JSON)</h2>
    </div>

    <form method="POST" action="<?= APP_URL ?>/site_data">
        <?= Csrf::getTokenField() ?>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="site_data">
                JSON Content
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline font-mono text-sm h-96" id="site_data" name="site_data" required><?= htmlspecialchars($currentData) ?></textarea>
            <p class="text-xs text-gray-500 mt-2">Careful: Invalid JSON will break the frontend rendering.</p>
        </div>
        <div class="flex items-center justify-end">
            <button class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Save JSON Data
            </button>
        </div>
    </form>
</div>
