<?php
namespace Core\ProLang\PHP;

/**
 * PHPSynthesizer
 * Generates professional PHP code structures based on semantic intent.
 */
class PHPSynthesizer {
    private \Core\ProLang\CSTSynthesizer $cst;

    public function __construct(\Core\ProLang\CSTSynthesizer $cst) {
        $this->cst = $cst;
    }

    /**
     * Synthesizes a PHP script from NLU parameters
     */
    public function synthesize(string $request, array $progData = []): string {
        $structures = $progData['structures'] ?? [];
        $variables = $progData['variables'] ?? [];
        $name = !empty($variables) ? $variables[0] : 'MyEntity';
        
        $code = "<?php\n\n";

        if (in_array('database', $structures) || in_array('crud', $structures)) {
            $table = !empty($variables) ? $variables[0] : 'items';
            $code .= $this->cst->buildDBConnection('php') . "\n\n";
            $code .= $this->cst->buildCRUD('php', $table);
            return "Sir, maine PHP Database Manager (CRUD) taiyar kar diya hai:\n" . $this->cst->formatCodeBlock($code, 'php');
        }

        if (str_contains(strtolower($request), 'login') || str_contains(strtolower($request), 'auth')) {
            $code .= $this->cst->buildAuth('php');
            return "Sir, ye raha PHP Authentication logic:\n" . $this->cst->formatCodeBlock($code, 'php');
        }

        if (in_array('class', $structures)) {
            $methods = array_slice($variables, 1);
            if (empty($methods)) $methods = ['process', 'validate'];
            $code .= $this->cst->buildClass('php', $name, $methods);
            return "Sir, aapka PHP class structure ready hai:\n" . $this->cst->formatCodeBlock($code, 'php');
        }

        if (in_array('function', $structures) || empty($structures)) {
            $params = array_slice($variables, 1);
            if (empty($params)) $params = ['data', 'options'];
            $code .= $this->cst->buildFunction('php', $name, $params);
            return "Sir, aapka PHP function ban gaya hai:\n" . $this->cst->formatCodeBlock($code, 'php');
        }

        return $this->cst->formatCodeBlock("<?php\n// Hritik PHP Engine\necho 'Hritik AI is ready to code PHP!';", 'php');
    }
}
