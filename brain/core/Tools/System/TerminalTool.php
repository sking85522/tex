<?php
namespace Core\Tools\System;

/**
 * TerminalTool
 * Bridges the web dashboard to the local shell for real-time execution.
 */
class TerminalTool {
    
    public function run($params = []) {
        $command = $params['command'] ?? '';
        if (empty($command)) return "Terminal Ready. Input command...";

        // Security filtering (local dev focus)
        $forbidden = ['rm -rf /', 'format', 'mkfs'];
        foreach ($forbidden as $f) {
            if (strpos($command, $f) !== false) return "Operation Forbidden.";
        }

        try {
            $output = shell_exec($command . " 2>&1");
            return [
                'status' => 'success',
                'command' => $command,
                'output' => $output ?: "Command executed with no output.",
                'cwd' => getcwd()
            ];
        } catch (\Exception $e) {
            return "Terminal Error: " . $e->getMessage();
        }
    }
}
