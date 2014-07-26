-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2014 at 06:05 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dev_cashier_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `billers`
--

CREATE TABLE IF NOT EXISTS `billers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `company` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(55) NOT NULL,
  `state` varchar(55) NOT NULL,
  `postal_code` varchar(8) NOT NULL,
  `country` varchar(55) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `invoice_footer` varchar(1000) NOT NULL,
  `cf1` varchar(100) DEFAULT NULL,
  `cf2` varchar(100) DEFAULT NULL,
  `cf3` varchar(100) DEFAULT NULL,
  `cf4` varchar(100) DEFAULT NULL,
  `cf5` varchar(100) DEFAULT NULL,
  `cf6` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `billers`
--

INSERT INTO `billers` (`id`, `name`, `company`, `address`, `city`, `state`, `postal_code`, `country`, `phone`, `email`, `logo`, `invoice_footer`, `cf1`, `cf2`, `cf3`, `cf4`, `cf5`, `cf6`) VALUES
(1, 'Mian Saleem', 'Tecdiary IT Solutions', 'Address', 'City', 'Sate', '0000', 'Malaysia', '012345678', 'saleem@tecdairy.com', 'logo.png', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `date` date NOT NULL,
  `data` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(55) NOT NULL,
  `name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `code`, `name`) VALUES
(1, 'C1', 'Category 1');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment`) VALUES
('&lt;h4&gt;Thank you for Purchasing Stock Manager Advance 2.3 with POS Module &lt;/h4&gt;\r\n&lt;p&gt;\r\n              This is latest the latest release of Stock Manager Advance.\r\n&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `company` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(55) NOT NULL,
  `state` varchar(55) NOT NULL,
  `postal_code` varchar(8) NOT NULL,
  `country` varchar(55) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `giftcard_code` int(11) NOT NULL,
  `giftcard` int(11) NOT NULL,
  `cf1` varchar(100) DEFAULT NULL,
  `cf2` varchar(100) DEFAULT NULL,
  `cf3` varchar(100) DEFAULT NULL,
  `cf4` varchar(100) DEFAULT NULL,
  `cf5` varchar(100) DEFAULT NULL,
  `cf6` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `company`, `address`, `city`, `state`, `postal_code`, `country`, `phone`, `email`, `giftcard_code`, `giftcard`, `cf1`, `cf2`, `cf3`, `cf4`, `cf5`, `cf6`) VALUES
(1, 'Test Customer', 'Customer Company Name', 'Customer Address', 'Petaling Jaya', 'Selangor', '46000', 'Malaysia', '0123456789', 'customer@tecdiary.com', 1396966267, 418, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `damage_products`
--

CREATE TABLE IF NOT EXISTS `damage_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `date_format`
--

CREATE TABLE IF NOT EXISTS `date_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `js` varchar(20) NOT NULL,
  `php` varchar(20) NOT NULL,
  `sql` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `date_format`
--

INSERT INTO `date_format` (`id`, `js`, `php`, `sql`) VALUES
(1, 'mm-dd-yyyy', 'm-d-Y', '%m-%d-%Y'),
(2, 'mm/dd/yyyy', 'm/d/Y', '%m/%d/%Y'),
(3, 'mm.dd.yyyy', 'm.d.Y', '%m.%d.%Y'),
(4, 'dd-mm-yyyy', 'd-m-Y', '%d-%m-%Y'),
(5, 'dd/mm/yyyy', 'd/m/Y', '%d/%m/%Y'),
(6, 'dd.mm.yyyy', 'd.m.Y', '%d.%m.%Y');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` varchar(10) NOT NULL,
  `reference_no` varchar(55) NOT NULL,
  `customer` varchar(55) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `name`, `discount`, `type`) VALUES
(1, 'No Discount', 0.00, '2'),
(2, '2.5 Percent', 2.50, '1'),
(3, '5 Percent', 5.00, '1'),
(4, '10 Percent', 10.00, '1');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'owner', 'Owner'),
(2, 'admin', 'Administrator'),
(3, 'purchaser', 'Purchasing Staff'),
(4, 'salesman', 'Sales Staff'),
(5, 'viewer', 'View Only User');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_types`
--

CREATE TABLE IF NOT EXISTS `invoice_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pos_settings`
--

CREATE TABLE IF NOT EXISTS `pos_settings` (
  `pos_id` int(1) NOT NULL,
  `cat_limit` int(11) NOT NULL,
  `pro_limit` int(11) NOT NULL,
  `default_category` int(11) NOT NULL,
  `default_customer` int(11) NOT NULL,
  `default_biller` int(11) NOT NULL,
  `display_time` varchar(3) NOT NULL DEFAULT 'yes',
  `cf_title1` varchar(255) DEFAULT NULL,
  `cf_title2` varchar(255) DEFAULT NULL,
  `cf_value1` varchar(255) DEFAULT NULL,
  `cf_value2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pos_settings`
--

INSERT INTO `pos_settings` (`pos_id`, `cat_limit`, `pro_limit`, `default_category`, `default_customer`, `default_biller`, `display_time`, `cf_title1`, `cf_title2`, `cf_value1`, `cf_value2`) VALUES
(1, 22, 20, 1, 1, 1, 'yes', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` char(255) NOT NULL,
  `product_type` varchar(50) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `size` varchar(55) NOT NULL,
  `cost` decimal(25,2) DEFAULT NULL,
  `price` decimal(25,2) NOT NULL,
  `alert_quantity` int(11) NOT NULL DEFAULT '20',
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `cf1` varchar(255) DEFAULT NULL,
  `cf2` varchar(255) DEFAULT NULL,
  `cf3` varchar(255) DEFAULT NULL,
  `cf4` varchar(255) DEFAULT NULL,
  `cf5` varchar(255) DEFAULT NULL,
  `cf6` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `tax_rate` int(11) DEFAULT NULL,
  `track_quantity` tinyint(4) DEFAULT '1',
  `details` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `category_id` (`category_id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `category_id_2` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE IF NOT EXISTS `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(55) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(55) NOT NULL,
  `date` date NOT NULL,
  `note` varchar(1000) NOT NULL,
  `total` decimal(25,2) NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `total_tax` decimal(25,2) DEFAULT '0.00',
  `inv_total` decimal(25,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(25,2) NOT NULL,
  `tax_amount` decimal(25,2) DEFAULT NULL,
  `gross_total` decimal(25,2) NOT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `val_tax` decimal(25,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(55) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `biller_id` int(11) NOT NULL,
  `biller_name` varchar(55) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(55) NOT NULL,
  `date` date NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `internal_note` varchar(1000) DEFAULT NULL,
  `inv_total` decimal(25,2) NOT NULL,
  `total_tax` decimal(25,2) DEFAULT NULL,
  `total` decimal(25,2) NOT NULL,
  `invoice_type` int(11) DEFAULT NULL,
  `in_type` varchar(55) DEFAULT NULL,
  `total_tax2` decimal(25,2) DEFAULT NULL,
  `tax_rate2_id` int(11) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `inv_discount` decimal(25,2) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `shipping` decimal(25,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quote_items`
--

CREATE TABLE IF NOT EXISTS `quote_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_unit` varchar(50) NOT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(25,2) NOT NULL,
  `gross_total` decimal(25,2) NOT NULL,
  `val_tax` decimal(25,2) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `discount_val` decimal(25,2) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quote_id` (`quote_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(55) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `biller_id` int(11) NOT NULL,
  `biller_name` varchar(55) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(55) NOT NULL,
  `date` date NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `internal_note` varchar(1000) DEFAULT NULL,
  `inv_total` decimal(25,2) NOT NULL,
  `total_tax` decimal(25,2) DEFAULT NULL,
  `total` decimal(25,2) NOT NULL,
  `invoice_type` int(11) DEFAULT NULL,
  `in_type` varchar(55) DEFAULT NULL,
  `total_tax2` decimal(25,2) DEFAULT NULL,
  `tax_rate2_id` int(11) DEFAULT NULL,
  `inv_discount` decimal(25,2) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `paid_by` varchar(55) DEFAULT 'cash',
  `count` int(11) DEFAULT NULL,
  `shipping` decimal(25,2) DEFAULT '0.00',
  `pos` tinyint(4) NOT NULL DEFAULT '0',
  `paid` decimal(25,2) DEFAULT NULL,
  `paid_giftcard` decimal(25,2) DEFAULT NULL,
  `cc_no` varchar(20) DEFAULT NULL,
  `cc_holder` varchar(100) DEFAULT NULL,
  `cheque_no` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE IF NOT EXISTS `sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_unit` varchar(50) NOT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(25,2) NOT NULL,
  `gross_total` decimal(25,2) NOT NULL,
  `val_tax` decimal(25,2) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `discount_val` decimal(25,2) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(1) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `logo2` varchar(255) NOT NULL,
  `site_name` varchar(55) NOT NULL,
  `language` varchar(20) NOT NULL,
  `default_warehouse` int(2) NOT NULL,
  `currency_prefix` varchar(3) NOT NULL,
  `default_invoice_type` int(2) NOT NULL,
  `default_tax_rate` int(2) NOT NULL,
  `rows_per_page` int(2) NOT NULL,
  `no_of_rows` int(2) NOT NULL,
  `total_rows` int(2) NOT NULL,
  `version` varchar(5) NOT NULL DEFAULT '2.3',
  `default_tax_rate2` int(11) NOT NULL DEFAULT '0',
  `dateformat` int(11) NOT NULL,
  `sales_prefix` varchar(20) NOT NULL,
  `quote_prefix` varchar(55) NOT NULL,
  `purchase_prefix` varchar(55) NOT NULL,
  `transfer_prefix` varchar(55) NOT NULL,
  `barcode_symbology` varchar(20) NOT NULL,
  `theme` varchar(20) NOT NULL,
  `product_serial` tinyint(4) NOT NULL,
  `default_discount` int(11) NOT NULL,
  `discount_option` tinyint(4) NOT NULL,
  `discount_method` tinyint(4) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  `restrict_sale` tinyint(4) NOT NULL DEFAULT '0',
  `restrict_user` tinyint(4) NOT NULL DEFAULT '0',
  `restrict_calendar` tinyint(4) NOT NULL DEFAULT '0',
  `bstatesave` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `logo`, `logo2`, `site_name`, `language`, `default_warehouse`, `currency_prefix`, `default_invoice_type`, `default_tax_rate`, `rows_per_page`, `no_of_rows`, `total_rows`, `version`, `default_tax_rate2`, `dateformat`, `sales_prefix`, `quote_prefix`, `purchase_prefix`, `transfer_prefix`, `barcode_symbology`, `theme`, `product_serial`, `default_discount`, `discount_option`, `discount_method`, `tax1`, `tax2`, `restrict_sale`, `restrict_user`, `restrict_calendar`, `bstatesave`) VALUES
(1, 'header_logo.png', 'login_logo.png', 'Stock Manager Advance', 'finland', 1, 'USD', 2, 2, 10, 9, 30, '2.3', 1, 5, 'SL', 'QU', 'PO', 'TR', 'code128', 'blue', 1, 1, 2, 2, 1, 1, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE IF NOT EXISTS `subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `code` varchar(55) NOT NULL,
  `name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `code`, `name`) VALUES
(1, 1, 'Sub-c1', 'sub category 1');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `company` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(55) NOT NULL,
  `state` varchar(55) NOT NULL,
  `postal_code` varchar(8) NOT NULL,
  `country` varchar(55) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cf1` varchar(100) DEFAULT NULL,
  `cf2` varchar(100) DEFAULT NULL,
  `cf3` varchar(100) DEFAULT NULL,
  `cf4` varchar(100) DEFAULT NULL,
  `cf5` varchar(100) DEFAULT NULL,
  `cf6` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `company`, `address`, `city`, `state`, `postal_code`, `country`, `phone`, `email`, `cf1`, `cf2`, `cf3`, `cf4`, `cf5`, `cf6`) VALUES
(1, 'Test Supplier', 'Supplier Company Name', 'Supplier Address', 'Petaling Jaya', 'Selangor', '46050', 'Malaysia', '0123456789', 'supplier@tecdiary.com', '-', '-', '-', '-', '-', '-');

-- --------------------------------------------------------

--
-- Table structure for table `suspended_bills`
--

CREATE TABLE IF NOT EXISTS `suspended_bills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `tax1` float(25,2) DEFAULT NULL,
  `tax2` float(25,2) DEFAULT NULL,
  `discount` decimal(25,2) DEFAULT NULL,
  `inv_total` decimal(25,2) NOT NULL,
  `total` float(25,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `suspended_bills`
--

INSERT INTO `suspended_bills` (`id`, `date`, `customer_id`, `count`, `tax1`, `tax2`, `discount`, `inv_total`, `total`) VALUES
(1, '2014-03-10', 1, 1, 3.60, 0.00, 0.00, 15.00, 18.60);

-- --------------------------------------------------------

--
-- Table structure for table `suspended_items`
--

CREATE TABLE IF NOT EXISTS `suspended_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suspend_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_unit` varchar(50) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(25,2) NOT NULL,
  `gross_total` decimal(25,2) NOT NULL,
  `val_tax` decimal(25,2) DEFAULT NULL,
  `discount` varchar(55) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `discount_val` decimal(25,2) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `suspended_items`
--

INSERT INTO `suspended_items` (`id`, `suspend_id`, `product_id`, `product_code`, `product_name`, `product_unit`, `tax_rate_id`, `tax`, `quantity`, `unit_price`, `gross_total`, `val_tax`, `discount`, `discount_id`, `discount_val`, `serial_no`) VALUES
(1, 1, 8, 'S3', 'Service 3', 'kg', 2, '24.00%', 1, 15.00, 15.00, 3.60, '0.00', 1, 0.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE IF NOT EXISTS `tax_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `rate` decimal(8,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `name`, `rate`, `type`) VALUES
(1, 'No Tax', 0.00, '2'),
(2, 'VAT', 24.00, '1'),
(3, 'GST', 6.00, '1');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE IF NOT EXISTS `transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_no` varchar(55) NOT NULL,
  `date` date NOT NULL,
  `from_warehouse_id` int(11) NOT NULL,
  `from_warehouse_code` varchar(55) NOT NULL,
  `from_warehouse_name` varchar(55) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `to_warehouse_code` varchar(55) NOT NULL,
  `to_warehouse_name` varchar(55) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `total_tax` decimal(25,2) DEFAULT NULL,
  `total` decimal(25,2) DEFAULT NULL,
  `tr_total` decimal(25,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_items`
--

CREATE TABLE IF NOT EXISTS `transfer_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(55) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_unit` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `tax` varchar(55) DEFAULT NULL,
  `tax_val` decimal(25,2) DEFAULT NULL,
  `unit_price` decimal(25,2) DEFAULT NULL,
  `gross_total` decimal(25,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transfer_id` (`transfer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE IF NOT EXISTS `warehouses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `code`, `name`, `address`, `city`) VALUES
(1, 'WHI', 'Warehouse 1', 'Address', 'City');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses_products`
--

CREATE TABLE IF NOT EXISTS `warehouses_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
