<?php
/**
 * Hritik Autoloader
 * Automatically maps namespaces to the modules/ directory.
 */
spl_autoload_register(function ($class) {
    // 0. Link SciPHP Modules
    $modulesAutoload = dirname(__DIR__, 2) . '/modules/autoload.php';
    if (file_exists($modulesAutoload)) {
        require_once $modulesAutoload;
    }

    // Project-specific namespace prefix
    $root = dirname(__DIR__); // h:\...\site_ai\brain
    $base_dir = dirname(__DIR__, 2) . '/modules/';

    // Handle Core Namespace
    if (strpos($class, 'Core\\') === 0) {
        $relative_class = str_replace('Core\\', '', $class);
        $file = $root . '/core/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) { require_once $file; return; }
        // Try lowercase core just in case
        $file = $root . '/core/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }

    // Handle Zxing (QR Decoder)
    if (strpos($class, 'Zxing\\') === 0) {
        $relative_class = str_replace('\\', '/', $class);
        $file = $base_dir . 'qr-decoder/lib/' . $relative_class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }

    // Handle MathPHP
    if (strpos($class, 'MathPHP\\') === 0) {
        $relative_class = str_replace('MathPHP\\', '', $class);
        $file = $base_dir . 'math-php/src/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }

    // Handle PhpParser
    if (strpos($class, 'PhpParser\\') === 0) {
        $relative_class = str_replace('PhpParser\\', '', $class);
        $file = $base_dir . 'php-parser/lib/PhpParser/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }

    // Handle Whisper (STT)
    if (strpos($class, 'Whisper\\') === 0) {
        $file = $base_dir . 'whisper/src/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }

    // Handle PhpChatbot (Multi-Model AI)
    if (strpos($class, 'Rumenx\\PhpChatbot\\') === 0) {
        $relative_class = str_replace('Rumenx\\PhpChatbot\\', '', $class);
        $file = $base_dir . 'chatbot-engine/src/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }

    // Generic fallback for other modules
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
