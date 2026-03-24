<?php

// Central autoloader for all modules.

// 1. Get the list of available modules.
$modules = require __DIR__ . '/modules.php';

// 2. Loop through each module and load its autoloader and main class file.
foreach ($modules as $module) {
    $moduleDir = __DIR__ . '/' . $module;

    // Load the module's specific autoloader
    $autoloaderFile = $moduleDir . '/autoload.php';
    if (file_exists($autoloaderFile)) {
        require_once $autoloaderFile;
    }

    // Load the module's main class file (e.g., NumPHP.php, SciPHP.php)
    $className = str_replace('php', 'PHP', ucfirst($module));
    $mainClassFile = $moduleDir . '/' . $className . '.php';
    if (file_exists($mainClassFile)) {
        require_once $mainClassFile;
    }
}
