<?php
namespace Core\Tools;

/**
 * ToolManager
 * Dynamically loads and executes utility tools (QR, Scanner, Unit Converter, etc.)
 */
class ToolManager {
    
    private $toolsDir;

    public function __construct() {
        $this->toolsDir = __DIR__;
    }

    /**
     * Executes a tool if it matches the requested type.
     */
    public function execute($toolName, $params = []) {
        // Direct Mapping for Core Pro Tools
        $proTools = [
            'ImageGenerator' => 'Core\\Tools\\Visual\\ImageGeneratorTool',
            'Terminal' => 'Core\\Tools\\System\\TerminalTool',
            'MathSolver' => 'Core\\Tools\\Data\\MathSolverTool',
            'QR' => 'Core\\Tools\\Data\\QRTool',
            'LocalKnowledge' => 'Core\\Tools\\Search\\LocalKnowledgeTool',
            'GoogleSearch' => 'Core\\Tools\\Search\\GoogleSearchTool'
        ];

        if (isset($proTools[$toolName])) {
            $fullClassName = $proTools[$toolName];
            if (class_exists($fullClassName)) {
                $tool = new $fullClassName();
                return $tool->run($params);
            }
        }

        $toolName = ucfirst($toolName) . "Tool";
        
        // Recursive search for the tool file
        $directory = new \RecursiveDirectoryIterator($this->toolsDir);
        $iterator = new \RecursiveIteratorIterator($directory);
        $filePath = null;

        foreach ($iterator as $file) {
            if ($file->getFilename() === $toolName . ".php") {
                $filePath = $file->getPathname();
                break;
            }
        }

        if ($filePath && file_exists($filePath)) {
            require_once $filePath;
            // Handle nested namespaces if needed
            $parts = explode(DIRECTORY_SEPARATOR, str_replace($this->toolsDir, '', dirname($filePath)));
            $subNamespace = implode('\\', array_filter($parts));
            $fullClassName = "Core\\Tools\\" . ($subNamespace ? $subNamespace . "\\" : "") . $toolName;

            if (class_exists($fullClassName)) {
                $tool = new $fullClassName();
                return $tool->run($params);
            }
        }

        return "Tool '{$toolName}' not found in Pro Hub.";
    }

    /**
     * Lists all available tools in the repository.
     */
    public function getAvailableTools() {
        $tools = [];
        $files = glob($this->toolsDir . "/*Tool.php");
        foreach ($files as $file) {
            $tools[] = str_replace('Tool.php', '', basename($file));
        }
        return $tools;
    }
}
