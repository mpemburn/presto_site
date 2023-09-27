<?php

namespace App\Helpers;
class WordPressHelper
{
    public static function getPrefix(string $wpPath, string $default): string
    {
        $name = $default;

        collect(FileHelper::getContentsArray($wpPath . '/wp-config.php'))
            ->each(function ($line) use (&$name) {
                if (stripos($line, '$table_prefix') !== false) {
                    $name = trim(preg_replace(
                        '/(\$table_prefix )(.*)(\'|")([\w_]+)(\'|\")(;)/',
                        '$4',
                        $line
                    ));
                }
            });

        return $name;
    }

    public static function getThemesArray(string $wpThemePath): array
    {
        return collect(FileHelper::getDirectories($wpThemePath))
            ->map(function ($theme) use ($wpThemePath) {
            return ['name' => $theme, 'label' => self::getThemeName($wpThemePath . '/' . $theme)];
        })->sortBy('label')->toArray();
    }

    protected static function getThemeName(string $wpThemePath): string
    {
        $name = '';
        $styleSheetPath = $wpThemePath . '/style.css';

        collect(FileHelper::getContentsArray($styleSheetPath))
            ->each(function ($line) use (&$name) {
                if (stripos($line, 'Theme Name:') !== false) {
                    $name = trim(preg_replace('/(.*)(Theme Name:)/', '', $line));
                }
            });

        return $name;
    }
}
