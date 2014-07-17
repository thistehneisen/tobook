DROP TABLE IF EXISTS `salondoris_hey_appscheduler_bookings`;

CREATE TABLE `salondoris_hey_appscheduler_bookings` (
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_bookings_extra_service`;

CREATE TABLE `salondoris_hey_appscheduler_bookings_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `extra_id` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_bookings_services`;

CREATE TABLE `salondoris_hey_appscheduler_bookings_services` (
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
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `tmp_hash` (`tmp_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_bookings_status`;

CREATE TABLE `salondoris_hey_appscheduler_bookings_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) unsigned DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `admin` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_calendars`;

CREATE TABLE `salondoris_hey_appscheduler_calendars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_calendars` VALUES('1', '1');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_custom_times`;

CREATE TABLE `salondoris_hey_appscheduler_custom_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `start_lunch` time DEFAULT NULL,
  `end_lunch` time DEFAULT NULL,
  `is_dayoff` enum('T','F') DEFAULT 'F',
  PRIMARY KEY (`id`),
  KEY `is_dayoff` (`is_dayoff`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_custom_times` VALUES('1', 'Closed', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `salondoris_hey_appscheduler_custom_times` VALUES('2', 'Test 1', '10:00:00', '16:00:00', '00:00:00', '00:00:00', 'F');
INSERT INTO `salondoris_hey_appscheduler_custom_times` VALUES('3', 'Test 2', '09:00:00', '18:00:00', '12:00:00', '13:00:00', 'F');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_dates`;

CREATE TABLE `salondoris_hey_appscheduler_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `type` enum('calendar','employee') DEFAULT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `start_lunch` time DEFAULT NULL,
  `end_lunch` time DEFAULT NULL,
  `is_dayoff` enum('T','F') DEFAULT 'F',
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_id` (`foreign_id`,`type`,`date`),
  KEY `is_dayoff` (`is_dayoff`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('16', '', 'employee', '1970-01-01', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('51', '3', 'employee', '2014-07-28', '10:00:00', '16:00:00', '00:00:00', '00:00:00', 'F');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('52', '3', 'employee', '2014-07-29', '10:00:00', '16:00:00', '00:00:00', '00:00:00', 'F');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('53', '3', 'employee', '2014-07-30', '09:00:00', '18:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('54', '1', 'employee', '2014-06-02', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('57', '1', 'employee', '2014-05-26', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('58', '1', 'employee', '2014-05-27', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('59', '1', 'employee', '2014-05-28', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('60', '1', 'employee', '2014-05-29', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('61', '1', 'employee', '2014-05-30', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `salondoris_hey_appscheduler_dates` VALUES('62', '1', 'employee', '2014-05-31', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_employees`;

CREATE TABLE `salondoris_hey_appscheduler_employees` (
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_employees` VALUES('1', '1', 'employee1@gmail.com', 'ð—Ïè#a¼XðMÆ''Äž', '123456789', 'Employee description', '', '', '0', '0', '1');
INSERT INTO `salondoris_hey_appscheduler_employees` VALUES('2', '1', 'employee2@gmail.com', 'ð—Ïè#a¼XðMÆ''Äž', '123456789', 'Employee description', '', '', '0', '0', '1');
INSERT INTO `salondoris_hey_appscheduler_employees` VALUES('3', '1', 'employee3@gmail.com', 'ð—Ïè#a¼XðMÆ''Äž', '123456789', 'Employee description', '', '', '0', '0', '1');
INSERT INTO `salondoris_hey_appscheduler_employees` VALUES('4', '1', 'employee4@gmail.com', 'ð—Ïè#a¼XðMÆ''Äž', '123456789', 'Employee description', '', '', '0', '0', '1');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_employees_custom_times`;

CREATE TABLE `salondoris_hey_appscheduler_employees_custom_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned DEFAULT NULL,
  `customtime_id` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_employees_freetime`;

CREATE TABLE `salondoris_hey_appscheduler_employees_freetime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `start_ts` int(11) DEFAULT NULL,
  `end_ts` int(11) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_employees_services`;

CREATE TABLE `salondoris_hey_appscheduler_employees_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `plustime` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`,`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('203', '1', '5', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('205', '3', '5', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('206', '4', '5', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('207', '1', '6', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('209', '3', '6', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('210', '4', '6', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('215', '1', '8', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('217', '3', '8', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('218', '4', '8', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('219', '1', '7', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('221', '3', '7', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('222', '4', '7', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('224', '2', '5', '');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('253', '1', '1', '10');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('254', '2', '1', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('255', '3', '1', '0');
INSERT INTO `salondoris_hey_appscheduler_employees_services` VALUES('256', '4', '1', '0');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_extra_service`;

CREATE TABLE `salondoris_hey_appscheduler_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `message` text,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_extra_service` VALUES('12', 'Extra Service 1', 'Extra Service Message', '10.00', '20');
INSERT INTO `salondoris_hey_appscheduler_extra_service` VALUES('13', 'Extra Service 2', 'Extra Service Message', '20.00', '20');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_fields`;

CREATE TABLE `salondoris_hey_appscheduler_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) DEFAULT NULL,
  `type` enum('backend','frontend','arrays') DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `source` enum('script','plugin') DEFAULT 'script',
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2257 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('5', 'user', 'backend', 'Username', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('6', 'pass', 'backend', 'Password', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('7', 'email', 'backend', 'E-Mail', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('8', 'url', 'backend', 'URL', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('13', 'created', 'backend', 'Created', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('16', 'btnSave', 'backend', 'Save', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('17', 'btnReset', 'backend', 'Reset', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('18', 'addLocale', 'backend', 'Add language', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('22', 'menuLang', 'backend', 'Menu Multi lang', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('23', 'menuPlugins', 'backend', 'Menu Plugins', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('24', 'menuUsers', 'backend', 'Menu Users', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('25', 'menuOptions', 'backend', 'Menu Options', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('26', 'menuLogout', 'backend', 'Menu Logout', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('31', 'btnUpdate', 'backend', 'Update', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('36', 'lblChoose', 'backend', 'Choose', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('37', 'btnSearch', 'backend', 'Search', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('40', 'backend', 'backend', 'Backend titles', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('41', 'frontend', 'backend', 'Front-end titles', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('42', 'locales', 'backend', 'Languages', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('44', 'adminLogin', 'backend', 'Admin Login', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('45', 'btnLogin', 'backend', 'Login', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('47', 'menuDashboard', 'backend', 'Menu Dashboard', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('57', 'lblOptionList', 'backend', 'Option list', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('58', 'btnAdd', 'backend', 'Button Add', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('62', 'lblDelete', 'backend', 'Delete', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('65', 'lblType', 'backend', 'Type', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('66', 'lblName', 'backend', 'Name', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('67', 'lblRole', 'backend', 'Role', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('68', 'lblStatus', 'backend', 'Status', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('69', 'lblIsActive', 'backend', 'Is Active', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('70', 'lblUpdateUser', 'backend', 'Update user', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('71', 'lblAddUser', 'backend', 'Add user', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('72', 'lblValue', 'backend', 'Value', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('73', 'lblOption', 'backend', 'Option', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('74', 'lblDays', 'backend', 'Days', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('115', 'menuLocales', 'backend', 'Menu Languages', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('116', 'lblYes', 'backend', 'Yes', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('117', 'lblNo', 'backend', 'No', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('338', 'lblError', 'backend', 'Error', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('347', 'btnBack', 'backend', 'Button Back', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('355', 'btnCancel', 'backend', 'Button Cancel', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('356', 'lblForgot', 'backend', 'Forgot password', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('357', 'adminForgot', 'backend', 'Forgot password', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('358', 'btnSend', 'backend', 'Button Send', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('359', 'emailForgotSubject', 'backend', 'Email / Forgot Subject', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('360', 'emailForgotBody', 'backend', 'Email / Forgot Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('365', 'menuProfile', 'backend', 'Menu Profile', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('380', 'infoLocalesTitle', 'backend', 'Infobox / Locales Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('381', 'infoLocalesBody', 'backend', 'Infobox / Locales Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('382', 'infoLocalesBackendTitle', 'backend', 'Infobox / Locales Backend Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('383', 'infoLocalesBackendBody', 'backend', 'Infobox / Locales Backend Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('384', 'infoLocalesFrontendTitle', 'backend', 'Infobox / Locales Frontend Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('385', 'infoLocalesFrontendBody', 'backend', 'Infobox / Locales Frontend Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('386', 'infoListingPricesTitle', 'backend', 'Infobox / Listing Prices Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('387', 'infoListingPricesBody', 'backend', 'Infobox / Listing Prices Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('388', 'infoListingBookingsTitle', 'backend', 'Infobox / Listing Bookings Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('389', 'infoListingBookingsBody', 'backend', 'Infobox / Listing Bookings Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('390', 'infoListingContactTitle', 'backend', 'Infobox / Listing Contact Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('391', 'infoListingContactBody', 'backend', 'Infobox / Listing Contact Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('392', 'infoListingAddressTitle', 'backend', 'Infobox / Listing Address Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('393', 'infoListingAddressBody', 'backend', 'Infobox / Listing Address Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('395', 'infoListingExtendTitle', 'backend', 'Infobox / Extend exp.date Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('396', 'infoListingExtendBody', 'backend', 'Infobox / Extend exp.date Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('408', 'menuBackup', 'backend', 'Menu Backup', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('409', 'btnBackup', 'backend', 'Button Backup', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('410', 'lblBackupDatabase', 'backend', 'Backup / Database', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('411', 'lblBackupFiles', 'backend', 'Backup / Files', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('412', 'gridChooseAction', 'backend', 'Grid / Choose Action', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('413', 'gridGotoPage', 'backend', 'Grid / Go to page', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('414', 'gridTotalItems', 'backend', 'Grid / Total items', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('415', 'gridItemsPerPage', 'backend', 'Grid / Items per page', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('416', 'gridPrevPage', 'backend', 'Grid / Prev page', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('417', 'gridPrev', 'backend', 'Grid / Prev', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('418', 'gridNextPage', 'backend', 'Grid / Next page', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('419', 'gridNext', 'backend', 'Grid / Next', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('420', 'gridDeleteConfirmation', 'backend', 'Grid / Delete confirmation', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('421', 'gridConfirmationTitle', 'backend', 'Grid / Confirmation Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('422', 'gridActionTitle', 'backend', 'Grid / Action Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('423', 'gridBtnOk', 'backend', 'Grid / Button OK', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('424', 'gridBtnCancel', 'backend', 'Grid / Button Cancel', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('425', 'gridBtnDelete', 'backend', 'Grid / Button Delete', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('426', 'gridEmptyResult', 'backend', 'Grid / Empty resultset', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('433', 'multilangTooltip', 'backend', 'MultiLang / Tooltip', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('434', 'lblIp', 'backend', 'IP address', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('435', 'lblUserCreated', 'backend', 'User / Registration Date & Time', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('441', 'opt_o_currency', 'backend', 'Options / Currency', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('442', 'opt_o_date_format', 'backend', 'Options / Date format', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('451', 'opt_o_timezone', 'backend', 'Options / Timezone', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('452', 'opt_o_week_start', 'backend', 'Options / First day of the week', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('455', 'u_statarr_ARRAY_T', 'arrays', 'u_statarr_ARRAY_T', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('456', 'u_statarr_ARRAY_F', 'arrays', 'u_statarr_ARRAY_F', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('457', 'filter_ARRAY_active', 'arrays', 'filter_ARRAY_active', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('458', 'filter_ARRAY_inactive', 'arrays', 'filter_ARRAY_inactive', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('471', '_yesno_ARRAY_T', 'arrays', '_yesno_ARRAY_T', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('472', '_yesno_ARRAY_F', 'arrays', '_yesno_ARRAY_F', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('476', 'personal_titles_ARRAY_mr', 'arrays', 'personal_titles_ARRAY_mr', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('477', 'personal_titles_ARRAY_mrs', 'arrays', 'personal_titles_ARRAY_mrs', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('478', 'personal_titles_ARRAY_miss', 'arrays', 'personal_titles_ARRAY_miss', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('479', 'personal_titles_ARRAY_ms', 'arrays', 'personal_titles_ARRAY_ms', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('480', 'personal_titles_ARRAY_dr', 'arrays', 'personal_titles_ARRAY_dr', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('481', 'personal_titles_ARRAY_prof', 'arrays', 'personal_titles_ARRAY_prof', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('482', 'personal_titles_ARRAY_rev', 'arrays', 'personal_titles_ARRAY_rev', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('483', 'personal_titles_ARRAY_other', 'arrays', 'personal_titles_ARRAY_other', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('496', 'timezones_ARRAY_-43200', 'arrays', 'timezones_ARRAY_-43200', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('497', 'timezones_ARRAY_-39600', 'arrays', 'timezones_ARRAY_-39600', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('498', 'timezones_ARRAY_-36000', 'arrays', 'timezones_ARRAY_-36000', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('499', 'timezones_ARRAY_-32400', 'arrays', 'timezones_ARRAY_-32400', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('500', 'timezones_ARRAY_-28800', 'arrays', 'timezones_ARRAY_-28800', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('501', 'timezones_ARRAY_-25200', 'arrays', 'timezones_ARRAY_-25200', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('502', 'timezones_ARRAY_-21600', 'arrays', 'timezones_ARRAY_-21600', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('503', 'timezones_ARRAY_-18000', 'arrays', 'timezones_ARRAY_-18000', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('504', 'timezones_ARRAY_-14400', 'arrays', 'timezones_ARRAY_-14400', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('505', 'timezones_ARRAY_-10800', 'arrays', 'timezones_ARRAY_-10800', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('506', 'timezones_ARRAY_-7200', 'arrays', 'timezones_ARRAY_-7200', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('507', 'timezones_ARRAY_-3600', 'arrays', 'timezones_ARRAY_-3600', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('508', 'timezones_ARRAY_0', 'arrays', 'timezones_ARRAY_0', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('509', 'timezones_ARRAY_3600', 'arrays', 'timezones_ARRAY_3600', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('510', 'timezones_ARRAY_7200', 'arrays', 'timezones_ARRAY_7200', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('511', 'timezones_ARRAY_10800', 'arrays', 'timezones_ARRAY_10800', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('512', 'timezones_ARRAY_14400', 'arrays', 'timezones_ARRAY_14400', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('513', 'timezones_ARRAY_18000', 'arrays', 'timezones_ARRAY_18000', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('514', 'timezones_ARRAY_21600', 'arrays', 'timezones_ARRAY_21600', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('515', 'timezones_ARRAY_25200', 'arrays', 'timezones_ARRAY_25200', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('516', 'timezones_ARRAY_28800', 'arrays', 'timezones_ARRAY_28800', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('517', 'timezones_ARRAY_32400', 'arrays', 'timezones_ARRAY_32400', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('518', 'timezones_ARRAY_36000', 'arrays', 'timezones_ARRAY_36000', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('519', 'timezones_ARRAY_39600', 'arrays', 'timezones_ARRAY_39600', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('520', 'timezones_ARRAY_43200', 'arrays', 'timezones_ARRAY_43200', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('521', 'timezones_ARRAY_46800', 'arrays', 'timezones_ARRAY_46800', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('540', 'error_titles_ARRAY_AU01', 'arrays', 'error_titles_ARRAY_AU01', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('541', 'error_titles_ARRAY_AU03', 'arrays', 'error_titles_ARRAY_AU03', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('542', 'error_titles_ARRAY_AU04', 'arrays', 'error_titles_ARRAY_AU04', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('543', 'error_titles_ARRAY_AU08', 'arrays', 'error_titles_ARRAY_AU08', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('544', 'error_titles_ARRAY_AO01', 'arrays', 'error_titles_ARRAY_AO01', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('552', 'error_titles_ARRAY_AB01', 'arrays', 'error_titles_ARRAY_AB01', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('553', 'error_titles_ARRAY_AB02', 'arrays', 'error_titles_ARRAY_AB02', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('554', 'error_titles_ARRAY_AB03', 'arrays', 'error_titles_ARRAY_AB03', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('555', 'error_titles_ARRAY_AB04', 'arrays', 'error_titles_ARRAY_AB04', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('556', 'error_titles_ARRAY_AA10', 'arrays', 'error_titles_ARRAY_AA10', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('557', 'error_titles_ARRAY_AA11', 'arrays', 'error_titles_ARRAY_AA11', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('558', 'error_titles_ARRAY_AA12', 'arrays', 'error_titles_ARRAY_AA12', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('559', 'error_titles_ARRAY_AA13', 'arrays', 'error_titles_ARRAY_AA13', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('578', 'error_bodies_ARRAY_AU01', 'arrays', 'error_bodies_ARRAY_AU01', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('579', 'error_bodies_ARRAY_AU03', 'arrays', 'error_bodies_ARRAY_AU03', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('580', 'error_bodies_ARRAY_AU04', 'arrays', 'error_bodies_ARRAY_AU04', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('581', 'error_bodies_ARRAY_AU08', 'arrays', 'error_bodies_ARRAY_AU08', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('582', 'error_bodies_ARRAY_AO01', 'arrays', 'error_bodies_ARRAY_AO01', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('589', 'error_bodies_ARRAY_ALC01', 'arrays', 'error_bodies_ARRAY_ALC01', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('590', 'error_bodies_ARRAY_AB01', 'arrays', 'error_bodies_ARRAY_AB01', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('591', 'error_bodies_ARRAY_AB02', 'arrays', 'error_bodies_ARRAY_AB02', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('592', 'error_bodies_ARRAY_AB03', 'arrays', 'error_bodies_ARRAY_AB03', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('593', 'error_bodies_ARRAY_AB04', 'arrays', 'error_bodies_ARRAY_AB04', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('594', 'error_bodies_ARRAY_AA10', 'arrays', 'error_bodies_ARRAY_AA10', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('595', 'error_bodies_ARRAY_AA11', 'arrays', 'error_bodies_ARRAY_AA11', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('596', 'error_bodies_ARRAY_AA12', 'arrays', 'error_bodies_ARRAY_AA12', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('597', 'error_bodies_ARRAY_AA13', 'arrays', 'error_bodies_ARRAY_AA13', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('627', 'months_ARRAY_1', 'arrays', 'months_ARRAY_1', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('628', 'months_ARRAY_2', 'arrays', 'months_ARRAY_2', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('629', 'months_ARRAY_3', 'arrays', 'months_ARRAY_3', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('630', 'months_ARRAY_4', 'arrays', 'months_ARRAY_4', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('631', 'months_ARRAY_5', 'arrays', 'months_ARRAY_5', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('632', 'months_ARRAY_6', 'arrays', 'months_ARRAY_6', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('633', 'months_ARRAY_7', 'arrays', 'months_ARRAY_7', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('634', 'months_ARRAY_8', 'arrays', 'months_ARRAY_8', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('635', 'months_ARRAY_9', 'arrays', 'months_ARRAY_9', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('636', 'months_ARRAY_10', 'arrays', 'months_ARRAY_10', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('637', 'months_ARRAY_11', 'arrays', 'months_ARRAY_11', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('638', 'months_ARRAY_12', 'arrays', 'months_ARRAY_12', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('639', 'days_ARRAY_0', 'arrays', 'days_ARRAY_0', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('640', 'days_ARRAY_1', 'arrays', 'days_ARRAY_1', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('641', 'days_ARRAY_2', 'arrays', 'days_ARRAY_2', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('642', 'days_ARRAY_3', 'arrays', 'days_ARRAY_3', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('643', 'days_ARRAY_4', 'arrays', 'days_ARRAY_4', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('644', 'days_ARRAY_5', 'arrays', 'days_ARRAY_5', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('645', 'days_ARRAY_6', 'arrays', 'days_ARRAY_6', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('646', 'day_names_ARRAY_0', 'arrays', 'day_names_ARRAY_0', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('647', 'day_names_ARRAY_1', 'arrays', 'day_names_ARRAY_1', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('648', 'day_names_ARRAY_2', 'arrays', 'day_names_ARRAY_2', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('649', 'day_names_ARRAY_3', 'arrays', 'day_names_ARRAY_3', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('650', 'day_names_ARRAY_4', 'arrays', 'day_names_ARRAY_4', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('651', 'day_names_ARRAY_5', 'arrays', 'day_names_ARRAY_5', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('652', 'day_names_ARRAY_6', 'arrays', 'day_names_ARRAY_6', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('653', 'short_months_ARRAY_1', 'arrays', 'short_months_ARRAY_1', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('654', 'short_months_ARRAY_2', 'arrays', 'short_months_ARRAY_2', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('655', 'short_months_ARRAY_3', 'arrays', 'short_months_ARRAY_3', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('656', 'short_months_ARRAY_4', 'arrays', 'short_months_ARRAY_4', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('657', 'short_months_ARRAY_5', 'arrays', 'short_months_ARRAY_5', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('658', 'short_months_ARRAY_6', 'arrays', 'short_months_ARRAY_6', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('659', 'short_months_ARRAY_7', 'arrays', 'short_months_ARRAY_7', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('660', 'short_months_ARRAY_8', 'arrays', 'short_months_ARRAY_8', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('661', 'short_months_ARRAY_9', 'arrays', 'short_months_ARRAY_9', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('662', 'short_months_ARRAY_10', 'arrays', 'short_months_ARRAY_10', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('663', 'short_months_ARRAY_11', 'arrays', 'short_months_ARRAY_11', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('664', 'short_months_ARRAY_12', 'arrays', 'short_months_ARRAY_12', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('665', 'status_ARRAY_1', 'arrays', 'status_ARRAY_1', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('666', 'status_ARRAY_2', 'arrays', 'status_ARRAY_2', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('667', 'status_ARRAY_3', 'arrays', 'status_ARRAY_3', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('668', 'status_ARRAY_7', 'arrays', 'status_ARRAY_7', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('669', 'status_ARRAY_123', 'arrays', 'status_ARRAY_123', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('670', 'status_ARRAY_999', 'arrays', 'status_ARRAY_999', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('671', 'status_ARRAY_998', 'arrays', 'status_ARRAY_998', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('672', 'status_ARRAY_997', 'arrays', 'status_ARRAY_997', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('673', 'status_ARRAY_996', 'arrays', 'status_ARRAY_996', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('674', 'status_ARRAY_9999', 'arrays', 'status_ARRAY_9999', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('675', 'status_ARRAY_9998', 'arrays', 'status_ARRAY_9998', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('676', 'status_ARRAY_9997', 'arrays', 'status_ARRAY_9997', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('677', 'login_err_ARRAY_1', 'arrays', 'login_err_ARRAY_1', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('678', 'login_err_ARRAY_2', 'arrays', 'login_err_ARRAY_2', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('679', 'login_err_ARRAY_3', 'arrays', 'login_err_ARRAY_3', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('907', 'localeArrays', 'backend', 'Locale / Arrays titles', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('908', 'infoLocalesArraysTitle', 'backend', 'Locale / Languages Array Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('909', 'infoLocalesArraysBody', 'backend', 'Locale / Languages Array Body', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('910', 'lnkBack', 'backend', 'Link Back', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('982', 'locale_order', 'backend', 'Locale / Order', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('983', 'locale_is_default', 'backend', 'Locale / Is default', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('984', 'locale_flag', 'backend', 'Locale / Flag', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('985', 'locale_title', 'backend', 'Locale / Title', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('986', 'btnDelete', 'backend', 'Button Delete', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('990', 'btnContinue', 'backend', 'Button Continue', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('992', 'vr_email_taken', 'backend', 'Users / Email already taken', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('993', 'revert_status', 'backend', 'Revert status', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('994', 'lblExport', 'backend', 'Export', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('995', 'opt_o_send_email', 'backend', 'opt_o_send_email', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('996', 'opt_o_smtp_host', 'backend', 'opt_o_smtp_host', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('997', 'opt_o_smtp_port', 'backend', 'opt_o_smtp_port', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('998', 'opt_o_smtp_user', 'backend', 'opt_o_smtp_user', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('999', 'opt_o_smtp_pass', 'backend', 'opt_o_smtp_pass', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1053', 'menuServices', 'backend', 'Menu Services', 'script', '2013-09-16 09:50:50');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1054', 'menuEmployees', 'backend', 'Menu Employees', 'script', '2013-09-16 09:51:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1055', 'service_add', 'backend', 'Services / Add service', 'script', '2013-09-16 12:44:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1056', 'lblAll', 'backend', 'All', 'script', '2013-09-16 12:47:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1057', 'service_name', 'backend', 'Services / Name', 'script', '2013-11-22 09:45:50');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1058', 'service_price', 'backend', 'Services / Price', 'script', '2013-09-16 12:52:48');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1059', 'service_before', 'backend', 'Services / Before', 'script', '2013-09-16 12:53:30');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1060', 'service_after', 'backend', 'Services / After', 'script', '2013-09-16 12:53:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1061', 'service_total', 'backend', 'Services / Total', 'script', '2013-09-16 12:53:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1062', 'service_length', 'backend', 'Services / Length', 'script', '2013-09-16 12:53:59');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1063', 'service_desc', 'backend', 'Services / Description', 'script', '2013-09-16 12:54:18');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1064', 'service_status', 'backend', 'Services / Status', 'script', '2013-09-16 12:56:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1065', 'service_employees', 'backend', 'Services / Employees', 'script', '2013-09-16 12:59:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1066', 'service_update', 'backend', 'Services / Update service', 'script', '2013-09-16 13:21:54');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1067', 'is_active_ARRAY_1', 'arrays', 'is_active_ARRAY_1', 'script', '2013-09-16 13:42:57');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1068', 'is_active_ARRAY_0', 'arrays', 'is_active_ARRAY_0', 'script', '2013-09-16 13:43:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1069', 'delete_selected', 'backend', 'Grid / Delete selected', 'script', '2013-09-16 14:10:00');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1070', 'delete_confirmation', 'backend', 'Grid / Confirmation Title', 'script', '2013-09-16 14:09:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1071', 'error_bodies_ARRAY_AS08', 'arrays', 'error_bodies_ARRAY_AS08', 'script', '2013-09-16 14:11:08');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1072', 'error_titles_ARRAY_AS01', 'arrays', 'error_titles_ARRAY_AS01', 'script', '2013-09-16 14:11:21');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1073', 'error_titles_ARRAY_AS03', 'arrays', 'error_titles_ARRAY_AS03', 'script', '2013-09-16 14:11:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1074', 'error_titles_ARRAY_AS04', 'arrays', 'error_titles_ARRAY_AS04', 'script', '2013-09-16 14:11:40');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1075', 'error_titles_ARRAY_AS08', 'arrays', 'error_titles_ARRAY_AS08', 'script', '2013-09-16 14:11:48');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1076', 'error_bodies_ARRAY_AS01', 'arrays', 'error_bodies_ARRAY_AS01', 'script', '2013-09-16 14:11:58');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1077', 'error_bodies_ARRAY_AS03', 'arrays', 'error_bodies_ARRAY_AS03', 'script', '2013-09-16 14:12:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1078', 'error_bodies_ARRAY_AS04', 'arrays', 'error_bodies_ARRAY_AS04', 'script', '2013-09-16 14:12:21');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1079', 'error_titles_ARRAY_AS09', 'arrays', 'error_titles_ARRAY_AS09', 'script', '2013-09-16 14:15:00');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1080', 'error_bodies_ARRAY_AS09', 'arrays', 'error_bodies_ARRAY_AS09', 'script', '2013-11-22 09:45:38');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1081', 'error_bodies_ARRAY_AS10', 'arrays', 'error_bodies_ARRAY_AS10', 'script', '2013-11-22 09:49:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1082', 'error_titles_ARRAY_AS10', 'arrays', 'error_titles_ARRAY_AS10', 'script', '2013-09-16 14:15:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1083', 'employee_add', 'backend', 'Employees / Add employee', 'script', '2013-09-16 14:20:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1084', 'employee_name', 'backend', 'Employees / Employee Name', 'script', '2013-11-22 09:51:34');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1085', 'employee_email', 'backend', 'Employees / Email', 'script', '2013-09-16 14:24:29');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1086', 'employee_phone', 'backend', 'Employees / Phone', 'script', '2013-09-16 14:24:39');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1087', 'employee_services', 'backend', 'Employees / Services', 'script', '2013-09-16 14:27:56');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1088', 'employee_status', 'backend', 'Employees / Status', 'script', '2013-09-16 14:28:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1089', 'employee_update', 'backend', 'Employees / Update employee', 'script', '2013-09-16 14:37:06');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1090', 'error_titles_ARRAY_AE09', 'arrays', 'error_titles_ARRAY_AE09', 'script', '2013-09-16 14:39:06');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1091', 'error_titles_ARRAY_AE10', 'arrays', 'error_titles_ARRAY_AE10', 'script', '2013-09-16 14:39:01');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1092', 'error_bodies_ARRAY_AE10', 'arrays', 'error_bodies_ARRAY_AE10', 'script', '2013-11-22 09:53:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1093', 'error_bodies_ARRAY_AE09', 'arrays', 'error_bodies_ARRAY_AE09', 'script', '2013-11-22 09:52:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1094', 'employee_notes', 'backend', 'Employees / Notes', 'script', '2013-09-16 14:39:25');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1095', 'employee_is_subscribed', 'backend', 'Employees / Send email', 'script', '2013-09-17 06:14:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1096', 'employee_password', 'backend', 'Employees / Password', 'script', '2013-09-17 06:17:21');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1098', 'error_bodies_ARRAY_AE01', 'arrays', 'error_bodies_ARRAY_AE01', 'script', '2013-09-17 06:21:08');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1099', 'error_titles_ARRAY_AE01', 'arrays', 'error_titles_ARRAY_AE01', 'script', '2013-09-17 06:21:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1100', 'error_titles_ARRAY_AE03', 'arrays', 'error_titles_ARRAY_AE03', 'script', '2013-09-17 06:22:16');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1101', 'error_bodies_ARRAY_AE03', 'arrays', 'error_bodies_ARRAY_AE03', 'script', '2013-09-17 06:22:27');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1102', 'error_bodies_ARRAY_AE08', 'arrays', 'error_bodies_ARRAY_AE08', 'script', '2013-09-17 06:24:07');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1103', 'error_titles_ARRAY_AE08', 'arrays', 'error_titles_ARRAY_AE08', 'script', '2013-09-17 06:24:15');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1104', 'error_titles_ARRAY_AE04', 'arrays', 'error_titles_ARRAY_AE04', 'script', '2013-09-17 06:26:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1105', 'error_bodies_ARRAY_AE04', 'arrays', 'error_bodies_ARRAY_AE04', 'script', '2013-09-17 06:26:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1106', 'employee_last_login', 'backend', 'Employees / Last login', 'script', '2013-09-17 06:29:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1107', 'menuTime', 'backend', 'Menu Working Time', 'script', '2013-09-17 06:50:37');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1108', 'error_titles_ARRAY_AT01', 'arrays', 'error_titles_ARRAY_AT01', 'script', '2013-09-17 07:25:21');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1109', 'error_bodies_ARRAY_AT01', 'arrays', 'error_bodies_ARRAY_AT01', 'script', '2013-09-17 07:25:34');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1110', 'error_titles_ARRAY_AT02', 'arrays', 'error_titles_ARRAY_AT02', 'script', '2013-09-17 07:26:05');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1111', 'error_bodies_ARRAY_AT02', 'arrays', 'error_bodies_ARRAY_AT02', 'script', '2013-09-17 07:26:18');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1112', 'error_titles_ARRAY_AT03', 'arrays', 'error_titles_ARRAY_AT03', 'script', '2013-09-17 07:26:50');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1113', 'error_bodies_ARRAY_AT03', 'arrays', 'error_bodies_ARRAY_AT03', 'script', '2013-09-17 07:26:58');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1114', 'error_titles_ARRAY_AT04', 'arrays', 'error_titles_ARRAY_AT04', 'script', '2013-09-17 07:45:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1115', 'error_bodies_ARRAY_AT04', 'arrays', 'error_bodies_ARRAY_AT04', 'script', '2013-12-12 18:48:05');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1116', 'time_update_custom', 'backend', 'Working Time / Update custom', 'script', '2013-09-17 07:47:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1117', 'time_default', 'backend', 'Working Time / Default', 'script', '2013-09-17 08:42:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1118', 'time_custom', 'backend', 'Working Time / Custom', 'script', '2013-09-17 07:47:57');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1119', 'time_day', 'backend', 'Working Time / Day of week', 'script', '2013-09-17 07:48:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1120', 'time_from', 'backend', 'Working Time / Start Time', 'script', '2013-09-17 07:48:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1121', 'time_to', 'backend', 'Working Time / End Time', 'script', '2013-09-17 07:48:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1122', 'time_is', 'backend', 'Working Time / Is Day off', 'script', '2013-09-17 07:48:49');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1123', 'time_date', 'backend', 'Working Time / Date', 'script', '2013-09-17 07:49:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1124', 'employee_general', 'backend', 'Employees / General', 'script', '2013-09-17 08:41:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1125', 'time_default_wt', 'backend', 'Working Time / Default Working Time', 'script', '2013-09-17 08:42:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1126', 'time_custom_wt', 'backend', 'Working Time / Custom Working Time', 'script', '2013-09-17 08:42:55');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1127', 'time_lunch_from', 'backend', 'Working Time / Lunch from', 'script', '2013-09-17 10:28:54');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1128', 'time_lunch_to', 'backend', 'Working Time / Lunch to', 'script', '2013-09-17 10:29:07');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1129', 'menuInstall', 'backend', 'Menu Install', 'script', '2013-09-18 06:04:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1130', 'menuPreview', 'backend', 'Menu Preview', 'script', '2013-09-18 06:04:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1131', 'menuBookings', 'backend', 'Menu Bookings', 'script', '2013-09-18 06:05:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1132', 'menuGeneral', 'backend', 'Menu General', 'script', '2013-09-18 06:17:39');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1133', 'menuPayments', 'backend', 'Menu Payments', 'script', '2013-09-18 06:18:26');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1134', 'menuBookingForm', 'backend', 'Menu Booking form', 'script', '2013-09-18 06:18:55');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1135', 'menuConfirmation', 'backend', 'Menu Confirmation', 'script', '2013-09-18 06:19:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1136', 'menuTerms', 'backend', 'Menu Terms', 'script', '2013-09-18 06:19:23');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1137', 'opt_o_bf_address_1', 'backend', 'Options / Address 1', 'script', '2013-09-18 06:31:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1138', 'opt_o_bf_captcha', 'backend', 'Options / Captcha', 'script', '2013-09-18 06:31:32');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1139', 'opt_o_bf_city', 'backend', 'Options / City', 'script', '2013-09-18 06:31:49');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1140', 'opt_o_bf_email', 'backend', 'Options / Email', 'script', '2013-09-18 06:32:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1141', 'opt_o_bf_name', 'backend', 'Options / Name', 'script', '2013-09-18 06:32:23');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1142', 'opt_o_bf_notes', 'backend', 'Options / Notes', 'script', '2013-09-18 06:32:45');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1143', 'opt_o_bf_phone', 'backend', 'Options / Phone ', 'script', '2013-09-18 06:33:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1144', 'opt_o_bf_state', 'backend', 'Options / State', 'script', '2013-09-18 06:33:24');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1145', 'opt_o_bf_terms', 'backend', 'Options / Terms', 'script', '2013-09-18 06:33:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1146', 'opt_o_bf_zip', 'backend', 'Options / Zip', 'script', '2013-09-18 06:34:16');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1147', 'opt_o_bf_country', 'backend', 'Options / Country', 'script', '2013-09-18 06:34:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1148', 'opt_o_paypal_address', 'backend', 'Options / Paypal address', 'script', '2013-09-18 06:35:08');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1149', 'opt_o_accept_bookings', 'backend', 'Options / Accept Bookings', 'script', '2013-09-18 06:35:32');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1150', 'opt_o_allow_authorize', 'backend', 'Options / Allow Authorize.net', 'script', '2013-09-18 06:36:05');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1151', 'opt_o_allow_bank', 'backend', 'Options / Allow Bank', 'script', '2013-11-22 10:06:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1152', 'opt_o_allow_creditcard', 'backend', 'Options / Allow Credit Card', 'script', '2013-11-22 10:06:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1153', 'opt_o_allow_paypal', 'backend', 'Options / Allow Paypal', 'script', '2013-09-18 06:37:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1154', 'opt_o_authorize_key', 'backend', 'Options / Authorize.net transaction key', 'script', '2013-09-18 06:37:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1155', 'opt_o_authorize_mid', 'backend', 'Options / Authorize.net merchant ID', 'script', '2013-09-18 06:38:01');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1156', 'opt_o_bank_account', 'backend', 'Options / Bank account', 'script', '2013-09-18 06:38:18');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1157', 'opt_o_deposit', 'backend', 'Options / Deposit', 'script', '2013-11-22 10:08:04');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1158', 'opt_o_disable_payments', 'backend', 'Options / Disable payments', 'script', '2013-11-22 10:07:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1162', 'opt_o_status_if_not_paid', 'backend', 'Options / Default status for booked dates if not paid', 'script', '2013-09-18 06:40:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1163', 'opt_o_status_if_paid', 'backend', 'Options / Default status for booked dates if paid', 'script', '2013-09-18 06:40:27');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1164', 'opt_o_tax', 'backend', 'Options / Tax payment', 'script', '2013-11-22 10:09:27');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1165', 'opt_o_thankyou_page', 'backend', 'Options / "Thank you" page location', 'script', '2013-11-22 10:06:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1166', 'opt_o_authorize_tz', 'backend', 'Options / Authorize.net Time zone', 'script', '2013-09-18 06:41:23');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1167', 'opt_o_email_new_reservation', 'backend', 'Options / New booking received', 'script', '2013-09-18 06:41:40');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1168', 'opt_o_email_reservation_cancelled', 'backend', 'Options / Booking cancelled', 'script', '2013-09-18 06:41:55');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1169', 'opt_o_email_password_reminder', 'backend', 'Notifications / Password reminder', 'script', '2013-09-18 06:42:08');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1170', 'opt_o_bf_address_2', 'backend', 'Options / Address 2', 'script', '2013-09-18 06:42:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1171', 'opt_o_datetime_format', 'backend', 'Options / Datetime format', 'script', '2013-09-18 06:42:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1172', 'opt_o_authorize_hash', 'backend', 'Options / Authorize.net hash value', 'script', '2013-09-18 06:42:51');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1173', 'confirmation_subject', 'backend', 'Confirmation / Email subject', 'script', '2013-09-18 06:51:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1174', 'confirmation_body', 'backend', 'Confirmation / Email body', 'script', '2013-09-18 06:51:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1175', 'confirmation_client_confirmation', 'backend', 'Confirmation / Client confirmation title', 'script', '2013-09-18 06:51:59');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1176', 'confirmation_client_payment', 'backend', 'Confirmation / Client payment title', 'script', '2013-09-18 06:52:11');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1177', 'confirmation_admin_confirmation', 'backend', 'Confirmation / Admin confirmation title', 'script', '2013-09-18 06:52:25');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1178', 'confirmation_admin_payment', 'backend', 'Confirmation / Admin payment title', 'script', '2013-09-18 06:52:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1179', 'lblOptionsTermsURL', 'backend', 'Options / Booking terms URL', 'script', '2013-09-18 06:53:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1180', 'lblOptionsTermsContent', 'backend', 'Options / Booking terms content', 'script', '2013-09-18 06:54:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1181', 'booking_add', 'backend', 'Bookings / Add booking', 'script', '2013-09-18 06:57:23');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1182', 'booking_statuses_ARRAY_confirmed', 'arrays', 'Bookings / Status: confirmed', 'script', '2013-09-18 06:58:11');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1183', 'booking_statuses_ARRAY_pending', 'arrays', 'Bookings / Status: pending', 'script', '2013-09-18 06:58:27');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1184', 'booking_statuses_ARRAY_cancelled', 'arrays', 'Bookings / Status: cancelled', 'script', '2013-09-18 06:58:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1185', 'booking_uuid', 'backend', 'Bookings / Unique ID', 'script', '2013-09-18 06:59:00');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1186', 'booking_status', 'backend', 'Bookings / Status', 'script', '2013-09-18 06:59:25');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1187', 'booking_update', 'backend', 'Bookings / Update booking', 'script', '2013-09-18 06:59:38');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1315', 'booking_cc_exp', 'backend', 'Bookings / CC Exp.date', 'script', '2013-09-18 07:10:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1316', 'booking_cc_code', 'backend', 'Bookings / CC Code', 'script', '2013-09-18 07:10:32');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1317', 'booking_cc_num', 'backend', 'Bookings / CC Number', 'script', '2013-09-18 07:10:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1318', 'booking_cc_type', 'backend', 'Bookings / CC Type', 'script', '2013-09-18 07:11:00');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1319', 'booking_cc_types_ARRAY_maestro', 'arrays', 'booking_cc_types_ARRAY_maestro', 'script', '2013-09-18 07:11:18');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1320', 'booking_cc_types_ARRAY_amex', 'arrays', 'booking_cc_types_ARRAY_amex', 'script', '2013-09-18 07:11:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1321', 'booking_cc_types_ARRAY_mastercard', 'arrays', 'booking_cc_types_ARRAY_mastercard', 'script', '2013-09-18 07:17:32');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1322', 'booking_cc_types_ARRAY_visa', 'arrays', 'booking_cc_types_ARRAY_visa', 'script', '2013-09-18 07:17:48');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1324', 'booking_phone', 'backend', 'Bookings / Phone', 'script', '2013-09-18 07:18:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1325', 'booking_email', 'backend', 'Bookings / Email', 'script', '2013-09-18 07:18:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1326', 'booking_invoice_details', 'backend', 'Bookings / Invoice details', 'script', '2013-09-18 07:18:34');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1327', 'booking_create_invoice', 'backend', 'Bookings / Create Invoice', 'script', '2013-09-18 07:35:27');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1328', 'booking_customer', 'backend', 'Bookings / Customer details', 'script', '2013-09-18 07:35:44');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1329', 'booking_notes', 'backend', 'Bookings / Notes', 'script', '2013-09-18 07:35:58');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1330', 'booking_address_2', 'backend', 'Bookings / Address Line 2', 'script', '2013-09-18 07:36:12');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1331', 'booking_address_1', 'backend', 'Bookings / Address Line 1', 'script', '2013-09-18 07:36:27');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1332', 'booking_name', 'backend', 'Bookings / Name', 'script', '2013-09-18 07:36:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1333', 'booking_zip', 'backend', 'Bookings / Zip', 'script', '2013-09-18 07:36:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1334', 'booking_city', 'backend', 'Bookings / City', 'script', '2013-09-18 07:37:06');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1335', 'booking_state', 'backend', 'Bookings / State', 'script', '2013-09-18 07:37:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1336', 'booking_country', 'backend', 'Bookings / Country', 'script', '2013-09-18 07:37:34');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1337', 'booking_tab_client', 'backend', 'Bookings / Client', 'script', '2013-09-18 07:37:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1338', 'booking_tab_details', 'backend', 'Bookings / Booking', 'script', '2013-09-18 07:37:59');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1339', 'booking_general', 'backend', 'Bookings / Details', 'script', '2013-09-18 07:38:11');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1340', 'booking_choose', 'backend', 'Bookings / Choose', 'script', '2013-09-18 07:38:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1341', 'booking_payment_method', 'backend', 'Bookings / Payment method', 'script', '2013-09-18 07:38:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1342', 'booking_price', 'backend', 'Bookings / Price', 'script', '2013-09-18 07:38:44');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1344', 'booking_tax', 'backend', 'Bookings / Tax', 'script', '2013-09-18 07:39:07');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1345', 'booking_deposit', 'backend', 'Bookings / Deposit', 'script', '2013-09-18 07:47:15');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1346', 'booking_total', 'backend', 'Bookings / Total', 'script', '2013-09-18 07:47:27');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1347', 'booking_created', 'backend', 'Bookings / Created', 'script', '2013-09-18 07:47:38');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1348', 'payment_methods_ARRAY_authorize', 'arrays', 'payment_methods_ARRAY_authorize', 'script', '2013-09-18 07:55:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1349', 'payment_methods_ARRAY_bank', 'arrays', 'payment_methods_ARRAY_bank', 'script', '2013-09-18 07:55:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1350', 'payment_methods_ARRAY_creditcard', 'arrays', 'payment_methods_ARRAY_creditcard', 'script', '2013-09-18 07:55:44');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1351', 'payment_methods_ARRAY_paypal', 'arrays', 'payment_methods_ARRAY_paypal', 'script', '2013-09-18 07:55:57');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1352', 'error_titles_ARRAY_AO24', 'arrays', 'error_titles_ARRAY_AO24', 'script', '2013-09-18 08:44:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1353', 'error_titles_ARRAY_AO25', 'arrays', 'error_titles_ARRAY_AO25', 'script', '2013-09-18 08:44:26');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1354', 'error_titles_ARRAY_AO26', 'arrays', 'error_titles_ARRAY_AO26', 'script', '2013-09-18 08:44:51');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1355', 'error_bodies_ARRAY_AO24', 'arrays', 'error_bodies_ARRAY_AO24', 'script', '2013-09-18 08:45:18');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1356', 'error_bodies_ARRAY_AO25', 'arrays', 'error_bodies_ARRAY_AO25', 'script', '2013-12-12 19:55:12');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1357', 'error_bodies_ARRAY_AO26', 'arrays', 'error_bodies_ARRAY_AO26', 'script', '2013-09-18 08:45:51');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1358', 'error_bodies_ARRAY_AO27', 'arrays', 'error_bodies_ARRAY_AO27', 'script', '2013-11-22 10:10:04');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1359', 'error_titles_ARRAY_AO27', 'arrays', 'error_titles_ARRAY_AO27', 'script', '2013-09-18 08:47:26');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1360', 'error_bodies_ARRAY_AO23', 'arrays', 'error_bodies_ARRAY_AO23', 'script', '2013-11-22 10:05:23');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1361', 'error_titles_ARRAY_AO23', 'arrays', 'error_titles_ARRAY_AO23', 'script', '2013-09-18 08:48:23');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1362', 'error_bodies_ARRAY_AO21', 'arrays', 'error_bodies_ARRAY_AO21', 'script', '2013-09-18 08:48:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1363', 'error_titles_ARRAY_AO21', 'arrays', 'error_titles_ARRAY_AO21', 'script', '2013-09-18 08:48:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1364', 'opt_o_hide_prices', 'backend', 'Options / Hide prices', 'script', '2013-09-18 08:53:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1365', 'opt_o_step', 'backend', 'Options / Step', 'script', '2013-09-18 08:54:04');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1366', 'booking_services', 'backend', 'Bookings / Services', 'script', '2013-09-18 09:04:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1367', 'employee_avatar', 'backend', 'Employees / Picture', 'script', '2013-09-18 10:22:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1368', 'employee_avatar_delete', 'backend', 'Employees / Delete picture', 'script', '2013-09-18 11:15:12');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1369', 'employee_avatar_dtitle', 'backend', 'Employees / Delete confirmation', 'script', '2013-09-18 11:23:07');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1370', 'employee_avatar_dbody', 'backend', 'Employees / Delete content', 'script', '2013-09-18 11:23:46');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1371', 'lblInstallJs1_title', 'backend', 'Install / Title', 'script', '2013-09-18 13:30:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1372', 'lblInstallJs1_body', 'backend', 'Install / Body', 'script', '2013-09-18 13:30:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1373', 'lblInstallJs1_1', 'backend', 'Install / Step 1', 'script', '2013-09-18 13:30:26');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1374', 'lblInstallJs1_2', 'backend', 'Install / Step 2', 'script', '2013-09-18 13:30:37');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1375', 'lblInstallJs1_3', 'backend', 'Install / Step 3', 'script', '2013-09-18 13:30:48');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1376', 'opt_o_seo_url', 'backend', 'Options / Seo URLs', 'script', '2013-09-19 09:30:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1377', 'opt_o_time_format', 'backend', 'Options / Time format', 'script', '2013-09-20 07:37:12');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1378', 'opt_o_week_numbers', 'backend', 'Options / Show week numbers', 'script', '2013-09-20 09:29:59');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1379', 'co_captcha', 'frontend', 'Checkout / Captcha', 'script', '2013-10-03 12:41:20');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1380', 'co_select_country', 'frontend', 'Checkout / Select Country', 'script', '2013-10-03 12:41:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1381', 'co_terms', 'frontend', 'Checkout / Terms', 'script', '2013-10-03 12:42:05');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1382', 'co_empty_notice', 'frontend', 'Checkout / Empty notice', 'script', '2013-10-03 12:42:40');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1383', 'front_select_payment', 'frontend', 'Frontend / Select Payment method', 'script', '2013-10-03 12:43:12');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1384', 'front_select_cc_type', 'frontend', 'Bookings / Select CC Type', 'script', '2013-10-03 12:44:50');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1385', 'booking_employee', 'backend', 'Bookings / Employee', 'script', '2013-10-04 13:04:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1386', 'booking_service', 'backend', 'Bookings / Service', 'script', '2013-10-04 13:04:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1387', 'booking_from', 'backend', 'Bookings / Date from', 'script', '2013-10-04 13:04:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1388', 'booking_to', 'backend', 'Bookings / Date to', 'script', '2013-10-04 13:04:51');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1389', 'booking_query', 'backend', 'Bookings / Query', 'script', '2013-10-04 13:22:00');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1390', 'booking_date', 'backend', 'Bookings / Date', 'script', '2013-10-04 13:57:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1391', 'booking_export', 'backend', 'Bookings / Export', 'script', '2013-10-07 06:19:11');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1392', 'booking_delimiter_ARRAY_comma', 'arrays', 'booking_delimiter_ARRAY_comma', 'script', '2013-10-07 07:13:45');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1393', 'booking_delimiter_ARRAY_semicolon', 'arrays', 'booking_delimiter_ARRAY_semicolon', 'script', '2013-10-07 07:13:59');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1394', 'booking_delimiter_ARRAY_tab', 'arrays', 'booking_delimiter_ARRAY_tab', 'script', '2013-10-07 07:14:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1395', 'booking_format_ARRAY_csv', 'arrays', 'booking_delimiter_ARRAY_csv', 'script', '2013-10-07 07:15:01');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1396', 'booking_format_ARRAY_xml', 'arrays', 'booking_delimiter_ARRAY_xml', 'script', '2013-10-07 07:15:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1397', 'booking_format_ARRAY_ical', 'arrays', 'booking_delimiter_ARRAY_ical', 'script', '2013-10-07 07:15:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1398', 'booking_delimiter_lbl', 'backend', 'Bookings / Delimiter', 'script', '2013-10-07 07:18:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1399', 'booking_format_lbl', 'backend', 'Bookings / Format', 'script', '2013-10-07 07:18:54');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1400', 'booking_na', 'backend', 'Bookings / Not available', 'script', '2013-10-07 09:33:57');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1401', 'booking_export_title', 'backend', 'Bookings / Export bookings', 'script', '2013-10-07 09:35:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1402', 'booking_dt', 'backend', 'Bookings / Date Time', 'script', '2013-10-07 09:48:12');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1403', 'booking_service_employee', 'backend', 'Bookings / Service/Employee', 'script', '2013-10-07 09:48:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1404', 'menuReminder', 'backend', 'Menu Reminder', 'script', '2013-10-07 10:02:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1405', 'opt_o_reminder_enable', 'backend', 'Options / Enable notifications', 'script', '2013-12-12 19:56:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1406', 'opt_o_reminder_email_before', 'backend', 'Options / Send email reminder', 'script', '2013-12-12 19:58:20');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1407', 'opt_o_reminder_subject', 'backend', 'Options / Email Reminder subject', 'script', '2013-10-07 10:05:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1408', 'opt_o_reminder_sms_hours', 'backend', 'Options / Send SMS reminder', 'script', '2013-12-12 20:00:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1409', 'opt_o_reminder_sms_country_code', 'backend', 'Options / SMS country code', 'script', '2013-12-12 20:00:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1410', 'opt_o_reminder_sms_message', 'backend', 'Options / SMS message', 'script', '2013-12-12 20:00:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1411', 'opt_o_reminder_body', 'backend', 'Options / Email Reminder body', 'script', '2013-12-12 19:58:37');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1412', 'get_key', 'backend', 'Options / Get key', 'script', '2013-10-07 10:42:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1413', 'error_bodies_ARRAY_AO28', 'arrays', 'error_bodies_ARRAY_AO28', 'script', '2013-12-12 20:00:42');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1414', 'error_titles_ARRAY_AO28', 'arrays', 'error_titles_ARRAY_AO28', 'script', '2013-10-07 10:44:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1415', 'error_titles_ARRAY_ABK01', 'arrays', 'error_titles_ARRAY_ABK01', 'script', '2013-10-07 10:48:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1416', 'error_bodies_ARRAY_ABK01', 'arrays', 'error_bodies_ARRAY_ABK01', 'script', '2013-10-07 10:48:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1417', 'error_titles_ARRAY_ABK08', 'arrays', 'error_titles_ARRAY_ABK08', 'script', '2013-10-07 10:49:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1418', 'error_bodies_ARRAY_ABK08', 'arrays', 'error_bodies_ARRAY_ABK08', 'script', '2013-10-07 10:50:05');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1419', 'error_titles_ARRAY_ABK03', 'arrays', 'error_titles_ARRAY_ABK03', 'script', '2013-10-07 10:50:42');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1420', 'error_bodies_ARRAY_ABK03', 'arrays', 'error_bodies_ARRAY_ABK03', 'script', '2013-10-07 10:51:08');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1421', 'error_titles_ARRAY_ABK04', 'arrays', 'error_titles_ARRAY_ABK04', 'script', '2013-10-07 10:51:24');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1422', 'error_bodies_ARRAY_ABK04', 'arrays', 'error_bodies_ARRAY_ABK04', 'script', '2013-10-07 10:51:40');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1423', 'error_bodies_ARRAY_ABK10', 'arrays', 'error_bodies_ARRAY_ABK10', 'script', '2013-11-22 09:43:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1424', 'error_titles_ARRAY_ABK10', 'arrays', 'error_titles_ARRAY_ABK10', 'script', '2013-10-07 10:52:49');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1425', 'error_titles_ARRAY_ABK11', 'arrays', 'error_titles_ARRAY_ABK11', 'script', '2013-10-07 10:53:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1426', 'error_bodies_ARRAY_ABK11', 'arrays', 'error_bodies_ARRAY_ABK11', 'script', '2013-11-22 09:44:07');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1427', 'error_bodies_ARRAY_ABK13', 'arrays', 'error_bodies_ARRAY_ABK13', 'script', '2013-10-07 10:54:44');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1428', 'error_titles_ARRAY_ABK13', 'arrays', 'error_titles_ARRAY_ABK13', 'script', '2013-10-07 10:54:38');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1429', 'error_bodies_ARRAY_ABK12', 'arrays', 'error_bodies_ARRAY_ABK12', 'script', '2013-10-07 10:55:06');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1430', 'error_titles_ARRAY_ABK12', 'arrays', 'error_titles_ARRAY_ABK12', 'script', '2013-10-07 10:55:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1431', 'error_bodies_ARRAY_AO08', 'arrays', 'error_bodies_ARRAY_AO08', 'script', '2013-10-07 11:41:38');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1432', 'error_titles_ARRAY_AO08', 'arrays', 'error_titles_ARRAY_AO08', 'script', '2013-10-07 11:41:48');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1433', 'error_bodies_ARRAY_AO06', 'arrays', 'error_bodies_ARRAY_AO06', 'script', '2013-10-07 11:42:11');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1434', 'error_titles_ARRAY_AO06', 'arrays', 'error_titles_ARRAY_AO06', 'script', '2013-10-07 11:42:21');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1435', 'error_titles_ARRAY_AO05', 'arrays', 'error_titles_ARRAY_AO05', 'script', '2013-10-07 11:42:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1436', 'error_bodies_ARRAY_AO05', 'arrays', 'error_bodies_ARRAY_AO05', 'script', '2013-10-07 11:42:58');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1437', 'error_bodies_ARRAY_AO04', 'arrays', 'error_bodies_ARRAY_AO04', 'script', '2013-10-07 11:43:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1438', 'error_titles_ARRAY_AO04', 'arrays', 'error_titles_ARRAY_AO04', 'script', '2013-10-07 11:43:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1439', 'error_titles_ARRAY_AO07', 'arrays', 'error_titles_ARRAY_AO07', 'script', '2013-10-07 11:44:24');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1440', 'error_bodies_ARRAY_AO07', 'arrays', 'error_bodies_ARRAY_AO07', 'script', '2013-10-07 11:44:37');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1441', 'error_bodies_ARRAY_AO03', 'arrays', 'error_bodies_ARRAY_AO03', 'script', '2013-10-07 11:45:45');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1442', 'error_titles_ARRAY_AO03', 'arrays', 'error_titles_ARRAY_AO03', 'script', '2013-10-07 11:45:39');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1454', 'booking_recalc', 'backend', 'Bookings / Recalculate the price', 'script', '2013-10-07 14:34:20');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1455', 'booking_service_add', 'backend', 'Bookings / Add service', 'script', '2013-10-07 14:35:29');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1456', 'booking_service_add_title', 'backend', 'Bookings / Add service (title)', 'script', '2013-10-07 14:36:58');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1457', 'booking_service_delete_title', 'backend', 'Bookings / Remove service (title)', 'script', '2013-10-07 14:38:39');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1458', 'booking_service_delete_body', 'backend', 'Bookings / Remove service (body)', 'script', '2013-10-07 14:38:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1459', 'front_cart_empty', 'frontend', 'Frontend / Cart is empty', 'script', '2013-10-07 15:10:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1460', 'front_cart_total', 'frontend', 'Frontend / Total', 'script', '2013-10-07 15:10:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1461', 'front_selected_services', 'frontend', 'Frontend / Selected Services', 'script', '2013-10-07 15:10:07');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1462', 'front_select_date', 'frontend', 'Frontend / Select a Date', 'script', '2013-10-07 15:10:01');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1463', 'front_make_appointment', 'frontend', 'Frontend / Make an Appointment', 'script', '2013-10-07 15:09:57');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1464', 'front_start_time', 'frontend', 'Frontend / Start time', 'script', '2013-10-07 15:09:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1465', 'front_end_time', 'frontend', 'Frontend / End time', 'script', '2013-10-07 15:10:54');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1466', 'front_select_services', 'frontend', 'Frontend / Select Services', 'script', '2013-10-07 15:11:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1467', 'front_availability', 'frontend', 'Frontend / Availability', 'script', '2013-10-07 15:12:16');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1468', 'front_booking_form', 'frontend', 'Frontend / Booking Form', 'script', '2013-10-07 15:12:40');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1469', 'front_system_msg', 'frontend', 'Frontend / System message', 'script', '2013-10-07 15:13:54');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1470', 'front_checkout_na', 'frontend', 'Frontend / Checkout form not available', 'script', '2013-10-07 15:14:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1471', 'front_return_back', 'frontend', 'Frontend / Return back', 'script', '2013-10-07 15:14:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1472', 'front_preview_form', 'frontend', 'Frontend / Booking Preview', 'script', '2013-10-07 15:15:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1473', 'front_confirm_booking', 'frontend', 'Frontend / Confirm booking', 'script', '2013-10-07 15:16:08');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1474', 'front_preview_na', 'frontend', 'Frontend / Preview not available', 'script', '2013-10-07 15:16:58');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1475', 'error_titles_ARRAY_AA14', 'arrays', 'error_titles_ARRAY_AA14', 'script', '2013-10-09 09:00:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1476', 'error_bodies_ARRAY_AA14', 'arrays', 'error_bodies_ARRAY_AA14', 'script', '2013-10-09 09:00:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1477', 'error_titles_ARRAY_AA15', 'arrays', 'error_titles_ARRAY_AA15', 'script', '2013-10-09 09:01:59');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1478', 'error_bodies_ARRAY_AA15', 'arrays', 'error_bodies_ARRAY_AA15', 'script', '2013-10-09 09:02:18');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1479', 'booking_ip', 'backend', 'Bookings / IP address', 'script', '2013-10-09 12:04:45');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1480', 'booking_view_title', 'backend', 'Bookings / Booking Service details', 'script', '2013-10-09 12:11:48');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1481', 'booking_service_email_title', 'backend', 'Bookings / Resend email (title)', 'script', '2013-10-09 12:51:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1482', 'booking_service_sms_title', 'backend', 'Bookings / Resend SMS (title)', 'script', '2013-10-09 12:52:05');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1483', 'booking_subject', 'backend', 'Bookings / Subject', 'script', '2013-10-09 13:36:00');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1484', 'booking_message', 'backend', 'Bookings / Message', 'script', '2013-10-09 13:36:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1485', 'menuReports', 'backend', 'Menu Reports', 'script', '2013-10-09 14:30:39');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1486', 'report_menu_employees', 'backend', 'Reports / Employees menu', 'script', '2013-10-09 14:37:45');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1487', 'report_menu_services', 'backend', 'Reports / Services menu', 'script', '2013-10-09 14:37:57');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1488', 'report_bookings', 'backend', 'Reports / Bookings', 'script', '2013-10-09 14:50:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1489', 'report_total_bookings', 'backend', 'Reports / Total bookings', 'script', '2013-10-10 06:21:58');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1490', 'report_confirmed_bookings', 'backend', 'Reports / Confirmed bookings', 'script', '2013-10-10 06:22:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1491', 'report_pending_bookings', 'backend', 'Reports / Pending bookings', 'script', '2013-10-10 06:22:35');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1492', 'report_cancelled_bookings', 'backend', 'Reports / Cancelled bookings', 'script', '2013-10-10 06:22:48');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1493', 'report_total_amount', 'backend', 'Reports / Total amount', 'script', '2013-10-10 06:23:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1494', 'report_confirmed_amount', 'backend', 'Reports / Confirmed Bookings Amount', 'script', '2013-10-10 06:23:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1495', 'report_pending_amount', 'backend', 'Reports / Pending Bookings Amount', 'script', '2013-10-10 06:24:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1496', 'report_cancelled_amount', 'backend', 'Reports / Cancelled Bookings Amount', 'script', '2013-10-10 06:24:26');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1497', 'report_columns', 'backend', 'Reports / Columns', 'script', '2013-10-10 11:55:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1498', 'report_print', 'backend', 'Reports / Print', 'script', '2013-10-10 13:36:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1499', 'report_pdf', 'backend', 'Reports / Save as PDF', 'script', '2013-10-11 06:18:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1500', 'menu', 'backend', 'Menu', 'script', '2013-11-11 09:22:39');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1501', 'employee_view_bookings', 'backend', 'Employees / View bookings', 'script', '2013-11-11 09:23:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1502', 'employee_working_time', 'backend', 'Employees / Working time', 'script', '2013-11-11 09:23:57');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1503', 'error_titles_ARRAY_AS11', 'arrays', 'error_titles_ARRAY_AS11', 'script', '2013-11-11 09:46:01');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1504', 'error_bodies_ARRAY_AS11', 'arrays', 'error_bodies_ARRAY_AS11', 'script', '2013-11-22 09:44:50');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1505', 'service_tip_length', 'backend', 'Services / Length tooltip', 'script', '2013-11-22 09:46:30');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1506', 'service_tip_before', 'backend', 'Services / Before tooltip', 'script', '2013-11-22 09:47:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1507', 'service_tip_after', 'backend', 'Services / After tooltip', 'script', '2013-11-22 09:48:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1508', 'service_tip_employees', 'backend', 'Services / Employees tooltip', 'script', '2013-11-11 10:05:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1509', 'employee_is_subscribed_sms', 'backend', 'Employees / Send sms', 'script', '2013-11-11 12:34:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1510', 'booking_reminder_client', 'backend', 'Bookings / Send to client', 'script', '2013-11-11 12:58:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1511', 'booking_reminder_employee', 'backend', 'Bookings / Send to employee', 'script', '2013-11-11 12:59:04');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1512', 'front_click_available', 'frontend', 'Frontend / Click on available time', 'script', '2013-11-12 08:29:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1514', 'error_titles_ARRAY_AE11', 'arrays', 'error_titles_ARRAY_AE11', 'script', '2013-11-18 07:36:16');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1515', 'error_bodies_ARRAY_AE11', 'arrays', 'error_bodies_ARRAY_AE11', 'script', '2013-11-22 09:50:21');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1530', 'menuSeo', 'backend', 'Menu SEO', 'script', '2013-11-18 08:37:13');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1531', 'lblInstallConfig', 'backend', 'Install / Config', 'script', '2013-11-18 08:40:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1532', 'lblInstallConfigLocale', 'backend', 'Install / Locale', 'script', '2013-11-18 08:40:54');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1533', 'lblInstallConfigHide', 'backend', 'Install / Config hide', 'script', '2013-11-18 08:41:05');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1534', 'error_bodies_ARRAY_AO30', 'arrays', 'error_titles_ARRAY_AO30', 'script', '2013-11-18 08:45:15');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1535', 'error_titles_ARRAY_AO30', 'arrays', 'error_titles_ARRAY_AO30', 'script', '2013-11-18 08:45:32');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1536', 'lblInstallSeo_1', 'backend', 'Install / SEO Step 1', 'script', '2013-11-18 08:46:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1537', 'lblInstallSeo_2', 'backend', 'Install / SEO Step 2', 'script', '2013-11-18 08:47:16');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1538', 'lblInstallSeo_3', 'backend', 'Install / SEO Step 3', 'script', '2013-11-18 08:47:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1539', 'btnGenerate', 'backend', 'Generate', 'script', '2013-11-18 10:11:06');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1540', 'booking_index', 'backend', 'Bookings / Index', 'script', '2013-11-18 10:31:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1541', 'error_titles_ARRAY_AR01', 'arrays', 'error_titles_ARRAY_AR01', 'script', '2013-11-19 07:05:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1542', 'error_bodies_ARRAY_AR01', 'arrays', 'error_bodies_ARRAY_AR01', 'script', '2013-11-26 11:30:50');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1543', 'error_titles_ARRAY_AR02', 'arrays', 'error_titles_ARRAY_AR02', 'script', '2013-11-19 08:58:03');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1544', 'error_bodies_ARRAY_AR02', 'arrays', 'error_bodies_ARRAY_AR02', 'script', '2013-11-26 11:32:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1545', 'report_amount', 'backend', 'Reports / Amount', 'script', '2013-11-19 09:11:38');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1546', 'report_cnt', 'backend', 'Reports / Count', 'script', '2013-11-19 09:11:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1547', 'error_bodies_ARRAY_AD01', 'arrays', 'error_titles_ARRAY_AD01', 'script', '2013-11-26 11:28:15');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1548', 'error_titles_ARRAY_AD01', 'arrays', 'error_titles_ARRAY_AD01', 'script', '2013-11-19 14:39:41');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1549', 'btnApply', 'backend', 'Save', 'script', '2013-11-19 15:19:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1550', 'dashboard_filter', 'backend', 'Dashboard / Filter', 'script', '2013-11-19 15:20:36');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1756', 'error_titles_ARRAY_AT05', 'arrays', 'error_titles_ARRAY_AT05', 'script', '2013-12-12 18:48:22');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1758', 'error_bodies_ARRAY_AT05', 'arrays', 'error_bodies_ARRAY_AT05', 'script', '2013-12-12 18:49:38');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1759', 'error_bodies_ARRAY_AT06', 'arrays', 'error_bodies_ARRAY_AT06', 'script', '2013-12-12 18:57:00');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1760', 'error_titles_ARRAY_AT06', 'arrays', 'error_titles_ARRAY_AT06', 'script', '2013-12-12 18:44:11');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1761', 'error_titles_ARRAY_AT07', 'arrays', 'error_titles_ARRAY_AT07', 'script', '2013-12-12 18:57:15');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1762', 'error_bodies_ARRAY_AT07', 'arrays', 'error_bodies_ARRAY_AT07', 'script', '2013-12-12 19:35:49');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1768', 'btnToday', 'backend', 'Button / Today', 'script', '2013-11-25 09:07:23');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1769', 'btnTomorrow', 'backend', 'Button / Tomorrow', 'script', '2013-11-25 09:07:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1770', 'error_titles_ARRAY_AD02', 'arrays', 'error_titles_ARRAY_AD02', 'script', '2013-11-25 10:58:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1771', 'error_bodies_ARRAY_AD02', 'arrays', 'error_titles_ARRAY_AD02', 'script', '2013-11-25 10:59:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1772', 'payment_paypal_submit', 'frontend', 'Frontend / Paypal submit', 'script', '2013-12-18 10:49:09');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1773', 'payment_authorize_submit', 'frontend', 'Frontend / Authorize.NET submit', 'script', '2013-12-18 10:49:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1774', 'front_booking_status_ARRAY_11', 'arrays', 'front_booking_status_ARRAY_11', 'script', '2013-12-18 10:51:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1775', 'front_booking_status_ARRAY_1', 'arrays', 'front_booking_status_ARRAY_1', 'script', '2013-12-18 10:51:29');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1776', 'front_booking_status_ARRAY_4', 'arrays', 'front_booking_status_ARRAY_4', 'script', '2013-12-18 10:52:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1777', 'front_booking_status_ARRAY_3', 'arrays', 'front_booking_status_ARRAY_3', 'script', '2013-12-18 10:56:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1778', 'front_booking_na', 'frontend', 'Frontend / Booking not available', 'script', '2013-12-18 12:19:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1779', 'booking_start_time', 'backend', 'Bookings / Start time', 'script', '2013-12-18 13:58:57');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1780', 'booking_end_time', 'backend', 'Bookings / End time', 'script', '2013-12-18 13:59:11');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1781', 'error_titles_ARRAY_ABK14', 'arrays', 'error_titles_ARRAY_ABK14', 'script', '2013-12-18 14:26:25');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1782', 'error_bodies_ARRAY_ABK14', 'arrays', 'error_bodies_ARRAY_ABK14', 'script', '2013-12-18 14:27:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1783', 'error_titles_ARRAY_ABK15', 'arrays', 'error_titles_ARRAY_ABK15', 'script', '2013-12-18 14:49:29');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1784', 'error_bodies_ARRAY_ABK15', 'arrays', 'error_bodies_ARRAY_ABK15', 'script', '2013-12-18 14:50:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1785', 'front_minutes', 'frontend', 'Frontend / Minutes', 'script', '2014-01-09 09:50:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1786', 'front_day_suffix_ARRAY_st', 'arrays', 'Frontend / Day suffix: 1st', 'script', '2014-01-09 12:22:48');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1787', 'front_day_suffix_ARRAY_nd', 'arrays', 'Frontend / Day suffix: 2nd', 'script', '2014-01-09 12:23:01');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1788', 'front_day_suffix_ARRAY_rd', 'arrays', 'Frontend / Day suffix: 3rd', 'script', '2014-01-09 12:23:32');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1789', 'front_day_suffix_ARRAY_th', 'arrays', 'Frontend / Day suffix: Nth', 'script', '2014-01-09 12:24:47');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1790', 'front_on', 'frontend', 'Frontend / On', 'script', '2014-01-09 12:29:55');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1791', 'front_back_services', 'backend', 'Frontend / Back to services', 'script', '2014-01-09 12:39:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1792', 'front_checkout', 'frontend', 'Frontend / Checkout', 'script', '2014-01-09 12:55:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1793', 'front_cart_done', 'frontend', 'Frontend / Service added', 'script', '2014-01-09 12:56:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1794', 'front_app_ARRAY_v_remote', 'arrays', 'front_app_ARRAY_v_remote', 'script', '2014-01-09 15:12:14');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1795', 'front_from', 'backend', 'Frontend / From', 'script', '2014-01-09 15:41:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1796', 'front_till', 'backend', 'Frontend / Till', 'script', '2014-01-09 15:41:28');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1797', 'cancel_err_ARRAY_1', 'arrays', 'cancel_err_ARRAY_1', 'script', '2014-01-22 07:55:23');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1798', 'cancel_err_ARRAY_2', 'arrays', 'cancel_err_ARRAY_2', 'script', '2014-01-22 07:56:24');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1799', 'cancel_err_ARRAY_3', 'arrays', 'cancel_err_ARRAY_3', 'script', '2014-01-22 07:56:17');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1800', 'cancel_err_ARRAY_4', 'arrays', 'cancel_err_ARRAY_4', 'script', '2014-01-22 07:56:38');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1801', 'cancel_err_ARRAY_5', 'arrays', 'cancel_err_ARRAY_5', 'script', '2014-01-22 07:57:02');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1802', 'cancel_details', 'frontend', 'Cancel / Customer Details', 'script', '2014-01-22 08:42:56');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1803', 'cancel_confirm', 'frontend', 'Cancel / Cancel button', 'script', '2014-01-22 08:41:25');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1804', 'cancel_services', 'frontend', 'Cancel / Booking Services', 'script', '2014-01-22 08:42:33');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1805', 'cancel_title', 'frontend', 'Cancel / Page title', 'script', '2014-01-22 08:43:29');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1806', 'confirmation_employee_confirmation', 'backend', 'Confirmation / Employee confirmation title', 'script', '2014-01-30 07:19:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1807', 'confirmation_employee_payment', 'backend', 'Confirmation / Employee payment title', 'script', '2014-01-30 07:19:16');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1808', 'time_update_default', 'backend', 'Working Time / Update default working time', 'script', '2014-01-30 09:04:39');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1809', 'opt_o_layout', 'backend', 'Options / Layout', 'script', '2014-02-06 09:05:31');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1810', 'front_single_date', 'frontend', 'Single / Select date', 'script', '2014-02-06 10:36:29');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1811', 'front_single_service', 'frontend', 'Single / Service', 'script', '2014-02-06 10:36:52');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1812', 'front_single_time', 'frontend', 'Single / Select time', 'script', '2014-02-06 10:37:06');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1813', 'front_single_employee', 'frontend', 'Single / Employee', 'script', '2014-02-06 10:37:37');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1814', 'btnBook', 'backend', 'Button / Book', 'script', '2014-02-06 10:41:10');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1815', 'front_single_date_service', 'frontend', 'Single / Select date and service', 'script', '2014-02-06 10:41:55');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1816', 'front_single_choose_date', 'frontend', 'Single / Choose date', 'script', '2014-02-06 10:45:32');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1817', 'single_date', 'frontend', 'Single / Date', 'script', '2014-02-10 14:04:43');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1818', 'single_price', 'frontend', 'Single / Price', 'script', '2014-02-10 14:04:53');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1819', 'front_booking_status_ARRAY_5', 'arrays', 'front_booking_status_ARRAY_5', 'script', '2014-02-17 07:38:07');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1820', 'front_single_na', 'frontend', 'Single / Not available', 'script', '2014-02-20 15:21:19');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1821', 'plugin_locale_languages', 'backend', 'Locale plugin / Languages', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1822', 'plugin_locale_titles', 'backend', 'Locale plugin / Titles', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1823', 'plugin_locale_index_title', 'backend', 'Locale plugin / Languages info title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1824', 'plugin_locale_index_body', 'backend', 'Locale plugin / Languages info body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1825', 'plugin_locale_titles_title', 'backend', 'Locale plugin / Titles info title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1826', 'plugin_locale_titles_body', 'backend', 'Locale plugin / Titles info body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1827', 'plugin_locale_lbl_title', 'backend', 'Locale plugin / Title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1828', 'plugin_locale_lbl_flag', 'backend', 'Locale plugin / Flag', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1829', 'plugin_locale_lbl_is_default', 'backend', 'Locale plugin / Is default', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1830', 'plugin_locale_lbl_order', 'backend', 'Locale plugin / Order', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1831', 'plugin_locale_add_lang', 'backend', 'Locale plugin / Add Language', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1832', 'plugin_locale_lbl_field', 'backend', 'Locale plugin / Field', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1833', 'plugin_locale_lbl_value', 'backend', 'Locale plugin / Value', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1834', 'plugin_locale_type_backend', 'backend', 'Locale plugin / Back-end title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1835', 'plugin_locale_type_frontend', 'backend', 'Locale plugin / Front-end title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1836', 'plugin_locale_type_arrays', 'backend', 'Locale plugin / Special title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1837', 'error_titles_ARRAY_PAL01', 'arrays', 'Locale plugin / Status title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1838', 'error_bodies_ARRAY_PAL01', 'arrays', 'Locale plugin / Status body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1839', 'plugin_locale_lbl_rows', 'backend', 'Locale plugin / Per page', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1840', 'error_titles_ARRAY_PAL02', 'arrays', 'Locale plugin / Status title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1841', 'error_bodies_ARRAY_PAL02', 'arrays', 'Locale plugin / Status body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1842', 'error_titles_ARRAY_PAL03', 'arrays', 'Locale plugin / Status title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1843', 'error_bodies_ARRAY_PAL03', 'arrays', 'Locale plugin / Status body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1844', 'error_titles_ARRAY_PAL04', 'arrays', 'Locale plugin / Status title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1845', 'error_bodies_ARRAY_PAL04', 'arrays', 'Locale plugin / Status body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1846', 'plugin_locale_import_export', 'backend', 'Locale plugin / Import Export menu', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1847', 'plugin_locale_import', 'backend', 'Locale plugin / Import', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1848', 'plugin_locale_export', 'backend', 'Locale plugin / Export', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1849', 'plugin_locale_language', 'backend', 'Locale plugin / Language', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1850', 'plugin_locale_browse', 'backend', 'Locale plugin / Browse your computer', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1851', 'plugin_locale_ie_title', 'backend', 'Locale plugin / Import Export (title)', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1852', 'plugin_locale_ie_body', 'backend', 'Locale plugin / Import Export (body)', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1853', 'error_titles_ARRAY_PBU01', 'arrays', 'error_titles_ARRAY_PBU01', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1854', 'error_titles_ARRAY_PBU02', 'arrays', 'error_titles_ARRAY_PBU02', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1855', 'error_titles_ARRAY_PBU03', 'arrays', 'error_titles_ARRAY_PBU03', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1856', 'error_titles_ARRAY_PBU04', 'arrays', 'error_titles_ARRAY_PBU04', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1857', 'error_bodies_ARRAY_PBU01', 'arrays', 'error_bodies_ARRAY_PBU01', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1858', 'error_bodies_ARRAY_PBU02', 'arrays', 'error_bodies_ARRAY_PBU02', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1859', 'error_bodies_ARRAY_PBU03', 'arrays', 'error_bodies_ARRAY_PBU03', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1860', 'error_bodies_ARRAY_PBU04', 'arrays', 'error_bodies_ARRAY_PBU04', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1861', 'plugin_backup_menu_backup', 'backend', 'Backup plugin / Menu Backup', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1862', 'plugin_backup_database', 'backend', 'Backup plugin / Backup database', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1863', 'plugin_backup_files', 'backend', 'Backup plugin / Backup files', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1864', 'plugin_backup_btn_backup', 'backend', 'Backup plugin / Backup button', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1865', 'plugin_log_menu_log', 'backend', 'Log plugin / Menu Log', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1866', 'plugin_log_menu_config', 'backend', 'Log plugin / Menu Config', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1867', 'plugin_log_btn_empty', 'backend', 'Log plugin / Empty button', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1868', 'error_titles_ARRAY_PLG01', 'arrays', 'error_titles_ARRAY_PLG01', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1869', 'error_bodies_ARRAY_PLG01', 'arrays', 'error_bodies_ARRAY_PLG01', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1870', 'plugin_one_admin_menu_index', 'backend', 'One Admin plugin / List', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1871', 'plugin_one_admin_btn_add', 'backend', 'One Admin plugin / Add button', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1872', 'plugin_country_name', 'backend', 'Country plugin / Country name', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1873', 'plugin_country_alpha_2', 'backend', 'Country plugin / Alpha 2', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1874', 'plugin_country_alpha_3', 'backend', 'Country plugin / Alpha 3', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1875', 'plugin_country_status', 'backend', 'Country plugin / Status', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1876', 'plugin_country_btn_add', 'backend', 'Country plugin / Button Add', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1877', 'plugin_country_statuses_ARRAY_T', 'arrays', 'Country plugin / Status (active)', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1878', 'plugin_country_statuses_ARRAY_F', 'arrays', 'Country plugin / Status (inactive)', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1879', 'plugin_country_btn_save', 'backend', 'Country plugin / Button Save', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1880', 'plugin_country_btn_cancel', 'backend', 'Country plugin / Button Cancel', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1881', 'plugin_country_menu_countries', 'backend', 'Country plugin / Menu Countries', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1882', 'error_titles_ARRAY_PCY01', 'arrays', 'error_titles_ARRAY_PCY01', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1883', 'error_titles_ARRAY_PCY03', 'arrays', 'error_titles_ARRAY_PCY03', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1884', 'error_titles_ARRAY_PCY04', 'arrays', 'error_titles_ARRAY_PCY04', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1885', 'error_titles_ARRAY_PCY08', 'arrays', 'error_titles_ARRAY_PCY08', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1886', 'error_titles_ARRAY_PCY10', 'arrays', 'error_titles_ARRAY_PCY10', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1887', 'error_titles_ARRAY_PCY11', 'arrays', 'error_titles_ARRAY_PCY11', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1888', 'error_titles_ARRAY_PCY12', 'arrays', 'error_titles_ARRAY_PCY12', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1889', 'error_bodies_ARRAY_PCY01', 'arrays', 'error_bodies_ARRAY_PCY01', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1890', 'error_bodies_ARRAY_PCY03', 'arrays', 'error_bodies_ARRAY_PCY03', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1891', 'error_bodies_ARRAY_PCY04', 'arrays', 'error_bodies_ARRAY_PCY04', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1892', 'error_bodies_ARRAY_PCY08', 'arrays', 'error_bodies_ARRAY_PCY08', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1893', 'error_bodies_ARRAY_PCY10', 'arrays', 'error_bodies_ARRAY_PCY10', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1894', 'error_bodies_ARRAY_PCY11', 'arrays', 'error_bodies_ARRAY_PCY11', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1895', 'error_bodies_ARRAY_PCY12', 'arrays', 'error_bodies_ARRAY_PCY12', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1896', 'plugin_country_delete_confirmation', 'backend', 'Country plugin / Delete confirmation', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1897', 'plugin_country_delete_selected', 'backend', 'Country plugin / Delete selected', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1898', 'plugin_country_btn_all', 'backend', 'Country plugin / Button All', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1899', 'plugin_country_btn_search', 'backend', 'Country plugin / Button Search', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1900', 'plugin_invoice_menu_invoices', 'backend', 'Invoice plugin / Menu Invoices', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1901', 'plugin_invoice_config', 'backend', 'Invoice plugin / Invoice config', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1902', 'plugin_invoice_i_logo', 'backend', 'Invoice plugin / Company logo', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1903', 'plugin_invoice_i_company', 'backend', 'Invoice plugin / Company name', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1904', 'plugin_invoice_i_name', 'backend', 'Invoice plugin / Name', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1905', 'plugin_invoice_i_street_address', 'backend', 'Invoice plugin / Street address', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1906', 'plugin_invoice_i_city', 'backend', 'Invoice plugin / City', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1907', 'plugin_invoice_i_state', 'backend', 'Invoice plugin / State', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1908', 'plugin_invoice_i_zip', 'backend', 'Invoice plugin / Zip', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1909', 'plugin_invoice_i_phone', 'backend', 'Invoice plugin / Phone', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1910', 'plugin_invoice_i_fax', 'backend', 'Invoice plugin / Fax', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1911', 'plugin_invoice_i_email', 'backend', 'Invoice plugin / Email', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1912', 'plugin_invoice_i_url', 'backend', 'Invoice plugin / Website', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1913', 'error_titles_ARRAY_PIN01', 'arrays', 'Invoice plugin / Info title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1914', 'error_bodies_ARRAY_PIN01', 'arrays', 'Invoice plugin / Info body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1915', 'error_titles_ARRAY_PIN02', 'arrays', 'Invoice plugin / Invoice config updated title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1916', 'error_bodies_ARRAY_PIN02', 'arrays', 'Invoice plugin / Info body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1917', 'error_titles_ARRAY_PIN03', 'arrays', 'Invoice plugin / Upload failed', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1918', 'plugin_invoice_template', 'backend', 'Invoice plugin / Invoice Template', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1919', 'plugin_invoice_delete_logo_title', 'backend', 'Invoice plugin / Delete logo title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1920', 'plugin_invoice_delete_logo_body', 'backend', 'Invoice plugin / Delete logo body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1921', 'plugin_invoice_billing_info', 'backend', 'Invoice plugin / Billing information', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1922', 'plugin_invoice_shipping_info', 'backend', 'Invoice plugin / Shipping information', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1923', 'plugin_invoice_company_info', 'backend', 'Invoice plugin / Company information', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1924', 'plugin_invoice_payment_info', 'backend', 'Invoice plugin / Payment information', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1925', 'plugin_invoice_i_address', 'backend', 'Invoice plugin / Address', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1926', 'plugin_invoice_i_billing_address', 'backend', 'Invoice plugin / Billing address', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1927', 'plugin_invoice_general_info', 'backend', 'Invoice plugin / General information', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1928', 'plugin_invoice_i_uuid', 'backend', 'Invoice plugin / Invoice no.', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1929', 'plugin_invoice_i_order_id', 'backend', 'Invoice plugin / Order no.', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1930', 'plugin_invoice_i_issue_date', 'backend', 'Invoice plugin / Issue date', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1931', 'plugin_invoice_i_due_date', 'backend', 'Invoice plugin / Due date', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1932', 'plugin_invoice_i_shipping_date', 'backend', 'Invoice plugin / Shipping date', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1933', 'plugin_invoice_i_shipping_terms', 'backend', 'Invoice plugin / Shipping terms', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1934', 'plugin_invoice_i_subtotal', 'backend', 'Invoice plugin / Subtotal', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1935', 'plugin_invoice_i_discount', 'backend', 'Invoice plugin / Discount', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1936', 'plugin_invoice_i_tax', 'backend', 'Invoice plugin / Tax', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1937', 'plugin_invoice_i_shipping', 'backend', 'Invoice plugin / Tax', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1938', 'plugin_invoice_i_total', 'backend', 'Invoice plugin / Total', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1939', 'plugin_invoice_i_paid_deposit', 'backend', 'Invoice plugin / Paid deposit', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1940', 'plugin_invoice_i_amount_due', 'backend', 'Invoice plugin / Amount due', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1941', 'plugin_invoice_i_currency', 'backend', 'Invoice plugin / Currency', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1942', 'plugin_invoice_i_notes', 'backend', 'Invoice plugin / Notes', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1943', 'plugin_invoice_i_shipping_address', 'backend', 'Invoice plugin / Shipping address', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1944', 'plugin_invoice_i_created', 'backend', 'Invoice plugin / Created', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1945', 'plugin_invoice_i_modified', 'backend', 'Invoice plugin / Modified', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1946', 'plugin_invoice_i_item', 'backend', 'Invoice plugin / Item', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1947', 'plugin_invoice_i_qty', 'backend', 'Invoice plugin / Qty', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1948', 'plugin_invoice_i_unit', 'backend', 'Invoice plugin / Unit price', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1949', 'plugin_invoice_i_amount', 'backend', 'Invoice plugin / Amount', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1950', 'plugin_invoice_add_item_title', 'backend', 'Invoice plugin / Add item title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1951', 'plugin_invoice_edit_item_title', 'backend', 'Invoice plugin / Update item title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1952', 'plugin_invoice_i_description', 'backend', 'Invoice plugin / Description', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1953', 'plugin_invoice_i_accept_payments', 'backend', 'Invoice plugin / Accept payments', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1954', 'plugin_invoice_print', 'backend', 'Invoice plugin / Print invoice', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1955', 'plugin_invoice_send', 'backend', 'Invoice plugin / Send invoice', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1956', 'plugin_invoice_view', 'backend', 'Invoice plugin / View invoice', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1957', 'plugin_invoice_send_invoice_title', 'backend', 'Invoice plugin / Send invoice dialog title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1958', 'plugin_invoice_send_subject', 'backend', 'Invoice plugin / Send invoice subject', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1959', 'plugin_invoice_items_info', 'backend', 'Invoice plugin / Items information', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1960', 'plugin_invoice_i_accept_paypal', 'backend', 'Invoice plugin / Accept payments with PayPal', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1961', 'plugin_invoice_i_accept_authorize', 'backend', 'Invoice plugin / Accept payments with Authorize.NET', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1962', 'plugin_invoice_i_accept_creditcard', 'backend', 'Invoice plugin / Accept payments with Credit Card', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1963', 'plugin_invoice_i_accept_bank', 'backend', 'Invoice plugin / Accept payments with Bank Account', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1964', 'plugin_invoice_i_s_include', 'backend', 'Invoice plugin / Include Shipping information', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1965', 'plugin_invoice_i_s_shipping_address', 'backend', 'Invoice plugin / Include Shipping address', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1966', 'plugin_invoice_i_s_company', 'backend', 'Invoice plugin / Include Company', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1967', 'plugin_invoice_i_s_name', 'backend', 'Invoice plugin / Include Name', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1968', 'plugin_invoice_i_s_address', 'backend', 'Invoice plugin / Include Address', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1969', 'plugin_invoice_i_s_city', 'backend', 'Invoice plugin / Include City', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1970', 'plugin_invoice_i_s_state', 'backend', 'Invoice plugin / Include State', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1971', 'plugin_invoice_i_s_zip', 'backend', 'Invoice plugin / Include Zip', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1972', 'plugin_invoice_i_s_phone', 'backend', 'Invoice plugin / Include Phone', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1973', 'plugin_invoice_i_s_fax', 'backend', 'Invoice plugin / Include Fax', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1974', 'plugin_invoice_i_s_email', 'backend', 'Invoice plugin / Include Email', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1975', 'plugin_invoice_i_s_url', 'backend', 'Invoice plugin / Include Website', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1976', 'plugin_invoice_i_s_street_address', 'backend', 'Invoice plugin / Include Street address', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1977', 'error_titles_ARRAY_PIN05', 'arrays', 'Invoice plugin / Invoice updated title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1978', 'error_bodies_ARRAY_PIN05', 'arrays', 'Invoice plugin / Invoice updated body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1979', 'error_titles_ARRAY_PIN04', 'arrays', 'Invoice plugin / Invoice Not Found title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1980', 'error_bodies_ARRAY_PIN04', 'arrays', 'Invoice plugin / Invoice Not Found body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1981', 'error_titles_ARRAY_PIN06', 'arrays', 'Invoice plugin / Invalid data title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1982', 'error_bodies_ARRAY_PIN06', 'arrays', 'Invoice plugin / Invalid data body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1983', 'plugin_invoice_i_status', 'backend', 'Invoice plugin / Status', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1984', 'plugin_invoice_pay_with_paypal', 'backend', 'Invoice plugin / Pay with Paypal', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1985', 'plugin_invoice_pay_with_authorize', 'backend', 'Invoice plugin / Pay with Authorize.Net', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1986', 'plugin_invoice_pay_with_creditcard', 'backend', 'Invoice plugin / Pay with Credit Card', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1987', 'plugin_invoice_pay_with_bank', 'backend', 'Invoice plugin / Pay with Bank Account', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1988', 'plugin_invoice_pay_now', 'backend', 'Invoice plugin / Pay Now', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1989', 'plugin_invoice_paypal_title', 'frontend', 'Invoice plugin / Paypal title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1990', 'plugin_invoice_authorize_title', 'frontend', 'Invoice plugin / Payment Authorize title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1991', 'plugin_invoice_i_paypal_address', 'backend', 'Invoice plugin / Paypal address', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1992', 'plugin_invoice_i_authorize_tz', 'backend', 'Invoice plugin / Authorize.Net Timezone', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1993', 'plugin_invoice_i_authorize_mid', 'backend', 'Invoice plugin / Authorize.Net Merchant ID', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1994', 'plugin_invoice_i_authorize_key', 'backend', 'Invoice plugin / Authorize.Net Transaction Key', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1995', 'plugin_invoice_i_bank_account', 'backend', 'Invoice plugin / Bank Account', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1996', 'plugin_invoice_paypal_redirect', 'backend', 'Invoice plugin / Paypal redirect', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1997', 'plugin_invoice_authorize_redirect', 'backend', 'Invoice plugin / Authorize.Net redirect', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1998', 'plugin_invoice_paypal_proceed', 'backend', 'Invoice plugin / Paypal proceed button', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('1999', 'plugin_invoice_authorize_proceed', 'backend', 'Invoice plugin / Authorize.Net proceed button', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2000', 'plugin_invoice_i_delete_title', 'backend', 'Invoice plugin / Delete title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2001', 'plugin_invoice_i_delete_body', 'backend', 'Invoice plugin / Delete body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2002', 'plugin_invoice_i_is_shipped', 'backend', 'Invoice plugin / Is shipped', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2003', 'plugin_invoice_i_s_date', 'backend', 'Invoice plugin / Include Shipping date', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2004', 'plugin_invoice_i_s_terms', 'backend', 'Invoice plugin / Include Shipping terms', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2005', 'plugin_invoice_i_s_is_shipped', 'backend', 'Invoice plugin / Include Is Shipped', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2006', 'plugin_invoice_statuses_ARRAY_not_paid', 'arrays', 'Invoice plugin / Status: Not Paid', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2007', 'plugin_invoice_statuses_ARRAY_paid', 'arrays', 'Invoice plugin / Status: Paid', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2008', 'plugin_invoice_statuses_ARRAY_cancelled', 'arrays', 'Invoice plugin / Status: Cancelled', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2009', 'plugin_invoice_i_num', 'backend', 'Invoice plugin / No.', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2010', 'plugin_invoice_add', 'backend', 'Invoice plugin / Add', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2011', 'plugin_invoice_save', 'backend', 'Invoice plugin / Save', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2012', 'plugin_invoice_i_booking_url', 'backend', 'Invoice plugin / Booking URL - Token: {ORDER_ID}', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2013', 'plugin_invoice_i_s_shipping', 'backend', 'Invoice plugin / Include Shipping', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2014', 'error_titles_ARRAY_PIN07', 'arrays', 'Invoice plugin / Invoice added title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2015', 'error_bodies_ARRAY_PIN07', 'arrays', 'Invoice plugin / Invoice added body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2016', 'error_titles_ARRAY_PIN08', 'arrays', 'Invoice plugin / Invoice failed to add title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2017', 'error_bodies_ARRAY_PIN08', 'arrays', 'Invoice plugin / Invoice failed to add body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2018', 'error_titles_ARRAY_PIN09', 'arrays', 'Invoice plugin / Invoice Send title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2019', 'error_bodies_ARRAY_PIN09', 'arrays', 'Invoice plugin / Invoice send body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2020', 'error_titles_ARRAY_PIN10', 'arrays', 'Invoice plugin / Invoice heading title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2021', 'error_bodies_ARRAY_PIN10', 'arrays', 'Invoice plugin / Invoice heading body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2022', 'error_titles_ARRAY_PIN11', 'arrays', 'Invoice plugin / Invoice billing title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2023', 'error_bodies_ARRAY_PIN11', 'arrays', 'Invoice plugin / Invoice billing body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2024', 'plugin_invoice_i_qty_is_int', 'backend', 'Invoice plugin / Quantity format', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2025', 'plugin_invoice_i_qty_int', 'backend', 'Invoice plugin / Quantity INT instead of FLOAT', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2026', 'plugin_invoice_i_authorize_hash', 'backend', 'Invoice plugin / Authorize.Net hash value', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2027', 'plugin_sms_menu_sms', 'backend', 'SMS plugin / Menu SMS', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2028', 'plugin_sms_config', 'backend', 'SMS plugin / SMS config', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2029', 'plugin_sms_number', 'backend', 'SMS plugin / Number', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2030', 'plugin_sms_text', 'backend', 'SMS plugin / Text', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2031', 'plugin_sms_status', 'backend', 'SMS plugin / Status', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2032', 'plugin_sms_created', 'backend', 'SMS plugin / Date & Time', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2033', 'plugin_sms_api', 'backend', 'SMS plugin / API Key', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2034', 'error_titles_ARRAY_PSS01', 'arrays', 'SMS plugin / Info title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2035', 'error_bodies_ARRAY_PSS01', 'arrays', 'SMS plugin / Info body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2036', 'error_titles_ARRAY_PSS02', 'arrays', 'SMS plugin / API key updates info title', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2037', 'error_bodies_ARRAY_PSS02', 'arrays', 'SMS plugin / API key updates info body', 'plugin', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2038', 'opt_o_layout_backend', 'backend', 'Option/ Layout Backend', 'script', '');
INSERT INTO `salondoris_hey_appscheduler_fields` VALUES('2039', 'opt_o_custom_status', 'backend', 'Option/ Custom Status', 'script', '');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_formstyle`;

CREATE TABLE `salondoris_hey_appscheduler_formstyle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logo` varchar(250) DEFAULT NULL,
  `banner` varchar(250) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `background` varchar(250) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_formstyle` VALUES('6', 'http://ajanvaraus-kodinterra.fi/nokia/wp-content/plugins/AppointmentScheduler-22-4-2014/library/app/web/img/backend/logo.png', 'http://www.sourcevl.com/appointment/wp-content/uploads/sites/2/2014/06/banner.jpg', '#444', '#444', '', '');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_multi_lang`;

CREATE TABLE `salondoris_hey_appscheduler_multi_lang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `locale` tinyint(3) unsigned DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `content` text,
  `source` enum('script','plugin','data') DEFAULT 'script',
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_id` (`foreign_id`,`model`,`locale`,`field`)
) ENGINE=InnoDB AUTO_INCREMENT=3921 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1', '5', 'pjField', '1', 'title', 'Username', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2', '6', 'pjField', '1', 'title', 'Password', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3', '7', 'pjField', '1', 'title', 'Email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('4', '8', 'pjField', '1', 'title', 'URL', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('5', '13', 'pjField', '1', 'title', 'DateTime', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('6', '16', 'pjField', '1', 'title', 'Save', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('7', '17', 'pjField', '1', 'title', 'Reset', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('8', '18', 'pjField', '1', 'title', 'Add language', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('9', '22', 'pjField', '1', 'title', 'Multi Lang', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('10', '23', 'pjField', '1', 'title', 'Plugins', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('11', '24', 'pjField', '1', 'title', 'Users', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('12', '25', 'pjField', '1', 'title', 'Options', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('13', '26', 'pjField', '1', 'title', 'Logout', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('14', '31', 'pjField', '1', 'title', 'Update', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('15', '36', 'pjField', '1', 'title', 'Choose', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('16', '37', 'pjField', '1', 'title', 'Search', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('17', '40', 'pjField', '1', 'title', 'Back-end titles', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('18', '41', 'pjField', '1', 'title', 'Front-end titles', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('19', '42', 'pjField', '1', 'title', 'Languages', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('20', '44', 'pjField', '1', 'title', 'Admin Login', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('21', '45', 'pjField', '1', 'title', 'Login', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('22', '47', 'pjField', '1', 'title', 'Dashboard', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('23', '57', 'pjField', '1', 'title', 'Option list', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('24', '58', 'pjField', '1', 'title', 'Add +', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('25', '62', 'pjField', '1', 'title', 'Delete', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('26', '65', 'pjField', '1', 'title', 'Type', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('27', '66', 'pjField', '1', 'title', 'Name', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('28', '67', 'pjField', '1', 'title', 'Role', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('29', '68', 'pjField', '1', 'title', 'Status', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('30', '69', 'pjField', '1', 'title', 'Is confirmed', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('31', '70', 'pjField', '1', 'title', 'Update user', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('32', '71', 'pjField', '1', 'title', 'Add user', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('33', '72', 'pjField', '1', 'title', 'Value', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('34', '73', 'pjField', '1', 'title', 'Option', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('35', '74', 'pjField', '1', 'title', 'days', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('36', '115', 'pjField', '1', 'title', 'Languages', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('73', '116', 'pjField', '1', 'title', 'Yes', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('75', '117', 'pjField', '1', 'title', 'No', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('77', '338', 'pjField', '1', 'title', 'Error', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('79', '347', 'pjField', '1', 'title', '&laquo; Back', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('81', '355', 'pjField', '1', 'title', 'Cancel', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('83', '356', 'pjField', '1', 'title', 'Forgot password', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('85', '357', 'pjField', '1', 'title', 'Password reminder', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('87', '358', 'pjField', '1', 'title', 'Send', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('89', '359', 'pjField', '1', 'title', 'Password reminder', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('91', '360', 'pjField', '1', 'title', 'Dear {Name},Your password: {Password}', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('93', '365', 'pjField', '1', 'title', 'Profile', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('95', '380', 'pjField', '1', 'title', 'Languages Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('97', '381', 'pjField', '1', 'title', 'Languages Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('99', '382', 'pjField', '1', 'title', 'Languages Backend Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('101', '383', 'pjField', '1', 'title', 'Languages Backend Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('103', '384', 'pjField', '1', 'title', 'Languages Frontend Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('105', '385', 'pjField', '1', 'title', 'Languages Frontend Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('107', '386', 'pjField', '1', 'title', 'Listing Prices Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('109', '387', 'pjField', '1', 'title', 'Listing Prices Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('111', '388', 'pjField', '1', 'title', 'Listing Bookings Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('113', '389', 'pjField', '1', 'title', 'Listing Bookings Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('115', '390', 'pjField', '1', 'title', 'Listing Contact Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('117', '391', 'pjField', '1', 'title', 'Listing Contact Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('119', '392', 'pjField', '1', 'title', 'Listing Address Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('121', '393', 'pjField', '1', 'title', 'Listing Address Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('123', '395', 'pjField', '1', 'title', 'Extend exp.date Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('125', '396', 'pjField', '1', 'title', 'Extend exp.date Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('127', '408', 'pjField', '1', 'title', 'Backup', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('129', '409', 'pjField', '1', 'title', 'Backup', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('131', '410', 'pjField', '1', 'title', 'Backup database', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('133', '411', 'pjField', '1', 'title', 'Backup files', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('135', '412', 'pjField', '1', 'title', 'Choose Action', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('137', '413', 'pjField', '1', 'title', 'Go to page:', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('139', '414', 'pjField', '1', 'title', 'Total items:', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('141', '415', 'pjField', '1', 'title', 'Items per page', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('143', '416', 'pjField', '1', 'title', 'Prev page', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('145', '417', 'pjField', '1', 'title', '&laquo; Prev', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('147', '418', 'pjField', '1', 'title', 'Next page', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('149', '419', 'pjField', '1', 'title', 'Next &raquo;', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('151', '420', 'pjField', '1', 'title', 'Delete confirmation', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('153', '421', 'pjField', '1', 'title', 'Are you sure you want to delete selected record?', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('155', '422', 'pjField', '1', 'title', 'Action confirmation', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('157', '423', 'pjField', '1', 'title', 'OK', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('159', '424', 'pjField', '1', 'title', 'Cancel', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('161', '425', 'pjField', '1', 'title', 'Delete', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('163', '426', 'pjField', '1', 'title', 'No records found', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('165', '433', 'pjField', '1', 'title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('167', '434', 'pjField', '1', 'title', 'IP address', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('169', '435', 'pjField', '1', 'title', 'Registration date/time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('171', '441', 'pjField', '1', 'title', 'Currency', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('173', '442', 'pjField', '1', 'title', 'Date format', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('175', '451', 'pjField', '1', 'title', 'Timezone', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('177', '452', 'pjField', '1', 'title', 'First day of the week', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('179', '455', 'pjField', '1', 'title', 'Active', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('180', '456', 'pjField', '1', 'title', 'Inactive', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('181', '457', 'pjField', '1', 'title', 'Active', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('182', '458', 'pjField', '1', 'title', 'Inactive', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('183', '471', 'pjField', '1', 'title', 'Yes', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('184', '472', 'pjField', '1', 'title', 'No', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('185', '476', 'pjField', '1', 'title', 'Mr.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('186', '477', 'pjField', '1', 'title', 'Mrs.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('187', '478', 'pjField', '1', 'title', 'Miss', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('188', '479', 'pjField', '1', 'title', 'Ms.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('189', '480', 'pjField', '1', 'title', 'Dr.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('190', '481', 'pjField', '1', 'title', 'Prof.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('191', '482', 'pjField', '1', 'title', 'Rev.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('192', '483', 'pjField', '1', 'title', 'Other', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('193', '496', 'pjField', '1', 'title', 'GMT-12:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('194', '497', 'pjField', '1', 'title', 'GMT-11:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('195', '498', 'pjField', '1', 'title', 'GMT-10:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('196', '499', 'pjField', '1', 'title', 'GMT-09:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('197', '500', 'pjField', '1', 'title', 'GMT-08:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('198', '501', 'pjField', '1', 'title', 'GMT-07:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('199', '502', 'pjField', '1', 'title', 'GMT-06:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('200', '503', 'pjField', '1', 'title', 'GMT-05:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('201', '504', 'pjField', '1', 'title', 'GMT-04:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('202', '505', 'pjField', '1', 'title', 'GMT-03:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('203', '506', 'pjField', '1', 'title', 'GMT-02:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('204', '507', 'pjField', '1', 'title', 'GMT-01:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('205', '508', 'pjField', '1', 'title', 'GMT', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('206', '509', 'pjField', '1', 'title', 'GMT+01:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('207', '510', 'pjField', '1', 'title', 'GMT+02:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('208', '511', 'pjField', '1', 'title', 'GMT+03:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('209', '512', 'pjField', '1', 'title', 'GMT+04:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('210', '513', 'pjField', '1', 'title', 'GMT+05:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('211', '514', 'pjField', '1', 'title', 'GMT+06:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('212', '515', 'pjField', '1', 'title', 'GMT+07:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('213', '516', 'pjField', '1', 'title', 'GMT+08:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('214', '517', 'pjField', '1', 'title', 'GMT+09:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('215', '518', 'pjField', '1', 'title', 'GMT+10:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('216', '519', 'pjField', '1', 'title', 'GMT+11:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('217', '520', 'pjField', '1', 'title', 'GMT+12:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('218', '521', 'pjField', '1', 'title', 'GMT+13:00', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('219', '540', 'pjField', '1', 'title', 'User updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('220', '541', 'pjField', '1', 'title', 'User added!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('221', '542', 'pjField', '1', 'title', 'User failed to add.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('222', '543', 'pjField', '1', 'title', 'User not found.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('223', '544', 'pjField', '1', 'title', 'Options updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('224', '552', 'pjField', '1', 'title', 'Backup', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('225', '553', 'pjField', '1', 'title', 'Backup complete!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('226', '554', 'pjField', '1', 'title', 'Backup failed!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('227', '555', 'pjField', '1', 'title', 'Backup failed!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('228', '556', 'pjField', '1', 'title', 'Account not found!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('229', '557', 'pjField', '1', 'title', 'Password send!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('230', '558', 'pjField', '1', 'title', 'Password not send!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('231', '559', 'pjField', '1', 'title', 'Profile updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('232', '578', 'pjField', '1', 'title', 'All the changes made to this user have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('233', '579', 'pjField', '1', 'title', 'All the changes made to this user have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('234', '580', 'pjField', '1', 'title', 'We are sorry, but the user has not been added.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('235', '581', 'pjField', '1', 'title', 'User your looking for is missing.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('236', '582', 'pjField', '1', 'title', 'All the changes made to options have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('237', '589', 'pjField', '1', 'title', 'All the changes made to titles have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('238', '590', 'pjField', '1', 'title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at ligula non arcu dignissim pretium. Praesent in magna nulla, in porta leo.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('239', '591', 'pjField', '1', 'title', 'All backup files have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('240', '592', 'pjField', '1', 'title', 'No option was selected.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('241', '593', 'pjField', '1', 'title', 'Backup not performed.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('242', '594', 'pjField', '1', 'title', 'Given email address is not associated with any account.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('243', '595', 'pjField', '1', 'title', 'For further instructions please check your mailbox.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('244', '596', 'pjField', '1', 'title', 'We''re sorry, please try again later.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('245', '597', 'pjField', '1', 'title', 'All the changes made to your profile have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('246', '627', 'pjField', '1', 'title', 'January', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('247', '628', 'pjField', '1', 'title', 'February', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('248', '629', 'pjField', '1', 'title', 'March', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('249', '630', 'pjField', '1', 'title', 'April', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('250', '631', 'pjField', '1', 'title', 'May', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('251', '632', 'pjField', '1', 'title', 'June', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('252', '633', 'pjField', '1', 'title', 'July', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('253', '634', 'pjField', '1', 'title', 'August', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('254', '635', 'pjField', '1', 'title', 'September', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('255', '636', 'pjField', '1', 'title', 'October', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('256', '637', 'pjField', '1', 'title', 'November', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('257', '638', 'pjField', '1', 'title', 'December', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('258', '639', 'pjField', '1', 'title', 'Sunday', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('259', '640', 'pjField', '1', 'title', 'Monday', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('260', '641', 'pjField', '1', 'title', 'Tuesday', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('261', '642', 'pjField', '1', 'title', 'Wednesday', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('262', '643', 'pjField', '1', 'title', 'Thursday', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('263', '644', 'pjField', '1', 'title', 'Friday', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('264', '645', 'pjField', '1', 'title', 'Saturday', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('265', '646', 'pjField', '1', 'title', 'S', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('266', '647', 'pjField', '1', 'title', 'M', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('267', '648', 'pjField', '1', 'title', 'T', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('268', '649', 'pjField', '1', 'title', 'W', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('269', '650', 'pjField', '1', 'title', 'T', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('270', '651', 'pjField', '1', 'title', 'F', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('271', '652', 'pjField', '1', 'title', 'S', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('272', '653', 'pjField', '1', 'title', 'Jan', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('273', '654', 'pjField', '1', 'title', 'Feb', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('274', '655', 'pjField', '1', 'title', 'Mar', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('275', '656', 'pjField', '1', 'title', 'Apr', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('276', '657', 'pjField', '1', 'title', 'May', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('277', '658', 'pjField', '1', 'title', 'Jun', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('278', '659', 'pjField', '1', 'title', 'Jul', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('279', '660', 'pjField', '1', 'title', 'Aug', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('280', '661', 'pjField', '1', 'title', 'Sep', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('281', '662', 'pjField', '1', 'title', 'Oct', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('282', '663', 'pjField', '1', 'title', 'Nov', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('283', '664', 'pjField', '1', 'title', 'Dec', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('284', '665', 'pjField', '1', 'title', 'You are not loged in.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('285', '666', 'pjField', '1', 'title', 'Access denied. You have not requisite rights to.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('286', '667', 'pjField', '1', 'title', 'Empty resultset.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('287', '668', 'pjField', '1', 'title', 'The operation is not allowed in demo mode.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('288', '669', 'pjField', '1', 'title', 'Your hosting account does not allow uploading such a large image.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('289', '670', 'pjField', '1', 'title', 'No permisions to edit the property', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('290', '671', 'pjField', '1', 'title', 'No permisions to edit the reservation', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('291', '672', 'pjField', '1', 'title', 'No reservation found', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('292', '673', 'pjField', '1', 'title', 'No property for the reservation found', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('293', '674', 'pjField', '1', 'title', 'Your registration was successfull.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('294', '675', 'pjField', '1', 'title', 'Your registration was successfull. Your account needs to be approved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('295', '676', 'pjField', '1', 'title', 'E-Mail address already exist', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('296', '677', 'pjField', '1', 'title', 'Wrong username or password', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('297', '678', 'pjField', '1', 'title', 'Access denied', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('298', '679', 'pjField', '1', 'title', 'Account is disabled', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('419', '907', 'pjField', '1', 'title', 'Arrays titles', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('421', '908', 'pjField', '1', 'title', 'Languages Arrays Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('423', '909', 'pjField', '1', 'title', 'Languages Array Body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('425', '910', 'pjField', '1', 'title', 'Back', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('427', '982', 'pjField', '1', 'title', 'Order', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('429', '983', 'pjField', '1', 'title', 'Is default', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('431', '984', 'pjField', '1', 'title', 'Flag', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('433', '985', 'pjField', '1', 'title', 'Title', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('435', '986', 'pjField', '1', 'title', 'Delete', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('437', '990', 'pjField', '1', 'title', 'Continue', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('439', '992', 'pjField', '1', 'title', 'Email address is already in use', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('441', '993', 'pjField', '1', 'title', 'Revert status', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('443', '994', 'pjField', '1', 'title', 'Export', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('667', '995', 'pjField', '1', 'title', 'Send email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('670', '996', 'pjField', '1', 'title', 'SMTP Host', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('673', '997', 'pjField', '1', 'title', 'SMTP Port', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('676', '998', 'pjField', '1', 'title', 'SMTP Username', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('679', '999', 'pjField', '1', 'title', 'SMTP Password', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1034', '1053', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1035', '1054', 'pjField', '1', 'title', 'Employees', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1036', '1055', 'pjField', '1', 'title', 'Add service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1037', '1056', 'pjField', '1', 'title', 'All', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1038', '1057', 'pjField', '1', 'title', 'Service name', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1039', '1058', 'pjField', '1', 'title', 'Price', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1040', '1059', 'pjField', '1', 'title', 'Before (minutes)', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1041', '1060', 'pjField', '1', 'title', 'After (minutes)', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1043', '1061', 'pjField', '1', 'title', 'Total (minutes)', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1044', '1062', 'pjField', '1', 'title', 'Length (minutes)', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1045', '1063', 'pjField', '1', 'title', 'Service description', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1046', '1064', 'pjField', '1', 'title', 'Status', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1047', '1065', 'pjField', '1', 'title', 'Employees', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1048', '1066', 'pjField', '1', 'title', 'Update service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1049', '1067', 'pjField', '1', 'title', 'Active', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1051', '1068', 'pjField', '1', 'title', 'Inactive', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1063', '1069', 'pjField', '1', 'title', 'Delete selected', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1064', '1070', 'pjField', '1', 'title', 'Are you sure you want to delete selected records?', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1066', '1071', 'pjField', '1', 'title', 'Service your are looking for is missing.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1067', '1072', 'pjField', '1', 'title', 'Service updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1068', '1073', 'pjField', '1', 'title', 'Service added!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1069', '1074', 'pjField', '1', 'title', 'Service failed to add.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1070', '1075', 'pjField', '1', 'title', 'Service not found.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1071', '1076', 'pjField', '1', 'title', 'All the changes made to this service have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1072', '1077', 'pjField', '1', 'title', 'All the changes made to this service have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1073', '1078', 'pjField', '1', 'title', 'We are sorry, but the service has not been added.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1078', '1079', 'pjField', '1', 'title', 'Add a service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1079', '1080', 'pjField', '1', 'title', 'Fill in the form below to add a new service. You can add title, description, price, length and employees who do this service.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1080', '1081', 'pjField', '1', 'title', 'Use the form below to modify the service. You can change title, description, price, length and employees who do this service.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1081', '1082', 'pjField', '1', 'title', 'Update a service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1084', '1083', 'pjField', '1', 'title', 'Add employee', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1085', '1084', 'pjField', '1', 'title', 'Employee name', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1086', '1085', 'pjField', '1', 'title', 'Email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1087', '1086', 'pjField', '1', 'title', 'Phone', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1088', '1087', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1089', '1088', 'pjField', '1', 'title', 'Status', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1093', '1089', 'pjField', '1', 'title', 'Update employee', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1095', '1090', 'pjField', '1', 'title', 'Add an employee', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1096', '1091', 'pjField', '1', 'title', 'Update an employee', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1097', '1092', 'pjField', '1', 'title', 'Use the form below to update employee''s details. You can select the service that this employee does. You can also configure it so an email and/or sms notification is sent to the employee when a booking is made. Each employee can access the Appointment Scheduler and manage his/her bookings only.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1098', '1093', 'pjField', '1', 'title', 'Fill in the form below to add a new employee. You can select the service that this employee does. You can also configure it so an email and/or sms notification is sent to the employee when a booking is made. Each employee can access the Appointment Scheduler and manage his/her bookings only.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1101', '1094', 'pjField', '1', 'title', 'Notes', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1102', '1095', 'pjField', '1', 'title', 'Send email when new booking is made', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1104', '1096', 'pjField', '1', 'title', 'Password', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1109', '1098', 'pjField', '1', 'title', 'All the changes made to this employee have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1110', '1099', 'pjField', '1', 'title', 'Employee updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1112', '1100', 'pjField', '1', 'title', 'Employee added!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1113', '1101', 'pjField', '1', 'title', 'All the changes made to this employee have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1114', '1102', 'pjField', '1', 'title', 'Employee your are looking for is missing.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1115', '1103', 'pjField', '1', 'title', 'Employee not found.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1116', '1104', 'pjField', '1', 'title', 'Employee failed to add.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1117', '1105', 'pjField', '1', 'title', 'We are sorry, but the employee has not been added.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1118', '1106', 'pjField', '1', 'title', 'Last login', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1119', '1107', 'pjField', '1', 'title', 'Working Time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1121', '1108', 'pjField', '1', 'title', 'Working Time updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1122', '1109', 'pjField', '1', 'title', 'All the changes made to working time have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1123', '1110', 'pjField', '1', 'title', 'Custom Working Time saved!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1124', '1111', 'pjField', '1', 'title', 'All the changes made to custom working time have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1125', '1112', 'pjField', '1', 'title', 'Custom Working Time updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1126', '1113', 'pjField', '1', 'title', 'All the changes made to custom working time have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1127', '1114', 'pjField', '1', 'title', 'Working Time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1128', '1115', 'pjField', '1', 'title', 'Different working time can be set for each day of the week. You can also set days off and a lunch break. Under Edit Employee page you can set up custom working time for each of your employees.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1129', '1116', 'pjField', '1', 'title', 'Update custom', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1130', '1117', 'pjField', '1', 'title', 'Default', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1131', '1118', 'pjField', '1', 'title', 'Custom', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1132', '1119', 'pjField', '1', 'title', 'Day of week', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1133', '1120', 'pjField', '1', 'title', 'Start Time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1134', '1121', 'pjField', '1', 'title', 'End Time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1135', '1122', 'pjField', '1', 'title', 'Is Day off', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1136', '1123', 'pjField', '1', 'title', 'Date', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1141', '1124', 'pjField', '1', 'title', 'General', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1144', '1125', 'pjField', '1', 'title', 'Default Working Time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1145', '1126', 'pjField', '1', 'title', 'Custom Working Time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1146', '1127', 'pjField', '1', 'title', 'Lunch from', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1147', '1128', 'pjField', '1', 'title', 'Lunch to', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1148', '1129', 'pjField', '1', 'title', 'Install', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1149', '1130', 'pjField', '1', 'title', 'Preview', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1150', '1131', 'pjField', '1', 'title', 'Bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1151', '1132', 'pjField', '1', 'title', 'General', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1152', '1133', 'pjField', '1', 'title', 'Payments', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1153', '1134', 'pjField', '1', 'title', 'Booking form', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1154', '1135', 'pjField', '1', 'title', 'Confirmation', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1155', '1136', 'pjField', '1', 'title', 'Terms', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1156', '1137', 'pjField', '1', 'title', 'Address 1', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1157', '1138', 'pjField', '1', 'title', 'Captcha', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1158', '1139', 'pjField', '1', 'title', 'City', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1159', '1140', 'pjField', '1', 'title', 'Email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1160', '1141', 'pjField', '1', 'title', 'Name', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1161', '1142', 'pjField', '1', 'title', 'Notes', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1162', '1143', 'pjField', '1', 'title', 'Phone', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1164', '1144', 'pjField', '1', 'title', 'State', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1165', '1145', 'pjField', '1', 'title', 'Terms', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1166', '1146', 'pjField', '1', 'title', 'Zip', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1167', '1147', 'pjField', '1', 'title', 'Country', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1168', '1148', 'pjField', '1', 'title', 'Paypal address', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1169', '1149', 'pjField', '1', 'title', 'Accept Bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1170', '1150', 'pjField', '1', 'title', 'Allow payments with Authorize.net', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1171', '1151', 'pjField', '1', 'title', 'Provide Bank account details for wire transfers', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1172', '1152', 'pjField', '1', 'title', 'Collect Credit Card details for offline processing', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1173', '1153', 'pjField', '1', 'title', 'Allow payments with PayPal', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1174', '1154', 'pjField', '1', 'title', 'Authorize.net transaction key', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1175', '1155', 'pjField', '1', 'title', 'Authorize.net merchant ID', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1176', '1156', 'pjField', '1', 'title', 'Bank account', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1177', '1157', 'pjField', '1', 'title', 'Set deposit amount to be collected for each appointment', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1178', '1158', 'pjField', '1', 'title', 'Check if you want to disable payments and only collect reservation details', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1182', '1162', 'pjField', '1', 'title', 'Default status for booked dates if not paid', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1183', '1163', 'pjField', '1', 'title', 'Default status for booked dates if paid', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1184', '1164', 'pjField', '1', 'title', 'Tax amount to be collected for each appointment', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1185', '1165', 'pjField', '1', 'title', 'URL for the web page where your clients will be redirected after PayPal or Authorize.net payment', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1186', '1166', 'pjField', '1', 'title', 'Authorize.net time zone', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1187', '1167', 'pjField', '1', 'title', 'New reservation received', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1188', '1168', 'pjField', '1', 'title', 'Reservation cancelled', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1189', '1169', 'pjField', '1', 'title', 'Password reminder', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1190', '1170', 'pjField', '1', 'title', 'Address 2', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1191', '1171', 'pjField', '1', 'title', 'Date/Time format', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1192', '1172', 'pjField', '1', 'title', 'Authorize.net hash value', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1193', '1173', 'pjField', '1', 'title', 'Subject', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1195', '1174', 'pjField', '1', 'title', 'Email body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1196', '1175', 'pjField', '1', 'title', 'Client - booking confirmation email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1197', '1176', 'pjField', '1', 'title', 'Client - payment confirmation email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1198', '1177', 'pjField', '1', 'title', 'Admin - booking confirmation email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1199', '1178', 'pjField', '1', 'title', 'Admin - payment confirmation email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1200', '1179', 'pjField', '1', 'title', 'Booking terms URL', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1201', '1180', 'pjField', '1', 'title', 'Booking terms content', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1202', '1181', 'pjField', '1', 'title', 'Add booking', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1203', '1182', 'pjField', '1', 'title', 'Confirmed', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1205', '1183', 'pjField', '1', 'title', 'Pending', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1206', '1184', 'pjField', '1', 'title', 'Cancelled', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1207', '1185', 'pjField', '1', 'title', 'Unique ID', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1208', '1186', 'pjField', '1', 'title', 'Status', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1209', '1187', 'pjField', '1', 'title', 'Update booking', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1337', '1315', 'pjField', '1', 'title', 'CC Exp.date', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1338', '1316', 'pjField', '1', 'title', 'CC Code', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1339', '1317', 'pjField', '1', 'title', 'CC Number', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1340', '1318', 'pjField', '1', 'title', 'CC Type', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1341', '1319', 'pjField', '1', 'title', 'Maestro', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1342', '1320', 'pjField', '1', 'title', 'AmericanExpress', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1343', '1321', 'pjField', '1', 'title', 'MasterCard', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1344', '1322', 'pjField', '1', 'title', 'Visa', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1346', '1324', 'pjField', '1', 'title', 'Phone', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1347', '1325', 'pjField', '1', 'title', 'Email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1348', '1326', 'pjField', '1', 'title', 'Invoice details', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1349', '1327', 'pjField', '1', 'title', 'Create Invoice', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1350', '1328', 'pjField', '1', 'title', 'Customer details', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1351', '1329', 'pjField', '1', 'title', 'Notes', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1352', '1330', 'pjField', '1', 'title', 'Address Line 2', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1353', '1331', 'pjField', '1', 'title', 'Address Line 1', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1354', '1332', 'pjField', '1', 'title', 'Name', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1355', '1333', 'pjField', '1', 'title', 'Zip', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1356', '1334', 'pjField', '1', 'title', 'City', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1357', '1335', 'pjField', '1', 'title', 'State', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1358', '1336', 'pjField', '1', 'title', 'Country', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1359', '1337', 'pjField', '1', 'title', 'Client', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1360', '1338', 'pjField', '1', 'title', 'Booking', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1361', '1339', 'pjField', '1', 'title', 'Booking details', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1362', '1340', 'pjField', '1', 'title', '-- Choose --', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1363', '1341', 'pjField', '1', 'title', 'Payment method', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1364', '1342', 'pjField', '1', 'title', 'Price', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1366', '1344', 'pjField', '1', 'title', 'Tax', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1367', '1345', 'pjField', '1', 'title', 'Deposit', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1368', '1346', 'pjField', '1', 'title', 'Total', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1369', '1347', 'pjField', '1', 'title', 'Created', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1370', '1348', 'pjField', '1', 'title', 'Authorize.net', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1371', '1349', 'pjField', '1', 'title', 'Bank account', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1372', '1350', 'pjField', '1', 'title', 'Credit card', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1373', '1351', 'pjField', '1', 'title', 'Paypal', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1374', '1352', 'pjField', '1', 'title', 'Booking form', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1375', '1353', 'pjField', '1', 'title', 'Confirmation', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1376', '1354', 'pjField', '1', 'title', 'Terms', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1378', '1355', 'pjField', '1', 'title', 'Choose the fields that should be available on the booking form.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1379', '1356', 'pjField', '1', 'title', 'Email notifications will be sent to people who make a booking after the booking form is completed or/and payment is made. If you leave subject field blank no email will be sent. You can use tokens in the email messages to personalize them.<br /><br />\r\n\r\n<table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n  <tbody><tr>\r\n    <td width="50%" valign="top"><p>{Name} - customer name;<br />\r\n      {Phone} - customer phone number; <br />\r\n      {Email} - customer e-mail address; <br />\r\n      {Notes} - additional notes; <br />\r\n      {Address1} - address 1; <br />\r\n      {Address2} - address 2; <br />\r\n      {City} - city; <br />\r\n      {State} - state; <br />\r\n      {Zip} - zip code; <br />\r\n      {Country} - country; <br />\r\n    </p></td>\r\n    <td width="50%" valign="top">\r\n	{BookingID} - Booking ID; <br />\r\n    {Services} - Selected services<br />\r\n    {CCType} - CC type; <br />\r\n      {CCNum} - CC number; <br />\r\n      {CCExpMonth} - CC exp.month; <br />\r\n      {CCExpYear} - CC exp.year; <br />\r\n      {CCSec} - CC sec. code; <br />\r\n      {PaymentMethod} - selected payment method; <br />\r\n      {Price} - price for selected services; <br />\r\n      {Deposit} - Deposit amount; <br />\r\n      {Tax} - Tax amount; <br />\r\n      {Total} - Total amount; <br />\r\n      {CancelURL} - Link for booking cancellation; </td>\r\n  </tr>\r\n</tbody></table>', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1380', '1357', 'pjField', '1', 'title', 'Enter booking terms and conditions. You can also include a link to external web page where your terms and conditions page is.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1381', '1358', 'pjField', '1', 'title', 'Set different payment options for your Appointment Scheduler software. Enable or disable the available payment processing companies.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1383', '1359', 'pjField', '1', 'title', 'Booking payment options', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1384', '1360', 'pjField', '1', 'title', 'Here you can set some general options about the booking process.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1385', '1361', 'pjField', '1', 'title', 'Booking options', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1386', '1362', 'pjField', '1', 'title', 'Set-up general settings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1387', '1363', 'pjField', '1', 'title', 'General options', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1388', '1364', 'pjField', '1', 'title', 'Hide prices', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1389', '1365', 'pjField', '1', 'title', 'Step (in minutes)', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1390', '1366', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1391', '1367', 'pjField', '1', 'title', 'Picture', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1398', '1368', 'pjField', '1', 'title', 'Delete picture', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1399', '1369', 'pjField', '1', 'title', 'Delete confirmation', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1400', '1370', 'pjField', '1', 'title', 'Are you sure you want to delete this picture?', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1409', '1371', 'pjField', '1', 'title', 'Install instructions', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1410', '1372', 'pjField', '1', 'title', 'In order to install this script into your web page, please follow below steps.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1411', '1373', 'pjField', '1', 'title', 'Step 1 (Required)', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1412', '1374', 'pjField', '1', 'title', 'Step 2 (Optional) - for SEO purposes and better ranking you need to put next meta tag into the HEAD part of your page', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1413', '1375', 'pjField', '1', 'title', 'Step 3 (Optional) - for SEO purposes and better ranking you need to create a .htaccess file (or update existing one) with data below. Put the file in the same folder as your webpage.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1421', '1376', 'pjField', '1', 'title', 'Use SEO URLs', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1429', '1377', 'pjField', '1', 'title', 'Time format', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1431', '1378', 'pjField', '1', 'title', 'Show week numbers', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1437', '1379', 'pjField', '1', 'title', 'Captcha', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1438', '1380', 'pjField', '1', 'title', 'Select Country', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1439', '1381', 'pjField', '1', 'title', 'I agree with terms and conditions', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1440', '1382', 'pjField', '1', 'title', 'Please go back to your basket.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1441', '1383', 'pjField', '1', 'title', 'Select Payment method', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1442', '1384', 'pjField', '1', 'title', 'Select CC Type', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1449', '1385', 'pjField', '1', 'title', 'Employee', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1450', '1386', 'pjField', '1', 'title', 'Service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1451', '1387', 'pjField', '1', 'title', 'From', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1452', '1388', 'pjField', '1', 'title', 'To', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1453', '1389', 'pjField', '1', 'title', 'Query', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1454', '1390', 'pjField', '1', 'title', 'Date', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1455', '1391', 'pjField', '1', 'title', 'Export selected', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1457', '1392', 'pjField', '1', 'title', 'Comma', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1458', '1393', 'pjField', '1', 'title', 'Semicolon', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1459', '1394', 'pjField', '1', 'title', 'Tab', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1460', '1395', 'pjField', '1', 'title', 'CSV', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1461', '1396', 'pjField', '1', 'title', 'XML', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1462', '1397', 'pjField', '1', 'title', 'iCal', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1463', '1398', 'pjField', '1', 'title', 'Delimiter', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1464', '1399', 'pjField', '1', 'title', 'Format', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1465', '1400', 'pjField', '1', 'title', 'Not Available', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1466', '1401', 'pjField', '1', 'title', 'Export Bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1467', '1402', 'pjField', '1', 'title', 'Date/Time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1469', '1403', 'pjField', '1', 'title', 'Service/Employee', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1470', '1404', 'pjField', '1', 'title', 'Reminder', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1471', '1405', 'pjField', '1', 'title', 'Check this if you want to send reminders to your clients.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1472', '1406', 'pjField', '1', 'title', 'Set number of hours before the booking start time when an email reminder will be sent', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1473', '1407', 'pjField', '1', 'title', 'Email Reminder subject', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1474', '1408', 'pjField', '1', 'title', 'Set number of hours before the booking start time when an SMS reminder will be sent', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1475', '1409', 'pjField', '1', 'title', 'SMS country code', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1477', '1410', 'pjField', '1', 'title', 'SMS message', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1478', '1411', 'pjField', '1', 'title', 'Email Reminder body', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1479', '1412', 'pjField', '1', 'title', 'Get key', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1480', '1413', 'pjField', '1', 'title', 'You can send email and sms reminders to your clients X hours before their booking. You can use these tokens to customize the messages that are sent.<br /><br />\r\n<table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n  <tbody><tr>\r\n    <td width="50%" valign="top"><p>{Name} - customer name;<br />\r\n      {Phone} - customer phone number; <br />\r\n      {Email} - customer e-mail address;</p></td>\r\n    <td width="50%" valign="top">\r\n	{BookingID} - Booking ID; <br />\r\n    {Services} - Selected services<br />\r\n    {Price} - price for selected services; <br />\r\n      {Deposit} - Deposit amount; <br />\r\n      {Tax} - Tax amount; <br />\r\n      {Total} - Total amount; <br />\r\n      {CancelURL} - Link for booking cancellation; </td>\r\n  </tr>\r\n</tbody></table><br />\r\nYou should also set up a CRON job for cron.php file which should execute every hour. You need to use our SMS gateway to support sms reminders.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1481', '1414', 'pjField', '1', 'title', 'Reminder options', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1482', '1415', 'pjField', '1', 'title', 'Booking updated', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1483', '1416', 'pjField', '1', 'title', 'All changes made to the booking has been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1484', '1417', 'pjField', '1', 'title', 'Booking not found', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1485', '1418', 'pjField', '1', 'title', 'Sorry, but the booking you''re looking for is missing.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1486', '1419', 'pjField', '1', 'title', 'Booking added', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1487', '1420', 'pjField', '1', 'title', 'The booking has been successfully added.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1488', '1421', 'pjField', '1', 'title', 'Booking not added', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1489', '1422', 'pjField', '1', 'title', 'Sorry, but the booking has not been added.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1490', '1423', 'pjField', '1', 'title', 'Fill in the form below to add a new booking. Under Clients tab you can enter information about the client. ', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1491', '1424', 'pjField', '1', 'title', 'Add a booking', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1492', '1425', 'pjField', '1', 'title', 'Client details', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1493', '1426', 'pjField', '1', 'title', 'Use the form below to enter details about your client.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1494', '1427', 'pjField', '1', 'title', 'Use form below to update client related data.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1495', '1428', 'pjField', '1', 'title', 'Client details', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1496', '1429', 'pjField', '1', 'title', 'Use form below to update booking details.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1497', '1430', 'pjField', '1', 'title', 'Booking update', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1504', '1431', 'pjField', '1', 'title', 'All the changes made to reminder have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1505', '1432', 'pjField', '1', 'title', 'Reminder updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1506', '1433', 'pjField', '1', 'title', 'All the changes made to terms have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1507', '1434', 'pjField', '1', 'title', 'Terms updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1508', '1435', 'pjField', '1', 'title', 'Confirmation updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1509', '1436', 'pjField', '1', 'title', 'All the changes made to confirmation have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1512', '1437', 'pjField', '1', 'title', 'All the changes made to booking form have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1513', '1438', 'pjField', '1', 'title', 'Booking form updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1514', '1439', 'pjField', '1', 'title', 'Payment options updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1515', '1440', 'pjField', '1', 'title', 'All the changes made to payment options have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1516', '1441', 'pjField', '1', 'title', 'All the changes made to booking options have been saved.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1517', '1442', 'pjField', '1', 'title', 'Booking options updated!', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1531', '1454', 'pjField', '1', 'title', 'Recalculate the price', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1532', '1455', 'pjField', '1', 'title', '+ Add service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1534', '1456', 'pjField', '1', 'title', 'Add service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1535', '1457', 'pjField', '1', 'title', 'Delete confirmation', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1537', '1458', 'pjField', '1', 'title', 'Are you sure you want to delete selected service from the current booking?', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1538', '1459', 'pjField', '1', 'title', 'There are not any selected services yet.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1539', '1460', 'pjField', '1', 'title', 'TOTAL', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1540', '1461', 'pjField', '1', 'title', 'Selected Services', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1541', '1462', 'pjField', '1', 'title', 'Select a Date', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1542', '1463', 'pjField', '1', 'title', 'Make an Appointment', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1543', '1464', 'pjField', '1', 'title', 'Start time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1550', '1465', 'pjField', '1', 'title', 'End time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1551', '1466', 'pjField', '1', 'title', 'Select service on', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1552', '1467', 'pjField', '1', 'title', 'Availability', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1553', '1468', 'pjField', '1', 'title', 'Booking Form', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1554', '1469', 'pjField', '1', 'title', 'System message', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1555', '1470', 'pjField', '1', 'title', 'Checkout form not available', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1556', '1471', 'pjField', '1', 'title', 'Return back', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1557', '1472', 'pjField', '1', 'title', 'Booking Preview', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1558', '1473', 'pjField', '1', 'title', 'Confirm booking', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1559', '1474', 'pjField', '1', 'title', 'Preview not available', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1561', '1475', 'pjField', '1', 'title', 'Invalid data', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1562', '1476', 'pjField', '1', 'title', 'Sorry, submitted data not validate.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1563', '1477', 'pjField', '1', 'title', 'Profile', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1564', '1478', 'pjField', '1', 'title', 'Use form below to update your profile settings.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1569', '1479', 'pjField', '1', 'title', 'IP address', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1570', '1480', 'pjField', '1', 'title', 'Booking Service details', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1571', '1481', 'pjField', '1', 'title', 'Send email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1572', '1482', 'pjField', '1', 'title', 'Send SMS', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1573', '1483', 'pjField', '1', 'title', 'Subject', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1574', '1484', 'pjField', '1', 'title', 'Message', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1575', '1485', 'pjField', '1', 'title', 'Reports', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1576', '1486', 'pjField', '1', 'title', 'Employees', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1577', '1487', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1578', '1488', 'pjField', '1', 'title', 'Bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1579', '1489', 'pjField', '1', 'title', 'All Bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1580', '1490', 'pjField', '1', 'title', 'Confirmed Bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1581', '1491', 'pjField', '1', 'title', 'Pending Bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1582', '1492', 'pjField', '1', 'title', 'Cancelled Bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1583', '1493', 'pjField', '1', 'title', 'Total amount', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1584', '1494', 'pjField', '1', 'title', 'Confirmed Bookings Amount', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1585', '1495', 'pjField', '1', 'title', 'Pending Bookings Amount', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1586', '1496', 'pjField', '1', 'title', 'Cancelled Bookings Amount', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1596', '1497', 'pjField', '1', 'title', 'Columns', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1597', '1498', 'pjField', '1', 'title', 'Print', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1598', '1499', 'pjField', '1', 'title', 'Save as PDF', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1600', '1500', 'pjField', '1', 'title', 'Menu', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1601', '1501', 'pjField', '1', 'title', 'View bookings', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1602', '1502', 'pjField', '1', 'title', 'Working time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1603', '1503', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1604', '1504', 'pjField', '1', 'title', 'Below you can see the available services that your clients can book. Under Add service tab you can add a new service. Or use the edit icon for each service to modify it.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1609', '1505', 'pjField', '1', 'title', 'Specify the time needed to do this service.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1610', '1506', 'pjField', '1', 'title', 'In case you need some time before the start time for the service you can add it here. For example if your service is 60 minutes long and you input 30 minutes here, then when someone books a service at 10am you will not be available for other bookings between 9:30am and 10am', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1611', '1507', 'pjField', '1', 'title', 'In case you need some time after the end time for the service you can add it here. For example if your service is 60 minutes long and you input 30 minutes here, then when someone books a service at 10am till 11am you will not be available for other bookings between 11am and 11:30am', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1612', '1508', 'pjField', '1', 'title', 'Employees tooltip', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1623', '1509', 'pjField', '1', 'title', 'Send SMS when new booking is made', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1629', '1510', 'pjField', '1', 'title', 'Send to client', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1630', '1511', 'pjField', '1', 'title', 'Send to employee', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1633', '1512', 'pjField', '1', 'title', 'Click on available time to make an appointment', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1634', '1514', 'pjField', '1', 'title', 'Employees', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1635', '1515', 'pjField', '1', 'title', 'Below you can see a list of employees who do the different service you offer. You can have one or multiple employees.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1652', '1530', 'pjField', '1', 'title', 'SEO', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1653', '1531', 'pjField', '1', 'title', 'Language options', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1654', '1532', 'pjField', '1', 'title', 'Language', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1655', '1533', 'pjField', '1', 'title', 'Hide language selector', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1656', '1534', 'pjField', '1', 'title', 'To better optimize your shopping cart please follow the steps below', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1657', '1535', 'pjField', '1', 'title', 'SEO Optimization', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1658', '1536', 'pjField', '1', 'title', 'Step 1. Webpage where your front end appointment scheduler is', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1659', '1537', 'pjField', '1', 'title', 'Step 2. Put the meta tag below between &lt;head&gt; and &lt;/head&gt; tags on your web page', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1660', '1538', 'pjField', '1', 'title', 'Step 3. Create .htaccess file (or update existing one) in the folder where your web page is and put the data below in it', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1661', '1539', 'pjField', '1', 'title', 'Generate', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1662', '1540', 'pjField', '1', 'title', 'Index', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1663', '1541', 'pjField', '1', 'title', 'Employees report', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1664', '1542', 'pjField', '1', 'title', 'Using the form below you can generate a report for specific service and date range. You can also generate the results based on number of services each employee did or the total amount paid for these services.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1665', '1543', 'pjField', '1', 'title', 'Services report', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1666', '1544', 'pjField', '1', 'title', 'Using the form below you can generate a report for specific employee and date range. You can also generate the results based on number of services each employee did or the total amount paid for these services.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1667', '1545', 'pjField', '1', 'title', 'Amount', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1668', '1546', 'pjField', '1', 'title', 'Count', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1669', '1547', 'pjField', '1', 'title', 'Below you can see working schedule for all employees. Using the date selector below to refresh the schedule. Click on Print button to print work timesheet.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1670', '1548', 'pjField', '1', 'title', 'Dashboard', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1671', '1549', 'pjField', '1', 'title', 'Apply', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1672', '1550', 'pjField', '1', 'title', 'Dashboard filter', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1673', '1756', 'pjField', '1', 'title', 'Custom working time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1674', '1758', 'pjField', '1', 'title', 'Using the form below you can set a custom working time for any date. Just select a date and set working time for it. Or you can just mark the date as a day off and bookings on that date will not be accepted. ', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1675', '1759', 'pjField', '1', 'title', 'Here you can set working time for this employee only. Different working time can be set for each day of the week. You can also set days off and a lunch break. ', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1676', '1760', 'pjField', '1', 'title', 'Working Time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1677', '1761', 'pjField', '1', 'title', 'Custom working time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1678', '1762', 'pjField', '1', 'title', 'Using the form below you can set a custom working time for any date for this employee only. Just select a date and set working time for it. Or you can just mark the date as a day off and bookings on that date for this employee will not be accepted.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1679', '1768', 'pjField', '1', 'title', 'Today', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1680', '1769', 'pjField', '1', 'title', 'Tomorrow', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1681', '1770', 'pjField', '1', 'title', 'Dashboard Notice', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1682', '1771', 'pjField', '1', 'title', 'Selected date is set to "day off". Use the date picker above to choose another date. Please, note that you can change working time under Options page.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1683', '1772', 'pjField', '1', 'title', 'Go to PayPal Secure page', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1684', '1773', 'pjField', '1', 'title', 'Go to Authorize.NET Secure page', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1685', '1774', 'pjField', '1', 'title', 'Please wait while redirect to secure payment processor webpage complete...', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1686', '1775', 'pjField', '1', 'title', 'Your request has been sent successfully. Thank you.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1687', '1776', 'pjField', '1', 'title', 'Booking not found', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1688', '1777', 'pjField', '1', 'title', 'The invoice for this booking is already paid.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1689', '1778', 'pjField', '1', 'title', 'Booking not available', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1690', '1779', 'pjField', '1', 'title', 'Start time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1691', '1780', 'pjField', '1', 'title', 'End time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1692', '1781', 'pjField', '1', 'title', 'Services not found', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1693', '1782', 'pjField', '1', 'title', 'You need to have at least a service.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1694', '1783', 'pjField', '1', 'title', 'Employees not found', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1695', '1784', 'pjField', '1', 'title', 'You need to create employee and assign service first.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1696', '1785', 'pjField', '1', 'title', 'mins', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1697', '1786', 'pjField', '1', 'title', 'st', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1698', '1787', 'pjField', '1', 'title', 'nd', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1699', '1788', 'pjField', '1', 'title', 'rd', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1700', '1789', 'pjField', '1', 'title', 'th', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1701', '1790', 'pjField', '1', 'title', 'on', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1702', '1791', 'pjField', '1', 'title', 'back to services', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1703', '1792', 'pjField', '1', 'title', 'Checkout', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1704', '1793', 'pjField', '1', 'title', 'Service added to your cart.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1705', '1794', 'pjField', '1', 'title', 'Some of the items in your basket is not available.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1706', '1795', 'pjField', '1', 'title', 'from', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1707', '1796', 'pjField', '1', 'title', 'till', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1708', '1797', 'pjField', '1', 'title', 'Missing, empty or invalid parameters.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1709', '1798', 'pjField', '1', 'title', 'Booking with such an ID did not exists.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1710', '1799', 'pjField', '1', 'title', 'Security hash did not match.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1711', '1800', 'pjField', '1', 'title', 'Booking is already cancelled.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1712', '1801', 'pjField', '1', 'title', 'Booking has been cancelled successfully.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1713', '1802', 'pjField', '1', 'title', 'Customer Details', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1714', '1803', 'pjField', '1', 'title', 'Cancel booking', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1715', '1804', 'pjField', '1', 'title', 'Booking Services', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1716', '1805', 'pjField', '1', 'title', 'Booking Cancellation', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1717', '1806', 'pjField', '1', 'title', 'Employee - booking confirmation email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1718', '1807', 'pjField', '1', 'title', 'Employee - payment confirmation email', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1719', '1808', 'pjField', '1', 'title', 'Update the default working time for all the employees', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1720', '1809', 'pjField', '1', 'title', 'Layout', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1721', '1810', 'pjField', '1', 'title', 'Select date', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1722', '1811', 'pjField', '1', 'title', 'Service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1723', '1812', 'pjField', '1', 'title', 'Select time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1724', '1813', 'pjField', '1', 'title', 'Employee', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1725', '1814', 'pjField', '1', 'title', 'Book', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1726', '1815', 'pjField', '1', 'title', 'Select date and service', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1727', '1816', 'pjField', '1', 'title', 'Choose date', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1728', '1817', 'pjField', '1', 'title', 'Date', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1729', '1818', 'pjField', '1', 'title', 'Price', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1730', '1819', 'pjField', '1', 'title', 'This invoice have been cancelled.', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1731', '1820', 'pjField', '1', 'title', 'not available on selected date and time', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1732', '1', 'pjCalendar', '1', 'confirm_subject_client', 'Booking confirmation', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1733', '1', 'pjCalendar', '1', 'confirm_tokens_client', 'Thank you for your booking. \r\n\r\nID: {BookingID}\r\n\r\nServices\r\n{Services}\r\n\r\nPersonal details\r\nName: {Name}\r\nPhone: {Phone}\r\nEmail: {Email}\r\n\r\nThis is the price for your booking\r\nTax: {Price}\r\nTax: {Tax}\r\nTotal: {Total}\r\nDeposit required to confirm your booking: {Deposit}\r\n\r\nAdditional notes:\r\n{Notes}\r\n\r\nThank you,\r\nThe Management', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1734', '1', 'pjCalendar', '1', 'payment_subject_client', 'Payment received', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1735', '1', 'pjCalendar', '1', 'payment_tokens_client', 'We''ve received the payment for your booking and it is now confirmed.\r\n\r\nID: {BookingID}\r\n\r\nThank you,\r\nThe Management', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1736', '1', 'pjCalendar', '1', 'confirm_subject_admin', 'New booking received', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1737', '1', 'pjCalendar', '1', 'confirm_tokens_admin', 'New booking has been made. \r\n\r\nID: {BookingID}\r\n\r\nServices\r\n{Services}\r\n\r\nPersonal details\r\nName: {Name}\r\nPhone: {Phone}\r\nEmail: {Email}\r\n\r\nPrice\r\nTax: {Price}\r\nTax: {Tax}\r\nTotal: {Total}\r\nDeposit required to confirm the booking: {Deposit}\r\n\r\nAdditional notes:\r\n{Notes}', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1738', '1', 'pjCalendar', '1', 'payment_subject_admin', 'New payment received', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1739', '1', 'pjCalendar', '1', 'payment_tokens_admin', 'Booking deposit has been paid.\r\n\r\nID: {BookingID}', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1740', '1', 'pjCalendar', '1', 'confirm_subject_employee', 'New appointment received', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1741', '1', 'pjCalendar', '1', 'confirm_tokens_employee', 'New appointment has been made.\r\n\r\nID: {BookingID}\r\n\r\nServices\r\n{Services}\r\n\r\nPersonal details\r\nName: {Name}\r\nPhone: {Phone}\r\nEmail: {Email}\r\n\r\nAdditional notes:\r\n{Notes}', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1742', '1', 'pjCalendar', '1', 'payment_subject_employee', 'New payment received', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1743', '1', 'pjCalendar', '1', 'payment_tokens_employee', 'Booking deposit has been paid.\r\n\r\nID: {BookingID}', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1744', '1821', 'pjField', '1', 'title', 'Languages', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1747', '1822', 'pjField', '1', 'title', 'Titles', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1750', '1823', 'pjField', '1', 'title', 'Languages', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1753', '1824', 'pjField', '1', 'title', 'Add as many languages as you need to your script. For each of the languages added you need to translate all the text titles.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1756', '1825', 'pjField', '1', 'title', 'Titles', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1759', '1826', 'pjField', '1', 'title', 'Edit all page titles. Use the search box to quickly locate a title.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1762', '1827', 'pjField', '1', 'title', 'Title', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1765', '1828', 'pjField', '1', 'title', 'Flag', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1768', '1829', 'pjField', '1', 'title', 'Is default', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1771', '1830', 'pjField', '1', 'title', 'Order', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1774', '1831', 'pjField', '1', 'title', 'Add Language', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1777', '1832', 'pjField', '1', 'title', 'Field', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1780', '1833', 'pjField', '1', 'title', 'Value', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1783', '1834', 'pjField', '1', 'title', 'Back-end title', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1786', '1835', 'pjField', '1', 'title', 'Front-end title', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1789', '1836', 'pjField', '1', 'title', 'Special title', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1792', '1837', 'pjField', '1', 'title', 'Titles Updated', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1795', '1838', 'pjField', '1', 'title', 'All the changes made to titles have been saved.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1798', '1839', 'pjField', '1', 'title', 'Per page', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1801', '1840', 'pjField', '1', 'title', 'Import error', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1804', '1841', 'pjField', '1', 'title', 'Import failed due missing parameters.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1807', '1842', 'pjField', '1', 'title', 'Import complete', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1810', '1843', 'pjField', '1', 'title', 'The import was performed successfully.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1813', '1844', 'pjField', '1', 'title', 'Import error', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1816', '1845', 'pjField', '1', 'title', 'Import failed due SQL error.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1819', '1846', 'pjField', '1', 'title', 'Import / Export', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1822', '1847', 'pjField', '1', 'title', 'Import', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1825', '1848', 'pjField', '1', 'title', 'Export', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1828', '1849', 'pjField', '1', 'title', 'Language', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1831', '1850', 'pjField', '1', 'title', 'Browse your computer', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1834', '1851', 'pjField', '1', 'title', 'Import / Export', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1837', '1852', 'pjField', '1', 'title', 'Use form below to Import or Export choosen language.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1840', '1853', 'pjField', '1', 'title', 'Backup', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1843', '1854', 'pjField', '1', 'title', 'Backup complete!', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1846', '1855', 'pjField', '1', 'title', 'Backup failed!', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1849', '1856', 'pjField', '1', 'title', 'Backup failed!', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1852', '1857', 'pjField', '1', 'title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at ligula non arcu dignissim pretium. Praesent in magna nulla, in porta leo.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1855', '1858', 'pjField', '1', 'title', 'All backup files have been saved.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1858', '1859', 'pjField', '1', 'title', 'No option was selected.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1861', '1860', 'pjField', '1', 'title', 'Backup not performed.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1864', '1861', 'pjField', '1', 'title', 'Backup', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1867', '1862', 'pjField', '1', 'title', 'Backup database', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1870', '1863', 'pjField', '1', 'title', 'Backup files', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1873', '1864', 'pjField', '1', 'title', 'Backup', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1876', '1865', 'pjField', '1', 'title', 'Log', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1879', '1866', 'pjField', '1', 'title', 'Config log', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1882', '1867', 'pjField', '1', 'title', 'Empty log', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1885', '1868', 'pjField', '1', 'title', 'Config log updated.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1888', '1869', 'pjField', '1', 'title', 'The config log have been updated.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1891', '1870', 'pjField', '1', 'title', 'List', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1894', '1871', 'pjField', '1', 'title', '+ Add', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1897', '1872', 'pjField', '1', 'title', 'Country name', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1900', '1873', 'pjField', '1', 'title', 'Alpha 2', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1903', '1874', 'pjField', '1', 'title', 'Alpha 3', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1906', '1875', 'pjField', '1', 'title', 'Status', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1909', '1876', 'pjField', '1', 'title', 'Add +', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1912', '1877', 'pjField', '1', 'title', 'Active', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1915', '1878', 'pjField', '1', 'title', 'Inactive', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1918', '1879', 'pjField', '1', 'title', 'Save', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1921', '1880', 'pjField', '1', 'title', 'Cancel', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1924', '1881', 'pjField', '1', 'title', 'Countries', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1927', '1882', 'pjField', '1', 'title', 'Country updated', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1930', '1883', 'pjField', '1', 'title', 'Country added', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1933', '1884', 'pjField', '1', 'title', 'Country failed to add', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1936', '1885', 'pjField', '1', 'title', 'Country not found', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1939', '1886', 'pjField', '1', 'title', 'Add country', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1942', '1887', 'pjField', '1', 'title', 'Update country', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1945', '1888', 'pjField', '1', 'title', 'Manage countries', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1948', '1889', 'pjField', '1', 'title', 'Country has been updated successfully.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1951', '1890', 'pjField', '1', 'title', 'Country has been added successfully.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1954', '1891', 'pjField', '1', 'title', 'Country has not been added successfully.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1957', '1892', 'pjField', '1', 'title', 'Country you are looking for has not been found.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1960', '1893', 'pjField', '1', 'title', 'Use form below to add a country.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1963', '1894', 'pjField', '1', 'title', 'Use form below to update a country.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1966', '1895', 'pjField', '1', 'title', 'Use grid below to organize your country list.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1969', '1896', 'pjField', '1', 'title', 'Are you sure you want to delete selected country?', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1972', '1897', 'pjField', '1', 'title', 'Delete selected', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1975', '1898', 'pjField', '1', 'title', 'All', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1978', '1899', 'pjField', '1', 'title', 'Search', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1981', '1', 'pjCountry', '1', 'name', 'Afghanistan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1982', '2', 'pjCountry', '1', 'name', 'Ã…land Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1983', '3', 'pjCountry', '1', 'name', 'Albania', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1984', '4', 'pjCountry', '1', 'name', 'Algeria', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1985', '5', 'pjCountry', '1', 'name', 'American Samoa', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1986', '6', 'pjCountry', '1', 'name', 'Andorra', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1987', '7', 'pjCountry', '1', 'name', 'Angola', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1988', '8', 'pjCountry', '1', 'name', 'Anguilla', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1989', '9', 'pjCountry', '1', 'name', 'Antarctica', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1990', '10', 'pjCountry', '1', 'name', 'Antigua and Barbuda', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1991', '11', 'pjCountry', '1', 'name', 'Argentina', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1992', '12', 'pjCountry', '1', 'name', 'Armenia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1993', '13', 'pjCountry', '1', 'name', 'Aruba', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1994', '14', 'pjCountry', '1', 'name', 'Australia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1995', '15', 'pjCountry', '1', 'name', 'Austria', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1996', '16', 'pjCountry', '1', 'name', 'Azerbaijan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1997', '17', 'pjCountry', '1', 'name', 'Bahamas', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1998', '18', 'pjCountry', '1', 'name', 'Bahrain', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('1999', '19', 'pjCountry', '1', 'name', 'Bangladesh', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2000', '20', 'pjCountry', '1', 'name', 'Barbados', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2001', '21', 'pjCountry', '1', 'name', 'Belarus', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2002', '22', 'pjCountry', '1', 'name', 'Belgium', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2003', '23', 'pjCountry', '1', 'name', 'Belize', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2004', '24', 'pjCountry', '1', 'name', 'Benin', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2005', '25', 'pjCountry', '1', 'name', 'Bermuda', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2006', '26', 'pjCountry', '1', 'name', 'Bhutan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2007', '27', 'pjCountry', '1', 'name', 'Bolivia, Plurinational State of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2008', '28', 'pjCountry', '1', 'name', 'Bonaire, Sint Eustatius and Saba', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2009', '29', 'pjCountry', '1', 'name', 'Bosnia and Herzegovina', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2010', '30', 'pjCountry', '1', 'name', 'Botswana', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2011', '31', 'pjCountry', '1', 'name', 'Bouvet Island', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2012', '32', 'pjCountry', '1', 'name', 'Brazil', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2013', '33', 'pjCountry', '1', 'name', 'British Indian Ocean Territory', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2014', '34', 'pjCountry', '1', 'name', 'Brunei Darussalam', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2015', '35', 'pjCountry', '1', 'name', 'Bulgaria', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2016', '36', 'pjCountry', '1', 'name', 'Burkina Faso', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2017', '37', 'pjCountry', '1', 'name', 'Burundi', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2018', '38', 'pjCountry', '1', 'name', 'Cambodia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2019', '39', 'pjCountry', '1', 'name', 'Cameroon', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2020', '40', 'pjCountry', '1', 'name', 'Canada', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2021', '41', 'pjCountry', '1', 'name', 'Cape Verde', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2022', '42', 'pjCountry', '1', 'name', 'Cayman Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2023', '43', 'pjCountry', '1', 'name', 'Central African Republic', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2024', '44', 'pjCountry', '1', 'name', 'Chad', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2025', '45', 'pjCountry', '1', 'name', 'Chile', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2026', '46', 'pjCountry', '1', 'name', 'China', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2027', '47', 'pjCountry', '1', 'name', 'Christmas Island', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2028', '48', 'pjCountry', '1', 'name', 'Cocos array(Keeling) Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2029', '49', 'pjCountry', '1', 'name', 'Colombia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2030', '50', 'pjCountry', '1', 'name', 'Comoros', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2031', '51', 'pjCountry', '1', 'name', 'Congo', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2032', '52', 'pjCountry', '1', 'name', 'Congo, the Democratic Republic of the', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2033', '53', 'pjCountry', '1', 'name', 'Cook Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2034', '54', 'pjCountry', '1', 'name', 'Costa Rica', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2035', '55', 'pjCountry', '1', 'name', 'CÃ´te d''Ivoire', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2036', '56', 'pjCountry', '1', 'name', 'Croatia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2037', '57', 'pjCountry', '1', 'name', 'Cuba', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2038', '58', 'pjCountry', '1', 'name', 'CuraÃ§ao', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2039', '59', 'pjCountry', '1', 'name', 'Cyprus', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2040', '60', 'pjCountry', '1', 'name', 'Czech Republic', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2041', '61', 'pjCountry', '1', 'name', 'Denmark', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2042', '62', 'pjCountry', '1', 'name', 'Djibouti', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2043', '63', 'pjCountry', '1', 'name', 'Dominica', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2044', '64', 'pjCountry', '1', 'name', 'Dominican Republic', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2045', '65', 'pjCountry', '1', 'name', 'Ecuador', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2046', '66', 'pjCountry', '1', 'name', 'Egypt', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2047', '67', 'pjCountry', '1', 'name', 'El Salvador', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2048', '68', 'pjCountry', '1', 'name', 'Equatorial Guinea', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2049', '69', 'pjCountry', '1', 'name', 'Eritrea', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2050', '70', 'pjCountry', '1', 'name', 'Estonia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2051', '71', 'pjCountry', '1', 'name', 'Ethiopia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2052', '72', 'pjCountry', '1', 'name', 'Falkland Islands array(Malvinas)', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2053', '73', 'pjCountry', '1', 'name', 'Faroe Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2054', '74', 'pjCountry', '1', 'name', 'Fiji', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2055', '75', 'pjCountry', '1', 'name', 'Finland', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2056', '76', 'pjCountry', '1', 'name', 'France', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2057', '77', 'pjCountry', '1', 'name', 'French Guiana', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2058', '78', 'pjCountry', '1', 'name', 'French Polynesia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2059', '79', 'pjCountry', '1', 'name', 'French Southern Territories', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2060', '80', 'pjCountry', '1', 'name', 'Gabon', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2061', '81', 'pjCountry', '1', 'name', 'Gambia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2062', '82', 'pjCountry', '1', 'name', 'Georgia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2063', '83', 'pjCountry', '1', 'name', 'Germany', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2064', '84', 'pjCountry', '1', 'name', 'Ghana', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2065', '85', 'pjCountry', '1', 'name', 'Gibraltar', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2066', '86', 'pjCountry', '1', 'name', 'Greece', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2067', '87', 'pjCountry', '1', 'name', 'Greenland', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2068', '88', 'pjCountry', '1', 'name', 'Grenada', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2069', '89', 'pjCountry', '1', 'name', 'Guadeloupe', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2070', '90', 'pjCountry', '1', 'name', 'Guam', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2071', '91', 'pjCountry', '1', 'name', 'Guatemala', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2072', '92', 'pjCountry', '1', 'name', 'Guernsey', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2073', '93', 'pjCountry', '1', 'name', 'Guinea', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2074', '94', 'pjCountry', '1', 'name', 'Guinea-Bissau', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2075', '95', 'pjCountry', '1', 'name', 'Guyana', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2076', '96', 'pjCountry', '1', 'name', 'Haiti', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2077', '97', 'pjCountry', '1', 'name', 'Heard Island and McDonald Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2078', '98', 'pjCountry', '1', 'name', 'Holy See array(Vatican City State)', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2079', '99', 'pjCountry', '1', 'name', 'Honduras', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2080', '100', 'pjCountry', '1', 'name', 'Hong Kong', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2081', '101', 'pjCountry', '1', 'name', 'Hungary', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2082', '102', 'pjCountry', '1', 'name', 'Iceland', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2083', '103', 'pjCountry', '1', 'name', 'India', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2084', '104', 'pjCountry', '1', 'name', 'Indonesia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2085', '105', 'pjCountry', '1', 'name', 'Iran, Islamic Republic of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2086', '106', 'pjCountry', '1', 'name', 'Iraq', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2087', '107', 'pjCountry', '1', 'name', 'Ireland', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2088', '108', 'pjCountry', '1', 'name', 'Isle of Man', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2089', '109', 'pjCountry', '1', 'name', 'Israel', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2090', '110', 'pjCountry', '1', 'name', 'Italy', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2091', '111', 'pjCountry', '1', 'name', 'Jamaica', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2092', '112', 'pjCountry', '1', 'name', 'Japan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2093', '113', 'pjCountry', '1', 'name', 'Jersey', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2094', '114', 'pjCountry', '1', 'name', 'Jordan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2095', '115', 'pjCountry', '1', 'name', 'Kazakhstan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2096', '116', 'pjCountry', '1', 'name', 'Kenya', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2097', '117', 'pjCountry', '1', 'name', 'Kiribati', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2098', '118', 'pjCountry', '1', 'name', 'Korea, Democratic People''s Republic of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2099', '119', 'pjCountry', '1', 'name', 'Korea, Republic of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2100', '120', 'pjCountry', '1', 'name', 'Kuwait', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2101', '121', 'pjCountry', '1', 'name', 'Kyrgyzstan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2102', '122', 'pjCountry', '1', 'name', 'Lao People''s Democratic Republic', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2103', '123', 'pjCountry', '1', 'name', 'Latvia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2104', '124', 'pjCountry', '1', 'name', 'Lebanon', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2105', '125', 'pjCountry', '1', 'name', 'Lesotho', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2106', '126', 'pjCountry', '1', 'name', 'Liberia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2107', '127', 'pjCountry', '1', 'name', 'Libya', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2108', '128', 'pjCountry', '1', 'name', 'Liechtenstein', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2109', '129', 'pjCountry', '1', 'name', 'Lithuania', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2110', '130', 'pjCountry', '1', 'name', 'Luxembourg', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2111', '131', 'pjCountry', '1', 'name', 'Macao', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2112', '132', 'pjCountry', '1', 'name', 'Macedonia, The Former Yugoslav Republic of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2113', '133', 'pjCountry', '1', 'name', 'Madagascar', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2114', '134', 'pjCountry', '1', 'name', 'Malawi', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2115', '135', 'pjCountry', '1', 'name', 'Malaysia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2116', '136', 'pjCountry', '1', 'name', 'Maldives', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2117', '137', 'pjCountry', '1', 'name', 'Mali', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2118', '138', 'pjCountry', '1', 'name', 'Malta', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2119', '139', 'pjCountry', '1', 'name', 'Marshall Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2120', '140', 'pjCountry', '1', 'name', 'Martinique', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2121', '141', 'pjCountry', '1', 'name', 'Mauritania', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2122', '142', 'pjCountry', '1', 'name', 'Mauritius', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2123', '143', 'pjCountry', '1', 'name', 'Mayotte', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2124', '144', 'pjCountry', '1', 'name', 'Mexico', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2125', '145', 'pjCountry', '1', 'name', 'Micronesia, Federated States of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2126', '146', 'pjCountry', '1', 'name', 'Moldova, Republic of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2127', '147', 'pjCountry', '1', 'name', 'Monaco', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2128', '148', 'pjCountry', '1', 'name', 'Mongolia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2129', '149', 'pjCountry', '1', 'name', 'Montenegro', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2130', '150', 'pjCountry', '1', 'name', 'Montserrat', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2131', '151', 'pjCountry', '1', 'name', 'Morocco', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2132', '152', 'pjCountry', '1', 'name', 'Mozambique', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2133', '153', 'pjCountry', '1', 'name', 'Myanmar', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2134', '154', 'pjCountry', '1', 'name', 'Namibia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2135', '155', 'pjCountry', '1', 'name', 'Nauru', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2136', '156', 'pjCountry', '1', 'name', 'Nepal', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2137', '157', 'pjCountry', '1', 'name', 'Netherlands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2138', '158', 'pjCountry', '1', 'name', 'New Caledonia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2139', '159', 'pjCountry', '1', 'name', 'New Zealand', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2140', '160', 'pjCountry', '1', 'name', 'Nicaragua', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2141', '161', 'pjCountry', '1', 'name', 'Niger', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2142', '162', 'pjCountry', '1', 'name', 'Nigeria', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2143', '163', 'pjCountry', '1', 'name', 'Niue', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2144', '164', 'pjCountry', '1', 'name', 'Norfolk Island', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2145', '165', 'pjCountry', '1', 'name', 'Northern Mariana Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2146', '166', 'pjCountry', '1', 'name', 'Norway', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2147', '167', 'pjCountry', '1', 'name', 'Oman', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2148', '168', 'pjCountry', '1', 'name', 'Pakistan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2149', '169', 'pjCountry', '1', 'name', 'Palau', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2150', '170', 'pjCountry', '1', 'name', 'Palestine, State of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2151', '171', 'pjCountry', '1', 'name', 'Panama', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2152', '172', 'pjCountry', '1', 'name', 'Papua New Guinea', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2153', '173', 'pjCountry', '1', 'name', 'Paraguay', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2154', '174', 'pjCountry', '1', 'name', 'Peru', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2155', '175', 'pjCountry', '1', 'name', 'Philippines', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2156', '176', 'pjCountry', '1', 'name', 'Pitcairn', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2157', '177', 'pjCountry', '1', 'name', 'Poland', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2158', '178', 'pjCountry', '1', 'name', 'Portugal', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2159', '179', 'pjCountry', '1', 'name', 'Puerto Rico', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2160', '180', 'pjCountry', '1', 'name', 'Qatar', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2161', '181', 'pjCountry', '1', 'name', 'RÃ©union', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2162', '182', 'pjCountry', '1', 'name', 'Romania', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2163', '183', 'pjCountry', '1', 'name', 'Russian Federation', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2164', '184', 'pjCountry', '1', 'name', 'Rwanda', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2165', '185', 'pjCountry', '1', 'name', 'Saint BarthÃ©lemy', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2166', '186', 'pjCountry', '1', 'name', 'Saint Helena, Ascension and Tristan da Cunha', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2167', '187', 'pjCountry', '1', 'name', 'Saint Kitts and Nevis', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2168', '188', 'pjCountry', '1', 'name', 'Saint Lucia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2169', '189', 'pjCountry', '1', 'name', 'Saint Martin array(French part)', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2170', '190', 'pjCountry', '1', 'name', 'Saint Pierre and Miquelon', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2171', '191', 'pjCountry', '1', 'name', 'Saint Vincent and the Grenadines', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2172', '192', 'pjCountry', '1', 'name', 'Samoa', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2173', '193', 'pjCountry', '1', 'name', 'San Marino', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2174', '194', 'pjCountry', '1', 'name', 'Sao Tome and Principe', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2175', '195', 'pjCountry', '1', 'name', 'Saudi Arabia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2176', '196', 'pjCountry', '1', 'name', 'Senegal', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2177', '197', 'pjCountry', '1', 'name', 'Serbia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2178', '198', 'pjCountry', '1', 'name', 'Seychelles', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2179', '199', 'pjCountry', '1', 'name', 'Sierra Leone', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2180', '200', 'pjCountry', '1', 'name', 'Singapore', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2181', '201', 'pjCountry', '1', 'name', 'Sint Maarten array(Dutch part)', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2182', '202', 'pjCountry', '1', 'name', 'Slovakia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2183', '203', 'pjCountry', '1', 'name', 'Slovenia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2184', '204', 'pjCountry', '1', 'name', 'Solomon Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2185', '205', 'pjCountry', '1', 'name', 'Somalia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2186', '206', 'pjCountry', '1', 'name', 'South Africa', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2187', '207', 'pjCountry', '1', 'name', 'South Georgia and the South Sandwich Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2188', '208', 'pjCountry', '1', 'name', 'South Sudan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2189', '209', 'pjCountry', '1', 'name', 'Spain', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2190', '210', 'pjCountry', '1', 'name', 'Sri Lanka', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2191', '211', 'pjCountry', '1', 'name', 'Sudan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2192', '212', 'pjCountry', '1', 'name', 'Suriname', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2193', '213', 'pjCountry', '1', 'name', 'Svalbard and Jan Mayen', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2194', '214', 'pjCountry', '1', 'name', 'Swaziland', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2195', '215', 'pjCountry', '1', 'name', 'Sweden', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2196', '216', 'pjCountry', '1', 'name', 'Switzerland', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2197', '217', 'pjCountry', '1', 'name', 'Syrian Arab Republic', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2198', '218', 'pjCountry', '1', 'name', 'Taiwan, Province of China', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2199', '219', 'pjCountry', '1', 'name', 'Tajikistan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2200', '220', 'pjCountry', '1', 'name', 'Tanzania, United Republic of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2201', '221', 'pjCountry', '1', 'name', 'Thailand', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2202', '222', 'pjCountry', '1', 'name', 'Timor-Leste', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2203', '223', 'pjCountry', '1', 'name', 'Togo', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2204', '224', 'pjCountry', '1', 'name', 'Tokelau', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2205', '225', 'pjCountry', '1', 'name', 'Tonga', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2206', '226', 'pjCountry', '1', 'name', 'Trinidad and Tobago', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2207', '227', 'pjCountry', '1', 'name', 'Tunisia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2208', '228', 'pjCountry', '1', 'name', 'Turkey', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2209', '229', 'pjCountry', '1', 'name', 'Turkmenistan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2210', '230', 'pjCountry', '1', 'name', 'Turks and Caicos Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2211', '231', 'pjCountry', '1', 'name', 'Tuvalu', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2212', '232', 'pjCountry', '1', 'name', 'Uganda', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2213', '233', 'pjCountry', '1', 'name', 'Ukraine', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2214', '234', 'pjCountry', '1', 'name', 'United Arab Emirates', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2215', '235', 'pjCountry', '1', 'name', 'United Kingdom', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2216', '236', 'pjCountry', '1', 'name', 'United States', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2217', '237', 'pjCountry', '1', 'name', 'United States Minor Outlying Islands', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2218', '238', 'pjCountry', '1', 'name', 'Uruguay', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2219', '239', 'pjCountry', '1', 'name', 'Uzbekistan', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2220', '240', 'pjCountry', '1', 'name', 'Vanuatu', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2221', '241', 'pjCountry', '1', 'name', 'Venezuela, Bolivarian Republic of', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2222', '242', 'pjCountry', '1', 'name', 'Viet Nam', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2223', '243', 'pjCountry', '1', 'name', 'Virgin Islands, British', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2224', '244', 'pjCountry', '1', 'name', 'Virgin Islands, U.S.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2225', '245', 'pjCountry', '1', 'name', 'Wallis and Futuna', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2226', '246', 'pjCountry', '1', 'name', 'Western Sahara', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2227', '247', 'pjCountry', '1', 'name', 'Yemen', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2228', '248', 'pjCountry', '1', 'name', 'Zambia', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2229', '249', 'pjCountry', '1', 'name', 'Zimbabwe', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2728', '1900', 'pjField', '1', 'title', 'Invoices', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2731', '1901', 'pjField', '1', 'title', 'Invoice Config', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2734', '1902', 'pjField', '1', 'title', 'Company logo', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2737', '1903', 'pjField', '1', 'title', 'Company name', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2740', '1904', 'pjField', '1', 'title', 'Name', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2743', '1905', 'pjField', '1', 'title', 'Street address', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2746', '1906', 'pjField', '1', 'title', 'City', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2749', '1907', 'pjField', '1', 'title', 'State', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2752', '1908', 'pjField', '1', 'title', 'Zip', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2755', '1909', 'pjField', '1', 'title', 'Phone', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2758', '1910', 'pjField', '1', 'title', 'Fax', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2761', '1911', 'pjField', '1', 'title', 'Email', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2764', '1912', 'pjField', '1', 'title', 'Website', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2767', '1913', 'pjField', '1', 'title', 'Invoice', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2770', '1914', 'pjField', '1', 'title', 'In order to configure the invoices module you need to fill in your company details. To view all invoices <a href="index.php?controller=pjInvoice&action=pjActionInvoices">click here</a>', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2773', '1915', 'pjField', '1', 'title', 'Invoice config updated!', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2776', '1916', 'pjField', '1', 'title', 'Invoice config data has been properly updated.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2779', '1917', 'pjField', '1', 'title', 'Upload failed', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2782', '1918', 'pjField', '1', 'title', 'Invoice Template', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2785', '1919', 'pjField', '1', 'title', 'Delete confirmation', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2788', '1920', 'pjField', '1', 'title', 'Are you sure you want to delete selected logo?', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2791', '1921', 'pjField', '1', 'title', 'Billing information', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2794', '1922', 'pjField', '1', 'title', 'Shipping information', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2797', '1923', 'pjField', '1', 'title', 'Company information', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2800', '1924', 'pjField', '1', 'title', 'Payment information', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2803', '1925', 'pjField', '1', 'title', 'Address', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2806', '1926', 'pjField', '1', 'title', 'Billing address', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2809', '1927', 'pjField', '1', 'title', 'General information', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2812', '1928', 'pjField', '1', 'title', 'Invoice no.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2815', '1929', 'pjField', '1', 'title', 'Order no.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2818', '1930', 'pjField', '1', 'title', 'Issue date', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2821', '1931', 'pjField', '1', 'title', 'Due date', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2824', '1932', 'pjField', '1', 'title', 'Shipping date', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2827', '1933', 'pjField', '1', 'title', 'Shipping terms', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2830', '1934', 'pjField', '1', 'title', 'Subtotal', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2833', '1935', 'pjField', '1', 'title', 'Discount', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2836', '1936', 'pjField', '1', 'title', 'Tax', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2839', '1937', 'pjField', '1', 'title', 'Shipping', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2842', '1938', 'pjField', '1', 'title', 'Total', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2845', '1939', 'pjField', '1', 'title', 'Paid deposit', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2848', '1940', 'pjField', '1', 'title', 'Amount due', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2851', '1941', 'pjField', '1', 'title', 'Currency', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2854', '1942', 'pjField', '1', 'title', 'Notes', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2857', '1943', 'pjField', '1', 'title', 'Shipping address', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2860', '1944', 'pjField', '1', 'title', 'Created', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2863', '1945', 'pjField', '1', 'title', 'Modified', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2866', '1946', 'pjField', '1', 'title', 'Item', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2869', '1947', 'pjField', '1', 'title', 'Qty', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2872', '1948', 'pjField', '1', 'title', 'Unit price', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2875', '1949', 'pjField', '1', 'title', 'Amount', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2878', '1950', 'pjField', '1', 'title', 'Add item', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2881', '1951', 'pjField', '1', 'title', 'Update item', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2884', '1952', 'pjField', '1', 'title', 'Description', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2887', '1953', 'pjField', '1', 'title', 'Accept payments', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2890', '1954', 'pjField', '1', 'title', 'PRINT', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2893', '1955', 'pjField', '1', 'title', 'EMAIL', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2896', '1956', 'pjField', '1', 'title', 'VIEW', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2899', '1957', 'pjField', '1', 'title', 'Send invoice', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2902', '1958', 'pjField', '1', 'title', 'Invoice', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2905', '1959', 'pjField', '1', 'title', 'Items information', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2908', '1960', 'pjField', '1', 'title', 'Accept payments with PayPal', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2911', '1961', 'pjField', '1', 'title', 'Accept payments with Authorize.NET', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2914', '1962', 'pjField', '1', 'title', 'Accept payments with Credit Card', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2917', '1963', 'pjField', '1', 'title', 'Accept payments with Bank Account', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2920', '1964', 'pjField', '1', 'title', 'Include Shipping information', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2923', '1965', 'pjField', '1', 'title', 'Include Shipping address', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2926', '1966', 'pjField', '1', 'title', 'Include Company', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2929', '1967', 'pjField', '1', 'title', 'Include Name', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2932', '1968', 'pjField', '1', 'title', 'Include Address', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2935', '1969', 'pjField', '1', 'title', 'Include City', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2938', '1970', 'pjField', '1', 'title', 'Include State', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2941', '1971', 'pjField', '1', 'title', 'Include Zip', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2944', '1972', 'pjField', '1', 'title', 'Include Phone', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2947', '1973', 'pjField', '1', 'title', 'Include Fax', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2950', '1974', 'pjField', '1', 'title', 'Include Email', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2953', '1975', 'pjField', '1', 'title', 'Include Website', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2956', '1976', 'pjField', '1', 'title', 'Include Street address', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2959', '1977', 'pjField', '1', 'title', 'Invoice updated!', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2962', '1978', 'pjField', '1', 'title', 'Invoice has been updated.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2965', '1979', 'pjField', '1', 'title', 'Invoice Not Found', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2968', '1980', 'pjField', '1', 'title', 'Invoice with such ID was not found.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2971', '1981', 'pjField', '1', 'title', 'Update failed', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2974', '1982', 'pjField', '1', 'title', 'Some of the data is not valid.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2977', '1983', 'pjField', '1', 'title', 'Status', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2980', '1984', 'pjField', '1', 'title', 'Pay with Paypal', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2983', '1985', 'pjField', '1', 'title', 'Pay with Authorize.Net', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2986', '1986', 'pjField', '1', 'title', 'Pay with Credit Card', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2989', '1987', 'pjField', '1', 'title', 'Pay with Bank Account', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2992', '1988', 'pjField', '1', 'title', 'Pay Now', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2995', '1989', 'pjField', '1', 'title', 'Invoice module', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('2998', '1990', 'pjField', '1', 'title', 'Invoice module', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3001', '1991', 'pjField', '1', 'title', 'Paypal address', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3004', '1992', 'pjField', '1', 'title', 'Authorize.Net Timezone', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3007', '1993', 'pjField', '1', 'title', 'Authorize.Net Merchant ID', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3010', '1994', 'pjField', '1', 'title', 'Authorize.Net Transaction Key', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3013', '1995', 'pjField', '1', 'title', 'Bank Account', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3016', '1996', 'pjField', '1', 'title', 'You will be redirected to Paypal in 3 seconds. If not please click on the button.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3019', '1997', 'pjField', '1', 'title', 'You will be redirected to Authorize.net in 3 seconds. If not please click on the button.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3022', '1998', 'pjField', '1', 'title', 'Proceed with payment', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3025', '1999', 'pjField', '1', 'title', 'Proceed with payment', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3028', '2000', 'pjField', '1', 'title', 'Delete selected', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3031', '2001', 'pjField', '1', 'title', 'Are you sure you want to delete selected invoices?', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3034', '2002', 'pjField', '1', 'title', 'Is shipped', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3037', '2003', 'pjField', '1', 'title', 'Include Shipping date', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3040', '2004', 'pjField', '1', 'title', 'Include Shipping terms', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3043', '2005', 'pjField', '1', 'title', 'Include Is Shipped', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3046', '2006', 'pjField', '1', 'title', 'NOT PAID', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3049', '2007', 'pjField', '1', 'title', 'PAID', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3052', '2008', 'pjField', '1', 'title', 'CANCELLED', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3055', '2009', 'pjField', '1', 'title', 'No.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3058', '2010', 'pjField', '1', 'title', 'ADD +', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3061', '2011', 'pjField', '1', 'title', 'SAVE', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3064', '2012', 'pjField', '1', 'title', 'Booking URL - Token: {ORDER_ID}', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3067', '2013', 'pjField', '1', 'title', 'Include Shipping', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3070', '2014', 'pjField', '1', 'title', 'Invoice added!', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3073', '2015', 'pjField', '1', 'title', 'Invoice has been added.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3076', '2016', 'pjField', '1', 'title', 'Invoice has not been added.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3079', '2017', 'pjField', '1', 'title', 'Invoice failed to add!', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3082', '2018', 'pjField', '1', 'title', 'Notice', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3085', '2019', 'pjField', '1', 'title', 'Check the email address(es) where invoice should be sent.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3088', '2020', 'pjField', '1', 'title', 'Invoice details', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3091', '2021', 'pjField', '1', 'title', 'Fill in invoice details and use the buttons below to view, print or email the invoice.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3094', '2022', 'pjField', '1', 'title', 'Billing details', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3097', '2023', 'pjField', '1', 'title', 'Under "Billing information" you can edit your customer billing details. Under "Company information" is your company billing information which will be used for the invoice.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3100', '2024', 'pjField', '1', 'title', 'Quantity format', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3103', '2025', 'pjField', '1', 'title', 'Format as INT instead of FLOAT', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3106', '2026', 'pjField', '1', 'title', 'Authorize.Net hash value', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3109', '2027', 'pjField', '1', 'title', 'SMS', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3112', '2028', 'pjField', '1', 'title', 'SMS Config', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3115', '2029', 'pjField', '1', 'title', 'Phone number', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3118', '2030', 'pjField', '1', 'title', 'Message', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3121', '2031', 'pjField', '1', 'title', 'Status', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3124', '2032', 'pjField', '1', 'title', 'Date/Time sent', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3127', '2033', 'pjField', '1', 'title', 'API Key', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3130', '2034', 'pjField', '1', 'title', 'SMS', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3133', '2035', 'pjField', '1', 'title', 'To send SMS you need a valid API Key. Please, contact StivaSoft to purchase an API key.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3136', '2036', 'pjField', '1', 'title', 'SMS API key updated!', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3139', '2037', 'pjField', '1', 'title', 'All changes have been saved.', 'plugin');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3142', '1', 'pjService', '1', 'name', 'Service 1', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3143', '1', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3144', '1', 'pjEmployee', '1', 'name', 'Employee 1', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3145', '3', 'pjService', '1', 'name', 'service 2', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3146', '3', 'pjService', '1', 'description', '', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3147', '2', 'pjEmployee', '1', 'name', 'Employee 2', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3148', '2038', 'pjField', '1', 'title', 'Layout Backend', 'script');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3149', '4', 'pjService', '1', 'name', 'Service 5', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3150', '4', 'pjService', '1', 'description', 'Test', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3151', '5', 'pjService', '1', 'name', 'Service 2', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3152', '5', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3155', '6', 'pjService', '1', 'name', 'Service 3', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3156', '6', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3157', '7', 'pjService', '1', 'name', 'Service 4', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3158', '7', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3163', '8', 'pjService', '1', 'name', 'Service 5', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3164', '8', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3171', '3', 'pjEmployee', '1', 'name', 'Employee 3', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3172', '4', 'pjEmployee', '1', 'name', 'Employee 4', 'data');
INSERT INTO `salondoris_hey_appscheduler_multi_lang` VALUES('3173', '2039', 'pjField', '1', 'title', 'Custom Status', 'script');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_options`;

CREATE TABLE `salondoris_hey_appscheduler_options` (
  `foreign_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) NOT NULL DEFAULT '',
  `tab_id` tinyint(3) unsigned DEFAULT NULL,
  `value` text,
  `label` text,
  `type` enum('string','text','int','float','enum','bool') NOT NULL DEFAULT 'string',
  `order` int(10) unsigned DEFAULT NULL,
  `is_visible` tinyint(1) unsigned DEFAULT '1',
  `style` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`foreign_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_accept_bookings', '3', '1|0::1', '', 'bool', '1', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_allow_authorize', '7', '1|0::1', '', 'bool', '18', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_allow_bank', '7', '1|0::1', '', 'bool', '24', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_allow_creditcard', '7', '1|0::1', '', 'bool', '23', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_allow_paypal', '7', '1|0::1', '', 'bool', '16', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_authorize_hash', '7', 'abcd', '', 'string', '21', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_authorize_key', '7', '53N2U726wZwksK4a', '', 'string', '20', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_authorize_mid', '7', '745ean5JCt2Y', '', 'string', '19', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_authorize_tz', '7', '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', '22', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bank_account', '7', 'Bank of America', '', 'text', '25', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_address_1', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '6', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_address_2', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '7', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_captcha', '4', '1|3::1', 'No|Yes (Required)', 'enum', '16', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_city', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '12', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_country', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '15', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_email', '4', '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', '4', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_name', '4', '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', '3', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_notes', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '8', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_phone', '4', '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', '5', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_state', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '13', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_terms', '4', '1|3::1', 'No|Yes (Required)', 'enum', '17', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_bf_zip', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '14', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_currency', '1', 'AED|AFN|ALL|AMD|ANG|AOA|ARS|AUD|AWG|AZN|BAM|BBD|BDT|BGN|BHD|BIF|BMD|BND|BOB|BOV|BRL|BSD|BTN|BWP|BYR|BZD|CAD|CDF|CHE|CHF|CHW|CLF|CLP|CNY|COP|COU|CRC|CUC|CUP|CVE|CZK|DJF|DKK|DOP|DZD|EEK|EGP|ERN|ETB|EUR|FJD|FKP|GBP|GEL|GHS|GIP|GMD|GNF|GTQ|GYD|HKD|HNL|HRK|HTG|HUF|IDR|ILS|INR|IQD|IRR|ISK|JMD|JOD|JPY|KES|KGS|KHR|KMF|KPW|KRW|KWD|KYD|KZT|LAK|LBP|LKR|LRD|LSL|LTL|LVL|LYD|MAD|MDL|MGA|MKD|MMK|MNT|MOP|MRO|MUR|MVR|MWK|MXN|MXV|MYR|MZN|NAD|NGN|NIO|NOK|NPR|NZD|OMR|PAB|PEN|PGK|PHP|PKR|PLN|PYG|QAR|RON|RSD|RUB|RWF|SAR|SBD|SCR|SDG|SEK|SGD|SHP|SLL|SOS|SRD|STD|SYP|SZL|THB|TJS|TMT|TND|TOP|TRY|TTD|TWD|TZS|UAH|UGX|USD|USN|USS|UYU|UZS|VEF|VND|VUV|WST|XAF|XAG|XAU|XBA|XBB|XBC|XBD|XCD|XDR|XFU|XOF|XPD|XPF|XPT|XTS|XXX|YER|ZAR|ZMK|ZWL::EUR', '', 'enum', '3', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_custom_status', '1', '1|0::1', '', 'bool', '2', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_datetime_format', '1', 'd.m.Y, H:i|d.m.Y, H:i:s|m.d.Y, H:i|m.d.Y, H:i:s|Y.m.d, H:i|Y.m.d, H:i:s|j.n.Y, H:i|j.n.Y, H:i:s|n.j.Y, H:i|n.j.Y, H:i:s|Y.n.j, H:i|Y.n.j, H:i:s|d/m/Y, H:i|d/m/Y, H:i:s|m/d/Y, H:i|m/d/Y, H:i:s|Y/m/d, H:i|Y/m/d, H:i:s|j/n/Y, H:i|j/n/Y, H:i:s|n/j/Y, H:i|n/j/Y, H:i:s|Y/n/j, H:i|Y/n/j, H:i:s|d-m-Y, H:i|d-m-Y, H:i:s|m-d-Y, H:i|m-d-Y, H:i:s|Y-m-d, H:i|Y-m-d, H:i:s|j-n-Y, H:i|j-n-Y, H:i:s|n-j-Y, H:i|n-j-Y, H:i:s|Y-n-j, H:i|Y-n-j, H:i:s::j/n/Y, H:i', 'd.m.Y, H:i (25.09.2010, 09:51)|d.m.Y, H:i:s (25.09.2010, 09:51:47)|m.d.Y, H:i (09.25.2010, 09:51)|m.d.Y, H:i:s (09.25.2010, 09:51:47)|Y.m.d, H:i (2010.09.25, 09:51)|Y.m.d, H:i:s (2010.09.25, 09:51:47)|j.n.Y, H:i (25.9.2010, 09:51)|j.n.Y, H:i:s (25.9.2010, 09:51:47)|n.j.Y, H:i (9.25.2010, 09:51)|n.j.Y, H:i:s (9.25.2010, 09:51:47)|Y.n.j, H:i (2010.9.25, 09:51)|Y.n.j, H:i:s (2010.9.25, 09:51:47)|d/m/Y, H:i (25/09/2010, 09:51)|d/m/Y, H:i:s (25/09/2010, 09:51:47)|m/d/Y, H:i (09/25/2010, 09:51)|m/d/Y, H:i:s (09/25/2010, 09:51:47)|Y/m/d, H:i (2010/09/25, 09:51)|Y/m/d, H:i:s (2010/09/25, 09:51:47)|j/n/Y, H:i (25/9/2010, 09:51)|j/n/Y, H:i:s (25/9/2010, 09:51:47)|n/j/Y, H:i (9/25/2010, 09:51)|n/j/Y, H:i:s (9/25/2010, 09:51:47)|Y/n/j, H:i (2010/9/25, 09:51)|Y/n/j, H:i:s (2010/9/25, 09:51:47)|d-m-Y, H:i (25-09-2010, 09:51)|d-m-Y, H:i:s (25-09-2010, 09:51:47)|m-d-Y, H:i (09-25-2010, 09:51)|m-d-Y, H:i:s (09-25-2010, 09:51:47)|Y-m-d, H:i (2010-09-25, 09:51)|Y-m-d, H:i:s (2010-09-25, 09:51:47)|j-n-Y, H:i (25-9-2010, 09:51)|j-n-Y, H:i:s (25-9-2010, 09:51:47)|n-j-Y, H:i (9-25-2010, 09:51)|n-j-Y, H:i:s (9-25-2010, 09:51:47)|Y-n-j, H:i (2010-9-25, 09:51)|Y-n-j, H:i:s (2010-9-25, 09:51:47)', 'enum', '8', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_date_format', '1', 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::d-m-Y', 'd.m.Y (25.09.2012)|m.d.Y (09.25.2012)|Y.m.d (2012.09.25)|j.n.Y (25.9.2012)|n.j.Y (9.25.2012)|Y.n.j (2012.9.25)|d/m/Y (25/09/2012)|m/d/Y (09/25/2012)|Y/m/d (2012/09/25)|j/n/Y (25/9/2012)|n/j/Y (9/25/2012)|Y/n/j (2012/9/25)|d-m-Y (25-09-2012)|m-d-Y (09-25-2012)|Y-m-d (2012-09-25)|j-n-Y (25-9-2012)|n-j-Y (9-25-2012)|Y-n-j (2012-9-25)', 'enum', '6', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_deposit', '7', '20', '', 'float', '12', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_deposit_type', '7', 'amount|percent::percent', 'Amount|Percent', 'enum', '', '0', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_disable_payments', '7', '1|0::1', '', 'bool', '4', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_hide_prices', '3', '1|0::0', '', 'bool', '2', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_layout', '1', '1|2|3::1', 'Layout 1|Layout 2|Layout 3', 'enum', '1', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_layout_backend', '1', '1|2::2', 'Layout 1|Layout 2', 'enum', '1', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_multi_lang', '99', '1|0::0', '', 'enum', '', '0', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_paypal_address', '7', 'paypal_seller@example.com', '', 'string', '17', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_reminder_body', '8', 'Dear {Name},\r\r\n\r\r\nYour booking is coming soon!\r\r\n\r\r\nBooking ID: {BookingID}\r\r\n\r\r\nServices\r\r\n{Services}\r\r\n\r\r\nRegards,\r\r\nThe Management', '', 'text', '4', '1', 'height:350px');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_reminder_email_before', '8', '10', '', 'int', '2', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_reminder_enable', '8', '1|0::1', '', 'bool', '1', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_reminder_sms_country_code', '8', '358', 'SMS country code', 'string', '5', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_reminder_sms_hours', '8', '2', '', 'int', '5', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_reminder_sms_message', '8', '{Name}, your booking is coming.', '', 'text', '7', '1', 'height:200px');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_reminder_subject', '8', 'Booking Reminder', '', 'string', '3', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_send_email', '1', 'mail|smtp::mail', 'PHP mail()|SMTP', 'enum', '11', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_seo_url', '1', '1|0::0', '', 'bool', '20', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_smtp_host', '1', '', '', 'string', '12', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_smtp_pass', '1', '', '', 'string', '15', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_smtp_port', '1', '25', '', 'int', '13', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_smtp_user', '1', '', '', 'string', '14', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_status_if_not_paid', '3', 'confirmed|pending::confirmed', 'Confirmed|Pending', 'enum', '10', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_status_if_paid', '3', 'confirmed|pending::confirmed', 'Confirmed|Pending', 'enum', '9', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_step', '3', '5|10|15|20|25|30|35|40|45|50|55|60::15', '', 'enum', '3', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_tax', '7', '10', '', 'float', '14', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_thankyou_page', '7', 'http://varaa.com/', '', 'string', '26', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_timezone', '1', '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', '10', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_time_format', '1', 'H:i|G:i|h:i|h:i a|h:i A|g:i|g:i a|g:i A::H:i', 'H:i (09:45)|G:i (9:45)|h:i (09:45)|h:i a (09:45 am)|h:i A (09:45 AM)|g:i (9:45)|g:i a (9:45 am)|g:i A (9:45 AM)', 'enum', '7', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_week_numbers', '1', '1|0::1', '', 'bool', '19', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'o_week_start', '1', '0|1|2|3|4|5|6::1', 'Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday', 'enum', '9', '1', '');
INSERT INTO `salondoris_hey_appscheduler_options` VALUES('1', 'private_key', '99', 'R9/Oloz+U9YjLmSfenqcRiISDlI09LDR1WViqtYe/vxshtThVDXLQFxA2U5mIkYd3vy42wtW1j6C33ndue/jpSe3DeV8NPZxVIS4B87R3cCCY7L1bGrLQL5P49l4FBfJzlncUYoE9dCq7h1EPZTjV7HS9mSvfiPnvdyXt0mE2PerPdl+LNFtmeefHkHpJei6FvELm01Cep3bVP5lq/fmTimq+gmj3SB92LbPdFQpYmAFn1+dTTOqb97zOpuMeqcf9J4+/vRwemasu1lx4nmeCH+h8j/f4FBdNZZbbJ7g7dmHF949qPpqE24kCP/YU3KgxDAhiy1m79qrqpnQE3Ey1A==', '', 'string', '', '1', 'string');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_country`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alpha_2` varchar(2) DEFAULT NULL,
  `alpha_3` varchar(3) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha_2` (`alpha_2`),
  UNIQUE KEY `alpha_3` (`alpha_3`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('1', 'AF', 'AFG', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('2', 'AX', 'ALA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('3', 'AL', 'ALB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('4', 'DZ', 'DZA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('5', 'AS', 'ASM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('6', 'AD', 'AND', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('7', 'AO', 'AGO', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('8', 'AI', 'AIA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('9', 'AQ', 'ATA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('10', 'AG', 'ATG', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('11', 'AR', 'ARG', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('12', 'AM', 'ARM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('13', 'AW', 'ABW', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('14', 'AU', 'AUS', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('15', 'AT', 'AUT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('16', 'AZ', 'AZE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('17', 'BS', 'BHS', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('18', 'BH', 'BHR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('19', 'BD', 'BGD', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('20', 'BB', 'BRB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('21', 'BY', 'BLR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('22', 'BE', 'BEL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('23', 'BZ', 'BLZ', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('24', 'BJ', 'BEN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('25', 'BM', 'BMU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('26', 'BT', 'BTN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('27', 'BO', 'BOL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('28', 'BQ', 'BES', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('29', 'BA', 'BIH', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('30', 'BW', 'BWA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('31', 'BV', 'BVT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('32', 'BR', 'BRA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('33', 'IO', 'IOT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('34', 'BN', 'BRN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('35', 'BG', 'BGR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('36', 'BF', 'BFA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('37', 'BI', 'BDI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('38', 'KH', 'KHM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('39', 'CM', 'CMR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('40', 'CA', 'CAN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('41', 'CV', 'CPV', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('42', 'KY', 'CYM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('43', 'CF', 'CAF', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('44', 'TD', 'TCD', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('45', 'CL', 'CHL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('46', 'CN', 'CHN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('47', 'CX', 'CXR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('48', 'CC', 'CCK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('49', 'CO', 'COL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('50', 'KM', 'COM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('51', 'CG', 'COG', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('52', 'CD', 'COD', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('53', 'CK', 'COK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('54', 'CR', 'CRI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('55', 'CI', 'CIV', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('56', 'HR', 'HRV', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('57', 'CU', 'CUB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('58', 'CW', 'CUW', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('59', 'CY', 'CYP', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('60', 'CZ', 'CZE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('61', 'DK', 'DNK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('62', 'DJ', 'DJI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('63', 'DM', 'DMA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('64', 'DO', 'DOM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('65', 'EC', 'ECU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('66', 'EG', 'EGY', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('67', 'SV', 'SLV', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('68', 'GQ', 'GNQ', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('69', 'ER', 'ERI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('70', 'EE', 'EST', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('71', 'ET', 'ETH', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('72', 'FK', 'FLK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('73', 'FO', 'FRO', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('74', 'FJ', 'FJI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('75', 'FI', 'FIN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('76', 'FR', 'FRA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('77', 'GF', 'GUF', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('78', 'PF', 'PYF', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('79', 'TF', 'ATF', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('80', 'GA', 'GAB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('81', 'GM', 'GMB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('82', 'GE', 'GEO', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('83', 'DE', 'DEU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('84', 'GH', 'GHA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('85', 'GI', 'GIB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('86', 'GR', 'GRC', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('87', 'GL', 'GRL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('88', 'GD', 'GRD', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('89', 'GP', 'GLP', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('90', 'GU', 'GUM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('91', 'GT', 'GTM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('92', 'GG', 'GGY', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('93', 'GN', 'GIN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('94', 'GW', 'GNB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('95', 'GY', 'GUY', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('96', 'HT', 'HTI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('97', 'HM', 'HMD', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('98', 'VA', 'VAT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('99', 'HN', 'HND', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('100', 'HK', 'HKG', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('101', 'HU', 'HUN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('102', 'IS', 'ISL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('103', 'IN', 'IND', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('104', 'ID', 'IDN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('105', 'IR', 'IRN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('106', 'IQ', 'IRQ', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('107', 'IE', 'IRL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('108', 'IM', 'IMN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('109', 'IL', 'ISR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('110', 'IT', 'ITA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('111', 'JM', 'JAM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('112', 'JP', 'JPN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('113', 'JE', 'JEY', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('114', 'JO', 'JOR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('115', 'KZ', 'KAZ', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('116', 'KE', 'KEN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('117', 'KI', 'KIR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('118', 'KP', 'PRK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('119', 'KR', 'KOR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('120', 'KW', 'KWT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('121', 'KG', 'KGZ', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('122', 'LA', 'LAO', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('123', 'LV', 'LVA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('124', 'LB', 'LBN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('125', 'LS', 'LSO', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('126', 'LR', 'LBR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('127', 'LY', 'LBY', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('128', 'LI', 'LIE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('129', 'LT', 'LTU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('130', 'LU', 'LUX', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('131', 'MO', 'MAC', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('132', 'MK', 'MKD', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('133', 'MG', 'MDG', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('134', 'MW', 'MWI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('135', 'MY', 'MYS', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('136', 'MV', 'MDV', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('137', 'ML', 'MLI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('138', 'MT', 'MLT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('139', 'MH', 'MHL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('140', 'MQ', 'MTQ', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('141', 'MR', 'MRT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('142', 'MU', 'MUS', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('143', 'YT', 'MYT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('144', 'MX', 'MEX', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('145', 'FM', 'FSM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('146', 'MD', 'MDA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('147', 'MC', 'MCO', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('148', 'MN', 'MNG', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('149', 'ME', 'MNE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('150', 'MS', 'MSR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('151', 'MA', 'MAR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('152', 'MZ', 'MOZ', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('153', 'MM', 'MMR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('154', 'NA', 'NAM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('155', 'NR', 'NRU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('156', 'NP', 'NPL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('157', 'NL', 'NLD', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('158', 'NC', 'NCL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('159', 'NZ', 'NZL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('160', 'NI', 'NIC', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('161', 'NE', 'NER', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('162', 'NG', 'NGA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('163', 'NU', 'NIU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('164', 'NF', 'NFK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('165', 'MP', 'MNP', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('166', 'NO', 'NOR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('167', 'OM', 'OMN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('168', 'PK', 'PAK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('169', 'PW', 'PLW', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('170', 'PS', 'PSE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('171', 'PA', 'PAN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('172', 'PG', 'PNG', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('173', 'PY', 'PRY', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('174', 'PE', 'PER', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('175', 'PH', 'PHL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('176', 'PN', 'PCN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('177', 'PL', 'POL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('178', 'PT', 'PRT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('179', 'PR', 'PRI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('180', 'QA', 'QAT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('181', 'RE', 'REU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('182', 'RO', 'ROU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('183', 'RU', 'RUS', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('184', 'RW', 'RWA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('185', 'BL', 'BLM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('186', 'SH', 'SHN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('187', 'KN', 'KNA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('188', 'LC', 'LCA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('189', 'MF', 'MAF', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('190', 'PM', 'SPM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('191', 'VC', 'VCT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('192', 'WS', 'WSM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('193', 'SM', 'SMR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('194', 'ST', 'STP', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('195', 'SA', 'SAU', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('196', 'SN', 'SEN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('197', 'RS', 'SRB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('198', 'SC', 'SYC', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('199', 'SL', 'SLE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('200', 'SG', 'SGP', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('201', 'SX', 'SXM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('202', 'SK', 'SVK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('203', 'SI', 'SVN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('204', 'SB', 'SLB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('205', 'SO', 'SOM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('206', 'ZA', 'ZAF', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('207', 'GS', 'SGS', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('208', 'SS', 'SSD', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('209', 'ES', 'ESP', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('210', 'LK', 'LKA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('211', 'SD', 'SDN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('212', 'SR', 'SUR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('213', 'SJ', 'SJM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('214', 'SZ', 'SWZ', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('215', 'SE', 'SWE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('216', 'CH', 'CHE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('217', 'SY', 'SYR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('218', 'TW', 'TWN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('219', 'TJ', 'TJK', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('220', 'TZ', 'TZA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('221', 'TH', 'THA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('222', 'TL', 'TLS', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('223', 'TG', 'TGO', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('224', 'TK', 'TKL', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('225', 'TO', 'TON', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('226', 'TT', 'TTO', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('227', 'TN', 'TUN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('228', 'TR', 'TUR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('229', 'TM', 'TKM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('230', 'TC', 'TCA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('231', 'TV', 'TUV', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('232', 'UG', 'UGA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('233', 'UA', 'UKR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('234', 'AE', 'ARE', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('235', 'GB', 'GBR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('236', 'US', 'USA', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('237', 'UM', 'UMI', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('238', 'UY', 'URY', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('239', 'UZ', 'UZB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('240', 'VU', 'VUT', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('241', 'VE', 'VEN', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('242', 'VN', 'VNM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('243', 'VG', 'VGB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('244', 'VI', 'VIR', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('245', 'WF', 'WLF', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('246', 'EH', 'ESH', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('247', 'YE', 'YEM', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('248', 'ZM', 'ZMB', 'T');
INSERT INTO `salondoris_hey_appscheduler_plugin_country` VALUES('249', 'ZW', 'ZWE', 'T');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_invoice`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_invoice` (
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `order_id` (`order_id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_invoice_config`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_invoice_config` (
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_plugin_invoice_config` VALUES('1', '', 'New York Knicks', 'John Smith', 'Madison Square', 'New York City', 'NY', '11222', '(111) 222 3333', '(222) 333 4444', 'info@domain.com', 'http://www.google.com/', '<table style="width: 100%;" border="0">\r\n<tbody>\r\n<tr>\r\n<td style="width: 50%;"><strong>{y_company}</strong></td>\r\n<td><strong>Invoice no.</strong> {uuid}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td><strong>Date</strong> {issue_date}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<table style="width: 85%;" border="0" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;"><strong>Bill To:</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>{b_name}</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>{b_company}</strong></td>\r\n</tr>\r\n<tr>\r\n<td>{b_billing_address}</td>\r\n</tr>\r\n<tr>\r\n<td>{b_city}</td>\r\n</tr>\r\n<tr>\r\n<td>{b_state} {b_zip}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>Phone: {b_phone}, Fax: {b_fax}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<p style="text-align: center;"><span style="font-size: large;"><strong>Invoice</strong></span></p>\r\n<p>{items}</p>\r\n<p>&nbsp;</p>\r\n<table style="width: 100%;" border="0">\r\n<tbody>\r\n<tr>\r\n<td>Note:</td>\r\n<td style="text-align: right;">SubTotal:</td>\r\n<td style="text-align: right;">{subtotal}</td>\r\n</tr>\r\n<tr>\r\n<td>Thanks for your business!</td>\r\n<td style="text-align: right;">Discount:</td>\r\n<td style="text-align: right;">{discount}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;"><strong>Total:</strong></td>\r\n<td style="text-align: right;"><strong>{total}</strong></td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;">Deposit:</td>\r\n<td style="text-align: right;">{paid_deposit}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;"><strong>Amount Due:</strong></td>\r\n<td style="text-align: right;"><strong>{amount_due}</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<table style="width: 100%; border-collapse: collapse;" border="0">\r\n<tbody>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;"><strong>{y_company}</strong></td>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>{y_street_address}</td>\r\n<td>Phone: {y_phone}</td>\r\n</tr>\r\n<tr>\r\n<td>{y_city}</td>\r\n<td>Email: {y_email}</td>\r\n</tr>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">{y_state} {y_zip}</td>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">Website: {y_url}</td>\r\n</tr>\r\n</tbody>\r\n</table>', '1', '1', '0', '1', '1', 'info@domain.com', '0', '', '', '', 'bank account information goes here', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'index.php?controller=pjAdminBookings&action=pjActionUpdate&uuid={ORDER_ID}', '0');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_invoice_items`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_invoice_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) unsigned DEFAULT NULL,
  `tmp` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` tinytext,
  `qty` decimal(9,2) unsigned DEFAULT NULL,
  `unit_price` decimal(9,2) unsigned DEFAULT NULL,
  `amount` decimal(9,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_locale`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_locale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_iso` varchar(2) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT NULL,
  `is_default` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_iso` (`language_iso`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_plugin_locale` VALUES('1', 'gb', '1', '1');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_locale_languages`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_locale_languages` (
  `iso` varchar(2) NOT NULL DEFAULT '',
  `title` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('aa', 'Afar', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ab', 'Abkhazian', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ae', 'Avestan', 'ae.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('af', 'Afrikaans', 'af.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ak', 'Akan', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('am', 'Amharic', 'am.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('an', 'Aragonese', 'an.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('as', 'Assamese', 'as.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('av', 'Avaric', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ay', 'Aymara', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ba', 'Bashkir', 'ba.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('be', 'Belarusian', 'be.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('bg', 'Bulgarian', 'bg.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('bh', 'Bihari', 'bh.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('bi', 'Bislama', 'bi.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('bm', 'Bambara', 'bm.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('bn', 'Bengali', 'bn.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('bo', 'Tibetan', 'bo.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('br', 'Breton', 'br.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('bs', 'Bosnian', 'bs.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ca', 'Catalan', 'ca.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ce', 'Chechen', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ch', 'Chamorro', 'ch.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('co', 'Corsican', 'co.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('cr', 'Cree', 'cr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('cs', 'Czech', 'cz.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('cu', 'Church Slavic', 'cu.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('cv', 'Chuvash', 'cv.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('cy', 'Welsh', 'cy.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('da', 'Danish', 'dk.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('de', 'German', 'de.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('dz', 'Dzongkha', 'dz.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ee', 'Ewe', 'ee.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('el', 'Greek', 'gr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('eo', 'Esperanto', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('es', 'Spanish', 'es.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('et', 'Estonian', 'et.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('eu', 'Basque', 'eu.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ff', 'Fulah', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('fi', 'Finnish', 'fi.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('fj', 'Fijian', 'fj.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('fo', 'Faroese', 'fo.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('fr', 'French', 'fr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('fy', 'Western Frisian', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ga', 'Irish', 'ga.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('gb', 'English - UK', 'gb.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('gd', 'Scottish Gaelic', 'gd.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('gl', 'Galician', 'gl.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('gn', 'Guarani', 'gn.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('gu', 'Gujarati', 'gu.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('gv', 'Manx', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ha', 'Hausa', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('hi', 'Hindi', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ho', 'Hiri Motu', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('hr', 'Croatian', 'hr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ht', 'Haitian', 'ht.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('hu', 'Hungarian', 'hu.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('hy', 'Armenian', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('hz', 'Herero', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ia', 'Interlingua (International Auxiliary Language Association)', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('id', 'Indonesian', 'id.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ie', 'Interlingue', 'ie.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ig', 'Igbo', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ii', 'Sichuan Yi', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ik', 'Inupiaq', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('io', 'Ido', 'io.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('is', 'Icelandic', 'is.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('it', 'Italian', 'it.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('iu', 'Inuktitut', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ka', 'Georgian', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('kg', 'Kongo', 'kg.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ki', 'Kikuyu', 'ki.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('kj', 'Kwanyama', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('kl', 'Kalaallisut', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('km', 'Khmer', 'km.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('kn', 'Kannada', 'kn.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ko', 'Korean', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('kr', 'Kanuri', 'kr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('kv', 'Komi', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('kw', 'Cornish', 'kw.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ky', 'Kirghiz', 'ky.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('la', 'Latin', 'la.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('lb', 'Luxembourgish', 'lb.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('lg', 'Ganda', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('li', 'Limburgish', 'li.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ln', 'Lingala', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('lo', 'Lao', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('lt', 'Lithuanian', 'lt.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('lu', 'Luba-Katanga', 'lu.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('lv', 'Latvian', 'lv.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('mg', 'Malagasy', 'mg.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('mh', 'Marshallese', 'mh.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('mi', 'Maori', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('mk', 'Macedonian', 'mk.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('mn', 'Mongolian', 'mn.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('mr', 'Marathi', 'mr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('mt', 'Maltese', 'mt.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('my', 'Burmese', 'my.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('na', 'Nauru', 'na.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('nb', 'Norwegian Bokmal', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('nd', 'North Ndebele', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ne', 'Nepali', 'ne.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ng', 'Ndonga', 'ng.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('nl', 'Dutch', 'nl.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('nn', 'Norwegian Nynorsk', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('no', 'Norwegian', 'no.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('nr', 'South Ndebele', 'nr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('nv', 'Navajo', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ny', 'Chichewa', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('oc', 'Occitan', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('oj', 'Ojibwa', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('om', 'Oromo', 'om.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('or', 'Oriya', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('os', 'Ossetian', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('pi', 'Pali', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('pl', 'Polish', 'pl.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('pt', 'Portuguese', 'pt.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('qu', 'Quechua', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('rm', 'Raeto-Romance', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('rn', 'Kirundi', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ro', 'Romanian', 'ro.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ru', 'Russian', 'ru.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('rw', 'Kinyarwanda', 'rw.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sa', 'Sanskrit', 'sa.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sc', 'Sardinian', 'sc.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('se', 'Northern Sami', 'se.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sg', 'Sango', 'sg.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('si', 'Sinhala', 'si.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sk', 'Slovak', 'sk.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sl', 'Slovenian', 'sl.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sm', 'Samoan', 'sm.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sn', 'Shona', 'sn.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sq', 'Albanian', 'al.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sr', 'Serbian', 'sr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ss', 'Swati', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('st', 'Southern Sotho', 'st.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('su', 'Sundanese', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sv', 'Swedish', 'se.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('sw', 'Swahili', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ta', 'Tamil', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('te', 'Telugu', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('tg', 'Tajik', 'tg.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('th', 'Thai', 'th.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ti', 'Tigrinya', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('tl', 'Tagalog', 'tl.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('tn', 'Tswana', 'tn.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('to', 'Tonga', 'to.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('tr', 'Turkish', 'tr.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ts', 'Tsonga', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('tt', 'Tatar', 'tt.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('tw', 'Twi', 'tw.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ty', 'Tahitian', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('uk', 'Ukrainian', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('us', 'English - USA', 'us.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('uz', 'Uzbek', 'uz.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('ve', 'Venda', 've.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('vi', 'Vietnamese', 'vi.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('vo', 'Volapuk', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('wa', 'Walloon', 'wa.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('wo', 'Wolof', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('xh', 'Xhosa', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('yo', 'Yoruba', '');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('za', 'Zhuang', 'za.png');
INSERT INTO `salondoris_hey_appscheduler_plugin_locale_languages` VALUES('zu', 'Zulu', '');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_log`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `function` varchar(255) DEFAULT NULL,
  `value` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_log_config`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_log_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_one_admin`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_one_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_plugin_sms`;

CREATE TABLE `salondoris_hey_appscheduler_plugin_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_resources`;

CREATE TABLE `salondoris_hey_appscheduler_resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_resources_services`;

CREATE TABLE `salondoris_hey_appscheduler_resources_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resources_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`resources_id`,`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_roles`;

CREATE TABLE `salondoris_hey_appscheduler_roles` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_roles` VALUES('1', 'admin', 'T');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_services`;

CREATE TABLE `salondoris_hey_appscheduler_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calendar_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  `before` smallint(5) unsigned DEFAULT NULL,
  `after` smallint(5) unsigned DEFAULT NULL,
  `total` smallint(5) unsigned DEFAULT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_services` VALUES('1', '1', '1', '10.00', '10', '0', '0', '10', '1');
INSERT INTO `salondoris_hey_appscheduler_services` VALUES('5', '1', '3', '20.00', '20', '0', '0', '20', '1');
INSERT INTO `salondoris_hey_appscheduler_services` VALUES('6', '1', '2', '30.00', '20', '5', '5', '30', '1');
INSERT INTO `salondoris_hey_appscheduler_services` VALUES('7', '1', '1', '40.00', '20', '10', '10', '40', '1');
INSERT INTO `salondoris_hey_appscheduler_services` VALUES('8', '1', '1', '50.00', '50', '0', '0', '50', '1');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_services_category`;

CREATE TABLE `salondoris_hey_appscheduler_services_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `show_front` varchar(250) DEFAULT NULL,
  `message` text,
  `order` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `salondoris_hey_appscheduler_services_category` VALUES('1', 'Categroy 1', 'on', 'Categroy description', '');
INSERT INTO `salondoris_hey_appscheduler_services_category` VALUES('2', 'Categroy 2', 'on', 'Categroy description', '');
INSERT INTO `salondoris_hey_appscheduler_services_category` VALUES('3', 'Categroy 3', 'on', 'Categroy description', '');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_services_extra_service`;

CREATE TABLE `salondoris_hey_appscheduler_services_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `extra_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`extra_id`,`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_services_extra_service` VALUES('8', '12', '1');
INSERT INTO `salondoris_hey_appscheduler_services_extra_service` VALUES('9', '13', '1');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_services_time`;

CREATE TABLE `salondoris_hey_appscheduler_services_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  `before` smallint(5) unsigned DEFAULT NULL,
  `after` smallint(5) unsigned DEFAULT NULL,
  `total` smallint(5) unsigned DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `calendar_id` (`foreign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `salondoris_hey_appscheduler_users`;

CREATE TABLE `salondoris_hey_appscheduler_users` (
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_users` VALUES('1', '1', 'admin3@demo.com', 'Ì[6;„‡Þ†ÿËòj{‹', 'Administrator', '', '2014-04-10 15:12:14', '2014-07-02 07:46:42', 'T', 'F', '127.0.0.1');
INSERT INTO `salondoris_hey_appscheduler_users` VALUES('2', '1', 'admin@varaa.com', '8n(~»ÆüA½¥§HÐ®©', 'Administrator', '', '2014-07-01 05:42:20', '2014-07-01 05:42:20', 'T', 'F', '88.112.84.164');

DROP TABLE IF EXISTS `salondoris_hey_appscheduler_working_times`;

CREATE TABLE `salondoris_hey_appscheduler_working_times` (
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_id` (`foreign_id`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `salondoris_hey_appscheduler_working_times` VALUES('1', '1', 'calendar', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `salondoris_hey_appscheduler_working_times` VALUES('2', '1', 'employee', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `salondoris_hey_appscheduler_working_times` VALUES('3', '2', 'employee', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `salondoris_hey_appscheduler_working_times` VALUES('4', '3', 'employee', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `salondoris_hey_appscheduler_working_times` VALUES('5', '4', 'employee', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');

