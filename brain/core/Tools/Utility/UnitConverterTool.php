<?php
namespace Core\Tools\Utility;

/**
 * UnitConverterTool
 * Handles conversions between various units (Weight, Length, Temperature, Data).
 */
class UnitConverterTool {
    
    public function run($params = []) {
        $value = $params['value'] ?? 0;
        $from = strtolower($params['from'] ?? '');
        $to = strtolower($params['to'] ?? '');

        // Distance Logic
        if ($from === 'km' && $to === 'miles') return $value * 0.621371;
        if ($from === 'miles' && $to === 'km') return $value * 1.60934;

        // Temperature Logic
        if ($from === 'c' && $to === 'f') return ($value * 9/5) + 32;
        if ($from === 'f' && $to === 'c') return ($value - 32) * 5/9;

        // Data Logic
        if ($from === 'mb' && $to === 'gb') return $value / 1024;
        if ($from === 'gb' && $to === 'mb') return $value * 1024;

        return "Conversion for '{$from}' to '{$to}' is not yet optimized. Check back soon!";
    }
}
