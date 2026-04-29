<?php
namespace Core\Engine;

/**
 * DataSyncManager
 * Synchronizes local storage (JSON/SQLite) into the main MySQL database.
 * Automatically creates tables based on data structure.
 */
class DataSyncManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Synchronizes a specific storage file to a database table.
     */
    public function syncFileToTable(string $filePath, string $tableName): array {
        if (!file_exists($filePath)) {
            return ['status' => 'error', 'message' => "Source file not found: $filePath"];
        }

        $data = json_decode(file_get_contents($filePath), true);
        if (!$data || !is_array($data)) {
            return ['status' => 'error', 'message' => "Invalid JSON data in $filePath"];
        }

        // Take first item to determine schema
        $sample = is_array(reset($data)) ? reset($data) : null;
        if (!$sample) {
            return ['status' => 'error', 'message' => "Empty dataset"];
        }

        $this->ensureTableExists($tableName, $sample);

        $inserted = 0;
        foreach ($data as $row) {
            if ($this->insertRow($tableName, $row)) {
                $inserted++;
            }
        }

        return [
            'status' => 'success',
            'table' => $tableName,
            'records_synced' => $inserted
        ];
    }

    /**
     * Auto-creates table based on JSON keys.
     */
    private function ensureTableExists(string $tableName, array $sample) {
        $columns = ["id INT AUTO_INCREMENT PRIMARY KEY"];
        foreach ($sample as $key => $value) {
            $type = is_numeric($value) ? "DECIMAL(15,2)" : "TEXT";
            if (is_bool($value)) $type = "TINYINT(1)";
            $columns[] = "`$key` $type";
        }
        $columns[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";

        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(", ", $columns) . ")";
        $this->pdo->exec($sql);
    }

    private function insertRow(string $tableName, array $row): bool {
        $keys = array_keys($row);
        $fields = "`" . implode("`, `", $keys) . "`";
        $placeholders = implode(", ", array_fill(0, count($keys), "?"));
        
        $sql = "INSERT IGNORE INTO `$tableName` ($fields) VALUES ($placeholders)";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(array_values($row));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Syncs the high-priority Neural Knowledge (SQLite -> MySQL)
     */
    public function syncNeuralKnowledge(): array {
        $sqlitePath = dirname(__DIR__, 2) . '/storage/training/neural_knowledge.db';
        if (!file_exists($sqlitePath)) return ['status' => 'error', 'message' => 'SQLite DB not found'];

        try {
            $sqlite = new \PDO("sqlite:$sqlitePath");
            $rows = $sqlite->query("SELECT * FROM knowledge")->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($rows)) return ['status' => 'success', 'records_synced' => 0];

            $this->ensureTableExists('ai_sync_knowledge', $rows[0]);
            
            $count = 0;
            foreach ($rows as $row) {
                if ($this->insertRow('ai_sync_knowledge', $row)) $count++;
            }

            return ['status' => 'success', 'records_synced' => $count];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Backup Local Data to Central Remote DB
     */
    public function backupToCentralDB(string $localTable): array {
        try {
            require_once __DIR__ . '/RemoteDB.php';
            $remoteDB = new \Core\Engine\RemoteDB();

            // Get all local data
            $rows = $this->pdo->query("SELECT * FROM `$localTable`")->fetchAll(\PDO::FETCH_ASSOC);
            if (empty($rows)) return ['status' => 'success', 'message' => 'No data to backup'];

            // The remote API expects base64 encoded raw SQL. We must ensure it's perfectly escaped.
            // Using PDO::quote() from the local database connection is the safest way to escape strings for MySQL before sending over the wire.

            $columns = array_keys($rows[0]);
            $colString = "`" . implode("`, `", $columns) . "`";

            $valuesArr = [];
            foreach ($rows as $row) {
                $escapedVals = array_map(function($v) {
                    if ($v === null) return 'NULL';
                    // Use local PDO to safely quote the string
                    return $this->pdo->quote($v);
                }, array_values($row));
                $valuesArr[] = "(" . implode(", ", $escapedVals) . ")";
            }

            $sql = "INSERT IGNORE INTO `$localTable` ($colString) VALUES " . implode(", ", $valuesArr);

            $result = $remoteDB->query($sql);
            return ['status' => 'success', 'remote_response' => $result, 'records_sent' => count($rows)];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
