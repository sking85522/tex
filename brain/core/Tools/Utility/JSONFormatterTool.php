<?php
namespace Core\Tools\Utility;

/**
 * JSONFormatterTool
 * Beautifies and validates raw JSON strings.
 */
class JSONFormatterTool {
    
    public function run($params = []) {
        $json = $params['json'] ?? '';
        if (empty($json)) return "Please provide JSON data to format.";

        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return "Invalid JSON: " . json_last_error_msg();
        }

        return [
            'formatted' => json_encode($data, JSON_PRETTY_PRINT),
            'status' => 'valid',
            'size' => strlen($json) . ' bytes'
        ];
    }
}
