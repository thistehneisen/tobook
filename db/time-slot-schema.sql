-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `ts_booking_bookings`;
CREATE TABLE `ts_booking_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calendar_id` int(10) unsigned DEFAULT NULL,
  `booking_total` decimal(9,2) unsigned DEFAULT NULL,
  `booking_deposit` decimal(9,2) unsigned DEFAULT NULL,
  `booking_tax` decimal(9,2) unsigned DEFAULT NULL,
  `booking_status` enum('pending','confirmed','cancelled') DEFAULT NULL,
  `payment_method` enum('paypal','authorize','creditcard') DEFAULT NULL,
  `payment_option` enum('deposit','total') DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_country` int(10) unsigned DEFAULT NULL,
  `customer_city` varchar(255) DEFAULT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `customer_zip` varchar(255) DEFAULT NULL,
  `customer_notes` text,
  `cc_type` varchar(255) DEFAULT NULL,
  `cc_num` varchar(255) DEFAULT NULL,
  `cc_exp` varchar(255) DEFAULT NULL,
  `cc_code` varchar(255) DEFAULT NULL,
  `txn_id` varchar(255) DEFAULT NULL,
  `processed_on` datetime DEFAULT NULL,
  `reminder_email` tinyint(1) unsigned DEFAULT '0',
  `reminder_sms` tinyint(1) unsigned DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_bookings_slots`;
CREATE TABLE `ts_booking_bookings_slots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) unsigned NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `start_ts` int(10) unsigned DEFAULT NULL,
  `end_ts` int(10) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`,`booking_date`,`start_time`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_calendars`;
CREATE TABLE `ts_booking_calendars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `calendar_title` varchar(255) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_countries`;
CREATE TABLE `ts_booking_countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_title` varchar(255) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_dates`;
CREATE TABLE `ts_booking_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calendar_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `slot_length` smallint(5) unsigned DEFAULT NULL COMMENT 'Values in minutes',
  `slot_limit` smallint(5) unsigned DEFAULT '1' COMMENT 'Max bookings per slot',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `is_dayoff` enum('T','F') DEFAULT 'F',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `calendar_id` (`calendar_id`,`date`, `owner_id`),
  KEY `is_dayoff` (`is_dayoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_options`;
CREATE TABLE `ts_booking_options` (
  `calendar_id` int(10) unsigned NOT NULL,
  `key` varchar(255) NOT NULL DEFAULT '',
  `tab_id` tinyint(3) unsigned DEFAULT NULL,
  `group` enum('borders','colors','fonts','sizes') DEFAULT NULL,
  `value` text,
  `description` text,
  `label` text,
  `type` enum('string','text','int','float','enum','color') NOT NULL DEFAULT 'string',
  `order` int(10) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`calendar_id`,`key`),
  KEY `tab_id` (`tab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_prices`;
CREATE TABLE `ts_booking_prices` (
  `calendar_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time DEFAULT NULL,
  `start_ts` int(10) unsigned DEFAULT NULL,
  `end_ts` int(10) unsigned DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`calendar_id`,`date`,`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_prices_days`;
CREATE TABLE `ts_booking_prices_days` (
  `calendar_id` int(10) unsigned NOT NULL,
  `day` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`calendar_id`,`day`,`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_roles`;
CREATE TABLE `ts_booking_roles` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_users`;
CREATE TABLE `ts_booking_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`, `owner_id`),
  UNIQUE KEY `username` (`username`, `owner_id`),
  KEY `role_id` (`role_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ts_booking_working_times`;
CREATE TABLE `ts_booking_working_times` (
  `calendar_id` int(10) unsigned NOT NULL DEFAULT '0',
  `monday_from` time DEFAULT NULL,
  `monday_to` time DEFAULT NULL,
  `monday_price` decimal(9,2) unsigned DEFAULT NULL,
  `monday_limit` smallint(5) unsigned DEFAULT '1',
  `monday_length` smallint(5) unsigned DEFAULT '60',
  `monday_dayoff` enum('T','F') DEFAULT 'F',
  `tuesday_from` time DEFAULT NULL,
  `tuesday_to` time DEFAULT NULL,
  `tuesday_price` decimal(9,2) unsigned DEFAULT NULL,
  `tuesday_limit` smallint(5) unsigned DEFAULT '1',
  `tuesday_length` smallint(5) unsigned DEFAULT '60',
  `tuesday_dayoff` enum('T','F') DEFAULT 'F',
  `wednesday_from` time DEFAULT NULL,
  `wednesday_to` time DEFAULT NULL,
  `wednesday_price` decimal(9,2) unsigned DEFAULT NULL,
  `wednesday_limit` smallint(5) unsigned DEFAULT '1',
  `wednesday_length` smallint(5) unsigned DEFAULT '60',
  `wednesday_dayoff` enum('T','F') DEFAULT 'F',
  `thursday_from` time DEFAULT NULL,
  `thursday_to` time DEFAULT NULL,
  `thursday_price` decimal(9,2) unsigned DEFAULT NULL,
  `thursday_limit` smallint(5) unsigned DEFAULT '1',
  `thursday_length` smallint(5) unsigned DEFAULT '60',
  `thursday_dayoff` enum('T','F') DEFAULT 'F',
  `friday_from` time DEFAULT NULL,
  `friday_to` time DEFAULT NULL,
  `friday_price` decimal(9,2) unsigned DEFAULT NULL,
  `friday_limit` smallint(5) unsigned DEFAULT '1',
  `friday_length` smallint(5) unsigned DEFAULT '60',
  `friday_dayoff` enum('T','F') DEFAULT 'F',
  `saturday_from` time DEFAULT NULL,
  `saturday_to` time DEFAULT NULL,
  `saturday_price` decimal(9,2) unsigned DEFAULT NULL,
  `saturday_limit` smallint(5) unsigned DEFAULT '1',
  `saturday_length` smallint(5) unsigned DEFAULT '60',
  `saturday_dayoff` enum('T','F') DEFAULT 'F',
  `sunday_from` time DEFAULT NULL,
  `sunday_to` time DEFAULT NULL,
  `sunday_price` decimal(9,2) unsigned DEFAULT NULL,
  `sunday_limit` smallint(5) unsigned DEFAULT '1',
  `sunday_length` smallint(5) unsigned DEFAULT '60',
  `sunday_dayoff` enum('T','F') DEFAULT 'F',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2014-07-31 13:25:25
