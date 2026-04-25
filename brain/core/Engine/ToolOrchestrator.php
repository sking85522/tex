<?php
namespace Core\Engine;

require_once __DIR__ . '/../Tools/Intelligence/CoderTool.php';
use Core\Tools\Intelligence\CoderTool;

class ToolOrchestrator {
    private CoderTool $coder;
    private $toolManager;

    public function __construct($toolManager) {
        $this->coder = new CoderTool();
        $this->toolManager = $toolManager;
    }

    public function resolve(string $prompt): ?array {
        $low = strtolower($prompt);

        // 1. Math Tool
        if (preg_match('/(\d+)\s*([\+\-\*\/\^])\s*(\d+)/', $prompt, $matches)) {
            $n1 = (float)$matches[1];
            $op = $matches[2];
            $n2 = (float)$matches[3];
            $res = 0;
            switch($op) {
                case '+': $res = $n1 + $n2; break;
                case '-': $res = $n1 - $n2; break;
                case '*': $res = $n1 * $n2; break;
                case '/': $res = ($n2 != 0) ? $n1 / $n2 : 'Infinity'; break;
            }
            return [
                'status' => 'success',
                'response' => "Sir, iska calculation ye raha: $n1 $op $n2 = $res",
                'intent' => 'math_calculation'
            ];
        }

        // 2. Dynamic Tool Routing (Using ToolManager)
        $routingMap = [
            'convert' => 'UnitConverter',
            'json' => 'JSONFormatter',
            'image' => 'ImageGenerator',
            'generate' => 'ImageGenerator',
            'bnao' => 'ImageGenerator',
            'search' => 'GoogleSearch',
            'terminal' => 'Terminal',
            'shell' => 'Terminal'
        ];

        foreach ($routingMap as $keyword => $toolName) {
            if (str_contains($low, $keyword)) {
                $result = $this->toolManager->execute($toolName, ['prompt' => $prompt, 'input' => $prompt]);
                if ($result && !str_contains($result, 'not found')) {
                    return [
                        'status' => 'success',
                        'response' => is_array($result) ? ($result['message'] ?? json_encode($result)) : $result,
                        'intent' => 'tool_execution'
                    ];
                }
            }
        }

        // 3. Coding Tool
        if (str_contains($low, 'code') || str_contains($low, 'program')) {
            $code = $this->coder->run(['prompt' => $prompt]);
            if (!empty($code)) {
                return [
                    'status' => 'success',
                    'response' => "Sir, ye raha aapka requested code:\n\n```" . $code . "```",
                    'intent' => 'coding'
                ];
            }
        }

        return null;
    }
}
