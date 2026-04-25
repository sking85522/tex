<?php
namespace Core\Tools\Security;

/**
 * CodeExecutorTool
 * Safely (as possible) executes PHP code snippets and returns output.
 */
class CodeExecutorTool {
    
    public function run($params = []) {
        $code = $params['code'] ?? '';
        if (empty($code)) return "Please provide code to execute.";

        // Basic Safety Check (Restrict system calls)
        $forbidden = ['system', 'exec', 'passthru', 'shell_exec', 'unlink', 'rmdir'];
        foreach ($forbidden as $func) {
            if (strpos($code, $func) !== false) {
                return "Security Alert: Use of forbidden function '{$func}' detected.";
            }
        }

        // Capture output
        ob_start();
        try {
            eval("?>" . $code);
            $output = ob_get_clean();
            return [
                'status' => 'success',
                'output' => $output ?: 'Code executed successfully (No output).',
                'timestamp' => date('H:i:s')
            ];
        } catch (\Throwable $e) {
            ob_end_clean();
            return "Execution Error: " . $e->getMessage();
        }
    }
}
