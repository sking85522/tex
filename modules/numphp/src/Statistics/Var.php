<?php

namespace NumPHP\Statistics;

use NumPHP\Core\NDArray;

class Var
{
    public static function var(NDArray $a, ?int $axis = null)
    {
        return Var_::var($a, $axis);
    }
}
