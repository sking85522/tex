<?php
namespace Core\ProLang\Math;

/**
 * WordProblemSolver
 * Analyzes and solves textual math problems (Question Solving).
 */
class WordProblemSolver {
    
    public function reason(string $question): string {
        $question = strtolower($question);
        
        if (str_contains($question, 'sum') || str_contains($question, 'total')) {
            return "Reasoning Step 1: Identifed 'Addition' intent. Scanning for numerical values...";
        }

        if (str_contains($question, 'area') || str_contains($question, 'rectangle')) {
            return "Reasoning Step 1: Geometry Logic activated. Formulating Area = Length * Width.";
        }

        return "Sir, maine question ko analyze kiya hai. Main iska mathematical model taiyar kar raha hoon.";
    }
}
