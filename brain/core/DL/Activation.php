<?php
namespace Core\DL;

class Activation {
    
    public static function sigmoid(float $x): float {
        return 1 / (1 + exp(-$x));
    }

    public static function sigmoidDerivative(float $x): float {
        $s = self::sigmoid($x);
        return $s * (1 - $s);
    }

    public static function relu(float $x): float {
        return max(0, $x);
    }

    public static function reluDerivative(float $x): float {
        return $x > 0 ? 1 : 0;
    }

    public static function tanh(float $x): float {
        return tanh($x);
    }

    public static function tanhDerivative(float $x): float {
        return 1 - pow(tanh($x), 2);
    }
}
