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
	`created` DATETIME NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_collection_items` (
	`id` CHAR(36) NOT NULL,
	`collection_id` CHAR(36) NOT NULL,
	`user_id` CHAR(36) DEFAULT NULL,
	`group_id` CHAR(36) DEFAULT NULL,
	`type` VARCHAR(255) NOT NULL,
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
	`deleted_by` CHAR(36) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
	`deleted_by` CHAR(36) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
	`deleted_by` CHAR(36) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_content_objects` (
	`id` CHAR(36) NOT NULL,
	`title` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_contents` (
	`id` CHAR(36) NOT NULL,
	`type` VARCHAR(255) NOT NULL DEFAULT 'content',
	`model` VARCHAR(120) DEFAULT NULL,
	`foreign_id` CHAR(36) DEFAULT NULL,
	`status` INT(3) DEFAULT '0',
	`locale` VARCHAR(10) DEFAULT NULL,
	`slug` VARCHAR(255) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL COMMENT 'internal name',
	`description` TINYTEXT,
	`tags` VARCHAR(255) NOT NULL,
	`valid_from` DATETIME DEFAULT NULL,
	`valid_to` DATETIME DEFAULT NULL,
	`created` DATETIME NOT NULL,
	`created_by` CHAR(36) NOT NULL,
	`modified` DATETIME DEFAULT NULL,
	`modified_by` CHAR(36) DEFAULT NULL,
	`deleted` DATETIME DEFAULT NULL,
	`deleted_by` CHAR(36) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_meta_fields` (
	`id` CHAR(36) NOT NULL,
	`model` VARCHAR(120) NOT NULL,
	`foreign_id` CHAR(36) NOT NULL,
	`locale` VARCHAR(10) DEFAULT NULL,
	`name` VARCHAR(255) NOT NULL,
	`val` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_model` (`model`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_tagged` (
	`id` VARCHAR(36) NOT NULL,
	`foreign_key` VARCHAR(36) NOT NULL,
	`tag_id` VARCHAR(36) NOT NULL,
	`model` VARCHAR(255) NOT NULL,
	`language` VARCHAR(6) DEFAULT NULL,
	`created` DATETIME DEFAULT NULL,
	`modified` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `UNIQUE_TAGGING` (`model`,`foreign_key`,`tag_id`,`language`),
	KEY `INDEX_TAGGED` (`model`),
	KEY `INDEX_LANGUAGE` (`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flour_tags` (
	`id` VARCHAR(36) NOT NULL,
	`identifier` VARCHAR(30) DEFAULT NULL,
	`name` VARCHAR(30) NOT NULL,
	`keyname` VARCHAR(30) NOT NULL,
	`weight` INT(2) NOT NULL DEFAULT '0',
	`created` DATETIME DEFAULT NULL,
	`modified` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `UNIQUE_TAG` (`identifier`,`keyname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
