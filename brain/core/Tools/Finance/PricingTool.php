<?php
namespace Core\Tools\Finance;

class PricingTool
{
    public function calculate(string $query): string
    {
        $query = strtolower($query);
        $complexity = 1.0;
        
        if (strpos($query, 'ecommerce') !== false) $complexity += 3.0;
        if (strpos($query, 'mobile') !== false || strpos($query, 'app') !== false) $complexity += 2.5;
        if (strpos($query, 'ai') !== false || strpos($query, 'neural') !== false) $complexity += 4.5;
        if (strpos($query, 'enterprise') !== false) $complexity += 5.0;
        
        $baseRate = 12000;
        return number_format($baseRate * $complexity, 0);
    }
}
