<?php

namespace NumPHP\Utils;

use NumPHP\Core\NDArray;

class Empty
{
    public static function empty(...$args)
    {
        return \NumPHP\NumPHP::empty(...$args);
    }
}
