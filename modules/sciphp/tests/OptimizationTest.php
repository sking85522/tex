<?php

namespace SciPHP\Tests;

use PHPUnit\Framework\TestCase;
use SciPHP\Optimization\RootFinding;

require_once __DIR__ . '/../autoload.php';

class OptimizationTest extends TestCase
{
    public function testNewtonRaphsonMethod()
    {
        // f(x) = x^2 - 4
        $func = function ($x) {
            return $x ** 2 - 4;
        };

        // f'(x) = 2x
        $fprime = function ($x) {
            return 2 * $x;
        };

        // Root is at x = 2
        $root = RootFinding::newton($func, 3.0, $fprime);
        $this->assertEqualsWithDelta(2.0, $root, 1e-7);

        // Root is at x = -2
        $root = RootFinding::newton($func, -3.0, $fprime);
        $this->assertEqualsWithDelta(-2.0, $root, 1e-7);
    }

    public function testSecantMethod()
    {
        // f(x) = x^2 - 4
        $func = function ($x) {
            return $x ** 2 - 4;
        };

        // Root is at x = 2, using Secant (no fprime provided)
        $root = RootFinding::newton($func, 3.0);
        $this->assertEqualsWithDelta(2.0, $root, 1e-7);

        // Root is at x = -2
        $root = RootFinding::newton($func, -3.0);
        $this->assertEqualsWithDelta(-2.0, $root, 1e-7);
    }

    public function testNewtonStartsAtExactRoot()
    {
        $func = function ($x) {
            return $x ** 2 - 4;
        };

        $fprime = function ($x) {
            return 2 * $x;
        };

        $root = RootFinding::newton($func, 2.0, $fprime);
        $this->assertEquals(2.0, $root);

        $rootSecant = RootFinding::newton($func, 2.0);
        $this->assertEquals(2.0, $rootSecant);
    }

    public function testNewtonZeroDerivativeThrowsException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Derivative was zero.");

        // f(x) = x^2 - 4
        $func = function ($x) {
            return $x ** 2 - 4;
        };

        // f'(x) = 2x
        $fprime = function ($x) {
            return 2 * $x;
        };

        // Starting exactly at x=0 where f'(x)=0 should throw
        RootFinding::newton($func, 0.0, $fprime);
    }

    public function testNewtonFailsToConverge()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Failed to converge after 5 iterations.");

        $func = function ($x) {
            // Function that bounces around
            return $x ** 3 - 2 * $x + 2;
        };

        $fprime = function ($x) {
            return 3 * ($x ** 2) - 2;
        };

        // For this starting point it will fail to converge within 5 iterations
        RootFinding::newton($func, 0.0, $fprime, 1e-8, 5);
    }

    public function testSecantFailsToConverge()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Failed to converge after 5 iterations.");

        $func = function ($x) {
            return $x ** 3 - 2 * $x + 2;
        };

        RootFinding::newton($func, 0.0, null, 1e-8, 5);
    }
}
