<?php

namespace NumPHP\Types;

use NumPHP\Core\NDArray;

class Void
{
    public static function void(...$args)
    {
        return \NumPHP\NumPHP::void(...$args);
    }
}
