<?php
namespace Core\Tools\Intelligence;

use MathPHP\Algebra;

/**
 * MathSolverTool
 * Performs complex algebraic, statistical, and numerical analysis.
 */
class MathSolverTool {
    
    public function run($params = []) {
        $prompt = $params['prompt'] ?? '';
        
        // Example: Finding GCD of numbers in prompt
        if (preg_match_all('/\d+/', $prompt, $matches)) {
            $numbers = array_map('intval', $matches[0]);
            if (count($numbers) >= 2) {
                // Using MathPHP Algebra class (cloned from GitHub)
                try {
                    $gcd = \MathPHP\Algebra::gcd(...$numbers);
                    $lcm = \MathPHP\Algebra::lcm(...$numbers);
                    return [
                        'numbers' => $numbers,
                        'gcd' => $gcd,
                        'lcm' => $lcm,
                        'message' => "Sir, maine Math-PHP engine se calculations kar li hain. In numbers ka GCD {$gcd} aur LCM {$lcm} hai."
                    ];
                } catch (\Exception $e) {
                    return "Math Engine Error: " . $e->getMessage();
                }
            }
        }

        return "Sir, please complex numbers ya algebraic expression provide karein taaki main Math-PHP engine activate kar sakoon.";
    }
}
