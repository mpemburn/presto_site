<?php

namespace App\Helpers;

use RecursiveDirectoryIterator;

class FileHelper
{
    public static function getDirectories(string $path): array
    {
        $subdirs = [];

        $iterator = new RecursiveDirectoryIterator($path);
        collect($iterator)->each(function ($file) use (&$subdirs) {
            if ($file->isDir()) {
                $name = $file->getFilename();
                if ($name !== '.' && $name !== '..') {
                    $subdirs[] = $name;
                }
            }
        });

        return $subdirs;
    }

    public static function getFileContents(string $filepath): ?string
    {
        if (file_exists($filepath)) {
            return file_get_contents($filepath);
        }

        return null;
    }

    public static function getContentsArray(string $filepath): array
    {
        $contents = static::getFileContents($filepath);
        if ($contents) {
            return explode("\n", $contents);
        }

        return [];
    }
}
