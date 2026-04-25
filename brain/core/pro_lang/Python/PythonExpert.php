<?php
namespace Core\ProLang\Python;

/**
 * PythonExpert
 * Specialized in Python scripts, data processing, and automation.
 */
class PythonExpert {
    private \Core\ProLang\CSTSynthesizer $cst;

    public function __construct(\Core\ProLang\CSTSynthesizer $cst) {
        $this->cst = $cst;
    }

    public function generate(string $request, array $progData = []): string {
        $structures = $progData['structures'] ?? [];
        $variables = $progData['variables'] ?? [];
        $name = !empty($variables) ? $variables[0] : 'my_entity';
        
        $code = "";

        if (in_array('database', $structures) || in_array('crud', $structures)) {
            $table = !empty($variables) ? $variables[0] : 'items';
            $code = $this->cst->buildDBConnection('python') . "\n\n";
            $code .= $this->cst->buildCRUD('python', $table);
            return "Sir, maine Python Database Manager (CRUD) taiyar kar diya hai:\n" . $this->cst->formatCodeBlock($code, 'python');
        }

        if (in_array('class', $structures)) {
            $methods = array_slice($variables, 1);
            if (empty($methods)) $methods = ['process_data', 'analyze'];
            $code = $this->cst->buildClass('python', $name, $methods);
            return "Sir, aapka Python OOP class structure ready hai:\n" . $this->cst->formatCodeBlock($code, 'python');
        }

        if (in_array('function', $structures) || empty($structures)) {
            $params = array_slice($variables, 1);
            if (empty($params)) $params = ['data_frame', 'options'];
            $code = $this->cst->buildFunction('python', $name, $params);
            return "Sir, aapka Python function generate ho gaya hai:\n" . $this->cst->formatCodeBlock($code, 'python');
        }

        return $this->cst->formatCodeBlock("# Hritik Python Engine\ndef main():\n    print('Hritik Python Expert is Online!')\n\nif __name__ == '__main__':\n    main()", 'python');
    }
}
