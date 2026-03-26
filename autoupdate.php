<?php
// Secure Update Script for Shared Hosting Without SSH
// Usage: techelevatex.in/autoupdate.php?token=784512326598
set_time_limit(300); // Allow script to run for 5 minutes

$SECRET_TOKEN = '784512326598';
$REPO_URL = 'https://github.com/sking85522/tex/archive/refs/heads/main.zip';
$EXTRACT_DIR = __DIR__ . '/temp_update/';
$ROOT_DIR = __DIR__ . '/';

if (!isset($_GET['token']) || $_GET['token'] !== $SECRET_TOKEN) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized Access. Invalid Token.']));
}

function logMessage($msg) {
    echo "[" . date('Y-m-d H:i:s') . "] " . $msg . "<br>";
    @file_put_contents(__DIR__ . '/update_log.txt', "[" . date('Y-m-d H:i:s') . "] " . $msg . PHP_EOL, FILE_APPEND);
}

logMessage("Starting auto-update process...");

// 1. Download ZIP from GitHub
$zipFile = __DIR__ . '/latest.zip';
logMessage("Downloading latest code from GitHub main branch...");

$ch = curl_init($REPO_URL);
$fp = fopen($zipFile, 'w+');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// GitHub requires a User-Agent string to download via cURL
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
$success = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
fclose($fp);

if ($httpCode !== 200 || !file_exists($zipFile) || filesize($zipFile) == 0) {
    die(logMessage("Error: Failed to download ZIP. HTTP Code: $httpCode"));
}
logMessage("Download complete.");

// 2. Extract ZIP
logMessage("Extracting ZIP file...");
$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    if (!is_dir($EXTRACT_DIR)) {
        mkdir($EXTRACT_DIR, 0755, true);
    }
    $zip->extractTo($EXTRACT_DIR);
    $zip->close();
    logMessage("Extraction successful.");
} else {
    die(logMessage("Error: Failed to open ZIP file."));
}

// 3. Move extracted files to root directory
logMessage("Replacing old files with new files...");
// GitHub zip usually extracts into a subfolder like 'tex-main'
$extractedFolders = array_diff(scandir($EXTRACT_DIR), ['.', '..']);
$repoFolderName = '';
foreach ($extractedFolders as $folder) {
    if (is_dir($EXTRACT_DIR . $folder)) {
        $repoFolderName = $folder;
        break;
    }
}

if (!$repoFolderName) {
    die(logMessage("Error: Could not find extracted repository folder."));
}

$sourcePath = $EXTRACT_DIR . $repoFolderName . '/';

function recursiveCopy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (($file = readdir($dir)) !== false) {
        if (($file != '.') && ($file != '..')) {
            $srcPath = $src . '/' . $file;
            $dstPath = $dst . '/' . $file;

            if (is_dir($srcPath)) {
                recursiveCopy($srcPath, $dstPath);
            } else {
                // Don't overwrite the main database credentials if they exist
                // This protects the live server's DB password from being overwritten by github code
                if ($file === 'db.php' && file_exists($dstPath)) {
                    continue;
                }
                copy($srcPath, $dstPath);
            }
        }
    }
    closedir($dir);
}

recursiveCopy($sourcePath, $ROOT_DIR);
logMessage("Files updated successfully.");

// 4. Cleanup Temp Files
logMessage("Cleaning up temporary files...");
function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
    }
    return rmdir($dir);
}
deleteDirectory($EXTRACT_DIR);
@unlink($zipFile);
logMessage("Cleanup complete.");

// 5. Run Database Migrations
logMessage("Checking for database schema updates in migrations/ folder...");
require_once $ROOT_DIR . 'includes/db.php';

try {
    // Ensure migrations table exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `system_migrations` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `migration_file` varchar(255) NOT NULL UNIQUE,
        `executed_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $migrationsDir = $ROOT_DIR . 'migrations/';
    if (is_dir($migrationsDir)) {
        $files = array_diff(scandir($migrationsDir), ['.', '..']);
        sort($files); // Run in alphabetical order (e.g. 001_initial.sql, 002_add_table.sql)

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {

                // Check if already executed
                $stmt = $pdo->prepare("SELECT id FROM system_migrations WHERE migration_file = ?");
                $stmt->execute([$file]);
                $alreadyExecuted = $stmt->fetch();



                if (!$alreadyExecuted) {
                    logMessage("Running database migration: $file...");
                    $sqlContent = file_get_contents($migrationsDir . $file);

                    $statements = explode(";", $sqlContent);
                    $successCount = 0;
                    $errorCount = 0;

                    foreach ($statements as $statement) {
                        $statement = trim($statement);
                        if (!empty($statement)) {
                            try {
                                $pdo->exec($statement);
                                $successCount++;
                            } catch (PDOException $e) {
                                $errorCount++;
                                @file_put_contents(__DIR__ . "/update_log.txt", "[DB Warning in $file] " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                            }
                        }
                    }

                    $stmt = $pdo->prepare("INSERT INTO system_migrations (migration_file) VALUES (?)");
                    $stmt->execute([$file]);
                    logMessage("Migration $file applied (Success: $successCount, Errors Ignored: $errorCount).");
                }
 }
        }
    } else {
        logMessage("No migrations folder found. Skipping DB updates.");
    }

} catch (PDOException $e) {
    logMessage("Database Error during migrations: " . $e->getMessage());
}

logMessage("<b>✅ Auto-Update Process Finished Successfully!</b>");
?>
