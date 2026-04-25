<?php

namespace NumPHP\Utils;

use NumPHP\Core\NDArray;

class Require
{
    public static function require(...$args)
    {
        return \NumPHP\NumPHP::require(...$args);
    }
}
