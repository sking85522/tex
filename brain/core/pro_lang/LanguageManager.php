<?php
namespace Core\ProLang;

/**
 * LanguageManager
 * Handles code generation, parsing, and multi-language logic.
 */
class LanguageManager {
    
    private $supportedLangs = ['php', 'javascript', 'python', 'html', 'css'];
    private $phpExpert;
    private $htmlExpert;
    private $cssExpert;
    private $jsExpert;
    private $pythonExpert;
    private $javaExpert;
    private $mathExpert;
    private $reasoningExpert;
    private CSTSynthesizer $cst;

    public function __construct() {
        require_once __DIR__ . '/CSTSynthesizer.php';
        require_once __DIR__ . '/PHP/PHPSynthesizer.php';
        require_once __DIR__ . '/Web/HTML/HTMLExpert.php';
        require_once __DIR__ . '/Web/CSS/CSSExpert.php';
        require_once __DIR__ . '/Web/JS/JSExpert.php';
        require_once __DIR__ . '/Python/PythonExpert.php';
        require_once __DIR__ . '/Java/JavaExpert.php';
        
        $this->cst = new CSTSynthesizer();
        $this->phpExpert = new \Core\ProLang\PHP\PHPSynthesizer($this->cst);
        $this->htmlExpert = new \Core\ProLang\Web\HTML\HTMLExpert($this->cst);
        $this->cssExpert = new \Core\ProLang\Web\CSS\CSSExpert($this->cst);
        $this->jsExpert = new \Core\ProLang\Web\JS\JSExpert($this->cst);
        $this->pythonExpert = new \Core\ProLang\Python\PythonExpert($this->cst);
        $this->javaExpert = new \Core\ProLang\Java\JavaExpert($this->cst);
    }

    /**
     * Identifies code snippets or programming requests.
     */
    public function handleCodeRequest(string $prompt, array $nluData = []): string {
        $progData = $nluData['entities']['programming'] ?? [];
        $detectedLang = strtolower($progData['language'] ?? '');

        // Fallback to manual keyword scanning if NLU missed it
        if (!$detectedLang) {
            $promptLower = strtolower($prompt);
            if (str_contains($promptLower, 'php')) $detectedLang = 'php';
            elseif (str_contains($promptLower, 'js') || str_contains($promptLower, 'javascript')) $detectedLang = 'javascript';
            elseif (str_contains($promptLower, 'python') || str_contains($promptLower, 'py')) $detectedLang = 'python';
            elseif (str_contains($promptLower, 'html')) $detectedLang = 'html';
            elseif (str_contains($promptLower, 'css')) $detectedLang = 'css';
            else $detectedLang = 'php'; // Default language
        }

        switch ($detectedLang) {
            case 'php':
                return $this->phpExpert->synthesize($prompt, $progData);
            case 'javascript':
            case 'node':
                return $this->jsExpert->generate($prompt, $progData);
            case 'html':
                return $this->htmlExpert->generate($prompt, $progData);
            case 'css':
                return $this->cssExpert->generate($prompt, $progData);
            case 'python':
                return $this->pythonExpert->generate($prompt, $progData);
            case 'java':
                return $this->javaExpert->generate($prompt, $progData);
            default:
                return $this->phpExpert->synthesize($prompt, $progData); // Fallback
        }
    }

    /**
     * Mock execution/validation logic
     */
    public function validateSnippet(string $code, string $lang = 'php'): bool {
        // Logic to use nikic/PHP-Parser to check for syntax errors
        return true;
    }
}
