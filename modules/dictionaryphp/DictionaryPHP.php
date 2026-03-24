<?php

namespace DictionaryPHP;

use DictionaryPHP\Dict\LocalDictionary;

class DictionaryPHP
{
    public static function meaning(string $word): ?array
    {
        return LocalDictionary::lookup($word);
    }

    public static function translateToHindi(string $word): ?string
    {
        return LocalDictionary::translateEnToHi($word);
    }
}
