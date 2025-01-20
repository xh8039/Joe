CREATE TABLE IF NOT EXISTS `prefix_orders` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`trade_no` TEXT NOT NULL UNIQUE,
	`api_trade_no` TEXT,
	`name` TEXT NOT NULL,
	`content_title` TEXT,
	`content_cid` INTEGER NOT NULL,
	`type` TEXT NOT NULL,
	`money` TEXT NOT NULL,
	`ip` TEXT,
	`user_id` TEXT NOT NULL,
	`create_time` TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`update_time` TEXT,
	`pay_type` TEXT,
	`pay_price` TEXT,
	`admin_email` INTEGER NOT NULL DEFAULT 0,
	`user_email` INTEGER NOT NULL DEFAULT 0,
	`status` INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS `prefix_friends` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`title` TEXT NOT NULL,
	`url` TEXT NOT NULL,
	`description` TEXT,
	`logo` TEXT,
	`rel` TEXT,
	`position` TEXT,
	`email` TEXT,
	`order` INTEGER NOT NULL DEFAULT 0,
	`status` INTEGER NOT NULL DEFAULT 0,
	`create_time` TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);