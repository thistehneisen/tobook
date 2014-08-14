DROP TABLE IF EXISTS `restaurant_booking_bookings`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_bookings` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uuid` int(10) unsigned default NULL,
  `dt` datetime default NULL,
  `dt_to` datetime default NULL,
  `people` smallint(5) unsigned default NULL,
  `code` varchar(255) default NULL,
  `total` decimal(9,2) unsigned default NULL,
  `payment_method` enum('paypal','authorize','creditcard','cash') default NULL,
  `is_paid` enum('total','none') default 'none',
  `status` enum('complete','confirmed','cancelled','pending','enquiry') default 'pending',
  `txn_id` varchar(255) default NULL,
  `processed_on` datetime default NULL,
  `created` datetime default NULL,
  `c_title` varchar(255) default NULL,
  `c_fname` varchar(255) default NULL,
  `c_lname` varchar(255) default NULL,
  `c_phone` varchar(255) default NULL,
  `c_email` varchar(255) default NULL,
  `c_company` varchar(255) default NULL,
  `c_notes` text,
  `c_address` varchar(255) default NULL,
  `c_city` varchar(255) default NULL,
  `c_state` varchar(255) default NULL,
  `c_zip` varchar(255) default NULL,
  `c_country` int(10) unsigned default NULL,
  `cc_type` varchar(255) default NULL,
  `cc_num` varchar(255) default NULL,
  `cc_exp` varchar(255) default NULL,
  `cc_code` varchar(255) default NULL,
  `reminder_email` tinyint(1) unsigned default '0',
  `reminder_sms` tinyint(1) unsigned default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `restaurant_booking_bookings_tables`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_bookings_tables` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `booking_id` int(10) unsigned default NULL,
  `table_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `booking_id` (`booking_id`,`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `restaurant_booking_template`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `subject` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `message` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


DROP TABLE IF EXISTS `restaurant_booking_service`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `s_name` varchar(254) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `s_length` smallint(6) DEFAULT NULL,
  `s_price` int(11) NOT NULL,
  `s_seats` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

INSERT INTO `restaurant_booking_service` (`id`, `s_name`, `start_time`, `end_time`, `s_length`, `s_price`, `s_seats`) VALUES
(6, 'Breakfast', '08:00:00', '10:59:00', 1, 20, 30),
(7, 'Lunch', '11:00:00', '13:59:00', 1, 30, 30),
(8, 'Afterlunch', '14:00:00', '16:59:00', 2, 30, 30),
(9, 'Dinner', '17:00:00', '23:59:00', 3, 40, 30);


DROP TABLE IF EXISTS `restaurant_booking_countries`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_countries` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `country_title` varchar(255) default NULL,
  `status` enum('T','F') NOT NULL default 'T',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `restaurant_booking_countries` (`id`, `country_title`, `status`) VALUES
(1, 'Afghanistan', 'T'),
(2, 'Albania', 'T'),
(3, 'Algeria', 'T'),
(4, 'American Samoa', 'T'),
(5, 'Andorra', 'T'),
(6, 'Angola', 'T'),
(7, 'Anguilla', 'T'),
(8, 'Antarctica', 'T'),
(9, 'Antigua and Barbuda', 'T'),
(10, 'Argentina', 'T'),
(11, 'Armenia', 'T'),
(12, 'Arctic Ocean', 'T'),
(13, 'Aruba', 'T'),
(14, 'Ashmore and Cartier Islands', 'T'),
(15, 'Atlantic Ocean', 'T'),
(16, 'Australia', 'T'),
(17, 'Austria', 'T'),
(18, 'Azerbaijan', 'T'),
(19, 'Bahamas', 'T'),
(20, 'Bahrain', 'T'),
(21, 'Baker Island', 'T'),
(22, 'Bangladesh', 'T'),
(23, 'Barbados', 'T'),
(24, 'Bassas da India', 'T'),
(25, 'Belarus', 'T'),
(26, 'Belgium', 'T'),
(27, 'Belize', 'T'),
(28, 'Benin', 'T'),
(29, 'Bermuda', 'T'),
(30, 'Bhutan', 'T'),
(31, 'Bolivia', 'T'),
(32, 'Borneo', 'T'),
(33, 'Bosnia and Herzegovina', 'T'),
(34, 'Botswana', 'T'),
(35, 'Bouvet Island', 'T'),
(36, 'Brazil', 'T'),
(37, 'British Virgin Islands', 'T'),
(38, 'Brunei', 'T'),
(39, 'Bulgaria', 'T'),
(40, 'Burkina Faso', 'T'),
(41, 'Burundi', 'T'),
(42, 'Cambodia', 'T'),
(43, 'Cameroon', 'T'),
(44, 'Canada', 'T'),
(45, 'Cape Verde', 'T'),
(46, 'Cayman Islands', 'T'),
(47, 'Central African Republic', 'T'),
(48, 'Chad', 'T'),
(49, 'Chile', 'T'),
(50, 'China', 'T'),
(51, 'Christmas Island', 'T'),
(52, 'Clipperton Island', 'T'),
(53, 'Cocos Islands', 'T'),
(54, 'Colombia', 'T'),
(55, 'Comoros', 'T'),
(56, 'Cook Islands', 'T'),
(57, 'Coral Sea Islands', 'T'),
(58, 'Costa Rica', 'T'),
(59, 'Cote d''Ivoire', 'T'),
(60, 'Croatia', 'T'),
(61, 'Cuba', 'T'),
(62, 'Cyprus', 'T'),
(63, 'Czech Republic', 'T'),
(64, 'Denmark', 'T'),
(65, 'Democratic Republic of the Congo', 'T'),
(66, 'Djibouti', 'T'),
(67, 'Dominica', 'T'),
(68, 'Dominican Republic', 'T'),
(69, 'East Timor', 'T'),
(70, 'Ecuador', 'T'),
(71, 'Egypt', 'T'),
(72, 'El Salvador', 'T'),
(73, 'Equatorial Guinea', 'T'),
(74, 'Eritrea', 'T'),
(75, 'Estonia', 'T'),
(76, 'Ethiopia', 'T'),
(77, 'Europa Island', 'T'),
(78, 'Falkland Islands (Islas Malvinas)', 'T'),
(79, 'Faroe Islands', 'T'),
(80, 'Fiji', 'T'),
(81, 'Finland', 'T'),
(82, 'France', 'T'),
(83, 'French Guiana', 'T'),
(84, 'French Polynesia', 'T'),
(85, 'French Southern and Antarctic Lands', 'T'),
(86, 'Gabon', 'T'),
(87, 'Gambia', 'T'),
(88, 'Gaza Strip', 'T'),
(89, 'Georgia', 'T'),
(90, 'Germany', 'T'),
(91, 'Ghana', 'T'),
(92, 'Gibraltar', 'T'),
(93, 'Glorioso Islands', 'T'),
(94, 'Greece', 'T'),
(95, 'Greenland', 'T'),
(96, 'Grenada', 'T'),
(97, 'Guadeloupe', 'T'),
(98, 'Guam', 'T'),
(99, 'Guatemala', 'T'),
(100, 'Guernsey', 'T'),
(101, 'Guinea', 'T'),
(102, 'Guinea-Bissau', 'T'),
(103, 'Guyana', 'T'),
(104, 'Haiti', 'T'),
(105, 'Heard Island and McDonald Islands', 'T'),
(106, 'Honduras', 'T'),
(107, 'Hong Kong', 'T'),
(108, 'Howland Island', 'T'),
(109, 'Hungary', 'T'),
(110, 'Iceland', 'T'),
(111, 'India', 'T'),
(112, 'Indian Ocean', 'T'),
(113, 'Indonesia', 'T'),
(114, 'Iran', 'T'),
(115, 'Iraq', 'T'),
(116, 'Ireland', 'T'),
(117, 'Isle of Man', 'T'),
(118, 'Israel', 'T'),
(119, 'Italy', 'T'),
(120, 'Jamaica', 'T'),
(121, 'Jan Mayen', 'T'),
(122, 'Japan', 'T'),
(123, 'Jarvis Island', 'T'),
(124, 'Jersey', 'T'),
(125, 'Johnston Atoll', 'T'),
(126, 'Jordan', 'T'),
(127, 'Juan de Nova Island', 'T'),
(128, 'Kazakhstan', 'T'),
(129, 'Kenya', 'T'),
(130, 'Kingman Reef', 'T'),
(131, 'Kiribati', 'T'),
(132, 'Kerguelen Archipelago', 'T'),
(133, 'Kosovo', 'T'),
(134, 'Kuwait', 'T'),
(135, 'Kyrgyzstan', 'T'),
(136, 'Laos', 'T'),
(137, 'Latvia', 'T'),
(138, 'Lebanon', 'T'),
(139, 'Lesotho', 'T'),
(140, 'Liberia', 'T'),
(141, 'Libya', 'T'),
(142, 'Liechtenstein', 'T'),
(143, 'Lithuania', 'T'),
(144, 'Luxembourg', 'T'),
(145, 'Macau', 'T'),
(146, 'Macedonia', 'T'),
(147, 'Madagascar', 'T'),
(148, 'Malawi', 'T'),
(149, 'Malaysia', 'T'),
(150, 'Maldives', 'T'),
(151, 'Mali', 'T'),
(152, 'Malta', 'T'),
(153, 'Marshall Islands', 'T'),
(154, 'Martinique', 'T'),
(155, 'Mauritania', 'T'),
(156, 'Mauritius', 'T'),
(157, 'Mayotte', 'T'),
(158, 'Mediterranean Sea', 'T'),
(159, 'Mexico', 'T'),
(160, 'Micronesia', 'T'),
(161, 'Midway Islands', 'T'),
(162, 'Moldova', 'T'),
(163, 'Monaco', 'T'),
(164, 'Mongolia', 'T'),
(165, 'Montenegro', 'T'),
(166, 'Montserrat', 'T'),
(167, 'Morocco', 'T'),
(168, 'Mozambique', 'T'),
(169, 'Myanmar', 'T'),
(170, 'Namibia', 'T'),
(171, 'Nauru', 'T'),
(172, 'Navassa Island', 'T'),
(173, 'Nepal', 'T'),
(174, 'Netherlands', 'T'),
(175, 'Netherlands Antilles', 'T'),
(176, 'New Caledonia', 'T'),
(177, 'New Zealand', 'T'),
(178, 'Nicaragua', 'T'),
(179, 'Niger', 'T'),
(180, 'Nigeria', 'T'),
(181, 'Niue', 'T'),
(182, 'Norfolk Island', 'T'),
(183, 'North Korea', 'T'),
(184, 'North Sea', 'T'),
(185, 'Northern Mariana Islands', 'T'),
(186, 'Norway', 'T'),
(187, 'Oman', 'T'),
(188, 'Pacific Ocean', 'T'),
(189, 'Pakistan', 'T'),
(190, 'Palau', 'T'),
(191, 'Palmyra Atoll', 'T'),
(192, 'Panama', 'T'),
(193, 'Papua New Guinea', 'T'),
(194, 'Paracel Islands', 'T'),
(195, 'Paraguay', 'T'),
(196, 'Peru', 'T'),
(197, 'Philippines', 'T'),
(198, 'Pitcairn Islands', 'T'),
(199, 'Poland', 'T'),
(200, 'Portugal', 'T'),
(201, 'Puerto Rico', 'T'),
(202, 'Qatar', 'T'),
(203, 'Reunion', 'T'),
(204, 'Republic of the Congo', 'T'),
(205, 'Romania', 'T'),
(206, 'Ross Sea', 'T'),
(207, 'Russia', 'T'),
(208, 'Rwanda', 'T'),
(209, 'Saint Helena', 'T'),
(210, 'Saint Kitts and Nevis', 'T'),
(211, 'Saint Lucia', 'T'),
(212, 'Saint Pierre and Miquelon', 'T'),
(213, 'Saint Vincent and the Grenadines', 'T'),
(214, 'Samoa', 'T'),
(215, 'San Marino', 'T'),
(216, 'Sao Tome and Principe', 'T'),
(217, 'Saudi Arabia', 'T'),
(218, 'Senegal', 'T'),
(219, 'Serbia', 'T'),
(220, 'Seychelles', 'T'),
(221, 'Sierra Leone', 'T'),
(222, 'Singapore', 'T'),
(223, 'Slovakia', 'T'),
(224, 'Slovenia', 'T'),
(225, 'Solomon Islands', 'T'),
(226, 'Somalia', 'T'),
(227, 'South Africa', 'T'),
(228, 'South Georgia and the South Sandwich Islands', 'T'),
(229, 'South Korea', 'T'),
(230, 'Southern Ocean', 'T'),
(231, 'Spain', 'T'),
(232, 'Spratly Islands', 'T'),
(233, 'Sri Lanka', 'T'),
(234, 'Sudan', 'T'),
(235, 'Suriname', 'T'),
(236, 'Svalbard', 'T'),
(237, 'Swaziland', 'T'),
(238, 'Sweden', 'T'),
(239, 'Switzerland', 'T'),
(240, 'Syria', 'T'),
(241, 'Taiwan', 'T'),
(242, 'Tajikistan', 'T'),
(243, 'Tanzania', 'T'),
(244, 'Tasman Sea', 'T'),
(245, 'Thailand', 'T'),
(246, 'Togo', 'T'),
(247, 'Tokelau', 'T'),
(248, 'Tonga', 'T'),
(249, 'Trinidad and Tobago', 'T'),
(250, 'Tromelin Island', 'T'),
(251, 'Tunisia', 'T'),
(252, 'Turkey', 'T'),
(253, 'Turkmenistan', 'T'),
(254, 'Turks and Caicos Islands', 'T'),
(255, 'Tuvalu', 'T'),
(256, 'Uganda', 'T'),
(257, 'Ukraine', 'T'),
(258, 'United Arab Emirates', 'T'),
(259, 'United Kingdom', 'T'),
(260, 'USA', 'T'),
(261, 'Uruguay', 'T'),
(262, 'Uzbekistan', 'T'),
(263, 'Vanuatu', 'T'),
(264, 'Venezuela', 'T'),
(265, 'Viet Nam', 'T'),
(266, 'Virgin Islands', 'T'),
(267, 'Wake Island', 'T'),
(268, 'Wallis and Futuna', 'T'),
(269, 'West Bank', 'T'),
(270, 'Western Sahara', 'T'),
(271, 'Yemen', 'T'),
(272, 'Zambia', 'T'),
(273, 'Zimbabwe', 'T');

DROP TABLE IF EXISTS `restaurant_booking_dates`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_dates` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `start_time` time default NULL,
  `end_time` time default NULL,
  `is_dayoff` enum('T','F') default 'F',
  `message` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `date` (`date`),
  KEY `is_dayoff` (`is_dayoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `restaurant_booking_options`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_options` (
  `key` varchar(255) NOT NULL default '',
  `tab_id` tinyint(3) unsigned default NULL,
  `group` enum('borders','colors','fonts','sizes') default NULL,
  `value` text,
  `description` text,
  `label` text,
  `type` enum('string','text','int','float','enum','color','bool') default 'string',
  `order` int(10) unsigned default NULL,
  `style` varchar(255) default NULL,
  `is_visible` tinyint(1) unsigned default '1',
  PRIMARY KEY  (`key`),
  KEY `tab_id` (`tab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `restaurant_booking_options` (`key`, `tab_id`, `group`, `value`, `description`, `label`, `type`, `order`, `style`, `is_visible`) VALUES
('bf_include_address', 4, NULL, '1|2|3::1', 'Address&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 7, NULL, 1),
('bf_include_captcha', 4, NULL, '1|3::3', 'Captcha&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes (Required)', 'enum', 16, NULL, 1),
('bf_include_city', 4, NULL, '1|2|3::1', 'City&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 12, NULL, 1),
('bf_include_company', 4, NULL, '1|2|3::1', 'Company&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 6, NULL, 1),
('bf_include_country', 4, NULL, '1|2|3::1', 'Country&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 15, NULL, 1),
('bf_include_email', 4, NULL, '1|2|3::3', 'E-Mail address&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 5, NULL, 1),
('bf_include_fname', 4, NULL, '1|2|3::3', 'First Name&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 2, NULL, 1),
('bf_include_lname', 4, NULL, '1|2|3::3', 'Last Name&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 3, NULL, 1),
('bf_include_notes', 4, NULL, '1|2|3::1', 'Notes&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 8, NULL, 1),
('bf_include_phone', 4, NULL, '1|2|3::3', 'Phone&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 4, NULL, 1),
('bf_include_promo', 4, NULL, '1|2|3::2', 'Voucher&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 11, NULL, 1),
('bf_include_state', 4, NULL, '1|2|3::1', 'State&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 13, NULL, 1),
('bf_include_title', 4, NULL, '1|2|3::3', 'Title&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 1, NULL, 1),
('bf_include_zip', 4, NULL, '1|2|3::1', 'Zip&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;Select &quot;Yes&quot; if you want to include the field in the booking form, otherwise select &quot;No&quot;&lt;/span&gt;', 'No|Yes|Yes (Required)', 'enum', 14, NULL, 1),
('booking_earlier', 2, NULL, '2', 'Book X hours earlier&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;how many hours earlier, you can book a table&lt;/span&gt;', NULL, 'int', 1, NULL, 1),
('booking_front_end', 2, NULL, '1|2::1', 'Booking front end', 'Time|Category', 'enum', 1, NULL, 1),
('booking_group_booking', 2, NULL, '10', 'Group booking', NULL, 'int', 1, NULL, 1),
('booking_length', 2, NULL, '180', 'Booking length&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;set the default booking length, values in minutes&lt;/span&gt;', NULL, 'int', 1, NULL, 1),
('booking_price', 2, NULL, '50', 'Booking price&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;set default booking price&lt;/span&gt;', NULL, 'float', 1, NULL, 1),
('booking_status', 2, NULL, 'confirmed|pending|cancelled::pending', 'Default booking status&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;set the default status for each booking after it is made&lt;/span&gt;', NULL, 'enum', 5, NULL, 1),
('cm_include_address', 7, NULL, '1|2::1', 'Address', 'No|Yes', 'enum', 7, NULL, 1),
('cm_include_city', 7, NULL, '1|2::1', 'City', 'No|Yes', 'enum', 12, NULL, 1),
('cm_include_company', 7, NULL, '1|2::1', 'Company', 'No|Yes', 'enum', 6, NULL, 1),
('cm_include_count', 7, NULL, '1|2::2', 'Count', 'No|Yes', 'enum', 17, NULL, 1),
('cm_include_country', 7, NULL, '1|2::1', 'Country', 'No|Yes', 'enum', 15, NULL, 1),
('cm_include_email', 7, NULL, '1|2::2', 'E-Mail address', 'No|Yes', 'enum', 5, NULL, 1),
('cm_include_fname', 7, NULL, '1|2::2', 'First Name', 'No|Yes', 'enum', 2, NULL, 1),
('cm_include_lname', 7, NULL, '1|2::2', 'Last Name', 'No|Yes', 'enum', 3, NULL, 1),
('cm_include_phone', 7, NULL, '1|2::2', 'Phone', 'No|Yes', 'enum', 4, NULL, 1),
('cm_include_state', 7, NULL, '1|2::1', 'State', 'No|Yes', 'enum', 13, NULL, 1),
('cm_include_title', 7, NULL, '1|2::1', 'Title', 'No|Yes', 'enum', 1, NULL, 1),
('cm_include_zip', 7, NULL, '1|2::1', 'Zip', 'No|Yes', 'enum', 14, NULL, 1),
('currency', 1, NULL, 'USD|GPB|EUR::USD', 'Currency', NULL, 'enum', 1, NULL, 1),
('datetime_format', 1, NULL, 'd.m.Y, H:i|d.m.Y, H:i:s|m.d.Y, H:i|m.d.Y, H:i:s|Y.m.d, H:i|Y.m.d, H:i:s|j.n.Y, H:i|j.n.Y, H:i:s|n.j.Y, H:i|n.j.Y, H:i:s|Y.n.j, H:i|Y.n.j, H:i:s|d/m/Y, H:i|d/m/Y, H:i:s|m/d/Y, H:i|m/d/Y, H:i:s|Y/m/d, H:i|Y/m/d, H:i:s|j/n/Y, H:i|j/n/Y, H:i:s|n/j/Y, H:i|n/j/Y, H:i:s|Y/n/j, H:i|Y/n/j, H:i:s|d-m-Y, H:i|d-m-Y, H:i:s|m-d-Y, H:i|m-d-Y, H:i:s|Y-m-d, H:i|Y-m-d, H:i:s|j-n-Y, H:i|j-n-Y, H:i:s|n-j-Y, H:i|n-j-Y, H:i:s|Y-n-j, H:i|Y-n-j, H:i:s::j/n/Y, H:i', 'Date/Time format', 'd.m.Y, H:i (25.09.2010, 09:51)|d.m.Y, H:i:s (25.09.2010, 09:51:47)|m.d.Y, H:i (09.25.2010, 09:51)|m.d.Y, H:i:s (09.25.2010, 09:51:47)|Y.m.d, H:i (2010.09.25, 09:51)|Y.m.d, H:i:s (2010.09.25, 09:51:47)|j.n.Y, H:i (25.9.2010, 09:51)|j.n.Y, H:i:s (25.9.2010, 09:51:47)|n.j.Y, H:i (9.25.2010, 09:51)|n.j.Y, H:i:s (9.25.2010, 09:51:47)|Y.n.j, H:i (2010.9.25, 09:51)|Y.n.j, H:i:s (2010.9.25, 09:51:47)|d/m/Y, H:i (25/09/2010, 09:51)|d/m/Y, H:i:s (25/09/2010, 09:51:47)|m/d/Y, H:i (09/25/2010, 09:51)|m/d/Y, H:i:s (09/25/2010, 09:51:47)|Y/m/d, H:i (2010/09/25, 09:51)|Y/m/d, H:i:s (2010/09/25, 09:51:47)|j/n/Y, H:i (25/9/2010, 09:51)|j/n/Y, H:i:s (25/9/2010, 09:51:47)|n/j/Y, H:i (9/25/2010, 09:51)|n/j/Y, H:i:s (9/25/2010, 09:51:47)|Y/n/j, H:i (2010/9/25, 09:51)|Y/n/j, H:i:s (2010/9/25, 09:51:47)|d-m-Y, H:i (25-09-2010, 09:51)|d-m-Y, H:i:s (25-09-2010, 09:51:47)|m-d-Y, H:i (09-25-2010, 09:51)|m-d-Y, H:i:s (09-25-2010, 09:51:47)|Y-m-d, H:i (2010-09-25, 09:51)|Y-m-d, H:i:s (2010-09-25, 09:51:47)|j-n-Y, H:i (25-9-2010, 09:51)|j-n-Y, H:i:s (25-9-2010, 09:51:47)|n-j-Y, H:i (9-25-2010, 09:51)|n-j-Y, H:i:s (9-25-2010, 09:51:47)|Y-n-j, H:i (2010-9-25, 09:51)|Y-n-j, H:i:s (2010-9-25, 09:51:47)', 'enum', 3, NULL, 1),
('date_format', 1, NULL, 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::j/n/Y', 'Date format', 'd.m.Y (25.09.2010)|m.d.Y (09.25.2010)|Y.m.d (2010.09.25)|j.n.Y (25.9.2010)|n.j.Y (9.25.2010)|Y.n.j (2010.9.25)|d/m/Y (25/09/2010)|m/d/Y (09/25/2010)|Y/m/d (2010/09/25)|j/n/Y (25/9/2010)|n/j/Y (9/25/2010)|Y/n/j (2010/9/25)|d-m-Y (25-09-2010)|m-d-Y (09-25-2010)|Y-m-d (2010-09-25)|j-n-Y (25-9-2010)|n-j-Y (9-25-2010)|Y-n-j (2010-9-25)', 'enum', 2, NULL, 1),
('db_version', 99, NULL, '1.0.0', 'Database version', NULL, 'string', NULL, NULL, 0),
('email_address', 3, NULL, 'info@domain.com', 'Notification email address', NULL, 'string', 1, NULL, 1),
('email_confirmation', 3, NULL, '1|2|3::2', 'Send confirmation email&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;select if and when confirmation email should be sent to clients after they make a booking&lt;/span&gt;', 'None|After booking form|After payment', 'enum', 2, NULL, 1),
('email_confirmation_message', 3, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nTitle: {Title}\r\nFirst Name: {FirstName}\r\nLast Name: {LastName}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nNotes: {Notes}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nCompany: {Company}\r\n\r\nBooking details:\r\nDate/Time From: {DtFrom}\r\nTable: {Table}\r\nPeople: {People}\r\nBooking ID: {BookingID}\r\nUnique ID: {UniqueID}\r\nTotal: {Total}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Confirmation email&lt;br /&gt;\n&lt;u&gt;Available Tokens:&lt;/u&gt;&lt;br /&gt;\n{Title}&lt;br /&gt;\n{FirstName}&lt;br /&gt;\n{LastName}&lt;br /&gt;\n{Email}&lt;br /&gt;\n{Phone}&lt;br /&gt;\n{Notes}&lt;br /&gt;\n{Country}&lt;br /&gt;\n{City}&lt;br /&gt;\n{State}&lt;br /&gt;\n{Zip}&lt;br /&gt;\n{Address}&lt;br /&gt;\n{Company}&lt;br /&gt;\n{DtFrom}&lt;br /&gt;\n{Table}&lt;br /&gt;\n{People}&lt;br /&gt;\n{BookingID}&lt;br /&gt;\n{UniqueID}&lt;br /&gt;\n{Total}&lt;br /&gt;\n{PaymentMethod}&lt;br /&gt;\n{CCType}&lt;br /&gt;\n{CCNum}&lt;br /&gt;\n{CCExp}&lt;br /&gt;\n{CCSec}&lt;br /&gt;\n{CancelURL}&lt;br /&gt;', NULL, 'text', 4, 'height: 465px', 1),
('email_confirmation_subject', 3, NULL, 'Confirmation message', 'Confirmation email subject', NULL, 'string', 3, NULL, 1),
('email_enquiry', 3, NULL, '1|4::4', 'Send enquiry email&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;select if and when confirmation email should be sent to clients after they make a enquiry for  booking&lt;/span&gt;', 'None|After booking form', 'enum', 8, NULL, 1),
('email_enquiry_message', 3, NULL, 'You''ve just made a enquiry.\r\n\r\nPersonal details:\r\nTitle: {Title}\r\nFirst Name: {FirstName}\r\nLast Name: {LastName}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nNotes: {Notes}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nCompany: {Company}\r\n\r\nEnquiry details:\r\nDate/Time From: {DtFrom}\r\nPeople: {People}\r\nUnique ID: {UniqueID}\r\n\r\nIf you want to cancel your enquiry follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Enquiry email&lt;br /&gt;\r\n&lt;u&gt;Available Tokens:&lt;/u&gt;&lt;br /&gt;\r\n{Title}&lt;br /&gt;\r\n{FirstName}&lt;br /&gt;\r\n{LastName}&lt;br /&gt;\r\n{Email}&lt;br /&gt;\r\n{Phone}&lt;br /&gt;\r\n{Notes}&lt;br /&gt;\r\n{Country}&lt;br /&gt;\r\n{City}&lt;br /&gt;\r\n{State}&lt;br /&gt;\r\n{Zip}&lt;br /&gt;\r\n{Address}&lt;br /&gt;\r\n{Company}&lt;br /&gt;\r\n{DtFrom}&lt;br /&gt;\r\n{People}&lt;br /&gt;\r\n{UniqueID}&lt;br /&gt;\r\n{CancelURL}&lt;br /&gt;\r\n', NULL, 'text', 10, 'height: 420px', 1),
('email_enquiry_subject', 3, NULL, 'Enquiry message', 'Enquiry email subject', NULL, 'string', 9, NULL, 1),
('email_payment', 3, NULL, '1|3::1', 'Send payment confirmation email&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;select if and when confirmation email should be sent to clients after they make a payment for their booking&lt;/span&gt;', 'None|After payment', 'enum', 5, NULL, 1),
('email_payment_message', 3, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nTitle: {Title}\r\nFirst Name: {FirstName}\r\nLast Name: {LastName}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nNotes: {Notes}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nCompany: {Company}\r\n\r\nBooking details:\r\nDate/Time From: {DtFrom}\r\nTable: {Table}\r\nPeople: {People}\r\nBooking ID: {BookingID}\r\nUnique ID: {UniqueID}\r\nTotal: {Total}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Payment confirmation email&lt;br /&gt;\n&lt;u&gt;Available Tokens:&lt;/u&gt;&lt;br /&gt;\n{Title}&lt;br /&gt;\n{FirstName}&lt;br /&gt;\n{LastName}&lt;br /&gt;\n{Email}&lt;br /&gt;\n{Phone}&lt;br /&gt;\n{Notes}&lt;br /&gt;\n{Country}&lt;br /&gt;\n{City}&lt;br /&gt;\n{State}&lt;br /&gt;\n{Zip}&lt;br /&gt;\n{Address}&lt;br /&gt;\n{Company}&lt;br /&gt;\n{DtFrom}&lt;br /&gt;\n{Table}&lt;br /&gt;\n{People}&lt;br /&gt;\n{BookingID}&lt;br /&gt;\n{UniqueID}&lt;br /&gt;\n{Total}&lt;br /&gt;\n{PaymentMethod}&lt;br /&gt;\n{CCType}&lt;br /&gt;\n{CCNum}&lt;br /&gt;\n{CCExp}&lt;br /&gt;\n{CCSec}&lt;br /&gt;\n{CancelURL}&lt;br /&gt;', NULL, 'text', 7, 'height: 465px', 1),
('email_payment_subject', 3, NULL, 'Payment message', 'Payment email subject', NULL, 'string', 6, NULL, 1),
('enquiry', 5, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eu ipsum consectetur arcu commodo egestas nec eu ante. Aenean nec enim lorem. Proin accumsan luctus luctus. Vivamus pulvinar mollis orci, id convallis eros ultricies vel. Nullam adipiscing, risus non pellentesque aliquam, nibh ligula dictum justo, quis commodo nisi dolor ut nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ante leo, ultricies quis gravida id, vestibulum nec risus. Mauris adipiscing vestibulum nibh non ullamcorper. Suspendisse justo turpis, mattis a cursus ac, vulputate quis metus. Fusce vestibulum faucibus dignissim. Aliquam fermentum mauris felis, a ultrices sem.', 'Enquiry', NULL, 'text', 2, 'height: 300px', 1),
('payment_authorize_key', 2, NULL, '', 'Authorize.net transaction key', NULL, 'string', 34, NULL, 1),
('payment_authorize_mid', 2, NULL, '', 'Authorize.net merchant ID', NULL, 'string', 35, NULL, 1),
('payment_disable', 2, NULL, 'No|Yes::No', 'Disable payments&lt;br /&gt;&lt;span style=&quot;font-size: 0.9em&quot;&gt;to disable payments and only accept bookings, set this to &quot;Yes&quot;&lt;/span&gt;', NULL, 'enum', 30, NULL, 1),
('payment_enable_authorize', 2, NULL, 'Yes|No::No', 'Allow Authorize.net payments', NULL, 'enum', 33, NULL, 1),
('payment_enable_cash', 2, NULL, 'Yes|No::Yes', 'Allow Cash payments', NULL, 'enum', 37, NULL, 1),
('payment_enable_creditcard', 2, NULL, 'Yes|No::Yes', 'Allow payments with Credit cards', NULL, 'enum', 38, NULL, 1),
('payment_enable_paypal', 2, NULL, 'Yes|No::Yes', 'Allow PayPal payments', NULL, 'enum', 31, NULL, 1),
('payment_status', 2, NULL, 'confirmed|pending|cancelled::confirmed', 'Default booking status after payment&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;set the default status for each booking after payment is made for it&lt;/span&gt;', NULL, 'enum', 6, NULL, 1),
('paypal_address', 2, NULL, 'paypal@domain.com', 'PayPal business email address', NULL, 'string', 32, NULL, 1),
('reminder_body', 6, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nTitle: {Title}\r\nFirst Name: {FirstName}\r\nLast Name: {LastName}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nNotes: {Notes}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nCompany: {Company}\r\n\r\nBooking details:\r\nDate/Time From: {DtFrom}\r\nTable: {Table}\r\nPeople: {People}\r\nBooking ID: {BookingID}\r\nUnique ID: {UniqueID}\r\nTotal: {Total}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Email Reminder body<br />\n<u>Available Tokens:</u><br />\n{Title}<br />\n{FirstName}<br />\n{LastName}<br />\n{Email}<br />\n{Phone}<br />\n{Notes}<br />\n{Country}<br />\n{City}<br />\n{State}<br />\n{Zip}<br />\n{Address}<br />\n{Company}<br />\n{DtFrom}<br />\n{Table}<br />\n{People}<br />\n{BookingID}<br />\n{UniqueID}<br />\n{Total}<br />\n{PaymentMethod}<br />\n{CCType}<br />\n{CCNum}<br />\n{CCExp}<br />\n{CCSec}<br />\n{CancelURL}<br />', NULL, 'text', 4, 'height:405px;', 1),
('reminder_email_before', 6, NULL, '2', 'Send email reminder', NULL, 'int', 2, NULL, 1),
('reminder_enable', 6, NULL, 'Yes|No::Yes', 'Enable notifications', NULL, 'enum', 1, NULL, 1),
('reminder_sms_country_code', 6, NULL, '358', 'SMS country code', NULL, 'int', 6, NULL, 1),
('reminder_sms_send_address', 6, NULL, 'varaa.com', 'SMS send address', NULL, 'string', 6, NULL, 1),
('reminder_sms_hours', 6, NULL, '1', 'Send SMS reminder', NULL, 'int', 5, NULL, 1),
('reminder_sms_message', 6, NULL, '{FirstName}, booking reminder\r\n\r\n{Table}', 'SMS message<br />\n<u>Available Tokens:</u><br />\n{Title}<br />\n{FirstName}<br />\n{LastName}<br />\n{Email}<br />\n{Phone}<br />\n{Notes}<br />\n{Country}<br />\n{City}<br />\n{State}<br />\n{Zip}<br />\n{Address}<br />\n{Company}<br />\n{DtFrom}<br />\n{Table}<br />\n{People}<br />\n{BookingID}<br />\n{UniqueID}<br />\n{Total}<br />\n{PaymentMethod}<br />\n{CCType}<br />\n{CCNum}<br />\n{CCExp}<br />\n{CCSec}<br />\n{CancelURL}<br />', NULL, 'text', 7, 'height:380px;', 1),
('reminder_subject', 6, NULL, 'Booking Reminder', 'Email Reminder subject', NULL, 'string', 3, NULL, 1),
('terms', 5, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eu ipsum consectetur arcu commodo egestas nec eu ante. Aenean nec enim lorem. Proin accumsan luctus luctus. Vivamus pulvinar mollis orci, id convallis eros ultricies vel. Nullam adipiscing, risus non pellentesque aliquam, nibh ligula dictum justo, quis commodo nisi dolor ut nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ante leo, ultricies quis gravida id, vestibulum nec risus. Mauris adipiscing vestibulum nibh non ullamcorper. Suspendisse justo turpis, mattis a cursus ac, vulputate quis metus. Fusce vestibulum faucibus dignissim. Aliquam fermentum mauris felis, a ultrices sem.\r\n\r\nSuspendisse porttitor, odio eget eleifend aliquet, nibh urna placerat lacus, a rhoncus metus metus et lectus. Fusce convallis nunc dignissim magna condimentum sed lobortis nibh faucibus. Vivamus gravida libero et elit sagittis vel dignissim erat euismod. Nullam quam mi, mollis non feugiat et, facilisis eget sapien. Pellentesque sapien enim, dictum sit amet tincidunt eget, mollis et velit. Aenean scelerisque sem quis eros imperdiet et interdum nunc pellentesque. Morbi consectetur mauris sed sapien tristique eget malesuada tellus suscipit. Praesent aliquam hendrerit purus et vestibulum. Pellentesque dictum lorem velit, id semper tortor. ', 'Terms &amp;amp; Conditions', NULL, 'text', 1, 'height: 400px', 1),
('thank_you_page', 2, NULL, 'http://varaa.com/', '&quot;Thank you&quot; page location&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;this is the page where people will be redirected after paying&lt;/span&gt;', NULL, 'string', 7, NULL, 1),
('timezone', 1, NULL, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'Timezone&lt;br /&gt;\r\n&lt;span style=&quot;font-size: 0.9em&quot;&gt;select your time zone so booking interval can be limited based on your time zone&lt;/span&gt;', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 5, NULL, 1),
('time_format', 1, NULL, 'H:i|G:i|h:i|h:i a|h:i A|g:i|g:i a|g:i A::H:i', 'Time format', 'H:i (09:45)|G:i (9:45)|h:i (09:45)|h:i a (09:45 am)|h:i A (09:45 AM)|g:i (9:45)|g:i a (9:45 am)|g:i A (9:45 AM)', 'enum', 4, NULL, 1),
('use_map', 99, NULL, '1|0::0', 'Use seat map', 'Yes|No', 'bool', NULL, NULL, 0);


DROP TABLE IF EXISTS `restaurant_booking_roles`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_roles` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `role` varchar(255) default NULL,
  `status` enum('T','F') NOT NULL default 'T',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `restaurant_booking_roles` (`id`, `role`, `status`) VALUES
(1, 'admin', 'T');

DROP TABLE IF EXISTS `restaurant_booking_tables`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_tables` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `width` smallint(5) unsigned default NULL,
  `height` smallint(5) unsigned default NULL,
  `top` smallint(5) unsigned default NULL,
  `left` smallint(5) unsigned default NULL,
  `name` varchar(255) default NULL,
  `seats` smallint(5) unsigned default NULL,
  `minimum` smallint(5) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `restaurant_booking_users`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `role_id` int(10) unsigned default NULL,
  `email` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `created` datetime default NULL,
  `last_login` datetime default NULL,
  `status` enum('T','F') default 'T',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `restaurant_booking_users` (`id`, `role_id`, `email`, `password`, `name`, `created`, `last_login`, `status`) VALUES
(1, 1, 'admin@admin.com', 'pass', 'Administrator', '2012-06-21 04:54:47', '2012-06-21 07:54:50', 'T');

DROP TABLE IF EXISTS `restaurant_booking_vouchers`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_vouchers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `code` varchar(255) default NULL,
  `type` enum('amount','percent') default NULL,
  `discount` decimal(9,2) unsigned default NULL,
  `valid` enum('fixed','period','recurring') default NULL,
  `date_from` date default NULL,
  `date_to` date default NULL,
  `time_from` time default NULL,
  `time_to` time default NULL,
  `every` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `restaurant_booking_tables_group`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_tables_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) NOT NULL,
  `tables_id` varchar(250) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `restaurant_booking_bookings_tables_group`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_bookings_tables_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `tables_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `restaurant_booking_menu`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `m_name` varchar(254) CHARACTER SET utf8 NOT NULL,
  `m_type` enum('starters','main_course','desert') CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

DROP TABLE IF EXISTS `restaurant_booking_bookings_menu`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_bookings_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
--
-- Dumping data for table `wp_restaurant_booking_menu`
--

INSERT INTO `restaurant_booking_menu` (`id`, `m_name`, `m_type`) VALUES
(3, 'Steak', 'main_course'),
(5, 'Chicken Salad', 'starters'),
(11, 'Chocolate cake', 'desert');

DROP TABLE IF EXISTS `restaurant_booking_working_times`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_working_times` (
  `id` int(10) unsigned NOT NULL default '0',
  `monday_from` time default NULL,
  `monday_to` time default NULL,
  `monday_dayoff` enum('T','F') default 'F',
  `tuesday_from` time default NULL,
  `tuesday_to` time default NULL,
  `tuesday_dayoff` enum('T','F') default 'F',
  `wednesday_from` time default NULL,
  `wednesday_to` time default NULL,
  `wednesday_dayoff` enum('T','F') default 'F',
  `thursday_from` time default NULL,
  `thursday_to` time default NULL,
  `thursday_dayoff` enum('T','F') default 'F',
  `friday_from` time default NULL,
  `friday_to` time default NULL,
  `friday_dayoff` enum('T','F') default 'F',
  `saturday_from` time default NULL,
  `saturday_to` time default NULL,
  `saturday_dayoff` enum('T','F') default 'F',
  `sunday_from` time default NULL,
  `sunday_to` time default NULL,
  `sunday_dayoff` enum('T','F') default 'F',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `restaurant_booking_working_times` (`id`, `monday_from`, `monday_to`, `monday_dayoff`, `tuesday_from`, `tuesday_to`, `tuesday_dayoff`, `wednesday_from`, `wednesday_to`, `wednesday_dayoff`, `thursday_from`, `thursday_to`, `thursday_dayoff`, `friday_from`, `friday_to`, `friday_dayoff`, `saturday_from`, `saturday_to`, `saturday_dayoff`, `sunday_from`, `sunday_to`, `sunday_dayoff`) VALUES
(1, '09:00:00', '23:00:00', 'F', '09:00:00', '23:00:00', 'F', '09:00:00', '23:00:00', 'F', '09:00:00', '23:00:00', 'F', '09:00:00', '23:00:00', 'F', '09:00:00', '23:00:00', 'F', NULL, NULL, 'T');

--
-- Table structure for table `wp_4_restaurant_booking_formstyle`
--
DROP TABLE IF EXISTS `restaurant_booking_formstyle`;
CREATE TABLE IF NOT EXISTS `restaurant_booking_formstyle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `background` varchar(250) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `wp_4_restaurant_booking_formstyle`
--

INSERT INTO `restaurant_booking_formstyle` (`id`, `logo`, `banner`, `color`, `background`, `font`) VALUES
(6, '', '', '', '', '');
