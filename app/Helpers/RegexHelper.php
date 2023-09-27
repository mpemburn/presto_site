<?php

namespace App\Helpers;
class RegexHelper
{
    public static function extractAttribute(string $attribute, string $source): string
    {
        if (preg_match('/(' . $attribute . '=")([^">]+)(")/', $source, $matches)) {
            return $matches['2'];
        }

        return '';
    }
}
