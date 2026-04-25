<?php
namespace Core\ProLang\Web\HTML;

/**
 * HTMLExpert
 * Specialized in semantic and professional HTML5 structures.
 */
class HTMLExpert {
    private \Core\ProLang\CSTSynthesizer $cst;

    public function __construct(\Core\ProLang\CSTSynthesizer $cst) {
        $this->cst = $cst;
    }

    public function generate(string $request, array $progData = []): string {
        $structures = $progData['structures'] ?? [];
        $variables = $progData['variables'] ?? [];
        $name = !empty($variables) ? ucfirst($variables[0]) : 'Dashboard';

        if (in_array('ui', $structures) || str_contains(strtolower($request), 'login') || str_contains(strtolower($request), 'form')) {
            $code = <<<HTML
<div class="{$name}-container">
    <h2>{$name}</h2>
    <form id="{$name}Form">
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary">Submit</button>
    </form>
</div>
HTML;
            return "Sir, maine aapke liye ek clean UI form structure banaya hai:\n" . $this->cst->formatCodeBlock($code, 'html');
        }

        if (str_contains(strtolower($request), 'dashboard') || str_contains(strtolower($request), 'layout')) {
            $code = <<<HTML
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="logo">Hritik AI</div>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#analytics">Analytics</a></li>
                <li><a href="#settings">Settings</a></li>
            </ul>
        </nav>
    </aside>
    <main class="content-area">
        <header>
            <h1>Overview</h1>
        </header>
        <div class="widgets-grid">
            <div class="widget">Widget 1</div>
            <div class="widget">Widget 2</div>
            <div class="widget">Widget 3</div>
        </div>
    </main>
</div>
HTML;
            return "Sir, ye raha modern dashboard layout ka HTML structure:\n" . $this->cst->formatCodeBlock($code, 'html');
        }

        $code = "<!DOCTYPE html>\n<html lang='en'>\n<head>\n    <meta charset='UTF-8'>\n    <title>{$name}</title>\n</head>\n<body>\n    <section class='main'>\n        <h1>{$name} Synthesized by Hritik AI</h1>\n    </section>\n</body>\n</html>";
        return "Sir, aapka basic HTML5 boilerplate ready hai:\n" . $this->cst->formatCodeBlock($code, 'html');
    }
}
