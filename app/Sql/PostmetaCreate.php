<?php

namespace App\Sql;
class PostmetaCreate extends TableCreate
{
    protected static function statement(string $table): string
    {
        return "CREATE TABLE `{$table}` (
              `meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
              `post_id` bigint unsigned NOT NULL DEFAULT '0',
              `meta_key` varchar(255) DEFAULT NULL,
              `meta_value` longtext,
              PRIMARY KEY (`meta_id`),
              KEY `post_id` (`post_id`),
              KEY `meta_key` (`meta_key`(191))
            ) ENGINE=InnoDB AUTO_INCREMENT=4818 DEFAULT CHARSET=utf8mb3;";
    }
}
