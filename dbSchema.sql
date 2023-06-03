-- database osol_mvc
DROP TABLE IF EXISTS `osol_mvc_php_sessions`;
CREATE TABLE IF NOT EXISTS `osol_mvc_php_sessions` (
      `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
      `access` datetime NOT NULL,
      `data` text COLLATE utf8_unicode_ci NOT NULL,
      `cookie_start_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
	  `user_id` bigint(20) NOT NULL,
	  `device` varchar(255) NOT NULL,
      UNIQUE KEY id (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- ALTER TABLE `osol_mvc_user` ADD `active` TINYINT NOT NULL AFTER `id`; 	
DROP TABLE IF EXISTS `osol_mvc_user`;
CREATE TABLE IF NOT EXISTS `osol_mvc_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `active` tinyint(4) NOT NULL  DEFAULT '1',
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ACL TABLES
DROP TABLE IF EXISTS `osol_mvc_acl_user_permissions`;
CREATE TABLE IF NOT EXISTS `osol_mvc_acl_user_permissions` (
      `permission_id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `permission_name` varchar(255) NOT NULL,
	  PRIMARY KEY (`permission_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO `osol_mvc_acl_user_permissions` ( `permission_name`) VALUES
( 'admin.core.view'),
( 'admin.core.add'),
( 'admin.core.publish'),
( 'admin.core.edit'),
( 'admin.core.delete');
DROP TABLE IF EXISTS `osol_mvc_acl_user_groups`;	
CREATE TABLE IF NOT EXISTS `osol_mvc_acl_user_groups` (
      `group_id` bigint(20) NOT NULL AUTO_INCREMENT,
      `parent_group_id` bigint(20) NOT NULL,
	  `group_name` varchar(255) NOT NULL,
	  PRIMARY KEY (`group_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO `osol_mvc_acl_user_groups` (`parent_group_id`, `group_name`) VALUES
( 0, 'Registered User'),
( 0, 'Special User Level 1'),
( 2, 'Special User Level 2'),
( 0, 'Admin Level 1'),
( 4, 'Admin Level 2');	
DROP TABLE IF EXISTS `osol_mvc_acl_group_permissions`;	
CREATE TABLE IF NOT EXISTS `osol_mvc_acl_group_permissions` (
      `group_permission_id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `group_id` bigint(20) NOT NULL,
	  `permission_id` bigint(20) NOT NULL,
	  PRIMARY KEY (`group_permission_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
insert into `osol_mvc_acl_group_permissions` (`group_id`,`permission_id`) VALUES
(4,1),
(4,2),
(4,3),
(4,4),
(4,5);	
DROP TABLE IF EXISTS `osol_mvc_acl_group_declined_permissions`;	
CREATE TABLE IF NOT EXISTS `osol_mvc_acl_group_declined_permissions` (
      `group_declined_permission_id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `group_id` bigint(20) NOT NULL,
	  `permission_id` bigint(20) NOT NULL,
	  PRIMARY KEY (`group_declined_permission_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;	
INSERT INTO `osol_mvc_acl_group_declined_permissions` ( `group_id`, `permission_id`) VALUES
( 5, 3),
( 5, 4),
( 5, 5);	
DROP TABLE IF EXISTS `osol_mvc_acl_user_declined_permissions`;	
CREATE TABLE IF NOT EXISTS `osol_mvc_acl_user_declined_permissions` (
      `user_declined_permission_id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `user_id` bigint(20) NOT NULL,
	  `permission_id` bigint(20) NOT NULL,
	  PRIMARY KEY (`user_declined_permission_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
	
	
DROP TABLE IF EXISTS `osol_mvc_acl_user_2_group`;	
CREATE TABLE IF NOT EXISTS `osol_mvc_acl_user_2_group` (
      `user_2_group_id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `user_id` bigint(20) NOT NULL,
	  `group_id` bigint(20) NOT NULL,
	  PRIMARY KEY (`user_2_group_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;	
-- insert into `osol_mvc_acl_user_2_group` (`user_id`,`group_id`) VALUES (1,4);