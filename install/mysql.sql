CREATE TABLE IF NOT EXISTS `prefix_orders` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`trade_no` varchar(64) NOT NULL unique,
	`api_trade_no` varchar(64) DEFAULT NULL,
	`name` varchar(64) NOT NULL,
	`content_title` varchar(150) DEFAULT NULL,
	`content_cid` INT NOT NULL,
	`type` varchar(10) NOT NULL,
	`money` varchar(32) NOT NULL,
	`ip` varchar(128) DEFAULT NULL,
	`user_id` varchar(32) NOT NULL,
	`create_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`update_time` DATETIME DEFAULT NULL,
	`pay_type` varchar(10) DEFAULT NULL,
	`pay_price` varchar(32) DEFAULT NULL,
	`admin_email` BOOLEAN NOT NULL DEFAULT FALSE,
	`user_email` BOOLEAN NOT NULL DEFAULT FALSE,
	`status` BOOLEAN NOT NULL DEFAULT FALSE,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `prefix_friends` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` varchar(128) NOT NULL,
	`url` varchar(255) NOT NULL,
	`description` TEXT DEFAULT NULL,
	`logo` TEXT DEFAULT NULL,
	`rel` varchar(128) DEFAULT NULL,
	`position` varchar(255) DEFAULT NULL,
	`email` varchar(64) DEFAULT NULL,
	`order` INT NOT NULL DEFAULT 0,
	`status` BOOLEAN NOT NULL DEFAULT FALSE,
	`create_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET = utf8mb4;