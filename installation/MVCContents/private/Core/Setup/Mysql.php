<?php
$this->queries[] = "CREATE TABLE IF NOT EXISTS `{$this->tablePrefix}php_sessions` (
					  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
					  `user_id` bigint(20) NOT NULL,
					  `access` datetime NOT NULL,
					  `data` text COLLATE utf8_unicode_ci NOT NULL,
					  `cookie_start_time` datetime NOT NULL,
					  `device` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
					  UNIQUE KEY `id` (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$this->queries[] = "CREATE TABLE IF NOT EXISTS `{$this->tablePrefix}user` (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `email` varchar(255) NOT NULL,
					  `first_name` varchar(255) NOT NULL,
					  `last_name` varchar(255) NOT NULL,
					  `gender` varchar(2) DEFAULT '',
					  `picture` text NOT NULL,
					  `DOB` date DEFAULT NULL,
					  `address1` varchar(255) DEFAULT NULL,
					  `address2` varchar(255) DEFAULT NULL,
					  `city` varchar(255) DEFAULT NULL,
					  `state` varchar(255) DEFAULT NULL,
					  `country` int(11) DEFAULT NULL,
					  `zip` varchar(20) DEFAULT NULL,
					  `date_joined` datetime NOT NULL,
					  `last_visited` datetime NOT NULL DEFAULT '1970-01-02 00:00:00',
					  `refresh_token` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `unique_email` (`email`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";
//$this->queries[] = "";
?>