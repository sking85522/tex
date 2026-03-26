<?php

namespace LanguagePHP;

use LanguagePHP\Detection\Detector;

class LanguagePHP
{
    public static function detect(string $text): array
    {
        return Detector::detect($text);
    }
}
