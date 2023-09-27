<?php

namespace App\Sql;
class PostCreate extends TableCreate
{
    protected static function statement(string $table): string
    {
        return "CREATE TABLE `{$table}` (
          `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
          `post_author` bigint unsigned NOT NULL DEFAULT '0',
          `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `post_date_gmt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `post_content` longtext NOT NULL,
          `post_title` text NOT NULL,
          `post_excerpt` text NOT NULL,
          `post_status` varchar(20) NOT NULL DEFAULT 'publish',
          `comment_status` varchar(20) NOT NULL DEFAULT 'open',
          `ping_status` varchar(20) NOT NULL DEFAULT 'open',
          `post_password` varchar(255) NOT NULL DEFAULT '',
          `post_name` varchar(200) NOT NULL DEFAULT '',
          `to_ping` text NOT NULL,
          `pinged` text NOT NULL,
          `post_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `post_modified_gmt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `post_content_filtered` longtext NOT NULL,
          `post_parent` bigint unsigned NOT NULL DEFAULT '0',
          `guid` varchar(255) NOT NULL DEFAULT '',
          `menu_order` int NOT NULL DEFAULT '0',
          `post_type` varchar(20) NOT NULL DEFAULT 'post',
          `post_mime_type` varchar(100) NOT NULL DEFAULT '',
          `comment_count` bigint NOT NULL DEFAULT '0',
          PRIMARY KEY (`ID`),
          KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
          KEY `post_parent` (`post_parent`),
          KEY `post_author` (`post_author`),
          KEY `post_name` (`post_name`(191))
        ) ENGINE=InnoDB AUTO_INCREMENT=2120 DEFAULT CHARSET=utf8mb3;";
    }
}
