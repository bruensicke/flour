CREATE TABLE IF NOT EXISTS `flour_activities` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`type` VARCHAR(255) NOT NULL,
	`model` VARCHAR(120) DEFAULT NULL,
	`foreign_id` CHAR(36) DEFAULT NULL,
	`status` VARCHAR(40) NOT NULL,
	`message` TEXT NOT NULL,
	`tags` VARCHAR(255) NOT NULL,
	`data` TEXT NOT NULL,
	`created` DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS `flour_collection_items` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`collection_id` CHAR(36) NOT NULL,
	`type` VARCHAR(255) NOT NULL,
	`sequence` INT(7) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`val` TEXT NOT NULL,
	`status` INT(3) DEFAULT '1',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_collections` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`name` VARCHAR(255) NOT NULL,
	`status` INT(3) DEFAULT '0',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_configurations` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`collection_slug` VARCHAR(255) NOT NULL,
	`autoload` INT(3) NOT NULL DEFAULT '0',
	`type` VARCHAR(255) NOT NULL,
	`category` VARCHAR(255) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`val` TEXT NOT NULL,
	`status` INT(3) DEFAULT '0',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_content_objects` (
	`id` CHAR(36) NOT NULL,
	`title` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `flour_contents` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`type` VARCHAR(255) NOT NULL DEFAULT 'content',
	`model` VARCHAR(120) DEFAULT NULL,
	`foreign_id` CHAR(36) DEFAULT NULL,
	`status` INT(3) DEFAULT '0',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL,
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_meta_fields` (
	`id` CHAR(36) NOT NULL,
	`model` VARCHAR(120) NOT NULL,
	`foreign_id` CHAR(36) NOT NULL,
	`locale` VARCHAR(10) DEFAULT NULL,
	`name` VARCHAR(255) NOT NULL,
	`val` TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS `flour_tagged` (
	`id` VARCHAR(36) NOT NULL,
	`foreign_key` VARCHAR(36) NOT NULL,
	`tag_id` VARCHAR(36) NOT NULL,
	`model` VARCHAR(255) NOT NULL,
	`language` VARCHAR(6) DEFAULT NULL,
	`created` DATETIME DEFAULT NULL,
	`modified` DATETIME DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_tags` (
	`id` VARCHAR(36) NOT NULL,
	`identifier` VARCHAR(30) DEFAULT NULL,
	`name` VARCHAR(30) NOT NULL,
	`keyname` VARCHAR(30) NOT NULL,
	`weight` INT(2) NOT NULL DEFAULT '0',
	`created` DATETIME DEFAULT NULL,
	`modified` DATETIME DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_templates` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`type` VARCHAR(255) NOT NULL DEFAULT 'html',
	`status` INT(3) DEFAULT '0',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL,
	`content` TEXT DEFAULT NULL,
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_widgets` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`type` VARCHAR(255) NOT NULL DEFAULT 'html',
	`status` INT(3) DEFAULT '0',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL,
	`class` VARCHAR(255) NOT NULL DEFAULT '',
	`title` VARCHAR(255) DEFAULT NULL,
	`intro` TEXT DEFAULT NULL,
	`data` TEXT DEFAULT NULL,
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_navigation_items` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`navigation_id` CHAR(36) NOT NULL,
	`parent_id` CHAR(36) DEFAULT NULL,
	`lft` INT(7) DEFAULT NULL,
	`rght` INT(7) DEFAULT NULL,
	`type` VARCHAR(255) NOT NULL,
	`template` VARCHAR(255) DEFAULT NULL,
	`name` VARCHAR(255) NOT NULL,
	`class` VARCHAR(255) DEFAULT NULL,
	`url` TINYTEXT DEFAULT NULL,
	`status` INT(3) DEFAULT '1',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_navigations` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`type` VARCHAR(255) NOT NULL,
	`template` VARCHAR(255) DEFAULT NULL,
	`name` VARCHAR(255) NOT NULL,
	`class` VARCHAR(255) DEFAULT NULL,
	`status` INT(3) DEFAULT '0',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_users` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`template` VARCHAR(255) DEFAULT NULL,
	`status` INT(3) DEFAULT '0',
	`locale` VARCHAR(10) DEFAULT NULL,
	`name` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_accounts_users` (
	`id` CHAR(36) NOT NULL,
	`account_id` CHAR(36) DEFAULT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`role` VARCHAR(255) NOT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_accounts` (
	`id` CHAR(36) NOT NULL,
	`type` VARCHAR(255) NOT NULL,
	`status` INT(3) DEFAULT '0',
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL,
	`url` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `flour_login_tokens` (
	`id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`expires` DATETIME NOT NULL,
	`used` TINYINT(1) NOT NULL DEFAULT '0',
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL
);
