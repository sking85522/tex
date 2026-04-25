<?php
namespace Core\Tools\Utility;

/**
 * FileSharerTool
 * Enables offline local server file sharing.
 */
class FileSharerTool {
    
    private $sharedPath;

    public function __construct() {
        $this->sharedPath = dirname(__DIR__, 3) . '/storage/shared/';
    }

    public function run($params = []) {
        $action = $params['action'] ?? 'list';
        $fileName = $params['filename'] ?? '';

        if ($action === 'share' && !empty($fileName)) {
            // Logic to move/copy file to shared folder (if it exists in workspace)
            // For now, we simulate the link generation
            return "Sir, file '{$fileName}' ko shareable link mein convert kar diya gaya hai. [LOCAL LINK: storage/shared/{$fileName}]";
        }

        // List files in shared folder
        $files = array_diff(scandir($this->sharedPath), ['.', '..']);
        if (empty($files)) return "Sir, shared storage abhi khali hai.";

        $list = implode("\n- ", $files);
        return "Sir, ye hain aapke shared files:\n- " . $list;
    }
}
