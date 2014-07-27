-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `as_bookings`;
CREATE TABLE `as_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(12) DEFAULT NULL,
  `calendar_id` int(10) unsigned DEFAULT NULL,
  `booking_price` decimal(9,2) unsigned DEFAULT NULL,
  `booking_total` decimal(9,2) unsigned DEFAULT NULL,
  `booking_deposit` decimal(9,2) unsigned DEFAULT NULL,
  `booking_tax` decimal(9,2) unsigned DEFAULT NULL,
  `booking_status` enum('pending','confirmed','cancelled') DEFAULT NULL,
  `payment_method` enum('paypal','authorize','creditcard','bank') DEFAULT NULL,
  `c_name` varchar(255) DEFAULT NULL,
  `c_email` varchar(255) DEFAULT NULL,
  `c_phone` varchar(255) DEFAULT NULL,
  `c_country_id` int(10) unsigned DEFAULT NULL,
  `c_city` varchar(255) DEFAULT NULL,
  `c_state` varchar(255) DEFAULT NULL,
  `c_zip` varchar(255) DEFAULT NULL,
  `c_address_1` varchar(255) DEFAULT NULL,
  `c_address_2` varchar(255) DEFAULT NULL,
  `c_notes` text,
  `cc_type` varchar(255) DEFAULT NULL,
  `cc_num` varchar(255) DEFAULT NULL,
  `cc_exp_year` year(4) DEFAULT NULL,
  `cc_exp_month` varchar(2) DEFAULT NULL,
  `cc_code` varchar(255) DEFAULT NULL,
  `txn_id` varchar(255) DEFAULT NULL,
  `processed_on` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `locale_id` tinyint(3) unsigned DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_bookings_extra_service`;
CREATE TABLE `as_bookings_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `extra_id` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_bookings_services`;
CREATE TABLE `as_bookings_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tmp_hash` varchar(32) DEFAULT NULL,
  `booking_id` int(10) unsigned DEFAULT NULL,
  `service_id` int(10) unsigned DEFAULT NULL,
  `employee_id` int(10) unsigned DEFAULT NULL,
  `resources_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start` time DEFAULT NULL,
  `start_ts` int(10) unsigned DEFAULT NULL,
  `total` smallint(5) unsigned DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `reminder_email` tinyint(1) unsigned DEFAULT '0',
  `reminder_sms` tinyint(1) unsigned DEFAULT '0',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `tmp_hash` (`tmp_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_bookings_status`;
CREATE TABLE `as_bookings_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) unsigned DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `admin` smallint(6) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_calendars`;
CREATE TABLE `as_calendars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_custom_times`;
CREATE TABLE `as_custom_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `start_lunch` time DEFAULT NULL,
  `end_lunch` time DEFAULT NULL,
  `is_dayoff` enum('T','F') DEFAULT 'F',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `is_dayoff` (`is_dayoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_dates`;
CREATE TABLE `as_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `type` enum('calendar','employee') DEFAULT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `start_lunch` time DEFAULT NULL,
  `end_lunch` time DEFAULT NULL,
  `is_dayoff` enum('T','F') DEFAULT 'F',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_id` (`foreign_id`,`type`,`date`),
  KEY `is_dayoff` (`is_dayoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_employees`;
CREATE TABLE `as_employees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calendar_id` int(10) unsigned DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` blob,
  `phone` varchar(255) DEFAULT NULL,
  `notes` text,
  `avatar` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_subscribed` tinyint(1) unsigned DEFAULT '0',
  `is_subscribed_sms` tinyint(1) unsigned DEFAULT '0',
  `is_active` tinyint(1) unsigned DEFAULT '1',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_employees_custom_times`;
CREATE TABLE `as_employees_custom_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned DEFAULT NULL,
  `customtime_id` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_employees_freetime`;
CREATE TABLE `as_employees_freetime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `start_ts` int(11) DEFAULT NULL,
  `end_ts` int(11) DEFAULT NULL,
  `message` text,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_employees_services`;
CREATE TABLE `as_employees_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `plustime` smallint(6) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`,`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_extra_service`;
CREATE TABLE `as_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `message` text,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_fields`;
CREATE TABLE `as_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) DEFAULT NULL,
  `type` enum('backend','frontend','arrays') DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `source` enum('script','plugin') DEFAULT 'script',
  `modified` datetime DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_formstyle`;
CREATE TABLE `as_formstyle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logo` varchar(250) DEFAULT NULL,
  `banner` varchar(250) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `background` varchar(250) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  `message` text,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_multi_lang`;
CREATE TABLE `as_multi_lang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `locale` tinyint(3) unsigned DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `content` text,
  `source` enum('script','plugin','data') DEFAULT 'script',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_id` (`foreign_id`,`model`,`locale`,`field`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_options`;
CREATE TABLE `as_options` (
  `foreign_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) NOT NULL DEFAULT '',
  `tab_id` tinyint(3) unsigned DEFAULT NULL,
  `value` text,
  `label` text,
  `type` enum('string','text','int','float','enum','bool') NOT NULL DEFAULT 'string',
  `order` int(10) unsigned DEFAULT NULL,
  `is_visible` tinyint(1) unsigned DEFAULT '1',
  `style` varchar(500) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`foreign_id`,`key`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_country`;
CREATE TABLE `as_plugin_country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alpha_2` varchar(2) DEFAULT NULL,
  `alpha_3` varchar(3) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha_2` (`alpha_2`, `owner_id`),
  UNIQUE KEY `alpha_3` (`alpha_3`, `owner_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_invoice`;
CREATE TABLE `as_plugin_invoice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(12) DEFAULT NULL,
  `order_id` varchar(12) DEFAULT NULL,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `status` enum('not_paid','paid','cancelled') DEFAULT NULL,
  `txn_id` varchar(255) DEFAULT NULL,
  `processed_on` datetime DEFAULT NULL,
  `subtotal` decimal(9,2) unsigned DEFAULT NULL,
  `discount` decimal(9,2) unsigned DEFAULT NULL,
  `tax` decimal(9,2) unsigned DEFAULT NULL,
  `shipping` decimal(9,2) unsigned DEFAULT NULL,
  `total` decimal(9,2) unsigned DEFAULT NULL,
  `paid_deposit` decimal(9,2) unsigned DEFAULT NULL,
  `amount_due` decimal(9,2) unsigned DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `notes` text,
  `y_logo` varchar(255) DEFAULT NULL,
  `y_company` varchar(255) DEFAULT NULL,
  `y_name` varchar(255) DEFAULT NULL,
  `y_street_address` varchar(255) DEFAULT NULL,
  `y_city` varchar(255) DEFAULT NULL,
  `y_state` varchar(255) DEFAULT NULL,
  `y_zip` varchar(255) DEFAULT NULL,
  `y_phone` varchar(255) DEFAULT NULL,
  `y_fax` varchar(255) DEFAULT NULL,
  `y_email` varchar(255) DEFAULT NULL,
  `y_url` varchar(255) DEFAULT NULL,
  `b_billing_address` varchar(255) DEFAULT NULL,
  `b_company` varchar(255) DEFAULT NULL,
  `b_name` varchar(255) DEFAULT NULL,
  `b_address` varchar(255) DEFAULT NULL,
  `b_street_address` varchar(255) DEFAULT NULL,
  `b_city` varchar(255) DEFAULT NULL,
  `b_state` varchar(255) DEFAULT NULL,
  `b_zip` varchar(255) DEFAULT NULL,
  `b_phone` varchar(255) DEFAULT NULL,
  `b_fax` varchar(255) DEFAULT NULL,
  `b_email` varchar(255) DEFAULT NULL,
  `b_url` varchar(255) DEFAULT NULL,
  `s_shipping_address` varchar(255) DEFAULT NULL,
  `s_company` varchar(255) DEFAULT NULL,
  `s_name` varchar(255) DEFAULT NULL,
  `s_address` varchar(255) DEFAULT NULL,
  `s_street_address` varchar(255) DEFAULT NULL,
  `s_city` varchar(255) DEFAULT NULL,
  `s_state` varchar(255) DEFAULT NULL,
  `s_zip` varchar(255) DEFAULT NULL,
  `s_phone` varchar(255) DEFAULT NULL,
  `s_fax` varchar(255) DEFAULT NULL,
  `s_email` varchar(255) DEFAULT NULL,
  `s_url` varchar(255) DEFAULT NULL,
  `s_date` date DEFAULT NULL,
  `s_terms` text,
  `s_is_shipped` tinyint(1) unsigned DEFAULT '0',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `order_id` (`order_id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_invoice_config`;
CREATE TABLE `as_plugin_invoice_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `y_logo` varchar(255) DEFAULT NULL,
  `y_company` varchar(255) DEFAULT NULL,
  `y_name` varchar(255) DEFAULT NULL,
  `y_street_address` varchar(255) DEFAULT NULL,
  `y_city` varchar(255) DEFAULT NULL,
  `y_state` varchar(255) DEFAULT NULL,
  `y_zip` varchar(255) DEFAULT NULL,
  `y_phone` varchar(255) DEFAULT NULL,
  `y_fax` varchar(255) DEFAULT NULL,
  `y_email` varchar(255) DEFAULT NULL,
  `y_url` varchar(255) DEFAULT NULL,
  `y_template` text,
  `p_accept_payments` tinyint(1) unsigned DEFAULT '0',
  `p_accept_paypal` tinyint(1) unsigned DEFAULT '0',
  `p_accept_authorize` tinyint(1) unsigned DEFAULT '0',
  `p_accept_creditcard` tinyint(1) unsigned DEFAULT '0',
  `p_accept_bank` tinyint(1) unsigned DEFAULT '0',
  `p_paypal_address` varchar(255) DEFAULT NULL,
  `p_authorize_tz` varchar(255) DEFAULT NULL,
  `p_authorize_key` varchar(255) DEFAULT NULL,
  `p_authorize_mid` varchar(255) DEFAULT NULL,
  `p_authorize_hash` varchar(255) DEFAULT NULL,
  `p_bank_account` tinytext,
  `si_include` tinyint(1) unsigned DEFAULT '0',
  `si_shipping_address` tinyint(1) unsigned DEFAULT '0',
  `si_company` tinyint(1) unsigned DEFAULT '0',
  `si_name` tinyint(1) unsigned DEFAULT '0',
  `si_address` tinyint(1) unsigned DEFAULT '0',
  `si_street_address` tinyint(1) unsigned DEFAULT '0',
  `si_city` tinyint(1) unsigned DEFAULT '0',
  `si_state` tinyint(1) unsigned DEFAULT '0',
  `si_zip` tinyint(1) unsigned DEFAULT '0',
  `si_phone` tinyint(1) unsigned DEFAULT '0',
  `si_fax` tinyint(1) unsigned DEFAULT '0',
  `si_email` tinyint(1) unsigned DEFAULT '0',
  `si_url` tinyint(1) unsigned DEFAULT '0',
  `si_date` tinyint(1) unsigned DEFAULT '0',
  `si_terms` tinyint(1) unsigned DEFAULT '0',
  `si_is_shipped` tinyint(1) unsigned DEFAULT '0',
  `si_shipping` tinyint(1) unsigned DEFAULT '0',
  `o_booking_url` varchar(255) DEFAULT NULL,
  `o_qty_is_int` tinyint(1) unsigned DEFAULT '0',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_invoice_items`;
CREATE TABLE `as_plugin_invoice_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) unsigned DEFAULT NULL,
  `tmp` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` tinytext,
  `qty` decimal(9,2) unsigned DEFAULT NULL,
  `unit_price` decimal(9,2) unsigned DEFAULT NULL,
  `amount` decimal(9,2) unsigned DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_locale`;
CREATE TABLE `as_plugin_locale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_iso` varchar(2) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT NULL,
  `is_default` tinyint(1) unsigned DEFAULT '0',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_iso` (`language_iso`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_locale_languages`;
CREATE TABLE `as_plugin_locale_languages` (
  `iso` varchar(2) NOT NULL DEFAULT '',
  `title` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`iso`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_log`;
CREATE TABLE `as_plugin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `function` varchar(255) DEFAULT NULL,
  `value` text,
  `created` datetime DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_log_config`;
CREATE TABLE `as_plugin_log_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_one_admin`;
CREATE TABLE `as_plugin_one_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` blob,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_sms`;
CREATE TABLE `as_plugin_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_resources`;
CREATE TABLE `as_resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `message` text,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_resources_services`;
CREATE TABLE `as_resources_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resources_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`resources_id`,`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_roles`;
CREATE TABLE `as_roles` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_services`;
CREATE TABLE `as_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calendar_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  `before` smallint(5) unsigned DEFAULT NULL,
  `after` smallint(5) unsigned DEFAULT NULL,
  `total` smallint(5) unsigned DEFAULT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '1',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_services_category`;
CREATE TABLE `as_services_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `show_front` varchar(250) DEFAULT NULL,
  `message` text,
  `order` smallint(6) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_services_extra_service`;
CREATE TABLE `as_services_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `extra_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`extra_id`,`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_services_time`;
CREATE TABLE `as_services_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  `before` smallint(5) unsigned DEFAULT NULL,
  `after` smallint(5) unsigned DEFAULT NULL,
  `total` smallint(5) unsigned DEFAULT NULL,
  `description` text,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  KEY `calendar_id` (`foreign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_users`;
CREATE TABLE `as_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` blob,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  `is_active` enum('T','F') NOT NULL DEFAULT 'F',
  `ip` varchar(15) DEFAULT NULL,
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`, `owner_id`),
  KEY `role_id` (`role_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_working_times`;
CREATE TABLE `as_working_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `type` enum('calendar','employee') DEFAULT NULL,
  `monday_admin_from` time DEFAULT NULL,
  `monday_admin_to` time DEFAULT NULL,
  `monday_from` time DEFAULT NULL,
  `monday_to` time DEFAULT NULL,
  `monday_lunch_from` time DEFAULT NULL,
  `monday_lunch_to` time DEFAULT NULL,
  `monday_dayoff` enum('T','F') DEFAULT 'F',
  `tuesday_admin_from` time DEFAULT NULL,
  `tuesday_admin_to` time DEFAULT NULL,
  `tuesday_from` time DEFAULT NULL,
  `tuesday_to` time DEFAULT NULL,
  `tuesday_lunch_from` time DEFAULT NULL,
  `tuesday_lunch_to` time DEFAULT NULL,
  `tuesday_dayoff` enum('T','F') DEFAULT 'F',
  `wednesday_admin_from` time DEFAULT NULL,
  `wednesday_admin_to` time DEFAULT NULL,
  `wednesday_from` time DEFAULT NULL,
  `wednesday_to` time DEFAULT NULL,
  `wednesday_lunch_from` time DEFAULT NULL,
  `wednesday_lunch_to` time DEFAULT NULL,
  `wednesday_dayoff` enum('T','F') DEFAULT 'F',
  `thursday_admin_from` time DEFAULT NULL,
  `thursday_admin_to` time DEFAULT NULL,
  `thursday_from` time DEFAULT NULL,
  `thursday_to` time DEFAULT NULL,
  `thursday_lunch_from` time DEFAULT NULL,
  `thursday_lunch_to` time DEFAULT NULL,
  `thursday_dayoff` enum('T','F') DEFAULT 'F',
  `friday_admin_from` time DEFAULT NULL,
  `friday_admin_to` time DEFAULT NULL,
  `friday_from` time DEFAULT NULL,
  `friday_to` time DEFAULT NULL,
  `friday_lunch_from` time DEFAULT NULL,
  `friday_lunch_to` time DEFAULT NULL,
  `friday_dayoff` enum('T','F') DEFAULT 'F',
  `saturday_admin_from` time DEFAULT NULL,
  `saturday_admin_to` time DEFAULT NULL,
  `saturday_from` time DEFAULT NULL,
  `saturday_to` time DEFAULT NULL,
  `saturday_lunch_from` time DEFAULT NULL,
  `saturday_lunch_to` time DEFAULT NULL,
  `saturday_dayoff` enum('T','F') DEFAULT 'F',
  `sunday_admin_from` time DEFAULT NULL,
  `sunday_admin_to` time DEFAULT NULL,
  `sunday_from` time DEFAULT NULL,
  `sunday_to` time DEFAULT NULL,
  `sunday_lunch_from` time DEFAULT NULL,
  `sunday_lunch_to` time DEFAULT NULL,
  `sunday_dayoff` enum('T','F') DEFAULT 'F',
  `owner_id` int(8) NOT NULL,
  FOREIGN KEY fk_owner_id(owner_id) REFERENCES tbl_user_mast(nuser_id) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_id` (`foreign_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2014-07-26 13:21:44
