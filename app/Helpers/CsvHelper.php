<?php

namespace App\Helpers;

class CsvHelper
{
    public static function read(string $path): array
    {
        $csv = array_map("str_getcsv", file($path, FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);

        foreach ($csv as $i => $row) {
            $csv[$i] = array_combine($keys, $row);
        }

        return $csv;
    }
}
