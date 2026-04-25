<?php
namespace Core\NeuralSchema;

/**
 * HRITIK AI - NEURAL LINKER
 * Connects all files in the system so the AI knows its own architecture.
 */
class NeuralLinker {
    private $projectRoot;

    public function __construct() {
        $this->projectRoot = dirname(__DIR__, 2);
    }

    public function mapSystem(): array {
        $map = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->projectRoot));

        foreach ($iterator as $file) {
            if ($file->isDir()) continue;
            
            $path = $file->getPathname();
            $ext = $file->getExtension();
            
            if ($ext === 'php') {
                $content = file_get_contents($path);
                // Extract purpose from class name or comments
                $purpose = "General Utility";
                if (preg_match('/class (\w+)/', $content, $m)) $purpose = "Logic for " . $m[1];
                if (str_contains($path, 'ML')) $purpose = "Machine Learning Module";
                if (str_contains($path, 'Memory')) $purpose = "Data Storage & Retrieval";
                
                $map[basename($path)] = [
                    'path' => $path,
                    'purpose' => $purpose,
                    'linked_to' => $this->findLinks($content)
                ];
            }
        }
        return $map;
    }

    private function findLinks($content) {
        preg_match_all('/use (Core\\\[\w\\\]+)/', $content, $matches);
        return $matches[1] ?? [];
    }
}
