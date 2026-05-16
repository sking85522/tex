<?php

namespace SciPHP\Tests\Optimization;

// The autoloader is at modules/autoload.php or modules/index.php
require_once __DIR__ . '/../../index.php';

use SciPHP\Optimization\Minimize;

class OptimizationTest
{
    private int $passed = 0;
    private int $failed = 0;

    public function run(): void
    {
        echo "Running Optimization Tests...\n";

        $this->testSingleVariableQuadratic();
        $this->testMultiVariableQuadratic();
        $this->testMaxIterationsExceeded();

        echo "\nTest Results:\n";
        echo "Passed: {$this->passed}\n";
        echo "Failed: {$this->failed}\n";

        if ($this->failed > 0) {
            exit(1);
        }

        echo "✅ All tests passed!\n";
    }

    private function assertClose($actual, $expected, $tolerance = 1e-4, $message = ""): void
    {
        if (abs($actual - $expected) <= $tolerance) {
            $this->passed++;
        } else {
            $this->failed++;
            echo "❌ FAILED: $message\n";
            echo "   Expected close to $expected, got $actual\n";
        }
    }

    private function assertArrayClose(array $actual, array $expected, $tolerance = 1e-4, $message = ""): void
    {
        if (count($actual) !== count($expected)) {
            $this->failed++;
            echo "❌ FAILED: $message\n";
            echo "   Array lengths differ. Expected " . count($expected) . ", got " . count($actual) . "\n";
            return;
        }

        $all_close = true;
        foreach ($actual as $i => $val) {
            if (abs($val - $expected[$i]) > $tolerance) {
                $all_close = false;
                break;
            }
        }

        if ($all_close) {
            $this->passed++;
        } else {
            $this->failed++;
            echo "❌ FAILED: $message\n";
            echo "   Expected: [" . implode(', ', $expected) . "]\n";
            echo "   Got:      [" . implode(', ', $actual) . "]\n";
        }
    }

    private function assertTrue($condition, $message = ""): void
    {
        if ($condition) {
            $this->passed++;
        } else {
            $this->failed++;
            echo "❌ FAILED: $message\n";
        }
    }

    private function assertFalse($condition, $message = ""): void
    {
        if (!$condition) {
            $this->passed++;
        } else {
            $this->failed++;
            echo "❌ FAILED: $message\n";
        }
    }

    private function testSingleVariableQuadratic(): void
    {
        echo "Testing single-variable quadratic: f(x) = (x - 3)^2\n";
        $f = fn($x) => ($x[0] - 3)**2;
        $result = Minimize::gradient_descent($f, [0.0]);
        $this->assertTrue($result['success'], "Optimization should succeed for single-variable quadratic");
        $this->assertClose($result['x'][0], 3.0, 1e-3, "Minimum x should be approximately 3.0");
    }

    private function testMultiVariableQuadratic(): void
    {
        echo "Testing multi-variable quadratic: f(x, y) = (x - 2)^2 + (y + 1)^2\n";
        $f = fn($x) => ($x[0] - 2)**2 + ($x[1] + 1)**2;
        $result = Minimize::gradient_descent($f, [0.0, 0.0]);
        $this->assertTrue($result['success'], "Optimization should succeed for multi-variable quadratic");
        $this->assertArrayClose($result['x'], [2.0, -1.0], 1e-3, "Minimum x, y should be approximately [2.0, -1.0]");
    }

    private function testMaxIterationsExceeded(): void
    {
        echo "Testing max iterations exceeded\n";
        // Set learning rate too small and max_iter too small to reach the minimum
        $f = fn($x) => ($x[0] - 10)**2;
        $result = Minimize::gradient_descent($f, [0.0], 1e-4, 10);
        $this->assertFalse($result['success'], "Optimization should fail to converge within max_iter");
        $this->assertTrue($result['nit'] === 10, "Iterations should equal max_iter (10)");
        $this->assertTrue($result['message'] === 'Maximum number of iterations exceeded.', "Failure message should be correct");
    }
}

// Only execute if run directly
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $tester = new OptimizationTest();
    $tester->run();
}
