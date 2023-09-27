<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SqlHelper
{
    public static function insert($data, string $tableName): bool
    {
        $columns = collect(array_keys($data))->map(function ($column) {
            return '`' . $column . '`';
        })->implode(',');
        $values = array_values($data);
        $placeholders = implode(',', array_fill(0, count(array_keys($data)), '?'));

        $insertStub = "INSERT INTO {$tableName} ({$columns}) VALUES($placeholders);";

        return DB::insert($insertStub, $values);
    }
}
