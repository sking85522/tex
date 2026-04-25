<?php
namespace Core\Memory;

require_once __DIR__ . '/../ML/SupervisedLearner.php';
use Core\ML\SupervisedLearner;

class SystemIntrospection {
    private SupervisedLearner $learner;
    private string $rootPath;

    public function __construct() {
        $this->learner = new SupervisedLearner();
        $this->rootPath = dirname(dirname(__DIR__));
    }

    public function learnSelf(): array {
        $scanDirs = ['core']; // Only scan code for architecture
        $stats = ['files_scanned' => 0, 'concepts_learned' => 0];

        // Access the underlying PDO for transaction speed
        $reflection = new \ReflectionClass($this->learner);
        $dbProp = $reflection->getProperty('db');
        $dbProp->setAccessible(true);
        $neuralDb = $dbProp->getValue($this->learner);
        $reflectionDb = new \ReflectionClass($neuralDb);
        $pdoProp = $reflectionDb->getProperty('db');
        $pdoProp->setAccessible(true);
        $pdo = $pdoProp->getValue($neuralDb);

        $pdo->beginTransaction();

        foreach ($scanDirs as $dir) {
            $path = $this->rootPath . DIRECTORY_SEPARATOR . $dir;
            if (!is_dir($path)) continue;

            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $file) {
                if ($file->isDir()) continue;
                
                // Skip hidden files/folders (.git, .vscode, etc)
                if (preg_match('/[\\\\\/]\./', $file->getRealPath())) continue;

                // Skip massive files to prevent memory crash
                if ($file->getSize() > 1024 * 1024) continue; 

                $filename = $file->getBasename();
                $content = file_get_contents($file->getRealPath());
                $relPath = str_replace($this->rootPath, '', $file->getRealPath());
                $filename = $file->getBasename();

                // 1. Learn file existence and purpose
                $purpose = $this->determinePurpose($filename, $content);
                $this->learner->teach("What is the purpose of $filename?", "The file $relPath is $purpose.", 'self_introspection');
                $this->learner->teach("Where is the $filename file located?", "It is located at $relPath.", 'self_introspection');
                
                $stats['files_scanned']++;
                $stats['concepts_learned'] += 2;
            }
        }

        $pdo->commit();

        return $stats;
    }

    /**
     * Uses heuristics to determine what a file does based on its code/content.
     */
    private function determinePurpose(string $filename, string $content): string {
        if (str_contains($content, 'class Engine')) return "the central heart of Hritik AI, orchestrating all memory, logic, and responses";
        if (str_contains($content, 'class NeuralDatabase')) return "my high-performance brain memory using SQLite for massive data storage";
        if (str_contains($content, 'class SupervisedLearner')) return "my active learning system that stores and recalls taught facts";
        if (str_contains($content, 'class MarkovGenerator')) return "my creative thought generator that synthesizes new sentences";
        if (str_contains($content, 'class ResponseBuilder')) return "my speech synthesis layer that formats and personalizes answers for the user";
        if (str_contains($content, 'class KnowledgeIngestor')) return "my autonomous document reader that learns from local project files";
        
        // General heuristics
        if (str_ends_with($filename, '.db')) return "a persistent database file where I store my long-term knowledge";
        if (str_contains($relPath ?? '', 'Memory')) return "part of my neural memory system for storing conversation and facts";
        if (str_contains($relPath ?? '', 'ML') || str_contains($relPath ?? '', 'DL')) return "one of my machine learning or deep learning modules for pattern recognition";
        
        return "a supporting component in my neural architecture designed by Hritik Softwares";
    }
}
