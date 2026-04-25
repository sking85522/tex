<?php
namespace Core\Tools\System;

/**
 * UpdaterTool
 * Automatically updates cloned modules from GitHub using git pull.
 */
class UpdaterTool {
    
    public function run($params = []) {
        $module = $params['module'] ?? 'all';
        $modulesDir = dirname(__DIR__, 2) . '/modules/';
        
        $results = [];
        $dirs = ($module === 'all') ? glob($modulesDir . '*', GLOB_ONLYDIR) : [$modulesDir . $module];

        foreach ($dirs as $dir) {
            if (is_dir($dir . '/.git')) {
                chdir($dir);
                $output = shell_exec("git pull 2>&1");
                $results[basename($dir)] = trim($output);
            }
        }

        return [
            'status' => 'success',
            'updates' => $results,
            'message' => "Sir, maine GitHub modules ko check kar liya hai. " . count($results) . " modules update kiye gaye hain."
        ];
    }
}
