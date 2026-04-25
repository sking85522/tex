<?php

namespace NumPHP\Types;

use NumPHP\Core\NDArray;

class Bool
{
    public static function bool(...$args)
    {
        return \NumPHP\NumPHP::bool(...$args);
    }
}
