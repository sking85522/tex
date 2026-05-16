<?php

namespace NumPHP\String;

use NumPHP\Core\NDArray;

class Rfind
{
    /**
     * For each element in a, return the highest index in the string where substring sub is found.
     * Return -1 on failure.
     *
     * @param NDArray $a
     * @param string $sub
     * @param int $offset
     * @return NDArray
     */
    public static function rfind(NDArray $a, string $sub, int $offset = 0): NDArray
    {
        $data = $a->getData();
        $recursive_map = function ($item) use ($sub, $offset, &$recursive_map) {
            if (is_array($item)) {
                return array_map($recursive_map, $item);
            }
            $result = strrpos($item, $sub, $offset);
            return $result === false ? -1 : $result;
        };
        return new NDArray($recursive_map($data), 'int');
    }
}
