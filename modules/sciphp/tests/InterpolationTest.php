<?php

namespace SciPHP\Tests;

use PHPUnit\Framework\TestCase;
use SciPHP\Interpolation\Linear;

class InterpolationTest extends TestCase
{
    public function testInterp1dBoundsSingle()
    {
        $x = [0, 1, 2, 3];
        $y = [0, 2, 4, 6];

        $interp = Linear::interp1d($x, $y);

        // Exact lower bound
        $this->assertEquals(0, $interp(0));

        // Below lower bound
        $this->assertEquals(0, $interp(-1));
        $this->assertEquals(0, $interp(-100));

        // Exact upper bound
        $this->assertEquals(6, $interp(3));

        // Above upper bound
        $this->assertEquals(6, $interp(4));
        $this->assertEquals(6, $interp(100));
    }

    public function testInterp1dBoundsArray()
    {
        $x = [0, 1, 2, 3];
        $y = [0, 2, 4, 6];

        $interp = Linear::interp1d($x, $y);

        $x_new = [-1, 0, 3, 4];
        $result = $interp($x_new);

        // Result is an NDArray, we should extract data to assert
        $expected = [0, 0, 6, 6];
        $this->assertEquals($expected, $result->getData());
    }
}
