# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.12)
# Database: varaa_dev_dump
# Generation Time: 2014-10-10 23:50:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table varaa_as_booking_extra_services
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_booking_extra_services`;

CREATE TABLE `varaa_as_booking_extra_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) unsigned DEFAULT NULL,
  `extra_service_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tmp_uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_booking_extra_services_booking_id_foreign` (`booking_id`),
  KEY `as_booking_extra_services_extra_service_id_foreign` (`extra_service_id`),
  CONSTRAINT `as_booking_extra_services_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `varaa_as_bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_booking_extra_services_extra_service_id_foreign` FOREIGN KEY (`extra_service_id`) REFERENCES `varaa_as_extra_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_as_booking_payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_booking_payments`;

CREATE TABLE `varaa_as_booking_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) unsigned NOT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `metadata` text COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `deposit` double NOT NULL,
  `tax` double NOT NULL,
  `cc_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_expired_month` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_expired_year` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cc_expired_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_booking_payments_booking_id_foreign` (`booking_id`),
  CONSTRAINT `as_booking_payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `varaa_as_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_as_booking_services
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_booking_services`;

CREATE TABLE `varaa_as_booking_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tmp_uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `booking_id` int(10) unsigned DEFAULT NULL,
  `service_id` int(10) unsigned DEFAULT NULL,
  `service_time_id` int(10) unsigned DEFAULT NULL,
  `employee_id` int(10) unsigned DEFAULT NULL,
  `modify_time` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  `start_at` time NOT NULL,
  `end_at` time NOT NULL,
  `is_reminder_email` tinyint(1) NOT NULL,
  `is_reminder_sms` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_booking_services_user_id_foreign` (`user_id`),
  KEY `as_booking_services_service_id_foreign` (`service_id`),
  KEY `as_booking_services_service_time_id_foreign` (`service_time_id`),
  KEY `as_booking_services_employee_id_foreign` (`employee_id`),
  KEY `as_booking_services_booking_id_foreign` (`booking_id`),
  CONSTRAINT `as_booking_services_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `varaa_as_bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_booking_services_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `varaa_as_employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_booking_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `varaa_as_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_booking_services_service_time_id_foreign` FOREIGN KEY (`service_time_id`) REFERENCES `varaa_as_service_times` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_booking_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_booking_services` WRITE;
/*!40000 ALTER TABLE `varaa_as_booking_services` DISABLE KEYS */;

INSERT INTO `varaa_as_booking_services` (`id`, `tmp_uuid`, `user_id`, `booking_id`, `service_id`, `service_time_id`, `employee_id`, `modify_time`, `date`, `start_at`, `end_at`, `is_reminder_email`, `is_reminder_sms`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(13789,'MR1411541983',70,24543,338,NULL,64,30,'2014-09-25','12:00:00','13:30:00',0,0,'2014-09-24 10:00:13','2014-09-24 10:00:16',NULL),
	(14204,'EO1411644940',70,24890,329,NULL,64,0,'2014-10-14','12:00:00','13:30:00',0,0,'2014-09-25 14:36:09','2014-09-25 14:36:14',NULL),
	(15216,'UV1412064152',70,25778,328,NULL,64,0,'2014-10-01','14:30:00','15:30:00',0,0,'2014-09-30 11:02:52','2014-09-30 11:02:54',NULL),
	(15217,'BX1412064197',70,25779,338,NULL,70,0,'2014-10-01','15:30:00','16:30:00',0,0,'2014-09-30 11:03:38','2014-09-30 11:03:40',NULL),
	(15863,'JE1412237984',70,26312,328,NULL,64,0,'2014-12-10','10:00:00','11:00:00',0,0,'2014-10-02 11:20:57','2014-10-02 11:21:07',NULL),
	(16555,'XQ1412584446',70,26848,353,NULL,64,0,'2014-11-10','10:00:00','10:30:00',0,0,'2014-10-06 11:34:52','2014-10-06 11:35:01',NULL),
	(16557,'TV1412584511',70,26850,329,NULL,64,0,'2014-11-10','10:30:00','12:00:00',0,0,'2014-10-06 11:35:42','2014-10-06 11:35:48',NULL);

/*!40000 ALTER TABLE `varaa_as_booking_services` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_bookings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_bookings`;

CREATE TABLE `varaa_as_bookings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `consumer_id` int(10) unsigned DEFAULT NULL,
  `employee_id` int(10) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total` int(10) unsigned DEFAULT '0',
  `modify_time` int(10) DEFAULT NULL,
  `plustime` int(11) NOT NULL DEFAULT '0',
  `start_at` time NOT NULL,
  `end_at` time NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total_price` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `as_bookings_uuid_unique` (`uuid`),
  KEY `as_bookings_user_id_foreign` (`user_id`),
  KEY `as_bookings_consumer_id_foreign` (`consumer_id`),
  KEY `as_bookings_employee_id_foreign` (`employee_id`),
  CONSTRAINT `as_bookings_consumer_id_foreign` FOREIGN KEY (`consumer_id`) REFERENCES `varaa_consumers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_bookings_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `varaa_as_employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_bookings` WRITE;
/*!40000 ALTER TABLE `varaa_as_bookings` DISABLE KEYS */;

INSERT INTO `varaa_as_bookings` (`id`, `uuid`, `user_id`, `consumer_id`, `employee_id`, `date`, `total`, `modify_time`, `plustime`, `start_at`, `end_at`, `status`, `ip`, `notes`, `created_at`, `updated_at`, `total_price`, `deleted_at`, `source`)
VALUES
	(24543,'MR1411541983',70,2433,64,'2014-09-25',90,30,0,'12:00:00','13:30:00',5,'87.108.39.105','laser  13','2014-09-24 10:00:16','2014-09-25 14:07:25',49,NULL,NULL),
	(24890,'EO1411644940',70,2075,64,'2014-10-14',90,0,0,'12:00:00','13:30:00',1,'87.108.39.105','pidempi tauko. 3 vk viestistä. Ollut matkalla. Laita muistutus ma viestillä.','2014-09-25 14:36:14','2014-09-25 14:37:25',59,NULL,NULL),
	(25778,'UV1412064152',70,2433,64,'2014-10-01',60,0,0,'14:30:00','15:30:00',1,'87.108.39.105','4 ylläpito KH. Menee laseriin Marialle','2014-09-30 11:02:53','2014-10-01 14:39:15',49,'2014-10-01 14:39:15',NULL),
	(25779,'BX1412064197',70,2433,70,'2014-10-01',60,0,0,'15:30:00','16:30:00',1,'87.108.39.105','','2014-09-30 11:03:40','2014-10-01 09:11:40',49,'2014-10-01 09:11:40',NULL),
	(26312,'JE1412237984',70,2453,64,'2014-12-10',60,0,0,'10:00:00','11:00:00',1,'87.108.39.105','10 vk tauko. Ollut Espanjassa lomalla. ','2014-10-02 11:21:07','2014-10-02 11:21:07',49,NULL,NULL),
	(26848,'XQ1412584446',70,2253,64,'2014-11-10',30,0,0,'10:00:00','10:30:00',1,'87.108.39.105','4 ekh +tsek 5 vk taukoa','2014-10-06 11:35:01','2014-10-06 11:35:01',35.5,NULL,NULL),
	(26850,'TV1412584511',70,2253,64,'2014-11-10',90,0,0,'10:30:00','12:00:00',1,'87.108.39.105','EKH 4','2014-10-06 11:35:48','2014-10-06 11:35:48',59,NULL,NULL);

/*!40000 ALTER TABLE `varaa_as_bookings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_consumers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_consumers`;

CREATE TABLE `varaa_as_consumers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `consumer_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_consumers_user_id_foreign` (`user_id`),
  KEY `as_consumers_consumer_id_foreign` (`consumer_id`),
  CONSTRAINT `as_consumers_consumer_id_foreign` FOREIGN KEY (`consumer_id`) REFERENCES `varaa_consumers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_consumers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_consumers` WRITE;
/*!40000 ALTER TABLE `varaa_as_consumers` DISABLE KEYS */;

INSERT INTO `varaa_as_consumers` (`id`, `consumer_id`, `user_id`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(2075,2075,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2253,2253,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2396,2396,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2402,2402,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2433,2433,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2453,2453,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2454,2454,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2461,2461,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2466,2466,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2468,2468,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2480,2480,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2499,2499,70,'2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2533,2433,70,'2014-07-09 05:45:02','2014-07-09 05:45:02',NULL),
	(2534,2396,70,'2014-07-09 05:56:45','2014-07-09 05:56:45',NULL),
	(2556,2433,70,'2014-07-09 09:33:14','2014-07-09 09:33:14',NULL),
	(2896,2433,70,'2014-07-15 10:07:32','2014-07-15 10:07:32',NULL),
	(2898,2433,70,'2014-07-16 01:58:23','2014-07-16 01:58:23',NULL),
	(3035,2433,70,'2014-07-23 05:13:58','2014-07-23 05:13:58',NULL),
	(3110,2402,70,'2014-07-28 06:53:35','2014-07-28 06:53:35',NULL),
	(14664,2433,70,'2014-08-07 10:55:38','2014-08-07 10:55:38',NULL),
	(15919,2433,70,'2014-08-14 08:04:06','2014-08-14 08:04:06',NULL),
	(19913,2433,70,'2014-09-02 03:52:23','2014-09-02 03:52:23',NULL),
	(20485,2433,70,'2014-09-04 06:28:58','2014-09-04 06:28:58',NULL),
	(21466,2433,70,'2014-09-09 03:46:43','2014-09-09 03:46:43',NULL),
	(22948,2433,70,'2014-09-16 05:21:31','2014-09-16 05:21:31',NULL);

/*!40000 ALTER TABLE `varaa_as_consumers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_custom_times
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_custom_times`;

CREATE TABLE `varaa_as_custom_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_at` time NOT NULL,
  `end_at` time NOT NULL,
  `is_day_off` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_custom_times_user_id_foreign` (`user_id`),
  CONSTRAINT `as_custom_times_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_custom_times` WRITE;
/*!40000 ALTER TABLE `varaa_as_custom_times` DISABLE KEYS */;

INSERT INTO `varaa_as_custom_times` (`id`, `user_id`, `name`, `start_at`, `end_at`, `is_day_off`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(66,70,'Suljettu','00:00:00','00:00:00',1,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(590,70,'9.30-19.30','09:30:00','19:30:00',0,'2014-09-18 15:21:25','2014-09-18 15:21:25',NULL),
	(594,70,'8-19.45','08:00:00','19:45:00',0,'2014-09-19 11:16:51','2014-09-19 11:16:51',NULL),
	(595,70,'9.30-19.15','09:30:00','19:15:00',0,'2014-09-19 11:18:02','2014-09-19 11:18:02',NULL),
	(596,70,'10.30-13.00','10:30:00','13:00:00',0,'2014-09-19 11:26:31','2014-09-19 11:26:31',NULL),
	(601,70,'8-20.30','08:00:00','20:30:00',0,'2014-09-19 19:58:40','2014-09-19 19:58:40',NULL),
	(602,70,'9.30-11.00','09:30:00','11:00:00',0,'2014-09-19 20:16:11','2014-09-19 20:16:11',NULL),
	(603,70,'8.00-19.30','08:00:00','19:30:00',0,'2014-09-19 20:23:51','2014-09-19 20:23:51',NULL),
	(604,70,'10.30-14.00','10:30:00','14:00:00',0,'2014-09-20 12:55:46','2014-09-20 12:55:46',NULL),
	(608,70,'9-10','09:00:00','10:00:00',0,'2014-09-23 11:22:10','2014-09-23 11:22:10',NULL),
	(613,70,'9-19.15','09:00:00','19:15:00',0,'2014-09-25 12:59:40','2014-09-25 12:59:40',NULL),
	(614,70,'8-12.30','08:00:00','12:30:00',0,'2014-09-25 14:11:58','2014-09-25 14:11:58',NULL),
	(615,70,'15.00-18.00','15:00:00','18:00:00',0,'2014-09-25 20:09:49','2014-09-25 20:09:49',NULL),
	(621,70,'9-18.45','09:00:00','18:45:00',0,'2014-09-29 16:43:58','2014-09-29 16:43:58',NULL);

/*!40000 ALTER TABLE `varaa_as_custom_times` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_employee_custom_time
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_employee_custom_time`;

CREATE TABLE `varaa_as_employee_custom_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `custom_time_id` int(10) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_employee_custom_time_employee_id_foreign` (`employee_id`),
  KEY `as_employee_custom_time_custom_time_id_foreign` (`custom_time_id`),
  CONSTRAINT `as_employee_custom_time_custom_time_id_foreign` FOREIGN KEY (`custom_time_id`) REFERENCES `varaa_as_custom_times` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_employee_custom_time_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `varaa_as_employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_employee_custom_time` WRITE;
/*!40000 ALTER TABLE `varaa_as_employee_custom_time` DISABLE KEYS */;

INSERT INTO `varaa_as_employee_custom_time` (`id`, `employee_id`, `custom_time_id`, `date`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(12258,71,66,'2015-01-18','2014-10-02 15:16:39','2014-10-02 15:16:39',NULL),
	(12263,71,66,'2015-01-23','2014-10-02 15:16:39','2014-10-02 15:16:39',NULL),
	(12265,71,66,'2015-01-25','2014-10-02 15:16:39','2014-10-02 15:16:39',NULL),
	(12271,71,66,'2015-01-31','2014-10-02 15:16:39','2014-10-02 15:16:39',NULL),
	(12273,71,66,'2015-05-01','2014-10-02 15:55:28','2014-10-02 15:55:28',NULL),
	(12274,71,66,'2015-05-14','2014-10-02 15:56:29','2014-10-02 15:56:29',NULL),
	(12275,71,66,'2015-05-24','2014-10-02 15:56:29','2014-10-02 15:56:29',NULL),
	(12530,71,66,'2014-11-20','2014-10-06 16:10:54','2014-10-06 16:10:54',NULL),
	(12532,71,66,'2014-11-23','2014-10-06 16:10:54','2014-10-06 16:10:54',NULL),
	(12538,71,66,'2014-11-30','2014-10-06 16:12:52','2014-10-06 16:12:52',NULL);

/*!40000 ALTER TABLE `varaa_as_employee_custom_time` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_employee_default_time
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_employee_default_time`;

CREATE TABLE `varaa_as_employee_default_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `type` enum('mon','tue','wed','thu','fri','sat','sun') COLLATE utf8_unicode_ci NOT NULL,
  `start_at` time NOT NULL,
  `end_at` time NOT NULL,
  `is_day_off` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_employee_default_time_employee_id_foreign` (`employee_id`),
  CONSTRAINT `as_employee_default_time_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `varaa_as_employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_employee_default_time` WRITE;
/*!40000 ALTER TABLE `varaa_as_employee_default_time` DISABLE KEYS */;

INSERT INTO `varaa_as_employee_default_time` (`id`, `employee_id`, `type`, `start_at`, `end_at`, `is_day_off`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(523,75,'fri','00:00:00','00:00:00',0,'2014-09-16 22:33:57','2014-09-17 10:33:33',NULL),
	(524,75,'sat','00:00:00','00:00:00',0,'2014-09-16 22:33:57','2014-09-17 10:33:33',NULL),
	(525,75,'sun','00:00:00','00:00:00',0,'2014-09-16 22:33:57','2014-09-17 10:33:33',NULL),
	(1863,267,'mon','09:30:00','17:30:00',0,'2014-09-16 22:33:57','2014-09-16 22:33:57',NULL),
	(1864,267,'tue','09:30:00','17:30:00',0,'2014-09-16 22:33:57','2014-09-16 22:33:57',NULL),
	(1865,267,'wed','09:30:00','17:30:00',0,'2014-09-16 22:33:57','2014-09-16 22:33:57',NULL),
	(1866,267,'thu','09:30:00','17:30:00',0,'2014-09-16 22:33:57','2014-09-16 22:33:57',NULL),
	(1867,267,'fri','09:30:00','17:30:00',0,'2014-09-16 22:33:57','2014-09-16 22:33:57',NULL),
	(1868,267,'sat','00:00:00','00:00:00',1,'2014-09-16 22:33:57','2014-09-16 22:33:57',NULL),
	(1869,267,'sun','00:00:00','00:00:00',1,'2014-09-16 22:33:57','2014-09-16 22:33:57',NULL);

/*!40000 ALTER TABLE `varaa_as_employee_default_time` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_employee_freetime
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_employee_freetime`;

CREATE TABLE `varaa_as_employee_freetime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `start_at` time NOT NULL,
  `end_at` time NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_employee_freetime_user_id_foreign` (`user_id`),
  KEY `as_employee_freetime_employee_id_foreign` (`employee_id`),
  CONSTRAINT `as_employee_freetime_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `varaa_as_employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_employee_freetime_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_employee_freetime` WRITE;
/*!40000 ALTER TABLE `varaa_as_employee_freetime` DISABLE KEYS */;

INSERT INTO `varaa_as_employee_freetime` (`id`, `user_id`, `employee_id`, `date`, `start_at`, `end_at`, `description`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(7319,70,64,'2014-10-06','12:15:00','12:30:00','RT','2014-10-06 12:52:15','2014-10-06 12:52:15',NULL),
	(7320,70,64,'2014-10-08','09:00:00','09:30:00','MENO','2014-10-06 12:53:17','2014-10-06 12:53:17',NULL),
	(7326,70,63,'2014-10-13','14:45:00','15:15:00','RT','2014-10-06 13:26:44','2014-10-06 13:26:44',NULL),
	(7344,70,71,'2014-10-06','14:30:00','15:00:00','ruokkis','2014-10-06 14:16:09','2014-10-06 14:16:09',NULL),
	(7357,70,71,'2014-10-06','17:15:00','18:00:00','','2014-10-06 15:07:18','2014-10-06 15:07:18',NULL),
	(7371,70,64,'2014-10-13','12:30:00','13:00:00','RT','2014-10-06 15:42:31','2014-10-06 15:42:31',NULL),
	(7372,70,70,'2014-10-06','16:00:00','17:00:00','','2014-10-06 15:47:07','2014-10-06 15:47:07',NULL),
	(7377,70,69,'2014-10-06','18:00:00','19:00:00','','2014-10-06 16:19:26','2014-10-06 16:19:26',NULL);

/*!40000 ALTER TABLE `varaa_as_employee_freetime` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_employee_service
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_employee_service`;

CREATE TABLE `varaa_as_employee_service` (
  `employee_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `plustime` tinyint(4) NOT NULL,
  PRIMARY KEY (`employee_id`,`service_id`),
  KEY `as_employee_service_service_id_foreign` (`service_id`),
  CONSTRAINT `as_employee_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `varaa_as_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_employee_service_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `varaa_as_employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_employee_service` WRITE;
/*!40000 ALTER TABLE `varaa_as_employee_service` DISABLE KEYS */;

INSERT INTO `varaa_as_employee_service` (`employee_id`, `service_id`, `plustime`)
VALUES
	(63,328,0),
	(63,329,0),
	(63,330,0),
	(63,331,15),
	(63,332,15),
	(63,333,0),
	(63,334,0),
	(63,335,0),
	(63,336,0),
	(63,337,0),
	(63,338,0),
	(63,339,0),
	(63,340,0),
	(63,341,0),
	(63,342,0),
	(63,343,0),
	(63,344,0),
	(63,345,0),
	(63,346,0),
	(63,348,0),
	(63,349,0),
	(63,350,0),
	(63,352,0),
	(63,353,0),
	(63,354,0),
	(63,360,0),
	(63,367,0),
	(64,328,0),
	(64,329,0),
	(64,330,0),
	(64,331,0),
	(64,332,0),
	(64,334,0),
	(64,335,0),
	(64,336,0),
	(64,337,0),
	(64,338,0),
	(64,341,0),
	(64,342,0),
	(64,343,0),
	(64,344,0),
	(64,345,0),
	(64,346,0),
	(64,347,0),
	(64,348,0),
	(64,349,0),
	(64,350,0),
	(64,352,0),
	(64,353,0),
	(64,360,0),
	(64,367,0),
	(64,1964,0),
	(65,313,0),
	(65,314,0),
	(65,315,0),
	(65,316,0),
	(65,317,0),
	(65,318,0),
	(65,319,0),
	(65,320,0),
	(65,321,0),
	(65,322,0),
	(65,323,0),
	(65,324,0),
	(65,325,0),
	(65,326,0),
	(65,327,0),
	(65,328,0),
	(65,329,0),
	(65,330,0),
	(65,331,0),
	(65,332,0),
	(65,333,0),
	(65,334,0),
	(65,335,0),
	(65,336,0),
	(65,337,0),
	(65,338,0),
	(65,339,0),
	(65,341,0),
	(65,342,0),
	(65,343,0),
	(65,344,0),
	(65,345,0),
	(65,346,0),
	(65,349,0),
	(65,350,0),
	(65,352,0),
	(65,353,0),
	(65,354,0),
	(65,356,0),
	(65,357,0),
	(65,358,0),
	(65,360,0),
	(65,363,0),
	(65,364,0),
	(65,365,0),
	(65,366,0),
	(65,367,0),
	(65,1580,0),
	(65,1964,0),
	(66,328,-30),
	(66,329,0),
	(66,330,0),
	(66,331,0),
	(66,332,0),
	(66,333,0),
	(66,334,0),
	(66,335,0),
	(66,336,0),
	(66,337,0),
	(66,340,0),
	(66,341,0),
	(66,342,0),
	(66,343,0),
	(66,344,0),
	(66,345,0),
	(66,346,0),
	(66,349,0),
	(66,350,0),
	(66,352,0),
	(66,353,0),
	(66,354,0),
	(66,359,0),
	(67,314,0),
	(67,315,0),
	(67,316,0),
	(67,317,0),
	(67,318,0),
	(67,319,0),
	(67,320,0),
	(67,328,0),
	(67,329,0),
	(67,331,0),
	(67,332,0),
	(67,333,0),
	(67,334,0),
	(67,335,0),
	(67,336,0),
	(67,337,0),
	(67,338,0),
	(67,339,0),
	(67,340,0),
	(67,341,0),
	(67,342,0),
	(67,343,0),
	(67,344,0),
	(67,345,0),
	(67,346,0),
	(67,347,0),
	(67,348,0),
	(67,349,0),
	(67,350,0),
	(67,352,0),
	(67,353,0),
	(67,354,0),
	(67,356,0),
	(67,357,0),
	(67,360,0),
	(67,367,0),
	(67,1476,0),
	(67,1927,0),
	(68,301,0),
	(68,302,0),
	(68,303,0),
	(68,304,0),
	(68,305,15),
	(68,306,0),
	(68,307,0),
	(68,308,0),
	(68,309,0),
	(68,310,0),
	(68,311,0),
	(68,312,0),
	(68,313,0),
	(68,349,0),
	(68,350,0),
	(68,351,0),
	(68,358,0),
	(68,361,0),
	(68,362,0),
	(68,363,0),
	(68,364,0),
	(68,365,0),
	(68,366,0),
	(68,367,0),
	(69,314,0),
	(69,315,0),
	(69,316,0),
	(69,317,0),
	(69,318,0),
	(69,319,0),
	(69,320,0),
	(69,321,0),
	(69,323,0),
	(69,324,0),
	(69,325,0),
	(69,326,0),
	(69,327,0),
	(69,328,0),
	(69,329,0),
	(69,330,0),
	(69,331,0),
	(69,332,0),
	(69,333,0),
	(69,334,0),
	(69,335,0),
	(69,336,0),
	(69,337,0),
	(69,338,0),
	(69,339,0),
	(69,340,0),
	(69,349,0),
	(69,350,0),
	(69,356,0),
	(69,357,0),
	(69,367,0),
	(70,314,0),
	(70,315,0),
	(70,316,0),
	(70,318,0),
	(70,319,0),
	(70,321,0),
	(70,322,0),
	(70,323,0),
	(70,324,0),
	(70,325,0),
	(70,326,0),
	(70,328,0),
	(70,329,0),
	(70,330,0),
	(70,331,0),
	(70,332,0),
	(70,333,0),
	(70,334,0),
	(70,335,0),
	(70,336,0),
	(70,337,0),
	(70,338,0),
	(70,340,0),
	(70,344,0),
	(70,345,0),
	(70,346,0),
	(70,347,0),
	(70,348,0),
	(70,349,0),
	(70,350,0),
	(70,356,0),
	(70,357,0),
	(70,358,0),
	(70,363,0),
	(70,364,0),
	(70,365,0),
	(70,366,0),
	(70,367,0),
	(71,314,15),
	(71,315,0),
	(71,316,0),
	(71,317,0),
	(71,318,0),
	(71,319,0),
	(71,320,0),
	(71,321,0),
	(71,322,0),
	(71,323,0),
	(71,324,0),
	(71,325,0),
	(71,326,0),
	(71,328,0),
	(71,329,0),
	(71,330,0),
	(71,331,0),
	(71,332,0),
	(71,333,0),
	(71,334,0),
	(71,335,0),
	(71,336,0),
	(71,337,0),
	(71,338,0),
	(71,339,0),
	(71,340,0),
	(71,344,0),
	(71,345,0),
	(71,346,0),
	(71,347,0),
	(71,349,0),
	(71,350,0),
	(71,356,0),
	(71,357,0),
	(71,358,0),
	(71,363,0),
	(71,364,0),
	(71,365,0),
	(71,366,0),
	(71,367,0),
	(72,314,0),
	(72,315,0),
	(72,316,0),
	(72,317,0),
	(72,319,0),
	(72,320,0),
	(72,321,0),
	(72,322,0),
	(72,323,0),
	(72,325,0),
	(72,326,0),
	(72,327,0),
	(72,328,0),
	(72,329,0),
	(72,331,0),
	(72,332,0),
	(72,333,0),
	(72,335,0),
	(72,336,0),
	(72,337,0),
	(72,338,0),
	(72,339,0),
	(72,340,0),
	(72,347,0),
	(72,349,0),
	(72,350,0),
	(72,356,0),
	(72,357,0),
	(72,367,0),
	(73,348,0),
	(73,349,0),
	(73,350,0),
	(73,355,0),
	(74,335,0),
	(74,336,0),
	(74,337,0),
	(74,338,0),
	(74,339,0),
	(74,341,0),
	(74,342,0),
	(74,343,0),
	(74,348,0),
	(74,349,0),
	(74,350,0),
	(74,352,0),
	(74,353,0),
	(74,354,0),
	(74,367,0),
	(75,314,0),
	(75,315,0),
	(75,316,0),
	(75,317,0),
	(75,318,0),
	(75,319,0),
	(75,320,0),
	(75,321,0),
	(75,322,0),
	(75,323,0),
	(75,324,0),
	(75,325,0),
	(75,326,0),
	(75,327,0),
	(75,329,0),
	(75,333,0),
	(75,334,0),
	(75,335,0),
	(75,336,0),
	(75,337,0),
	(75,338,0),
	(75,339,0),
	(75,340,0),
	(75,349,0),
	(75,350,0),
	(75,356,0),
	(75,357,0),
	(75,358,0),
	(75,363,0),
	(75,364,0),
	(75,365,0),
	(75,366,0),
	(267,349,0);

/*!40000 ALTER TABLE `varaa_as_employee_service` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_employees
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_employees`;

CREATE TABLE `varaa_as_employees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_subscribed_email` tinyint(1) NOT NULL,
  `is_subscribed_sms` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_employees_user_id_foreign` (`user_id`),
  CONSTRAINT `as_employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_employees` WRITE;
/*!40000 ALTER TABLE `varaa_as_employees` DISABLE KEYS */;

INSERT INTO `varaa_as_employees` (`id`, `user_id`, `name`, `email`, `phone`, `avatar`, `description`, `is_subscribed_email`, `is_subscribed_sms`, `is_active`, `order`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(63,70,'Anne K','anne.kaakinen@hiusakatemia.fi','096122700','','',0,0,1,0,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(64,70,'Mari L.','mari.lahnalampi@hiusakatelmia.fi','096122700','','',0,0,1,1,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(65,70,'Ritva K.','Ritva.kassila@hiusakatemia.fi','096122700','','',0,0,1,2,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(66,70,'Pia S.','pia.salminen@hiusakatemia.fi','096122700','','',0,0,1,3,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(67,70,'Kirsi P.','kirsi.pekola@hiusakatemia.fi','096122700','','',0,0,1,4,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(68,70,'Outi Kotipohja','Outi.kotipohja@hiusakatemia.fi','096122700','','',1,0,1,5,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(69,70,'Svetlana','svetlana.gronroos@hiusakatemia.fi','096122700','','',0,0,1,6,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(70,70,'Maria J-N','maria.junerval-nousiainen@hiusakatemia.fi','0926122700','','',0,0,1,7,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(71,70,'Marja H.','Marja.halkola@hiusakatemia.fi','096122700','','',0,0,1,8,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(72,70,'Anne Widomski','anne.maria.widomski@hiusakatemia.fi','096122700','','',0,0,1,10,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(73,70,'Maili L.','maili.lepola@hiusakatemia.fi','096122700','','',0,0,1,11,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(74,70,'Annikki','annikki.hagroskoski@hiusakatemia.fi','096122700','','',0,0,0,12,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(75,70,'Sirpa R','Sirpa.rissanen@hiusakatemia.fi','096122700','','',0,0,1,9,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(267,70,'Marika Westerholm-Amin','marika@hiusakatemia.fi','123','','',0,0,1,14,'2014-09-16 22:33:52','2014-09-16 22:33:52',NULL);

/*!40000 ALTER TABLE `varaa_as_employees` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_extra_service_service
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_extra_service_service`;

CREATE TABLE `varaa_as_extra_service_service` (
  `service_id` int(10) unsigned NOT NULL,
  `extra_service_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`extra_service_id`,`service_id`),
  KEY `as_extra_service_service_service_id_foreign` (`service_id`),
  CONSTRAINT `as_extra_service_service_extra_service_id_foreign` FOREIGN KEY (`extra_service_id`) REFERENCES `varaa_as_extra_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_extra_service_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `varaa_as_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_as_extra_services
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_extra_services`;

CREATE TABLE `varaa_as_extra_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `length` int(10) unsigned DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_extra_services_user_id_foreign` (`user_id`),
  CONSTRAINT `as_extra_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_extra_services` WRITE;
/*!40000 ALTER TABLE `varaa_as_extra_services` DISABLE KEYS */;

INSERT INTO `varaa_as_extra_services` (`id`, `user_id`, `name`, `description`, `price`, `length`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(49,70,'Extra Service 1','Extra Service Message',10,20,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(50,70,'Extra Service 2','Extra Service Message',20,20,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL);

/*!40000 ALTER TABLE `varaa_as_extra_services` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_invoice_bookings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_invoice_bookings`;

CREATE TABLE `varaa_as_invoice_bookings` (
  `invoice_id` int(10) unsigned NOT NULL,
  `booking_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`invoice_id`,`booking_id`),
  KEY `as_invoice_bookings_booking_id_foreign` (`booking_id`),
  CONSTRAINT `as_invoice_bookings_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `varaa_as_bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_invoice_bookings_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `varaa_as_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_as_invoice_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_invoice_products`;

CREATE TABLE `varaa_as_invoice_products` (
  `invoice_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned DEFAULT '0',
  `unit_price` double NOT NULL,
  `amount` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`invoice_id`,`product_id`),
  KEY `as_invoice_products_product_id_foreign` (`product_id`),
  CONSTRAINT `as_invoice_products_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `varaa_as_invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_invoice_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `varaa_as_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_as_invoices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_invoices`;

CREATE TABLE `varaa_as_invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) unsigned NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `total` double NOT NULL,
  `discount` double NOT NULL,
  `deposit` double NOT NULL,
  `amount_due` double NOT NULL,
  `shipping_cost` double NOT NULL,
  `tax` double NOT NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_shipped` tinyint(1) NOT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_invoices_booking_id_foreign` (`booking_id`),
  CONSTRAINT `as_invoices_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `varaa_as_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_as_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_options`;

CREATE TABLE `varaa_as_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `is_visible` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_options_user_id_foreign` (`user_id`),
  CONSTRAINT `as_options_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_options` WRITE;
/*!40000 ALTER TABLE `varaa_as_options` DISABLE KEYS */;

INSERT INTO `varaa_as_options` (`id`, `user_id`, `key`, `name`, `value`, `is_visible`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(134,70,'style_logo','','null',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(135,70,'style_banner','','\"http:\\/\\/www.hiusakatemia.fi\\/images\\/logo.png\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(136,70,'style_heading_color','','null',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(137,70,'style_text_color','','null',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(138,70,'style_background','','\"#ffffff\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(139,70,'style_custom_css','','\"#asContainer_1 td.asCalendarToday {\\r\\nbackground-color: #4eaf30;\\r\\n}\\r\\n\\r\\n.asContainer .asServiceName, .asContainer .asEmployeeName {\\r\\n    color: #4eaf30;\\r\\n}\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(140,70,'style_main_color','','\"#4eaf30;\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(741,70,'working_time','','{\"mon\":{\"start\":\"08:00:00\",\"end\":\"20:00:00\"},\"tue\":{\"start\":\"08:00:00\",\"end\":\"20:00:00\"},\"wed\":{\"start\":\"08:00:00\",\"end\":\"20:00:00\"},\"thu\":{\"start\":\"08:00:00\",\"end\":\"20:00:00\"},\"fri\":{\"start\":\"08:00:00\",\"end\":\"20:00:00\"},\"sat\":{\"start\":\"08:00:00\",\"end\":\"20:00:00\"},\"sun\":{\"start\":\"08:00:00\",\"end\":\"20:00:00\"}}',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(937,70,'confirm_subject_client','','\"Booking confirmation\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(938,70,'confirm_tokens_client','','\"Thank you for your booking. \\r\\n\\r\\nID: {BookingID}\\r\\n\\r\\nServices\\r\\n{Services}\\r\\n\\r\\nPersonal details\\r\\nName: {Name}\\r\\nPhone: {Phone}\\r\\nEmail: {Email}\\r\\n\\r\\nThis is the price for your booking\\r\\nTax: {Price}\\r\\nTax: {Tax}\\r\\nTotal: {Total}\\r\\nDeposit required to confirm your booking: {Deposit}\\r\\n\\r\\nAdditional notes:\\r\\n{Notes}\\r\\n\\r\\nThank you,\\r\\nThe Management\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(939,70,'confirm_subject_admin','','\"New booking received\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(940,70,'confirm_tokens_admin','','\"New booking has been made. \\r\\n\\r\\nID: {BookingID}\\r\\n\\r\\nServices\\r\\n{Services}\\r\\n\\r\\nPersonal details\\r\\nName: {Name}\\r\\nPhone: {Phone}\\r\\nEmail: {Email}\\r\\n\\r\\nPrice\\r\\nTax: {Price}\\r\\nTax: {Tax}\\r\\nTotal: {Total}\\r\\nDeposit required to confirm the booking: {Deposit}\\r\\n\\r\\nAdditional notes:\\r\\n{Notes}\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(941,70,'confirm_subject_employee','','\"New appointment received\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(942,70,'confirm_tokens_employee','','\"New appointment has been made.\\r\\n\\r\\nID: {BookingID}\\r\\n\\r\\nServices\\r\\n{Services}\\r\\n\\r\\nPersonal details\\r\\nName: {Name}\\r\\nPhone: {Phone}\\r\\nEmail: {Email}\\r\\n\\r\\nAdditional notes:\\r\\n{Notes}\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(1327,70,'confirm_subject_client','','\"Kiitos varauksestasi\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(1328,70,'confirm_tokens_client','','\"Hei!\\r\\n\\r\\nKiitos varauksestasi!\\r\\n\\r\\nValitut palvelut: \\r\\n{Services}\\r\\n\\r\\n**Mik\\u00e4li peruutat varauksen se tulee tehd\\u00e4 48 tuntia ennen varattua aikaa.\\r\\n\\r\\nTervetuloa!\\r\\n\\r\\nTerveisin, Hiusakatemia\\r\\npuh. 09 6122 700\\r\\n\\r\\nPalvelun tarjoaa varaa.com\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(1329,70,'confirm_subject_admin','','\"Uusi varaus on saapunut\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(1330,70,'confirm_tokens_admin','','\"Hei!\\r\\n\\r\\nOlet saanut uuden varauksen\\r\\n\\r\\nID: {BookingID}\\r\\n\\r\\nPalvelut\\r\\n{Services}\\r\\n\\r\\nAsiakkaan tiedot\\r\\nNimi: {Name}\\r\\nPuhelin: {Phone}\\r\\nEmail: {Email}\\r\\n\\r\\nLis\\u00e4tiedot:\\r\\n{Notes}\\r\\n\\r\\nTerveisin, Hiusakatemia\\r\\npuh. 09 6122 700\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(1331,70,'confirm_subject_employee','','\"Uusi varaus on saapunut\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(1332,70,'confirm_tokens_employee','','\"Hei!\\r\\n\\r\\nOlet saanut uuden varauksen\\r\\n\\r\\nID: {BookingID}\\r\\n\\r\\nPalvelut\\r\\n{Services}\\r\\n\\r\\nAsiakkaan tiedot\\r\\nNimi: {Name}\\r\\nPuhelin: {Phone}\\r\\nEmail: {Email}\\r\\n\\r\\nLis\\u00e4tiedot:\\r\\n{Notes}\\r\\n\\r\\nTerveisin, Hiusakatemia\\r\\npuh. 09 6122 700\"',1,'2014-09-16 22:35:08','2014-09-16 22:35:08',NULL),
	(1498,70,'confirm_email_enable','','\"0\"',0,'2014-09-19 10:55:59','2014-09-19 10:55:59',NULL),
	(1499,70,'confirm_sms_enable','','\"0\"',0,'2014-09-19 10:55:59','2014-09-19 10:55:59',NULL),
	(1500,70,'confirm_consumer_sms_message','','\"Hei,\\r\\n\\r\\nKiitos varauksestasi palveluun:\\r\\n\\r\\n{Services}\\r\\n\\r\\nTerveisin,\"',0,'2014-09-19 10:55:59','2014-09-19 10:55:59',NULL),
	(1501,70,'confirm_employee_sms_message','','\"Hei,\\r\\n\\r\\nSinulle on uusi varaus asiakkaalta {Consumer} palveluun {Services}\\r\\n\\r\\nTerveisin,\"',0,'2014-09-19 10:55:59','2014-09-19 10:55:59',NULL),
	(1502,70,'terms_body','','\"Varausehdot\\r\\n\\r\\nVaraus tulee sitovasti voimaan, kun asiakas on tehnyt varauksen ja saanut siit\\u00e4 vahvistuksen joko puhelimitse tai kirjallisesti s\\u00e4hk\\u00f6postitse. Palveluntarjoaja kantaa kaiken vastuun palvelun tuottamisesta ja hoitaa tarvittaessa kaiken yhteydenpidon asiakkaisiin.\\r\\n\\r\\nPeruutusehdot\\r\\n\\r\\nVaraajalla on oikeus peruutus- ja varausehtojen puitteissa peruuttaa varauksensa ilmoittamalla siit\\u00e4 puhelimitse v\\u00e4hint\\u00e4\\u00e4n 48h ennen palveluajan alkamista. Muutoin paikalle saapumatta j\\u00e4tt\\u00e4misest\\u00e4 voi palveluntarjoaja halutessaan peri\\u00e4 voimassaolevan hinnastonsa mukaisen palvelukorvauksen.\"',0,'2014-09-19 10:55:59','2014-09-19 10:55:59',NULL);

/*!40000 ALTER TABLE `varaa_as_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_product_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_product_categories`;

CREATE TABLE `varaa_as_product_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_as_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_products`;

CREATE TABLE `varaa_as_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_category_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_products_product_category_id_foreign` (`product_category_id`),
  CONSTRAINT `as_products_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `varaa_as_product_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_as_resource_service
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_resource_service`;

CREATE TABLE `varaa_as_resource_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(10) unsigned NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `as_resource_service_service_id_foreign` (`service_id`),
  KEY `as_resource_service_resource_id_foreign` (`resource_id`),
  CONSTRAINT `as_resource_service_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `varaa_as_resources` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_resource_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `varaa_as_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_resource_service` WRITE;
/*!40000 ALTER TABLE `varaa_as_resource_service` DISABLE KEYS */;

INSERT INTO `varaa_as_resource_service` (`id`, `service_id`, `resource_id`, `created_at`, `updated_at`)
VALUES
	(99,305,9,'2014-09-16 22:33:51','2014-09-16 22:33:51');

/*!40000 ALTER TABLE `varaa_as_resource_service` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_resources
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_resources`;

CREATE TABLE `varaa_as_resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(10) unsigned DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_resources_user_id_foreign` (`user_id`),
  CONSTRAINT `as_resources_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_resources` WRITE;
/*!40000 ALTER TABLE `varaa_as_resources` DISABLE KEYS */;

INSERT INTO `varaa_as_resources` (`id`, `user_id`, `name`, `description`, `quantity`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(9,70,'Bemerlaite','',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL);

/*!40000 ALTER TABLE `varaa_as_resources` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_service_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_service_categories`;

CREATE TABLE `varaa_as_service_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_show_front` tinyint(1) NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_service_categories_user_id_foreign` (`user_id`),
  CONSTRAINT `as_service_categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_service_categories` WRITE;
/*!40000 ALTER TABLE `varaa_as_service_categories` DISABLE KEYS */;

INSERT INTO `varaa_as_service_categories` (`id`, `user_id`, `name`, `description`, `is_show_front`, `order`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(105,70,'Hiuspohjatutkimus','Kun kärsit kutinasta, hilseestä tai muusta hiuspohjaongelmasta.\r\nHuom! Syötä varausta tehdessäsi mahdollinen kampanjakoodi \'muistiinpanoja\' -kenttään!',1,1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(106,70,'Hiusjuuritutkimus','Silloin kun kärsit hiustenlähdöstä.\r\nHuom! Syötä varausta tehdessäsi mahdollinen kampanjakoodi \'muistiinpanoja\' -kenttään! ',1,0,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(107,70,'Parturi- Kampaamopalvelut','',1,3,'2014-09-16 22:33:49','2014-09-17 17:01:04',NULL),
	(108,70,'Hiushoitolapalvelut','',1,2,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(109,70,'Kosmetologi- ja hierontapalvelut','',1,4,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(110,70,'Funktionaalisen lääketieteenvastaanotto','',1,6,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(111,70,'Hiusakatemia','',0,7,'2014-09-16 22:33:49','2014-09-22 14:15:35',NULL),
	(112,70,'Kampaukset','',1,9,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(521,70,'Ravintoneuvonta','Sisältää 2 tapaamista ravintoneuvoojan luona.',1,5,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL);

/*!40000 ALTER TABLE `varaa_as_service_categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_service_times
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_service_times`;

CREATE TABLE `varaa_as_service_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(10) unsigned NOT NULL,
  `price` double NOT NULL,
  `length` int(10) unsigned DEFAULT '0',
  `before` int(10) unsigned DEFAULT '0',
  `during` int(10) unsigned DEFAULT '0',
  `after` int(10) unsigned DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_service_times_service_id_foreign` (`service_id`),
  CONSTRAINT `as_service_times_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `varaa_as_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_service_times` WRITE;
/*!40000 ALTER TABLE `varaa_as_service_times` DISABLE KEYS */;

INSERT INTO `varaa_as_service_times` (`id`, `service_id`, `price`, `length`, `before`, `during`, `after`, `description`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(122,301,48,60,0,45,15,'45min','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(123,301,78,90,0,75,15,'75min','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(124,314,60,60,0,60,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(125,314,70,90,0,90,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(126,315,50,75,0,75,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(127,315,58,90,0,90,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(128,316,50,75,0,75,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(129,316,58,90,0,90,0,'','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(130,317,50,75,0,75,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(131,317,58,90,0,90,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(132,318,50,75,0,75,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(133,318,58,90,0,90,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(134,319,50,75,0,75,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(135,319,58,90,0,90,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(136,321,150,150,0,150,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(137,321,173,180,0,180,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(138,322,160,180,0,180,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(139,322,189,210,0,210,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(140,323,120,150,0,150,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(141,323,140,180,0,180,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(142,324,160,180,0,180,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(143,324,189,210,0,210,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(144,325,50,120,0,120,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(145,325,62,150,0,150,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(146,326,70,150,0,150,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(147,326,85,180,0,180,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(148,348,0,120,0,120,0,'2h','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(149,348,0,180,0,180,0,'3h','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(150,356,50,75,0,75,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(151,356,58,90,0,90,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(152,357,50,75,0,75,0,'Keskipitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL),
	(153,357,58,90,0,90,0,'Pitkät','2014-09-16 22:33:52','2014-09-16 22:33:52',NULL);

/*!40000 ALTER TABLE `varaa_as_service_times` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_as_services
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_as_services`;

CREATE TABLE `varaa_as_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `length` int(10) unsigned DEFAULT '0',
  `before` int(10) unsigned DEFAULT '0',
  `during` int(10) unsigned DEFAULT '0',
  `after` int(10) unsigned DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as_services_user_id_foreign` (`user_id`),
  KEY `as_services_category_id_foreign` (`category_id`),
  CONSTRAINT `as_services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `varaa_as_service_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `as_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_as_services` WRITE;
/*!40000 ALTER TABLE `varaa_as_services` DISABLE KEYS */;

INSERT INTO `varaa_as_services` (`id`, `category_id`, `user_id`, `name`, `price`, `length`, `before`, `during`, `after`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(301,109,70,'Klassinen hieronta',35,45,0,30,15,'30min',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(302,109,70,'Vahvistava tyrnikasvohoito',69,75,0,60,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(303,109,70,'Rauhoittava Mustaherukkakasvohoito',69,75,0,60,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(304,109,70,'Kosteuttava Vadelmakasvohoito',69,75,0,60,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(305,109,70,'Anti-Akne Kasvohoito',69,75,0,60,15,'esimerkki esimerkki',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(306,109,70,'Yksilöllinen kasvohoito juuri sinulle 60min',69,90,0,60,30,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(307,109,70,'Hemmotteleva kasvohoito',89,90,0,75,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(308,109,70,'BTB13 Tehokasvohoito',75,75,0,60,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(309,109,70,'Erikoisteho kasvohoito',95,90,0,75,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(310,109,70,'Pikakasvohoito',35,45,0,30,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(311,109,70,'Ihonpuhdistus',79,90,0,75,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(312,109,70,'BTB13 Erikoisvalohoito',42,60,0,45,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(313,109,70,'Bemer-hoito',25,30,0,30,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(314,107,70,'Hiustenleikkaus',35,45,0,45,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(315,108,70,'Éxtreme tehohoito vaurioituneille hiuksille',42,60,0,60,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(316,108,70,'Colore tehohoito värikäsitellyille hiuksille',42,60,0,60,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(317,108,70,'Soft tehohoito kuiville hauraille hiuksille',42,60,0,60,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(318,108,70,'Luomukookosöljy tehohoito',42,60,0,60,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(319,108,70,'Räätälöity tehohoito juuri sinulle',42,60,0,60,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(320,107,70,'Hiusmallin muutosleikkaus ja suunnittelu',80,90,0,90,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(321,107,70,'Väri/raidat yhdellä värillä, sis. leikkaus, muotoonkuivaus ',126,120,0,120,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(322,107,70,'Monivärjäys/-raidat sis. leikkaus, muotoonkuivaus',145,150,0,150,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(323,107,70,'Tyviväri, . sis leikkaus ja muotoonkuivaus',102,120,0,120,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(324,107,70,'Permanentti sis. leikkaus ja muotoonkuivaus',140,150,0,150,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(325,112,70,'Kampaus sis.pesu, föönaus/rullakampaus',45,90,0,90,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(326,112,70,'Juhlakampaus sis. kaikki tarvikkeet + pinnit',57,120,0,120,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(327,112,70,'Vanhojentanssi / Hääkampaus',85,180,0,180,0,'hinnat ovat alkaen hintoja',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(328,108,70,'Kapillaarihoito',49,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-22 13:07:11',NULL),
	(329,108,70,'Erikoiskapillaarihoito',59,90,0,90,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(330,108,70,'Tehokapillaarihoito',69,120,0,120,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(331,108,70,'Syväpuhdistus ja kapillaarihoito',75.2,90,0,90,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(332,108,70,'Syväpuhdistus ja erikoiskapillaarihoito',84.2,105,0,105,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(333,108,70,'Hiusten syväpuhdistus',40,45,0,45,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(334,108,70,'Turvehoito',55,90,0,90,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(335,108,70,'Öljyhoito hiuspohjan kireyden poistoon',58,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(336,108,70,'Öljyhoito psoriaasiin',76,90,0,90,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(337,108,70,'Öljyhoito atopiaan ja hiuspohjan kuivuuteen',58,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(338,108,70,'Hiuslaserhoito',49,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(339,108,70,'Hiuspohjan hieronta',33,30,0,30,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(340,108,70,'Sienihoidot',60,75,0,75,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(341,106,70,'Hiusjuuritutkimus',138.6,135,0,135,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(342,105,70,'Hiuspohjatutkimus',66,75,0,75,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(343,108,70,'Hiusten laatu ja kunto tutkimus',66,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(344,111,70,'TP1',116,150,0,150,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(345,111,70,'TP2',116,150,0,150,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(346,111,70,'TP3',116,150,0,150,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(347,111,70,'Hoito',0,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(348,111,70,'Tapaaminen',0,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(349,111,70,'Koulutus',0,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-22 15:04:01',NULL),
	(350,111,70,'Asiakasilta',0,150,0,150,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(351,111,70,'Rentouttava turvehoito vartalolle',65,90,0,75,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(352,111,70,'Välitarkistus',0,15,0,15,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(353,111,70,'Jälkitarkistus',35.5,30,0,30,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(354,111,70,'Seurantatarkistus',66,60,0,60,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(355,110,70,'Maili Lepolan vastaanotto',160,90,0,90,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(356,108,70,'Anti-Frizz tehohoito erittäin kuiville pörröisille hiuksille',42,60,0,60,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(357,108,70,'Control tehohoito herkistyneille paksuille hiuksille',42,60,0,60,0,'Lyhyet',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(358,109,70,'Ripsien ja kulmien kestovärjäys + muotoilu',29,60,0,45,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(359,111,70,'Kapillaarihoito Pia',49.9,30,0,30,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(360,111,70,'Sieniviljely',63.2,30,0,30,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(361,109,70,'Klassinen hieronta',48,60,0,45,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(362,109,70,'Klassinen hieronta',78,105,0,75,30,'75 minuutin hieronta',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(363,109,70,'Kulmien kestovärjäys',11,30,0,30,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(364,109,70,'Kulmien kestovärjäys ja muotoilu',17,45,0,45,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(365,109,70,'Ripsien kestovärjäys',15,30,0,30,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(366,109,70,'Ripsien ja kulmien kestovärjäys',23,45,0,45,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(367,111,70,'Käynti postissa tai tukussa',0,30,0,30,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(1476,111,70,'Ravintoneuvonta',120,60,0,45,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(1580,111,70,'Toimisto',0,120,0,120,0,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(1927,521,70,'Ravintoneuvonta',120,60,0,45,15,'',1,'2014-09-16 22:33:49','2014-09-16 22:33:49',NULL),
	(1964,106,70,'test service',100,0,0,0,0,'',1,'2014-09-23 14:45:05','2014-09-26 14:41:49','2014-09-26 14:41:49');

/*!40000 ALTER TABLE `varaa_as_services` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_assigned_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_assigned_roles`;

CREATE TABLE `varaa_assigned_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assigned_roles_user_id_foreign` (`user_id`),
  KEY `assigned_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `assigned_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `varaa_roles` (`id`),
  CONSTRAINT `assigned_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_assigned_roles` WRITE;
/*!40000 ALTER TABLE `varaa_assigned_roles` DISABLE KEYS */;

INSERT INTO `varaa_assigned_roles` (`id`, `user_id`, `role_id`)
VALUES
	(64,70,1);

/*!40000 ALTER TABLE `varaa_assigned_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_business_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_business_categories`;

CREATE TABLE `varaa_business_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_business_categories` WRITE;
/*!40000 ALTER TABLE `varaa_business_categories` DISABLE KEYS */;

INSERT INTO `varaa_business_categories` (`id`, `name`, `parent_id`, `keywords`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,'beauty_hair',NULL,'','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(2,'beautysalon','1','facial treatment, feet treatment, eyelash extension, waxing, sugaring,hair removal, pedicure, manicure, body treatment, beauty, make up','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(3,'nails','1','manicure, pedicure','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(4,'hairdresser','1','Hair cut, hair coloring, hair styling, hair extensions','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(5,'restaurant',NULL,'','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(6,'fine_dining','5','steak, fine dining','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(7,'nepalese','5','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(8,'traditional','5','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(9,'sushi','5','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(10,'thai','5','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(11,'italian','5','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(12,'grill','5','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(13,'chinese','5','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(14,'car',NULL,'','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(15,'car_wash','14','car detailing, car cleaning, car wash','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(16,'car_service','14','tire rotation, car service, oil change, window shield repair, cool system flush','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(17,'bike_service','14','bike maintenance, bike repair','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(18,'wellness',NULL,'','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(19,'physical_theraphy','18','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(20,'massage','18','hot stone massage, massage, ','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(21,'dentist','18','dental care, ','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(22,'acupuncture','18','acupuncture, chinese wellness','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(23,'chiropractic_treatment','18','back problem, chiropractitian','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(24,'teeth_whitening','18','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(25,'activities',NULL,'','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(26,'bowling','25','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(27,'karting','25','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(28,'gym','25','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(29,'dance','25','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(30,'badminton','25','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(31,'tennis','25','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(32,'personal_training','25','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(33,'yoga','25','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(34,'home',NULL,'','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(35,'house_cleaning','34','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(36,'handyman','34','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(37,'photography','34','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(38,'babysitting','34','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL),
	(39,'snow_removal','34','','2014-09-16 22:32:12','2014-09-16 22:32:12',NULL);

/*!40000 ALTER TABLE `varaa_business_categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_business_category_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_business_category_user`;

CREATE TABLE `varaa_business_category_user` (
  `user_id` int(10) unsigned NOT NULL,
  `business_category_id` int(10) unsigned NOT NULL,
  KEY `business_category_user_user_id_foreign` (`user_id`),
  KEY `business_category_user_business_category_id_foreign` (`business_category_id`),
  CONSTRAINT `business_category_user_business_category_id_foreign` FOREIGN KEY (`business_category_id`) REFERENCES `varaa_business_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `business_category_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_business_category_user` WRITE;
/*!40000 ALTER TABLE `varaa_business_category_user` DISABLE KEYS */;

INSERT INTO `varaa_business_category_user` (`user_id`, `business_category_id`)
VALUES
	(70,1),
	(70,4);

/*!40000 ALTER TABLE `varaa_business_category_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_cart_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_cart_details`;

CREATE TABLE `varaa_cart_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int(10) unsigned NOT NULL,
  `item` int(10) unsigned NOT NULL,
  `variant` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `quantity` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_details_cart_id_foreign` (`cart_id`),
  CONSTRAINT `cart_details_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `varaa_carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_carts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_carts`;

CREATE TABLE `varaa_carts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `consumer_id` int(10) unsigned DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_consumer_id_foreign` (`consumer_id`),
  CONSTRAINT `carts_consumer_id_foreign` FOREIGN KEY (`consumer_id`) REFERENCES `varaa_consumers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_consumer_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_consumer_user`;

CREATE TABLE `varaa_consumer_user` (
  `consumer_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `is_visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`consumer_id`,`user_id`),
  KEY `consumer_user_user_id_foreign` (`user_id`),
  CONSTRAINT `consumer_user_consumer_id_foreign` FOREIGN KEY (`consumer_id`) REFERENCES `varaa_consumers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consumer_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_consumer_user` WRITE;
/*!40000 ALTER TABLE `varaa_consumer_user` DISABLE KEYS */;

INSERT INTO `varaa_consumer_user` (`consumer_id`, `user_id`, `is_visible`)
VALUES
	(2075,70,1),
	(2253,70,1),
	(2396,70,1),
	(2402,70,1),
	(2433,70,1),
	(2453,70,1),
	(2454,70,1),
	(2461,70,1),
	(2466,70,1),
	(2468,70,1),
	(2480,70,1),
	(2499,70,1);

/*!40000 ALTER TABLE `varaa_consumer_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_consumers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_consumers`;

CREATE TABLE `varaa_consumers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_consumers` WRITE;
/*!40000 ALTER TABLE `varaa_consumers` DISABLE KEYS */;

INSERT INTO `varaa_consumers` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `postcode`, `country`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(2075,'Kaivonen','Marja-Riitta','asconsumer_1886@varaa.com','407491409','','','','','2014-07-07 07:01:39','2014-09-25 14:36:14',NULL),
	(2253,'Joenpelto','Timo','asconsumer_1962@varaa.com','9594226','','','','','2014-07-07 07:01:39','2014-10-06 11:35:01',NULL),
	(2396,'Jaatinen','Jarl','JJ.econtact@gmail.com','405885122','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2402,'Kangas','Kaija','kaija.kangas@teak.fi','405105924','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2433,'Keeroja','Kaie','kkeeroja@hotmail.com','407192401','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2453,'Hirvonen','Vuokko','asconsumer_1809@varaa.com','408210240','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2454,'Eklund','Maria','asconsumer_1810@varaa.com','040 509 2035','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2461,'Kaakinen','Elisa','asconsumer_1817@varaa.com','044 217 7909','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2466,'Autio','Virpi','asconsumer_1821@varaa.com','407505056','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2468,'erikson','susanna','asconsumer_1823@varaa.com','050-5828246','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2480,'heikkilä','leena','asconsumer_1835@varaa.com','050-4430275','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL),
	(2499,'Alexandra','von','asconsumer_1854@varaa.com','505380973','','','','','2014-07-07 07:01:39','2014-07-07 07:01:39',NULL);

/*!40000 ALTER TABLE `varaa_consumers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_disabled_modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_disabled_modules`;

CREATE TABLE `varaa_disabled_modules` (
  `user_id` int(10) unsigned NOT NULL,
  `module` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`,`module`),
  CONSTRAINT `disabled_modules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_fd_coupons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_fd_coupons`;

CREATE TABLE `varaa_fd_coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `discounted_price` double NOT NULL,
  `valid_date` date NOT NULL,
  `quantity` mediumint(9) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fd_coupons_service_id_foreign` (`service_id`),
  KEY `fd_coupons_user_id_foreign` (`user_id`),
  CONSTRAINT `fd_coupons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fd_coupons_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `varaa_fd_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_fd_flash_deal_dates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_fd_flash_deal_dates`;

CREATE TABLE `varaa_fd_flash_deal_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `flash_deal_id` int(10) unsigned NOT NULL,
  `expire` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remains` mediumint(9) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fd_flash_deal_dates_flash_deal_id_expire_unique` (`flash_deal_id`,`expire`),
  KEY `fd_flash_deal_dates_user_id_foreign` (`user_id`),
  CONSTRAINT `fd_flash_deal_dates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fd_flash_deal_dates_flash_deal_id_foreign` FOREIGN KEY (`flash_deal_id`) REFERENCES `varaa_fd_flash_deals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_fd_flash_deals
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_fd_flash_deals`;

CREATE TABLE `varaa_fd_flash_deals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `discounted_price` double NOT NULL,
  `quantity` mediumint(9) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fd_flash_deals_service_id_foreign` (`service_id`),
  KEY `fd_flash_deals_user_id_foreign` (`user_id`),
  CONSTRAINT `fd_flash_deals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fd_flash_deals_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `varaa_fd_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_fd_services
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_fd_services`;

CREATE TABLE `varaa_fd_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `business_category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fd_services_user_id_foreign` (`user_id`),
  KEY `fd_services_business_category_id_foreign` (`business_category_id`),
  CONSTRAINT `fd_services_business_category_id_foreign` FOREIGN KEY (`business_category_id`) REFERENCES `varaa_business_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fd_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_images`;

CREATE TABLE `varaa_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `imageable_id` int(10) unsigned NOT NULL,
  `imageable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `images_user_id_foreign` (`user_id`),
  CONSTRAINT `images_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_lc_consumers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_lc_consumers`;

CREATE TABLE `varaa_lc_consumers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `consumer_id` int(10) unsigned NOT NULL,
  `total_points` int(11) NOT NULL,
  `total_stamps` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lc_consumers_consumer_id_user_id_unique` (`consumer_id`,`user_id`),
  KEY `lc_consumers_user_id_foreign` (`user_id`),
  CONSTRAINT `lc_consumers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lc_consumers_consumer_id_foreign` FOREIGN KEY (`consumer_id`) REFERENCES `varaa_consumers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_lc_offers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_lc_offers`;

CREATE TABLE `varaa_lc_offers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `required` int(10) unsigned NOT NULL,
  `total_used` int(10) unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_auto_add` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lc_offers_user_id_foreign` (`user_id`),
  CONSTRAINT `lc_offers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_lc_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_lc_transactions`;

CREATE TABLE `varaa_lc_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `consumer_id` int(10) unsigned NOT NULL,
  `voucher_id` int(10) unsigned DEFAULT NULL,
  `offer_id` int(10) unsigned DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `stamp` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `lc_transactions_consumer_id_foreign` (`consumer_id`),
  KEY `lc_transactions_voucher_id_foreign` (`voucher_id`),
  KEY `lc_transactions_offer_id_foreign` (`offer_id`),
  CONSTRAINT `lc_transactions_consumer_id_foreign` FOREIGN KEY (`consumer_id`) REFERENCES `varaa_lc_consumers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lc_transactions_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `varaa_lc_offers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lc_transactions_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `varaa_lc_vouchers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_lc_vouchers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_lc_vouchers`;

CREATE TABLE `varaa_lc_vouchers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `required` int(10) unsigned NOT NULL,
  `total_used` int(10) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lc_vouchers_user_id_foreign` (`user_id`),
  CONSTRAINT `lc_vouchers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_migrations`;

CREATE TABLE `varaa_migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_migrations` WRITE;
/*!40000 ALTER TABLE `varaa_migrations` DISABLE KEYS */;

INSERT INTO `varaa_migrations` (`migration`, `batch`)
VALUES
	('2014_08_08_105602_confide_setup_users_table',1),
	('2014_08_08_185353_add_remember_token_to_table_users',1),
	('2014_08_11_123507_add_old_password_to_table_users',1),
	('2014_08_11_130338_entrust_setup_tables',1),
	('2014_08_21_081438_create_table_consumers',3),
	('2014_08_22_180354_add_table_consumer_user',3),
	('2014_08_22_184731_remove_user_id_from_table_consumers',3),
	('2014_08_19_185946_create_table_modules',4),
	('2014_08_19_190327_create_table_module_user',4),
	('2014_09_05_065951_remove_fields_in_table_users',5),
	('2014_09_05_070927_create_business_categories',5),
	('2014_09_05_073859_create_business_category_user',5),
	('2014_09_05_081611_add_fields_to_users',5),
	('2014_09_05_230316_create_table_images',5),
	('2014_09_13_152056_create_table_disabled_modules',6),
	('2014_08_20_135351_create_as_service_categories_table',7),
	('2014_08_20_135539_create_as_services_table',7),
	('2014_08_20_140520_create_as_extra_services_table',7),
	('2014_08_20_143826_create_as_extra_service_service_table',7),
	('2014_08_20_145528_create_as_service_times_table',7),
	('2014_08_20_145530_create_as_resources_table',7),
	('2014_08_20_145532_create_as_resource_service_table',7),
	('2014_08_20_151752_create_as_employees_table',7),
	('2014_08_20_152423_create_as_employee_service_table',7),
	('2014_08_20_153019_create_as_employee_freetime_table',7),
	('2014_08_20_173752_create_as_employee_default_time_table',7),
	('2014_08_20_174012_create_as_employee_custom_time_table',7),
	('2014_08_20_175344_create_as_options_table',7),
	('2014_08_20_180402_create_as_consumers_table',7),
	('2014_08_20_180502_create_as_bookings_table',7),
	('2014_08_20_181039_create_as_booking_payments_table',7),
	('2014_08_20_182345_create_as_booking_services_table',7),
	('2014_08_20_185201_create_as_booking_extra_services_table',7),
	('2014_08_21_131600_create_as_invoices_table',7),
	('2014_08_21_132503_create_as_product_categories_table',7),
	('2014_08_21_132603_create_as_products_table',7),
	('2014_08_21_132943_create_as_invoice_products_table',7),
	('2014_08_21_135651_create_as_invoice_bookings_table',7),
	('2014_09_11_200459_add_field_tmp_uuid_to_as_booking_extra_service_table',7),
	('2014_09_11_214521_add_field_total_price_to_as_bookings_table',7),
	('2014_09_12_122757_allow_null_in_table_as_bookings',7),
	('2014_09_12_151026_add_soft_delete_field',7),
	('2014_09_12_173400_allow_more_null_in_as_bookings',7),
	('2014_09_14_040611_modify_field_total_in_as_bookings_table',7),
	('2014_09_14_201958_add_index_to_uuid_column_in_as_bookings_table',7),
	('2014_09_15_045359_add_field_plustime_in_as_bookings_table',7),
	('2014_09_15_185852_create_as_custom_times',7),
	('2014_09_15_190317_add_column_custom_time_id_to_employee_custom_time_table',7),
	('2014_09_15_190638_drop_column_is_day_off_from_employee_custom_time_table',7),
	('2014_09_15_201015_drop_old_columns_from_employee_custom_time_table',7),
	('2014_09_16_002540_add_soft_delete_column_to_custom_time_table',7),
	('2014_09_16_002856_move_custom_time_id_column_after_employee_id_in_employee_custom_time_table',7),
	('2014_09_17_011420_change_tiny_int_to_integer_for_some_columns',7),
	('2014_09_12_181141_add_business_name_to_table_users',8),
	('2014_09_14_103709_add_table_fd_services',9),
	('2014_09_14_105552_add_table_fd_coupons',9),
	('2014_09_14_120037_add_table_fd_flash_deals',9),
	('2014_09_14_120316_add_table_fd_flash_deal_dates',9),
	('2014_09_14_142227_add_category_id_to_fd_services',9),
	('2014_09_14_165402_remove_quantity_from_fd_flash_deals',9),
	('2014_09_14_192949_remove_quantity_from_fd_services',9),
	('2014_09_14_195714_merge_date_time_in_fd_flash_deal_dates',9),
	('2014_09_14_200450_add_date_to_fd_flash_deal_dates',9),
	('2014_08_21_183923_create_mt_campaign',10),
	('2014_08_21_193122_create_mt_sms',10),
	('2014_08_21_193418_create_mt_settings',10),
	('2014_08_21_194039_create_mt_groups',10),
	('2014_08_21_194614_create_mt_historys',10),
	('2014_08_21_194941_create_mt_templates',10),
	('2014_08_22_042920_add_status_to_mt_sms',10),
	('2014_08_23_174219_delete_thumbnail_from_mt_templates',10),
	('2014_08_25_033937_delete_type_from_mt_settings',10),
	('2014_08_25_034420_delete_type_from_mt_historys',10),
	('2014_09_02_135626_add_thumbnail_to_mt_templates',10),
	('2014_09_04_075338_create_mt_group_consumers',10),
	('2014_09_17_182844_add_notes_to_as_bookings',11),
	('2014_09_17_191415_add_user_id_to_fd_tables',12),
	('2014_09_18_021318_drop_unique_index_for_email_column_in_varaa_consumers_table',13),
	('2014_09_21_220704_change_field_modify_time_to_signed_int_in_as_bookings_table',14),
	('2014_08_21_103016_create_table_lc_consumers',15),
	('2014_08_21_150929_create_table_lc_offers',15),
	('2014_08_21_152717_create_table_lc_vouchers',15),
	('2014_08_25_191636_create_table_lc_transactions',15),
	('2014_08_28_123332_alter_table_lc_consumers_add_soft_delete',15),
	('2014_08_28_124242_alter_table_lc_offers_add_soft_delete',15),
	('2014_08_28_124255_alter_table_lc_vouchers_add_soft_delete',15),
	('2014_09_08_164513_alter_table_lc_offers_remove_free_service',15),
	('2014_09_08_164843_alter_table_lc_transactions_remove_free_service',15),
	('2014_09_10_135300_alter_table_lc_consumers_add_unique',15),
	('2014_10_04_124040_add_table_cart',16),
	('2014_10_04_150244_add_table_cart_details',16),
	('2014_10_06_145400_add_source_column_to_as_bookings_table',17),
	('2014_10_09_133444_create_table_transactions',18);

/*!40000 ALTER TABLE `varaa_migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_module_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_module_user`;

CREATE TABLE `varaa_module_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `module_user_module_id_foreign` (`module_id`),
  KEY `module_user_user_id_foreign` (`user_id`),
  CONSTRAINT `module_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_user_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `varaa_modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_module_user` WRITE;
/*!40000 ALTER TABLE `varaa_module_user` DISABLE KEYS */;

INSERT INTO `varaa_module_user` (`id`, `module_id`, `user_id`, `start`, `end`, `is_active`, `created_at`, `updated_at`)
VALUES
	(321,1,70,'2014-08-27 00:44:08','2114-08-27 00:44:08',1,'2014-08-27 00:44:09','2014-08-27 00:44:09'),
	(322,2,70,'2014-08-27 00:44:08','2114-08-27 00:44:08',0,'2014-08-27 00:44:09','2014-08-27 00:44:09'),
	(323,3,70,'2014-08-27 00:44:08','2114-08-27 00:44:08',0,'2014-08-27 00:44:09','2014-08-27 00:44:09'),
	(324,4,70,'2014-08-27 00:44:08','2114-08-27 00:44:08',0,'2014-08-27 00:44:09','2014-08-27 00:44:09'),
	(325,5,70,'2014-08-27 00:44:08','2114-08-27 00:44:08',0,'2014-08-27 00:44:09','2014-08-27 00:44:09');

/*!40000 ALTER TABLE `varaa_module_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_modules`;

CREATE TABLE `varaa_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_modules` WRITE;
/*!40000 ALTER TABLE `varaa_modules` DISABLE KEYS */;

INSERT INTO `varaa_modules` (`id`, `name`, `uri`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,'appointment','/services/appointment-scheduler','2014-08-27 00:44:08','2014-08-27 00:44:08',NULL),
	(2,'cashier','/services/cashier','2014-08-27 00:44:08','2014-08-27 00:44:08',NULL),
	(3,'restaurant','/services/restaurant-booking','2014-08-27 00:44:08','2014-08-27 00:44:08',NULL),
	(4,'timeslot','/services/timeslot','2014-08-27 00:44:08','2014-08-27 00:44:08',NULL),
	(5,'loyalty','/services/loyalty-program','2014-08-27 00:44:08','2014-08-27 00:44:08',NULL);

/*!40000 ALTER TABLE `varaa_modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_mt_campaign
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_mt_campaign`;

CREATE TABLE `varaa_mt_campaign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `from_email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `from_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `campaign_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mt_campaign_user_id_foreign` (`user_id`),
  CONSTRAINT `mt_campaign_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_mt_group_consumers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_mt_group_consumers`;

CREATE TABLE `varaa_mt_group_consumers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned DEFAULT NULL,
  `consumer_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mt_group_consumers_user_id_foreign` (`user_id`),
  KEY `mt_group_consumers_group_id_foreign` (`group_id`),
  KEY `mt_group_consumers_consumer_id_foreign` (`consumer_id`),
  CONSTRAINT `mt_group_consumers_consumer_id_foreign` FOREIGN KEY (`consumer_id`) REFERENCES `varaa_consumers` (`id`),
  CONSTRAINT `mt_group_consumers_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `varaa_mt_groups` (`id`),
  CONSTRAINT `mt_group_consumers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_mt_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_mt_groups`;

CREATE TABLE `varaa_mt_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `is_individual` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mt_groups_user_id_foreign` (`user_id`),
  CONSTRAINT `mt_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_mt_historys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_mt_historys`;

CREATE TABLE `varaa_mt_historys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned DEFAULT NULL,
  `sms_id` int(10) unsigned DEFAULT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mt_historys_user_id_foreign` (`user_id`),
  KEY `mt_historys_campaign_id_foreign` (`campaign_id`),
  KEY `mt_historys_sms_id_foreign` (`sms_id`),
  KEY `mt_historys_group_id_foreign` (`group_id`),
  CONSTRAINT `mt_historys_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `varaa_mt_campaign` (`id`),
  CONSTRAINT `mt_historys_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `varaa_mt_groups` (`id`),
  CONSTRAINT `mt_historys_sms_id_foreign` FOREIGN KEY (`sms_id`) REFERENCES `varaa_mt_sms` (`id`),
  CONSTRAINT `mt_historys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_mt_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_mt_settings`;

CREATE TABLE `varaa_mt_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned DEFAULT NULL,
  `sms_id` int(10) unsigned DEFAULT NULL,
  `module_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `counts_prev_booking` int(10) unsigned DEFAULT NULL,
  `days_prev_booking` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mt_settings_user_id_foreign` (`user_id`),
  KEY `mt_settings_campaign_id_foreign` (`campaign_id`),
  KEY `mt_settings_sms_id_foreign` (`sms_id`),
  CONSTRAINT `mt_settings_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `varaa_mt_campaign` (`id`),
  CONSTRAINT `mt_settings_sms_id_foreign` FOREIGN KEY (`sms_id`) REFERENCES `varaa_mt_sms` (`id`),
  CONSTRAINT `mt_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_mt_sms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_mt_sms`;

CREATE TABLE `varaa_mt_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mt_sms_user_id_foreign` (`user_id`),
  CONSTRAINT `mt_sms_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_mt_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_mt_templates`;

CREATE TABLE `varaa_mt_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mt_templates_user_id_foreign` (`user_id`),
  CONSTRAINT `mt_templates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `varaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_password_reminders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_password_reminders`;

CREATE TABLE `varaa_password_reminders` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_password_reminders` WRITE;
/*!40000 ALTER TABLE `varaa_password_reminders` DISABLE KEYS */;

INSERT INTO `varaa_password_reminders` (`email`, `token`, `created_at`)
VALUES
	('tung.nguyen@metropolia.fi','1ea5182e898e31af1e38d620af348a6b','2014-08-22 11:06:21'),
	('myynti@varaa.com','4121e2695ce550ed838964ba76d5273d','2014-08-24 08:29:41'),
	('myynti@varaa.com','b2da803b4e65d3a8a13e0679f95c4508','2014-08-24 08:30:40');

/*!40000 ALTER TABLE `varaa_password_reminders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_permission_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_permission_role`;

CREATE TABLE `varaa_permission_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `varaa_roles` (`id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `varaa_permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_permission_role` WRITE;
/*!40000 ALTER TABLE `varaa_permission_role` DISABLE KEYS */;

INSERT INTO `varaa_permission_role` (`id`, `permission_id`, `role_id`)
VALUES
	(1,1,2);

/*!40000 ALTER TABLE `varaa_permission_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_permissions`;

CREATE TABLE `varaa_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_permissions` WRITE;
/*!40000 ALTER TABLE `varaa_permissions` DISABLE KEYS */;

INSERT INTO `varaa_permissions` (`id`, `name`, `display_name`, `created_at`, `updated_at`)
VALUES
	(1,'super_user','Super User','2014-08-18 21:21:09','2014-08-18 21:21:09');

/*!40000 ALTER TABLE `varaa_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_roles`;

CREATE TABLE `varaa_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_roles` WRITE;
/*!40000 ALTER TABLE `varaa_roles` DISABLE KEYS */;

INSERT INTO `varaa_roles` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'User','2014-08-18 21:21:09','2014-08-18 21:21:09'),
	(2,'Admin','2014-08-18 21:21:09','2014-08-18 21:21:09');

/*!40000 ALTER TABLE `varaa_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table varaa_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_transactions`;

CREATE TABLE `varaa_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int(10) unsigned NOT NULL,
  `amount` double(5,2) NOT NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paygate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table varaa_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `varaa_users`;

CREATE TABLE `varaa_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `old_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `business_size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `business_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lat` double(10,6) NOT NULL,
  `lng` double(10,6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `varaa_users` WRITE;
/*!40000 ALTER TABLE `varaa_users` DISABLE KEYS */;

INSERT INTO `varaa_users` (`id`, `username`, `email`, `password`, `old_password`, `confirmation_code`, `remember_token`, `confirmed`, `first_name`, `last_name`, `address`, `city`, `postcode`, `country`, `phone`, `created_at`, `updated_at`, `description`, `business_size`, `deleted_at`, `business_name`, `lat`, `lng`)
VALUES
	(70,'varaa_test','marika.westerholm-amin@hiusakatemia.fi','$2y$10$T4EES58sg4A5Q4mzianIROtByvgdEuamaqukRIPVBp1ioQsW446/2','2d33d8fb6161b8244bf9afe2ada3830e','','9uqanyi1KPawA5XlRYcS0ub38fGYi8S7JEowzXSitayLhuWKXk83rxP1cFKZ',1,'Marika Westerholm','','Mannerheimintie 6 A 6krs','Helsinki','00100','Finland','045 262 5977','2014-07-03 02:18:25','2014-10-06 13:15:41','','0',NULL,'HiusAkatemia',60.167161,24.942084);

/*!40000 ALTER TABLE `varaa_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
