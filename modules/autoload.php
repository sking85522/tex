<?php
/**
 * SciPHP Framework v2.1 — High-Performance Lazy Autoloader
 * 
 * Instead of loading all modules on start, this autoloader only
 * triggers when a specific SciPHP module class is instantiated.
 */

spl_autoload_register(function ($className) {
    // Handle namespaces (e.g., NLPHP\Classification\NaiveBayes)
    $parts = explode('\\', $className);
    $namespacePrefix = $parts[0] . '\\' . ($parts[1] ?? $parts[0]);
    
    // Simple mapping: Namespace Start -> Directory
    $namespaceToDir = [
        'NumPHP' => 'numphp',
        'SciPHP' => 'sciphp',
        'NLPHP' => 'nlphp',
        'NeuralPHP' => 'neuralphp',
        'MLPHP' => 'mlphp',
        'VisionPHP' => 'visionphp',
        'SpeechPHP' => 'speechphp',
        'PandaPHP' => 'pandaphp',
        'SearchPHP' => 'search',
    ];

    $topNamespace = $parts[0];
    if (isset($namespaceToDir[$topNamespace])) {
        $dir = $namespaceToDir[$topNamespace];
        $autoloadFile = __DIR__ . '/' . $dir . '/autoload.php';
        if (file_exists($autoloadFile)) {
            require_once $autoloadFile;
            // The module's own autoloader will now handle the specific class
        }
    }

    if (isset($classMap[$className])) {
        $filePath = __DIR__ . '/' . $classMap[$className];
        if (file_exists($filePath)) {
            // Check if module has its own internal autoloader for sub-components
            $moduleDir = dirname($filePath);
            if (file_exists($moduleDir . '/autoload.php')) {
                require_once $moduleDir . '/autoload.php';
            }
            require_once $filePath;
        }
    }
});
