-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `rb_restaurant_booking_bookings`;
CREATE TABLE `rb_restaurant_booking_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` int(10) unsigned DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  `dt_to` datetime DEFAULT NULL,
  `people` smallint(5) unsigned DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `total` decimal(9,2) unsigned DEFAULT NULL,
  `payment_method` enum('paypal','authorize','creditcard','cash') DEFAULT NULL,
  `is_paid` enum('total','none') DEFAULT 'none',
  `status` enum('complete','confirmed','cancelled','pending','enquiry') DEFAULT 'pending',
  `txn_id` varchar(255) DEFAULT NULL,
  `processed_on` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `c_title` varchar(255) DEFAULT NULL,
  `c_fname` varchar(255) DEFAULT NULL,
  `c_lname` varchar(255) DEFAULT NULL,
  `c_phone` varchar(255) DEFAULT NULL,
  `c_email` varchar(255) DEFAULT NULL,
  `c_company` varchar(255) DEFAULT NULL,
  `c_notes` text,
  `c_address` varchar(255) DEFAULT NULL,
  `c_city` varchar(255) DEFAULT NULL,
  `c_state` varchar(255) DEFAULT NULL,
  `c_zip` varchar(255) DEFAULT NULL,
  `c_country` int(10) unsigned DEFAULT NULL,
  `cc_type` varchar(255) DEFAULT NULL,
  `cc_num` varchar(255) DEFAULT NULL,
  `cc_exp` varchar(255) DEFAULT NULL,
  `cc_code` varchar(255) DEFAULT NULL,
  `reminder_email` tinyint(1) unsigned DEFAULT '0',
  `reminder_sms` tinyint(1) unsigned DEFAULT '0',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_bookings_menu`;
CREATE TABLE `rb_restaurant_booking_bookings_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `rb_restaurant_booking_bookings_tables`;
CREATE TABLE `rb_restaurant_booking_bookings_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) unsigned DEFAULT NULL,
  `table_id` int(10) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`,`table_id`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_bookings_tables_group`;
CREATE TABLE `rb_restaurant_booking_bookings_tables_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `tables_group_id` int(11) NOT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `rb_restaurant_booking_countries`;
CREATE TABLE `rb_restaurant_booking_countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_title` varchar(255) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_dates`;
CREATE TABLE `rb_restaurant_booking_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_dayoff` enum('T','F') DEFAULT 'F',
  `message` text,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`, `owner_id`),
  KEY `is_dayoff` (`is_dayoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_formstyle`;
CREATE TABLE `rb_restaurant_booking_formstyle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `background` varchar(250) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_menu`;
CREATE TABLE `rb_restaurant_booking_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `m_name` varchar(254) CHARACTER SET utf8 NOT NULL,
  `m_type` enum('starters','main_course','desert') CHARACTER SET utf8 NOT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `rb_restaurant_booking_options`;
CREATE TABLE `rb_restaurant_booking_options` (
  `key` varchar(255) NOT NULL DEFAULT '',
  `tab_id` tinyint(3) unsigned DEFAULT NULL,
  `group` enum('borders','colors','fonts','sizes') DEFAULT NULL,
  `value` text,
  `description` text,
  `label` text,
  `type` enum('string','text','int','float','enum','color','bool') DEFAULT 'string',
  `order` int(10) unsigned DEFAULT NULL,
  `style` varchar(255) DEFAULT NULL,
  `is_visible` tinyint(1) unsigned DEFAULT '1',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`key`, `owner_id`),
  KEY `tab_id` (`tab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_roles`;
CREATE TABLE `rb_restaurant_booking_roles` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_service`;
CREATE TABLE `rb_restaurant_booking_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `s_name` varchar(254) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `s_length` smallint(6) DEFAULT NULL,
  `s_price` int(11) NOT NULL,
  `s_seats` smallint(6) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_tables`;
CREATE TABLE `rb_restaurant_booking_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `width` smallint(5) unsigned DEFAULT NULL,
  `height` smallint(5) unsigned DEFAULT NULL,
  `top` smallint(5) unsigned DEFAULT NULL,
  `left` smallint(5) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `seats` smallint(5) unsigned DEFAULT NULL,
  `minimum` smallint(5) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_tables_group`;
CREATE TABLE `rb_restaurant_booking_tables_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) NOT NULL,
  `tables_id` varchar(250) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `rb_restaurant_booking_template`;
CREATE TABLE `rb_restaurant_booking_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `subject` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `message` text CHARACTER SET utf8,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `rb_restaurant_booking_users`;
CREATE TABLE `rb_restaurant_booking_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('T','F') DEFAULT 'T',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`, `owner_id`),
  KEY `role_id` (`role_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_vouchers`;
CREATE TABLE `rb_restaurant_booking_vouchers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `type` enum('amount','percent') DEFAULT NULL,
  `discount` decimal(9,2) unsigned DEFAULT NULL,
  `valid` enum('fixed','period','recurring') DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `every` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rb_restaurant_booking_working_times`;
CREATE TABLE `rb_restaurant_booking_working_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `monday_from` time DEFAULT NULL,
  `monday_to` time DEFAULT NULL,
  `monday_dayoff` enum('T','F') DEFAULT 'F',
  `tuesday_from` time DEFAULT NULL,
  `tuesday_to` time DEFAULT NULL,
  `tuesday_dayoff` enum('T','F') DEFAULT 'F',
  `wednesday_from` time DEFAULT NULL,
  `wednesday_to` time DEFAULT NULL,
  `wednesday_dayoff` enum('T','F') DEFAULT 'F',
  `thursday_from` time DEFAULT NULL,
  `thursday_to` time DEFAULT NULL,
  `thursday_dayoff` enum('T','F') DEFAULT 'F',
  `friday_from` time DEFAULT NULL,
  `friday_to` time DEFAULT NULL,
  `friday_dayoff` enum('T','F') DEFAULT 'F',
  `saturday_from` time DEFAULT NULL,
  `saturday_to` time DEFAULT NULL,
  `saturday_dayoff` enum('T','F') DEFAULT 'F',
  `sunday_from` time DEFAULT NULL,
  `sunday_to` time DEFAULT NULL,
  `sunday_dayoff` enum('T','F') DEFAULT 'F',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2014-08-02 14:32:30
