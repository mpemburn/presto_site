<?php

namespace App\Helpers;
class SourceHelper
{
    public const SERVER_PAGE_EXTENSIONS = [
        'aspx',
        'asp',
        'cfm',
        'html',
        'htm',
        'jspx',
        'jsp',
        'php',
    ];

    public static function getIndexFile(string $testPath): ?string
    {
        $index = null;

        collect(self::SERVER_PAGE_EXTENSIONS)
            ->each(function ($ext) use ($testPath, &$index) {
                $found = CurlHelper::testUrl($testPath . '/index.' . $ext);
                if ($found) {
                    $index = 'index.' . $ext;
                }
            });

        return $index;
    }

    public static function getForwardPage(string $testPath, string $index): string
    {
        $forward = $index;

        collect(CurlHelper::getContentsAsArray($testPath . '/' . $index))
            ->each(function ($line) use (&$forward) {
                if (stripos($line, 'http-equiv="refresh"') !== false) {
                    $refreshContent = RegexHelper::extractAttribute('content',$line);

                    $forward = preg_replace('/(.*)(=)([\w \.]+)/', '$3', $refreshContent);
                }
            });

        return trim($forward);
    }

}
