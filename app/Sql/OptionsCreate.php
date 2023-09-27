<?php

namespace App\Sql;
class OptionsCreate extends TableCreate
{
    protected static function statement(string $table): string
    {
        return "CREATE TABLE `{$table}` (
          `option_id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `option_name` varchar(191) DEFAULT NULL,
          `option_value` longtext NOT NULL,
          `autoload` varchar(20) NOT NULL DEFAULT 'yes',
          PRIMARY KEY (`option_id`),
          UNIQUE KEY `option_name` (`option_name`),
          KEY `autoload` (`autoload`)
        ) ENGINE=InnoDB AUTO_INCREMENT=494302 DEFAULT CHARSET=utf8mb3;";
    }
}
