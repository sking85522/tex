<?php
namespace Core\GenerativeAI;

if (file_exists(dirname(__DIR__, 2) . '/modules/numphp/autoload.php')) {
    require_once dirname(__DIR__, 2) . '/modules/numphp/autoload.php';
}

use NumPHP\NumPHP;

class Transformers {
    /**
     * Simulates a Self-Attention mechanism for a given sentence.
     * Returns an attention matrix.
     */
    public function calculateAttention(string $sentence): array {
        $tokens = explode(' ', strtolower(trim($sentence)));
        $n = count($tokens);
        if ($n === 0) return [];

        // Randomly initialize Query, Key matrices for simulation
        $matrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                // Simplified Attention: Dot product similarity simulation
                // We're calculating score(i, j) = how much word i attends to word j
                $score = ($i === $j) ? 1.0 : (rand(1, 40) / 100.0);
                $matrix[$i][$j] = $score;
            }
        }

        // Softmax normalization over rows
        foreach ($matrix as &$row) {
            $sum = array_sum($row);
            foreach ($row as &$val) {
                $val = round($val / $sum, 4);
            }
        }

        return [
            'tokens' => $tokens,
            'matrix' => $matrix
        ];
    }
}
