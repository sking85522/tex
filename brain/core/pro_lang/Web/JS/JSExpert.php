<?php
namespace Core\ProLang\Web\JS;

/**
 * JSExpert
 * Specialized in DOM manipulation and asynchronous logic.
 */
class JSExpert {
    private \Core\ProLang\CSTSynthesizer $cst;

    public function __construct(\Core\ProLang\CSTSynthesizer $cst) {
        $this->cst = $cst;
    }

    public function generate(string $request, array $progData = []): string {
        $structures = $progData['structures'] ?? [];
        $variables = $progData['variables'] ?? [];
        $name = !empty($variables) ? $variables[0] : 'myEntity';
        
        $code = "";

        if (in_array('database', $structures)) {
            $code = $this->cst->buildDBConnection('javascript');
            return "Sir, maine Node.js database connection script taiyar kar diya hai:\n" . $this->cst->formatCodeBlock($code, 'javascript');
        }

        if (in_array('class', $structures)) {
            $methods = array_slice($variables, 1);
            if (empty($methods)) $methods = ['init', 'render'];
            $code = $this->cst->buildClass('javascript', $name, $methods);
            return "Sir, aapka ES6 Class structure ready hai:\n" . $this->cst->formatCodeBlock($code, 'javascript');
        }

        if (in_array('api', $structures)) {
            $code = "async function fetch{$name}Data() {\n    try {\n        const response = await fetch('https://api.example.com/data');\n        const data = await response.json();\n        console.log(data);\n        return data;\n    } catch (error) {\n        console.error('Error fetching data:', error);\n    }\n}";
            return "Sir, maine async Fetch API script bana diya hai:\n" . $this->cst->formatCodeBlock($code, 'javascript');
        }

        if (in_array('ui', $structures) || str_contains($request, 'event') || str_contains($request, 'click')) {
            $code = "document.addEventListener('DOMContentLoaded', () => {\n    const button = document.getElementById('{$name}Btn');\n    if (button) {\n        button.addEventListener('click', (e) => {\n            console.log('Button clicked!');\n            // Add your logic here\n        });\n    }\n});";
            return "Sir, maine DOM Event Listener set kar diya hai:\n" . $this->cst->formatCodeBlock($code, 'javascript');
        }

        if (in_array('function', $structures) || empty($structures)) {
            $params = array_slice($variables, 1);
            if (empty($params)) $params = ['data', 'config'];
            $code = $this->cst->buildFunction('javascript', $name, $params);
            return "Sir, aapka JS function generate ho gaya hai:\n" . $this->cst->formatCodeBlock($code, 'javascript');
        }

        return $this->cst->formatCodeBlock("// Hritik Interactive Script\nconsole.log('Hritik AI Logic Activated.');", 'javascript');
    }
}
