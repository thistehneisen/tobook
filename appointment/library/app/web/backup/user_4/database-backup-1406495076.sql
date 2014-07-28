DROP TABLE IF EXISTS `as_bookings`;

CREATE TABLE `as_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
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
  KEY `nuser_id` (`owner_id`),
  KEY `calendar_id` (`calendar_id`),
  CONSTRAINT `as_bookings_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

INSERT INTO `as_bookings` VALUES('12', '4', 'QN1406389566', '1', '50.00', '55.00', '11.00', '5.00', 'confirmed', '', 'quang hung', 'quanghung@gmail.com', '013212321312', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 15:46:06', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('13', '4', 'OO1406390947', '1', '30.00', '33.00', '6.60', '3.00', 'confirmed', '', 'test employeee 3', 'employee3test@gmail.com', '01312312123', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 16:09:07', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('14', '4', 'CM1406393002', '1', '20.00', '22.00', '4.40', '2.00', 'confirmed', '', 'abc', 'acc@aasdsa.com', '13123132123', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 16:43:22', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('15', '4', 'ZE1406395879', '1', '0.00', '0.00', '0.00', '0.00', 'confirmed', '', 'quang hung', 'quanghung@gmail.com', '013212321312', '', '', '', '', 'dsad 123123 ', 'adasdasd', '', '', '', '', '', '', '', '', '2014-07-26 17:32:01', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('16', '4', 'HF1406396841', '1', '', '', '', '', 'confirmed', '', 'test employeee 3', 'employee3test@gmail.com', '01312312123', '', '', '', '', 'sadas', 'dasdasda', 'asdasdasd', '', '', '', '', '', '', '', '2014-07-26 17:50:17', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('17', '4', 'RL1406397086', '1', '', '', '', '', 'confirmed', '', 'quang hung', 'quanghung@gmail.com', '013212321312', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 17:52:48', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('18', '4', 'UT1406397211', '1', '', '', '', '', 'confirmed', '', 'quang hung', 'quanghung@gmail.com', '013212321312', '', '', '', '', 'dasdsa', 'asdasd', 'sdasd', '', '', '', '', '', '', '', '2014-07-26 17:53:45', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('19', '4', 'NK1406398853', '1', '10.00', '11.00', '2.20', '1.00', 'confirmed', '', 'sadasd', 'asd@adas.dcom', '13213123123', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 18:20:53', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('20', '4', 'WK1406399048', '1', '', '', '', '', 'confirmed', '', 'quang hung', 'quanghung@gmail.com', '013212321312', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 18:25:15', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('21', '4', 'AX1406399985', '1', '50.00', '55.00', '11.00', '5.00', 'confirmed', '', 'sada', 's2232@adasdas.com', '1321312313', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 18:39:45', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('22', '4', 'WR1406400244', '1', '0.00', '0.00', '0.00', '0.00', 'confirmed', '', 'quang hung', 'quanghung@gmail.com', '013212321312', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 18:49:07', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('23', '4', 'TB1406400616', '1', '0.00', '0.00', '0.00', '0.00', 'confirmed', '', 'test employeee 3', 'employee3test@gmail.com', '01312312123', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 18:51:21', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('28', '4', 'MH1406412323', '1', '', '', '', '', 'confirmed', '', 'quang hung', 'quanghung@gmail.com', '013212321312', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 22:08:02', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('29', '4', 'RX1406412490', '1', '', '', '', '', 'confirmed', '', 'test employeee 3', 'employee3test@gmail.com', '01312312123', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 22:08:26', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('30', '4', 'XZ1406413959', '1', '20.00', '22.00', '4.40', '2.00', 'confirmed', '', 'adasd', 'dasd@2okoisad.com', 'adadasd', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-26 22:32:39', '1', '127.0.0.1');
INSERT INTO `as_bookings` VALUES('31', '4', 'NT1406454321', '1', '40.00', '44.00', '8.80', '4.00', 'confirmed', '', 'adsa', 'adasdas@adasd.com', '132123132', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-07-27 09:45:21', '1', '127.0.0.1');

DROP TABLE IF EXISTS `as_bookings_extra_service`;

CREATE TABLE `as_bookings_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `booking_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `extra_id` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_bookings_extra_service_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `as_bookings_services`;

CREATE TABLE `as_bookings_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
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
  KEY `nuser_id` (`owner_id`),
  KEY `booking_id` (`booking_id`),
  KEY `tmp_hash` (`tmp_hash`),
  CONSTRAINT `as_bookings_services_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

INSERT INTO `as_bookings_services` VALUES('15', '4', '', '10', '1', '2', '', '2014-07-27', '14:30:00', '1406471400', '10', '10.00', '0', '0');
INSERT INTO `as_bookings_services` VALUES('16', '4', '', '11', '8', '3', '', '2014-07-27', '13:30:00', '1406467800', '50', '50.00', '0', '0');
INSERT INTO `as_bookings_services` VALUES('17', '4', '', '12', '8', '4', '', '2014-07-31', '15:00:00', '1406818800', '50', '50.00', '0', '0');
INSERT INTO `as_bookings_services` VALUES('18', '4', '', '13', '6', '3', '', '2014-07-27', '14:55:00', '1406472900', '105', '30.00', '0', '0');
INSERT INTO `as_bookings_services` VALUES('19', '4', '', '14', '1', '2', '', '2014-07-28', '13:45:00', '1406555100', '30', '20.00', '0', '0');

DROP TABLE IF EXISTS `as_bookings_status`;

CREATE TABLE `as_bookings_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `booking_id` int(11) unsigned DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `admin` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_bookings_status_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_calendars`;

CREATE TABLE `as_calendars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `as_calendars_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO `as_calendars` VALUES('1', '4', '1');

DROP TABLE IF EXISTS `as_custom_times`;

CREATE TABLE `as_custom_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `start_lunch` time DEFAULT NULL,
  `end_lunch` time DEFAULT NULL,
  `is_dayoff` enum('T','F') DEFAULT 'F',
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  KEY `is_dayoff` (`is_dayoff`),
  CONSTRAINT `as_custom_times_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `as_custom_times` VALUES('1', '4', 'Closed', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_custom_times` VALUES('2', '4', 'Test 1', '10:00:00', '16:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_custom_times` VALUES('3', '4', 'Test 2', '09:00:00', '18:00:00', '12:00:00', '13:00:00', 'T');

DROP TABLE IF EXISTS `as_dates`;

CREATE TABLE `as_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
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
  KEY `nuser_id` (`owner_id`),
  KEY `is_dayoff` (`is_dayoff`),
  CONSTRAINT `as_dates_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

INSERT INTO `as_dates` VALUES('16', '4', '', 'employee', '1970-01-01', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_dates` VALUES('51', '4', '3', 'employee', '2014-07-28', '10:00:00', '16:00:00', '00:00:00', '00:00:00', 'F');
INSERT INTO `as_dates` VALUES('52', '4', '3', 'employee', '2014-07-29', '10:00:00', '16:00:00', '00:00:00', '00:00:00', 'F');
INSERT INTO `as_dates` VALUES('53', '4', '3', 'employee', '2014-07-30', '09:00:00', '18:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_dates` VALUES('54', '4', '1', 'employee', '2014-06-02', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_dates` VALUES('57', '4', '1', 'employee', '2014-05-26', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_dates` VALUES('58', '4', '1', 'employee', '2014-05-27', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_dates` VALUES('59', '4', '1', 'employee', '2014-05-28', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_dates` VALUES('60', '4', '1', 'employee', '2014-05-29', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_dates` VALUES('61', '4', '1', 'employee', '2014-05-30', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');
INSERT INTO `as_dates` VALUES('62', '4', '1', 'employee', '2014-05-31', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'T');

DROP TABLE IF EXISTS `as_employees`;

CREATE TABLE `as_employees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
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
  KEY `nuser_id` (`owner_id`),
  KEY `calendar_id` (`calendar_id`),
  CONSTRAINT `as_employees_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

INSERT INTO `as_employees` VALUES('1', '4', '1', 'employee1@gmail.com', 'ð—Ïè#a¼XðMÆ''Äž', '123456789', 'Employee description', '', '', '0', '0', '1');
INSERT INTO `as_employees` VALUES('2', '4', '1', 'employee2@gmail.com', 'ð—Ïè#a¼XðMÆ''Äž', '123456789', 'Employee description', '', '', '0', '0', '1');
INSERT INTO `as_employees` VALUES('3', '4', '1', 'employee3@gmail.com', 'ð—Ïè#a¼XðMÆ''Äž', '123456789', 'Employee description', '', '', '0', '0', '1');
INSERT INTO `as_employees` VALUES('4', '4', '1', 'employee4@gmail.com', 'ð—Ïè#a¼XðMÆ''Äž', '123456789', 'Employee description', '', '', '0', '0', '1');

DROP TABLE IF EXISTS `as_employees_custom_times`;

CREATE TABLE `as_employees_custom_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `employee_id` int(10) unsigned DEFAULT NULL,
  `customtime_id` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_employees_custom_times_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;

INSERT INTO `as_employees_custom_times` VALUES('63', '4', '1', '6', '2014-07-27');
INSERT INTO `as_employees_custom_times` VALUES('64', '4', '2', '3', '2014-07-29');

DROP TABLE IF EXISTS `as_employees_freetime`;

CREATE TABLE `as_employees_freetime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `start_ts` int(11) DEFAULT NULL,
  `end_ts` int(11) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_employees_freetime_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_employees_services`;

CREATE TABLE `as_employees_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `plustime` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`,`service_id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_employees_services_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=285 DEFAULT CHARSET=utf8;

INSERT INTO `as_employees_services` VALUES('203', '4', '1', '5', '0');
INSERT INTO `as_employees_services` VALUES('205', '4', '3', '5', '0');
INSERT INTO `as_employees_services` VALUES('206', '4', '4', '5', '0');
INSERT INTO `as_employees_services` VALUES('207', '4', '1', '6', '0');
INSERT INTO `as_employees_services` VALUES('209', '4', '3', '6', '0');
INSERT INTO `as_employees_services` VALUES('210', '4', '4', '6', '0');
INSERT INTO `as_employees_services` VALUES('215', '4', '1', '8', '0');
INSERT INTO `as_employees_services` VALUES('217', '4', '3', '8', '0');
INSERT INTO `as_employees_services` VALUES('218', '4', '4', '8', '0');
INSERT INTO `as_employees_services` VALUES('219', '4', '1', '7', '0');
INSERT INTO `as_employees_services` VALUES('221', '4', '3', '7', '0');
INSERT INTO `as_employees_services` VALUES('222', '4', '4', '7', '0');
INSERT INTO `as_employees_services` VALUES('224', '4', '2', '5', '');
INSERT INTO `as_employees_services` VALUES('253', '4', '1', '1', '10');
INSERT INTO `as_employees_services` VALUES('254', '4', '2', '1', '0');
INSERT INTO `as_employees_services` VALUES('255', '4', '3', '1', '0');
INSERT INTO `as_employees_services` VALUES('256', '4', '4', '1', '0');
INSERT INTO `as_employees_services` VALUES('257', '4', '1', '9', '10');
INSERT INTO `as_employees_services` VALUES('258', '4', '2', '9', '15');
INSERT INTO `as_employees_services` VALUES('259', '4', '3', '9', '20');
INSERT INTO `as_employees_services` VALUES('260', '4', '4', '9', '25');
INSERT INTO `as_employees_services` VALUES('261', '4', '1', '10', '5');
INSERT INTO `as_employees_services` VALUES('262', '4', '2', '10', '10');
INSERT INTO `as_employees_services` VALUES('263', '4', '3', '10', '15');
INSERT INTO `as_employees_services` VALUES('264', '4', '4', '10', '20');

DROP TABLE IF EXISTS `as_extra_service`;

CREATE TABLE `as_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `message` text,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_extra_service_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

INSERT INTO `as_extra_service` VALUES('15', '4', 'dasdasd', 'dasdasd', '121.00', '1');
INSERT INTO `as_extra_service` VALUES('17', '4', '23123', 'sadasd', '12.00', '1');
INSERT INTO `as_extra_service` VALUES('18', '4', 'wewewe', '', '12.00', '1');

DROP TABLE IF EXISTS `as_fields`;

CREATE TABLE `as_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `key` varchar(100) DEFAULT NULL,
  `type` enum('backend','frontend','arrays') DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `source` enum('script','plugin') DEFAULT 'script',
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_fields_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6250 DEFAULT CHARSET=utf8;

INSERT INTO `as_fields` VALUES('5', '4', 'user', 'backend', 'Username', 'script', '');
INSERT INTO `as_fields` VALUES('6', '4', 'pass', 'backend', 'Password', 'script', '2014-07-26 20:55:24');
INSERT INTO `as_fields` VALUES('7', '4', 'email', 'backend', 'E-Mail', 'script', '');
INSERT INTO `as_fields` VALUES('8', '4', 'url', 'backend', 'URL', 'script', '');
INSERT INTO `as_fields` VALUES('13', '4', 'created', 'backend', 'Created', 'script', '');
INSERT INTO `as_fields` VALUES('16', '4', 'btnSave', 'backend', 'Save', 'script', '');
INSERT INTO `as_fields` VALUES('17', '4', 'btnReset', 'backend', 'Reset', 'script', '');
INSERT INTO `as_fields` VALUES('18', '4', 'addLocale', 'backend', 'Add language', 'script', '');
INSERT INTO `as_fields` VALUES('22', '4', 'menuLang', 'backend', 'Menu Multi lang', 'script', '');
INSERT INTO `as_fields` VALUES('23', '4', 'menuPlugins', 'backend', 'Menu Plugins', 'script', '');
INSERT INTO `as_fields` VALUES('24', '4', 'menuUsers', 'backend', 'Menu Users', 'script', '');
INSERT INTO `as_fields` VALUES('25', '4', 'menuOptions', 'backend', 'Menu Options', 'script', '');
INSERT INTO `as_fields` VALUES('26', '4', 'menuLogout', 'backend', 'Menu Logout', 'script', '');
INSERT INTO `as_fields` VALUES('31', '4', 'btnUpdate', 'backend', 'Update', 'script', '');
INSERT INTO `as_fields` VALUES('36', '4', 'lblChoose', 'backend', 'Choose', 'script', '');
INSERT INTO `as_fields` VALUES('37', '4', 'btnSearch', 'backend', 'Search', 'script', '');
INSERT INTO `as_fields` VALUES('40', '4', 'backend', 'backend', 'Backend titles', 'script', '');
INSERT INTO `as_fields` VALUES('41', '4', 'frontend', 'backend', 'Front-end titles', 'script', '');
INSERT INTO `as_fields` VALUES('42', '4', 'locales', 'backend', 'Languages', 'script', '');
INSERT INTO `as_fields` VALUES('44', '4', 'adminLogin', 'backend', 'Admin Login', 'script', '');
INSERT INTO `as_fields` VALUES('45', '4', 'btnLogin', 'backend', 'Login', 'script', '');
INSERT INTO `as_fields` VALUES('47', '4', 'menuDashboard', 'backend', 'Menu Dashboard', 'script', '');
INSERT INTO `as_fields` VALUES('57', '4', 'lblOptionList', 'backend', 'Option list', 'script', '');
INSERT INTO `as_fields` VALUES('58', '4', 'btnAdd', 'backend', 'Button Add', 'script', '');
INSERT INTO `as_fields` VALUES('62', '4', 'lblDelete', 'backend', 'Delete', 'script', '');
INSERT INTO `as_fields` VALUES('65', '4', 'lblType', 'backend', 'Type', 'script', '');
INSERT INTO `as_fields` VALUES('66', '4', 'lblName', 'backend', 'Name', 'script', '');
INSERT INTO `as_fields` VALUES('67', '4', 'lblRole', 'backend', 'Role', 'script', '');
INSERT INTO `as_fields` VALUES('68', '4', 'lblStatus', 'backend', 'Status', 'script', '');
INSERT INTO `as_fields` VALUES('69', '4', 'lblIsActive', 'backend', 'Is Active', 'script', '');
INSERT INTO `as_fields` VALUES('70', '4', 'lblUpdateUser', 'backend', 'Update user', 'script', '');
INSERT INTO `as_fields` VALUES('71', '4', 'lblAddUser', 'backend', 'Add user', 'script', '');
INSERT INTO `as_fields` VALUES('72', '4', 'lblValue', 'backend', 'Value', 'script', '');
INSERT INTO `as_fields` VALUES('73', '4', 'lblOption', 'backend', 'Option', 'script', '');
INSERT INTO `as_fields` VALUES('74', '4', 'lblDays', 'backend', 'Days', 'script', '');
INSERT INTO `as_fields` VALUES('115', '4', 'menuLocales', 'backend', 'Menu Languages', 'script', '');
INSERT INTO `as_fields` VALUES('116', '4', 'lblYes', 'backend', 'Yes', 'script', '');
INSERT INTO `as_fields` VALUES('117', '4', 'lblNo', 'backend', 'No', 'script', '');
INSERT INTO `as_fields` VALUES('338', '4', 'lblError', 'backend', 'Error', 'script', '');
INSERT INTO `as_fields` VALUES('347', '4', 'btnBack', 'backend', 'Button Back', 'script', '');
INSERT INTO `as_fields` VALUES('355', '4', 'btnCancel', 'backend', 'Button Cancel', 'script', '');
INSERT INTO `as_fields` VALUES('356', '4', 'lblForgot', 'backend', 'Forgot password', 'script', '');
INSERT INTO `as_fields` VALUES('357', '4', 'adminForgot', 'backend', 'Forgot password', 'script', '');
INSERT INTO `as_fields` VALUES('358', '4', 'btnSend', 'backend', 'Button Send', 'script', '');
INSERT INTO `as_fields` VALUES('359', '4', 'emailForgotSubject', 'backend', 'Email / Forgot Subject', 'script', '');
INSERT INTO `as_fields` VALUES('360', '4', 'emailForgotBody', 'backend', 'Email / Forgot Body', 'script', '');
INSERT INTO `as_fields` VALUES('365', '4', 'menuProfile', 'backend', 'Menu Profile', 'script', '');
INSERT INTO `as_fields` VALUES('380', '4', 'infoLocalesTitle', 'backend', 'Infobox / Locales Title', 'script', '');
INSERT INTO `as_fields` VALUES('381', '4', 'infoLocalesBody', 'backend', 'Infobox / Locales Body', 'script', '');
INSERT INTO `as_fields` VALUES('382', '4', 'infoLocalesBackendTitle', 'backend', 'Infobox / Locales Backend Title', 'script', '');
INSERT INTO `as_fields` VALUES('383', '4', 'infoLocalesBackendBody', 'backend', 'Infobox / Locales Backend Body', 'script', '');
INSERT INTO `as_fields` VALUES('384', '4', 'infoLocalesFrontendTitle', 'backend', 'Infobox / Locales Frontend Title', 'script', '');
INSERT INTO `as_fields` VALUES('385', '4', 'infoLocalesFrontendBody', 'backend', 'Infobox / Locales Frontend Body', 'script', '');
INSERT INTO `as_fields` VALUES('386', '4', 'infoListingPricesTitle', 'backend', 'Infobox / Listing Prices Title', 'script', '');
INSERT INTO `as_fields` VALUES('387', '4', 'infoListingPricesBody', 'backend', 'Infobox / Listing Prices Body', 'script', '');
INSERT INTO `as_fields` VALUES('388', '4', 'infoListingBookingsTitle', 'backend', 'Infobox / Listing Bookings Title', 'script', '');
INSERT INTO `as_fields` VALUES('389', '4', 'infoListingBookingsBody', 'backend', 'Infobox / Listing Bookings Body', 'script', '');
INSERT INTO `as_fields` VALUES('390', '4', 'infoListingContactTitle', 'backend', 'Infobox / Listing Contact Title', 'script', '');
INSERT INTO `as_fields` VALUES('391', '4', 'infoListingContactBody', 'backend', 'Infobox / Listing Contact Body', 'script', '');
INSERT INTO `as_fields` VALUES('392', '4', 'infoListingAddressTitle', 'backend', 'Infobox / Listing Address Title', 'script', '');
INSERT INTO `as_fields` VALUES('393', '4', 'infoListingAddressBody', 'backend', 'Infobox / Listing Address Body', 'script', '');
INSERT INTO `as_fields` VALUES('395', '4', 'infoListingExtendTitle', 'backend', 'Infobox / Extend exp.date Title', 'script', '');
INSERT INTO `as_fields` VALUES('396', '4', 'infoListingExtendBody', 'backend', 'Infobox / Extend exp.date Body', 'script', '');
INSERT INTO `as_fields` VALUES('408', '4', 'menuBackup', 'backend', 'Menu Backup', 'script', '');
INSERT INTO `as_fields` VALUES('409', '4', 'btnBackup', 'backend', 'Button Backup', 'script', '');
INSERT INTO `as_fields` VALUES('410', '4', 'lblBackupDatabase', 'backend', 'Backup / Database', 'script', '');
INSERT INTO `as_fields` VALUES('411', '4', 'lblBackupFiles', 'backend', 'Backup / Files', 'script', '');
INSERT INTO `as_fields` VALUES('412', '4', 'gridChooseAction', 'backend', 'Grid / Choose Action', 'script', '');
INSERT INTO `as_fields` VALUES('413', '4', 'gridGotoPage', 'backend', 'Grid / Go to page', 'script', '');
INSERT INTO `as_fields` VALUES('414', '4', 'gridTotalItems', 'backend', 'Grid / Total items', 'script', '');
INSERT INTO `as_fields` VALUES('415', '4', 'gridItemsPerPage', 'backend', 'Grid / Items per page', 'script', '');
INSERT INTO `as_fields` VALUES('416', '4', 'gridPrevPage', 'backend', 'Grid / Prev page', 'script', '');
INSERT INTO `as_fields` VALUES('417', '4', 'gridPrev', 'backend', 'Grid / Prev', 'script', '');
INSERT INTO `as_fields` VALUES('418', '4', 'gridNextPage', 'backend', 'Grid / Next page', 'script', '');
INSERT INTO `as_fields` VALUES('419', '4', 'gridNext', 'backend', 'Grid / Next', 'script', '');
INSERT INTO `as_fields` VALUES('420', '4', 'gridDeleteConfirmation', 'backend', 'Grid / Delete confirmation', 'script', '');
INSERT INTO `as_fields` VALUES('421', '4', 'gridConfirmationTitle', 'backend', 'Grid / Confirmation Title', 'script', '');
INSERT INTO `as_fields` VALUES('422', '4', 'gridActionTitle', 'backend', 'Grid / Action Title', 'script', '');
INSERT INTO `as_fields` VALUES('423', '4', 'gridBtnOk', 'backend', 'Grid / Button OK', 'script', '');
INSERT INTO `as_fields` VALUES('424', '4', 'gridBtnCancel', 'backend', 'Grid / Button Cancel', 'script', '');
INSERT INTO `as_fields` VALUES('425', '4', 'gridBtnDelete', 'backend', 'Grid / Button Delete', 'script', '');
INSERT INTO `as_fields` VALUES('426', '4', 'gridEmptyResult', 'backend', 'Grid / Empty resultset', 'script', '');
INSERT INTO `as_fields` VALUES('433', '4', 'multilangTooltip', 'backend', 'MultiLang / Tooltip', 'script', '');
INSERT INTO `as_fields` VALUES('434', '4', 'lblIp', 'backend', 'IP address', 'script', '');
INSERT INTO `as_fields` VALUES('435', '4', 'lblUserCreated', 'backend', 'User / Registration Date & Time', 'script', '');
INSERT INTO `as_fields` VALUES('441', '4', 'opt_o_currency', 'backend', 'Options / Currency', 'script', '');
INSERT INTO `as_fields` VALUES('442', '4', 'opt_o_date_format', 'backend', 'Options / Date format', 'script', '');
INSERT INTO `as_fields` VALUES('451', '4', 'opt_o_timezone', 'backend', 'Options / Timezone', 'script', '');
INSERT INTO `as_fields` VALUES('452', '4', 'opt_o_week_start', 'backend', 'Options / First day of the week', 'script', '');
INSERT INTO `as_fields` VALUES('455', '4', 'u_statarr_ARRAY_T', 'arrays', 'u_statarr_ARRAY_T', 'script', '');
INSERT INTO `as_fields` VALUES('456', '4', 'u_statarr_ARRAY_F', 'arrays', 'u_statarr_ARRAY_F', 'script', '');
INSERT INTO `as_fields` VALUES('457', '4', 'filter_ARRAY_active', 'arrays', 'filter_ARRAY_active', 'script', '');
INSERT INTO `as_fields` VALUES('458', '4', 'filter_ARRAY_inactive', 'arrays', 'filter_ARRAY_inactive', 'script', '');
INSERT INTO `as_fields` VALUES('471', '4', '_yesno_ARRAY_T', 'arrays', '_yesno_ARRAY_T', 'script', '');
INSERT INTO `as_fields` VALUES('472', '4', '_yesno_ARRAY_F', 'arrays', '_yesno_ARRAY_F', 'script', '');
INSERT INTO `as_fields` VALUES('476', '4', 'personal_titles_ARRAY_mr', 'arrays', 'personal_titles_ARRAY_mr', 'script', '');
INSERT INTO `as_fields` VALUES('477', '4', 'personal_titles_ARRAY_mrs', 'arrays', 'personal_titles_ARRAY_mrs', 'script', '');
INSERT INTO `as_fields` VALUES('478', '4', 'personal_titles_ARRAY_miss', 'arrays', 'personal_titles_ARRAY_miss', 'script', '');
INSERT INTO `as_fields` VALUES('479', '4', 'personal_titles_ARRAY_ms', 'arrays', 'personal_titles_ARRAY_ms', 'script', '');
INSERT INTO `as_fields` VALUES('480', '4', 'personal_titles_ARRAY_dr', 'arrays', 'personal_titles_ARRAY_dr', 'script', '');
INSERT INTO `as_fields` VALUES('481', '4', 'personal_titles_ARRAY_prof', 'arrays', 'personal_titles_ARRAY_prof', 'script', '');
INSERT INTO `as_fields` VALUES('482', '4', 'personal_titles_ARRAY_rev', 'arrays', 'personal_titles_ARRAY_rev', 'script', '');
INSERT INTO `as_fields` VALUES('483', '4', 'personal_titles_ARRAY_other', 'arrays', 'personal_titles_ARRAY_other', 'script', '');
INSERT INTO `as_fields` VALUES('496', '4', 'timezones_ARRAY_-43200', 'arrays', 'timezones_ARRAY_-43200', 'script', '');
INSERT INTO `as_fields` VALUES('497', '4', 'timezones_ARRAY_-39600', 'arrays', 'timezones_ARRAY_-39600', 'script', '');
INSERT INTO `as_fields` VALUES('498', '4', 'timezones_ARRAY_-36000', 'arrays', 'timezones_ARRAY_-36000', 'script', '');
INSERT INTO `as_fields` VALUES('499', '4', 'timezones_ARRAY_-32400', 'arrays', 'timezones_ARRAY_-32400', 'script', '');
INSERT INTO `as_fields` VALUES('500', '4', 'timezones_ARRAY_-28800', 'arrays', 'timezones_ARRAY_-28800', 'script', '');
INSERT INTO `as_fields` VALUES('501', '4', 'timezones_ARRAY_-25200', 'arrays', 'timezones_ARRAY_-25200', 'script', '');
INSERT INTO `as_fields` VALUES('502', '4', 'timezones_ARRAY_-21600', 'arrays', 'timezones_ARRAY_-21600', 'script', '');
INSERT INTO `as_fields` VALUES('503', '4', 'timezones_ARRAY_-18000', 'arrays', 'timezones_ARRAY_-18000', 'script', '');
INSERT INTO `as_fields` VALUES('504', '4', 'timezones_ARRAY_-14400', 'arrays', 'timezones_ARRAY_-14400', 'script', '');
INSERT INTO `as_fields` VALUES('505', '4', 'timezones_ARRAY_-10800', 'arrays', 'timezones_ARRAY_-10800', 'script', '');
INSERT INTO `as_fields` VALUES('506', '4', 'timezones_ARRAY_-7200', 'arrays', 'timezones_ARRAY_-7200', 'script', '');
INSERT INTO `as_fields` VALUES('507', '4', 'timezones_ARRAY_-3600', 'arrays', 'timezones_ARRAY_-3600', 'script', '');
INSERT INTO `as_fields` VALUES('508', '4', 'timezones_ARRAY_0', 'arrays', 'timezones_ARRAY_0', 'script', '');
INSERT INTO `as_fields` VALUES('509', '4', 'timezones_ARRAY_3600', 'arrays', 'timezones_ARRAY_3600', 'script', '');
INSERT INTO `as_fields` VALUES('510', '4', 'timezones_ARRAY_7200', 'arrays', 'timezones_ARRAY_7200', 'script', '');
INSERT INTO `as_fields` VALUES('511', '4', 'timezones_ARRAY_10800', 'arrays', 'timezones_ARRAY_10800', 'script', '');
INSERT INTO `as_fields` VALUES('512', '4', 'timezones_ARRAY_14400', 'arrays', 'timezones_ARRAY_14400', 'script', '');
INSERT INTO `as_fields` VALUES('513', '4', 'timezones_ARRAY_18000', 'arrays', 'timezones_ARRAY_18000', 'script', '');
INSERT INTO `as_fields` VALUES('514', '4', 'timezones_ARRAY_21600', 'arrays', 'timezones_ARRAY_21600', 'script', '');
INSERT INTO `as_fields` VALUES('515', '4', 'timezones_ARRAY_25200', 'arrays', 'timezones_ARRAY_25200', 'script', '');
INSERT INTO `as_fields` VALUES('516', '4', 'timezones_ARRAY_28800', 'arrays', 'timezones_ARRAY_28800', 'script', '');
INSERT INTO `as_fields` VALUES('517', '4', 'timezones_ARRAY_32400', 'arrays', 'timezones_ARRAY_32400', 'script', '');
INSERT INTO `as_fields` VALUES('518', '4', 'timezones_ARRAY_36000', 'arrays', 'timezones_ARRAY_36000', 'script', '');
INSERT INTO `as_fields` VALUES('519', '4', 'timezones_ARRAY_39600', 'arrays', 'timezones_ARRAY_39600', 'script', '');
INSERT INTO `as_fields` VALUES('520', '4', 'timezones_ARRAY_43200', 'arrays', 'timezones_ARRAY_43200', 'script', '');
INSERT INTO `as_fields` VALUES('521', '4', 'timezones_ARRAY_46800', 'arrays', 'timezones_ARRAY_46800', 'script', '');
INSERT INTO `as_fields` VALUES('540', '4', 'error_titles_ARRAY_AU01', 'arrays', 'error_titles_ARRAY_AU01', 'script', '');
INSERT INTO `as_fields` VALUES('541', '4', 'error_titles_ARRAY_AU03', 'arrays', 'error_titles_ARRAY_AU03', 'script', '');
INSERT INTO `as_fields` VALUES('542', '4', 'error_titles_ARRAY_AU04', 'arrays', 'error_titles_ARRAY_AU04', 'script', '');
INSERT INTO `as_fields` VALUES('543', '4', 'error_titles_ARRAY_AU08', 'arrays', 'error_titles_ARRAY_AU08', 'script', '');
INSERT INTO `as_fields` VALUES('544', '4', 'error_titles_ARRAY_AO01', 'arrays', 'error_titles_ARRAY_AO01', 'script', '');
INSERT INTO `as_fields` VALUES('552', '4', 'error_titles_ARRAY_AB01', 'arrays', 'error_titles_ARRAY_AB01', 'script', '');
INSERT INTO `as_fields` VALUES('553', '4', 'error_titles_ARRAY_AB02', 'arrays', 'error_titles_ARRAY_AB02', 'script', '');
INSERT INTO `as_fields` VALUES('554', '4', 'error_titles_ARRAY_AB03', 'arrays', 'error_titles_ARRAY_AB03', 'script', '');
INSERT INTO `as_fields` VALUES('555', '4', 'error_titles_ARRAY_AB04', 'arrays', 'error_titles_ARRAY_AB04', 'script', '');
INSERT INTO `as_fields` VALUES('556', '4', 'error_titles_ARRAY_AA10', 'arrays', 'error_titles_ARRAY_AA10', 'script', '');
INSERT INTO `as_fields` VALUES('557', '4', 'error_titles_ARRAY_AA11', 'arrays', 'error_titles_ARRAY_AA11', 'script', '');
INSERT INTO `as_fields` VALUES('558', '4', 'error_titles_ARRAY_AA12', 'arrays', 'error_titles_ARRAY_AA12', 'script', '');
INSERT INTO `as_fields` VALUES('559', '4', 'error_titles_ARRAY_AA13', 'arrays', 'error_titles_ARRAY_AA13', 'script', '');
INSERT INTO `as_fields` VALUES('578', '4', 'error_bodies_ARRAY_AU01', 'arrays', 'error_bodies_ARRAY_AU01', 'script', '');
INSERT INTO `as_fields` VALUES('579', '4', 'error_bodies_ARRAY_AU03', 'arrays', 'error_bodies_ARRAY_AU03', 'script', '');
INSERT INTO `as_fields` VALUES('580', '4', 'error_bodies_ARRAY_AU04', 'arrays', 'error_bodies_ARRAY_AU04', 'script', '');
INSERT INTO `as_fields` VALUES('581', '4', 'error_bodies_ARRAY_AU08', 'arrays', 'error_bodies_ARRAY_AU08', 'script', '');
INSERT INTO `as_fields` VALUES('582', '4', 'error_bodies_ARRAY_AO01', 'arrays', 'error_bodies_ARRAY_AO01', 'script', '');
INSERT INTO `as_fields` VALUES('589', '4', 'error_bodies_ARRAY_ALC01', 'arrays', 'error_bodies_ARRAY_ALC01', 'script', '');
INSERT INTO `as_fields` VALUES('590', '4', 'error_bodies_ARRAY_AB01', 'arrays', 'error_bodies_ARRAY_AB01', 'script', '');
INSERT INTO `as_fields` VALUES('591', '4', 'error_bodies_ARRAY_AB02', 'arrays', 'error_bodies_ARRAY_AB02', 'script', '');
INSERT INTO `as_fields` VALUES('592', '4', 'error_bodies_ARRAY_AB03', 'arrays', 'error_bodies_ARRAY_AB03', 'script', '');
INSERT INTO `as_fields` VALUES('593', '4', 'error_bodies_ARRAY_AB04', 'arrays', 'error_bodies_ARRAY_AB04', 'script', '');
INSERT INTO `as_fields` VALUES('594', '4', 'error_bodies_ARRAY_AA10', 'arrays', 'error_bodies_ARRAY_AA10', 'script', '');
INSERT INTO `as_fields` VALUES('595', '4', 'error_bodies_ARRAY_AA11', 'arrays', 'error_bodies_ARRAY_AA11', 'script', '');
INSERT INTO `as_fields` VALUES('596', '4', 'error_bodies_ARRAY_AA12', 'arrays', 'error_bodies_ARRAY_AA12', 'script', '');
INSERT INTO `as_fields` VALUES('597', '4', 'error_bodies_ARRAY_AA13', 'arrays', 'error_bodies_ARRAY_AA13', 'script', '');
INSERT INTO `as_fields` VALUES('627', '4', 'months_ARRAY_1', 'arrays', 'months_ARRAY_1', 'script', '');
INSERT INTO `as_fields` VALUES('628', '4', 'months_ARRAY_2', 'arrays', 'months_ARRAY_2', 'script', '');
INSERT INTO `as_fields` VALUES('629', '4', 'months_ARRAY_3', 'arrays', 'months_ARRAY_3', 'script', '');
INSERT INTO `as_fields` VALUES('630', '4', 'months_ARRAY_4', 'arrays', 'months_ARRAY_4', 'script', '');
INSERT INTO `as_fields` VALUES('631', '4', 'months_ARRAY_5', 'arrays', 'months_ARRAY_5', 'script', '');
INSERT INTO `as_fields` VALUES('632', '4', 'months_ARRAY_6', 'arrays', 'months_ARRAY_6', 'script', '');
INSERT INTO `as_fields` VALUES('633', '4', 'months_ARRAY_7', 'arrays', 'months_ARRAY_7', 'script', '');
INSERT INTO `as_fields` VALUES('634', '4', 'months_ARRAY_8', 'arrays', 'months_ARRAY_8', 'script', '');
INSERT INTO `as_fields` VALUES('635', '4', 'months_ARRAY_9', 'arrays', 'months_ARRAY_9', 'script', '');
INSERT INTO `as_fields` VALUES('636', '4', 'months_ARRAY_10', 'arrays', 'months_ARRAY_10', 'script', '');
INSERT INTO `as_fields` VALUES('637', '4', 'months_ARRAY_11', 'arrays', 'months_ARRAY_11', 'script', '');
INSERT INTO `as_fields` VALUES('638', '4', 'months_ARRAY_12', 'arrays', 'months_ARRAY_12', 'script', '');
INSERT INTO `as_fields` VALUES('639', '4', 'days_ARRAY_0', 'arrays', 'days_ARRAY_0', 'script', '');
INSERT INTO `as_fields` VALUES('640', '4', 'days_ARRAY_1', 'arrays', 'days_ARRAY_1', 'script', '');
INSERT INTO `as_fields` VALUES('641', '4', 'days_ARRAY_2', 'arrays', 'days_ARRAY_2', 'script', '');
INSERT INTO `as_fields` VALUES('642', '4', 'days_ARRAY_3', 'arrays', 'days_ARRAY_3', 'script', '');
INSERT INTO `as_fields` VALUES('643', '4', 'days_ARRAY_4', 'arrays', 'days_ARRAY_4', 'script', '');
INSERT INTO `as_fields` VALUES('644', '4', 'days_ARRAY_5', 'arrays', 'days_ARRAY_5', 'script', '');
INSERT INTO `as_fields` VALUES('645', '4', 'days_ARRAY_6', 'arrays', 'days_ARRAY_6', 'script', '');
INSERT INTO `as_fields` VALUES('646', '4', 'day_names_ARRAY_0', 'arrays', 'day_names_ARRAY_0', 'script', '');
INSERT INTO `as_fields` VALUES('647', '4', 'day_names_ARRAY_1', 'arrays', 'day_names_ARRAY_1', 'script', '');
INSERT INTO `as_fields` VALUES('648', '4', 'day_names_ARRAY_2', 'arrays', 'day_names_ARRAY_2', 'script', '');
INSERT INTO `as_fields` VALUES('649', '4', 'day_names_ARRAY_3', 'arrays', 'day_names_ARRAY_3', 'script', '');
INSERT INTO `as_fields` VALUES('650', '4', 'day_names_ARRAY_4', 'arrays', 'day_names_ARRAY_4', 'script', '');
INSERT INTO `as_fields` VALUES('651', '4', 'day_names_ARRAY_5', 'arrays', 'day_names_ARRAY_5', 'script', '');
INSERT INTO `as_fields` VALUES('652', '4', 'day_names_ARRAY_6', 'arrays', 'day_names_ARRAY_6', 'script', '');
INSERT INTO `as_fields` VALUES('653', '4', 'short_months_ARRAY_1', 'arrays', 'short_months_ARRAY_1', 'script', '');
INSERT INTO `as_fields` VALUES('654', '4', 'short_months_ARRAY_2', 'arrays', 'short_months_ARRAY_2', 'script', '');
INSERT INTO `as_fields` VALUES('655', '4', 'short_months_ARRAY_3', 'arrays', 'short_months_ARRAY_3', 'script', '');
INSERT INTO `as_fields` VALUES('656', '4', 'short_months_ARRAY_4', 'arrays', 'short_months_ARRAY_4', 'script', '');
INSERT INTO `as_fields` VALUES('657', '4', 'short_months_ARRAY_5', 'arrays', 'short_months_ARRAY_5', 'script', '');
INSERT INTO `as_fields` VALUES('658', '4', 'short_months_ARRAY_6', 'arrays', 'short_months_ARRAY_6', 'script', '');
INSERT INTO `as_fields` VALUES('659', '4', 'short_months_ARRAY_7', 'arrays', 'short_months_ARRAY_7', 'script', '');
INSERT INTO `as_fields` VALUES('660', '4', 'short_months_ARRAY_8', 'arrays', 'short_months_ARRAY_8', 'script', '');
INSERT INTO `as_fields` VALUES('661', '4', 'short_months_ARRAY_9', 'arrays', 'short_months_ARRAY_9', 'script', '');
INSERT INTO `as_fields` VALUES('662', '4', 'short_months_ARRAY_10', 'arrays', 'short_months_ARRAY_10', 'script', '');
INSERT INTO `as_fields` VALUES('663', '4', 'short_months_ARRAY_11', 'arrays', 'short_months_ARRAY_11', 'script', '');
INSERT INTO `as_fields` VALUES('664', '4', 'short_months_ARRAY_12', 'arrays', 'short_months_ARRAY_12', 'script', '');
INSERT INTO `as_fields` VALUES('665', '4', 'status_ARRAY_1', 'arrays', 'status_ARRAY_1', 'script', '');
INSERT INTO `as_fields` VALUES('666', '4', 'status_ARRAY_2', 'arrays', 'status_ARRAY_2', 'script', '');
INSERT INTO `as_fields` VALUES('667', '4', 'status_ARRAY_3', 'arrays', 'status_ARRAY_3', 'script', '');
INSERT INTO `as_fields` VALUES('668', '4', 'status_ARRAY_7', 'arrays', 'status_ARRAY_7', 'script', '');
INSERT INTO `as_fields` VALUES('669', '4', 'status_ARRAY_123', 'arrays', 'status_ARRAY_123', 'script', '');
INSERT INTO `as_fields` VALUES('670', '4', 'status_ARRAY_999', 'arrays', 'status_ARRAY_999', 'script', '');
INSERT INTO `as_fields` VALUES('671', '4', 'status_ARRAY_998', 'arrays', 'status_ARRAY_998', 'script', '');
INSERT INTO `as_fields` VALUES('672', '4', 'status_ARRAY_997', 'arrays', 'status_ARRAY_997', 'script', '');
INSERT INTO `as_fields` VALUES('673', '4', 'status_ARRAY_996', 'arrays', 'status_ARRAY_996', 'script', '');
INSERT INTO `as_fields` VALUES('674', '4', 'status_ARRAY_9999', 'arrays', 'status_ARRAY_9999', 'script', '');
INSERT INTO `as_fields` VALUES('675', '4', 'status_ARRAY_9998', 'arrays', 'status_ARRAY_9998', 'script', '');
INSERT INTO `as_fields` VALUES('676', '4', 'status_ARRAY_9997', 'arrays', 'status_ARRAY_9997', 'script', '');
INSERT INTO `as_fields` VALUES('677', '4', 'login_err_ARRAY_1', 'arrays', 'login_err_ARRAY_1', 'script', '');
INSERT INTO `as_fields` VALUES('678', '4', 'login_err_ARRAY_2', 'arrays', 'login_err_ARRAY_2', 'script', '');
INSERT INTO `as_fields` VALUES('679', '4', 'login_err_ARRAY_3', 'arrays', 'login_err_ARRAY_3', 'script', '');
INSERT INTO `as_fields` VALUES('907', '4', 'localeArrays', 'backend', 'Locale / Arrays titles', 'script', '');
INSERT INTO `as_fields` VALUES('908', '4', 'infoLocalesArraysTitle', 'backend', 'Locale / Languages Array Title', 'script', '');
INSERT INTO `as_fields` VALUES('909', '4', 'infoLocalesArraysBody', 'backend', 'Locale / Languages Array Body', 'script', '');
INSERT INTO `as_fields` VALUES('910', '4', 'lnkBack', 'backend', 'Link Back', 'script', '');
INSERT INTO `as_fields` VALUES('982', '4', 'locale_order', 'backend', 'Locale / Order', 'script', '');
INSERT INTO `as_fields` VALUES('983', '4', 'locale_is_default', 'backend', 'Locale / Is default', 'script', '');
INSERT INTO `as_fields` VALUES('984', '4', 'locale_flag', 'backend', 'Locale / Flag', 'script', '');
INSERT INTO `as_fields` VALUES('985', '4', 'locale_title', 'backend', 'Locale / Title', 'script', '');
INSERT INTO `as_fields` VALUES('986', '4', 'btnDelete', 'backend', 'Button Delete', 'script', '');
INSERT INTO `as_fields` VALUES('990', '4', 'btnContinue', 'backend', 'Button Continue', 'script', '');
INSERT INTO `as_fields` VALUES('992', '4', 'vr_email_taken', 'backend', 'Users / Email already taken', 'script', '');
INSERT INTO `as_fields` VALUES('993', '4', 'revert_status', 'backend', 'Revert status', 'script', '');
INSERT INTO `as_fields` VALUES('994', '4', 'lblExport', 'backend', 'Export', 'script', '');
INSERT INTO `as_fields` VALUES('995', '4', 'opt_o_send_email', 'backend', 'opt_o_send_email', 'script', '');
INSERT INTO `as_fields` VALUES('996', '4', 'opt_o_smtp_host', 'backend', 'opt_o_smtp_host', 'script', '');
INSERT INTO `as_fields` VALUES('997', '4', 'opt_o_smtp_port', 'backend', 'opt_o_smtp_port', 'script', '');
INSERT INTO `as_fields` VALUES('998', '4', 'opt_o_smtp_user', 'backend', 'opt_o_smtp_user', 'script', '');
INSERT INTO `as_fields` VALUES('999', '4', 'opt_o_smtp_pass', 'backend', 'opt_o_smtp_pass', 'script', '');
INSERT INTO `as_fields` VALUES('1053', '4', 'menuServices', 'backend', 'Menu Services', 'script', '2013-09-16 09:50:50');
INSERT INTO `as_fields` VALUES('1054', '4', 'menuEmployees', 'backend', 'Menu Employees', 'script', '2013-09-16 09:51:03');
INSERT INTO `as_fields` VALUES('1055', '4', 'service_add', 'backend', 'Services / Add service', 'script', '2013-09-16 12:44:28');
INSERT INTO `as_fields` VALUES('1056', '4', 'lblAll', 'backend', 'All', 'script', '2013-09-16 12:47:43');
INSERT INTO `as_fields` VALUES('1057', '4', 'service_name', 'backend', 'Services / Name', 'script', '2013-11-22 09:45:50');
INSERT INTO `as_fields` VALUES('1058', '4', 'service_price', 'backend', 'Services / Price', 'script', '2013-09-16 12:52:48');
INSERT INTO `as_fields` VALUES('1059', '4', 'service_before', 'backend', 'Services / Before', 'script', '2013-09-16 12:53:30');
INSERT INTO `as_fields` VALUES('1060', '4', 'service_after', 'backend', 'Services / After', 'script', '2013-09-16 12:53:22');
INSERT INTO `as_fields` VALUES('1061', '4', 'service_total', 'backend', 'Services / Total', 'script', '2013-09-16 12:53:41');
INSERT INTO `as_fields` VALUES('1062', '4', 'service_length', 'backend', 'Services / Length', 'script', '2013-09-16 12:53:59');
INSERT INTO `as_fields` VALUES('1063', '4', 'service_desc', 'backend', 'Services / Description', 'script', '2013-09-16 12:54:18');
INSERT INTO `as_fields` VALUES('1064', '4', 'service_status', 'backend', 'Services / Status', 'script', '2013-09-16 12:56:13');
INSERT INTO `as_fields` VALUES('1065', '4', 'service_employees', 'backend', 'Services / Employees', 'script', '2013-09-16 12:59:53');
INSERT INTO `as_fields` VALUES('1066', '4', 'service_update', 'backend', 'Services / Update service', 'script', '2013-09-16 13:21:54');
INSERT INTO `as_fields` VALUES('1067', '4', 'is_active_ARRAY_1', 'arrays', 'is_active_ARRAY_1', 'script', '2013-09-16 13:42:57');
INSERT INTO `as_fields` VALUES('1068', '4', 'is_active_ARRAY_0', 'arrays', 'is_active_ARRAY_0', 'script', '2013-09-16 13:43:10');
INSERT INTO `as_fields` VALUES('1069', '4', 'delete_selected', 'backend', 'Grid / Delete selected', 'script', '2013-09-16 14:10:00');
INSERT INTO `as_fields` VALUES('1070', '4', 'delete_confirmation', 'backend', 'Grid / Confirmation Title', 'script', '2013-09-16 14:09:36');
INSERT INTO `as_fields` VALUES('1071', '4', 'error_bodies_ARRAY_AS08', 'arrays', 'error_bodies_ARRAY_AS08', 'script', '2013-09-16 14:11:08');
INSERT INTO `as_fields` VALUES('1072', '4', 'error_titles_ARRAY_AS01', 'arrays', 'error_titles_ARRAY_AS01', 'script', '2013-09-16 14:11:21');
INSERT INTO `as_fields` VALUES('1073', '4', 'error_titles_ARRAY_AS03', 'arrays', 'error_titles_ARRAY_AS03', 'script', '2013-09-16 14:11:31');
INSERT INTO `as_fields` VALUES('1074', '4', 'error_titles_ARRAY_AS04', 'arrays', 'error_titles_ARRAY_AS04', 'script', '2013-09-16 14:11:40');
INSERT INTO `as_fields` VALUES('1075', '4', 'error_titles_ARRAY_AS08', 'arrays', 'error_titles_ARRAY_AS08', 'script', '2013-09-16 14:11:48');
INSERT INTO `as_fields` VALUES('1076', '4', 'error_bodies_ARRAY_AS01', 'arrays', 'error_bodies_ARRAY_AS01', 'script', '2013-09-16 14:11:58');
INSERT INTO `as_fields` VALUES('1077', '4', 'error_bodies_ARRAY_AS03', 'arrays', 'error_bodies_ARRAY_AS03', 'script', '2013-09-16 14:12:09');
INSERT INTO `as_fields` VALUES('1078', '4', 'error_bodies_ARRAY_AS04', 'arrays', 'error_bodies_ARRAY_AS04', 'script', '2013-09-16 14:12:21');
INSERT INTO `as_fields` VALUES('1079', '4', 'error_titles_ARRAY_AS09', 'arrays', 'error_titles_ARRAY_AS09', 'script', '2013-09-16 14:15:00');
INSERT INTO `as_fields` VALUES('1080', '4', 'error_bodies_ARRAY_AS09', 'arrays', 'error_bodies_ARRAY_AS09', 'script', '2013-11-22 09:45:38');
INSERT INTO `as_fields` VALUES('1081', '4', 'error_bodies_ARRAY_AS10', 'arrays', 'error_bodies_ARRAY_AS10', 'script', '2013-11-22 09:49:41');
INSERT INTO `as_fields` VALUES('1082', '4', 'error_titles_ARRAY_AS10', 'arrays', 'error_titles_ARRAY_AS10', 'script', '2013-09-16 14:15:43');
INSERT INTO `as_fields` VALUES('1083', '4', 'employee_add', 'backend', 'Employees / Add employee', 'script', '2013-09-16 14:20:31');
INSERT INTO `as_fields` VALUES('1084', '4', 'employee_name', 'backend', 'Employees / Employee Name', 'script', '2013-11-22 09:51:34');
INSERT INTO `as_fields` VALUES('1085', '4', 'employee_email', 'backend', 'Employees / Email', 'script', '2013-09-16 14:24:29');
INSERT INTO `as_fields` VALUES('1086', '4', 'employee_phone', 'backend', 'Employees / Phone', 'script', '2013-09-16 14:24:39');
INSERT INTO `as_fields` VALUES('1087', '4', 'employee_services', 'backend', 'Employees / Services', 'script', '2013-09-16 14:27:56');
INSERT INTO `as_fields` VALUES('1088', '4', 'employee_status', 'backend', 'Employees / Status', 'script', '2013-09-16 14:28:41');
INSERT INTO `as_fields` VALUES('1089', '4', 'employee_update', 'backend', 'Employees / Update employee', 'script', '2013-09-16 14:37:06');
INSERT INTO `as_fields` VALUES('1090', '4', 'error_titles_ARRAY_AE09', 'arrays', 'error_titles_ARRAY_AE09', 'script', '2013-09-16 14:39:06');
INSERT INTO `as_fields` VALUES('1091', '4', 'error_titles_ARRAY_AE10', 'arrays', 'error_titles_ARRAY_AE10', 'script', '2013-09-16 14:39:01');
INSERT INTO `as_fields` VALUES('1092', '4', 'error_bodies_ARRAY_AE10', 'arrays', 'error_bodies_ARRAY_AE10', 'script', '2013-11-22 09:53:36');
INSERT INTO `as_fields` VALUES('1093', '4', 'error_bodies_ARRAY_AE09', 'arrays', 'error_bodies_ARRAY_AE09', 'script', '2013-11-22 09:52:17');
INSERT INTO `as_fields` VALUES('1094', '4', 'employee_notes', 'backend', 'Employees / Notes', 'script', '2013-09-16 14:39:25');
INSERT INTO `as_fields` VALUES('1095', '4', 'employee_is_subscribed', 'backend', 'Employees / Send email', 'script', '2013-09-17 06:14:14');
INSERT INTO `as_fields` VALUES('1096', '4', 'employee_password', 'backend', 'Employees / Password', 'script', '2013-09-17 06:17:21');
INSERT INTO `as_fields` VALUES('1098', '4', 'error_bodies_ARRAY_AE01', 'arrays', 'error_bodies_ARRAY_AE01', 'script', '2013-09-17 06:21:08');
INSERT INTO `as_fields` VALUES('1099', '4', 'error_titles_ARRAY_AE01', 'arrays', 'error_titles_ARRAY_AE01', 'script', '2013-09-17 06:21:19');
INSERT INTO `as_fields` VALUES('1100', '4', 'error_titles_ARRAY_AE03', 'arrays', 'error_titles_ARRAY_AE03', 'script', '2013-09-17 06:22:16');
INSERT INTO `as_fields` VALUES('1101', '4', 'error_bodies_ARRAY_AE03', 'arrays', 'error_bodies_ARRAY_AE03', 'script', '2013-09-17 06:22:27');
INSERT INTO `as_fields` VALUES('1102', '4', 'error_bodies_ARRAY_AE08', 'arrays', 'error_bodies_ARRAY_AE08', 'script', '2013-09-17 06:24:07');
INSERT INTO `as_fields` VALUES('1103', '4', 'error_titles_ARRAY_AE08', 'arrays', 'error_titles_ARRAY_AE08', 'script', '2013-09-17 06:24:15');
INSERT INTO `as_fields` VALUES('1104', '4', 'error_titles_ARRAY_AE04', 'arrays', 'error_titles_ARRAY_AE04', 'script', '2013-09-17 06:26:03');
INSERT INTO `as_fields` VALUES('1105', '4', 'error_bodies_ARRAY_AE04', 'arrays', 'error_bodies_ARRAY_AE04', 'script', '2013-09-17 06:26:14');
INSERT INTO `as_fields` VALUES('1106', '4', 'employee_last_login', 'backend', 'Employees / Last login', 'script', '2013-09-17 06:29:10');
INSERT INTO `as_fields` VALUES('1107', '4', 'menuTime', 'backend', 'Menu Working Time', 'script', '2013-09-17 06:50:37');
INSERT INTO `as_fields` VALUES('1108', '4', 'error_titles_ARRAY_AT01', 'arrays', 'error_titles_ARRAY_AT01', 'script', '2013-09-17 07:25:21');
INSERT INTO `as_fields` VALUES('1109', '4', 'error_bodies_ARRAY_AT01', 'arrays', 'error_bodies_ARRAY_AT01', 'script', '2013-09-17 07:25:34');
INSERT INTO `as_fields` VALUES('1110', '4', 'error_titles_ARRAY_AT02', 'arrays', 'error_titles_ARRAY_AT02', 'script', '2013-09-17 07:26:05');
INSERT INTO `as_fields` VALUES('1111', '4', 'error_bodies_ARRAY_AT02', 'arrays', 'error_bodies_ARRAY_AT02', 'script', '2013-09-17 07:26:18');
INSERT INTO `as_fields` VALUES('1112', '4', 'error_titles_ARRAY_AT03', 'arrays', 'error_titles_ARRAY_AT03', 'script', '2013-09-17 07:26:50');
INSERT INTO `as_fields` VALUES('1113', '4', 'error_bodies_ARRAY_AT03', 'arrays', 'error_bodies_ARRAY_AT03', 'script', '2013-09-17 07:26:58');
INSERT INTO `as_fields` VALUES('1114', '4', 'error_titles_ARRAY_AT04', 'arrays', 'error_titles_ARRAY_AT04', 'script', '2013-09-17 07:45:33');
INSERT INTO `as_fields` VALUES('1115', '4', 'error_bodies_ARRAY_AT04', 'arrays', 'error_bodies_ARRAY_AT04', 'script', '2013-12-12 18:48:05');
INSERT INTO `as_fields` VALUES('1116', '4', 'time_update_custom', 'backend', 'Working Time / Update custom', 'script', '2013-09-17 07:47:22');
INSERT INTO `as_fields` VALUES('1117', '4', 'time_default', 'backend', 'Working Time / Default', 'script', '2013-09-17 08:42:14');
INSERT INTO `as_fields` VALUES('1118', '4', 'time_custom', 'backend', 'Working Time / Custom', 'script', '2013-09-17 07:47:57');
INSERT INTO `as_fields` VALUES('1119', '4', 'time_day', 'backend', 'Working Time / Day of week', 'script', '2013-09-17 07:48:09');
INSERT INTO `as_fields` VALUES('1120', '4', 'time_from', 'backend', 'Working Time / Start Time', 'script', '2013-09-17 07:48:22');
INSERT INTO `as_fields` VALUES('1121', '4', 'time_to', 'backend', 'Working Time / End Time', 'script', '2013-09-17 07:48:36');
INSERT INTO `as_fields` VALUES('1122', '4', 'time_is', 'backend', 'Working Time / Is Day off', 'script', '2013-09-17 07:48:49');
INSERT INTO `as_fields` VALUES('1123', '4', 'time_date', 'backend', 'Working Time / Date', 'script', '2013-09-17 07:49:03');
INSERT INTO `as_fields` VALUES('1124', '4', 'employee_general', 'backend', 'Employees / General', 'script', '2013-09-17 08:41:28');
INSERT INTO `as_fields` VALUES('1125', '4', 'time_default_wt', 'backend', 'Working Time / Default Working Time', 'script', '2013-09-17 08:42:43');
INSERT INTO `as_fields` VALUES('1126', '4', 'time_custom_wt', 'backend', 'Working Time / Custom Working Time', 'script', '2013-09-17 08:42:55');
INSERT INTO `as_fields` VALUES('1127', '4', 'time_lunch_from', 'backend', 'Working Time / Lunch from', 'script', '2013-09-17 10:28:54');
INSERT INTO `as_fields` VALUES('1128', '4', 'time_lunch_to', 'backend', 'Working Time / Lunch to', 'script', '2013-09-17 10:29:07');
INSERT INTO `as_fields` VALUES('1129', '4', 'menuInstall', 'backend', 'Menu Install', 'script', '2013-09-18 06:04:31');
INSERT INTO `as_fields` VALUES('1130', '4', 'menuPreview', 'backend', 'Menu Preview', 'script', '2013-09-18 06:04:43');
INSERT INTO `as_fields` VALUES('1131', '4', 'menuBookings', 'backend', 'Menu Bookings', 'script', '2013-09-18 06:05:10');
INSERT INTO `as_fields` VALUES('1132', '4', 'menuGeneral', 'backend', 'Menu General', 'script', '2013-09-18 06:17:39');
INSERT INTO `as_fields` VALUES('1133', '4', 'menuPayments', 'backend', 'Menu Payments', 'script', '2013-09-18 06:18:26');
INSERT INTO `as_fields` VALUES('1134', '4', 'menuBookingForm', 'backend', 'Menu Booking form', 'script', '2013-09-18 06:18:55');
INSERT INTO `as_fields` VALUES('1135', '4', 'menuConfirmation', 'backend', 'Menu Confirmation', 'script', '2013-09-18 06:19:13');
INSERT INTO `as_fields` VALUES('1136', '4', 'menuTerms', 'backend', 'Menu Terms', 'script', '2013-09-18 06:19:23');
INSERT INTO `as_fields` VALUES('1137', '4', 'opt_o_bf_address_1', 'backend', 'Options / Address 1', 'script', '2013-09-18 06:31:09');
INSERT INTO `as_fields` VALUES('1138', '4', 'opt_o_bf_captcha', 'backend', 'Options / Captcha', 'script', '2013-09-18 06:31:32');
INSERT INTO `as_fields` VALUES('1139', '4', 'opt_o_bf_city', 'backend', 'Options / City', 'script', '2013-09-18 06:31:49');
INSERT INTO `as_fields` VALUES('1140', '4', 'opt_o_bf_email', 'backend', 'Options / Email', 'script', '2013-09-18 06:32:09');
INSERT INTO `as_fields` VALUES('1141', '4', 'opt_o_bf_name', 'backend', 'Options / Name', 'script', '2013-09-18 06:32:23');
INSERT INTO `as_fields` VALUES('1142', '4', 'opt_o_bf_notes', 'backend', 'Options / Notes', 'script', '2013-09-18 06:32:45');
INSERT INTO `as_fields` VALUES('1143', '4', 'opt_o_bf_phone', 'backend', 'Options / Phone ', 'script', '2013-09-18 06:33:03');
INSERT INTO `as_fields` VALUES('1144', '4', 'opt_o_bf_state', 'backend', 'Options / State', 'script', '2013-09-18 06:33:24');
INSERT INTO `as_fields` VALUES('1145', '4', 'opt_o_bf_terms', 'backend', 'Options / Terms', 'script', '2013-09-18 06:33:47');
INSERT INTO `as_fields` VALUES('1146', '4', 'opt_o_bf_zip', 'backend', 'Options / Zip', 'script', '2013-09-18 06:34:16');
INSERT INTO `as_fields` VALUES('1147', '4', 'opt_o_bf_country', 'backend', 'Options / Country', 'script', '2013-09-18 06:34:36');
INSERT INTO `as_fields` VALUES('1148', '4', 'opt_o_paypal_address', 'backend', 'Options / Paypal address', 'script', '2013-09-18 06:35:08');
INSERT INTO `as_fields` VALUES('1149', '4', 'opt_o_accept_bookings', 'backend', 'Options / Accept Bookings', 'script', '2013-09-18 06:35:32');
INSERT INTO `as_fields` VALUES('1150', '4', 'opt_o_allow_authorize', 'backend', 'Options / Allow Authorize.net', 'script', '2013-09-18 06:36:05');
INSERT INTO `as_fields` VALUES('1151', '4', 'opt_o_allow_bank', 'backend', 'Options / Allow Bank', 'script', '2013-11-22 10:06:31');
INSERT INTO `as_fields` VALUES('1152', '4', 'opt_o_allow_creditcard', 'backend', 'Options / Allow Credit Card', 'script', '2013-11-22 10:06:17');
INSERT INTO `as_fields` VALUES('1153', '4', 'opt_o_allow_paypal', 'backend', 'Options / Allow Paypal', 'script', '2013-09-18 06:37:10');
INSERT INTO `as_fields` VALUES('1154', '4', 'opt_o_authorize_key', 'backend', 'Options / Authorize.net transaction key', 'script', '2013-09-18 06:37:43');
INSERT INTO `as_fields` VALUES('1155', '4', 'opt_o_authorize_mid', 'backend', 'Options / Authorize.net merchant ID', 'script', '2013-09-18 06:38:01');
INSERT INTO `as_fields` VALUES('1156', '4', 'opt_o_bank_account', 'backend', 'Options / Bank account', 'script', '2013-09-18 06:38:18');
INSERT INTO `as_fields` VALUES('1157', '4', 'opt_o_deposit', 'backend', 'Options / Deposit', 'script', '2013-11-22 10:08:04');
INSERT INTO `as_fields` VALUES('1158', '4', 'opt_o_disable_payments', 'backend', 'Options / Disable payments', 'script', '2013-11-22 10:07:10');
INSERT INTO `as_fields` VALUES('1162', '4', 'opt_o_status_if_not_paid', 'backend', 'Options / Default status for booked dates if not paid', 'script', '2013-09-18 06:40:14');
INSERT INTO `as_fields` VALUES('1163', '4', 'opt_o_status_if_paid', 'backend', 'Options / Default status for booked dates if paid', 'script', '2013-09-18 06:40:27');
INSERT INTO `as_fields` VALUES('1164', '4', 'opt_o_tax', 'backend', 'Options / Tax payment', 'script', '2013-11-22 10:09:27');
INSERT INTO `as_fields` VALUES('1165', '4', 'opt_o_thankyou_page', 'backend', 'Options / "Thank you" page location', 'script', '2013-11-22 10:06:53');
INSERT INTO `as_fields` VALUES('1166', '4', 'opt_o_authorize_tz', 'backend', 'Options / Authorize.net Time zone', 'script', '2013-09-18 06:41:23');
INSERT INTO `as_fields` VALUES('1167', '4', 'opt_o_email_new_reservation', 'backend', 'Options / New booking received', 'script', '2013-09-18 06:41:40');
INSERT INTO `as_fields` VALUES('1168', '4', 'opt_o_email_reservation_cancelled', 'backend', 'Options / Booking cancelled', 'script', '2013-09-18 06:41:55');
INSERT INTO `as_fields` VALUES('1169', '4', 'opt_o_email_password_reminder', 'backend', 'Notifications / Password reminder', 'script', '2013-09-18 06:42:08');
INSERT INTO `as_fields` VALUES('1170', '4', 'opt_o_bf_address_2', 'backend', 'Options / Address 2', 'script', '2013-09-18 06:42:22');
INSERT INTO `as_fields` VALUES('1171', '4', 'opt_o_datetime_format', 'backend', 'Options / Datetime format', 'script', '2013-09-18 06:42:36');
INSERT INTO `as_fields` VALUES('1172', '4', 'opt_o_authorize_hash', 'backend', 'Options / Authorize.net hash value', 'script', '2013-09-18 06:42:51');
INSERT INTO `as_fields` VALUES('1173', '4', 'confirmation_subject', 'backend', 'Confirmation / Email subject', 'script', '2013-09-18 06:51:31');
INSERT INTO `as_fields` VALUES('1174', '4', 'confirmation_body', 'backend', 'Confirmation / Email body', 'script', '2013-09-18 06:51:47');
INSERT INTO `as_fields` VALUES('1175', '4', 'confirmation_client_confirmation', 'backend', 'Confirmation / Client confirmation title', 'script', '2013-09-18 06:51:59');
INSERT INTO `as_fields` VALUES('1176', '4', 'confirmation_client_payment', 'backend', 'Confirmation / Client payment title', 'script', '2013-09-18 06:52:11');
INSERT INTO `as_fields` VALUES('1177', '4', 'confirmation_admin_confirmation', 'backend', 'Confirmation / Admin confirmation title', 'script', '2013-09-18 06:52:25');
INSERT INTO `as_fields` VALUES('1178', '4', 'confirmation_admin_payment', 'backend', 'Confirmation / Admin payment title', 'script', '2013-09-18 06:52:36');
INSERT INTO `as_fields` VALUES('1179', '4', 'lblOptionsTermsURL', 'backend', 'Options / Booking terms URL', 'script', '2013-09-18 06:53:52');
INSERT INTO `as_fields` VALUES('1180', '4', 'lblOptionsTermsContent', 'backend', 'Options / Booking terms content', 'script', '2013-09-18 06:54:03');
INSERT INTO `as_fields` VALUES('1181', '4', 'booking_add', 'backend', 'Bookings / Add booking', 'script', '2013-09-18 06:57:23');
INSERT INTO `as_fields` VALUES('1182', '4', 'booking_statuses_ARRAY_confirmed', 'arrays', 'Bookings / Status: confirmed', 'script', '2013-09-18 06:58:11');
INSERT INTO `as_fields` VALUES('1183', '4', 'booking_statuses_ARRAY_pending', 'arrays', 'Bookings / Status: pending', 'script', '2013-09-18 06:58:27');
INSERT INTO `as_fields` VALUES('1184', '4', 'booking_statuses_ARRAY_cancelled', 'arrays', 'Bookings / Status: cancelled', 'script', '2013-09-18 06:58:41');
INSERT INTO `as_fields` VALUES('1185', '4', 'booking_uuid', 'backend', 'Bookings / Unique ID', 'script', '2013-09-18 06:59:00');
INSERT INTO `as_fields` VALUES('1186', '4', 'booking_status', 'backend', 'Bookings / Status', 'script', '2013-09-18 06:59:25');
INSERT INTO `as_fields` VALUES('1187', '4', 'booking_update', 'backend', 'Bookings / Update booking', 'script', '2013-09-18 06:59:38');
INSERT INTO `as_fields` VALUES('1315', '4', 'booking_cc_exp', 'backend', 'Bookings / CC Exp.date', 'script', '2013-09-18 07:10:17');
INSERT INTO `as_fields` VALUES('1316', '4', 'booking_cc_code', 'backend', 'Bookings / CC Code', 'script', '2013-09-18 07:10:32');
INSERT INTO `as_fields` VALUES('1317', '4', 'booking_cc_num', 'backend', 'Bookings / CC Number', 'script', '2013-09-18 07:10:47');
INSERT INTO `as_fields` VALUES('1318', '4', 'booking_cc_type', 'backend', 'Bookings / CC Type', 'script', '2013-09-18 07:11:00');
INSERT INTO `as_fields` VALUES('1319', '4', 'booking_cc_types_ARRAY_maestro', 'arrays', 'booking_cc_types_ARRAY_maestro', 'script', '2013-09-18 07:11:18');
INSERT INTO `as_fields` VALUES('1320', '4', 'booking_cc_types_ARRAY_amex', 'arrays', 'booking_cc_types_ARRAY_amex', 'script', '2013-09-18 07:11:33');
INSERT INTO `as_fields` VALUES('1321', '4', 'booking_cc_types_ARRAY_mastercard', 'arrays', 'booking_cc_types_ARRAY_mastercard', 'script', '2013-09-18 07:17:32');
INSERT INTO `as_fields` VALUES('1322', '4', 'booking_cc_types_ARRAY_visa', 'arrays', 'booking_cc_types_ARRAY_visa', 'script', '2013-09-18 07:17:48');
INSERT INTO `as_fields` VALUES('1324', '4', 'booking_phone', 'backend', 'Bookings / Phone', 'script', '2013-09-18 07:18:13');
INSERT INTO `as_fields` VALUES('1325', '4', 'booking_email', 'backend', 'Bookings / Email', 'script', '2013-09-18 07:18:22');
INSERT INTO `as_fields` VALUES('1326', '4', 'booking_invoice_details', 'backend', 'Bookings / Invoice details', 'script', '2013-09-18 07:18:34');
INSERT INTO `as_fields` VALUES('1327', '4', 'booking_create_invoice', 'backend', 'Bookings / Create Invoice', 'script', '2013-09-18 07:35:27');
INSERT INTO `as_fields` VALUES('1328', '4', 'booking_customer', 'backend', 'Bookings / Customer details', 'script', '2013-09-18 07:35:44');
INSERT INTO `as_fields` VALUES('1329', '4', 'booking_notes', 'backend', 'Bookings / Notes', 'script', '2013-09-18 07:35:58');
INSERT INTO `as_fields` VALUES('1330', '4', 'booking_address_2', 'backend', 'Bookings / Address Line 2', 'script', '2013-09-18 07:36:12');
INSERT INTO `as_fields` VALUES('1331', '4', 'booking_address_1', 'backend', 'Bookings / Address Line 1', 'script', '2013-09-18 07:36:27');
INSERT INTO `as_fields` VALUES('1332', '4', 'booking_name', 'backend', 'Bookings / Name', 'script', '2013-09-18 07:36:41');
INSERT INTO `as_fields` VALUES('1333', '4', 'booking_zip', 'backend', 'Bookings / Zip', 'script', '2013-09-18 07:36:53');
INSERT INTO `as_fields` VALUES('1334', '4', 'booking_city', 'backend', 'Bookings / City', 'script', '2013-09-18 07:37:06');
INSERT INTO `as_fields` VALUES('1335', '4', 'booking_state', 'backend', 'Bookings / State', 'script', '2013-09-18 07:37:22');
INSERT INTO `as_fields` VALUES('1336', '4', 'booking_country', 'backend', 'Bookings / Country', 'script', '2013-09-18 07:37:34');
INSERT INTO `as_fields` VALUES('1337', '4', 'booking_tab_client', 'backend', 'Bookings / Client', 'script', '2013-09-18 07:37:47');
INSERT INTO `as_fields` VALUES('1338', '4', 'booking_tab_details', 'backend', 'Bookings / Booking', 'script', '2013-09-18 07:37:59');
INSERT INTO `as_fields` VALUES('1339', '4', 'booking_general', 'backend', 'Bookings / Details', 'script', '2013-09-18 07:38:11');
INSERT INTO `as_fields` VALUES('1340', '4', 'booking_choose', 'backend', 'Bookings / Choose', 'script', '2013-09-18 07:38:22');
INSERT INTO `as_fields` VALUES('1341', '4', 'booking_payment_method', 'backend', 'Bookings / Payment method', 'script', '2013-09-18 07:38:33');
INSERT INTO `as_fields` VALUES('1342', '4', 'booking_price', 'backend', 'Bookings / Price', 'script', '2013-09-18 07:38:44');
INSERT INTO `as_fields` VALUES('1344', '4', 'booking_tax', 'backend', 'Bookings / Tax', 'script', '2013-09-18 07:39:07');
INSERT INTO `as_fields` VALUES('1345', '4', 'booking_deposit', 'backend', 'Bookings / Deposit', 'script', '2013-09-18 07:47:15');
INSERT INTO `as_fields` VALUES('1346', '4', 'booking_total', 'backend', 'Bookings / Total', 'script', '2013-09-18 07:47:27');
INSERT INTO `as_fields` VALUES('1347', '4', 'booking_created', 'backend', 'Bookings / Created', 'script', '2013-09-18 07:47:38');
INSERT INTO `as_fields` VALUES('1348', '4', 'payment_methods_ARRAY_authorize', 'arrays', 'payment_methods_ARRAY_authorize', 'script', '2013-09-18 07:55:03');
INSERT INTO `as_fields` VALUES('1349', '4', 'payment_methods_ARRAY_bank', 'arrays', 'payment_methods_ARRAY_bank', 'script', '2013-09-18 07:55:22');
INSERT INTO `as_fields` VALUES('1350', '4', 'payment_methods_ARRAY_creditcard', 'arrays', 'payment_methods_ARRAY_creditcard', 'script', '2013-09-18 07:55:44');
INSERT INTO `as_fields` VALUES('1351', '4', 'payment_methods_ARRAY_paypal', 'arrays', 'payment_methods_ARRAY_paypal', 'script', '2013-09-18 07:55:57');
INSERT INTO `as_fields` VALUES('1352', '4', 'error_titles_ARRAY_AO24', 'arrays', 'error_titles_ARRAY_AO24', 'script', '2013-09-18 08:44:03');
INSERT INTO `as_fields` VALUES('1353', '4', 'error_titles_ARRAY_AO25', 'arrays', 'error_titles_ARRAY_AO25', 'script', '2013-09-18 08:44:26');
INSERT INTO `as_fields` VALUES('1354', '4', 'error_titles_ARRAY_AO26', 'arrays', 'error_titles_ARRAY_AO26', 'script', '2013-09-18 08:44:51');
INSERT INTO `as_fields` VALUES('1355', '4', 'error_bodies_ARRAY_AO24', 'arrays', 'error_bodies_ARRAY_AO24', 'script', '2013-09-18 08:45:18');
INSERT INTO `as_fields` VALUES('1356', '4', 'error_bodies_ARRAY_AO25', 'arrays', 'error_bodies_ARRAY_AO25', 'script', '2013-12-12 19:55:12');
INSERT INTO `as_fields` VALUES('1357', '4', 'error_bodies_ARRAY_AO26', 'arrays', 'error_bodies_ARRAY_AO26', 'script', '2013-09-18 08:45:51');
INSERT INTO `as_fields` VALUES('1358', '4', 'error_bodies_ARRAY_AO27', 'arrays', 'error_bodies_ARRAY_AO27', 'script', '2013-11-22 10:10:04');
INSERT INTO `as_fields` VALUES('1359', '4', 'error_titles_ARRAY_AO27', 'arrays', 'error_titles_ARRAY_AO27', 'script', '2013-09-18 08:47:26');
INSERT INTO `as_fields` VALUES('1360', '4', 'error_bodies_ARRAY_AO23', 'arrays', 'error_bodies_ARRAY_AO23', 'script', '2013-11-22 10:05:23');
INSERT INTO `as_fields` VALUES('1361', '4', 'error_titles_ARRAY_AO23', 'arrays', 'error_titles_ARRAY_AO23', 'script', '2013-09-18 08:48:23');
INSERT INTO `as_fields` VALUES('1362', '4', 'error_bodies_ARRAY_AO21', 'arrays', 'error_bodies_ARRAY_AO21', 'script', '2013-09-18 08:48:36');
INSERT INTO `as_fields` VALUES('1363', '4', 'error_titles_ARRAY_AO21', 'arrays', 'error_titles_ARRAY_AO21', 'script', '2013-09-18 08:48:47');
INSERT INTO `as_fields` VALUES('1364', '4', 'opt_o_hide_prices', 'backend', 'Options / Hide prices', 'script', '2013-09-18 08:53:41');
INSERT INTO `as_fields` VALUES('1365', '4', 'opt_o_step', 'backend', 'Options / Step', 'script', '2013-09-18 08:54:04');
INSERT INTO `as_fields` VALUES('1366', '4', 'booking_services', 'backend', 'Bookings / Services', 'script', '2013-09-18 09:04:47');
INSERT INTO `as_fields` VALUES('1367', '4', 'employee_avatar', 'backend', 'Employees / Picture', 'script', '2013-09-18 10:22:36');
INSERT INTO `as_fields` VALUES('1368', '4', 'employee_avatar_delete', 'backend', 'Employees / Delete picture', 'script', '2013-09-18 11:15:12');
INSERT INTO `as_fields` VALUES('1369', '4', 'employee_avatar_dtitle', 'backend', 'Employees / Delete confirmation', 'script', '2013-09-18 11:23:07');
INSERT INTO `as_fields` VALUES('1370', '4', 'employee_avatar_dbody', 'backend', 'Employees / Delete content', 'script', '2013-09-18 11:23:46');
INSERT INTO `as_fields` VALUES('1371', '4', 'lblInstallJs1_title', 'backend', 'Install / Title', 'script', '2013-09-18 13:30:03');
INSERT INTO `as_fields` VALUES('1372', '4', 'lblInstallJs1_body', 'backend', 'Install / Body', 'script', '2013-09-18 13:30:14');
INSERT INTO `as_fields` VALUES('1373', '4', 'lblInstallJs1_1', 'backend', 'Install / Step 1', 'script', '2013-09-18 13:30:26');
INSERT INTO `as_fields` VALUES('1374', '4', 'lblInstallJs1_2', 'backend', 'Install / Step 2', 'script', '2013-09-18 13:30:37');
INSERT INTO `as_fields` VALUES('1375', '4', 'lblInstallJs1_3', 'backend', 'Install / Step 3', 'script', '2013-09-18 13:30:48');
INSERT INTO `as_fields` VALUES('1376', '4', 'opt_o_seo_url', 'backend', 'Options / Seo URLs', 'script', '2013-09-19 09:30:09');
INSERT INTO `as_fields` VALUES('1377', '4', 'opt_o_time_format', 'backend', 'Options / Time format', 'script', '2013-09-20 07:37:12');
INSERT INTO `as_fields` VALUES('1378', '4', 'opt_o_week_numbers', 'backend', 'Options / Show week numbers', 'script', '2013-09-20 09:29:59');
INSERT INTO `as_fields` VALUES('1379', '4', 'co_captcha', 'frontend', 'Checkout / Captcha', 'script', '2013-10-03 12:41:20');
INSERT INTO `as_fields` VALUES('1380', '4', 'co_select_country', 'frontend', 'Checkout / Select Country', 'script', '2013-10-03 12:41:52');
INSERT INTO `as_fields` VALUES('1381', '4', 'co_terms', 'frontend', 'Checkout / Terms', 'script', '2013-10-03 12:42:05');
INSERT INTO `as_fields` VALUES('1382', '4', 'co_empty_notice', 'frontend', 'Checkout / Empty notice', 'script', '2013-10-03 12:42:40');
INSERT INTO `as_fields` VALUES('1383', '4', 'front_select_payment', 'frontend', 'Frontend / Select Payment method', 'script', '2013-10-03 12:43:12');
INSERT INTO `as_fields` VALUES('1384', '4', 'front_select_cc_type', 'frontend', 'Bookings / Select CC Type', 'script', '2013-10-03 12:44:50');
INSERT INTO `as_fields` VALUES('1385', '4', 'booking_employee', 'backend', 'Bookings / Employee', 'script', '2013-10-04 13:04:17');
INSERT INTO `as_fields` VALUES('1386', '4', 'booking_service', 'backend', 'Bookings / Service', 'script', '2013-10-04 13:04:28');
INSERT INTO `as_fields` VALUES('1387', '4', 'booking_from', 'backend', 'Bookings / Date from', 'script', '2013-10-04 13:04:43');
INSERT INTO `as_fields` VALUES('1388', '4', 'booking_to', 'backend', 'Bookings / Date to', 'script', '2013-10-04 13:04:51');
INSERT INTO `as_fields` VALUES('1389', '4', 'booking_query', 'backend', 'Bookings / Query', 'script', '2013-10-04 13:22:00');
INSERT INTO `as_fields` VALUES('1390', '4', 'booking_date', 'backend', 'Bookings / Date', 'script', '2013-10-04 13:57:28');
INSERT INTO `as_fields` VALUES('1391', '4', 'booking_export', 'backend', 'Bookings / Export', 'script', '2013-10-07 06:19:11');
INSERT INTO `as_fields` VALUES('1392', '4', 'booking_delimiter_ARRAY_comma', 'arrays', 'booking_delimiter_ARRAY_comma', 'script', '2013-10-07 07:13:45');
INSERT INTO `as_fields` VALUES('1393', '4', 'booking_delimiter_ARRAY_semicolon', 'arrays', 'booking_delimiter_ARRAY_semicolon', 'script', '2013-10-07 07:13:59');
INSERT INTO `as_fields` VALUES('1394', '4', 'booking_delimiter_ARRAY_tab', 'arrays', 'booking_delimiter_ARRAY_tab', 'script', '2013-10-07 07:14:09');
INSERT INTO `as_fields` VALUES('1395', '4', 'booking_format_ARRAY_csv', 'arrays', 'booking_delimiter_ARRAY_csv', 'script', '2013-10-07 07:15:01');
INSERT INTO `as_fields` VALUES('1396', '4', 'booking_format_ARRAY_xml', 'arrays', 'booking_delimiter_ARRAY_xml', 'script', '2013-10-07 07:15:19');
INSERT INTO `as_fields` VALUES('1397', '4', 'booking_format_ARRAY_ical', 'arrays', 'booking_delimiter_ARRAY_ical', 'script', '2013-10-07 07:15:31');
INSERT INTO `as_fields` VALUES('1398', '4', 'booking_delimiter_lbl', 'backend', 'Bookings / Delimiter', 'script', '2013-10-07 07:18:41');
INSERT INTO `as_fields` VALUES('1399', '4', 'booking_format_lbl', 'backend', 'Bookings / Format', 'script', '2013-10-07 07:18:54');
INSERT INTO `as_fields` VALUES('1400', '4', 'booking_na', 'backend', 'Bookings / Not available', 'script', '2013-10-07 09:33:57');
INSERT INTO `as_fields` VALUES('1401', '4', 'booking_export_title', 'backend', 'Bookings / Export bookings', 'script', '2013-10-07 09:35:09');
INSERT INTO `as_fields` VALUES('1402', '4', 'booking_dt', 'backend', 'Bookings / Date Time', 'script', '2013-10-07 09:48:12');
INSERT INTO `as_fields` VALUES('1403', '4', 'booking_service_employee', 'backend', 'Bookings / Service/Employee', 'script', '2013-10-07 09:48:47');
INSERT INTO `as_fields` VALUES('1404', '4', 'menuReminder', 'backend', 'Menu Reminder', 'script', '2013-10-07 10:02:28');
INSERT INTO `as_fields` VALUES('1405', '4', 'opt_o_reminder_enable', 'backend', 'Options / Enable notifications', 'script', '2013-12-12 19:56:41');
INSERT INTO `as_fields` VALUES('1406', '4', 'opt_o_reminder_email_before', 'backend', 'Options / Send email reminder', 'script', '2013-12-12 19:58:20');
INSERT INTO `as_fields` VALUES('1407', '4', 'opt_o_reminder_subject', 'backend', 'Options / Email Reminder subject', 'script', '2013-10-07 10:05:19');
INSERT INTO `as_fields` VALUES('1408', '4', 'opt_o_reminder_sms_hours', 'backend', 'Options / Send SMS reminder', 'script', '2013-12-12 20:00:13');
INSERT INTO `as_fields` VALUES('1409', '4', 'opt_o_reminder_sms_country_code', 'backend', 'Options / SMS country code', 'script', '2013-12-12 20:00:13');
INSERT INTO `as_fields` VALUES('1410', '4', 'opt_o_reminder_sms_message', 'backend', 'Options / SMS message', 'script', '2013-12-12 20:00:53');
INSERT INTO `as_fields` VALUES('1411', '4', 'opt_o_reminder_body', 'backend', 'Options / Email Reminder body', 'script', '2013-12-12 19:58:37');
INSERT INTO `as_fields` VALUES('1412', '4', 'get_key', 'backend', 'Options / Get key', 'script', '2013-10-07 10:42:43');
INSERT INTO `as_fields` VALUES('1413', '4', 'error_bodies_ARRAY_AO28', 'arrays', 'error_bodies_ARRAY_AO28', 'script', '2013-12-12 20:00:42');
INSERT INTO `as_fields` VALUES('1414', '4', 'error_titles_ARRAY_AO28', 'arrays', 'error_titles_ARRAY_AO28', 'script', '2013-10-07 10:44:33');
INSERT INTO `as_fields` VALUES('1415', '4', 'error_titles_ARRAY_ABK01', 'arrays', 'error_titles_ARRAY_ABK01', 'script', '2013-10-07 10:48:09');
INSERT INTO `as_fields` VALUES('1416', '4', 'error_bodies_ARRAY_ABK01', 'arrays', 'error_bodies_ARRAY_ABK01', 'script', '2013-10-07 10:48:33');
INSERT INTO `as_fields` VALUES('1417', '4', 'error_titles_ARRAY_ABK08', 'arrays', 'error_titles_ARRAY_ABK08', 'script', '2013-10-07 10:49:10');
INSERT INTO `as_fields` VALUES('1418', '4', 'error_bodies_ARRAY_ABK08', 'arrays', 'error_bodies_ARRAY_ABK08', 'script', '2013-10-07 10:50:05');
INSERT INTO `as_fields` VALUES('1419', '4', 'error_titles_ARRAY_ABK03', 'arrays', 'error_titles_ARRAY_ABK03', 'script', '2013-10-07 10:50:42');
INSERT INTO `as_fields` VALUES('1420', '4', 'error_bodies_ARRAY_ABK03', 'arrays', 'error_bodies_ARRAY_ABK03', 'script', '2013-10-07 10:51:08');
INSERT INTO `as_fields` VALUES('1421', '4', 'error_titles_ARRAY_ABK04', 'arrays', 'error_titles_ARRAY_ABK04', 'script', '2013-10-07 10:51:24');
INSERT INTO `as_fields` VALUES('1422', '4', 'error_bodies_ARRAY_ABK04', 'arrays', 'error_bodies_ARRAY_ABK04', 'script', '2013-10-07 10:51:40');
INSERT INTO `as_fields` VALUES('1423', '4', 'error_bodies_ARRAY_ABK10', 'arrays', 'error_bodies_ARRAY_ABK10', 'script', '2013-11-22 09:43:28');
INSERT INTO `as_fields` VALUES('1424', '4', 'error_titles_ARRAY_ABK10', 'arrays', 'error_titles_ARRAY_ABK10', 'script', '2013-10-07 10:52:49');
INSERT INTO `as_fields` VALUES('1425', '4', 'error_titles_ARRAY_ABK11', 'arrays', 'error_titles_ARRAY_ABK11', 'script', '2013-10-07 10:53:28');
INSERT INTO `as_fields` VALUES('1426', '4', 'error_bodies_ARRAY_ABK11', 'arrays', 'error_bodies_ARRAY_ABK11', 'script', '2013-11-22 09:44:07');
INSERT INTO `as_fields` VALUES('1427', '4', 'error_bodies_ARRAY_ABK13', 'arrays', 'error_bodies_ARRAY_ABK13', 'script', '2013-10-07 10:54:44');
INSERT INTO `as_fields` VALUES('1428', '4', 'error_titles_ARRAY_ABK13', 'arrays', 'error_titles_ARRAY_ABK13', 'script', '2013-10-07 10:54:38');
INSERT INTO `as_fields` VALUES('1429', '4', 'error_bodies_ARRAY_ABK12', 'arrays', 'error_bodies_ARRAY_ABK12', 'script', '2013-10-07 10:55:06');
INSERT INTO `as_fields` VALUES('1430', '4', 'error_titles_ARRAY_ABK12', 'arrays', 'error_titles_ARRAY_ABK12', 'script', '2013-10-07 10:55:13');
INSERT INTO `as_fields` VALUES('1431', '4', 'error_bodies_ARRAY_AO08', 'arrays', 'error_bodies_ARRAY_AO08', 'script', '2013-10-07 11:41:38');
INSERT INTO `as_fields` VALUES('1432', '4', 'error_titles_ARRAY_AO08', 'arrays', 'error_titles_ARRAY_AO08', 'script', '2013-10-07 11:41:48');
INSERT INTO `as_fields` VALUES('1433', '4', 'error_bodies_ARRAY_AO06', 'arrays', 'error_bodies_ARRAY_AO06', 'script', '2013-10-07 11:42:11');
INSERT INTO `as_fields` VALUES('1434', '4', 'error_titles_ARRAY_AO06', 'arrays', 'error_titles_ARRAY_AO06', 'script', '2013-10-07 11:42:21');
INSERT INTO `as_fields` VALUES('1435', '4', 'error_titles_ARRAY_AO05', 'arrays', 'error_titles_ARRAY_AO05', 'script', '2013-10-07 11:42:43');
INSERT INTO `as_fields` VALUES('1436', '4', 'error_bodies_ARRAY_AO05', 'arrays', 'error_bodies_ARRAY_AO05', 'script', '2013-10-07 11:42:58');
INSERT INTO `as_fields` VALUES('1437', '4', 'error_bodies_ARRAY_AO04', 'arrays', 'error_bodies_ARRAY_AO04', 'script', '2013-10-07 11:43:43');
INSERT INTO `as_fields` VALUES('1438', '4', 'error_titles_ARRAY_AO04', 'arrays', 'error_titles_ARRAY_AO04', 'script', '2013-10-07 11:43:53');
INSERT INTO `as_fields` VALUES('1439', '4', 'error_titles_ARRAY_AO07', 'arrays', 'error_titles_ARRAY_AO07', 'script', '2013-10-07 11:44:24');
INSERT INTO `as_fields` VALUES('1440', '4', 'error_bodies_ARRAY_AO07', 'arrays', 'error_bodies_ARRAY_AO07', 'script', '2013-10-07 11:44:37');
INSERT INTO `as_fields` VALUES('1441', '4', 'error_bodies_ARRAY_AO03', 'arrays', 'error_bodies_ARRAY_AO03', 'script', '2013-10-07 11:45:45');
INSERT INTO `as_fields` VALUES('1442', '4', 'error_titles_ARRAY_AO03', 'arrays', 'error_titles_ARRAY_AO03', 'script', '2013-10-07 11:45:39');
INSERT INTO `as_fields` VALUES('1454', '4', 'booking_recalc', 'backend', 'Bookings / Recalculate the price', 'script', '2013-10-07 14:34:20');
INSERT INTO `as_fields` VALUES('1455', '4', 'booking_service_add', 'backend', 'Bookings / Add service', 'script', '2013-10-07 14:35:29');
INSERT INTO `as_fields` VALUES('1456', '4', 'booking_service_add_title', 'backend', 'Bookings / Add service (title)', 'script', '2013-10-07 14:36:58');
INSERT INTO `as_fields` VALUES('1457', '4', 'booking_service_delete_title', 'backend', 'Bookings / Remove service (title)', 'script', '2013-10-07 14:38:39');
INSERT INTO `as_fields` VALUES('1458', '4', 'booking_service_delete_body', 'backend', 'Bookings / Remove service (body)', 'script', '2013-10-07 14:38:28');
INSERT INTO `as_fields` VALUES('1459', '4', 'front_cart_empty', 'frontend', 'Frontend / Cart is empty', 'script', '2013-10-07 15:10:19');
INSERT INTO `as_fields` VALUES('1460', '4', 'front_cart_total', 'frontend', 'Frontend / Total', 'script', '2013-10-07 15:10:13');
INSERT INTO `as_fields` VALUES('1461', '4', 'front_selected_services', 'frontend', 'Frontend / Selected Services', 'script', '2013-10-07 15:10:07');
INSERT INTO `as_fields` VALUES('1462', '4', 'front_select_date', 'frontend', 'Frontend / Select a Date', 'script', '2013-10-07 15:10:01');
INSERT INTO `as_fields` VALUES('1463', '4', 'front_make_appointment', 'frontend', 'Frontend / Make an Appointment', 'script', '2013-10-07 15:09:57');
INSERT INTO `as_fields` VALUES('1464', '4', 'front_start_time', 'frontend', 'Frontend / Start time', 'script', '2013-10-07 15:09:53');
INSERT INTO `as_fields` VALUES('1465', '4', 'front_end_time', 'frontend', 'Frontend / End time', 'script', '2013-10-07 15:10:54');
INSERT INTO `as_fields` VALUES('1466', '4', 'front_select_services', 'frontend', 'Frontend / Select Services', 'script', '2013-10-07 15:11:52');
INSERT INTO `as_fields` VALUES('1467', '4', 'front_availability', 'frontend', 'Frontend / Availability', 'script', '2013-10-07 15:12:16');
INSERT INTO `as_fields` VALUES('1468', '4', 'front_booking_form', 'frontend', 'Frontend / Booking Form', 'script', '2013-10-07 15:12:40');
INSERT INTO `as_fields` VALUES('1469', '4', 'front_system_msg', 'frontend', 'Frontend / System message', 'script', '2013-10-07 15:13:54');
INSERT INTO `as_fields` VALUES('1470', '4', 'front_checkout_na', 'frontend', 'Frontend / Checkout form not available', 'script', '2013-10-07 15:14:17');
INSERT INTO `as_fields` VALUES('1471', '4', 'front_return_back', 'frontend', 'Frontend / Return back', 'script', '2013-10-07 15:14:33');
INSERT INTO `as_fields` VALUES('1472', '4', 'front_preview_form', 'frontend', 'Frontend / Booking Preview', 'script', '2013-10-07 15:15:17');
INSERT INTO `as_fields` VALUES('1473', '4', 'front_confirm_booking', 'frontend', 'Frontend / Confirm booking', 'script', '2013-10-07 15:16:08');
INSERT INTO `as_fields` VALUES('1474', '4', 'front_preview_na', 'frontend', 'Frontend / Preview not available', 'script', '2013-10-07 15:16:58');
INSERT INTO `as_fields` VALUES('1475', '4', 'error_titles_ARRAY_AA14', 'arrays', 'error_titles_ARRAY_AA14', 'script', '2013-10-09 09:00:13');
INSERT INTO `as_fields` VALUES('1476', '4', 'error_bodies_ARRAY_AA14', 'arrays', 'error_bodies_ARRAY_AA14', 'script', '2013-10-09 09:00:47');
INSERT INTO `as_fields` VALUES('1477', '4', 'error_titles_ARRAY_AA15', 'arrays', 'error_titles_ARRAY_AA15', 'script', '2013-10-09 09:01:59');
INSERT INTO `as_fields` VALUES('1478', '4', 'error_bodies_ARRAY_AA15', 'arrays', 'error_bodies_ARRAY_AA15', 'script', '2013-10-09 09:02:18');
INSERT INTO `as_fields` VALUES('1479', '4', 'booking_ip', 'backend', 'Bookings / IP address', 'script', '2013-10-09 12:04:45');
INSERT INTO `as_fields` VALUES('1480', '4', 'booking_view_title', 'backend', 'Bookings / Booking Service details', 'script', '2013-10-09 12:11:48');
INSERT INTO `as_fields` VALUES('1481', '4', 'booking_service_email_title', 'backend', 'Bookings / Resend email (title)', 'script', '2013-10-09 12:51:52');
INSERT INTO `as_fields` VALUES('1482', '4', 'booking_service_sms_title', 'backend', 'Bookings / Resend SMS (title)', 'script', '2013-10-09 12:52:05');
INSERT INTO `as_fields` VALUES('1483', '4', 'booking_subject', 'backend', 'Bookings / Subject', 'script', '2013-10-09 13:36:00');
INSERT INTO `as_fields` VALUES('1484', '4', 'booking_message', 'backend', 'Bookings / Message', 'script', '2013-10-09 13:36:13');
INSERT INTO `as_fields` VALUES('1485', '4', 'menuReports', 'backend', 'Menu Reports', 'script', '2013-10-09 14:30:39');
INSERT INTO `as_fields` VALUES('1486', '4', 'report_menu_employees', 'backend', 'Reports / Employees menu', 'script', '2013-10-09 14:37:45');
INSERT INTO `as_fields` VALUES('1487', '4', 'report_menu_services', 'backend', 'Reports / Services menu', 'script', '2013-10-09 14:37:57');
INSERT INTO `as_fields` VALUES('1488', '4', 'report_bookings', 'backend', 'Reports / Bookings', 'script', '2013-10-09 14:50:10');
INSERT INTO `as_fields` VALUES('1489', '4', 'report_total_bookings', 'backend', 'Reports / Total bookings', 'script', '2013-10-10 06:21:58');
INSERT INTO `as_fields` VALUES('1490', '4', 'report_confirmed_bookings', 'backend', 'Reports / Confirmed bookings', 'script', '2013-10-10 06:22:19');
INSERT INTO `as_fields` VALUES('1491', '4', 'report_pending_bookings', 'backend', 'Reports / Pending bookings', 'script', '2013-10-10 06:22:35');
INSERT INTO `as_fields` VALUES('1492', '4', 'report_cancelled_bookings', 'backend', 'Reports / Cancelled bookings', 'script', '2013-10-10 06:22:48');
INSERT INTO `as_fields` VALUES('1493', '4', 'report_total_amount', 'backend', 'Reports / Total amount', 'script', '2013-10-10 06:23:28');
INSERT INTO `as_fields` VALUES('1494', '4', 'report_confirmed_amount', 'backend', 'Reports / Confirmed Bookings Amount', 'script', '2013-10-10 06:23:47');
INSERT INTO `as_fields` VALUES('1495', '4', 'report_pending_amount', 'backend', 'Reports / Pending Bookings Amount', 'script', '2013-10-10 06:24:09');
INSERT INTO `as_fields` VALUES('1496', '4', 'report_cancelled_amount', 'backend', 'Reports / Cancelled Bookings Amount', 'script', '2013-10-10 06:24:26');
INSERT INTO `as_fields` VALUES('1497', '4', 'report_columns', 'backend', 'Reports / Columns', 'script', '2013-10-10 11:55:19');
INSERT INTO `as_fields` VALUES('1498', '4', 'report_print', 'backend', 'Reports / Print', 'script', '2013-10-10 13:36:52');
INSERT INTO `as_fields` VALUES('1499', '4', 'report_pdf', 'backend', 'Reports / Save as PDF', 'script', '2013-10-11 06:18:31');
INSERT INTO `as_fields` VALUES('1500', '4', 'menu', 'backend', 'Menu', 'script', '2013-11-11 09:22:39');
INSERT INTO `as_fields` VALUES('1501', '4', 'employee_view_bookings', 'backend', 'Employees / View bookings', 'script', '2013-11-11 09:23:31');
INSERT INTO `as_fields` VALUES('1502', '4', 'employee_working_time', 'backend', 'Employees / Working time', 'script', '2013-11-11 09:23:57');
INSERT INTO `as_fields` VALUES('1503', '4', 'error_titles_ARRAY_AS11', 'arrays', 'error_titles_ARRAY_AS11', 'script', '2013-11-11 09:46:01');
INSERT INTO `as_fields` VALUES('1504', '4', 'error_bodies_ARRAY_AS11', 'arrays', 'error_bodies_ARRAY_AS11', 'script', '2013-11-22 09:44:50');
INSERT INTO `as_fields` VALUES('1505', '4', 'service_tip_length', 'backend', 'Services / Length tooltip', 'script', '2013-11-22 09:46:30');
INSERT INTO `as_fields` VALUES('1506', '4', 'service_tip_before', 'backend', 'Services / Before tooltip', 'script', '2013-11-22 09:47:47');
INSERT INTO `as_fields` VALUES('1507', '4', 'service_tip_after', 'backend', 'Services / After tooltip', 'script', '2013-11-22 09:48:28');
INSERT INTO `as_fields` VALUES('1508', '4', 'service_tip_employees', 'backend', 'Services / Employees tooltip', 'script', '2013-11-11 10:05:28');
INSERT INTO `as_fields` VALUES('1509', '4', 'employee_is_subscribed_sms', 'backend', 'Employees / Send sms', 'script', '2013-11-11 12:34:31');
INSERT INTO `as_fields` VALUES('1510', '4', 'booking_reminder_client', 'backend', 'Bookings / Send to client', 'script', '2013-11-11 12:58:52');
INSERT INTO `as_fields` VALUES('1511', '4', 'booking_reminder_employee', 'backend', 'Bookings / Send to employee', 'script', '2013-11-11 12:59:04');
INSERT INTO `as_fields` VALUES('1512', '4', 'front_click_available', 'frontend', 'Frontend / Click on available time', 'script', '2013-11-12 08:29:47');
INSERT INTO `as_fields` VALUES('1514', '4', 'error_titles_ARRAY_AE11', 'arrays', 'error_titles_ARRAY_AE11', 'script', '2013-11-18 07:36:16');
INSERT INTO `as_fields` VALUES('1515', '4', 'error_bodies_ARRAY_AE11', 'arrays', 'error_bodies_ARRAY_AE11', 'script', '2013-11-22 09:50:21');
INSERT INTO `as_fields` VALUES('1530', '4', 'menuSeo', 'backend', 'Menu SEO', 'script', '2013-11-18 08:37:13');
INSERT INTO `as_fields` VALUES('1531', '4', 'lblInstallConfig', 'backend', 'Install / Config', 'script', '2013-11-18 08:40:41');
INSERT INTO `as_fields` VALUES('1532', '4', 'lblInstallConfigLocale', 'backend', 'Install / Locale', 'script', '2013-11-18 08:40:54');
INSERT INTO `as_fields` VALUES('1533', '4', 'lblInstallConfigHide', 'backend', 'Install / Config hide', 'script', '2013-11-18 08:41:05');
INSERT INTO `as_fields` VALUES('1534', '4', 'error_bodies_ARRAY_AO30', 'arrays', 'error_titles_ARRAY_AO30', 'script', '2013-11-18 08:45:15');
INSERT INTO `as_fields` VALUES('1535', '4', 'error_titles_ARRAY_AO30', 'arrays', 'error_titles_ARRAY_AO30', 'script', '2013-11-18 08:45:32');
INSERT INTO `as_fields` VALUES('1536', '4', 'lblInstallSeo_1', 'backend', 'Install / SEO Step 1', 'script', '2013-11-18 08:46:19');
INSERT INTO `as_fields` VALUES('1537', '4', 'lblInstallSeo_2', 'backend', 'Install / SEO Step 2', 'script', '2013-11-18 08:47:16');
INSERT INTO `as_fields` VALUES('1538', '4', 'lblInstallSeo_3', 'backend', 'Install / SEO Step 3', 'script', '2013-11-18 08:47:33');
INSERT INTO `as_fields` VALUES('1539', '4', 'btnGenerate', 'backend', 'Generate', 'script', '2013-11-18 10:11:06');
INSERT INTO `as_fields` VALUES('1540', '4', 'booking_index', 'backend', 'Bookings / Index', 'script', '2013-11-18 10:31:03');
INSERT INTO `as_fields` VALUES('1541', '4', 'error_titles_ARRAY_AR01', 'arrays', 'error_titles_ARRAY_AR01', 'script', '2013-11-19 07:05:22');
INSERT INTO `as_fields` VALUES('1542', '4', 'error_bodies_ARRAY_AR01', 'arrays', 'error_bodies_ARRAY_AR01', 'script', '2013-11-26 11:30:50');
INSERT INTO `as_fields` VALUES('1543', '4', 'error_titles_ARRAY_AR02', 'arrays', 'error_titles_ARRAY_AR02', 'script', '2013-11-19 08:58:03');
INSERT INTO `as_fields` VALUES('1544', '4', 'error_bodies_ARRAY_AR02', 'arrays', 'error_bodies_ARRAY_AR02', 'script', '2013-11-26 11:32:22');
INSERT INTO `as_fields` VALUES('1545', '4', 'report_amount', 'backend', 'Reports / Amount', 'script', '2013-11-19 09:11:38');
INSERT INTO `as_fields` VALUES('1546', '4', 'report_cnt', 'backend', 'Reports / Count', 'script', '2013-11-19 09:11:47');
INSERT INTO `as_fields` VALUES('1547', '4', 'error_bodies_ARRAY_AD01', 'arrays', 'error_titles_ARRAY_AD01', 'script', '2013-11-26 11:28:15');
INSERT INTO `as_fields` VALUES('1548', '4', 'error_titles_ARRAY_AD01', 'arrays', 'error_titles_ARRAY_AD01', 'script', '2013-11-19 14:39:41');
INSERT INTO `as_fields` VALUES('1549', '4', 'btnApply', 'backend', 'Save', 'script', '2013-11-19 15:19:53');
INSERT INTO `as_fields` VALUES('1550', '4', 'dashboard_filter', 'backend', 'Dashboard / Filter', 'script', '2013-11-19 15:20:36');
INSERT INTO `as_fields` VALUES('1756', '4', 'error_titles_ARRAY_AT05', 'arrays', 'error_titles_ARRAY_AT05', 'script', '2013-12-12 18:48:22');
INSERT INTO `as_fields` VALUES('1758', '4', 'error_bodies_ARRAY_AT05', 'arrays', 'error_bodies_ARRAY_AT05', 'script', '2013-12-12 18:49:38');
INSERT INTO `as_fields` VALUES('1759', '4', 'error_bodies_ARRAY_AT06', 'arrays', 'error_bodies_ARRAY_AT06', 'script', '2013-12-12 18:57:00');
INSERT INTO `as_fields` VALUES('1760', '4', 'error_titles_ARRAY_AT06', 'arrays', 'error_titles_ARRAY_AT06', 'script', '2013-12-12 18:44:11');
INSERT INTO `as_fields` VALUES('1761', '4', 'error_titles_ARRAY_AT07', 'arrays', 'error_titles_ARRAY_AT07', 'script', '2013-12-12 18:57:15');
INSERT INTO `as_fields` VALUES('1762', '4', 'error_bodies_ARRAY_AT07', 'arrays', 'error_bodies_ARRAY_AT07', 'script', '2013-12-12 19:35:49');
INSERT INTO `as_fields` VALUES('1768', '4', 'btnToday', 'backend', 'Button / Today', 'script', '2013-11-25 09:07:23');
INSERT INTO `as_fields` VALUES('1769', '4', 'btnTomorrow', 'backend', 'Button / Tomorrow', 'script', '2013-11-25 09:07:53');
INSERT INTO `as_fields` VALUES('1770', '4', 'error_titles_ARRAY_AD02', 'arrays', 'error_titles_ARRAY_AD02', 'script', '2013-11-25 10:58:52');
INSERT INTO `as_fields` VALUES('1771', '4', 'error_bodies_ARRAY_AD02', 'arrays', 'error_titles_ARRAY_AD02', 'script', '2013-11-25 10:59:14');
INSERT INTO `as_fields` VALUES('1772', '4', 'payment_paypal_submit', 'frontend', 'Frontend / Paypal submit', 'script', '2013-12-18 10:49:09');
INSERT INTO `as_fields` VALUES('1773', '4', 'payment_authorize_submit', 'frontend', 'Frontend / Authorize.NET submit', 'script', '2013-12-18 10:49:31');
INSERT INTO `as_fields` VALUES('1774', '4', 'front_booking_status_ARRAY_11', 'arrays', 'front_booking_status_ARRAY_11', 'script', '2013-12-18 10:51:14');
INSERT INTO `as_fields` VALUES('1775', '4', 'front_booking_status_ARRAY_1', 'arrays', 'front_booking_status_ARRAY_1', 'script', '2013-12-18 10:51:29');
INSERT INTO `as_fields` VALUES('1776', '4', 'front_booking_status_ARRAY_4', 'arrays', 'front_booking_status_ARRAY_4', 'script', '2013-12-18 10:52:17');
INSERT INTO `as_fields` VALUES('1777', '4', 'front_booking_status_ARRAY_3', 'arrays', 'front_booking_status_ARRAY_3', 'script', '2013-12-18 10:56:19');
INSERT INTO `as_fields` VALUES('1778', '4', 'front_booking_na', 'frontend', 'Frontend / Booking not available', 'script', '2013-12-18 12:19:33');
INSERT INTO `as_fields` VALUES('1779', '4', 'booking_start_time', 'backend', 'Bookings / Start time', 'script', '2013-12-18 13:58:57');
INSERT INTO `as_fields` VALUES('1780', '4', 'booking_end_time', 'backend', 'Bookings / End time', 'script', '2013-12-18 13:59:11');
INSERT INTO `as_fields` VALUES('1781', '4', 'error_titles_ARRAY_ABK14', 'arrays', 'error_titles_ARRAY_ABK14', 'script', '2013-12-18 14:26:25');
INSERT INTO `as_fields` VALUES('1782', '4', 'error_bodies_ARRAY_ABK14', 'arrays', 'error_bodies_ARRAY_ABK14', 'script', '2013-12-18 14:27:17');
INSERT INTO `as_fields` VALUES('1783', '4', 'error_titles_ARRAY_ABK15', 'arrays', 'error_titles_ARRAY_ABK15', 'script', '2013-12-18 14:49:29');
INSERT INTO `as_fields` VALUES('1784', '4', 'error_bodies_ARRAY_ABK15', 'arrays', 'error_bodies_ARRAY_ABK15', 'script', '2013-12-18 14:50:19');
INSERT INTO `as_fields` VALUES('1785', '4', 'front_minutes', 'frontend', 'Frontend / Minutes', 'script', '2014-01-09 09:50:10');
INSERT INTO `as_fields` VALUES('1786', '4', 'front_day_suffix_ARRAY_st', 'arrays', 'Frontend / Day suffix: 1st', 'script', '2014-01-09 12:22:48');
INSERT INTO `as_fields` VALUES('1787', '4', 'front_day_suffix_ARRAY_nd', 'arrays', 'Frontend / Day suffix: 2nd', 'script', '2014-01-09 12:23:01');
INSERT INTO `as_fields` VALUES('1788', '4', 'front_day_suffix_ARRAY_rd', 'arrays', 'Frontend / Day suffix: 3rd', 'script', '2014-01-09 12:23:32');
INSERT INTO `as_fields` VALUES('1789', '4', 'front_day_suffix_ARRAY_th', 'arrays', 'Frontend / Day suffix: Nth', 'script', '2014-01-09 12:24:47');
INSERT INTO `as_fields` VALUES('1790', '4', 'front_on', 'frontend', 'Frontend / On', 'script', '2014-01-09 12:29:55');
INSERT INTO `as_fields` VALUES('1791', '4', 'front_back_services', 'backend', 'Frontend / Back to services', 'script', '2014-01-09 12:39:14');
INSERT INTO `as_fields` VALUES('1792', '4', 'front_checkout', 'frontend', 'Frontend / Checkout', 'script', '2014-01-09 12:55:52');
INSERT INTO `as_fields` VALUES('1793', '4', 'front_cart_done', 'frontend', 'Frontend / Service added', 'script', '2014-01-09 12:56:53');
INSERT INTO `as_fields` VALUES('1794', '4', 'front_app_ARRAY_v_remote', 'arrays', 'front_app_ARRAY_v_remote', 'script', '2014-01-09 15:12:14');
INSERT INTO `as_fields` VALUES('1795', '4', 'front_from', 'backend', 'Frontend / From', 'script', '2014-01-09 15:41:17');
INSERT INTO `as_fields` VALUES('1796', '4', 'front_till', 'backend', 'Frontend / Till', 'script', '2014-01-09 15:41:28');
INSERT INTO `as_fields` VALUES('1797', '4', 'cancel_err_ARRAY_1', 'arrays', 'cancel_err_ARRAY_1', 'script', '2014-01-22 07:55:23');
INSERT INTO `as_fields` VALUES('1798', '4', 'cancel_err_ARRAY_2', 'arrays', 'cancel_err_ARRAY_2', 'script', '2014-01-22 07:56:24');
INSERT INTO `as_fields` VALUES('1799', '4', 'cancel_err_ARRAY_3', 'arrays', 'cancel_err_ARRAY_3', 'script', '2014-01-22 07:56:17');
INSERT INTO `as_fields` VALUES('1800', '4', 'cancel_err_ARRAY_4', 'arrays', 'cancel_err_ARRAY_4', 'script', '2014-01-22 07:56:38');
INSERT INTO `as_fields` VALUES('1801', '4', 'cancel_err_ARRAY_5', 'arrays', 'cancel_err_ARRAY_5', 'script', '2014-01-22 07:57:02');
INSERT INTO `as_fields` VALUES('1802', '4', 'cancel_details', 'frontend', 'Cancel / Customer Details', 'script', '2014-01-22 08:42:56');
INSERT INTO `as_fields` VALUES('1803', '4', 'cancel_confirm', 'frontend', 'Cancel / Cancel button', 'script', '2014-01-22 08:41:25');
INSERT INTO `as_fields` VALUES('1804', '4', 'cancel_services', 'frontend', 'Cancel / Booking Services', 'script', '2014-01-22 08:42:33');
INSERT INTO `as_fields` VALUES('1805', '4', 'cancel_title', 'frontend', 'Cancel / Page title', 'script', '2014-01-22 08:43:29');
INSERT INTO `as_fields` VALUES('1806', '4', 'confirmation_employee_confirmation', 'backend', 'Confirmation / Employee confirmation title', 'script', '2014-01-30 07:19:10');
INSERT INTO `as_fields` VALUES('1807', '4', 'confirmation_employee_payment', 'backend', 'Confirmation / Employee payment title', 'script', '2014-01-30 07:19:16');
INSERT INTO `as_fields` VALUES('1808', '4', 'time_update_default', 'backend', 'Working Time / Update default working time', 'script', '2014-01-30 09:04:39');
INSERT INTO `as_fields` VALUES('1809', '4', 'opt_o_layout', 'backend', 'Options / Layout', 'script', '2014-02-06 09:05:31');
INSERT INTO `as_fields` VALUES('1810', '4', 'front_single_date', 'frontend', 'Single / Select date', 'script', '2014-02-06 10:36:29');
INSERT INTO `as_fields` VALUES('1811', '4', 'front_single_service', 'frontend', 'Single / Service', 'script', '2014-02-06 10:36:52');
INSERT INTO `as_fields` VALUES('1812', '4', 'front_single_time', 'frontend', 'Single / Select time', 'script', '2014-02-06 10:37:06');
INSERT INTO `as_fields` VALUES('1813', '4', 'front_single_employee', 'frontend', 'Single / Employee', 'script', '2014-02-06 10:37:37');
INSERT INTO `as_fields` VALUES('1814', '4', 'btnBook', 'backend', 'Button / Book', 'script', '2014-02-06 10:41:10');
INSERT INTO `as_fields` VALUES('1815', '4', 'front_single_date_service', 'frontend', 'Single / Select date and service', 'script', '2014-02-06 10:41:55');
INSERT INTO `as_fields` VALUES('1816', '4', 'front_single_choose_date', 'frontend', 'Single / Choose date', 'script', '2014-02-06 10:45:32');
INSERT INTO `as_fields` VALUES('1817', '4', 'single_date', 'frontend', 'Single / Date', 'script', '2014-02-10 14:04:43');
INSERT INTO `as_fields` VALUES('1818', '4', 'single_price', 'frontend', 'Single / Price', 'script', '2014-02-10 14:04:53');
INSERT INTO `as_fields` VALUES('1819', '4', 'front_booking_status_ARRAY_5', 'arrays', 'front_booking_status_ARRAY_5', 'script', '2014-02-17 07:38:07');
INSERT INTO `as_fields` VALUES('1820', '4', 'front_single_na', 'frontend', 'Single / Not available', 'script', '2014-02-20 15:21:19');
INSERT INTO `as_fields` VALUES('1821', '4', 'plugin_locale_languages', 'backend', 'Locale plugin / Languages', 'plugin', '');
INSERT INTO `as_fields` VALUES('1822', '4', 'plugin_locale_titles', 'backend', 'Locale plugin / Titles', 'plugin', '');
INSERT INTO `as_fields` VALUES('1823', '4', 'plugin_locale_index_title', 'backend', 'Locale plugin / Languages info title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1824', '4', 'plugin_locale_index_body', 'backend', 'Locale plugin / Languages info body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1825', '4', 'plugin_locale_titles_title', 'backend', 'Locale plugin / Titles info title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1826', '4', 'plugin_locale_titles_body', 'backend', 'Locale plugin / Titles info body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1827', '4', 'plugin_locale_lbl_title', 'backend', 'Locale plugin / Title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1828', '4', 'plugin_locale_lbl_flag', 'backend', 'Locale plugin / Flag', 'plugin', '');
INSERT INTO `as_fields` VALUES('1829', '4', 'plugin_locale_lbl_is_default', 'backend', 'Locale plugin / Is default', 'plugin', '');
INSERT INTO `as_fields` VALUES('1830', '4', 'plugin_locale_lbl_order', 'backend', 'Locale plugin / Order', 'plugin', '');
INSERT INTO `as_fields` VALUES('1831', '4', 'plugin_locale_add_lang', 'backend', 'Locale plugin / Add Language', 'plugin', '');
INSERT INTO `as_fields` VALUES('1832', '4', 'plugin_locale_lbl_field', 'backend', 'Locale plugin / Field', 'plugin', '');
INSERT INTO `as_fields` VALUES('1833', '4', 'plugin_locale_lbl_value', 'backend', 'Locale plugin / Value', 'plugin', '');
INSERT INTO `as_fields` VALUES('1834', '4', 'plugin_locale_type_backend', 'backend', 'Locale plugin / Back-end title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1835', '4', 'plugin_locale_type_frontend', 'backend', 'Locale plugin / Front-end title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1836', '4', 'plugin_locale_type_arrays', 'backend', 'Locale plugin / Special title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1837', '4', 'error_titles_ARRAY_PAL01', 'arrays', 'Locale plugin / Status title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1838', '4', 'error_bodies_ARRAY_PAL01', 'arrays', 'Locale plugin / Status body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1839', '4', 'plugin_locale_lbl_rows', 'backend', 'Locale plugin / Per page', 'plugin', '');
INSERT INTO `as_fields` VALUES('1840', '4', 'error_titles_ARRAY_PAL02', 'arrays', 'Locale plugin / Status title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1841', '4', 'error_bodies_ARRAY_PAL02', 'arrays', 'Locale plugin / Status body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1842', '4', 'error_titles_ARRAY_PAL03', 'arrays', 'Locale plugin / Status title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1843', '4', 'error_bodies_ARRAY_PAL03', 'arrays', 'Locale plugin / Status body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1844', '4', 'error_titles_ARRAY_PAL04', 'arrays', 'Locale plugin / Status title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1845', '4', 'error_bodies_ARRAY_PAL04', 'arrays', 'Locale plugin / Status body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1846', '4', 'plugin_locale_import_export', 'backend', 'Locale plugin / Import Export menu', 'plugin', '');
INSERT INTO `as_fields` VALUES('1847', '4', 'plugin_locale_import', 'backend', 'Locale plugin / Import', 'plugin', '');
INSERT INTO `as_fields` VALUES('1848', '4', 'plugin_locale_export', 'backend', 'Locale plugin / Export', 'plugin', '');
INSERT INTO `as_fields` VALUES('1849', '4', 'plugin_locale_language', 'backend', 'Locale plugin / Language', 'plugin', '');
INSERT INTO `as_fields` VALUES('1850', '4', 'plugin_locale_browse', 'backend', 'Locale plugin / Browse your computer', 'plugin', '');
INSERT INTO `as_fields` VALUES('1851', '4', 'plugin_locale_ie_title', 'backend', 'Locale plugin / Import Export (title)', 'plugin', '');
INSERT INTO `as_fields` VALUES('1852', '4', 'plugin_locale_ie_body', 'backend', 'Locale plugin / Import Export (body)', 'plugin', '');
INSERT INTO `as_fields` VALUES('1853', '4', 'error_titles_ARRAY_PBU01', 'arrays', 'error_titles_ARRAY_PBU01', 'plugin', '');
INSERT INTO `as_fields` VALUES('1854', '4', 'error_titles_ARRAY_PBU02', 'arrays', 'error_titles_ARRAY_PBU02', 'plugin', '');
INSERT INTO `as_fields` VALUES('1855', '4', 'error_titles_ARRAY_PBU03', 'arrays', 'error_titles_ARRAY_PBU03', 'plugin', '');
INSERT INTO `as_fields` VALUES('1856', '4', 'error_titles_ARRAY_PBU04', 'arrays', 'error_titles_ARRAY_PBU04', 'plugin', '');
INSERT INTO `as_fields` VALUES('1857', '4', 'error_bodies_ARRAY_PBU01', 'arrays', 'error_bodies_ARRAY_PBU01', 'plugin', '');
INSERT INTO `as_fields` VALUES('1858', '4', 'error_bodies_ARRAY_PBU02', 'arrays', 'error_bodies_ARRAY_PBU02', 'plugin', '');
INSERT INTO `as_fields` VALUES('1859', '4', 'error_bodies_ARRAY_PBU03', 'arrays', 'error_bodies_ARRAY_PBU03', 'plugin', '');
INSERT INTO `as_fields` VALUES('1860', '4', 'error_bodies_ARRAY_PBU04', 'arrays', 'error_bodies_ARRAY_PBU04', 'plugin', '');
INSERT INTO `as_fields` VALUES('1861', '4', 'plugin_backup_menu_backup', 'backend', 'Backup plugin / Menu Backup', 'plugin', '');
INSERT INTO `as_fields` VALUES('1862', '4', 'plugin_backup_database', 'backend', 'Backup plugin / Backup database', 'plugin', '');
INSERT INTO `as_fields` VALUES('1863', '4', 'plugin_backup_files', 'backend', 'Backup plugin / Backup files', 'plugin', '');
INSERT INTO `as_fields` VALUES('1864', '4', 'plugin_backup_btn_backup', 'backend', 'Backup plugin / Backup button', 'plugin', '');
INSERT INTO `as_fields` VALUES('1865', '4', 'plugin_log_menu_log', 'backend', 'Log plugin / Menu Log', 'plugin', '');
INSERT INTO `as_fields` VALUES('1866', '4', 'plugin_log_menu_config', 'backend', 'Log plugin / Menu Config', 'plugin', '');
INSERT INTO `as_fields` VALUES('1867', '4', 'plugin_log_btn_empty', 'backend', 'Log plugin / Empty button', 'plugin', '');
INSERT INTO `as_fields` VALUES('1868', '4', 'error_titles_ARRAY_PLG01', 'arrays', 'error_titles_ARRAY_PLG01', 'plugin', '');
INSERT INTO `as_fields` VALUES('1869', '4', 'error_bodies_ARRAY_PLG01', 'arrays', 'error_bodies_ARRAY_PLG01', 'plugin', '');
INSERT INTO `as_fields` VALUES('1870', '4', 'plugin_one_admin_menu_index', 'backend', 'One Admin plugin / List', 'plugin', '');
INSERT INTO `as_fields` VALUES('1871', '4', 'plugin_one_admin_btn_add', 'backend', 'One Admin plugin / Add button', 'plugin', '');
INSERT INTO `as_fields` VALUES('1872', '4', 'plugin_country_name', 'backend', 'Country plugin / Country name', 'plugin', '');
INSERT INTO `as_fields` VALUES('1873', '4', 'plugin_country_alpha_2', 'backend', 'Country plugin / Alpha 2', 'plugin', '');
INSERT INTO `as_fields` VALUES('1874', '4', 'plugin_country_alpha_3', 'backend', 'Country plugin / Alpha 3', 'plugin', '');
INSERT INTO `as_fields` VALUES('1875', '4', 'plugin_country_status', 'backend', 'Country plugin / Status', 'plugin', '');
INSERT INTO `as_fields` VALUES('1876', '4', 'plugin_country_btn_add', 'backend', 'Country plugin / Button Add', 'plugin', '');
INSERT INTO `as_fields` VALUES('1877', '4', 'plugin_country_statuses_ARRAY_T', 'arrays', 'Country plugin / Status (active)', 'plugin', '');
INSERT INTO `as_fields` VALUES('1878', '4', 'plugin_country_statuses_ARRAY_F', 'arrays', 'Country plugin / Status (inactive)', 'plugin', '');
INSERT INTO `as_fields` VALUES('1879', '4', 'plugin_country_btn_save', 'backend', 'Country plugin / Button Save', 'plugin', '');
INSERT INTO `as_fields` VALUES('1880', '4', 'plugin_country_btn_cancel', 'backend', 'Country plugin / Button Cancel', 'plugin', '');
INSERT INTO `as_fields` VALUES('1881', '4', 'plugin_country_menu_countries', 'backend', 'Country plugin / Menu Countries', 'plugin', '');
INSERT INTO `as_fields` VALUES('1882', '4', 'error_titles_ARRAY_PCY01', 'arrays', 'error_titles_ARRAY_PCY01', 'plugin', '');
INSERT INTO `as_fields` VALUES('1883', '4', 'error_titles_ARRAY_PCY03', 'arrays', 'error_titles_ARRAY_PCY03', 'plugin', '');
INSERT INTO `as_fields` VALUES('1884', '4', 'error_titles_ARRAY_PCY04', 'arrays', 'error_titles_ARRAY_PCY04', 'plugin', '');
INSERT INTO `as_fields` VALUES('1885', '4', 'error_titles_ARRAY_PCY08', 'arrays', 'error_titles_ARRAY_PCY08', 'plugin', '');
INSERT INTO `as_fields` VALUES('1886', '4', 'error_titles_ARRAY_PCY10', 'arrays', 'error_titles_ARRAY_PCY10', 'plugin', '');
INSERT INTO `as_fields` VALUES('1887', '4', 'error_titles_ARRAY_PCY11', 'arrays', 'error_titles_ARRAY_PCY11', 'plugin', '');
INSERT INTO `as_fields` VALUES('1888', '4', 'error_titles_ARRAY_PCY12', 'arrays', 'error_titles_ARRAY_PCY12', 'plugin', '');
INSERT INTO `as_fields` VALUES('1889', '4', 'error_bodies_ARRAY_PCY01', 'arrays', 'error_bodies_ARRAY_PCY01', 'plugin', '');
INSERT INTO `as_fields` VALUES('1890', '4', 'error_bodies_ARRAY_PCY03', 'arrays', 'error_bodies_ARRAY_PCY03', 'plugin', '');
INSERT INTO `as_fields` VALUES('1891', '4', 'error_bodies_ARRAY_PCY04', 'arrays', 'error_bodies_ARRAY_PCY04', 'plugin', '');
INSERT INTO `as_fields` VALUES('1892', '4', 'error_bodies_ARRAY_PCY08', 'arrays', 'error_bodies_ARRAY_PCY08', 'plugin', '');
INSERT INTO `as_fields` VALUES('1893', '4', 'error_bodies_ARRAY_PCY10', 'arrays', 'error_bodies_ARRAY_PCY10', 'plugin', '');
INSERT INTO `as_fields` VALUES('1894', '4', 'error_bodies_ARRAY_PCY11', 'arrays', 'error_bodies_ARRAY_PCY11', 'plugin', '');
INSERT INTO `as_fields` VALUES('1895', '4', 'error_bodies_ARRAY_PCY12', 'arrays', 'error_bodies_ARRAY_PCY12', 'plugin', '');
INSERT INTO `as_fields` VALUES('1896', '4', 'plugin_country_delete_confirmation', 'backend', 'Country plugin / Delete confirmation', 'plugin', '');
INSERT INTO `as_fields` VALUES('1897', '4', 'plugin_country_delete_selected', 'backend', 'Country plugin / Delete selected', 'plugin', '');
INSERT INTO `as_fields` VALUES('1898', '4', 'plugin_country_btn_all', 'backend', 'Country plugin / Button All', 'plugin', '');
INSERT INTO `as_fields` VALUES('1899', '4', 'plugin_country_btn_search', 'backend', 'Country plugin / Button Search', 'plugin', '');
INSERT INTO `as_fields` VALUES('1900', '4', 'plugin_invoice_menu_invoices', 'backend', 'Invoice plugin / Menu Invoices', 'plugin', '');
INSERT INTO `as_fields` VALUES('1901', '4', 'plugin_invoice_config', 'backend', 'Invoice plugin / Invoice config', 'plugin', '');
INSERT INTO `as_fields` VALUES('1902', '4', 'plugin_invoice_i_logo', 'backend', 'Invoice plugin / Company logo', 'plugin', '');
INSERT INTO `as_fields` VALUES('1903', '4', 'plugin_invoice_i_company', 'backend', 'Invoice plugin / Company name', 'plugin', '');
INSERT INTO `as_fields` VALUES('1904', '4', 'plugin_invoice_i_name', 'backend', 'Invoice plugin / Name', 'plugin', '');
INSERT INTO `as_fields` VALUES('1905', '4', 'plugin_invoice_i_street_address', 'backend', 'Invoice plugin / Street address', 'plugin', '');
INSERT INTO `as_fields` VALUES('1906', '4', 'plugin_invoice_i_city', 'backend', 'Invoice plugin / City', 'plugin', '');
INSERT INTO `as_fields` VALUES('1907', '4', 'plugin_invoice_i_state', 'backend', 'Invoice plugin / State', 'plugin', '');
INSERT INTO `as_fields` VALUES('1908', '4', 'plugin_invoice_i_zip', 'backend', 'Invoice plugin / Zip', 'plugin', '');
INSERT INTO `as_fields` VALUES('1909', '4', 'plugin_invoice_i_phone', 'backend', 'Invoice plugin / Phone', 'plugin', '');
INSERT INTO `as_fields` VALUES('1910', '4', 'plugin_invoice_i_fax', 'backend', 'Invoice plugin / Fax', 'plugin', '');
INSERT INTO `as_fields` VALUES('1911', '4', 'plugin_invoice_i_email', 'backend', 'Invoice plugin / Email', 'plugin', '');
INSERT INTO `as_fields` VALUES('1912', '4', 'plugin_invoice_i_url', 'backend', 'Invoice plugin / Website', 'plugin', '');
INSERT INTO `as_fields` VALUES('1913', '4', 'error_titles_ARRAY_PIN01', 'arrays', 'Invoice plugin / Info title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1914', '4', 'error_bodies_ARRAY_PIN01', 'arrays', 'Invoice plugin / Info body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1915', '4', 'error_titles_ARRAY_PIN02', 'arrays', 'Invoice plugin / Invoice config updated title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1916', '4', 'error_bodies_ARRAY_PIN02', 'arrays', 'Invoice plugin / Info body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1917', '4', 'error_titles_ARRAY_PIN03', 'arrays', 'Invoice plugin / Upload failed', 'plugin', '');
INSERT INTO `as_fields` VALUES('1918', '4', 'plugin_invoice_template', 'backend', 'Invoice plugin / Invoice Template', 'plugin', '');
INSERT INTO `as_fields` VALUES('1919', '4', 'plugin_invoice_delete_logo_title', 'backend', 'Invoice plugin / Delete logo title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1920', '4', 'plugin_invoice_delete_logo_body', 'backend', 'Invoice plugin / Delete logo body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1921', '4', 'plugin_invoice_billing_info', 'backend', 'Invoice plugin / Billing information', 'plugin', '');
INSERT INTO `as_fields` VALUES('1922', '4', 'plugin_invoice_shipping_info', 'backend', 'Invoice plugin / Shipping information', 'plugin', '');
INSERT INTO `as_fields` VALUES('1923', '4', 'plugin_invoice_company_info', 'backend', 'Invoice plugin / Company information', 'plugin', '');
INSERT INTO `as_fields` VALUES('1924', '4', 'plugin_invoice_payment_info', 'backend', 'Invoice plugin / Payment information', 'plugin', '');
INSERT INTO `as_fields` VALUES('1925', '4', 'plugin_invoice_i_address', 'backend', 'Invoice plugin / Address', 'plugin', '');
INSERT INTO `as_fields` VALUES('1926', '4', 'plugin_invoice_i_billing_address', 'backend', 'Invoice plugin / Billing address', 'plugin', '');
INSERT INTO `as_fields` VALUES('1927', '4', 'plugin_invoice_general_info', 'backend', 'Invoice plugin / General information', 'plugin', '');
INSERT INTO `as_fields` VALUES('1928', '4', 'plugin_invoice_i_uuid', 'backend', 'Invoice plugin / Invoice no.', 'plugin', '');
INSERT INTO `as_fields` VALUES('1929', '4', 'plugin_invoice_i_order_id', 'backend', 'Invoice plugin / Order no.', 'plugin', '');
INSERT INTO `as_fields` VALUES('1930', '4', 'plugin_invoice_i_issue_date', 'backend', 'Invoice plugin / Issue date', 'plugin', '');
INSERT INTO `as_fields` VALUES('1931', '4', 'plugin_invoice_i_due_date', 'backend', 'Invoice plugin / Due date', 'plugin', '');
INSERT INTO `as_fields` VALUES('1932', '4', 'plugin_invoice_i_shipping_date', 'backend', 'Invoice plugin / Shipping date', 'plugin', '');
INSERT INTO `as_fields` VALUES('1933', '4', 'plugin_invoice_i_shipping_terms', 'backend', 'Invoice plugin / Shipping terms', 'plugin', '');
INSERT INTO `as_fields` VALUES('1934', '4', 'plugin_invoice_i_subtotal', 'backend', 'Invoice plugin / Subtotal', 'plugin', '');
INSERT INTO `as_fields` VALUES('1935', '4', 'plugin_invoice_i_discount', 'backend', 'Invoice plugin / Discount', 'plugin', '');
INSERT INTO `as_fields` VALUES('1936', '4', 'plugin_invoice_i_tax', 'backend', 'Invoice plugin / Tax', 'plugin', '');
INSERT INTO `as_fields` VALUES('1937', '4', 'plugin_invoice_i_shipping', 'backend', 'Invoice plugin / Tax', 'plugin', '');
INSERT INTO `as_fields` VALUES('1938', '4', 'plugin_invoice_i_total', 'backend', 'Invoice plugin / Total', 'plugin', '');
INSERT INTO `as_fields` VALUES('1939', '4', 'plugin_invoice_i_paid_deposit', 'backend', 'Invoice plugin / Paid deposit', 'plugin', '');
INSERT INTO `as_fields` VALUES('1940', '4', 'plugin_invoice_i_amount_due', 'backend', 'Invoice plugin / Amount due', 'plugin', '');
INSERT INTO `as_fields` VALUES('1941', '4', 'plugin_invoice_i_currency', 'backend', 'Invoice plugin / Currency', 'plugin', '');
INSERT INTO `as_fields` VALUES('1942', '4', 'plugin_invoice_i_notes', 'backend', 'Invoice plugin / Notes', 'plugin', '');
INSERT INTO `as_fields` VALUES('1943', '4', 'plugin_invoice_i_shipping_address', 'backend', 'Invoice plugin / Shipping address', 'plugin', '');
INSERT INTO `as_fields` VALUES('1944', '4', 'plugin_invoice_i_created', 'backend', 'Invoice plugin / Created', 'plugin', '');
INSERT INTO `as_fields` VALUES('1945', '4', 'plugin_invoice_i_modified', 'backend', 'Invoice plugin / Modified', 'plugin', '');
INSERT INTO `as_fields` VALUES('1946', '4', 'plugin_invoice_i_item', 'backend', 'Invoice plugin / Item', 'plugin', '');
INSERT INTO `as_fields` VALUES('1947', '4', 'plugin_invoice_i_qty', 'backend', 'Invoice plugin / Qty', 'plugin', '');
INSERT INTO `as_fields` VALUES('1948', '4', 'plugin_invoice_i_unit', 'backend', 'Invoice plugin / Unit price', 'plugin', '');
INSERT INTO `as_fields` VALUES('1949', '4', 'plugin_invoice_i_amount', 'backend', 'Invoice plugin / Amount', 'plugin', '');
INSERT INTO `as_fields` VALUES('1950', '4', 'plugin_invoice_add_item_title', 'backend', 'Invoice plugin / Add item title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1951', '4', 'plugin_invoice_edit_item_title', 'backend', 'Invoice plugin / Update item title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1952', '4', 'plugin_invoice_i_description', 'backend', 'Invoice plugin / Description', 'plugin', '');
INSERT INTO `as_fields` VALUES('1953', '4', 'plugin_invoice_i_accept_payments', 'backend', 'Invoice plugin / Accept payments', 'plugin', '');
INSERT INTO `as_fields` VALUES('1954', '4', 'plugin_invoice_print', 'backend', 'Invoice plugin / Print invoice', 'plugin', '');
INSERT INTO `as_fields` VALUES('1955', '4', 'plugin_invoice_send', 'backend', 'Invoice plugin / Send invoice', 'plugin', '');
INSERT INTO `as_fields` VALUES('1956', '4', 'plugin_invoice_view', 'backend', 'Invoice plugin / View invoice', 'plugin', '');
INSERT INTO `as_fields` VALUES('1957', '4', 'plugin_invoice_send_invoice_title', 'backend', 'Invoice plugin / Send invoice dialog title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1958', '4', 'plugin_invoice_send_subject', 'backend', 'Invoice plugin / Send invoice subject', 'plugin', '');
INSERT INTO `as_fields` VALUES('1959', '4', 'plugin_invoice_items_info', 'backend', 'Invoice plugin / Items information', 'plugin', '');
INSERT INTO `as_fields` VALUES('1960', '4', 'plugin_invoice_i_accept_paypal', 'backend', 'Invoice plugin / Accept payments with PayPal', 'plugin', '');
INSERT INTO `as_fields` VALUES('1961', '4', 'plugin_invoice_i_accept_authorize', 'backend', 'Invoice plugin / Accept payments with Authorize.NET', 'plugin', '');
INSERT INTO `as_fields` VALUES('1962', '4', 'plugin_invoice_i_accept_creditcard', 'backend', 'Invoice plugin / Accept payments with Credit Card', 'plugin', '');
INSERT INTO `as_fields` VALUES('1963', '4', 'plugin_invoice_i_accept_bank', 'backend', 'Invoice plugin / Accept payments with Bank Account', 'plugin', '');
INSERT INTO `as_fields` VALUES('1964', '4', 'plugin_invoice_i_s_include', 'backend', 'Invoice plugin / Include Shipping information', 'plugin', '');
INSERT INTO `as_fields` VALUES('1965', '4', 'plugin_invoice_i_s_shipping_address', 'backend', 'Invoice plugin / Include Shipping address', 'plugin', '');
INSERT INTO `as_fields` VALUES('1966', '4', 'plugin_invoice_i_s_company', 'backend', 'Invoice plugin / Include Company', 'plugin', '');
INSERT INTO `as_fields` VALUES('1967', '4', 'plugin_invoice_i_s_name', 'backend', 'Invoice plugin / Include Name', 'plugin', '');
INSERT INTO `as_fields` VALUES('1968', '4', 'plugin_invoice_i_s_address', 'backend', 'Invoice plugin / Include Address', 'plugin', '');
INSERT INTO `as_fields` VALUES('1969', '4', 'plugin_invoice_i_s_city', 'backend', 'Invoice plugin / Include City', 'plugin', '');
INSERT INTO `as_fields` VALUES('1970', '4', 'plugin_invoice_i_s_state', 'backend', 'Invoice plugin / Include State', 'plugin', '');
INSERT INTO `as_fields` VALUES('1971', '4', 'plugin_invoice_i_s_zip', 'backend', 'Invoice plugin / Include Zip', 'plugin', '');
INSERT INTO `as_fields` VALUES('1972', '4', 'plugin_invoice_i_s_phone', 'backend', 'Invoice plugin / Include Phone', 'plugin', '');
INSERT INTO `as_fields` VALUES('1973', '4', 'plugin_invoice_i_s_fax', 'backend', 'Invoice plugin / Include Fax', 'plugin', '');
INSERT INTO `as_fields` VALUES('1974', '4', 'plugin_invoice_i_s_email', 'backend', 'Invoice plugin / Include Email', 'plugin', '');
INSERT INTO `as_fields` VALUES('1975', '4', 'plugin_invoice_i_s_url', 'backend', 'Invoice plugin / Include Website', 'plugin', '');
INSERT INTO `as_fields` VALUES('1976', '4', 'plugin_invoice_i_s_street_address', 'backend', 'Invoice plugin / Include Street address', 'plugin', '');
INSERT INTO `as_fields` VALUES('1977', '4', 'error_titles_ARRAY_PIN05', 'arrays', 'Invoice plugin / Invoice updated title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1978', '4', 'error_bodies_ARRAY_PIN05', 'arrays', 'Invoice plugin / Invoice updated body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1979', '4', 'error_titles_ARRAY_PIN04', 'arrays', 'Invoice plugin / Invoice Not Found title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1980', '4', 'error_bodies_ARRAY_PIN04', 'arrays', 'Invoice plugin / Invoice Not Found body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1981', '4', 'error_titles_ARRAY_PIN06', 'arrays', 'Invoice plugin / Invalid data title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1982', '4', 'error_bodies_ARRAY_PIN06', 'arrays', 'Invoice plugin / Invalid data body', 'plugin', '');
INSERT INTO `as_fields` VALUES('1983', '4', 'plugin_invoice_i_status', 'backend', 'Invoice plugin / Status', 'plugin', '');
INSERT INTO `as_fields` VALUES('1984', '4', 'plugin_invoice_pay_with_paypal', 'backend', 'Invoice plugin / Pay with Paypal', 'plugin', '');
INSERT INTO `as_fields` VALUES('1985', '4', 'plugin_invoice_pay_with_authorize', 'backend', 'Invoice plugin / Pay with Authorize.Net', 'plugin', '');
INSERT INTO `as_fields` VALUES('1986', '4', 'plugin_invoice_pay_with_creditcard', 'backend', 'Invoice plugin / Pay with Credit Card', 'plugin', '');
INSERT INTO `as_fields` VALUES('1987', '4', 'plugin_invoice_pay_with_bank', 'backend', 'Invoice plugin / Pay with Bank Account', 'plugin', '');
INSERT INTO `as_fields` VALUES('1988', '4', 'plugin_invoice_pay_now', 'backend', 'Invoice plugin / Pay Now', 'plugin', '');
INSERT INTO `as_fields` VALUES('1989', '4', 'plugin_invoice_paypal_title', 'frontend', 'Invoice plugin / Paypal title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1990', '4', 'plugin_invoice_authorize_title', 'frontend', 'Invoice plugin / Payment Authorize title', 'plugin', '');
INSERT INTO `as_fields` VALUES('1991', '4', 'plugin_invoice_i_paypal_address', 'backend', 'Invoice plugin / Paypal address', 'plugin', '');
INSERT INTO `as_fields` VALUES('1992', '4', 'plugin_invoice_i_authorize_tz', 'backend', 'Invoice plugin / Authorize.Net Timezone', 'plugin', '');
INSERT INTO `as_fields` VALUES('1993', '4', 'plugin_invoice_i_authorize_mid', 'backend', 'Invoice plugin / Authorize.Net Merchant ID', 'plugin', '');
INSERT INTO `as_fields` VALUES('1994', '4', 'plugin_invoice_i_authorize_key', 'backend', 'Invoice plugin / Authorize.Net Transaction Key', 'plugin', '');
INSERT INTO `as_fields` VALUES('1995', '4', 'plugin_invoice_i_bank_account', 'backend', 'Invoice plugin / Bank Account', 'plugin', '');
INSERT INTO `as_fields` VALUES('1996', '4', 'plugin_invoice_paypal_redirect', 'backend', 'Invoice plugin / Paypal redirect', 'plugin', '');
INSERT INTO `as_fields` VALUES('1997', '4', 'plugin_invoice_authorize_redirect', 'backend', 'Invoice plugin / Authorize.Net redirect', 'plugin', '');
INSERT INTO `as_fields` VALUES('1998', '4', 'plugin_invoice_paypal_proceed', 'backend', 'Invoice plugin / Paypal proceed button', 'plugin', '');
INSERT INTO `as_fields` VALUES('1999', '4', 'plugin_invoice_authorize_proceed', 'backend', 'Invoice plugin / Authorize.Net proceed button', 'plugin', '');
INSERT INTO `as_fields` VALUES('2000', '4', 'plugin_invoice_i_delete_title', 'backend', 'Invoice plugin / Delete title', 'plugin', '');
INSERT INTO `as_fields` VALUES('2001', '4', 'plugin_invoice_i_delete_body', 'backend', 'Invoice plugin / Delete body', 'plugin', '');
INSERT INTO `as_fields` VALUES('2002', '4', 'plugin_invoice_i_is_shipped', 'backend', 'Invoice plugin / Is shipped', 'plugin', '');
INSERT INTO `as_fields` VALUES('2003', '4', 'plugin_invoice_i_s_date', 'backend', 'Invoice plugin / Include Shipping date', 'plugin', '');
INSERT INTO `as_fields` VALUES('2004', '4', 'plugin_invoice_i_s_terms', 'backend', 'Invoice plugin / Include Shipping terms', 'plugin', '');
INSERT INTO `as_fields` VALUES('2005', '4', 'plugin_invoice_i_s_is_shipped', 'backend', 'Invoice plugin / Include Is Shipped', 'plugin', '');
INSERT INTO `as_fields` VALUES('2006', '4', 'plugin_invoice_statuses_ARRAY_not_paid', 'arrays', 'Invoice plugin / Status: Not Paid', 'plugin', '');
INSERT INTO `as_fields` VALUES('2007', '4', 'plugin_invoice_statuses_ARRAY_paid', 'arrays', 'Invoice plugin / Status: Paid', 'plugin', '');
INSERT INTO `as_fields` VALUES('2008', '4', 'plugin_invoice_statuses_ARRAY_cancelled', 'arrays', 'Invoice plugin / Status: Cancelled', 'plugin', '');
INSERT INTO `as_fields` VALUES('2009', '4', 'plugin_invoice_i_num', 'backend', 'Invoice plugin / No.', 'plugin', '');
INSERT INTO `as_fields` VALUES('2010', '4', 'plugin_invoice_add', 'backend', 'Invoice plugin / Add', 'plugin', '');
INSERT INTO `as_fields` VALUES('2011', '4', 'plugin_invoice_save', 'backend', 'Invoice plugin / Save', 'plugin', '');
INSERT INTO `as_fields` VALUES('2012', '4', 'plugin_invoice_i_booking_url', 'backend', 'Invoice plugin / Booking URL - Token: {ORDER_ID}', 'plugin', '');
INSERT INTO `as_fields` VALUES('2013', '4', 'plugin_invoice_i_s_shipping', 'backend', 'Invoice plugin / Include Shipping', 'plugin', '');
INSERT INTO `as_fields` VALUES('2014', '4', 'error_titles_ARRAY_PIN07', 'arrays', 'Invoice plugin / Invoice added title', 'plugin', '');
INSERT INTO `as_fields` VALUES('2015', '4', 'error_bodies_ARRAY_PIN07', 'arrays', 'Invoice plugin / Invoice added body', 'plugin', '');
INSERT INTO `as_fields` VALUES('2016', '4', 'error_titles_ARRAY_PIN08', 'arrays', 'Invoice plugin / Invoice failed to add title', 'plugin', '');
INSERT INTO `as_fields` VALUES('2017', '4', 'error_bodies_ARRAY_PIN08', 'arrays', 'Invoice plugin / Invoice failed to add body', 'plugin', '');
INSERT INTO `as_fields` VALUES('2018', '4', 'error_titles_ARRAY_PIN09', 'arrays', 'Invoice plugin / Invoice Send title', 'plugin', '');
INSERT INTO `as_fields` VALUES('2019', '4', 'error_bodies_ARRAY_PIN09', 'arrays', 'Invoice plugin / Invoice send body', 'plugin', '');
INSERT INTO `as_fields` VALUES('2020', '4', 'error_titles_ARRAY_PIN10', 'arrays', 'Invoice plugin / Invoice heading title', 'plugin', '');
INSERT INTO `as_fields` VALUES('2021', '4', 'error_bodies_ARRAY_PIN10', 'arrays', 'Invoice plugin / Invoice heading body', 'plugin', '');
INSERT INTO `as_fields` VALUES('2022', '4', 'error_titles_ARRAY_PIN11', 'arrays', 'Invoice plugin / Invoice billing title', 'plugin', '');
INSERT INTO `as_fields` VALUES('2023', '4', 'error_bodies_ARRAY_PIN11', 'arrays', 'Invoice plugin / Invoice billing body', 'plugin', '');
INSERT INTO `as_fields` VALUES('2024', '4', 'plugin_invoice_i_qty_is_int', 'backend', 'Invoice plugin / Quantity format', 'plugin', '');
INSERT INTO `as_fields` VALUES('2025', '4', 'plugin_invoice_i_qty_int', 'backend', 'Invoice plugin / Quantity INT instead of FLOAT', 'plugin', '');
INSERT INTO `as_fields` VALUES('2026', '4', 'plugin_invoice_i_authorize_hash', 'backend', 'Invoice plugin / Authorize.Net hash value', 'plugin', '');
INSERT INTO `as_fields` VALUES('2027', '4', 'plugin_sms_menu_sms', 'backend', 'SMS plugin / Menu SMS', 'plugin', '');
INSERT INTO `as_fields` VALUES('2028', '4', 'plugin_sms_config', 'backend', 'SMS plugin / SMS config', 'plugin', '');
INSERT INTO `as_fields` VALUES('2029', '4', 'plugin_sms_number', 'backend', 'SMS plugin / Number', 'plugin', '');
INSERT INTO `as_fields` VALUES('2030', '4', 'plugin_sms_text', 'backend', 'SMS plugin / Text', 'plugin', '');
INSERT INTO `as_fields` VALUES('2031', '4', 'plugin_sms_status', 'backend', 'SMS plugin / Status', 'plugin', '');
INSERT INTO `as_fields` VALUES('2032', '4', 'plugin_sms_created', 'backend', 'SMS plugin / Date & Time', 'plugin', '');
INSERT INTO `as_fields` VALUES('2033', '4', 'plugin_sms_api', 'backend', 'SMS plugin / API Key', 'plugin', '');
INSERT INTO `as_fields` VALUES('2034', '4', 'error_titles_ARRAY_PSS01', 'arrays', 'SMS plugin / Info title', 'plugin', '');
INSERT INTO `as_fields` VALUES('2035', '4', 'error_bodies_ARRAY_PSS01', 'arrays', 'SMS plugin / Info body', 'plugin', '');
INSERT INTO `as_fields` VALUES('2036', '4', 'error_titles_ARRAY_PSS02', 'arrays', 'SMS plugin / API key updates info title', 'plugin', '');
INSERT INTO `as_fields` VALUES('2037', '4', 'error_bodies_ARRAY_PSS02', 'arrays', 'SMS plugin / API key updates info body', 'plugin', '');
INSERT INTO `as_fields` VALUES('2038', '4', 'opt_o_layout_backend', 'backend', 'Option/ Layout Backend', 'script', '');
INSERT INTO `as_fields` VALUES('2039', '4', 'opt_o_custom_status', 'backend', 'Option/ Custom Status', 'script', '');

DROP TABLE IF EXISTS `as_formstyle`;

CREATE TABLE `as_formstyle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `banner` varchar(250) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `background` varchar(250) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_formstyle_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `as_formstyle` VALUES('6', '4', 'http://ajanvaraus-kodinterra.fi/nokia/wp-content/plugins/AppointmentScheduler-22-4-2014/library/app/web/img/backend/logo.png', 'http://www.sourcevl.com/appointment/wp-content/uploads/sites/2/2014/06/banner.jpg', '', '#444', '', '');

DROP TABLE IF EXISTS `as_multi_lang`;

CREATE TABLE `as_multi_lang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `locale` tinyint(3) unsigned DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `content` text,
  `source` enum('script','plugin','data') DEFAULT 'script',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_OWNER_FIELD` (`owner_id`,`foreign_id`,`field`,`model`,`source`),
  CONSTRAINT `as_multi_lang_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4371 DEFAULT CHARSET=utf8;

INSERT INTO `as_multi_lang` VALUES('1', '4', '5', 'pjField', '1', 'title', 'Username', 'script');
INSERT INTO `as_multi_lang` VALUES('2', '4', '6', 'pjField', '1', 'title', 'Máº­t kháº©u', 'script');
INSERT INTO `as_multi_lang` VALUES('3', '4', '7', 'pjField', '1', 'title', 'Email', 'script');
INSERT INTO `as_multi_lang` VALUES('4', '4', '8', 'pjField', '1', 'title', 'URL', 'script');
INSERT INTO `as_multi_lang` VALUES('5', '4', '13', 'pjField', '1', 'title', 'DateTime', 'script');
INSERT INTO `as_multi_lang` VALUES('6', '4', '16', 'pjField', '1', 'title', 'Save', 'script');
INSERT INTO `as_multi_lang` VALUES('7', '4', '17', 'pjField', '1', 'title', 'Reset', 'script');
INSERT INTO `as_multi_lang` VALUES('8', '4', '18', 'pjField', '1', 'title', 'Add language', 'script');
INSERT INTO `as_multi_lang` VALUES('9', '4', '22', 'pjField', '1', 'title', 'Multi Lang', 'script');
INSERT INTO `as_multi_lang` VALUES('10', '4', '23', 'pjField', '1', 'title', 'Plugins', 'script');
INSERT INTO `as_multi_lang` VALUES('11', '4', '24', 'pjField', '1', 'title', 'Users', 'script');
INSERT INTO `as_multi_lang` VALUES('12', '4', '25', 'pjField', '1', 'title', 'Options', 'script');
INSERT INTO `as_multi_lang` VALUES('13', '4', '26', 'pjField', '1', 'title', 'Logout', 'script');
INSERT INTO `as_multi_lang` VALUES('14', '4', '31', 'pjField', '1', 'title', 'Update', 'script');
INSERT INTO `as_multi_lang` VALUES('15', '4', '36', 'pjField', '1', 'title', 'Choose', 'script');
INSERT INTO `as_multi_lang` VALUES('16', '4', '37', 'pjField', '1', 'title', 'Search', 'script');
INSERT INTO `as_multi_lang` VALUES('17', '4', '40', 'pjField', '1', 'title', 'Back-end titles', 'script');
INSERT INTO `as_multi_lang` VALUES('18', '4', '41', 'pjField', '1', 'title', 'Front-end titles', 'script');
INSERT INTO `as_multi_lang` VALUES('19', '4', '42', 'pjField', '1', 'title', 'Languages', 'script');
INSERT INTO `as_multi_lang` VALUES('20', '4', '44', 'pjField', '1', 'title', 'Admin Login', 'script');
INSERT INTO `as_multi_lang` VALUES('21', '4', '45', 'pjField', '1', 'title', 'Login', 'script');
INSERT INTO `as_multi_lang` VALUES('22', '4', '47', 'pjField', '1', 'title', 'Dashboard', 'script');
INSERT INTO `as_multi_lang` VALUES('23', '4', '57', 'pjField', '1', 'title', 'Option list', 'script');
INSERT INTO `as_multi_lang` VALUES('24', '4', '58', 'pjField', '1', 'title', 'Add +', 'script');
INSERT INTO `as_multi_lang` VALUES('25', '4', '62', 'pjField', '1', 'title', 'Delete', 'script');
INSERT INTO `as_multi_lang` VALUES('26', '4', '65', 'pjField', '1', 'title', 'Type', 'script');
INSERT INTO `as_multi_lang` VALUES('27', '4', '66', 'pjField', '1', 'title', 'Name', 'script');
INSERT INTO `as_multi_lang` VALUES('28', '4', '67', 'pjField', '1', 'title', 'Role', 'script');
INSERT INTO `as_multi_lang` VALUES('29', '4', '68', 'pjField', '1', 'title', 'Status', 'script');
INSERT INTO `as_multi_lang` VALUES('30', '4', '69', 'pjField', '1', 'title', 'Is confirmed', 'script');
INSERT INTO `as_multi_lang` VALUES('31', '4', '70', 'pjField', '1', 'title', 'Update user', 'script');
INSERT INTO `as_multi_lang` VALUES('32', '4', '71', 'pjField', '1', 'title', 'Add user', 'script');
INSERT INTO `as_multi_lang` VALUES('33', '4', '72', 'pjField', '1', 'title', 'Value', 'script');
INSERT INTO `as_multi_lang` VALUES('34', '4', '73', 'pjField', '1', 'title', 'Option', 'script');
INSERT INTO `as_multi_lang` VALUES('35', '4', '74', 'pjField', '1', 'title', 'days', 'script');
INSERT INTO `as_multi_lang` VALUES('36', '4', '115', 'pjField', '1', 'title', 'Languages', 'script');
INSERT INTO `as_multi_lang` VALUES('73', '4', '116', 'pjField', '1', 'title', 'Yes', 'script');
INSERT INTO `as_multi_lang` VALUES('75', '4', '117', 'pjField', '1', 'title', 'No', 'script');
INSERT INTO `as_multi_lang` VALUES('77', '4', '338', 'pjField', '1', 'title', 'Error', 'script');
INSERT INTO `as_multi_lang` VALUES('79', '4', '347', 'pjField', '1', 'title', '&laquo; Back', 'script');
INSERT INTO `as_multi_lang` VALUES('81', '4', '355', 'pjField', '1', 'title', 'Cancel', 'script');
INSERT INTO `as_multi_lang` VALUES('83', '4', '356', 'pjField', '1', 'title', 'Forgot password', 'script');
INSERT INTO `as_multi_lang` VALUES('85', '4', '357', 'pjField', '1', 'title', 'Password reminder', 'script');
INSERT INTO `as_multi_lang` VALUES('87', '4', '358', 'pjField', '1', 'title', 'Send', 'script');
INSERT INTO `as_multi_lang` VALUES('89', '4', '359', 'pjField', '1', 'title', 'Password reminder', 'script');
INSERT INTO `as_multi_lang` VALUES('91', '4', '360', 'pjField', '1', 'title', 'Dear {Name},Your password: {Password}', 'script');
INSERT INTO `as_multi_lang` VALUES('93', '4', '365', 'pjField', '1', 'title', 'Profile', 'script');
INSERT INTO `as_multi_lang` VALUES('95', '4', '380', 'pjField', '1', 'title', 'Languages Title', 'script');
INSERT INTO `as_multi_lang` VALUES('97', '4', '381', 'pjField', '1', 'title', 'Languages Body', 'script');
INSERT INTO `as_multi_lang` VALUES('99', '4', '382', 'pjField', '1', 'title', 'Languages Backend Title', 'script');
INSERT INTO `as_multi_lang` VALUES('101', '4', '383', 'pjField', '1', 'title', 'Languages Backend Body', 'script');
INSERT INTO `as_multi_lang` VALUES('103', '4', '384', 'pjField', '1', 'title', 'Languages Frontend Title', 'script');
INSERT INTO `as_multi_lang` VALUES('105', '4', '385', 'pjField', '1', 'title', 'Languages Frontend Body', 'script');
INSERT INTO `as_multi_lang` VALUES('107', '4', '386', 'pjField', '1', 'title', 'Listing Prices Title', 'script');
INSERT INTO `as_multi_lang` VALUES('109', '4', '387', 'pjField', '1', 'title', 'Listing Prices Body', 'script');
INSERT INTO `as_multi_lang` VALUES('111', '4', '388', 'pjField', '1', 'title', 'Listing Bookings Title', 'script');
INSERT INTO `as_multi_lang` VALUES('113', '4', '389', 'pjField', '1', 'title', 'Listing Bookings Body', 'script');
INSERT INTO `as_multi_lang` VALUES('115', '4', '390', 'pjField', '1', 'title', 'Listing Contact Title', 'script');
INSERT INTO `as_multi_lang` VALUES('117', '4', '391', 'pjField', '1', 'title', 'Listing Contact Body', 'script');
INSERT INTO `as_multi_lang` VALUES('119', '4', '392', 'pjField', '1', 'title', 'Listing Address Title', 'script');
INSERT INTO `as_multi_lang` VALUES('121', '4', '393', 'pjField', '1', 'title', 'Listing Address Body', 'script');
INSERT INTO `as_multi_lang` VALUES('123', '4', '395', 'pjField', '1', 'title', 'Extend exp.date Title', 'script');
INSERT INTO `as_multi_lang` VALUES('125', '4', '396', 'pjField', '1', 'title', 'Extend exp.date Body', 'script');
INSERT INTO `as_multi_lang` VALUES('127', '4', '408', 'pjField', '1', 'title', 'Backup', 'script');
INSERT INTO `as_multi_lang` VALUES('129', '4', '409', 'pjField', '1', 'title', 'Backup', 'script');
INSERT INTO `as_multi_lang` VALUES('131', '4', '410', 'pjField', '1', 'title', 'Backup database', 'script');
INSERT INTO `as_multi_lang` VALUES('133', '4', '411', 'pjField', '1', 'title', 'Backup files', 'script');
INSERT INTO `as_multi_lang` VALUES('135', '4', '412', 'pjField', '1', 'title', 'Choose Action', 'script');
INSERT INTO `as_multi_lang` VALUES('137', '4', '413', 'pjField', '1', 'title', 'Go to page:', 'script');
INSERT INTO `as_multi_lang` VALUES('139', '4', '414', 'pjField', '1', 'title', 'Total items:', 'script');
INSERT INTO `as_multi_lang` VALUES('141', '4', '415', 'pjField', '1', 'title', 'Items per page', 'script');
INSERT INTO `as_multi_lang` VALUES('143', '4', '416', 'pjField', '1', 'title', 'Prev page', 'script');
INSERT INTO `as_multi_lang` VALUES('145', '4', '417', 'pjField', '1', 'title', '&laquo; Prev', 'script');
INSERT INTO `as_multi_lang` VALUES('147', '4', '418', 'pjField', '1', 'title', 'Next page', 'script');
INSERT INTO `as_multi_lang` VALUES('149', '4', '419', 'pjField', '1', 'title', 'Next &raquo;', 'script');
INSERT INTO `as_multi_lang` VALUES('151', '4', '420', 'pjField', '1', 'title', 'Delete confirmation', 'script');
INSERT INTO `as_multi_lang` VALUES('153', '4', '421', 'pjField', '1', 'title', 'Are you sure you want to delete selected record?', 'script');
INSERT INTO `as_multi_lang` VALUES('155', '4', '422', 'pjField', '1', 'title', 'Action confirmation', 'script');
INSERT INTO `as_multi_lang` VALUES('157', '4', '423', 'pjField', '1', 'title', 'OK', 'script');
INSERT INTO `as_multi_lang` VALUES('159', '4', '424', 'pjField', '1', 'title', 'Cancel', 'script');
INSERT INTO `as_multi_lang` VALUES('161', '4', '425', 'pjField', '1', 'title', 'Delete', 'script');
INSERT INTO `as_multi_lang` VALUES('163', '4', '426', 'pjField', '1', 'title', 'No records found', 'script');
INSERT INTO `as_multi_lang` VALUES('165', '4', '433', 'pjField', '1', 'title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'script');
INSERT INTO `as_multi_lang` VALUES('167', '4', '434', 'pjField', '1', 'title', 'IP address', 'script');
INSERT INTO `as_multi_lang` VALUES('169', '4', '435', 'pjField', '1', 'title', 'Registration date/time', 'script');
INSERT INTO `as_multi_lang` VALUES('171', '4', '441', 'pjField', '1', 'title', 'Currency', 'script');
INSERT INTO `as_multi_lang` VALUES('173', '4', '442', 'pjField', '1', 'title', 'Date format', 'script');
INSERT INTO `as_multi_lang` VALUES('175', '4', '451', 'pjField', '1', 'title', 'Timezone', 'script');
INSERT INTO `as_multi_lang` VALUES('177', '4', '452', 'pjField', '1', 'title', 'First day of the week', 'script');
INSERT INTO `as_multi_lang` VALUES('179', '4', '455', 'pjField', '1', 'title', 'Active', 'script');
INSERT INTO `as_multi_lang` VALUES('180', '4', '456', 'pjField', '1', 'title', 'Inactive', 'script');
INSERT INTO `as_multi_lang` VALUES('181', '4', '457', 'pjField', '1', 'title', 'Active', 'script');
INSERT INTO `as_multi_lang` VALUES('182', '4', '458', 'pjField', '1', 'title', 'Inactive', 'script');
INSERT INTO `as_multi_lang` VALUES('183', '4', '471', 'pjField', '1', 'title', 'Yes', 'script');
INSERT INTO `as_multi_lang` VALUES('184', '4', '472', 'pjField', '1', 'title', 'No', 'script');
INSERT INTO `as_multi_lang` VALUES('185', '4', '476', 'pjField', '1', 'title', 'Mr.', 'script');
INSERT INTO `as_multi_lang` VALUES('186', '4', '477', 'pjField', '1', 'title', 'Mrs.', 'script');
INSERT INTO `as_multi_lang` VALUES('187', '4', '478', 'pjField', '1', 'title', 'Miss', 'script');
INSERT INTO `as_multi_lang` VALUES('188', '4', '479', 'pjField', '1', 'title', 'Ms.', 'script');
INSERT INTO `as_multi_lang` VALUES('189', '4', '480', 'pjField', '1', 'title', 'Dr.', 'script');
INSERT INTO `as_multi_lang` VALUES('190', '4', '481', 'pjField', '1', 'title', 'Prof.', 'script');
INSERT INTO `as_multi_lang` VALUES('191', '4', '482', 'pjField', '1', 'title', 'Rev.', 'script');
INSERT INTO `as_multi_lang` VALUES('192', '4', '483', 'pjField', '1', 'title', 'Other', 'script');
INSERT INTO `as_multi_lang` VALUES('193', '4', '496', 'pjField', '1', 'title', 'GMT-12:00', 'script');
INSERT INTO `as_multi_lang` VALUES('194', '4', '497', 'pjField', '1', 'title', 'GMT-11:00', 'script');
INSERT INTO `as_multi_lang` VALUES('195', '4', '498', 'pjField', '1', 'title', 'GMT-10:00', 'script');
INSERT INTO `as_multi_lang` VALUES('196', '4', '499', 'pjField', '1', 'title', 'GMT-09:00', 'script');
INSERT INTO `as_multi_lang` VALUES('197', '4', '500', 'pjField', '1', 'title', 'GMT-08:00', 'script');
INSERT INTO `as_multi_lang` VALUES('198', '4', '501', 'pjField', '1', 'title', 'GMT-07:00', 'script');
INSERT INTO `as_multi_lang` VALUES('199', '4', '502', 'pjField', '1', 'title', 'GMT-06:00', 'script');
INSERT INTO `as_multi_lang` VALUES('200', '4', '503', 'pjField', '1', 'title', 'GMT-05:00', 'script');
INSERT INTO `as_multi_lang` VALUES('201', '4', '504', 'pjField', '1', 'title', 'GMT-04:00', 'script');
INSERT INTO `as_multi_lang` VALUES('202', '4', '505', 'pjField', '1', 'title', 'GMT-03:00', 'script');
INSERT INTO `as_multi_lang` VALUES('203', '4', '506', 'pjField', '1', 'title', 'GMT-02:00', 'script');
INSERT INTO `as_multi_lang` VALUES('204', '4', '507', 'pjField', '1', 'title', 'GMT-01:00', 'script');
INSERT INTO `as_multi_lang` VALUES('205', '4', '508', 'pjField', '1', 'title', 'GMT', 'script');
INSERT INTO `as_multi_lang` VALUES('206', '4', '509', 'pjField', '1', 'title', 'GMT+01:00', 'script');
INSERT INTO `as_multi_lang` VALUES('207', '4', '510', 'pjField', '1', 'title', 'GMT+02:00', 'script');
INSERT INTO `as_multi_lang` VALUES('208', '4', '511', 'pjField', '1', 'title', 'GMT+03:00', 'script');
INSERT INTO `as_multi_lang` VALUES('209', '4', '512', 'pjField', '1', 'title', 'GMT+04:00', 'script');
INSERT INTO `as_multi_lang` VALUES('210', '4', '513', 'pjField', '1', 'title', 'GMT+05:00', 'script');
INSERT INTO `as_multi_lang` VALUES('211', '4', '514', 'pjField', '1', 'title', 'GMT+06:00', 'script');
INSERT INTO `as_multi_lang` VALUES('212', '4', '515', 'pjField', '1', 'title', 'GMT+07:00', 'script');
INSERT INTO `as_multi_lang` VALUES('213', '4', '516', 'pjField', '1', 'title', 'GMT+08:00', 'script');
INSERT INTO `as_multi_lang` VALUES('214', '4', '517', 'pjField', '1', 'title', 'GMT+09:00', 'script');
INSERT INTO `as_multi_lang` VALUES('215', '4', '518', 'pjField', '1', 'title', 'GMT+10:00', 'script');
INSERT INTO `as_multi_lang` VALUES('216', '4', '519', 'pjField', '1', 'title', 'GMT+11:00', 'script');
INSERT INTO `as_multi_lang` VALUES('217', '4', '520', 'pjField', '1', 'title', 'GMT+12:00', 'script');
INSERT INTO `as_multi_lang` VALUES('218', '4', '521', 'pjField', '1', 'title', 'GMT+13:00', 'script');
INSERT INTO `as_multi_lang` VALUES('219', '4', '540', 'pjField', '1', 'title', 'User updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('220', '4', '541', 'pjField', '1', 'title', 'User added!', 'script');
INSERT INTO `as_multi_lang` VALUES('221', '4', '542', 'pjField', '1', 'title', 'User failed to add.', 'script');
INSERT INTO `as_multi_lang` VALUES('222', '4', '543', 'pjField', '1', 'title', 'User not found.', 'script');
INSERT INTO `as_multi_lang` VALUES('223', '4', '544', 'pjField', '1', 'title', 'Options updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('224', '4', '552', 'pjField', '1', 'title', 'Backup', 'script');
INSERT INTO `as_multi_lang` VALUES('225', '4', '553', 'pjField', '1', 'title', 'Backup complete!', 'script');
INSERT INTO `as_multi_lang` VALUES('226', '4', '554', 'pjField', '1', 'title', 'Backup failed!', 'script');
INSERT INTO `as_multi_lang` VALUES('227', '4', '555', 'pjField', '1', 'title', 'Backup failed!', 'script');
INSERT INTO `as_multi_lang` VALUES('228', '4', '556', 'pjField', '1', 'title', 'Account not found!', 'script');
INSERT INTO `as_multi_lang` VALUES('229', '4', '557', 'pjField', '1', 'title', 'Password send!', 'script');
INSERT INTO `as_multi_lang` VALUES('230', '4', '558', 'pjField', '1', 'title', 'Password not send!', 'script');
INSERT INTO `as_multi_lang` VALUES('231', '4', '559', 'pjField', '1', 'title', 'Profile updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('232', '4', '578', 'pjField', '1', 'title', 'All the changes made to this user have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('233', '4', '579', 'pjField', '1', 'title', 'All the changes made to this user have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('234', '4', '580', 'pjField', '1', 'title', 'We are sorry, but the user has not been added.', 'script');
INSERT INTO `as_multi_lang` VALUES('235', '4', '581', 'pjField', '1', 'title', 'User your looking for is missing.', 'script');
INSERT INTO `as_multi_lang` VALUES('236', '4', '582', 'pjField', '1', 'title', 'All the changes made to options have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('237', '4', '589', 'pjField', '1', 'title', 'All the changes made to titles have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('238', '4', '590', 'pjField', '1', 'title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at ligula non arcu dignissim pretium. Praesent in magna nulla, in porta leo.', 'script');
INSERT INTO `as_multi_lang` VALUES('239', '4', '591', 'pjField', '1', 'title', 'All backup files have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('240', '4', '592', 'pjField', '1', 'title', 'No option was selected.', 'script');
INSERT INTO `as_multi_lang` VALUES('241', '4', '593', 'pjField', '1', 'title', 'Backup not performed.', 'script');
INSERT INTO `as_multi_lang` VALUES('242', '4', '594', 'pjField', '1', 'title', 'Given email address is not associated with any account.', 'script');
INSERT INTO `as_multi_lang` VALUES('243', '4', '595', 'pjField', '1', 'title', 'For further instructions please check your mailbox.', 'script');
INSERT INTO `as_multi_lang` VALUES('244', '4', '596', 'pjField', '1', 'title', 'We''re sorry, please try again later.', 'script');
INSERT INTO `as_multi_lang` VALUES('245', '4', '597', 'pjField', '1', 'title', 'All the changes made to your profile have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('246', '4', '627', 'pjField', '1', 'title', 'January', 'script');
INSERT INTO `as_multi_lang` VALUES('247', '4', '628', 'pjField', '1', 'title', 'February', 'script');
INSERT INTO `as_multi_lang` VALUES('248', '4', '629', 'pjField', '1', 'title', 'March', 'script');
INSERT INTO `as_multi_lang` VALUES('249', '4', '630', 'pjField', '1', 'title', 'April', 'script');
INSERT INTO `as_multi_lang` VALUES('250', '4', '631', 'pjField', '1', 'title', 'May', 'script');
INSERT INTO `as_multi_lang` VALUES('251', '4', '632', 'pjField', '1', 'title', 'June', 'script');
INSERT INTO `as_multi_lang` VALUES('252', '4', '633', 'pjField', '1', 'title', 'July', 'script');
INSERT INTO `as_multi_lang` VALUES('253', '4', '634', 'pjField', '1', 'title', 'August', 'script');
INSERT INTO `as_multi_lang` VALUES('254', '4', '635', 'pjField', '1', 'title', 'September', 'script');
INSERT INTO `as_multi_lang` VALUES('255', '4', '636', 'pjField', '1', 'title', 'October', 'script');
INSERT INTO `as_multi_lang` VALUES('256', '4', '637', 'pjField', '1', 'title', 'November', 'script');
INSERT INTO `as_multi_lang` VALUES('257', '4', '638', 'pjField', '1', 'title', 'December', 'script');
INSERT INTO `as_multi_lang` VALUES('258', '4', '639', 'pjField', '1', 'title', 'Sunday', 'script');
INSERT INTO `as_multi_lang` VALUES('259', '4', '640', 'pjField', '1', 'title', 'Monday', 'script');
INSERT INTO `as_multi_lang` VALUES('260', '4', '641', 'pjField', '1', 'title', 'Tuesday', 'script');
INSERT INTO `as_multi_lang` VALUES('261', '4', '642', 'pjField', '1', 'title', 'Wednesday', 'script');
INSERT INTO `as_multi_lang` VALUES('262', '4', '643', 'pjField', '1', 'title', 'Thursday', 'script');
INSERT INTO `as_multi_lang` VALUES('263', '4', '644', 'pjField', '1', 'title', 'Friday', 'script');
INSERT INTO `as_multi_lang` VALUES('264', '4', '645', 'pjField', '1', 'title', 'Saturday', 'script');
INSERT INTO `as_multi_lang` VALUES('265', '4', '646', 'pjField', '1', 'title', 'S', 'script');
INSERT INTO `as_multi_lang` VALUES('266', '4', '647', 'pjField', '1', 'title', 'M', 'script');
INSERT INTO `as_multi_lang` VALUES('267', '4', '648', 'pjField', '1', 'title', 'T', 'script');
INSERT INTO `as_multi_lang` VALUES('268', '4', '649', 'pjField', '1', 'title', 'W', 'script');
INSERT INTO `as_multi_lang` VALUES('269', '4', '650', 'pjField', '1', 'title', 'T', 'script');
INSERT INTO `as_multi_lang` VALUES('270', '4', '651', 'pjField', '1', 'title', 'F', 'script');
INSERT INTO `as_multi_lang` VALUES('271', '4', '652', 'pjField', '1', 'title', 'S', 'script');
INSERT INTO `as_multi_lang` VALUES('272', '4', '653', 'pjField', '1', 'title', 'Jan', 'script');
INSERT INTO `as_multi_lang` VALUES('273', '4', '654', 'pjField', '1', 'title', 'Feb', 'script');
INSERT INTO `as_multi_lang` VALUES('274', '4', '655', 'pjField', '1', 'title', 'Mar', 'script');
INSERT INTO `as_multi_lang` VALUES('275', '4', '656', 'pjField', '1', 'title', 'Apr', 'script');
INSERT INTO `as_multi_lang` VALUES('276', '4', '657', 'pjField', '1', 'title', 'May', 'script');
INSERT INTO `as_multi_lang` VALUES('277', '4', '658', 'pjField', '1', 'title', 'Jun', 'script');
INSERT INTO `as_multi_lang` VALUES('278', '4', '659', 'pjField', '1', 'title', 'Jul', 'script');
INSERT INTO `as_multi_lang` VALUES('279', '4', '660', 'pjField', '1', 'title', 'Aug', 'script');
INSERT INTO `as_multi_lang` VALUES('280', '4', '661', 'pjField', '1', 'title', 'Sep', 'script');
INSERT INTO `as_multi_lang` VALUES('281', '4', '662', 'pjField', '1', 'title', 'Oct', 'script');
INSERT INTO `as_multi_lang` VALUES('282', '4', '663', 'pjField', '1', 'title', 'Nov', 'script');
INSERT INTO `as_multi_lang` VALUES('283', '4', '664', 'pjField', '1', 'title', 'Dec', 'script');
INSERT INTO `as_multi_lang` VALUES('284', '4', '665', 'pjField', '1', 'title', 'You are not loged in.', 'script');
INSERT INTO `as_multi_lang` VALUES('285', '4', '666', 'pjField', '1', 'title', 'Access denied. You have not requisite rights to.', 'script');
INSERT INTO `as_multi_lang` VALUES('286', '4', '667', 'pjField', '1', 'title', 'Empty resultset.', 'script');
INSERT INTO `as_multi_lang` VALUES('287', '4', '668', 'pjField', '1', 'title', 'The operation is not allowed in demo mode.', 'script');
INSERT INTO `as_multi_lang` VALUES('288', '4', '669', 'pjField', '1', 'title', 'Your hosting account does not allow uploading such a large image.', 'script');
INSERT INTO `as_multi_lang` VALUES('289', '4', '670', 'pjField', '1', 'title', 'No permisions to edit the property', 'script');
INSERT INTO `as_multi_lang` VALUES('290', '4', '671', 'pjField', '1', 'title', 'No permisions to edit the reservation', 'script');
INSERT INTO `as_multi_lang` VALUES('291', '4', '672', 'pjField', '1', 'title', 'No reservation found', 'script');
INSERT INTO `as_multi_lang` VALUES('292', '4', '673', 'pjField', '1', 'title', 'No property for the reservation found', 'script');
INSERT INTO `as_multi_lang` VALUES('293', '4', '674', 'pjField', '1', 'title', 'Your registration was successfull.', 'script');
INSERT INTO `as_multi_lang` VALUES('294', '4', '675', 'pjField', '1', 'title', 'Your registration was successfull. Your account needs to be approved.', 'script');
INSERT INTO `as_multi_lang` VALUES('295', '4', '676', 'pjField', '1', 'title', 'E-Mail address already exist', 'script');
INSERT INTO `as_multi_lang` VALUES('296', '4', '677', 'pjField', '1', 'title', 'Wrong username or password', 'script');
INSERT INTO `as_multi_lang` VALUES('297', '4', '678', 'pjField', '1', 'title', 'Access denied', 'script');
INSERT INTO `as_multi_lang` VALUES('298', '4', '679', 'pjField', '1', 'title', 'Account is disabled', 'script');
INSERT INTO `as_multi_lang` VALUES('419', '4', '907', 'pjField', '1', 'title', 'Arrays titles', 'script');
INSERT INTO `as_multi_lang` VALUES('421', '4', '908', 'pjField', '1', 'title', 'Languages Arrays Title', 'script');
INSERT INTO `as_multi_lang` VALUES('423', '4', '909', 'pjField', '1', 'title', 'Languages Array Body', 'script');
INSERT INTO `as_multi_lang` VALUES('425', '4', '910', 'pjField', '1', 'title', 'Back', 'script');
INSERT INTO `as_multi_lang` VALUES('427', '4', '982', 'pjField', '1', 'title', 'Order', 'script');
INSERT INTO `as_multi_lang` VALUES('429', '4', '983', 'pjField', '1', 'title', 'Is default', 'script');
INSERT INTO `as_multi_lang` VALUES('431', '4', '984', 'pjField', '1', 'title', 'Flag', 'script');
INSERT INTO `as_multi_lang` VALUES('433', '4', '985', 'pjField', '1', 'title', 'Title', 'script');
INSERT INTO `as_multi_lang` VALUES('435', '4', '986', 'pjField', '1', 'title', 'Delete', 'script');
INSERT INTO `as_multi_lang` VALUES('437', '4', '990', 'pjField', '1', 'title', 'Continue', 'script');
INSERT INTO `as_multi_lang` VALUES('439', '4', '992', 'pjField', '1', 'title', 'Email address is already in use', 'script');
INSERT INTO `as_multi_lang` VALUES('441', '4', '993', 'pjField', '1', 'title', 'Revert status', 'script');
INSERT INTO `as_multi_lang` VALUES('443', '4', '994', 'pjField', '1', 'title', 'Export', 'script');
INSERT INTO `as_multi_lang` VALUES('667', '4', '995', 'pjField', '1', 'title', 'Send email', 'script');
INSERT INTO `as_multi_lang` VALUES('670', '4', '996', 'pjField', '1', 'title', 'SMTP Host', 'script');
INSERT INTO `as_multi_lang` VALUES('673', '4', '997', 'pjField', '1', 'title', 'SMTP Port', 'script');
INSERT INTO `as_multi_lang` VALUES('676', '4', '998', 'pjField', '1', 'title', 'SMTP Username', 'script');
INSERT INTO `as_multi_lang` VALUES('679', '4', '999', 'pjField', '1', 'title', 'SMTP Password', 'script');
INSERT INTO `as_multi_lang` VALUES('1034', '4', '1053', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `as_multi_lang` VALUES('1035', '4', '1054', 'pjField', '1', 'title', 'Employees', 'script');
INSERT INTO `as_multi_lang` VALUES('1036', '4', '1055', 'pjField', '1', 'title', 'Add service', 'script');
INSERT INTO `as_multi_lang` VALUES('1037', '4', '1056', 'pjField', '1', 'title', 'All', 'script');
INSERT INTO `as_multi_lang` VALUES('1038', '4', '1057', 'pjField', '1', 'title', 'Service name', 'script');
INSERT INTO `as_multi_lang` VALUES('1039', '4', '1058', 'pjField', '1', 'title', 'Price', 'script');
INSERT INTO `as_multi_lang` VALUES('1040', '4', '1059', 'pjField', '1', 'title', 'Before (minutes)', 'script');
INSERT INTO `as_multi_lang` VALUES('1041', '4', '1060', 'pjField', '1', 'title', 'After (minutes)', 'script');
INSERT INTO `as_multi_lang` VALUES('1043', '4', '1061', 'pjField', '1', 'title', 'Total (minutes)', 'script');
INSERT INTO `as_multi_lang` VALUES('1044', '4', '1062', 'pjField', '1', 'title', 'Length (minutes)', 'script');
INSERT INTO `as_multi_lang` VALUES('1045', '4', '1063', 'pjField', '1', 'title', 'Service description', 'script');
INSERT INTO `as_multi_lang` VALUES('1046', '4', '1064', 'pjField', '1', 'title', 'Status', 'script');
INSERT INTO `as_multi_lang` VALUES('1047', '4', '1065', 'pjField', '1', 'title', 'Employees', 'script');
INSERT INTO `as_multi_lang` VALUES('1048', '4', '1066', 'pjField', '1', 'title', 'Update service', 'script');
INSERT INTO `as_multi_lang` VALUES('1049', '4', '1067', 'pjField', '1', 'title', 'Active', 'script');
INSERT INTO `as_multi_lang` VALUES('1051', '4', '1068', 'pjField', '1', 'title', 'Inactive', 'script');
INSERT INTO `as_multi_lang` VALUES('1063', '4', '1069', 'pjField', '1', 'title', 'Delete selected', 'script');
INSERT INTO `as_multi_lang` VALUES('1064', '4', '1070', 'pjField', '1', 'title', 'Are you sure you want to delete selected records?', 'script');
INSERT INTO `as_multi_lang` VALUES('1066', '4', '1071', 'pjField', '1', 'title', 'Service your are looking for is missing.', 'script');
INSERT INTO `as_multi_lang` VALUES('1067', '4', '1072', 'pjField', '1', 'title', 'Service updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1068', '4', '1073', 'pjField', '1', 'title', 'Service added!', 'script');
INSERT INTO `as_multi_lang` VALUES('1069', '4', '1074', 'pjField', '1', 'title', 'Service failed to add.', 'script');
INSERT INTO `as_multi_lang` VALUES('1070', '4', '1075', 'pjField', '1', 'title', 'Service not found.', 'script');
INSERT INTO `as_multi_lang` VALUES('1071', '4', '1076', 'pjField', '1', 'title', 'All the changes made to this service have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1072', '4', '1077', 'pjField', '1', 'title', 'All the changes made to this service have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1073', '4', '1078', 'pjField', '1', 'title', 'We are sorry, but the service has not been added.', 'script');
INSERT INTO `as_multi_lang` VALUES('1078', '4', '1079', 'pjField', '1', 'title', 'Add a service', 'script');
INSERT INTO `as_multi_lang` VALUES('1079', '4', '1080', 'pjField', '1', 'title', 'Fill in the form below to add a new service. You can add title, description, price, length and employees who do this service.', 'script');
INSERT INTO `as_multi_lang` VALUES('1080', '4', '1081', 'pjField', '1', 'title', 'Use the form below to modify the service. You can change title, description, price, length and employees who do this service.', 'script');
INSERT INTO `as_multi_lang` VALUES('1081', '4', '1082', 'pjField', '1', 'title', 'Update a service', 'script');
INSERT INTO `as_multi_lang` VALUES('1084', '4', '1083', 'pjField', '1', 'title', 'Add employee', 'script');
INSERT INTO `as_multi_lang` VALUES('1085', '4', '1084', 'pjField', '1', 'title', 'Employee name', 'script');
INSERT INTO `as_multi_lang` VALUES('1086', '4', '1085', 'pjField', '1', 'title', 'Email', 'script');
INSERT INTO `as_multi_lang` VALUES('1087', '4', '1086', 'pjField', '1', 'title', 'Phone', 'script');
INSERT INTO `as_multi_lang` VALUES('1088', '4', '1087', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `as_multi_lang` VALUES('1089', '4', '1088', 'pjField', '1', 'title', 'Status', 'script');
INSERT INTO `as_multi_lang` VALUES('1093', '4', '1089', 'pjField', '1', 'title', 'Update employee', 'script');
INSERT INTO `as_multi_lang` VALUES('1095', '4', '1090', 'pjField', '1', 'title', 'Add an employee', 'script');
INSERT INTO `as_multi_lang` VALUES('1096', '4', '1091', 'pjField', '1', 'title', 'Update an employee', 'script');
INSERT INTO `as_multi_lang` VALUES('1097', '4', '1092', 'pjField', '1', 'title', 'Use the form below to update employee''s details. You can select the service that this employee does. You can also configure it so an email and/or sms notification is sent to the employee when a booking is made. Each employee can access the Appointment Scheduler and manage his/her bookings only.', 'script');
INSERT INTO `as_multi_lang` VALUES('1098', '4', '1093', 'pjField', '1', 'title', 'Fill in the form below to add a new employee. You can select the service that this employee does. You can also configure it so an email and/or sms notification is sent to the employee when a booking is made. Each employee can access the Appointment Scheduler and manage his/her bookings only.', 'script');
INSERT INTO `as_multi_lang` VALUES('1101', '4', '1094', 'pjField', '1', 'title', 'Notes', 'script');
INSERT INTO `as_multi_lang` VALUES('1102', '4', '1095', 'pjField', '1', 'title', 'Send email when new booking is made', 'script');
INSERT INTO `as_multi_lang` VALUES('1104', '4', '1096', 'pjField', '1', 'title', 'Password', 'script');
INSERT INTO `as_multi_lang` VALUES('1109', '4', '1098', 'pjField', '1', 'title', 'All the changes made to this employee have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1110', '4', '1099', 'pjField', '1', 'title', 'Employee updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1112', '4', '1100', 'pjField', '1', 'title', 'Employee added!', 'script');
INSERT INTO `as_multi_lang` VALUES('1113', '4', '1101', 'pjField', '1', 'title', 'All the changes made to this employee have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1114', '4', '1102', 'pjField', '1', 'title', 'Employee your are looking for is missing.', 'script');
INSERT INTO `as_multi_lang` VALUES('1115', '4', '1103', 'pjField', '1', 'title', 'Employee not found.', 'script');
INSERT INTO `as_multi_lang` VALUES('1116', '4', '1104', 'pjField', '1', 'title', 'Employee failed to add.', 'script');
INSERT INTO `as_multi_lang` VALUES('1117', '4', '1105', 'pjField', '1', 'title', 'We are sorry, but the employee has not been added.', 'script');
INSERT INTO `as_multi_lang` VALUES('1118', '4', '1106', 'pjField', '1', 'title', 'Last login', 'script');
INSERT INTO `as_multi_lang` VALUES('1119', '4', '1107', 'pjField', '1', 'title', 'Working Time', 'script');
INSERT INTO `as_multi_lang` VALUES('1121', '4', '1108', 'pjField', '1', 'title', 'Working Time updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1122', '4', '1109', 'pjField', '1', 'title', 'All the changes made to working time have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1123', '4', '1110', 'pjField', '1', 'title', 'Custom Working Time saved!', 'script');
INSERT INTO `as_multi_lang` VALUES('1124', '4', '1111', 'pjField', '1', 'title', 'All the changes made to custom working time have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1125', '4', '1112', 'pjField', '1', 'title', 'Custom Working Time updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1126', '4', '1113', 'pjField', '1', 'title', 'All the changes made to custom working time have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1127', '4', '1114', 'pjField', '1', 'title', 'Working Time', 'script');
INSERT INTO `as_multi_lang` VALUES('1128', '4', '1115', 'pjField', '1', 'title', 'Different working time can be set for each day of the week. You can also set days off and a lunch break. Under Edit Employee page you can set up custom working time for each of your employees.', 'script');
INSERT INTO `as_multi_lang` VALUES('1129', '4', '1116', 'pjField', '1', 'title', 'Update custom', 'script');
INSERT INTO `as_multi_lang` VALUES('1130', '4', '1117', 'pjField', '1', 'title', 'Default', 'script');
INSERT INTO `as_multi_lang` VALUES('1131', '4', '1118', 'pjField', '1', 'title', 'Custom', 'script');
INSERT INTO `as_multi_lang` VALUES('1132', '4', '1119', 'pjField', '1', 'title', 'Day of week', 'script');
INSERT INTO `as_multi_lang` VALUES('1133', '4', '1120', 'pjField', '1', 'title', 'Start Time', 'script');
INSERT INTO `as_multi_lang` VALUES('1134', '4', '1121', 'pjField', '1', 'title', 'End Time', 'script');
INSERT INTO `as_multi_lang` VALUES('1135', '4', '1122', 'pjField', '1', 'title', 'Is Day off', 'script');
INSERT INTO `as_multi_lang` VALUES('1136', '4', '1123', 'pjField', '1', 'title', 'Date', 'script');
INSERT INTO `as_multi_lang` VALUES('1141', '4', '1124', 'pjField', '1', 'title', 'General', 'script');
INSERT INTO `as_multi_lang` VALUES('1144', '4', '1125', 'pjField', '1', 'title', 'Default Working Time', 'script');
INSERT INTO `as_multi_lang` VALUES('1145', '4', '1126', 'pjField', '1', 'title', 'Custom Working Time', 'script');
INSERT INTO `as_multi_lang` VALUES('1146', '4', '1127', 'pjField', '1', 'title', 'Lunch from', 'script');
INSERT INTO `as_multi_lang` VALUES('1147', '4', '1128', 'pjField', '1', 'title', 'Lunch to', 'script');
INSERT INTO `as_multi_lang` VALUES('1148', '4', '1129', 'pjField', '1', 'title', 'Install', 'script');
INSERT INTO `as_multi_lang` VALUES('1149', '4', '1130', 'pjField', '1', 'title', 'Preview', 'script');
INSERT INTO `as_multi_lang` VALUES('1150', '4', '1131', 'pjField', '1', 'title', 'Bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1151', '4', '1132', 'pjField', '1', 'title', 'General', 'script');
INSERT INTO `as_multi_lang` VALUES('1152', '4', '1133', 'pjField', '1', 'title', 'Payments', 'script');
INSERT INTO `as_multi_lang` VALUES('1153', '4', '1134', 'pjField', '1', 'title', 'Booking form', 'script');
INSERT INTO `as_multi_lang` VALUES('1154', '4', '1135', 'pjField', '1', 'title', 'Confirmation', 'script');
INSERT INTO `as_multi_lang` VALUES('1155', '4', '1136', 'pjField', '1', 'title', 'Terms', 'script');
INSERT INTO `as_multi_lang` VALUES('1156', '4', '1137', 'pjField', '1', 'title', 'Address 1', 'script');
INSERT INTO `as_multi_lang` VALUES('1157', '4', '1138', 'pjField', '1', 'title', 'Captcha', 'script');
INSERT INTO `as_multi_lang` VALUES('1158', '4', '1139', 'pjField', '1', 'title', 'City', 'script');
INSERT INTO `as_multi_lang` VALUES('1159', '4', '1140', 'pjField', '1', 'title', 'Email', 'script');
INSERT INTO `as_multi_lang` VALUES('1160', '4', '1141', 'pjField', '1', 'title', 'Name', 'script');
INSERT INTO `as_multi_lang` VALUES('1161', '4', '1142', 'pjField', '1', 'title', 'Notes', 'script');
INSERT INTO `as_multi_lang` VALUES('1162', '4', '1143', 'pjField', '1', 'title', 'Phone', 'script');
INSERT INTO `as_multi_lang` VALUES('1164', '4', '1144', 'pjField', '1', 'title', 'State', 'script');
INSERT INTO `as_multi_lang` VALUES('1165', '4', '1145', 'pjField', '1', 'title', 'Terms', 'script');
INSERT INTO `as_multi_lang` VALUES('1166', '4', '1146', 'pjField', '1', 'title', 'Zip', 'script');
INSERT INTO `as_multi_lang` VALUES('1167', '4', '1147', 'pjField', '1', 'title', 'Country', 'script');
INSERT INTO `as_multi_lang` VALUES('1168', '4', '1148', 'pjField', '1', 'title', 'Paypal address', 'script');
INSERT INTO `as_multi_lang` VALUES('1169', '4', '1149', 'pjField', '1', 'title', 'Accept Bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1170', '4', '1150', 'pjField', '1', 'title', 'Allow payments with Authorize.net', 'script');
INSERT INTO `as_multi_lang` VALUES('1171', '4', '1151', 'pjField', '1', 'title', 'Provide Bank account details for wire transfers', 'script');
INSERT INTO `as_multi_lang` VALUES('1172', '4', '1152', 'pjField', '1', 'title', 'Collect Credit Card details for offline processing', 'script');
INSERT INTO `as_multi_lang` VALUES('1173', '4', '1153', 'pjField', '1', 'title', 'Allow payments with PayPal', 'script');
INSERT INTO `as_multi_lang` VALUES('1174', '4', '1154', 'pjField', '1', 'title', 'Authorize.net transaction key', 'script');
INSERT INTO `as_multi_lang` VALUES('1175', '4', '1155', 'pjField', '1', 'title', 'Authorize.net merchant ID', 'script');
INSERT INTO `as_multi_lang` VALUES('1176', '4', '1156', 'pjField', '1', 'title', 'Bank account', 'script');
INSERT INTO `as_multi_lang` VALUES('1177', '4', '1157', 'pjField', '1', 'title', 'Set deposit amount to be collected for each appointment', 'script');
INSERT INTO `as_multi_lang` VALUES('1178', '4', '1158', 'pjField', '1', 'title', 'Check if you want to disable payments and only collect reservation details', 'script');
INSERT INTO `as_multi_lang` VALUES('1182', '4', '1162', 'pjField', '1', 'title', 'Default status for booked dates if not paid', 'script');
INSERT INTO `as_multi_lang` VALUES('1183', '4', '1163', 'pjField', '1', 'title', 'Default status for booked dates if paid', 'script');
INSERT INTO `as_multi_lang` VALUES('1184', '4', '1164', 'pjField', '1', 'title', 'Tax amount to be collected for each appointment', 'script');
INSERT INTO `as_multi_lang` VALUES('1185', '4', '1165', 'pjField', '1', 'title', 'URL for the web page where your clients will be redirected after PayPal or Authorize.net payment', 'script');
INSERT INTO `as_multi_lang` VALUES('1186', '4', '1166', 'pjField', '1', 'title', 'Authorize.net time zone', 'script');
INSERT INTO `as_multi_lang` VALUES('1187', '4', '1167', 'pjField', '1', 'title', 'New reservation received', 'script');
INSERT INTO `as_multi_lang` VALUES('1188', '4', '1168', 'pjField', '1', 'title', 'Reservation cancelled', 'script');
INSERT INTO `as_multi_lang` VALUES('1189', '4', '1169', 'pjField', '1', 'title', 'Password reminder', 'script');
INSERT INTO `as_multi_lang` VALUES('1190', '4', '1170', 'pjField', '1', 'title', 'Address 2', 'script');
INSERT INTO `as_multi_lang` VALUES('1191', '4', '1171', 'pjField', '1', 'title', 'Date/Time format', 'script');
INSERT INTO `as_multi_lang` VALUES('1192', '4', '1172', 'pjField', '1', 'title', 'Authorize.net hash value', 'script');
INSERT INTO `as_multi_lang` VALUES('1193', '4', '1173', 'pjField', '1', 'title', 'Subject', 'script');
INSERT INTO `as_multi_lang` VALUES('1195', '4', '1174', 'pjField', '1', 'title', 'Email body', 'script');
INSERT INTO `as_multi_lang` VALUES('1196', '4', '1175', 'pjField', '1', 'title', 'Client - booking confirmation email', 'script');
INSERT INTO `as_multi_lang` VALUES('1197', '4', '1176', 'pjField', '1', 'title', 'Client - payment confirmation email', 'script');
INSERT INTO `as_multi_lang` VALUES('1198', '4', '1177', 'pjField', '1', 'title', 'Admin - booking confirmation email', 'script');
INSERT INTO `as_multi_lang` VALUES('1199', '4', '1178', 'pjField', '1', 'title', 'Admin - payment confirmation email', 'script');
INSERT INTO `as_multi_lang` VALUES('1200', '4', '1179', 'pjField', '1', 'title', 'Booking terms URL', 'script');
INSERT INTO `as_multi_lang` VALUES('1201', '4', '1180', 'pjField', '1', 'title', 'Booking terms content', 'script');
INSERT INTO `as_multi_lang` VALUES('1202', '4', '1181', 'pjField', '1', 'title', 'Add booking', 'script');
INSERT INTO `as_multi_lang` VALUES('1203', '4', '1182', 'pjField', '1', 'title', 'Confirmed', 'script');
INSERT INTO `as_multi_lang` VALUES('1205', '4', '1183', 'pjField', '1', 'title', 'Pending', 'script');
INSERT INTO `as_multi_lang` VALUES('1206', '4', '1184', 'pjField', '1', 'title', 'Cancelled', 'script');
INSERT INTO `as_multi_lang` VALUES('1207', '4', '1185', 'pjField', '1', 'title', 'Unique ID', 'script');
INSERT INTO `as_multi_lang` VALUES('1208', '4', '1186', 'pjField', '1', 'title', 'Status', 'script');
INSERT INTO `as_multi_lang` VALUES('1209', '4', '1187', 'pjField', '1', 'title', 'Update booking', 'script');
INSERT INTO `as_multi_lang` VALUES('1337', '4', '1315', 'pjField', '1', 'title', 'CC Exp.date', 'script');
INSERT INTO `as_multi_lang` VALUES('1338', '4', '1316', 'pjField', '1', 'title', 'CC Code', 'script');
INSERT INTO `as_multi_lang` VALUES('1339', '4', '1317', 'pjField', '1', 'title', 'CC Number', 'script');
INSERT INTO `as_multi_lang` VALUES('1340', '4', '1318', 'pjField', '1', 'title', 'CC Type', 'script');
INSERT INTO `as_multi_lang` VALUES('1341', '4', '1319', 'pjField', '1', 'title', 'Maestro', 'script');
INSERT INTO `as_multi_lang` VALUES('1342', '4', '1320', 'pjField', '1', 'title', 'AmericanExpress', 'script');
INSERT INTO `as_multi_lang` VALUES('1343', '4', '1321', 'pjField', '1', 'title', 'MasterCard', 'script');
INSERT INTO `as_multi_lang` VALUES('1344', '4', '1322', 'pjField', '1', 'title', 'Visa', 'script');
INSERT INTO `as_multi_lang` VALUES('1346', '4', '1324', 'pjField', '1', 'title', 'Phone', 'script');
INSERT INTO `as_multi_lang` VALUES('1347', '4', '1325', 'pjField', '1', 'title', 'Email', 'script');
INSERT INTO `as_multi_lang` VALUES('1348', '4', '1326', 'pjField', '1', 'title', 'Invoice details', 'script');
INSERT INTO `as_multi_lang` VALUES('1349', '4', '1327', 'pjField', '1', 'title', 'Create Invoice', 'script');
INSERT INTO `as_multi_lang` VALUES('1350', '4', '1328', 'pjField', '1', 'title', 'Customer details', 'script');
INSERT INTO `as_multi_lang` VALUES('1351', '4', '1329', 'pjField', '1', 'title', 'Notes', 'script');
INSERT INTO `as_multi_lang` VALUES('1352', '4', '1330', 'pjField', '1', 'title', 'Address Line 2', 'script');
INSERT INTO `as_multi_lang` VALUES('1353', '4', '1331', 'pjField', '1', 'title', 'Address Line 1', 'script');
INSERT INTO `as_multi_lang` VALUES('1354', '4', '1332', 'pjField', '1', 'title', 'Name', 'script');
INSERT INTO `as_multi_lang` VALUES('1355', '4', '1333', 'pjField', '1', 'title', 'Zip', 'script');
INSERT INTO `as_multi_lang` VALUES('1356', '4', '1334', 'pjField', '1', 'title', 'City', 'script');
INSERT INTO `as_multi_lang` VALUES('1357', '4', '1335', 'pjField', '1', 'title', 'State', 'script');
INSERT INTO `as_multi_lang` VALUES('1358', '4', '1336', 'pjField', '1', 'title', 'Country', 'script');
INSERT INTO `as_multi_lang` VALUES('1359', '4', '1337', 'pjField', '1', 'title', 'Client', 'script');
INSERT INTO `as_multi_lang` VALUES('1360', '4', '1338', 'pjField', '1', 'title', 'Booking', 'script');
INSERT INTO `as_multi_lang` VALUES('1361', '4', '1339', 'pjField', '1', 'title', 'Booking details', 'script');
INSERT INTO `as_multi_lang` VALUES('1362', '4', '1340', 'pjField', '1', 'title', '-- Choose --', 'script');
INSERT INTO `as_multi_lang` VALUES('1363', '4', '1341', 'pjField', '1', 'title', 'Payment method', 'script');
INSERT INTO `as_multi_lang` VALUES('1364', '4', '1342', 'pjField', '1', 'title', 'Price', 'script');
INSERT INTO `as_multi_lang` VALUES('1366', '4', '1344', 'pjField', '1', 'title', 'Tax', 'script');
INSERT INTO `as_multi_lang` VALUES('1367', '4', '1345', 'pjField', '1', 'title', 'Deposit', 'script');
INSERT INTO `as_multi_lang` VALUES('1368', '4', '1346', 'pjField', '1', 'title', 'Total', 'script');
INSERT INTO `as_multi_lang` VALUES('1369', '4', '1347', 'pjField', '1', 'title', 'Created', 'script');
INSERT INTO `as_multi_lang` VALUES('1370', '4', '1348', 'pjField', '1', 'title', 'Authorize.net', 'script');
INSERT INTO `as_multi_lang` VALUES('1371', '4', '1349', 'pjField', '1', 'title', 'Bank account', 'script');
INSERT INTO `as_multi_lang` VALUES('1372', '4', '1350', 'pjField', '1', 'title', 'Credit card', 'script');
INSERT INTO `as_multi_lang` VALUES('1373', '4', '1351', 'pjField', '1', 'title', 'Paypal', 'script');
INSERT INTO `as_multi_lang` VALUES('1374', '4', '1352', 'pjField', '1', 'title', 'Booking form', 'script');
INSERT INTO `as_multi_lang` VALUES('1375', '4', '1353', 'pjField', '1', 'title', 'Confirmation', 'script');
INSERT INTO `as_multi_lang` VALUES('1376', '4', '1354', 'pjField', '1', 'title', 'Terms', 'script');
INSERT INTO `as_multi_lang` VALUES('1378', '4', '1355', 'pjField', '1', 'title', 'Choose the fields that should be available on the booking form.', 'script');
INSERT INTO `as_multi_lang` VALUES('1379', '4', '1356', 'pjField', '1', 'title', 'Email notifications will be sent to people who make a booking after the booking form is completed or/and payment is made. If you leave subject field blank no email will be sent. You can use tokens in the email messages to personalize them.<br /><br />\r\n\r\n<table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n  <tbody><tr>\r\n    <td width="50%" valign="top"><p>{Name} - customer name;<br />\r\n      {Phone} - customer phone number; <br />\r\n      {Email} - customer e-mail address; <br />\r\n      {Notes} - additional notes; <br />\r\n      {Address1} - address 1; <br />\r\n      {Address2} - address 2; <br />\r\n      {City} - city; <br />\r\n      {State} - state; <br />\r\n      {Zip} - zip code; <br />\r\n      {Country} - country; <br />\r\n    </p></td>\r\n    <td width="50%" valign="top">\r\n  {BookingID} - Booking ID; <br />\r\n    {Services} - Selected services<br />\r\n    {CCType} - CC type; <br />\r\n      {CCNum} - CC number; <br />\r\n      {CCExpMonth} - CC exp.month; <br />\r\n      {CCExpYear} - CC exp.year; <br />\r\n      {CCSec} - CC sec. code; <br />\r\n      {PaymentMethod} - selected payment method; <br />\r\n      {Price} - price for selected services; <br />\r\n      {Deposit} - Deposit amount; <br />\r\n      {Tax} - Tax amount; <br />\r\n      {Total} - Total amount; <br />\r\n      {CancelURL} - Link for booking cancellation; </td>\r\n  </tr>\r\n</tbody></table>', 'script');
INSERT INTO `as_multi_lang` VALUES('1380', '4', '1357', 'pjField', '1', 'title', 'Enter booking terms and conditions. You can also include a link to external web page where your terms and conditions page is.', 'script');
INSERT INTO `as_multi_lang` VALUES('1381', '4', '1358', 'pjField', '1', 'title', 'Set different payment options for your Appointment Scheduler software. Enable or disable the available payment processing companies.', 'script');
INSERT INTO `as_multi_lang` VALUES('1383', '4', '1359', 'pjField', '1', 'title', 'Booking payment options', 'script');
INSERT INTO `as_multi_lang` VALUES('1384', '4', '1360', 'pjField', '1', 'title', 'Here you can set some general options about the booking process.', 'script');
INSERT INTO `as_multi_lang` VALUES('1385', '4', '1361', 'pjField', '1', 'title', 'Booking options', 'script');
INSERT INTO `as_multi_lang` VALUES('1386', '4', '1362', 'pjField', '1', 'title', 'Set-up general settings', 'script');
INSERT INTO `as_multi_lang` VALUES('1387', '4', '1363', 'pjField', '1', 'title', 'General options', 'script');
INSERT INTO `as_multi_lang` VALUES('1388', '4', '1364', 'pjField', '1', 'title', 'Hide prices', 'script');
INSERT INTO `as_multi_lang` VALUES('1389', '4', '1365', 'pjField', '1', 'title', 'Step (in minutes)', 'script');
INSERT INTO `as_multi_lang` VALUES('1390', '4', '1366', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `as_multi_lang` VALUES('1391', '4', '1367', 'pjField', '1', 'title', 'Picture', 'script');
INSERT INTO `as_multi_lang` VALUES('1398', '4', '1368', 'pjField', '1', 'title', 'Delete picture', 'script');
INSERT INTO `as_multi_lang` VALUES('1399', '4', '1369', 'pjField', '1', 'title', 'Delete confirmation', 'script');
INSERT INTO `as_multi_lang` VALUES('1400', '4', '1370', 'pjField', '1', 'title', 'Are you sure you want to delete this picture?', 'script');
INSERT INTO `as_multi_lang` VALUES('1409', '4', '1371', 'pjField', '1', 'title', 'Install instructions', 'script');
INSERT INTO `as_multi_lang` VALUES('1410', '4', '1372', 'pjField', '1', 'title', 'In order to install this script into your web page, please follow below steps.', 'script');
INSERT INTO `as_multi_lang` VALUES('1411', '4', '1373', 'pjField', '1', 'title', 'Step 1 (Required)', 'script');
INSERT INTO `as_multi_lang` VALUES('1412', '4', '1374', 'pjField', '1', 'title', 'Step 2 (Optional) - for SEO purposes and better ranking you need to put next meta tag into the HEAD part of your page', 'script');
INSERT INTO `as_multi_lang` VALUES('1413', '4', '1375', 'pjField', '1', 'title', 'Step 3 (Optional) - for SEO purposes and better ranking you need to create a .htaccess file (or update existing one) with data below. Put the file in the same folder as your webpage.', 'script');
INSERT INTO `as_multi_lang` VALUES('1421', '4', '1376', 'pjField', '1', 'title', 'Use SEO URLs', 'script');
INSERT INTO `as_multi_lang` VALUES('1429', '4', '1377', 'pjField', '1', 'title', 'Time format', 'script');
INSERT INTO `as_multi_lang` VALUES('1431', '4', '1378', 'pjField', '1', 'title', 'Show week numbers', 'script');
INSERT INTO `as_multi_lang` VALUES('1437', '4', '1379', 'pjField', '1', 'title', 'Captcha', 'script');
INSERT INTO `as_multi_lang` VALUES('1438', '4', '1380', 'pjField', '1', 'title', 'Select Country', 'script');
INSERT INTO `as_multi_lang` VALUES('1439', '4', '1381', 'pjField', '1', 'title', 'I agree with terms and conditions', 'script');
INSERT INTO `as_multi_lang` VALUES('1440', '4', '1382', 'pjField', '1', 'title', 'Please go back to your basket.', 'script');
INSERT INTO `as_multi_lang` VALUES('1441', '4', '1383', 'pjField', '1', 'title', 'Select Payment method', 'script');
INSERT INTO `as_multi_lang` VALUES('1442', '4', '1384', 'pjField', '1', 'title', 'Select CC Type', 'script');
INSERT INTO `as_multi_lang` VALUES('1449', '4', '1385', 'pjField', '1', 'title', 'Employee', 'script');
INSERT INTO `as_multi_lang` VALUES('1450', '4', '1386', 'pjField', '1', 'title', 'Service', 'script');
INSERT INTO `as_multi_lang` VALUES('1451', '4', '1387', 'pjField', '1', 'title', 'From', 'script');
INSERT INTO `as_multi_lang` VALUES('1452', '4', '1388', 'pjField', '1', 'title', 'To', 'script');
INSERT INTO `as_multi_lang` VALUES('1453', '4', '1389', 'pjField', '1', 'title', 'Query', 'script');
INSERT INTO `as_multi_lang` VALUES('1454', '4', '1390', 'pjField', '1', 'title', 'Date', 'script');
INSERT INTO `as_multi_lang` VALUES('1455', '4', '1391', 'pjField', '1', 'title', 'Export selected', 'script');
INSERT INTO `as_multi_lang` VALUES('1457', '4', '1392', 'pjField', '1', 'title', 'Comma', 'script');
INSERT INTO `as_multi_lang` VALUES('1458', '4', '1393', 'pjField', '1', 'title', 'Semicolon', 'script');
INSERT INTO `as_multi_lang` VALUES('1459', '4', '1394', 'pjField', '1', 'title', 'Tab', 'script');
INSERT INTO `as_multi_lang` VALUES('1460', '4', '1395', 'pjField', '1', 'title', 'CSV', 'script');
INSERT INTO `as_multi_lang` VALUES('1461', '4', '1396', 'pjField', '1', 'title', 'XML', 'script');
INSERT INTO `as_multi_lang` VALUES('1462', '4', '1397', 'pjField', '1', 'title', 'iCal', 'script');
INSERT INTO `as_multi_lang` VALUES('1463', '4', '1398', 'pjField', '1', 'title', 'Delimiter', 'script');
INSERT INTO `as_multi_lang` VALUES('1464', '4', '1399', 'pjField', '1', 'title', 'Format', 'script');
INSERT INTO `as_multi_lang` VALUES('1465', '4', '1400', 'pjField', '1', 'title', 'Not Available', 'script');
INSERT INTO `as_multi_lang` VALUES('1466', '4', '1401', 'pjField', '1', 'title', 'Export Bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1467', '4', '1402', 'pjField', '1', 'title', 'Date/Time', 'script');
INSERT INTO `as_multi_lang` VALUES('1469', '4', '1403', 'pjField', '1', 'title', 'Service/Employee', 'script');
INSERT INTO `as_multi_lang` VALUES('1470', '4', '1404', 'pjField', '1', 'title', 'Reminder', 'script');
INSERT INTO `as_multi_lang` VALUES('1471', '4', '1405', 'pjField', '1', 'title', 'Check this if you want to send reminders to your clients.', 'script');
INSERT INTO `as_multi_lang` VALUES('1472', '4', '1406', 'pjField', '1', 'title', 'Set number of hours before the booking start time when an email reminder will be sent', 'script');
INSERT INTO `as_multi_lang` VALUES('1473', '4', '1407', 'pjField', '1', 'title', 'Email Reminder subject', 'script');
INSERT INTO `as_multi_lang` VALUES('1474', '4', '1408', 'pjField', '1', 'title', 'Set number of hours before the booking start time when an SMS reminder will be sent', 'script');
INSERT INTO `as_multi_lang` VALUES('1475', '4', '1409', 'pjField', '1', 'title', 'SMS country code', 'script');
INSERT INTO `as_multi_lang` VALUES('1477', '4', '1410', 'pjField', '1', 'title', 'SMS message', 'script');
INSERT INTO `as_multi_lang` VALUES('1478', '4', '1411', 'pjField', '1', 'title', 'Email Reminder body', 'script');
INSERT INTO `as_multi_lang` VALUES('1479', '4', '1412', 'pjField', '1', 'title', 'Get key', 'script');
INSERT INTO `as_multi_lang` VALUES('1480', '4', '1413', 'pjField', '1', 'title', 'You can send email and sms reminders to your clients X hours before their booking. You can use these tokens to customize the messages that are sent.<br /><br />\r\n<table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n  <tbody><tr>\r\n    <td width="50%" valign="top"><p>{Name} - customer name;<br />\r\n      {Phone} - customer phone number; <br />\r\n      {Email} - customer e-mail address;</p></td>\r\n    <td width="50%" valign="top">\r\n {BookingID} - Booking ID; <br />\r\n    {Services} - Selected services<br />\r\n    {Price} - price for selected services; <br />\r\n      {Deposit} - Deposit amount; <br />\r\n      {Tax} - Tax amount; <br />\r\n      {Total} - Total amount; <br />\r\n      {CancelURL} - Link for booking cancellation; </td>\r\n  </tr>\r\n</tbody></table><br />\r\nYou should also set up a CRON job for cron.php file which should execute every hour. You need to use our SMS gateway to support sms reminders.', 'script');
INSERT INTO `as_multi_lang` VALUES('1481', '4', '1414', 'pjField', '1', 'title', 'Reminder options', 'script');
INSERT INTO `as_multi_lang` VALUES('1482', '4', '1415', 'pjField', '1', 'title', 'Booking updated', 'script');
INSERT INTO `as_multi_lang` VALUES('1483', '4', '1416', 'pjField', '1', 'title', 'All changes made to the booking has been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1484', '4', '1417', 'pjField', '1', 'title', 'Booking not found', 'script');
INSERT INTO `as_multi_lang` VALUES('1485', '4', '1418', 'pjField', '1', 'title', 'Sorry, but the booking you''re looking for is missing.', 'script');
INSERT INTO `as_multi_lang` VALUES('1486', '4', '1419', 'pjField', '1', 'title', 'Booking added', 'script');
INSERT INTO `as_multi_lang` VALUES('1487', '4', '1420', 'pjField', '1', 'title', 'The booking has been successfully added.', 'script');
INSERT INTO `as_multi_lang` VALUES('1488', '4', '1421', 'pjField', '1', 'title', 'Booking not added', 'script');
INSERT INTO `as_multi_lang` VALUES('1489', '4', '1422', 'pjField', '1', 'title', 'Sorry, but the booking has not been added.', 'script');
INSERT INTO `as_multi_lang` VALUES('1490', '4', '1423', 'pjField', '1', 'title', 'Fill in the form below to add a new booking. Under Clients tab you can enter information about the client. ', 'script');
INSERT INTO `as_multi_lang` VALUES('1491', '4', '1424', 'pjField', '1', 'title', 'Add a booking', 'script');
INSERT INTO `as_multi_lang` VALUES('1492', '4', '1425', 'pjField', '1', 'title', 'Client details', 'script');
INSERT INTO `as_multi_lang` VALUES('1493', '4', '1426', 'pjField', '1', 'title', 'Use the form below to enter details about your client.', 'script');
INSERT INTO `as_multi_lang` VALUES('1494', '4', '1427', 'pjField', '1', 'title', 'Use form below to update client related data.', 'script');
INSERT INTO `as_multi_lang` VALUES('1495', '4', '1428', 'pjField', '1', 'title', 'Client details', 'script');
INSERT INTO `as_multi_lang` VALUES('1496', '4', '1429', 'pjField', '1', 'title', 'Use form below to update booking details.', 'script');
INSERT INTO `as_multi_lang` VALUES('1497', '4', '1430', 'pjField', '1', 'title', 'Booking update', 'script');
INSERT INTO `as_multi_lang` VALUES('1504', '4', '1431', 'pjField', '1', 'title', 'All the changes made to reminder have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1505', '4', '1432', 'pjField', '1', 'title', 'Reminder updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1506', '4', '1433', 'pjField', '1', 'title', 'All the changes made to terms have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1507', '4', '1434', 'pjField', '1', 'title', 'Terms updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1508', '4', '1435', 'pjField', '1', 'title', 'Confirmation updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1509', '4', '1436', 'pjField', '1', 'title', 'All the changes made to confirmation have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1512', '4', '1437', 'pjField', '1', 'title', 'All the changes made to booking form have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1513', '4', '1438', 'pjField', '1', 'title', 'Booking form updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1514', '4', '1439', 'pjField', '1', 'title', 'Payment options updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1515', '4', '1440', 'pjField', '1', 'title', 'All the changes made to payment options have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1516', '4', '1441', 'pjField', '1', 'title', 'All the changes made to booking options have been saved.', 'script');
INSERT INTO `as_multi_lang` VALUES('1517', '4', '1442', 'pjField', '1', 'title', 'Booking options updated!', 'script');
INSERT INTO `as_multi_lang` VALUES('1531', '4', '1454', 'pjField', '1', 'title', 'Recalculate the price', 'script');
INSERT INTO `as_multi_lang` VALUES('1532', '4', '1455', 'pjField', '1', 'title', '+ Add service', 'script');
INSERT INTO `as_multi_lang` VALUES('1534', '4', '1456', 'pjField', '1', 'title', 'Add service', 'script');
INSERT INTO `as_multi_lang` VALUES('1535', '4', '1457', 'pjField', '1', 'title', 'Delete confirmation', 'script');
INSERT INTO `as_multi_lang` VALUES('1537', '4', '1458', 'pjField', '1', 'title', 'Are you sure you want to delete selected service from the current booking?', 'script');
INSERT INTO `as_multi_lang` VALUES('1538', '4', '1459', 'pjField', '1', 'title', 'There are not any selected services yet.', 'script');
INSERT INTO `as_multi_lang` VALUES('1539', '4', '1460', 'pjField', '1', 'title', 'TOTAL', 'script');
INSERT INTO `as_multi_lang` VALUES('1540', '4', '1461', 'pjField', '1', 'title', 'Selected Services', 'script');
INSERT INTO `as_multi_lang` VALUES('1541', '4', '1462', 'pjField', '1', 'title', 'Select a Date', 'script');
INSERT INTO `as_multi_lang` VALUES('1542', '4', '1463', 'pjField', '1', 'title', 'Make an Appointment', 'script');
INSERT INTO `as_multi_lang` VALUES('1543', '4', '1464', 'pjField', '1', 'title', 'Start time', 'script');
INSERT INTO `as_multi_lang` VALUES('1550', '4', '1465', 'pjField', '1', 'title', 'End time', 'script');
INSERT INTO `as_multi_lang` VALUES('1551', '4', '1466', 'pjField', '1', 'title', 'Select service on', 'script');
INSERT INTO `as_multi_lang` VALUES('1552', '4', '1467', 'pjField', '1', 'title', 'Availability', 'script');
INSERT INTO `as_multi_lang` VALUES('1553', '4', '1468', 'pjField', '1', 'title', 'Booking Form', 'script');
INSERT INTO `as_multi_lang` VALUES('1554', '4', '1469', 'pjField', '1', 'title', 'System message', 'script');
INSERT INTO `as_multi_lang` VALUES('1555', '4', '1470', 'pjField', '1', 'title', 'Checkout form not available', 'script');
INSERT INTO `as_multi_lang` VALUES('1556', '4', '1471', 'pjField', '1', 'title', 'Return back', 'script');
INSERT INTO `as_multi_lang` VALUES('1557', '4', '1472', 'pjField', '1', 'title', 'Booking Preview', 'script');
INSERT INTO `as_multi_lang` VALUES('1558', '4', '1473', 'pjField', '1', 'title', 'Confirm booking', 'script');
INSERT INTO `as_multi_lang` VALUES('1559', '4', '1474', 'pjField', '1', 'title', 'Preview not available', 'script');
INSERT INTO `as_multi_lang` VALUES('1561', '4', '1475', 'pjField', '1', 'title', 'Invalid data', 'script');
INSERT INTO `as_multi_lang` VALUES('1562', '4', '1476', 'pjField', '1', 'title', 'Sorry, submitted data not validate.', 'script');
INSERT INTO `as_multi_lang` VALUES('1563', '4', '1477', 'pjField', '1', 'title', 'Profile', 'script');
INSERT INTO `as_multi_lang` VALUES('1564', '4', '1478', 'pjField', '1', 'title', 'Use form below to update your profile settings.', 'script');
INSERT INTO `as_multi_lang` VALUES('1569', '4', '1479', 'pjField', '1', 'title', 'IP address', 'script');
INSERT INTO `as_multi_lang` VALUES('1570', '4', '1480', 'pjField', '1', 'title', 'Booking Service details', 'script');
INSERT INTO `as_multi_lang` VALUES('1571', '4', '1481', 'pjField', '1', 'title', 'Send email', 'script');
INSERT INTO `as_multi_lang` VALUES('1572', '4', '1482', 'pjField', '1', 'title', 'Send SMS', 'script');
INSERT INTO `as_multi_lang` VALUES('1573', '4', '1483', 'pjField', '1', 'title', 'Subject', 'script');
INSERT INTO `as_multi_lang` VALUES('1574', '4', '1484', 'pjField', '1', 'title', 'Message', 'script');
INSERT INTO `as_multi_lang` VALUES('1575', '4', '1485', 'pjField', '1', 'title', 'Reports', 'script');
INSERT INTO `as_multi_lang` VALUES('1576', '4', '1486', 'pjField', '1', 'title', 'Employees', 'script');
INSERT INTO `as_multi_lang` VALUES('1577', '4', '1487', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `as_multi_lang` VALUES('1578', '4', '1488', 'pjField', '1', 'title', 'Bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1579', '4', '1489', 'pjField', '1', 'title', 'All Bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1580', '4', '1490', 'pjField', '1', 'title', 'Confirmed Bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1581', '4', '1491', 'pjField', '1', 'title', 'Pending Bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1582', '4', '1492', 'pjField', '1', 'title', 'Cancelled Bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1583', '4', '1493', 'pjField', '1', 'title', 'Total amount', 'script');
INSERT INTO `as_multi_lang` VALUES('1584', '4', '1494', 'pjField', '1', 'title', 'Confirmed Bookings Amount', 'script');
INSERT INTO `as_multi_lang` VALUES('1585', '4', '1495', 'pjField', '1', 'title', 'Pending Bookings Amount', 'script');
INSERT INTO `as_multi_lang` VALUES('1586', '4', '1496', 'pjField', '1', 'title', 'Cancelled Bookings Amount', 'script');
INSERT INTO `as_multi_lang` VALUES('1596', '4', '1497', 'pjField', '1', 'title', 'Columns', 'script');
INSERT INTO `as_multi_lang` VALUES('1597', '4', '1498', 'pjField', '1', 'title', 'Print', 'script');
INSERT INTO `as_multi_lang` VALUES('1598', '4', '1499', 'pjField', '1', 'title', 'Save as PDF', 'script');
INSERT INTO `as_multi_lang` VALUES('1600', '4', '1500', 'pjField', '1', 'title', 'Menu', 'script');
INSERT INTO `as_multi_lang` VALUES('1601', '4', '1501', 'pjField', '1', 'title', 'View bookings', 'script');
INSERT INTO `as_multi_lang` VALUES('1602', '4', '1502', 'pjField', '1', 'title', 'Working time', 'script');
INSERT INTO `as_multi_lang` VALUES('1603', '4', '1503', 'pjField', '1', 'title', 'Services', 'script');
INSERT INTO `as_multi_lang` VALUES('1604', '4', '1504', 'pjField', '1', 'title', 'Below you can see the available services that your clients can book. Under Add service tab you can add a new service. Or use the edit icon for each service to modify it.', 'script');
INSERT INTO `as_multi_lang` VALUES('1609', '4', '1505', 'pjField', '1', 'title', 'Specify the time needed to do this service.', 'script');
INSERT INTO `as_multi_lang` VALUES('1610', '4', '1506', 'pjField', '1', 'title', 'In case you need some time before the start time for the service you can add it here. For example if your service is 60 minutes long and you input 30 minutes here, then when someone books a service at 10am you will not be available for other bookings between 9:30am and 10am', 'script');
INSERT INTO `as_multi_lang` VALUES('1611', '4', '1507', 'pjField', '1', 'title', 'In case you need some time after the end time for the service you can add it here. For example if your service is 60 minutes long and you input 30 minutes here, then when someone books a service at 10am till 11am you will not be available for other bookings between 11am and 11:30am', 'script');
INSERT INTO `as_multi_lang` VALUES('1612', '4', '1508', 'pjField', '1', 'title', 'Employees tooltip', 'script');
INSERT INTO `as_multi_lang` VALUES('1623', '4', '1509', 'pjField', '1', 'title', 'Send SMS when new booking is made', 'script');
INSERT INTO `as_multi_lang` VALUES('1629', '4', '1510', 'pjField', '1', 'title', 'Send to client', 'script');
INSERT INTO `as_multi_lang` VALUES('1630', '4', '1511', 'pjField', '1', 'title', 'Send to employee', 'script');
INSERT INTO `as_multi_lang` VALUES('1633', '4', '1512', 'pjField', '1', 'title', 'Click on available time to make an appointment', 'script');
INSERT INTO `as_multi_lang` VALUES('1634', '4', '1514', 'pjField', '1', 'title', 'Employees', 'script');
INSERT INTO `as_multi_lang` VALUES('1635', '4', '1515', 'pjField', '1', 'title', 'Below you can see a list of employees who do the different service you offer. You can have one or multiple employees.', 'script');
INSERT INTO `as_multi_lang` VALUES('1652', '4', '1530', 'pjField', '1', 'title', 'SEO', 'script');
INSERT INTO `as_multi_lang` VALUES('1653', '4', '1531', 'pjField', '1', 'title', 'Language options', 'script');
INSERT INTO `as_multi_lang` VALUES('1654', '4', '1532', 'pjField', '1', 'title', 'Language', 'script');
INSERT INTO `as_multi_lang` VALUES('1655', '4', '1533', 'pjField', '1', 'title', 'Hide language selector', 'script');
INSERT INTO `as_multi_lang` VALUES('1656', '4', '1534', 'pjField', '1', 'title', 'To better optimize your shopping cart please follow the steps below', 'script');
INSERT INTO `as_multi_lang` VALUES('1657', '4', '1535', 'pjField', '1', 'title', 'SEO Optimization', 'script');
INSERT INTO `as_multi_lang` VALUES('1658', '4', '1536', 'pjField', '1', 'title', 'Step 1. Webpage where your front end appointment scheduler is', 'script');
INSERT INTO `as_multi_lang` VALUES('1659', '4', '1537', 'pjField', '1', 'title', 'Step 2. Put the meta tag below between &lt;head&gt; and &lt;/head&gt; tags on your web page', 'script');
INSERT INTO `as_multi_lang` VALUES('1660', '4', '1538', 'pjField', '1', 'title', 'Step 3. Create .htaccess file (or update existing one) in the folder where your web page is and put the data below in it', 'script');
INSERT INTO `as_multi_lang` VALUES('1661', '4', '1539', 'pjField', '1', 'title', 'Generate', 'script');
INSERT INTO `as_multi_lang` VALUES('1662', '4', '1540', 'pjField', '1', 'title', 'Index', 'script');
INSERT INTO `as_multi_lang` VALUES('1663', '4', '1541', 'pjField', '1', 'title', 'Employees report', 'script');
INSERT INTO `as_multi_lang` VALUES('1664', '4', '1542', 'pjField', '1', 'title', 'Using the form below you can generate a report for specific service and date range. You can also generate the results based on number of services each employee did or the total amount paid for these services.', 'script');
INSERT INTO `as_multi_lang` VALUES('1665', '4', '1543', 'pjField', '1', 'title', 'Services report', 'script');
INSERT INTO `as_multi_lang` VALUES('1666', '4', '1544', 'pjField', '1', 'title', 'Using the form below you can generate a report for specific employee and date range. You can also generate the results based on number of services each employee did or the total amount paid for these services.', 'script');
INSERT INTO `as_multi_lang` VALUES('1667', '4', '1545', 'pjField', '1', 'title', 'Amount', 'script');
INSERT INTO `as_multi_lang` VALUES('1668', '4', '1546', 'pjField', '1', 'title', 'Count', 'script');
INSERT INTO `as_multi_lang` VALUES('1669', '4', '1547', 'pjField', '1', 'title', 'Below you can see working schedule for all employees. Using the date selector below to refresh the schedule. Click on Print button to print work timesheet.', 'script');
INSERT INTO `as_multi_lang` VALUES('1670', '4', '1548', 'pjField', '1', 'title', 'Dashboard', 'script');
INSERT INTO `as_multi_lang` VALUES('1671', '4', '1549', 'pjField', '1', 'title', 'Apply', 'script');
INSERT INTO `as_multi_lang` VALUES('1672', '4', '1550', 'pjField', '1', 'title', 'Dashboard filter', 'script');
INSERT INTO `as_multi_lang` VALUES('1673', '4', '1756', 'pjField', '1', 'title', 'Custom working time', 'script');
INSERT INTO `as_multi_lang` VALUES('1674', '4', '1758', 'pjField', '1', 'title', 'Using the form below you can set a custom working time for any date. Just select a date and set working time for it. Or you can just mark the date as a day off and bookings on that date will not be accepted. ', 'script');
INSERT INTO `as_multi_lang` VALUES('1675', '4', '1759', 'pjField', '1', 'title', 'Here you can set working time for this employee only. Different working time can be set for each day of the week. You can also set days off and a lunch break. ', 'script');
INSERT INTO `as_multi_lang` VALUES('1676', '4', '1760', 'pjField', '1', 'title', 'Working Time', 'script');
INSERT INTO `as_multi_lang` VALUES('1677', '4', '1761', 'pjField', '1', 'title', 'Custom working time', 'script');
INSERT INTO `as_multi_lang` VALUES('1678', '4', '1762', 'pjField', '1', 'title', 'Using the form below you can set a custom working time for any date for this employee only. Just select a date and set working time for it. Or you can just mark the date as a day off and bookings on that date for this employee will not be accepted.', 'script');
INSERT INTO `as_multi_lang` VALUES('1679', '4', '1768', 'pjField', '1', 'title', 'Today', 'script');
INSERT INTO `as_multi_lang` VALUES('1680', '4', '1769', 'pjField', '1', 'title', 'Tomorrow', 'script');
INSERT INTO `as_multi_lang` VALUES('1681', '4', '1770', 'pjField', '1', 'title', 'Dashboard Notice', 'script');
INSERT INTO `as_multi_lang` VALUES('1682', '4', '1771', 'pjField', '1', 'title', 'Selected date is set to "day off". Use the date picker above to choose another date. Please, note that you can change working time under Options page.', 'script');
INSERT INTO `as_multi_lang` VALUES('1683', '4', '1772', 'pjField', '1', 'title', 'Go to PayPal Secure page', 'script');
INSERT INTO `as_multi_lang` VALUES('1684', '4', '1773', 'pjField', '1', 'title', 'Go to Authorize.NET Secure page', 'script');
INSERT INTO `as_multi_lang` VALUES('1685', '4', '1774', 'pjField', '1', 'title', 'Please wait while redirect to secure payment processor webpage complete...', 'script');
INSERT INTO `as_multi_lang` VALUES('1686', '4', '1775', 'pjField', '1', 'title', 'Your request has been sent successfully. Thank you.', 'script');
INSERT INTO `as_multi_lang` VALUES('1687', '4', '1776', 'pjField', '1', 'title', 'Booking not found', 'script');
INSERT INTO `as_multi_lang` VALUES('1688', '4', '1777', 'pjField', '1', 'title', 'The invoice for this booking is already paid.', 'script');
INSERT INTO `as_multi_lang` VALUES('1689', '4', '1778', 'pjField', '1', 'title', 'Booking not available', 'script');
INSERT INTO `as_multi_lang` VALUES('1690', '4', '1779', 'pjField', '1', 'title', 'Start time', 'script');
INSERT INTO `as_multi_lang` VALUES('1691', '4', '1780', 'pjField', '1', 'title', 'End time', 'script');
INSERT INTO `as_multi_lang` VALUES('1692', '4', '1781', 'pjField', '1', 'title', 'Services not found', 'script');
INSERT INTO `as_multi_lang` VALUES('1693', '4', '1782', 'pjField', '1', 'title', 'You need to have at least a service.', 'script');
INSERT INTO `as_multi_lang` VALUES('1694', '4', '1783', 'pjField', '1', 'title', 'Employees not found', 'script');
INSERT INTO `as_multi_lang` VALUES('1695', '4', '1784', 'pjField', '1', 'title', 'You need to create employee and assign service first.', 'script');
INSERT INTO `as_multi_lang` VALUES('1696', '4', '1785', 'pjField', '1', 'title', 'mins', 'script');
INSERT INTO `as_multi_lang` VALUES('1697', '4', '1786', 'pjField', '1', 'title', 'st', 'script');
INSERT INTO `as_multi_lang` VALUES('1698', '4', '1787', 'pjField', '1', 'title', 'nd', 'script');
INSERT INTO `as_multi_lang` VALUES('1699', '4', '1788', 'pjField', '1', 'title', 'rd', 'script');
INSERT INTO `as_multi_lang` VALUES('1700', '4', '1789', 'pjField', '1', 'title', 'th', 'script');
INSERT INTO `as_multi_lang` VALUES('1701', '4', '1790', 'pjField', '1', 'title', 'on', 'script');
INSERT INTO `as_multi_lang` VALUES('1702', '4', '1791', 'pjField', '1', 'title', 'back to services', 'script');
INSERT INTO `as_multi_lang` VALUES('1703', '4', '1792', 'pjField', '1', 'title', 'Checkout', 'script');
INSERT INTO `as_multi_lang` VALUES('1704', '4', '1793', 'pjField', '1', 'title', 'Service added to your cart.', 'script');
INSERT INTO `as_multi_lang` VALUES('1705', '4', '1794', 'pjField', '1', 'title', 'Some of the items in your basket is not available.', 'script');
INSERT INTO `as_multi_lang` VALUES('1706', '4', '1795', 'pjField', '1', 'title', 'from', 'script');
INSERT INTO `as_multi_lang` VALUES('1707', '4', '1796', 'pjField', '1', 'title', 'till', 'script');
INSERT INTO `as_multi_lang` VALUES('1708', '4', '1797', 'pjField', '1', 'title', 'Missing, empty or invalid parameters.', 'script');
INSERT INTO `as_multi_lang` VALUES('1709', '4', '1798', 'pjField', '1', 'title', 'Booking with such an ID did not exists.', 'script');
INSERT INTO `as_multi_lang` VALUES('1710', '4', '1799', 'pjField', '1', 'title', 'Security hash did not match.', 'script');
INSERT INTO `as_multi_lang` VALUES('1711', '4', '1800', 'pjField', '1', 'title', 'Booking is already cancelled.', 'script');
INSERT INTO `as_multi_lang` VALUES('1712', '4', '1801', 'pjField', '1', 'title', 'Booking has been cancelled successfully.', 'script');
INSERT INTO `as_multi_lang` VALUES('1713', '4', '1802', 'pjField', '1', 'title', 'Customer Details', 'script');
INSERT INTO `as_multi_lang` VALUES('1714', '4', '1803', 'pjField', '1', 'title', 'Cancel booking', 'script');
INSERT INTO `as_multi_lang` VALUES('1715', '4', '1804', 'pjField', '1', 'title', 'Booking Services', 'script');
INSERT INTO `as_multi_lang` VALUES('1716', '4', '1805', 'pjField', '1', 'title', 'Booking Cancellation', 'script');
INSERT INTO `as_multi_lang` VALUES('1717', '4', '1806', 'pjField', '1', 'title', 'Employee - booking confirmation email', 'script');
INSERT INTO `as_multi_lang` VALUES('1718', '4', '1807', 'pjField', '1', 'title', 'Employee - payment confirmation email', 'script');
INSERT INTO `as_multi_lang` VALUES('1719', '4', '1808', 'pjField', '1', 'title', 'Update the default working time for all the employees', 'script');
INSERT INTO `as_multi_lang` VALUES('1720', '4', '1809', 'pjField', '1', 'title', 'Layout', 'script');
INSERT INTO `as_multi_lang` VALUES('1721', '4', '1810', 'pjField', '1', 'title', 'Select date', 'script');
INSERT INTO `as_multi_lang` VALUES('1722', '4', '1811', 'pjField', '1', 'title', 'Service', 'script');
INSERT INTO `as_multi_lang` VALUES('1723', '4', '1812', 'pjField', '1', 'title', 'Select time', 'script');
INSERT INTO `as_multi_lang` VALUES('1724', '4', '1813', 'pjField', '1', 'title', 'Employee', 'script');
INSERT INTO `as_multi_lang` VALUES('1725', '4', '1814', 'pjField', '1', 'title', 'Book', 'script');
INSERT INTO `as_multi_lang` VALUES('1726', '4', '1815', 'pjField', '1', 'title', 'Select date and service', 'script');
INSERT INTO `as_multi_lang` VALUES('1727', '4', '1816', 'pjField', '1', 'title', 'Choose date', 'script');
INSERT INTO `as_multi_lang` VALUES('1728', '4', '1817', 'pjField', '1', 'title', 'Date', 'script');
INSERT INTO `as_multi_lang` VALUES('1729', '4', '1818', 'pjField', '1', 'title', 'Price', 'script');
INSERT INTO `as_multi_lang` VALUES('1730', '4', '1819', 'pjField', '1', 'title', 'This invoice have been cancelled.', 'script');
INSERT INTO `as_multi_lang` VALUES('1731', '4', '1820', 'pjField', '1', 'title', 'not available on selected date and time', 'script');
INSERT INTO `as_multi_lang` VALUES('1732', '4', '1', 'pjCalendar', '1', 'confirm_subject_client', 'Kiitos varauksestasi', 'data');
INSERT INTO `as_multi_lang` VALUES('1733', '4', '1', 'pjCalendar', '1', 'confirm_tokens_client', 'Hei!\r\n\r\nKiitos varauksestasi!\r\n\r\nValitut palvelut: \r\n{Services}\r\n\r\n**Mikali peruutat varauksen se tulee tehda 48 tuntia ennen varattua aikaa.\r\n\r\nTervetuloa!\r\n\r\n\r\n\r\nPalvelun tarjoaa varaa.com', 'data');
INSERT INTO `as_multi_lang` VALUES('1734', '4', '1', 'pjCalendar', '1', 'payment_subject_client', 'Payment received', 'data');
INSERT INTO `as_multi_lang` VALUES('1735', '4', '1', 'pjCalendar', '1', 'payment_tokens_client', 'We''ve received the payment for your booking and it is now confirmed.\r\n\r\nID: {BookingID}\r\n\r\nThank you,\r\nThe Management', 'data');
INSERT INTO `as_multi_lang` VALUES('1736', '4', '1', 'pjCalendar', '1', 'confirm_subject_admin', 'Uusi varaus on saapunut', 'data');
INSERT INTO `as_multi_lang` VALUES('1737', '4', '1', 'pjCalendar', '1', 'confirm_tokens_admin', 'Hei!\r\n\r\nOlet saanut uuden varauksen\r\n\r\nID: {BookingID}\r\n\r\nPalvelut\r\n{Services}\r\n\r\nAsiakkaan tiedot\r\nNimi: {Name}\r\nPuhelin: {Phone}\r\nEmail: {Email}\r\n\r\nLisatiedot:\r\n{Notes}', 'data');
INSERT INTO `as_multi_lang` VALUES('1738', '4', '1', 'pjCalendar', '1', 'payment_subject_admin', 'New payment received', 'data');
INSERT INTO `as_multi_lang` VALUES('1739', '4', '1', 'pjCalendar', '1', 'payment_tokens_admin', 'Booking deposit has been paid.\r\n\r\nID: {BookingID}', 'data');
INSERT INTO `as_multi_lang` VALUES('1740', '4', '1', 'pjCalendar', '1', 'confirm_subject_employee', 'Uusi varaus on saapunut', 'data');
INSERT INTO `as_multi_lang` VALUES('1741', '4', '1', 'pjCalendar', '1', 'confirm_tokens_employee', 'Hei!\r\n\r\nOlet saanut uuden varauksen\r\n\r\nID: {BookingID}\r\n\r\nPalvelut\r\n{Services}\r\n\r\nAsiakkaan tiedot\r\nNimi: {Name}\r\nPuhelin: {Phone}\r\nEmail: {Email}\r\n\r\nLisatiedot:\r\n{Notes}', 'data');
INSERT INTO `as_multi_lang` VALUES('1742', '4', '1', 'pjCalendar', '1', 'payment_subject_employee', 'New payment received', 'data');
INSERT INTO `as_multi_lang` VALUES('1743', '4', '1', 'pjCalendar', '1', 'payment_tokens_employee', 'Booking deposit has been paid.\r\n\r\nID: {BookingID}', 'data');
INSERT INTO `as_multi_lang` VALUES('1744', '4', '1821', 'pjField', '1', 'title', 'Languages', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1747', '4', '1822', 'pjField', '1', 'title', 'Titles', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1750', '4', '1823', 'pjField', '1', 'title', 'Languages', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1753', '4', '1824', 'pjField', '1', 'title', 'Add as many languages as you need to your script. For each of the languages added you need to translate all the text titles.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1756', '4', '1825', 'pjField', '1', 'title', 'Titles', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1759', '4', '1826', 'pjField', '1', 'title', 'Edit all page titles. Use the search box to quickly locate a title.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1762', '4', '1827', 'pjField', '1', 'title', 'Title', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1765', '4', '1828', 'pjField', '1', 'title', 'Flag', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1768', '4', '1829', 'pjField', '1', 'title', 'Is default', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1771', '4', '1830', 'pjField', '1', 'title', 'Order', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1774', '4', '1831', 'pjField', '1', 'title', 'Add Language', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1777', '4', '1832', 'pjField', '1', 'title', 'Field', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1780', '4', '1833', 'pjField', '1', 'title', 'Value', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1783', '4', '1834', 'pjField', '1', 'title', 'Back-end title', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1786', '4', '1835', 'pjField', '1', 'title', 'Front-end title', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1789', '4', '1836', 'pjField', '1', 'title', 'Special title', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1792', '4', '1837', 'pjField', '1', 'title', 'Titles Updated', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1795', '4', '1838', 'pjField', '1', 'title', 'All the changes made to titles have been saved.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1798', '4', '1839', 'pjField', '1', 'title', 'Per page', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1801', '4', '1840', 'pjField', '1', 'title', 'Import error', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1804', '4', '1841', 'pjField', '1', 'title', 'Import failed due missing parameters.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1807', '4', '1842', 'pjField', '1', 'title', 'Import complete', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1810', '4', '1843', 'pjField', '1', 'title', 'The import was performed successfully.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1813', '4', '1844', 'pjField', '1', 'title', 'Import error', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1816', '4', '1845', 'pjField', '1', 'title', 'Import failed due SQL error.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1819', '4', '1846', 'pjField', '1', 'title', 'Import / Export', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1822', '4', '1847', 'pjField', '1', 'title', 'Import', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1825', '4', '1848', 'pjField', '1', 'title', 'Export', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1828', '4', '1849', 'pjField', '1', 'title', 'Language', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1831', '4', '1850', 'pjField', '1', 'title', 'Browse your computer', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1834', '4', '1851', 'pjField', '1', 'title', 'Import / Export', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1837', '4', '1852', 'pjField', '1', 'title', 'Use form below to Import or Export choosen language.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1840', '4', '1853', 'pjField', '1', 'title', 'Backup', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1843', '4', '1854', 'pjField', '1', 'title', 'Backup complete!', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1846', '4', '1855', 'pjField', '1', 'title', 'Backup failed!', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1849', '4', '1856', 'pjField', '1', 'title', 'Backup failed!', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1852', '4', '1857', 'pjField', '1', 'title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at ligula non arcu dignissim pretium. Praesent in magna nulla, in porta leo.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1855', '4', '1858', 'pjField', '1', 'title', 'All backup files have been saved.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1858', '4', '1859', 'pjField', '1', 'title', 'No option was selected.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1861', '4', '1860', 'pjField', '1', 'title', 'Backup not performed.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1864', '4', '1861', 'pjField', '1', 'title', 'Backup', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1867', '4', '1862', 'pjField', '1', 'title', 'Backup database', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1870', '4', '1863', 'pjField', '1', 'title', 'Backup files', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1873', '4', '1864', 'pjField', '1', 'title', 'Backup', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1876', '4', '1865', 'pjField', '1', 'title', 'Log', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1879', '4', '1866', 'pjField', '1', 'title', 'Config log', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1882', '4', '1867', 'pjField', '1', 'title', 'Empty log', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1885', '4', '1868', 'pjField', '1', 'title', 'Config log updated.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1888', '4', '1869', 'pjField', '1', 'title', 'The config log have been updated.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1891', '4', '1870', 'pjField', '1', 'title', 'List', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1894', '4', '1871', 'pjField', '1', 'title', '+ Add', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1897', '4', '1872', 'pjField', '1', 'title', 'Country name', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1900', '4', '1873', 'pjField', '1', 'title', 'Alpha 2', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1903', '4', '1874', 'pjField', '1', 'title', 'Alpha 3', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1906', '4', '1875', 'pjField', '1', 'title', 'Status', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1909', '4', '1876', 'pjField', '1', 'title', 'Add +', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1912', '4', '1877', 'pjField', '1', 'title', 'Active', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1915', '4', '1878', 'pjField', '1', 'title', 'Inactive', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1918', '4', '1879', 'pjField', '1', 'title', 'Save', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1921', '4', '1880', 'pjField', '1', 'title', 'Cancel', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1924', '4', '1881', 'pjField', '1', 'title', 'Countries', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1927', '4', '1882', 'pjField', '1', 'title', 'Country updated', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1930', '4', '1883', 'pjField', '1', 'title', 'Country added', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1933', '4', '1884', 'pjField', '1', 'title', 'Country failed to add', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1936', '4', '1885', 'pjField', '1', 'title', 'Country not found', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1939', '4', '1886', 'pjField', '1', 'title', 'Add country', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1942', '4', '1887', 'pjField', '1', 'title', 'Update country', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1945', '4', '1888', 'pjField', '1', 'title', 'Manage countries', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1948', '4', '1889', 'pjField', '1', 'title', 'Country has been updated successfully.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1951', '4', '1890', 'pjField', '1', 'title', 'Country has been added successfully.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1954', '4', '1891', 'pjField', '1', 'title', 'Country has not been added successfully.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1957', '4', '1892', 'pjField', '1', 'title', 'Country you are looking for has not been found.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1960', '4', '1893', 'pjField', '1', 'title', 'Use form below to add a country.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1963', '4', '1894', 'pjField', '1', 'title', 'Use form below to update a country.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1966', '4', '1895', 'pjField', '1', 'title', 'Use grid below to organize your country list.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1969', '4', '1896', 'pjField', '1', 'title', 'Are you sure you want to delete selected country?', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1972', '4', '1897', 'pjField', '1', 'title', 'Delete selected', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1975', '4', '1898', 'pjField', '1', 'title', 'All', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1978', '4', '1899', 'pjField', '1', 'title', 'Search', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1981', '4', '1', 'pjCountry', '1', 'name', 'Afghanistan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1982', '4', '2', 'pjCountry', '1', 'name', 'Ã…land Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1983', '4', '3', 'pjCountry', '1', 'name', 'Albania', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1984', '4', '4', 'pjCountry', '1', 'name', 'Algeria', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1985', '4', '5', 'pjCountry', '1', 'name', 'American Samoa', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1986', '4', '6', 'pjCountry', '1', 'name', 'Andorra', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1987', '4', '7', 'pjCountry', '1', 'name', 'Angola', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1988', '4', '8', 'pjCountry', '1', 'name', 'Anguilla', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1989', '4', '9', 'pjCountry', '1', 'name', 'Antarctica', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1990', '4', '10', 'pjCountry', '1', 'name', 'Antigua and Barbuda', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1991', '4', '11', 'pjCountry', '1', 'name', 'Argentina', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1992', '4', '12', 'pjCountry', '1', 'name', 'Armenia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1993', '4', '13', 'pjCountry', '1', 'name', 'Aruba', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1994', '4', '14', 'pjCountry', '1', 'name', 'Australia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1995', '4', '15', 'pjCountry', '1', 'name', 'Austria', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1996', '4', '16', 'pjCountry', '1', 'name', 'Azerbaijan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1997', '4', '17', 'pjCountry', '1', 'name', 'Bahamas', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1998', '4', '18', 'pjCountry', '1', 'name', 'Bahrain', 'plugin');
INSERT INTO `as_multi_lang` VALUES('1999', '4', '19', 'pjCountry', '1', 'name', 'Bangladesh', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2000', '4', '20', 'pjCountry', '1', 'name', 'Barbados', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2001', '4', '21', 'pjCountry', '1', 'name', 'Belarus', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2002', '4', '22', 'pjCountry', '1', 'name', 'Belgium', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2003', '4', '23', 'pjCountry', '1', 'name', 'Belize', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2004', '4', '24', 'pjCountry', '1', 'name', 'Benin', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2005', '4', '25', 'pjCountry', '1', 'name', 'Bermuda', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2006', '4', '26', 'pjCountry', '1', 'name', 'Bhutan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2007', '4', '27', 'pjCountry', '1', 'name', 'Bolivia, Plurinational State of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2008', '4', '28', 'pjCountry', '1', 'name', 'Bonaire, Sint Eustatius and Saba', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2009', '4', '29', 'pjCountry', '1', 'name', 'Bosnia and Herzegovina', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2010', '4', '30', 'pjCountry', '1', 'name', 'Botswana', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2011', '4', '31', 'pjCountry', '1', 'name', 'Bouvet Island', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2012', '4', '32', 'pjCountry', '1', 'name', 'Brazil', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2013', '4', '33', 'pjCountry', '1', 'name', 'British Indian Ocean Territory', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2014', '4', '34', 'pjCountry', '1', 'name', 'Brunei Darussalam', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2015', '4', '35', 'pjCountry', '1', 'name', 'Bulgaria', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2016', '4', '36', 'pjCountry', '1', 'name', 'Burkina Faso', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2017', '4', '37', 'pjCountry', '1', 'name', 'Burundi', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2018', '4', '38', 'pjCountry', '1', 'name', 'Cambodia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2019', '4', '39', 'pjCountry', '1', 'name', 'Cameroon', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2020', '4', '40', 'pjCountry', '1', 'name', 'Canada', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2021', '4', '41', 'pjCountry', '1', 'name', 'Cape Verde', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2022', '4', '42', 'pjCountry', '1', 'name', 'Cayman Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2023', '4', '43', 'pjCountry', '1', 'name', 'Central African Republic', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2024', '4', '44', 'pjCountry', '1', 'name', 'Chad', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2025', '4', '45', 'pjCountry', '1', 'name', 'Chile', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2026', '4', '46', 'pjCountry', '1', 'name', 'China', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2027', '4', '47', 'pjCountry', '1', 'name', 'Christmas Island', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2028', '4', '48', 'pjCountry', '1', 'name', 'Cocos array(Keeling) Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2029', '4', '49', 'pjCountry', '1', 'name', 'Colombia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2030', '4', '50', 'pjCountry', '1', 'name', 'Comoros', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2031', '4', '51', 'pjCountry', '1', 'name', 'Congo', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2032', '4', '52', 'pjCountry', '1', 'name', 'Congo, the Democratic Republic of the', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2033', '4', '53', 'pjCountry', '1', 'name', 'Cook Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2034', '4', '54', 'pjCountry', '1', 'name', 'Costa Rica', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2035', '4', '55', 'pjCountry', '1', 'name', 'CÃ´te d''Ivoire', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2036', '4', '56', 'pjCountry', '1', 'name', 'Croatia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2037', '4', '57', 'pjCountry', '1', 'name', 'Cuba', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2038', '4', '58', 'pjCountry', '1', 'name', 'CuraÃ§ao', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2039', '4', '59', 'pjCountry', '1', 'name', 'Cyprus', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2040', '4', '60', 'pjCountry', '1', 'name', 'Czech Republic', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2041', '4', '61', 'pjCountry', '1', 'name', 'Denmark', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2042', '4', '62', 'pjCountry', '1', 'name', 'Djibouti', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2043', '4', '63', 'pjCountry', '1', 'name', 'Dominica', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2044', '4', '64', 'pjCountry', '1', 'name', 'Dominican Republic', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2045', '4', '65', 'pjCountry', '1', 'name', 'Ecuador', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2046', '4', '66', 'pjCountry', '1', 'name', 'Egypt', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2047', '4', '67', 'pjCountry', '1', 'name', 'El Salvador', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2048', '4', '68', 'pjCountry', '1', 'name', 'Equatorial Guinea', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2049', '4', '69', 'pjCountry', '1', 'name', 'Eritrea', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2050', '4', '70', 'pjCountry', '1', 'name', 'Estonia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2051', '4', '71', 'pjCountry', '1', 'name', 'Ethiopia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2052', '4', '72', 'pjCountry', '1', 'name', 'Falkland Islands array(Malvinas)', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2053', '4', '73', 'pjCountry', '1', 'name', 'Faroe Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2054', '4', '74', 'pjCountry', '1', 'name', 'Fiji', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2055', '4', '75', 'pjCountry', '1', 'name', 'Finland', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2056', '4', '76', 'pjCountry', '1', 'name', 'France', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2057', '4', '77', 'pjCountry', '1', 'name', 'French Guiana', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2058', '4', '78', 'pjCountry', '1', 'name', 'French Polynesia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2059', '4', '79', 'pjCountry', '1', 'name', 'French Southern Territories', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2060', '4', '80', 'pjCountry', '1', 'name', 'Gabon', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2061', '4', '81', 'pjCountry', '1', 'name', 'Gambia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2062', '4', '82', 'pjCountry', '1', 'name', 'Georgia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2063', '4', '83', 'pjCountry', '1', 'name', 'Germany', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2064', '4', '84', 'pjCountry', '1', 'name', 'Ghana', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2065', '4', '85', 'pjCountry', '1', 'name', 'Gibraltar', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2066', '4', '86', 'pjCountry', '1', 'name', 'Greece', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2067', '4', '87', 'pjCountry', '1', 'name', 'Greenland', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2068', '4', '88', 'pjCountry', '1', 'name', 'Grenada', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2069', '4', '89', 'pjCountry', '1', 'name', 'Guadeloupe', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2070', '4', '90', 'pjCountry', '1', 'name', 'Guam', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2071', '4', '91', 'pjCountry', '1', 'name', 'Guatemala', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2072', '4', '92', 'pjCountry', '1', 'name', 'Guernsey', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2073', '4', '93', 'pjCountry', '1', 'name', 'Guinea', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2074', '4', '94', 'pjCountry', '1', 'name', 'Guinea-Bissau', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2075', '4', '95', 'pjCountry', '1', 'name', 'Guyana', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2076', '4', '96', 'pjCountry', '1', 'name', 'Haiti', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2077', '4', '97', 'pjCountry', '1', 'name', 'Heard Island and McDonald Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2078', '4', '98', 'pjCountry', '1', 'name', 'Holy See array(Vatican City State)', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2079', '4', '99', 'pjCountry', '1', 'name', 'Honduras', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2080', '4', '100', 'pjCountry', '1', 'name', 'Hong Kong', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2081', '4', '101', 'pjCountry', '1', 'name', 'Hungary', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2082', '4', '102', 'pjCountry', '1', 'name', 'Iceland', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2083', '4', '103', 'pjCountry', '1', 'name', 'India', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2084', '4', '104', 'pjCountry', '1', 'name', 'Indonesia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2085', '4', '105', 'pjCountry', '1', 'name', 'Iran, Islamic Republic of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2086', '4', '106', 'pjCountry', '1', 'name', 'Iraq', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2087', '4', '107', 'pjCountry', '1', 'name', 'Ireland', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2088', '4', '108', 'pjCountry', '1', 'name', 'Isle of Man', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2089', '4', '109', 'pjCountry', '1', 'name', 'Israel', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2090', '4', '110', 'pjCountry', '1', 'name', 'Italy', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2091', '4', '111', 'pjCountry', '1', 'name', 'Jamaica', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2092', '4', '112', 'pjCountry', '1', 'name', 'Japan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2093', '4', '113', 'pjCountry', '1', 'name', 'Jersey', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2094', '4', '114', 'pjCountry', '1', 'name', 'Jordan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2095', '4', '115', 'pjCountry', '1', 'name', 'Kazakhstan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2096', '4', '116', 'pjCountry', '1', 'name', 'Kenya', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2097', '4', '117', 'pjCountry', '1', 'name', 'Kiribati', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2098', '4', '118', 'pjCountry', '1', 'name', 'Korea, Democratic People''s Republic of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2099', '4', '119', 'pjCountry', '1', 'name', 'Korea, Republic of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2100', '4', '120', 'pjCountry', '1', 'name', 'Kuwait', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2101', '4', '121', 'pjCountry', '1', 'name', 'Kyrgyzstan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2102', '4', '122', 'pjCountry', '1', 'name', 'Lao People''s Democratic Republic', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2103', '4', '123', 'pjCountry', '1', 'name', 'Latvia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2104', '4', '124', 'pjCountry', '1', 'name', 'Lebanon', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2105', '4', '125', 'pjCountry', '1', 'name', 'Lesotho', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2106', '4', '126', 'pjCountry', '1', 'name', 'Liberia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2107', '4', '127', 'pjCountry', '1', 'name', 'Libya', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2108', '4', '128', 'pjCountry', '1', 'name', 'Liechtenstein', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2109', '4', '129', 'pjCountry', '1', 'name', 'Lithuania', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2110', '4', '130', 'pjCountry', '1', 'name', 'Luxembourg', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2111', '4', '131', 'pjCountry', '1', 'name', 'Macao', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2112', '4', '132', 'pjCountry', '1', 'name', 'Macedonia, The Former Yugoslav Republic of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2113', '4', '133', 'pjCountry', '1', 'name', 'Madagascar', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2114', '4', '134', 'pjCountry', '1', 'name', 'Malawi', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2115', '4', '135', 'pjCountry', '1', 'name', 'Malaysia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2116', '4', '136', 'pjCountry', '1', 'name', 'Maldives', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2117', '4', '137', 'pjCountry', '1', 'name', 'Mali', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2118', '4', '138', 'pjCountry', '1', 'name', 'Malta', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2119', '4', '139', 'pjCountry', '1', 'name', 'Marshall Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2120', '4', '140', 'pjCountry', '1', 'name', 'Martinique', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2121', '4', '141', 'pjCountry', '1', 'name', 'Mauritania', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2122', '4', '142', 'pjCountry', '1', 'name', 'Mauritius', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2123', '4', '143', 'pjCountry', '1', 'name', 'Mayotte', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2124', '4', '144', 'pjCountry', '1', 'name', 'Mexico', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2125', '4', '145', 'pjCountry', '1', 'name', 'Micronesia, Federated States of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2126', '4', '146', 'pjCountry', '1', 'name', 'Moldova, Republic of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2127', '4', '147', 'pjCountry', '1', 'name', 'Monaco', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2128', '4', '148', 'pjCountry', '1', 'name', 'Mongolia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2129', '4', '149', 'pjCountry', '1', 'name', 'Montenegro', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2130', '4', '150', 'pjCountry', '1', 'name', 'Montserrat', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2131', '4', '151', 'pjCountry', '1', 'name', 'Morocco', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2132', '4', '152', 'pjCountry', '1', 'name', 'Mozambique', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2133', '4', '153', 'pjCountry', '1', 'name', 'Myanmar', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2134', '4', '154', 'pjCountry', '1', 'name', 'Namibia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2135', '4', '155', 'pjCountry', '1', 'name', 'Nauru', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2136', '4', '156', 'pjCountry', '1', 'name', 'Nepal', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2137', '4', '157', 'pjCountry', '1', 'name', 'Netherlands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2138', '4', '158', 'pjCountry', '1', 'name', 'New Caledonia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2139', '4', '159', 'pjCountry', '1', 'name', 'New Zealand', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2140', '4', '160', 'pjCountry', '1', 'name', 'Nicaragua', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2141', '4', '161', 'pjCountry', '1', 'name', 'Niger', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2142', '4', '162', 'pjCountry', '1', 'name', 'Nigeria', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2143', '4', '163', 'pjCountry', '1', 'name', 'Niue', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2144', '4', '164', 'pjCountry', '1', 'name', 'Norfolk Island', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2145', '4', '165', 'pjCountry', '1', 'name', 'Northern Mariana Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2146', '4', '166', 'pjCountry', '1', 'name', 'Norway', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2147', '4', '167', 'pjCountry', '1', 'name', 'Oman', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2148', '4', '168', 'pjCountry', '1', 'name', 'Pakistan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2149', '4', '169', 'pjCountry', '1', 'name', 'Palau', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2150', '4', '170', 'pjCountry', '1', 'name', 'Palestine, State of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2151', '4', '171', 'pjCountry', '1', 'name', 'Panama', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2152', '4', '172', 'pjCountry', '1', 'name', 'Papua New Guinea', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2153', '4', '173', 'pjCountry', '1', 'name', 'Paraguay', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2154', '4', '174', 'pjCountry', '1', 'name', 'Peru', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2155', '4', '175', 'pjCountry', '1', 'name', 'Philippines', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2156', '4', '176', 'pjCountry', '1', 'name', 'Pitcairn', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2157', '4', '177', 'pjCountry', '1', 'name', 'Poland', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2158', '4', '178', 'pjCountry', '1', 'name', 'Portugal', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2159', '4', '179', 'pjCountry', '1', 'name', 'Puerto Rico', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2160', '4', '180', 'pjCountry', '1', 'name', 'Qatar', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2161', '4', '181', 'pjCountry', '1', 'name', 'RÃ©union', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2162', '4', '182', 'pjCountry', '1', 'name', 'Romania', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2163', '4', '183', 'pjCountry', '1', 'name', 'Russian Federation', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2164', '4', '184', 'pjCountry', '1', 'name', 'Rwanda', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2165', '4', '185', 'pjCountry', '1', 'name', 'Saint BarthÃ©lemy', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2166', '4', '186', 'pjCountry', '1', 'name', 'Saint Helena, Ascension and Tristan da Cunha', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2167', '4', '187', 'pjCountry', '1', 'name', 'Saint Kitts and Nevis', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2168', '4', '188', 'pjCountry', '1', 'name', 'Saint Lucia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2169', '4', '189', 'pjCountry', '1', 'name', 'Saint Martin array(French part)', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2170', '4', '190', 'pjCountry', '1', 'name', 'Saint Pierre and Miquelon', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2171', '4', '191', 'pjCountry', '1', 'name', 'Saint Vincent and the Grenadines', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2172', '4', '192', 'pjCountry', '1', 'name', 'Samoa', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2173', '4', '193', 'pjCountry', '1', 'name', 'San Marino', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2174', '4', '194', 'pjCountry', '1', 'name', 'Sao Tome and Principe', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2175', '4', '195', 'pjCountry', '1', 'name', 'Saudi Arabia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2176', '4', '196', 'pjCountry', '1', 'name', 'Senegal', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2177', '4', '197', 'pjCountry', '1', 'name', 'Serbia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2178', '4', '198', 'pjCountry', '1', 'name', 'Seychelles', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2179', '4', '199', 'pjCountry', '1', 'name', 'Sierra Leone', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2180', '4', '200', 'pjCountry', '1', 'name', 'Singapore', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2181', '4', '201', 'pjCountry', '1', 'name', 'Sint Maarten array(Dutch part)', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2182', '4', '202', 'pjCountry', '1', 'name', 'Slovakia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2183', '4', '203', 'pjCountry', '1', 'name', 'Slovenia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2184', '4', '204', 'pjCountry', '1', 'name', 'Solomon Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2185', '4', '205', 'pjCountry', '1', 'name', 'Somalia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2186', '4', '206', 'pjCountry', '1', 'name', 'South Africa', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2187', '4', '207', 'pjCountry', '1', 'name', 'South Georgia and the South Sandwich Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2188', '4', '208', 'pjCountry', '1', 'name', 'South Sudan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2189', '4', '209', 'pjCountry', '1', 'name', 'Spain', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2190', '4', '210', 'pjCountry', '1', 'name', 'Sri Lanka', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2191', '4', '211', 'pjCountry', '1', 'name', 'Sudan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2192', '4', '212', 'pjCountry', '1', 'name', 'Suriname', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2193', '4', '213', 'pjCountry', '1', 'name', 'Svalbard and Jan Mayen', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2194', '4', '214', 'pjCountry', '1', 'name', 'Swaziland', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2195', '4', '215', 'pjCountry', '1', 'name', 'Sweden', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2196', '4', '216', 'pjCountry', '1', 'name', 'Switzerland', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2197', '4', '217', 'pjCountry', '1', 'name', 'Syrian Arab Republic', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2198', '4', '218', 'pjCountry', '1', 'name', 'Taiwan, Province of China', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2199', '4', '219', 'pjCountry', '1', 'name', 'Tajikistan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2200', '4', '220', 'pjCountry', '1', 'name', 'Tanzania, United Republic of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2201', '4', '221', 'pjCountry', '1', 'name', 'Thailand', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2202', '4', '222', 'pjCountry', '1', 'name', 'Timor-Leste', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2203', '4', '223', 'pjCountry', '1', 'name', 'Togo', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2204', '4', '224', 'pjCountry', '1', 'name', 'Tokelau', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2205', '4', '225', 'pjCountry', '1', 'name', 'Tonga', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2206', '4', '226', 'pjCountry', '1', 'name', 'Trinidad and Tobago', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2207', '4', '227', 'pjCountry', '1', 'name', 'Tunisia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2208', '4', '228', 'pjCountry', '1', 'name', 'Turkey', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2209', '4', '229', 'pjCountry', '1', 'name', 'Turkmenistan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2210', '4', '230', 'pjCountry', '1', 'name', 'Turks and Caicos Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2211', '4', '231', 'pjCountry', '1', 'name', 'Tuvalu', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2212', '4', '232', 'pjCountry', '1', 'name', 'Uganda', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2213', '4', '233', 'pjCountry', '1', 'name', 'Ukraine', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2214', '4', '234', 'pjCountry', '1', 'name', 'United Arab Emirates', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2215', '4', '235', 'pjCountry', '1', 'name', 'United Kingdom', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2216', '4', '236', 'pjCountry', '1', 'name', 'United States', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2217', '4', '237', 'pjCountry', '1', 'name', 'United States Minor Outlying Islands', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2218', '4', '238', 'pjCountry', '1', 'name', 'Uruguay', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2219', '4', '239', 'pjCountry', '1', 'name', 'Uzbekistan', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2220', '4', '240', 'pjCountry', '1', 'name', 'Vanuatu', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2221', '4', '241', 'pjCountry', '1', 'name', 'Venezuela, Bolivarian Republic of', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2222', '4', '242', 'pjCountry', '1', 'name', 'Viet Nam', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2223', '4', '243', 'pjCountry', '1', 'name', 'Virgin Islands, British', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2224', '4', '244', 'pjCountry', '1', 'name', 'Virgin Islands, U.S.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2225', '4', '245', 'pjCountry', '1', 'name', 'Wallis and Futuna', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2226', '4', '246', 'pjCountry', '1', 'name', 'Western Sahara', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2227', '4', '247', 'pjCountry', '1', 'name', 'Yemen', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2228', '4', '248', 'pjCountry', '1', 'name', 'Zambia', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2229', '4', '249', 'pjCountry', '1', 'name', 'Zimbabwe', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2728', '4', '1900', 'pjField', '1', 'title', 'Invoices', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2731', '4', '1901', 'pjField', '1', 'title', 'Invoice Config', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2734', '4', '1902', 'pjField', '1', 'title', 'Company logo', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2737', '4', '1903', 'pjField', '1', 'title', 'Company name', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2740', '4', '1904', 'pjField', '1', 'title', 'Name', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2743', '4', '1905', 'pjField', '1', 'title', 'Street address', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2746', '4', '1906', 'pjField', '1', 'title', 'City', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2749', '4', '1907', 'pjField', '1', 'title', 'State', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2752', '4', '1908', 'pjField', '1', 'title', 'Zip', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2755', '4', '1909', 'pjField', '1', 'title', 'Phone', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2758', '4', '1910', 'pjField', '1', 'title', 'Fax', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2761', '4', '1911', 'pjField', '1', 'title', 'Email', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2764', '4', '1912', 'pjField', '1', 'title', 'Website', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2767', '4', '1913', 'pjField', '1', 'title', 'Invoice', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2770', '4', '1914', 'pjField', '1', 'title', 'In order to configure the invoices module you need to fill in your company details. To view all invoices <a href="index.php?controller=pjInvoice&action=pjActionInvoices">click here</a>', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2773', '4', '1915', 'pjField', '1', 'title', 'Invoice config updated!', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2776', '4', '1916', 'pjField', '1', 'title', 'Invoice config data has been properly updated.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2779', '4', '1917', 'pjField', '1', 'title', 'Upload failed', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2782', '4', '1918', 'pjField', '1', 'title', 'Invoice Template', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2785', '4', '1919', 'pjField', '1', 'title', 'Delete confirmation', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2788', '4', '1920', 'pjField', '1', 'title', 'Are you sure you want to delete selected logo?', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2791', '4', '1921', 'pjField', '1', 'title', 'Billing information', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2794', '4', '1922', 'pjField', '1', 'title', 'Shipping information', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2797', '4', '1923', 'pjField', '1', 'title', 'Company information', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2800', '4', '1924', 'pjField', '1', 'title', 'Payment information', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2803', '4', '1925', 'pjField', '1', 'title', 'Address', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2806', '4', '1926', 'pjField', '1', 'title', 'Billing address', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2809', '4', '1927', 'pjField', '1', 'title', 'General information', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2812', '4', '1928', 'pjField', '1', 'title', 'Invoice no.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2815', '4', '1929', 'pjField', '1', 'title', 'Order no.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2818', '4', '1930', 'pjField', '1', 'title', 'Issue date', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2821', '4', '1931', 'pjField', '1', 'title', 'Due date', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2824', '4', '1932', 'pjField', '1', 'title', 'Shipping date', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2827', '4', '1933', 'pjField', '1', 'title', 'Shipping terms', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2830', '4', '1934', 'pjField', '1', 'title', 'Subtotal', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2833', '4', '1935', 'pjField', '1', 'title', 'Discount', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2836', '4', '1936', 'pjField', '1', 'title', 'Tax', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2839', '4', '1937', 'pjField', '1', 'title', 'Shipping', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2842', '4', '1938', 'pjField', '1', 'title', 'Total', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2845', '4', '1939', 'pjField', '1', 'title', 'Paid deposit', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2848', '4', '1940', 'pjField', '1', 'title', 'Amount due', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2851', '4', '1941', 'pjField', '1', 'title', 'Currency', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2854', '4', '1942', 'pjField', '1', 'title', 'Notes', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2857', '4', '1943', 'pjField', '1', 'title', 'Shipping address', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2860', '4', '1944', 'pjField', '1', 'title', 'Created', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2863', '4', '1945', 'pjField', '1', 'title', 'Modified', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2866', '4', '1946', 'pjField', '1', 'title', 'Item', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2869', '4', '1947', 'pjField', '1', 'title', 'Qty', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2872', '4', '1948', 'pjField', '1', 'title', 'Unit price', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2875', '4', '1949', 'pjField', '1', 'title', 'Amount', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2878', '4', '1950', 'pjField', '1', 'title', 'Add item', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2881', '4', '1951', 'pjField', '1', 'title', 'Update item', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2884', '4', '1952', 'pjField', '1', 'title', 'Description', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2887', '4', '1953', 'pjField', '1', 'title', 'Accept payments', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2890', '4', '1954', 'pjField', '1', 'title', 'PRINT', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2893', '4', '1955', 'pjField', '1', 'title', 'EMAIL', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2896', '4', '1956', 'pjField', '1', 'title', 'VIEW', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2899', '4', '1957', 'pjField', '1', 'title', 'Send invoice', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2902', '4', '1958', 'pjField', '1', 'title', 'Invoice', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2905', '4', '1959', 'pjField', '1', 'title', 'Items information', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2908', '4', '1960', 'pjField', '1', 'title', 'Accept payments with PayPal', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2911', '4', '1961', 'pjField', '1', 'title', 'Accept payments with Authorize.NET', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2914', '4', '1962', 'pjField', '1', 'title', 'Accept payments with Credit Card', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2917', '4', '1963', 'pjField', '1', 'title', 'Accept payments with Bank Account', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2920', '4', '1964', 'pjField', '1', 'title', 'Include Shipping information', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2923', '4', '1965', 'pjField', '1', 'title', 'Include Shipping address', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2926', '4', '1966', 'pjField', '1', 'title', 'Include Company', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2929', '4', '1967', 'pjField', '1', 'title', 'Include Name', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2932', '4', '1968', 'pjField', '1', 'title', 'Include Address', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2935', '4', '1969', 'pjField', '1', 'title', 'Include City', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2938', '4', '1970', 'pjField', '1', 'title', 'Include State', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2941', '4', '1971', 'pjField', '1', 'title', 'Include Zip', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2944', '4', '1972', 'pjField', '1', 'title', 'Include Phone', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2947', '4', '1973', 'pjField', '1', 'title', 'Include Fax', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2950', '4', '1974', 'pjField', '1', 'title', 'Include Email', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2953', '4', '1975', 'pjField', '1', 'title', 'Include Website', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2956', '4', '1976', 'pjField', '1', 'title', 'Include Street address', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2959', '4', '1977', 'pjField', '1', 'title', 'Invoice updated!', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2962', '4', '1978', 'pjField', '1', 'title', 'Invoice has been updated.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2965', '4', '1979', 'pjField', '1', 'title', 'Invoice Not Found', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2968', '4', '1980', 'pjField', '1', 'title', 'Invoice with such ID was not found.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2971', '4', '1981', 'pjField', '1', 'title', 'Update failed', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2974', '4', '1982', 'pjField', '1', 'title', 'Some of the data is not valid.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2977', '4', '1983', 'pjField', '1', 'title', 'Status', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2980', '4', '1984', 'pjField', '1', 'title', 'Pay with Paypal', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2983', '4', '1985', 'pjField', '1', 'title', 'Pay with Authorize.Net', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2986', '4', '1986', 'pjField', '1', 'title', 'Pay with Credit Card', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2989', '4', '1987', 'pjField', '1', 'title', 'Pay with Bank Account', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2992', '4', '1988', 'pjField', '1', 'title', 'Pay Now', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2995', '4', '1989', 'pjField', '1', 'title', 'Invoice module', 'plugin');
INSERT INTO `as_multi_lang` VALUES('2998', '4', '1990', 'pjField', '1', 'title', 'Invoice module', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3001', '4', '1991', 'pjField', '1', 'title', 'Paypal address', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3004', '4', '1992', 'pjField', '1', 'title', 'Authorize.Net Timezone', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3007', '4', '1993', 'pjField', '1', 'title', 'Authorize.Net Merchant ID', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3010', '4', '1994', 'pjField', '1', 'title', 'Authorize.Net Transaction Key', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3013', '4', '1995', 'pjField', '1', 'title', 'Bank Account', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3016', '4', '1996', 'pjField', '1', 'title', 'You will be redirected to Paypal in 3 seconds. If not please click on the button.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3019', '4', '1997', 'pjField', '1', 'title', 'You will be redirected to Authorize.net in 3 seconds. If not please click on the button.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3022', '4', '1998', 'pjField', '1', 'title', 'Proceed with payment', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3025', '4', '1999', 'pjField', '1', 'title', 'Proceed with payment', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3028', '4', '2000', 'pjField', '1', 'title', 'Delete selected', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3031', '4', '2001', 'pjField', '1', 'title', 'Are you sure you want to delete selected invoices?', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3034', '4', '2002', 'pjField', '1', 'title', 'Is shipped', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3037', '4', '2003', 'pjField', '1', 'title', 'Include Shipping date', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3040', '4', '2004', 'pjField', '1', 'title', 'Include Shipping terms', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3043', '4', '2005', 'pjField', '1', 'title', 'Include Is Shipped', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3046', '4', '2006', 'pjField', '1', 'title', 'NOT PAID', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3049', '4', '2007', 'pjField', '1', 'title', 'PAID', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3052', '4', '2008', 'pjField', '1', 'title', 'CANCELLED', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3055', '4', '2009', 'pjField', '1', 'title', 'No.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3058', '4', '2010', 'pjField', '1', 'title', 'ADD +', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3061', '4', '2011', 'pjField', '1', 'title', 'SAVE', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3064', '4', '2012', 'pjField', '1', 'title', 'Booking URL - Token: {ORDER_ID}', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3067', '4', '2013', 'pjField', '1', 'title', 'Include Shipping', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3070', '4', '2014', 'pjField', '1', 'title', 'Invoice added!', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3073', '4', '2015', 'pjField', '1', 'title', 'Invoice has been added.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3076', '4', '2016', 'pjField', '1', 'title', 'Invoice has not been added.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3079', '4', '2017', 'pjField', '1', 'title', 'Invoice failed to add!', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3082', '4', '2018', 'pjField', '1', 'title', 'Notice', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3085', '4', '2019', 'pjField', '1', 'title', 'Check the email address(es) where invoice should be sent.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3088', '4', '2020', 'pjField', '1', 'title', 'Invoice details', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3091', '4', '2021', 'pjField', '1', 'title', 'Fill in invoice details and use the buttons below to view, print or email the invoice.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3094', '4', '2022', 'pjField', '1', 'title', 'Billing details', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3097', '4', '2023', 'pjField', '1', 'title', 'Under "Billing information" you can edit your customer billing details. Under "Company information" is your company billing information which will be used for the invoice.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3100', '4', '2024', 'pjField', '1', 'title', 'Quantity format', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3103', '4', '2025', 'pjField', '1', 'title', 'Format as INT instead of FLOAT', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3106', '4', '2026', 'pjField', '1', 'title', 'Authorize.Net hash value', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3109', '4', '2027', 'pjField', '1', 'title', 'SMS', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3112', '4', '2028', 'pjField', '1', 'title', 'SMS Config', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3115', '4', '2029', 'pjField', '1', 'title', 'Phone number', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3118', '4', '2030', 'pjField', '1', 'title', 'Message', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3121', '4', '2031', 'pjField', '1', 'title', 'Status', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3124', '4', '2032', 'pjField', '1', 'title', 'Date/Time sent', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3127', '4', '2033', 'pjField', '1', 'title', 'API Key', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3130', '4', '2034', 'pjField', '1', 'title', 'SMS', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3133', '4', '2035', 'pjField', '1', 'title', 'To send SMS you need a valid API Key. Please, contact StivaSoft to purchase an API key.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3136', '4', '2036', 'pjField', '1', 'title', 'SMS API key updated!', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3139', '4', '2037', 'pjField', '1', 'title', 'All changes have been saved.', 'plugin');
INSERT INTO `as_multi_lang` VALUES('3142', '4', '1', 'pjService', '1', 'name', 'Service 1', 'data');
INSERT INTO `as_multi_lang` VALUES('3143', '4', '1', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `as_multi_lang` VALUES('3144', '4', '1', 'pjEmployee', '1', 'name', 'Employee 1', 'data');
INSERT INTO `as_multi_lang` VALUES('3145', '4', '3', 'pjService', '1', 'name', 'service 2', 'data');
INSERT INTO `as_multi_lang` VALUES('3146', '4', '3', 'pjService', '1', 'description', '', 'data');
INSERT INTO `as_multi_lang` VALUES('3147', '4', '2', 'pjEmployee', '1', 'name', 'Employee 2', 'data');
INSERT INTO `as_multi_lang` VALUES('3148', '4', '2038', 'pjField', '1', 'title', 'Layout Backend', 'script');
INSERT INTO `as_multi_lang` VALUES('3149', '4', '4', 'pjService', '1', 'name', 'Service 5', 'data');
INSERT INTO `as_multi_lang` VALUES('3150', '4', '4', 'pjService', '1', 'description', 'Test', 'data');
INSERT INTO `as_multi_lang` VALUES('3151', '4', '5', 'pjService', '1', 'name', 'Service 2', 'data');
INSERT INTO `as_multi_lang` VALUES('3152', '4', '5', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `as_multi_lang` VALUES('3155', '4', '6', 'pjService', '1', 'name', 'Service 3', 'data');
INSERT INTO `as_multi_lang` VALUES('3156', '4', '6', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `as_multi_lang` VALUES('3157', '4', '7', 'pjService', '1', 'name', 'Service 4', 'data');
INSERT INTO `as_multi_lang` VALUES('3158', '4', '7', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `as_multi_lang` VALUES('3163', '4', '8', 'pjService', '1', 'name', 'Service 5', 'data');
INSERT INTO `as_multi_lang` VALUES('3164', '4', '8', 'pjService', '1', 'description', 'Service description', 'data');
INSERT INTO `as_multi_lang` VALUES('3171', '4', '3', 'pjEmployee', '1', 'name', 'Employee 3', 'data');
INSERT INTO `as_multi_lang` VALUES('3172', '4', '4', 'pjEmployee', '1', 'name', 'Employee 4', 'data');
INSERT INTO `as_multi_lang` VALUES('3173', '4', '2039', 'pjField', '1', 'title', 'Custom Status', 'script');
INSERT INTO `as_multi_lang` VALUES('3189', '4', '9', 'pjService', '1', 'name', 'Service tao lao', 'data');
INSERT INTO `as_multi_lang` VALUES('3190', '4', '9', 'pjService', '1', 'description', 'sadasd', 'data');
INSERT INTO `as_multi_lang` VALUES('3191', '4', '10', 'pjService', '1', 'name', 'sadasd', 'data');
INSERT INTO `as_multi_lang` VALUES('3192', '4', '10', 'pjService', '1', 'description', 'asdasdasd', 'data');
INSERT INTO `as_multi_lang` VALUES('3211', '4', '1', 'pjCalendar', '1', 'terms_url', 'https://www.youtube.com/watch?v=TXpVGwPtYIU', 'data');
INSERT INTO `as_multi_lang` VALUES('3212', '4', '1', 'pjCalendar', '1', 'terms_body', 'https://www.youtube.com/watch?v=TXpVGwPtYIU', 'data');

DROP TABLE IF EXISTS `as_options`;

CREATE TABLE `as_options` (
  `foreign_id` int(10) unsigned NOT NULL DEFAULT '0',
  `owner_id` int(8) DEFAULT NULL,
  `key` varchar(255) NOT NULL DEFAULT '',
  `tab_id` tinyint(3) unsigned DEFAULT NULL,
  `value` text,
  `label` text,
  `type` enum('string','text','int','float','enum','bool') NOT NULL DEFAULT 'string',
  `order` int(10) unsigned DEFAULT NULL,
  `is_visible` tinyint(1) unsigned DEFAULT '1',
  `style` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`foreign_id`,`key`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_options_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `as_options` VALUES('1', '4', 'o_accept_bookings', '3', '1|0::1', '', 'bool', '1', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_allow_authorize', '7', '1|0::1', '', 'bool', '18', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_allow_bank', '7', '1|0::1', '', 'bool', '24', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_allow_creditcard', '7', '1|0::1', '', 'bool', '23', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_allow_paypal', '7', '1|0::1', '', 'bool', '16', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_authorize_hash', '7', 'abcd', '', 'string', '21', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_authorize_key', '7', '53N2U726wZwksK4a', '', 'string', '20', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_authorize_mid', '7', '745ean5JCt2Y', '', 'string', '19', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_authorize_tz', '7', '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', '22', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bank_account', '7', 'Bank of America', '', 'text', '25', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_address_1', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '6', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_address_2', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '7', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_captcha', '4', '1|3::1', 'No|Yes (Required)', 'enum', '16', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_city', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '12', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_country', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '15', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_email', '4', '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', '4', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_name', '4', '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', '3', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_notes', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '8', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_phone', '4', '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', '5', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_state', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '13', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_terms', '4', '1|3::1', 'No|Yes (Required)', 'enum', '17', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_bf_zip', '4', '1|2|3::1', 'No|Yes|Yes (Required)', 'enum', '14', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_currency', '1', 'AED|AFN|ALL|AMD|ANG|AOA|ARS|AUD|AWG|AZN|BAM|BBD|BDT|BGN|BHD|BIF|BMD|BND|BOB|BOV|BRL|BSD|BTN|BWP|BYR|BZD|CAD|CDF|CHE|CHF|CHW|CLF|CLP|CNY|COP|COU|CRC|CUC|CUP|CVE|CZK|DJF|DKK|DOP|DZD|EEK|EGP|ERN|ETB|EUR|FJD|FKP|GBP|GEL|GHS|GIP|GMD|GNF|GTQ|GYD|HKD|HNL|HRK|HTG|HUF|IDR|ILS|INR|IQD|IRR|ISK|JMD|JOD|JPY|KES|KGS|KHR|KMF|KPW|KRW|KWD|KYD|KZT|LAK|LBP|LKR|LRD|LSL|LTL|LVL|LYD|MAD|MDL|MGA|MKD|MMK|MNT|MOP|MRO|MUR|MVR|MWK|MXN|MXV|MYR|MZN|NAD|NGN|NIO|NOK|NPR|NZD|OMR|PAB|PEN|PGK|PHP|PKR|PLN|PYG|QAR|RON|RSD|RUB|RWF|SAR|SBD|SCR|SDG|SEK|SGD|SHP|SLL|SOS|SRD|STD|SYP|SZL|THB|TJS|TMT|TND|TOP|TRY|TTD|TWD|TZS|UAH|UGX|USD|USN|USS|UYU|UZS|VEF|VND|VUV|WST|XAF|XAG|XAU|XBA|XBB|XBC|XBD|XCD|XDR|XFU|XOF|XPD|XPF|XPT|XTS|XXX|YER|ZAR|ZMK|ZWL::GBP', '', 'enum', '3', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_custom_status', '1', '1|0::0', '', 'bool', '2', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_datetime_format', '1', 'd.m.Y, H:i|d.m.Y, H:i:s|m.d.Y, H:i|m.d.Y, H:i:s|Y.m.d, H:i|Y.m.d, H:i:s|j.n.Y, H:i|j.n.Y, H:i:s|n.j.Y, H:i|n.j.Y, H:i:s|Y.n.j, H:i|Y.n.j, H:i:s|d/m/Y, H:i|d/m/Y, H:i:s|m/d/Y, H:i|m/d/Y, H:i:s|Y/m/d, H:i|Y/m/d, H:i:s|j/n/Y, H:i|j/n/Y, H:i:s|n/j/Y, H:i|n/j/Y, H:i:s|Y/n/j, H:i|Y/n/j, H:i:s|d-m-Y, H:i|d-m-Y, H:i:s|m-d-Y, H:i|m-d-Y, H:i:s|Y-m-d, H:i|Y-m-d, H:i:s|j-n-Y, H:i|j-n-Y, H:i:s|n-j-Y, H:i|n-j-Y, H:i:s|Y-n-j, H:i|Y-n-j, H:i:s::j/n/Y, H:i', 'd.m.Y, H:i (25.09.2010, 09:51)|d.m.Y, H:i:s (25.09.2010, 09:51:47)|m.d.Y, H:i (09.25.2010, 09:51)|m.d.Y, H:i:s (09.25.2010, 09:51:47)|Y.m.d, H:i (2010.09.25, 09:51)|Y.m.d, H:i:s (2010.09.25, 09:51:47)|j.n.Y, H:i (25.9.2010, 09:51)|j.n.Y, H:i:s (25.9.2010, 09:51:47)|n.j.Y, H:i (9.25.2010, 09:51)|n.j.Y, H:i:s (9.25.2010, 09:51:47)|Y.n.j, H:i (2010.9.25, 09:51)|Y.n.j, H:i:s (2010.9.25, 09:51:47)|d/m/Y, H:i (25/09/2010, 09:51)|d/m/Y, H:i:s (25/09/2010, 09:51:47)|m/d/Y, H:i (09/25/2010, 09:51)|m/d/Y, H:i:s (09/25/2010, 09:51:47)|Y/m/d, H:i (2010/09/25, 09:51)|Y/m/d, H:i:s (2010/09/25, 09:51:47)|j/n/Y, H:i (25/9/2010, 09:51)|j/n/Y, H:i:s (25/9/2010, 09:51:47)|n/j/Y, H:i (9/25/2010, 09:51)|n/j/Y, H:i:s (9/25/2010, 09:51:47)|Y/n/j, H:i (2010/9/25, 09:51)|Y/n/j, H:i:s (2010/9/25, 09:51:47)|d-m-Y, H:i (25-09-2010, 09:51)|d-m-Y, H:i:s (25-09-2010, 09:51:47)|m-d-Y, H:i (09-25-2010, 09:51)|m-d-Y, H:i:s (09-25-2010, 09:51:47)|Y-m-d, H:i (2010-09-25, 09:51)|Y-m-d, H:i:s (2010-09-25, 09:51:47)|j-n-Y, H:i (25-9-2010, 09:51)|j-n-Y, H:i:s (25-9-2010, 09:51:47)|n-j-Y, H:i (9-25-2010, 09:51)|n-j-Y, H:i:s (9-25-2010, 09:51:47)|Y-n-j, H:i (2010-9-25, 09:51)|Y-n-j, H:i:s (2010-9-25, 09:51:47)', 'enum', '8', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_date_format', '1', 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::d-m-Y', 'd.m.Y (25.09.2012)|m.d.Y (09.25.2012)|Y.m.d (2012.09.25)|j.n.Y (25.9.2012)|n.j.Y (9.25.2012)|Y.n.j (2012.9.25)|d/m/Y (25/09/2012)|m/d/Y (09/25/2012)|Y/m/d (2012/09/25)|j/n/Y (25/9/2012)|n/j/Y (9/25/2012)|Y/n/j (2012/9/25)|d-m-Y (25-09-2012)|m-d-Y (09-25-2012)|Y-m-d (2012-09-25)|j-n-Y (25-9-2012)|n-j-Y (9-25-2012)|Y-n-j (2012-9-25)', 'enum', '6', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_deposit', '7', '20', '', 'float', '12', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_deposit_type', '7', 'amount|percent::percent', 'Amount|Percent', 'enum', '', '0', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_disable_payments', '7', '1|0::1', '', 'bool', '4', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_hide_prices', '3', '1|0::0', '', 'bool', '2', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_layout', '1', '1|2|3::1', 'Layout 1|Layout 2|Layout 3', 'enum', '1', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_layout_backend', '1', '1|2::2', 'Layout 1|Layout 2', 'enum', '1', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_multi_lang', '99', '1|0::0', '', 'enum', '', '0', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_paypal_address', '7', 'paypal_seller@example.com', '', 'string', '17', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_reminder_body', '8', 'Hei {Name},\r\r\n\r\r\nTama on muistutus viesti varauksestasi!\r\r\n\r\r\nVaraus id: {BookingID}\r\r\n\r\r\nPalvelut\r\r\n{Services}\r\r\n\r\r\nTerveisin,', '', 'text', '4', '1', 'height:350px');
INSERT INTO `as_options` VALUES('1', '4', 'o_reminder_email_before', '8', '10', '', 'int', '2', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_reminder_enable', '8', '1|0::1', '', 'bool', '1', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_reminder_sms_country_code', '8', '358', 'SMS country code', 'string', '5', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_reminder_sms_hours', '8', '2', '', 'int', '5', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_reminder_sms_message', '8', 'Hei {Name}, tama on muistutusviesti varauksestasi.\r\r\n\r\r\nTerveisin,.', '', 'text', '7', '1', 'height:200px');
INSERT INTO `as_options` VALUES('1', '4', 'o_reminder_sms_send_address', '8', 'varaa.com', 'SMS send address', 'string', '6', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_reminder_subject', '8', 'Booking Reminder', '', 'string', '3', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_send_email', '1', 'mail|smtp::mail', 'PHP mail()|SMTP', 'enum', '11', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_seo_url', '1', '1|0::0', '', 'bool', '20', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_smtp_host', '1', '', '', 'string', '12', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_smtp_pass', '1', '', '', 'string', '15', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_smtp_port', '1', '25', '', 'int', '13', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_smtp_user', '1', '', '', 'string', '14', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_status_if_not_paid', '3', 'confirmed|pending::confirmed', 'Confirmed|Pending', 'enum', '10', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_status_if_paid', '3', 'confirmed|pending::confirmed', 'Confirmed|Pending', 'enum', '9', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_step', '3', '5|10|15|20|25|30|35|40|45|50|55|60::15', '', 'enum', '3', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_tax', '7', '10', '', 'float', '14', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_thankyou_page', '7', 'http://varaa.com/', '', 'string', '26', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_timezone', '1', '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', '10', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_time_format', '1', 'H:i|G:i|h:i|h:i a|h:i A|g:i|g:i a|g:i A::H:i', 'H:i (09:45)|G:i (9:45)|h:i (09:45)|h:i a (09:45 am)|h:i A (09:45 AM)|g:i (9:45)|g:i a (9:45 am)|g:i A (9:45 AM)', 'enum', '7', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_week_numbers', '1', '1|0::0', '', 'bool', '19', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'o_week_start', '1', '0|1|2|3|4|5|6::1', 'Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday', 'enum', '9', '1', '');
INSERT INTO `as_options` VALUES('1', '4', 'private_key', '99', 'R9/Oloz+U9YjLmSfenqcRiISDlI09LDR1WViqtYe/vxshtThVDXLQFxA2U5mIkYd3vy42wtW1j6C33ndue/jpSe3DeV8NPZxVIS4B87R3cCCY7L1bGrLQL5P49l4FBfJzlncUYoE9dCq7h1EPZTjV7HS9mSvfiPnvdyXt0mE2PerPdl+LNFtmeefHkHpJei6FvELm01Cep3bVP5lq/fmTimq+gmj3SB92LbPdFQpYmAFn1+dTTOqb97zOpuMeqcf9J4+/vRwemasu1lx4nmeCH+h8j/f4FBdNZZbbJ7g7dmHF949qPpqE24kCP/YU3KgxDAhiy1m79qrqpnQE3Ey1A==', '', 'string', '', '1', 'string');

DROP TABLE IF EXISTS `as_plugin_country`;

CREATE TABLE `as_plugin_country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `alpha_2` varchar(2) DEFAULT NULL,
  `alpha_3` varchar(3) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha_2` (`alpha_2`),
  UNIQUE KEY `alpha_3` (`alpha_3`),
  KEY `nuser_id` (`owner_id`),
  KEY `status` (`status`),
  CONSTRAINT `as_plugin_country_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8;

INSERT INTO `as_plugin_country` VALUES('1', '4', 'AF', 'AFG', 'T');
INSERT INTO `as_plugin_country` VALUES('2', '4', 'AX', 'ALA', 'T');
INSERT INTO `as_plugin_country` VALUES('3', '4', 'AL', 'ALB', 'T');
INSERT INTO `as_plugin_country` VALUES('4', '4', 'DZ', 'DZA', 'T');
INSERT INTO `as_plugin_country` VALUES('5', '4', 'AS', 'ASM', 'T');
INSERT INTO `as_plugin_country` VALUES('6', '4', 'AD', 'AND', 'T');
INSERT INTO `as_plugin_country` VALUES('7', '4', 'AO', 'AGO', 'T');
INSERT INTO `as_plugin_country` VALUES('8', '4', 'AI', 'AIA', 'T');
INSERT INTO `as_plugin_country` VALUES('9', '4', 'AQ', 'ATA', 'T');
INSERT INTO `as_plugin_country` VALUES('10', '4', 'AG', 'ATG', 'T');
INSERT INTO `as_plugin_country` VALUES('11', '4', 'AR', 'ARG', 'T');
INSERT INTO `as_plugin_country` VALUES('12', '4', 'AM', 'ARM', 'T');
INSERT INTO `as_plugin_country` VALUES('13', '4', 'AW', 'ABW', 'T');
INSERT INTO `as_plugin_country` VALUES('14', '4', 'AU', 'AUS', 'T');
INSERT INTO `as_plugin_country` VALUES('15', '4', 'AT', 'AUT', 'T');
INSERT INTO `as_plugin_country` VALUES('16', '4', 'AZ', 'AZE', 'T');
INSERT INTO `as_plugin_country` VALUES('17', '4', 'BS', 'BHS', 'T');
INSERT INTO `as_plugin_country` VALUES('18', '4', 'BH', 'BHR', 'T');
INSERT INTO `as_plugin_country` VALUES('19', '4', 'BD', 'BGD', 'T');
INSERT INTO `as_plugin_country` VALUES('20', '4', 'BB', 'BRB', 'T');
INSERT INTO `as_plugin_country` VALUES('21', '4', 'BY', 'BLR', 'T');
INSERT INTO `as_plugin_country` VALUES('22', '4', 'BE', 'BEL', 'T');
INSERT INTO `as_plugin_country` VALUES('23', '4', 'BZ', 'BLZ', 'T');
INSERT INTO `as_plugin_country` VALUES('24', '4', 'BJ', 'BEN', 'T');
INSERT INTO `as_plugin_country` VALUES('25', '4', 'BM', 'BMU', 'T');
INSERT INTO `as_plugin_country` VALUES('26', '4', 'BT', 'BTN', 'T');
INSERT INTO `as_plugin_country` VALUES('27', '4', 'BO', 'BOL', 'T');
INSERT INTO `as_plugin_country` VALUES('28', '4', 'BQ', 'BES', 'T');
INSERT INTO `as_plugin_country` VALUES('29', '4', 'BA', 'BIH', 'T');
INSERT INTO `as_plugin_country` VALUES('30', '4', 'BW', 'BWA', 'T');
INSERT INTO `as_plugin_country` VALUES('31', '4', 'BV', 'BVT', 'T');
INSERT INTO `as_plugin_country` VALUES('32', '4', 'BR', 'BRA', 'T');
INSERT INTO `as_plugin_country` VALUES('33', '4', 'IO', 'IOT', 'T');
INSERT INTO `as_plugin_country` VALUES('34', '4', 'BN', 'BRN', 'T');
INSERT INTO `as_plugin_country` VALUES('35', '4', 'BG', 'BGR', 'T');
INSERT INTO `as_plugin_country` VALUES('36', '4', 'BF', 'BFA', 'T');
INSERT INTO `as_plugin_country` VALUES('37', '4', 'BI', 'BDI', 'T');
INSERT INTO `as_plugin_country` VALUES('38', '4', 'KH', 'KHM', 'T');
INSERT INTO `as_plugin_country` VALUES('39', '4', 'CM', 'CMR', 'T');
INSERT INTO `as_plugin_country` VALUES('40', '4', 'CA', 'CAN', 'T');
INSERT INTO `as_plugin_country` VALUES('41', '4', 'CV', 'CPV', 'T');
INSERT INTO `as_plugin_country` VALUES('42', '4', 'KY', 'CYM', 'T');
INSERT INTO `as_plugin_country` VALUES('43', '4', 'CF', 'CAF', 'T');
INSERT INTO `as_plugin_country` VALUES('44', '4', 'TD', 'TCD', 'T');
INSERT INTO `as_plugin_country` VALUES('45', '4', 'CL', 'CHL', 'T');
INSERT INTO `as_plugin_country` VALUES('46', '4', 'CN', 'CHN', 'T');
INSERT INTO `as_plugin_country` VALUES('47', '4', 'CX', 'CXR', 'T');
INSERT INTO `as_plugin_country` VALUES('48', '4', 'CC', 'CCK', 'T');
INSERT INTO `as_plugin_country` VALUES('49', '4', 'CO', 'COL', 'T');
INSERT INTO `as_plugin_country` VALUES('50', '4', 'KM', 'COM', 'T');
INSERT INTO `as_plugin_country` VALUES('51', '4', 'CG', 'COG', 'T');
INSERT INTO `as_plugin_country` VALUES('52', '4', 'CD', 'COD', 'T');
INSERT INTO `as_plugin_country` VALUES('53', '4', 'CK', 'COK', 'T');
INSERT INTO `as_plugin_country` VALUES('54', '4', 'CR', 'CRI', 'T');
INSERT INTO `as_plugin_country` VALUES('55', '4', 'CI', 'CIV', 'T');
INSERT INTO `as_plugin_country` VALUES('56', '4', 'HR', 'HRV', 'T');
INSERT INTO `as_plugin_country` VALUES('57', '4', 'CU', 'CUB', 'T');
INSERT INTO `as_plugin_country` VALUES('58', '4', 'CW', 'CUW', 'T');
INSERT INTO `as_plugin_country` VALUES('59', '4', 'CY', 'CYP', 'T');
INSERT INTO `as_plugin_country` VALUES('60', '4', 'CZ', 'CZE', 'T');
INSERT INTO `as_plugin_country` VALUES('61', '4', 'DK', 'DNK', 'T');
INSERT INTO `as_plugin_country` VALUES('62', '4', 'DJ', 'DJI', 'T');
INSERT INTO `as_plugin_country` VALUES('63', '4', 'DM', 'DMA', 'T');
INSERT INTO `as_plugin_country` VALUES('64', '4', 'DO', 'DOM', 'T');
INSERT INTO `as_plugin_country` VALUES('65', '4', 'EC', 'ECU', 'T');
INSERT INTO `as_plugin_country` VALUES('66', '4', 'EG', 'EGY', 'T');
INSERT INTO `as_plugin_country` VALUES('67', '4', 'SV', 'SLV', 'T');
INSERT INTO `as_plugin_country` VALUES('68', '4', 'GQ', 'GNQ', 'T');
INSERT INTO `as_plugin_country` VALUES('69', '4', 'ER', 'ERI', 'T');
INSERT INTO `as_plugin_country` VALUES('70', '4', 'EE', 'EST', 'T');
INSERT INTO `as_plugin_country` VALUES('71', '4', 'ET', 'ETH', 'T');
INSERT INTO `as_plugin_country` VALUES('72', '4', 'FK', 'FLK', 'T');
INSERT INTO `as_plugin_country` VALUES('73', '4', 'FO', 'FRO', 'T');
INSERT INTO `as_plugin_country` VALUES('74', '4', 'FJ', 'FJI', 'T');
INSERT INTO `as_plugin_country` VALUES('75', '4', 'FI', 'FIN', 'T');
INSERT INTO `as_plugin_country` VALUES('76', '4', 'FR', 'FRA', 'T');
INSERT INTO `as_plugin_country` VALUES('77', '4', 'GF', 'GUF', 'T');
INSERT INTO `as_plugin_country` VALUES('78', '4', 'PF', 'PYF', 'T');
INSERT INTO `as_plugin_country` VALUES('79', '4', 'TF', 'ATF', 'T');
INSERT INTO `as_plugin_country` VALUES('80', '4', 'GA', 'GAB', 'T');
INSERT INTO `as_plugin_country` VALUES('81', '4', 'GM', 'GMB', 'T');
INSERT INTO `as_plugin_country` VALUES('82', '4', 'GE', 'GEO', 'T');
INSERT INTO `as_plugin_country` VALUES('83', '4', 'DE', 'DEU', 'T');
INSERT INTO `as_plugin_country` VALUES('84', '4', 'GH', 'GHA', 'T');
INSERT INTO `as_plugin_country` VALUES('85', '4', 'GI', 'GIB', 'T');
INSERT INTO `as_plugin_country` VALUES('86', '4', 'GR', 'GRC', 'T');
INSERT INTO `as_plugin_country` VALUES('87', '4', 'GL', 'GRL', 'T');
INSERT INTO `as_plugin_country` VALUES('88', '4', 'GD', 'GRD', 'T');
INSERT INTO `as_plugin_country` VALUES('89', '4', 'GP', 'GLP', 'T');
INSERT INTO `as_plugin_country` VALUES('90', '4', 'GU', 'GUM', 'T');
INSERT INTO `as_plugin_country` VALUES('91', '4', 'GT', 'GTM', 'T');
INSERT INTO `as_plugin_country` VALUES('92', '4', 'GG', 'GGY', 'T');
INSERT INTO `as_plugin_country` VALUES('93', '4', 'GN', 'GIN', 'T');
INSERT INTO `as_plugin_country` VALUES('94', '4', 'GW', 'GNB', 'T');
INSERT INTO `as_plugin_country` VALUES('95', '4', 'GY', 'GUY', 'T');
INSERT INTO `as_plugin_country` VALUES('96', '4', 'HT', 'HTI', 'T');
INSERT INTO `as_plugin_country` VALUES('97', '4', 'HM', 'HMD', 'T');
INSERT INTO `as_plugin_country` VALUES('98', '4', 'VA', 'VAT', 'T');
INSERT INTO `as_plugin_country` VALUES('99', '4', 'HN', 'HND', 'T');
INSERT INTO `as_plugin_country` VALUES('100', '4', 'HK', 'HKG', 'T');
INSERT INTO `as_plugin_country` VALUES('101', '4', 'HU', 'HUN', 'T');
INSERT INTO `as_plugin_country` VALUES('102', '4', 'IS', 'ISL', 'T');
INSERT INTO `as_plugin_country` VALUES('103', '4', 'IN', 'IND', 'T');
INSERT INTO `as_plugin_country` VALUES('104', '4', 'ID', 'IDN', 'T');
INSERT INTO `as_plugin_country` VALUES('105', '4', 'IR', 'IRN', 'T');
INSERT INTO `as_plugin_country` VALUES('106', '4', 'IQ', 'IRQ', 'T');
INSERT INTO `as_plugin_country` VALUES('107', '4', 'IE', 'IRL', 'T');
INSERT INTO `as_plugin_country` VALUES('108', '4', 'IM', 'IMN', 'T');
INSERT INTO `as_plugin_country` VALUES('109', '4', 'IL', 'ISR', 'T');
INSERT INTO `as_plugin_country` VALUES('110', '4', 'IT', 'ITA', 'T');
INSERT INTO `as_plugin_country` VALUES('111', '4', 'JM', 'JAM', 'T');
INSERT INTO `as_plugin_country` VALUES('112', '4', 'JP', 'JPN', 'T');
INSERT INTO `as_plugin_country` VALUES('113', '4', 'JE', 'JEY', 'T');
INSERT INTO `as_plugin_country` VALUES('114', '4', 'JO', 'JOR', 'T');
INSERT INTO `as_plugin_country` VALUES('115', '4', 'KZ', 'KAZ', 'T');
INSERT INTO `as_plugin_country` VALUES('116', '4', 'KE', 'KEN', 'T');
INSERT INTO `as_plugin_country` VALUES('117', '4', 'KI', 'KIR', 'T');
INSERT INTO `as_plugin_country` VALUES('118', '4', 'KP', 'PRK', 'T');
INSERT INTO `as_plugin_country` VALUES('119', '4', 'KR', 'KOR', 'T');
INSERT INTO `as_plugin_country` VALUES('120', '4', 'KW', 'KWT', 'T');
INSERT INTO `as_plugin_country` VALUES('121', '4', 'KG', 'KGZ', 'T');
INSERT INTO `as_plugin_country` VALUES('122', '4', 'LA', 'LAO', 'T');
INSERT INTO `as_plugin_country` VALUES('123', '4', 'LV', 'LVA', 'T');
INSERT INTO `as_plugin_country` VALUES('124', '4', 'LB', 'LBN', 'T');
INSERT INTO `as_plugin_country` VALUES('125', '4', 'LS', 'LSO', 'T');
INSERT INTO `as_plugin_country` VALUES('126', '4', 'LR', 'LBR', 'T');
INSERT INTO `as_plugin_country` VALUES('127', '4', 'LY', 'LBY', 'T');
INSERT INTO `as_plugin_country` VALUES('128', '4', 'LI', 'LIE', 'T');
INSERT INTO `as_plugin_country` VALUES('129', '4', 'LT', 'LTU', 'T');
INSERT INTO `as_plugin_country` VALUES('130', '4', 'LU', 'LUX', 'T');
INSERT INTO `as_plugin_country` VALUES('131', '4', 'MO', 'MAC', 'T');
INSERT INTO `as_plugin_country` VALUES('132', '4', 'MK', 'MKD', 'T');
INSERT INTO `as_plugin_country` VALUES('133', '4', 'MG', 'MDG', 'T');
INSERT INTO `as_plugin_country` VALUES('134', '4', 'MW', 'MWI', 'T');
INSERT INTO `as_plugin_country` VALUES('135', '4', 'MY', 'MYS', 'T');
INSERT INTO `as_plugin_country` VALUES('136', '4', 'MV', 'MDV', 'T');
INSERT INTO `as_plugin_country` VALUES('137', '4', 'ML', 'MLI', 'T');
INSERT INTO `as_plugin_country` VALUES('138', '4', 'MT', 'MLT', 'T');
INSERT INTO `as_plugin_country` VALUES('139', '4', 'MH', 'MHL', 'T');
INSERT INTO `as_plugin_country` VALUES('140', '4', 'MQ', 'MTQ', 'T');
INSERT INTO `as_plugin_country` VALUES('141', '4', 'MR', 'MRT', 'T');
INSERT INTO `as_plugin_country` VALUES('142', '4', 'MU', 'MUS', 'T');
INSERT INTO `as_plugin_country` VALUES('143', '4', 'YT', 'MYT', 'T');
INSERT INTO `as_plugin_country` VALUES('144', '4', 'MX', 'MEX', 'T');
INSERT INTO `as_plugin_country` VALUES('145', '4', 'FM', 'FSM', 'T');
INSERT INTO `as_plugin_country` VALUES('146', '4', 'MD', 'MDA', 'T');
INSERT INTO `as_plugin_country` VALUES('147', '4', 'MC', 'MCO', 'T');
INSERT INTO `as_plugin_country` VALUES('148', '4', 'MN', 'MNG', 'T');
INSERT INTO `as_plugin_country` VALUES('149', '4', 'ME', 'MNE', 'T');
INSERT INTO `as_plugin_country` VALUES('150', '4', 'MS', 'MSR', 'T');
INSERT INTO `as_plugin_country` VALUES('151', '4', 'MA', 'MAR', 'T');
INSERT INTO `as_plugin_country` VALUES('152', '4', 'MZ', 'MOZ', 'T');
INSERT INTO `as_plugin_country` VALUES('153', '4', 'MM', 'MMR', 'T');
INSERT INTO `as_plugin_country` VALUES('154', '4', 'NA', 'NAM', 'T');
INSERT INTO `as_plugin_country` VALUES('155', '4', 'NR', 'NRU', 'T');
INSERT INTO `as_plugin_country` VALUES('156', '4', 'NP', 'NPL', 'T');
INSERT INTO `as_plugin_country` VALUES('157', '4', 'NL', 'NLD', 'T');
INSERT INTO `as_plugin_country` VALUES('158', '4', 'NC', 'NCL', 'T');
INSERT INTO `as_plugin_country` VALUES('159', '4', 'NZ', 'NZL', 'T');
INSERT INTO `as_plugin_country` VALUES('160', '4', 'NI', 'NIC', 'T');
INSERT INTO `as_plugin_country` VALUES('161', '4', 'NE', 'NER', 'T');
INSERT INTO `as_plugin_country` VALUES('162', '4', 'NG', 'NGA', 'T');
INSERT INTO `as_plugin_country` VALUES('163', '4', 'NU', 'NIU', 'T');
INSERT INTO `as_plugin_country` VALUES('164', '4', 'NF', 'NFK', 'T');
INSERT INTO `as_plugin_country` VALUES('165', '4', 'MP', 'MNP', 'T');
INSERT INTO `as_plugin_country` VALUES('166', '4', 'NO', 'NOR', 'T');
INSERT INTO `as_plugin_country` VALUES('167', '4', 'OM', 'OMN', 'T');
INSERT INTO `as_plugin_country` VALUES('168', '4', 'PK', 'PAK', 'T');
INSERT INTO `as_plugin_country` VALUES('169', '4', 'PW', 'PLW', 'T');
INSERT INTO `as_plugin_country` VALUES('170', '4', 'PS', 'PSE', 'T');
INSERT INTO `as_plugin_country` VALUES('171', '4', 'PA', 'PAN', 'T');
INSERT INTO `as_plugin_country` VALUES('172', '4', 'PG', 'PNG', 'T');
INSERT INTO `as_plugin_country` VALUES('173', '4', 'PY', 'PRY', 'T');
INSERT INTO `as_plugin_country` VALUES('174', '4', 'PE', 'PER', 'T');
INSERT INTO `as_plugin_country` VALUES('175', '4', 'PH', 'PHL', 'T');
INSERT INTO `as_plugin_country` VALUES('176', '4', 'PN', 'PCN', 'T');
INSERT INTO `as_plugin_country` VALUES('177', '4', 'PL', 'POL', 'T');
INSERT INTO `as_plugin_country` VALUES('178', '4', 'PT', 'PRT', 'T');
INSERT INTO `as_plugin_country` VALUES('179', '4', 'PR', 'PRI', 'T');
INSERT INTO `as_plugin_country` VALUES('180', '4', 'QA', 'QAT', 'T');
INSERT INTO `as_plugin_country` VALUES('181', '4', 'RE', 'REU', 'T');
INSERT INTO `as_plugin_country` VALUES('182', '4', 'RO', 'ROU', 'T');
INSERT INTO `as_plugin_country` VALUES('183', '4', 'RU', 'RUS', 'T');
INSERT INTO `as_plugin_country` VALUES('184', '4', 'RW', 'RWA', 'T');
INSERT INTO `as_plugin_country` VALUES('185', '4', 'BL', 'BLM', 'T');
INSERT INTO `as_plugin_country` VALUES('186', '4', 'SH', 'SHN', 'T');
INSERT INTO `as_plugin_country` VALUES('187', '4', 'KN', 'KNA', 'T');
INSERT INTO `as_plugin_country` VALUES('188', '4', 'LC', 'LCA', 'T');
INSERT INTO `as_plugin_country` VALUES('189', '4', 'MF', 'MAF', 'T');
INSERT INTO `as_plugin_country` VALUES('190', '4', 'PM', 'SPM', 'T');
INSERT INTO `as_plugin_country` VALUES('191', '4', 'VC', 'VCT', 'T');
INSERT INTO `as_plugin_country` VALUES('192', '4', 'WS', 'WSM', 'T');
INSERT INTO `as_plugin_country` VALUES('193', '4', 'SM', 'SMR', 'T');
INSERT INTO `as_plugin_country` VALUES('194', '4', 'ST', 'STP', 'T');
INSERT INTO `as_plugin_country` VALUES('195', '4', 'SA', 'SAU', 'T');
INSERT INTO `as_plugin_country` VALUES('196', '4', 'SN', 'SEN', 'T');
INSERT INTO `as_plugin_country` VALUES('197', '4', 'RS', 'SRB', 'T');
INSERT INTO `as_plugin_country` VALUES('198', '4', 'SC', 'SYC', 'T');
INSERT INTO `as_plugin_country` VALUES('199', '4', 'SL', 'SLE', 'T');
INSERT INTO `as_plugin_country` VALUES('200', '4', 'SG', 'SGP', 'T');
INSERT INTO `as_plugin_country` VALUES('201', '4', 'SX', 'SXM', 'T');
INSERT INTO `as_plugin_country` VALUES('202', '4', 'SK', 'SVK', 'T');
INSERT INTO `as_plugin_country` VALUES('203', '4', 'SI', 'SVN', 'T');
INSERT INTO `as_plugin_country` VALUES('204', '4', 'SB', 'SLB', 'T');
INSERT INTO `as_plugin_country` VALUES('205', '4', 'SO', 'SOM', 'T');
INSERT INTO `as_plugin_country` VALUES('206', '4', 'ZA', 'ZAF', 'T');
INSERT INTO `as_plugin_country` VALUES('207', '4', 'GS', 'SGS', 'T');
INSERT INTO `as_plugin_country` VALUES('208', '4', 'SS', 'SSD', 'T');
INSERT INTO `as_plugin_country` VALUES('209', '4', 'ES', 'ESP', 'T');
INSERT INTO `as_plugin_country` VALUES('210', '4', 'LK', 'LKA', 'T');
INSERT INTO `as_plugin_country` VALUES('211', '4', 'SD', 'SDN', 'T');
INSERT INTO `as_plugin_country` VALUES('212', '4', 'SR', 'SUR', 'T');
INSERT INTO `as_plugin_country` VALUES('213', '4', 'SJ', 'SJM', 'T');
INSERT INTO `as_plugin_country` VALUES('214', '4', 'SZ', 'SWZ', 'T');
INSERT INTO `as_plugin_country` VALUES('215', '4', 'SE', 'SWE', 'T');
INSERT INTO `as_plugin_country` VALUES('216', '4', 'CH', 'CHE', 'T');
INSERT INTO `as_plugin_country` VALUES('217', '4', 'SY', 'SYR', 'T');
INSERT INTO `as_plugin_country` VALUES('218', '4', 'TW', 'TWN', 'T');
INSERT INTO `as_plugin_country` VALUES('219', '4', 'TJ', 'TJK', 'T');
INSERT INTO `as_plugin_country` VALUES('220', '4', 'TZ', 'TZA', 'T');
INSERT INTO `as_plugin_country` VALUES('221', '4', 'TH', 'THA', 'T');
INSERT INTO `as_plugin_country` VALUES('222', '4', 'TL', 'TLS', 'T');
INSERT INTO `as_plugin_country` VALUES('223', '4', 'TG', 'TGO', 'T');
INSERT INTO `as_plugin_country` VALUES('224', '4', 'TK', 'TKL', 'T');
INSERT INTO `as_plugin_country` VALUES('225', '4', 'TO', 'TON', 'T');
INSERT INTO `as_plugin_country` VALUES('226', '4', 'TT', 'TTO', 'T');
INSERT INTO `as_plugin_country` VALUES('227', '4', 'TN', 'TUN', 'T');
INSERT INTO `as_plugin_country` VALUES('228', '4', 'TR', 'TUR', 'T');
INSERT INTO `as_plugin_country` VALUES('229', '4', 'TM', 'TKM', 'T');
INSERT INTO `as_plugin_country` VALUES('230', '4', 'TC', 'TCA', 'T');
INSERT INTO `as_plugin_country` VALUES('231', '4', 'TV', 'TUV', 'T');
INSERT INTO `as_plugin_country` VALUES('232', '4', 'UG', 'UGA', 'T');
INSERT INTO `as_plugin_country` VALUES('233', '4', 'UA', 'UKR', 'T');
INSERT INTO `as_plugin_country` VALUES('234', '4', 'AE', 'ARE', 'T');
INSERT INTO `as_plugin_country` VALUES('235', '4', 'GB', 'GBR', 'T');
INSERT INTO `as_plugin_country` VALUES('236', '4', 'US', 'USA', 'T');
INSERT INTO `as_plugin_country` VALUES('237', '4', 'UM', 'UMI', 'T');
INSERT INTO `as_plugin_country` VALUES('238', '4', 'UY', 'URY', 'T');
INSERT INTO `as_plugin_country` VALUES('239', '4', 'UZ', 'UZB', 'T');
INSERT INTO `as_plugin_country` VALUES('240', '4', 'VU', 'VUT', 'T');
INSERT INTO `as_plugin_country` VALUES('241', '4', 'VE', 'VEN', 'T');
INSERT INTO `as_plugin_country` VALUES('242', '4', 'VN', 'VNM', 'T');
INSERT INTO `as_plugin_country` VALUES('243', '4', 'VG', 'VGB', 'T');
INSERT INTO `as_plugin_country` VALUES('244', '4', 'VI', 'VIR', 'T');
INSERT INTO `as_plugin_country` VALUES('245', '4', 'WF', 'WLF', 'T');
INSERT INTO `as_plugin_country` VALUES('246', '4', 'EH', 'ESH', 'T');
INSERT INTO `as_plugin_country` VALUES('247', '4', 'YE', 'YEM', 'T');
INSERT INTO `as_plugin_country` VALUES('248', '4', 'ZM', 'ZMB', 'T');
INSERT INTO `as_plugin_country` VALUES('249', '4', 'ZW', 'ZWE', 'T');

DROP TABLE IF EXISTS `as_plugin_invoice`;

CREATE TABLE `as_plugin_invoice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
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
  KEY `nuser_id` (`owner_id`),
  KEY `order_id` (`order_id`),
  KEY `foreign_id` (`foreign_id`),
  CONSTRAINT `as_plugin_invoice_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

INSERT INTO `as_plugin_invoice` VALUES('26', '4', 'DO1406413959', 'XZ1406413959', '1', '2014-07-26', '2014-07-26', '2014-07-26 22:32:39', '', 'paid', '', '', '22.00', '0.00', '2.00', '0.00', '22.00', '0.00', '0.00', 'GBP', '', '', 'Test', 'asdad', 'aadadMadison Square', 'asdasda', 'NY', '11222', '(111) 222 3333', '(222) 333 4444', 'info@domain.com', 'http://www.google.com/', '', '', 'adasd', '', '', '', '', '', 'adadasd', '', 'dasd@2okoisad.com', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0');
INSERT INTO `as_plugin_invoice` VALUES('27', '4', 'EH1406454321', 'NT1406454321', '1', '2014-07-27', '2014-07-27', '2014-07-27 09:45:22', '', 'paid', '', '', '44.00', '0.00', '4.00', '0.00', '44.00', '0.00', '0.00', 'GBP', '', '', 'Test', 'asdad', 'Madison Square', 'asdasda', 'NY', '11222', '(111) 222 3333', '(222) 333 4444', 'info@domain.com', 'http://www.google.com/', '', '', 'adsa', '', '', '', '', '', '132123132', '', 'adasdas@adasd.com', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0');
INSERT INTO `as_plugin_invoice` VALUES('28', '4', 'MC1406468316', 'ZQ1406468316', '1', '2014-07-27', '2014-07-27', '2014-07-27 13:38:36', '', 'paid', '', '', '16.50', '0.00', '1.50', '0.00', '16.50', '0.00', '0.00', 'EUR', '', '', 'Test', 'asdad', 'Madison Square', 'asdasda', 'NY', '11222', '(111) 222 3333', '(222) 333 4444', 'info@domain.com', 'http://www.google.com/', '', '', 'adsada', '', '', '', '', '', 'dasdasda', '', 'asdas@yadhaso.com', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0');
INSERT INTO `as_plugin_invoice` VALUES('29', '4', 'DE1406471734', 'XP1406471734', '1', '2014-07-27', '2014-07-27', '2014-07-27 14:35:35', '', 'paid', '', '', '16.50', '0.00', '1.50', '0.00', '16.50', '0.00', '0.00', 'EUR', '', '', 'Test', 'asdad', 'Madison Square', 'asdasda', 'NY', '11222', '(111) 222 3333', '(222) 333 4444', 'info@domain.com', 'http://www.google.com/', '', '', 'test', '', '', '', '', '', '12123', '', 'test@admin.com', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0');

DROP TABLE IF EXISTS `as_plugin_invoice_config`;

CREATE TABLE `as_plugin_invoice_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_plugin_invoice_config_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `as_plugin_invoice_config` VALUES('1', '4', '', 'Test', 'asdad', 'Madison Square', 'asdasda', 'NY', '11222', '(111) 222 3333', '(222) 333 4444', 'info@domain.com', 'http://www.google.com/', '<table style="width: 100%;" border="0">\r\n<tbody>\r\n<tr>\r\n<td style="width: 50%;"><strong>{y_company}</strong></td>\r\n<td><strong>Invoice no.</strong> {uuid}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td><strong>Date</strong> {issue_date}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<table style="width: 85%;" border="0" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;"><strong>Bill To:</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>{b_name}</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>{b_company}</strong></td>\r\n</tr>\r\n<tr>\r\n<td>{b_billing_address}</td>\r\n</tr>\r\n<tr>\r\n<td>{b_city}</td>\r\n</tr>\r\n<tr>\r\n<td>{b_state} {b_zip}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>Phone: {b_phone}, Fax: {b_fax}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<p style="text-align: center;"><span style="font-size: large;"><strong>Invoice</strong></span></p>\r\n<p>{items}</p>\r\n<p>&nbsp;</p>\r\n<table style="width: 100%;" border="0">\r\n<tbody>\r\n<tr>\r\n<td>Note:</td>\r\n<td style="text-align: right;">SubTotal:</td>\r\n<td style="text-align: right;">{subtotal}</td>\r\n</tr>\r\n<tr>\r\n<td>Thanks for your business!</td>\r\n<td style="text-align: right;">Discount:</td>\r\n<td style="text-align: right;">{discount}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;"><strong>Total:</strong></td>\r\n<td style="text-align: right;"><strong>{total}</strong></td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;">Deposit:</td>\r\n<td style="text-align: right;">{paid_deposit}</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td style="text-align: right;"><strong>Amount Due:</strong></td>\r\n<td style="text-align: right;"><strong>{amount_due}</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<table style="width: 100%; border-collapse: collapse;" border="0">\r\n<tbody>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;"><strong>{y_company}</strong></td>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>{y_street_address}</td>\r\n<td>Phone: {y_phone}</td>\r\n</tr>\r\n<tr>\r\n<td>{y_city}</td>\r\n<td>Email: {y_email}</td>\r\n</tr>\r\n<tr>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">{y_state} {y_zip}</td>\r\n<td style="border-bottom-style: solid; border-bottom-width: 1px;">Website: {y_url}</td>\r\n</tr>\r\n</tbody>\r\n</table>', '1', '1', '0', '1', '1', 'info@domain.com', '0', '', '', '', 'bank account information goes here', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'index.php?controller=pjAdminBookings&action=pjActionUpdate&uuid={ORDER_ID}', '0');

DROP TABLE IF EXISTS `as_plugin_invoice_items`;

CREATE TABLE `as_plugin_invoice_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `invoice_id` int(10) unsigned DEFAULT NULL,
  `tmp` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` tinytext,
  `qty` decimal(9,2) unsigned DEFAULT NULL,
  `unit_price` decimal(9,2) unsigned DEFAULT NULL,
  `amount` decimal(9,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `as_plugin_invoice_items_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

INSERT INTO `as_plugin_invoice_items` VALUES('23', '4', '19', '', 'Service 1', '', '1.00', '10.00', '10.00');
INSERT INTO `as_plugin_invoice_items` VALUES('24', '4', '20', '', 'Service 5', '', '1.00', '50.00', '50.00');
INSERT INTO `as_plugin_invoice_items` VALUES('25', '4', '21', '', 'Service 5', '', '1.00', '50.00', '50.00');
INSERT INTO `as_plugin_invoice_items` VALUES('26', '4', '22', '', 'Service 3', '', '1.00', '30.00', '30.00');
INSERT INTO `as_plugin_invoice_items` VALUES('27', '4', '23', '', 'Service 1', '', '1.00', '20.00', '20.00');
INSERT INTO `as_plugin_invoice_items` VALUES('28', '4', '', '61e015f149d8670a7a182f22c21e744b', 'Booking payment', '', '1.00', '', '');
INSERT INTO `as_plugin_invoice_items` VALUES('29', '4', '24', '', 'Service 1', '', '1.00', '10.00', '10.00');
INSERT INTO `as_plugin_invoice_items` VALUES('30', '4', '25', '', 'Service 5', '', '1.00', '50.00', '50.00');

DROP TABLE IF EXISTS `as_plugin_locale`;

CREATE TABLE `as_plugin_locale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `language_iso` varchar(2) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT NULL,
  `is_default` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_iso` (`language_iso`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_plugin_locale_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_locale_languages`;

CREATE TABLE `as_plugin_locale_languages` (
  `iso` varchar(2) NOT NULL DEFAULT '',
  `owner_id` int(8) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`iso`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_plugin_locale_languages_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `as_plugin_locale_languages` VALUES('aa', '4', 'Afar', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ab', '4', 'Abkhazian', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ae', '4', 'Avestan', 'ae.png');
INSERT INTO `as_plugin_locale_languages` VALUES('af', '4', 'Afrikaans', 'af.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ak', '4', 'Akan', '');
INSERT INTO `as_plugin_locale_languages` VALUES('am', '4', 'Amharic', 'am.png');
INSERT INTO `as_plugin_locale_languages` VALUES('an', '4', 'Aragonese', 'an.png');
INSERT INTO `as_plugin_locale_languages` VALUES('as', '4', 'Assamese', 'as.png');
INSERT INTO `as_plugin_locale_languages` VALUES('av', '4', 'Avaric', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ay', '4', 'Aymara', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ba', '4', 'Bashkir', 'ba.png');
INSERT INTO `as_plugin_locale_languages` VALUES('be', '4', 'Belarusian', 'be.png');
INSERT INTO `as_plugin_locale_languages` VALUES('bg', '4', 'Bulgarian', 'bg.png');
INSERT INTO `as_plugin_locale_languages` VALUES('bh', '4', 'Bihari', 'bh.png');
INSERT INTO `as_plugin_locale_languages` VALUES('bi', '4', 'Bislama', 'bi.png');
INSERT INTO `as_plugin_locale_languages` VALUES('bm', '4', 'Bambara', 'bm.png');
INSERT INTO `as_plugin_locale_languages` VALUES('bn', '4', 'Bengali', 'bn.png');
INSERT INTO `as_plugin_locale_languages` VALUES('bo', '4', 'Tibetan', 'bo.png');
INSERT INTO `as_plugin_locale_languages` VALUES('br', '4', 'Breton', 'br.png');
INSERT INTO `as_plugin_locale_languages` VALUES('bs', '4', 'Bosnian', 'bs.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ca', '4', 'Catalan', 'ca.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ce', '4', 'Chechen', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ch', '4', 'Chamorro', 'ch.png');
INSERT INTO `as_plugin_locale_languages` VALUES('co', '4', 'Corsican', 'co.png');
INSERT INTO `as_plugin_locale_languages` VALUES('cr', '4', 'Cree', 'cr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('cs', '4', 'Czech', 'cz.png');
INSERT INTO `as_plugin_locale_languages` VALUES('cu', '4', 'Church Slavic', 'cu.png');
INSERT INTO `as_plugin_locale_languages` VALUES('cv', '4', 'Chuvash', 'cv.png');
INSERT INTO `as_plugin_locale_languages` VALUES('cy', '4', 'Welsh', 'cy.png');
INSERT INTO `as_plugin_locale_languages` VALUES('da', '4', 'Danish', 'dk.png');
INSERT INTO `as_plugin_locale_languages` VALUES('de', '4', 'German', 'de.png');
INSERT INTO `as_plugin_locale_languages` VALUES('dz', '4', 'Dzongkha', 'dz.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ee', '4', 'Ewe', 'ee.png');
INSERT INTO `as_plugin_locale_languages` VALUES('el', '4', 'Greek', 'gr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('eo', '4', 'Esperanto', '');
INSERT INTO `as_plugin_locale_languages` VALUES('es', '4', 'Spanish', 'es.png');
INSERT INTO `as_plugin_locale_languages` VALUES('et', '4', 'Estonian', 'et.png');
INSERT INTO `as_plugin_locale_languages` VALUES('eu', '4', 'Basque', 'eu.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ff', '4', 'Fulah', '');
INSERT INTO `as_plugin_locale_languages` VALUES('fi', '4', 'Finnish', 'fi.png');
INSERT INTO `as_plugin_locale_languages` VALUES('fj', '4', 'Fijian', 'fj.png');
INSERT INTO `as_plugin_locale_languages` VALUES('fo', '4', 'Faroese', 'fo.png');
INSERT INTO `as_plugin_locale_languages` VALUES('fr', '4', 'French', 'fr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('fy', '4', 'Western Frisian', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ga', '4', 'Irish', 'ga.png');
INSERT INTO `as_plugin_locale_languages` VALUES('gb', '4', 'English - UK', 'gb.png');
INSERT INTO `as_plugin_locale_languages` VALUES('gd', '4', 'Scottish Gaelic', 'gd.png');
INSERT INTO `as_plugin_locale_languages` VALUES('gl', '4', 'Galician', 'gl.png');
INSERT INTO `as_plugin_locale_languages` VALUES('gn', '4', 'Guarani', 'gn.png');
INSERT INTO `as_plugin_locale_languages` VALUES('gu', '4', 'Gujarati', 'gu.png');
INSERT INTO `as_plugin_locale_languages` VALUES('gv', '4', 'Manx', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ha', '4', 'Hausa', '');
INSERT INTO `as_plugin_locale_languages` VALUES('hi', '4', 'Hindi', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ho', '4', 'Hiri Motu', '');
INSERT INTO `as_plugin_locale_languages` VALUES('hr', '4', 'Croatian', 'hr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ht', '4', 'Haitian', 'ht.png');
INSERT INTO `as_plugin_locale_languages` VALUES('hu', '4', 'Hungarian', 'hu.png');
INSERT INTO `as_plugin_locale_languages` VALUES('hy', '4', 'Armenian', '');
INSERT INTO `as_plugin_locale_languages` VALUES('hz', '4', 'Herero', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ia', '4', 'Interlingua (International Auxiliary Language Association)', '');
INSERT INTO `as_plugin_locale_languages` VALUES('id', '4', 'Indonesian', 'id.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ie', '4', 'Interlingue', 'ie.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ig', '4', 'Igbo', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ii', '4', 'Sichuan Yi', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ik', '4', 'Inupiaq', '');
INSERT INTO `as_plugin_locale_languages` VALUES('io', '4', 'Ido', 'io.png');
INSERT INTO `as_plugin_locale_languages` VALUES('is', '4', 'Icelandic', 'is.png');
INSERT INTO `as_plugin_locale_languages` VALUES('it', '4', 'Italian', 'it.png');
INSERT INTO `as_plugin_locale_languages` VALUES('iu', '4', 'Inuktitut', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ka', '4', 'Georgian', '');
INSERT INTO `as_plugin_locale_languages` VALUES('kg', '4', 'Kongo', 'kg.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ki', '4', 'Kikuyu', 'ki.png');
INSERT INTO `as_plugin_locale_languages` VALUES('kj', '4', 'Kwanyama', '');
INSERT INTO `as_plugin_locale_languages` VALUES('kl', '4', 'Kalaallisut', '');
INSERT INTO `as_plugin_locale_languages` VALUES('km', '4', 'Khmer', 'km.png');
INSERT INTO `as_plugin_locale_languages` VALUES('kn', '4', 'Kannada', 'kn.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ko', '4', 'Korean', '');
INSERT INTO `as_plugin_locale_languages` VALUES('kr', '4', 'Kanuri', 'kr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('kv', '4', 'Komi', '');
INSERT INTO `as_plugin_locale_languages` VALUES('kw', '4', 'Cornish', 'kw.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ky', '4', 'Kirghiz', 'ky.png');
INSERT INTO `as_plugin_locale_languages` VALUES('la', '4', 'Latin', 'la.png');
INSERT INTO `as_plugin_locale_languages` VALUES('lb', '4', 'Luxembourgish', 'lb.png');
INSERT INTO `as_plugin_locale_languages` VALUES('lg', '4', 'Ganda', '');
INSERT INTO `as_plugin_locale_languages` VALUES('li', '4', 'Limburgish', 'li.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ln', '4', 'Lingala', '');
INSERT INTO `as_plugin_locale_languages` VALUES('lo', '4', 'Lao', '');
INSERT INTO `as_plugin_locale_languages` VALUES('lt', '4', 'Lithuanian', 'lt.png');
INSERT INTO `as_plugin_locale_languages` VALUES('lu', '4', 'Luba-Katanga', 'lu.png');
INSERT INTO `as_plugin_locale_languages` VALUES('lv', '4', 'Latvian', 'lv.png');
INSERT INTO `as_plugin_locale_languages` VALUES('mg', '4', 'Malagasy', 'mg.png');
INSERT INTO `as_plugin_locale_languages` VALUES('mh', '4', 'Marshallese', 'mh.png');
INSERT INTO `as_plugin_locale_languages` VALUES('mi', '4', 'Maori', '');
INSERT INTO `as_plugin_locale_languages` VALUES('mk', '4', 'Macedonian', 'mk.png');
INSERT INTO `as_plugin_locale_languages` VALUES('mn', '4', 'Mongolian', 'mn.png');
INSERT INTO `as_plugin_locale_languages` VALUES('mr', '4', 'Marathi', 'mr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('mt', '4', 'Maltese', 'mt.png');
INSERT INTO `as_plugin_locale_languages` VALUES('my', '4', 'Burmese', 'my.png');
INSERT INTO `as_plugin_locale_languages` VALUES('na', '4', 'Nauru', 'na.png');
INSERT INTO `as_plugin_locale_languages` VALUES('nb', '4', 'Norwegian Bokmal', '');
INSERT INTO `as_plugin_locale_languages` VALUES('nd', '4', 'North Ndebele', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ne', '4', 'Nepali', 'ne.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ng', '4', 'Ndonga', 'ng.png');
INSERT INTO `as_plugin_locale_languages` VALUES('nl', '4', 'Dutch', 'nl.png');
INSERT INTO `as_plugin_locale_languages` VALUES('nn', '4', 'Norwegian Nynorsk', '');
INSERT INTO `as_plugin_locale_languages` VALUES('no', '4', 'Norwegian', 'no.png');
INSERT INTO `as_plugin_locale_languages` VALUES('nr', '4', 'South Ndebele', 'nr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('nv', '4', 'Navajo', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ny', '4', 'Chichewa', '');
INSERT INTO `as_plugin_locale_languages` VALUES('oc', '4', 'Occitan', '');
INSERT INTO `as_plugin_locale_languages` VALUES('oj', '4', 'Ojibwa', '');
INSERT INTO `as_plugin_locale_languages` VALUES('om', '4', 'Oromo', 'om.png');
INSERT INTO `as_plugin_locale_languages` VALUES('or', '4', 'Oriya', '');
INSERT INTO `as_plugin_locale_languages` VALUES('os', '4', 'Ossetian', '');
INSERT INTO `as_plugin_locale_languages` VALUES('pi', '4', 'Pali', '');
INSERT INTO `as_plugin_locale_languages` VALUES('pl', '4', 'Polish', 'pl.png');
INSERT INTO `as_plugin_locale_languages` VALUES('pt', '4', 'Portuguese', 'pt.png');
INSERT INTO `as_plugin_locale_languages` VALUES('qu', '4', 'Quechua', '');
INSERT INTO `as_plugin_locale_languages` VALUES('rm', '4', 'Raeto-Romance', '');
INSERT INTO `as_plugin_locale_languages` VALUES('rn', '4', 'Kirundi', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ro', '4', 'Romanian', 'ro.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ru', '4', 'Russian', 'ru.png');
INSERT INTO `as_plugin_locale_languages` VALUES('rw', '4', 'Kinyarwanda', 'rw.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sa', '4', 'Sanskrit', 'sa.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sc', '4', 'Sardinian', 'sc.png');
INSERT INTO `as_plugin_locale_languages` VALUES('se', '4', 'Northern Sami', 'se.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sg', '4', 'Sango', 'sg.png');
INSERT INTO `as_plugin_locale_languages` VALUES('si', '4', 'Sinhala', 'si.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sk', '4', 'Slovak', 'sk.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sl', '4', 'Slovenian', 'sl.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sm', '4', 'Samoan', 'sm.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sn', '4', 'Shona', 'sn.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sq', '4', 'Albanian', 'al.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sr', '4', 'Serbian', 'sr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ss', '4', 'Swati', '');
INSERT INTO `as_plugin_locale_languages` VALUES('st', '4', 'Southern Sotho', 'st.png');
INSERT INTO `as_plugin_locale_languages` VALUES('su', '4', 'Sundanese', '');
INSERT INTO `as_plugin_locale_languages` VALUES('sv', '4', 'Swedish', 'se.png');
INSERT INTO `as_plugin_locale_languages` VALUES('sw', '4', 'Swahili', '');
INSERT INTO `as_plugin_locale_languages` VALUES('ta', '4', 'Tamil', '');
INSERT INTO `as_plugin_locale_languages` VALUES('te', '4', 'Telugu', '');
INSERT INTO `as_plugin_locale_languages` VALUES('tg', '4', 'Tajik', 'tg.png');
INSERT INTO `as_plugin_locale_languages` VALUES('th', '4', 'Thai', 'th.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ti', '4', 'Tigrinya', '');
INSERT INTO `as_plugin_locale_languages` VALUES('tl', '4', 'Tagalog', 'tl.png');
INSERT INTO `as_plugin_locale_languages` VALUES('tn', '4', 'Tswana', 'tn.png');
INSERT INTO `as_plugin_locale_languages` VALUES('to', '4', 'Tonga', 'to.png');
INSERT INTO `as_plugin_locale_languages` VALUES('tr', '4', 'Turkish', 'tr.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ts', '4', 'Tsonga', '');
INSERT INTO `as_plugin_locale_languages` VALUES('tt', '4', 'Tatar', 'tt.png');
INSERT INTO `as_plugin_locale_languages` VALUES('tw', '4', 'Twi', 'tw.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ty', '4', 'Tahitian', '');
INSERT INTO `as_plugin_locale_languages` VALUES('uk', '4', 'Ukrainian', '');
INSERT INTO `as_plugin_locale_languages` VALUES('us', '4', 'English - USA', 'us.png');
INSERT INTO `as_plugin_locale_languages` VALUES('uz', '4', 'Uzbek', 'uz.png');
INSERT INTO `as_plugin_locale_languages` VALUES('ve', '4', 'Venda', 've.png');
INSERT INTO `as_plugin_locale_languages` VALUES('vi', '4', 'Vietnamese', 'vi.png');
INSERT INTO `as_plugin_locale_languages` VALUES('vo', '4', 'Volapuk', '');
INSERT INTO `as_plugin_locale_languages` VALUES('wa', '4', 'Walloon', 'wa.png');
INSERT INTO `as_plugin_locale_languages` VALUES('wo', '4', 'Wolof', '');
INSERT INTO `as_plugin_locale_languages` VALUES('xh', '4', 'Xhosa', '');
INSERT INTO `as_plugin_locale_languages` VALUES('yo', '4', 'Yoruba', '');
INSERT INTO `as_plugin_locale_languages` VALUES('za', '4', 'Zhuang', 'za.png');
INSERT INTO `as_plugin_locale_languages` VALUES('zu', '4', 'Zulu', '');

DROP TABLE IF EXISTS `as_plugin_log`;

CREATE TABLE `as_plugin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `function` varchar(255) DEFAULT NULL,
  `value` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_plugin_log_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_log_config`;

CREATE TABLE `as_plugin_log_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_plugin_log_config_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_one_admin`;

CREATE TABLE `as_plugin_one_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` blob,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_plugin_one_admin_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_plugin_sms`;

CREATE TABLE `as_plugin_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_plugin_sms_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_resources`;

CREATE TABLE `as_resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_resources_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `as_resources` VALUES('4', '4', 'Tim ra roi', 'ai ma biet');
INSERT INTO `as_resources` VALUES('5', '4', 'hello world', 'Chao the gioi');
INSERT INTO `as_resources` VALUES('7', '4', '1231', '3123123');
INSERT INTO `as_resources` VALUES('8', '4', 'wewe', 'wqeqw');

DROP TABLE IF EXISTS `as_resources_services`;

CREATE TABLE `as_resources_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `resources_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`resources_id`,`service_id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_resources_services_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_roles`;

CREATE TABLE `as_roles` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  KEY `status` (`status`),
  CONSTRAINT `as_roles_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_services`;

CREATE TABLE `as_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `calendar_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  `before` smallint(5) unsigned DEFAULT NULL,
  `after` smallint(5) unsigned DEFAULT NULL,
  `total` smallint(5) unsigned DEFAULT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  KEY `calendar_id` (`calendar_id`),
  CONSTRAINT `as_services_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `as_services` VALUES('1', '4', '1', '1', '10.00', '10', '0', '0', '10', '1');
INSERT INTO `as_services` VALUES('5', '4', '1', '3', '20.00', '20', '0', '0', '20', '1');
INSERT INTO `as_services` VALUES('6', '4', '1', '2', '30.00', '20', '5', '5', '30', '1');
INSERT INTO `as_services` VALUES('7', '4', '1', '1', '40.00', '20', '10', '10', '40', '1');
INSERT INTO `as_services` VALUES('8', '4', '1', '1', '50.00', '50', '0', '0', '50', '1');
INSERT INTO `as_services` VALUES('9', '4', '1', '1', '19.00', '1', '1', '1', '3', '1');
INSERT INTO `as_services` VALUES('10', '4', '1', '3', '21.00', '0', '0', '0', '0', '1');

DROP TABLE IF EXISTS `as_services_category`;

CREATE TABLE `as_services_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `show_front` varchar(250) DEFAULT NULL,
  `message` text,
  `order` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_services_category_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

INSERT INTO `as_services_category` VALUES('1', '4', 'Categroy 1', 'on', 'Categroy description', '');
INSERT INTO `as_services_category` VALUES('2', '4', 'Categroy 2', 'on', 'Categroy description', '');
INSERT INTO `as_services_category` VALUES('3', '4', 'Categroy 3', 'on', 'Categroy description', '');
INSERT INTO `as_services_category` VALUES('4', '4', 'hello world', 'on', 'sadsdasd', '');
INSERT INTO `as_services_category` VALUES('5', '4', 'rosso', 'on', 'dasdasdd', '');
INSERT INTO `as_services_category` VALUES('6', '4', 'catalan', 'off', 'd1312312', '');
INSERT INTO `as_services_category` VALUES('9', '4', 'test132dasdashghgh', 'on', 'sadasdasd', '');
INSERT INTO `as_services_category` VALUES('10', '4', 'frwewe', 'on', 'a123123', '');

DROP TABLE IF EXISTS `as_services_extra_service`;

CREATE TABLE `as_services_extra_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `extra_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`extra_id`,`service_id`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_services_extra_service_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_services_time`;

CREATE TABLE `as_services_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `price` decimal(9,2) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  `before` smallint(5) unsigned DEFAULT NULL,
  `after` smallint(5) unsigned DEFAULT NULL,
  `total` smallint(5) unsigned DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `nuser_id` (`owner_id`),
  KEY `calendar_id` (`foreign_id`),
  CONSTRAINT `as_services_time_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `as_users`;

CREATE TABLE `as_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
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
  KEY `nuser_id` (`owner_id`),
  KEY `role_id` (`role_id`),
  KEY `status` (`status`),
  CONSTRAINT `as_users_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

INSERT INTO `as_users` VALUES('1', '4', '1', 'hungnq9@gmail.com', '', 'hungnq', '0147559373', '2014-07-27 11:14:06', '2014-07-27 20:59:21', 'T', 'F', '');

DROP TABLE IF EXISTS `as_working_times`;

CREATE TABLE `as_working_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(8) DEFAULT NULL,
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
  UNIQUE KEY `foreign_id` (`foreign_id`,`type`),
  KEY `nuser_id` (`owner_id`),
  CONSTRAINT `as_working_times_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `tbl_user_mast` (`nuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

INSERT INTO `as_working_times` VALUES('1', '4', '1', 'calendar', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '16:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '19:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('2', '4', '1', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('3', '4', '2', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('4', '4', '3', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('5', '4', '4', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('10', '4', '9', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('20', '4', '14', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '16:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '19:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('21', '4', '15', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '16:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '19:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('22', '4', '16', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '16:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '19:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');
INSERT INTO `as_working_times` VALUES('23', '4', '18', 'employee', '09:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '13:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '16:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '19:00:00', '20:00:00', '09:00:00', '18:00:00', '12:30:00', '13:30:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F', '08:00:00', '20:00:00', '10:00:00', '17:00:00', '12:00:00', '13:00:00', 'F');

