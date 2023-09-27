<?php

namespace App\Sql;

use Illuminate\Support\Facades\DB;

abstract class TableCreate
{
    abstract protected static function statement(string $table): string;

    public static function make(string $table): void
    {
        $sql = static::statement($table);

        DB::statement($sql);
        // Make sure increment counter is reset
        DB::statement("TRUNCATE TABLE {$table};");
    }
}
