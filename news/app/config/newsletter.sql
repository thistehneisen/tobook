-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 08. Januar 2011 um 11:48
-- Server Version: 5.1.41
-- PHP-Version: 5.3.2-1ubuntu4.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `final`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `banned_domains`
--

CREATE TABLE IF NOT EXISTS `banned_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `banned_domains`
--

INSERT INTO `banned_domains` (`id`, `name`) VALUES
(2, 'dasd');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `subscriber_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `subscriber_count`) VALUES
(3, 'Category 1', '', 0),
(4, 'Category 2', '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories_subscribers`
--

CREATE TABLE IF NOT EXISTS `categories_subscribers` (
  `category_id` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`subscriber_id`),
  KEY `fk_categories_has_mails_categories` (`category_id`),
  KEY `fk_categories_has_mails_mails1` (`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `categories_subscribers`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `configurations`
--

CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_bin,
  `smtp` tinyint(1) NOT NULL DEFAULT '0',
  `host` varchar(255) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `smtp_auth` tinyint(1) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `from` varchar(255) DEFAULT NULL,
  `reply_to` varchar(255) DEFAULT NULL,
  `mails_per_connection` int(11) NOT NULL DEFAULT '10',
  `wait` int(11) NOT NULL DEFAULT '0',
  `free` datetime DEFAULT NULL,
  `inbox` tinyint(1) NOT NULL DEFAULT '0',
  `inbox_host` varchar(255) DEFAULT NULL,
  `inbox_port` int(11) DEFAULT NULL,
  `inbox_flags` varchar(255) DEFAULT NULL,
  `mailbox` varchar(255) DEFAULT NULL,
  `inbox_username` varchar(255) DEFAULT NULL,
  `inbox_password` varchar(255) DEFAULT NULL,
  `inbox_wait` int(11) DEFAULT NULL,
  `inbox_free` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `configurations`
--

INSERT INTO `configurations` (`id`, `name`, `description`, `smtp`, `host`, `port`, `smtp_auth`, `username`, `password`, `from`, `reply_to`, `mails_per_connection`, `wait`, `free`, `inbox`, `inbox_host`, `inbox_port`, `inbox_flags`, `mailbox`, `inbox_username`, `inbox_password`, `inbox_wait`, `inbox_free`) VALUES
(1, 'Gmail', '', 1, 'ssl://smtp.gmail.com', 465, 1, 'yourmail@gmail.com', 'password', 'yourmail@gmail.com', 'yourmail@gmail.com', 2, 4, '2010-12-24 20:40:51', 1, 'imap.gmail.com', 993, '/imap/ssl/novalidate-cert', '', 'yourmail@gmail.com', 'password', 4, '2010-12-24 20:55:40');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forms`
--

CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `selected_categories` longtext CHARACTER SET latin1 COLLATE latin1_bin,
  `thanks_text` longtext,
  `style` longtext,
  `unsubscribe_text` longtext,
  `unsubscribe_title` varchar(255) DEFAULT NULL,
  `thanks_title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `forms`
--

INSERT INTO `forms` (`id`, `name`, `description`, `title`, `content`, `selected_categories`, `thanks_text`, `style`, `unsubscribe_text`, `unsubscribe_title`, `thanks_title`) VALUES
(4, 'Subscribe', '', 'Subscribe', 'a:12:{s:12:"e-mail_label";s:6:"E-Mail";s:12:"e-mail_error";s:33:"Please enter a valid mail adresse";s:16:"first_name_label";s:10:"First Name";s:16:"first_name_error";s:28:"Please enter your first name";s:19:"first_name_required";s:1:"1";s:15:"last_name_label";s:9:"Last Name";s:15:"last_name_error";s:27:"Please enter your last name";s:18:"last_name_required";s:1:"1";s:16:"categories_label";s:25:"Subscribe this categories";s:16:"categories_error";s:35:"Please chose at least one categorie";s:15:"user_can_choose";s:1:"1";s:13:"submit_button";s:14:"Subscribe new!";}', 0x613a323a7b693a303b733a313a2233223b693a313b733a313a2234223b7d, '', NULL, '', 'Newsletter unsubscribed', 'Thank you for subscribing!');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(512) DEFAULT NULL,
  `clicks` int(11) NOT NULL DEFAULT '0',
  `mail_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

--
-- Daten für Tabelle `links`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mails`
--

CREATE TABLE IF NOT EXISTS `mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `content_html` longtext,
  `content_text` longtext,
  `created` datetime DEFAULT NULL,
  `send_date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `type` int(11) DEFAULT NULL,
  `configuration_id` int(11) DEFAULT NULL,
  `unsubscribed` int(11) NOT NULL DEFAULT '0',
  `modified` datetime DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `last_step` int(11) DEFAULT NULL,
  `final_html` longtext,
  `send_on` datetime DEFAULT NULL,
  `prepared` varchar(45) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_mails_configurations1` (`configuration_id`),
  KEY `fk_mails_templates1` (`template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `mails`
--

INSERT INTO `mails` (`id`, `subject`, `content_html`, `content_text`, `created`, `send_date`, `status`, `type`, `configuration_id`, `unsubscribed`, `modified`, `template_id`, `last_step`, `final_html`, `send_on`, `prepared`) VALUES
(1, 'Demo Newsletter', 'a:7:{s:5:"title";s:9:"Test Mail";s:5:"issue";s:2:"12";s:4:"date";s:17:"22. December 2010";s:8:"topimage";s:50:"/uploads/green-violetear-colibri-thalassinus-1.jpg";s:8:"subtitle";s:11:"Lorem ipsum";s:7:"Content";s:624:"Lorem ipsum dolor sit amet, <a href="http://youtube.com">consetetur sadipscing</a> elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";s:8:"articles";a:2:{i:0;a:3:{s:5:"title";s:11:"Lorem ipsum";s:7:"Content";s:623:"Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem<a href="http://google.com"> ipsum dolor sit amet</a>.";s:3:"img";s:30:"/uploads/grunge_full_1col.jpeg";}i:1;a:3:{s:5:"title";s:7:"At vero";s:7:"Content";s:435:"At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";s:3:"img";s:35:"/uploads/harbourcoat_full_1col.jpeg";}}}', NULL, '2010-12-29 22:09:23', NULL, 0, 1, 1, 0, '2010-12-29 22:11:58', 11, 3, NULL, NULL, '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mails_categories`
--

CREATE TABLE IF NOT EXISTS `mails_categories` (
  `mail_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`mail_id`,`category_id`),
  KEY `fk_mails_has_categories_mails1` (`mail_id`),
  KEY `fk_mails_has_categories_categories1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `mails_categories`
--

INSERT INTO `mails_categories` (`mail_id`, `category_id`) VALUES
(1, 3),
(1, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `recipients`
--

CREATE TABLE IF NOT EXISTS `recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(11) NOT NULL,
  `send_date` datetime DEFAULT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `read_date` datetime DEFAULT NULL,
  `mail_id` int(11) NOT NULL DEFAULT '0',
  `failed` int(11) NOT NULL DEFAULT '0',
  `read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `recipients`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subscribers`
--

CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `mail_adresse` varchar(255) DEFAULT NULL,
  `notes` text,
  `created` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `unsubscribe_code` varchar(255) DEFAULT NULL,
  `form_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subscribers_forms1` (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `subscribers`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `content` longtext,
  `preview` varchar(255) DEFAULT NULL,
  `fields` longtext,
  `parse_css` tinyint(1) NOT NULL DEFAULT '0',
  `fields_array` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='templatescol' AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `templates`
--

INSERT INTO `templates` (`id`, `name`, `content`, `preview`, `fields`, `parse_css`, `fields_array`) VALUES
(5, 'Air Mail', '<html>\r\n	<head>\r\n		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />	\r\n		<title>{$subject}</title>\r\n		<!--general stylesheet-->\r\n		<style type="text/css">\r\n			p { padding: 0; margin: 0; }\r\n			h1, h2, h3, h4, h5, p, li { font-family: Helvetica, Arial, sans-serif; }\r\n			td { vertical-align:top;}\r\n			ul, ol { margin: 0; padding: 0}\r\n		</style>\r\n	</head>\r\n	<body marginheight="0" topmargin="0" marginwidth="0" leftmargin="0" background="uploads/template/bg-1.jpg" style="margin: 0px; background-color: #c3b598; background-image: url(''uploads/template/1bg.jpg''); background-repeat: repeat-y no-repeat; background-position: top center;" bgcolor="#c3b598">\r\n<table style="margin: 0px;" border="0" cellspacing="0" cellpadding="0" width="100%" align="center">\r\n<tbody>\r\n<tr valign="top">\r\n<td style="background-image: url({$baseurl}uploads/template/1bg.jpg); background-repeat: repeat;" valign="top" background="uploads/template/bg-1.jpg"><!--container--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="621" height="77" align="center">\r\n<tbody>\r\n<tr>\r\n<td height="19" valign="top">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style="vertical-align: middle !important;" height="23" valign="middle">\r\n<p style="margin: 0; padding: 0; color: #766d59; font-size: 12px;">You''re receiving this newsletter because you bought widgets from us.</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="margin: 0; padding: 0;" height="1" valign="top"><img style="display: block;" src="{$baseurl}uploads/template/1spacer-2.jpg" alt="" width="621" height="1" /></td>\r\n</tr>\r\n<tr>\r\n<td style="vertical-align: middle !important;" height="23" valign="middle">\r\n<p style="margin: 0; padding: 0; color: #766d59; font-size: 12px;">Not interested anymore? <a href="{$baseurl}{$unsubscribe}"><span style="color: #75211e;">Unsubscribe</span></a>. Having trouble viewing this email? <a href="{$baseurl}{$browerslink}">View it in your browser</a>.</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="margin: 0; padding: 0;" height="1" valign="top"><img style="display: block;" src="{$baseurl}uploads/template/1spacer-2.jpg" alt="" width="621" height="1" /></td>\r\n</tr>\r\n<tr>\r\n<td height="10" valign="top">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="background-color: #f7f3e6; background-image: url({$baseurl}uploads/template/1bg-stamp-3.jpg); background-position: center top; background-repeat: repeat-x no-repeat; border: 11px solid #ffffff;" border="11" cellspacing="0" cellpadding="0" width="621" align="center" bgcolor="#f7f3e6" background="uploads/template/1bg-stamp-3.jpg">\r\n<tbody>\r\n<tr>\r\n<td style="border: none;" valign="top">\r\n<table style="padding-bottom: 13px;" border="0" cellspacing="0" cellpadding="0" align="center">\r\n<tbody>\r\n<tr>\r\n<td colspan="2" valign="top"><img src="{$baseurl}uploads/template/1header-top.jpg" alt="" width="599" height="18" /></td>\r\n</tr>\r\n<tr>\r\n<td style="padding-top: 19px; padding-left: 21px;" width="511" valign="top">\r\n<h1 style="margin: 0; font-size: 42px; color: #33384f;">{$title}</h1>\r\n</td>\r\n<td width="88" valign="top"><img src="{$baseurl}uploads/template/1stamp-bell.jpg" alt="" width="65" height="71" /></td>\r\n</tr>\r\n<tr>\r\n<td colspan="2" valign="top"><img src="{$baseurl}uploads/template/1double-spacer.jpg" alt="" width="599" height="6" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--content--> \r\n<table style="padding-bottom: 20px;" border="0" cellspacing="0" cellpadding="0" width="597" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<table border="0" cellspacing="0" cellpadding="0" width="597" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="padding-top: 6px; padding-bottom: 5px; padding-left: 23px; background-image: url({$baseurl}uploads/template/1date-bg-3.jpg); background-repeat: repeat-x; background-position: center;" valign="top" bgcolor="#545d6c" background="uploads/template/1date-bg-3.jpg">\r\n<h3 class="date" style="margin: 0; color: #ffffff; font-size: 10px; font-weight: bold; text-transform: uppercase; background-color: #545d6c;">{$subtitle}</h3>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border="0" cellspacing="0" cellpadding="0" width="551" align="center">\r\n<tbody>\r\n<!---foreach name=art item=article from=$articles---> \r\n<tr>\r\n<td style="padding-top: 10px; padding-bottom: 19px;" valign="top">\r\n<table border="0" cellspacing="0" cellpadding="0" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<h2 style="margin: 0; padding-bottom: 5px; font-size: 22px; font-family: Georgia; font-style: italic; font-weight: lighter; color: #853938;">{$article.title}</h2>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td height="5" valign="top"><img src="{$baseurl}uploads/template/1top-spacer-3.jpg" alt="" width="551" height="1" /></td>\r\n</tr>\r\n<tr>\r\n<td valign="top">\r\n<p style="text-align: justify; font-size: 12px; line-height: 25px; color: #524e47;">{$article.Content}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td><img src="{$baseurl}uploads/template/1article-spacer-3.jpg" alt="" width="551" height="6" /></td>\r\n</tr>\r\n<!---/foreach--->\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/content--></td>\r\n</tr>\r\n<tr>\r\n<td style="height: 124px; border: none; margin: 0px; background-color: #f7f3e6;" height="124" valign="top" bgcolor="#f7f3e6">\r\n<table border="0" cellspacing="0" cellpadding="0" width="597" align="center">\r\n<tbody>\r\n<tr>\r\n<td colspan="2" valign="top"><img src="{$baseurl}uploads/template/1bottom-db-spacer.jpg" alt="" width="597" height="6" /></td>\r\n</tr>\r\n<tr>\r\n<td style="padding-top: 14px; padding-left: 24px;" valign="top" background="http://newsletters.urldock.com/stan-cm3/images/footer-bg.jpg">\r\n<p style="font-size: 11px; line-height: 16px; color: #766d59;">Changed your details recently? Update your preferences. Don''t want to hear from us again? <a href="{$baseurl}{$unsubscribe}">Unsubscribe</a>.</p>\r\n</td>\r\n<td style="padding: 0; margin: 0;" width="189" valign="top"><img src="{$baseurl}uploads/template/1returned.jpg" alt="Returned to sender" width="189" height="102" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/container--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</body>\r\n</html>', '/uploads/airmail_full_1col.jpeg', 'title\r\nsubtitle\r\n<articles>\r\ntitle\r\nContent\r\n</articles>', 0, 'a:3:{i:0;s:5:"title";i:1;s:8:"subtitle";s:8:"articles";a:2:{i:0;s:5:"title";i:1;s:7:"Content";}}'),
(6, 'Grunge', '<html>\r\n<head>\r\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8">\r\n<title>{$subject}</title>\r\n<!--[if gte mso 9]>\r\n<style type="text/css">\r\n.body {background: #4d4031 url(''uploads/template/2body-bg.jpg'');}	     \r\n.case {background: none;}\r\n</style>\r\n<![endif]-->\r\n</head>\r\n<body class="body" style="margin: 0; padding: 0; background-color: #4d4031; background-image: url(uploads/template/2body-bg.jpg); background-repeat: repeat;" marginheight="0" topmargin="0" marginwidth="0" leftmargin="0">\r\n<!--100% body table--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td class="case" style="background-color: #4d4031; background-image: url({$baseurl}uploads/template/2body-bg.jpg); background-repeat: repeat;" bgcolor="#d8e7ea"><!--[if gte mso 9]>\r\n<td style="background: none;" _mce_style="background: none;"><![endif]--> <!--container--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="620" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="background-image: url({$baseurl}uploads/template/left-side.jpg); background-repeat: repeat;" width="22" valign="top" bgcolor="#d4d1cd" background="uploads/template/left-side.jpg">&nbsp;</td>\r\n<td width="576" valign="top" bgcolor="#d4d1cd"><!--top--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="575">\r\n<tbody>\r\n<tr>\r\n<td height="60" align="center">\r\n<p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; text-transform: uppercase; margin: 0; padding: 0;">Email not looking beautiful? <a href="{$baseurl}{$browerslink}"> View it in your browser</a></p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/top--> <!--header--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="575">\r\n<tbody>\r\n<tr>\r\n<td style="background-image: url({$baseurl}uploads/template/1header-bg.jpg); background-position: left; background-repeat: no-repeat; background-color: #000;" height="90" valign="top">\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td width="6%" height="10">&nbsp;</td>\r\n<td width="94%" height="10">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td valign="top">\r\n<table border="0" cellspacing="0" cellpadding="8" width="450">\r\n<tbody>\r\n<tr>\r\n<td bgcolor="#000000">\r\n<h1 style="margin: 0; padding: 0; font-family: Georgia, ''Times New Roman'', Times, serif; color: #fff; font-size: 24px;">{$title}</h1>\r\n<p style="margin: 0; padding: 0; font-family: Georgia, ''Times New Roman'', Times, serif; color: #fff; font-size: 12px;">{$subtitle}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/header--> <!--break--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="15">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/break--> <!--section--> {foreach name=art item=article from=$articles}              \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<table border="0" cellspacing="0" cellpadding="0" width="560" align="center">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<h2 style="color: #000; font-size: 21px; font-family: Georgia, ''Times New Roman'', Times, serif; margin: 0px; padding: 0; text-shadow: 1px 1px 1px #fff;">{$article.title}</h2>\r\n<!--break--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="10">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/break-->\r\n<p style="color: #000; font-size: 14px; font-family: Georgia, ''Times New Roman'', Times, serif; margin: 0; padding: 0;">{$article.Content}</p>\r\n<!--break--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="15">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/break--> {if !empty($article.img)}         \r\n<table border="0" cellspacing="10" cellpadding="0" width="540" bgcolor="#000000">\r\n<tbody>\r\n<tr>\r\n<td width="540" height="158">[image src={$article.img} w=540 ]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n{/if}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/section--> <!--break--> <!--/break--> <!--section--> {/foreach}       <!--/section--> <!--break--> <!--/break--> <!--section--> <!--/section--> <!--footer--> <!--dash--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="40" align="center" valign="bottom"><img src="{$baseurl}uploads/template/line-dash.jpg" alt="" width="559" height="22" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/dash--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="560" align="center">\r\n<tbody>\r\n<tr>\r\n<td width="57%" valign="top">\r\n<p style="font-size: 12px; color: #666; margin: 0; padding: 0; font-family: Georgia, ''Times New Roman'', Times, serif;">You received this email because at some point in the past you                                         either bought one of our products, signed up to our mailing list,                                          or drunk some of that &lsquo;special&rsquo; tea we slipped you. If you&rsquo;d like,                                          you can                                         unsubscribe                                          .</p>\r\n</td>\r\n<td width="43%" align="center" valign="middle"><!--button--> \r\n<table border="0" cellspacing="0" cellpadding="10" width="200">\r\n<tbody>\r\n<tr>\r\n<td style="color: #ffffff; text-align: center; background-image: url({$baseurl}uploads/template/share.jpg); background-position: center; background-repeat: no-repeat;" height="48">\r\n<table border="0" cellspacing="0" cellpadding="3" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="20" align="center" bgcolor="#cc0000"><span style="font-size: medium;"><a href="{$baseurl}{$unsubscribe}">Unsubscribe</a></span></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/button--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--dash--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="30" align="center" valign="top"><img src="{$baseurl}uploads/template/line-dash.jpg" alt="" width="559" height="22" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/dash--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="560" align="center">\r\n<tbody>\r\n<tr>\r\n<td align="center">\r\n<p style="font-size: 12px; color: #666; margin: 0; padding: 0; font-family: Georgia, ''Times New Roman'', Times, serif; text-transform: uppercase;">Company and the Company logo are registered trademarks of Company                                         Company &mdash; 123 Some Street, City, ST 99999. Ph +1 4 1477 89 745</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--break--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="25">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/break--> <!--footer--></td>\r\n<td style="background-image: url({$baseurl}uploads/template/right-side.jpg); background-repeat: repeat;" width="22" valign="top" bgcolor="#d4d1cd" background="uploads/template/right-side.jpg">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/container--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/100% body table-->\r\n</body>\r\n</html>', '/uploads/grunge_full_1col.jpeg', 'title\r\nsubtitle\r\n<articles>\r\ntitle\r\nContent\r\nimg_imgchooser\r\n</articles>', 0, 'a:3:{i:0;s:5:"title";i:1;s:8:"subtitle";s:8:"articles";a:3:{i:0;s:5:"title";i:1;s:7:"Content";i:2;s:14:"img_imgchooser";}}'),
(8, 'Natural', '<html>\r\n<head>\r\n<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />\r\n<title>{$subject}</title>\r\n</head>\r\n<body marginheight="0" topmargin="0" marginwidth="0" leftmargin="0" background="uploads/template/3body-bg.jpg" style="margin: 0px; background-color: #f5f5df; background-image: url(''uploads/template/3body-bg.jpg''); background-repeat: repeat;" bgcolor="#f5f5df">\r\n<!--100% body table--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr valign="top">\r\n<td><!--leaf background--> \r\n<table id="leaf" style="background: url({$baseurl}uploads/template/canvas-bg.jpg) no-repeat;" border="0" cellspacing="0" cellpadding="0" width="960" align="center">\r\n<tbody>\r\n<tr>\r\n<td><!--container--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="680" align="center">\r\n<tbody>\r\n<tr>\r\n<td><!--header--> \r\n<table id="header" style="position: relative; height: 162px;" border="0" cellspacing="0" cellpadding="0" width="680">\r\n<tbody>\r\n<tr>\r\n<td height="39" valign="top"><!--web version link--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="680">\r\n<tbody>\r\n<tr>\r\n<td style="background-color: #86bc74; background-image: url(http://newsletters.urldock.com/natural/images/web-version-link-bg.jpg); background-repeat: no-repeat; background-position: top;" width="345" height="39" valign="top" bgcolor="#86bc74">\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="10">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style="padding: 0; font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #fff; margin-bottom: 0px; margin-top: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Is this email not displaying correctly? <a href="{$baseurl}{$browerslink}">Try the                                                                 web version</a></p>\r\n</td>\r\n<td height="39">&nbsp;</td>\r\n<td style="background-color: #412b27; background-image: url(http://newsletters.urldock.com/natural/images/foward-to-friend-link-bg.jpg); background-repeat: no-repeat; background-position: top;" width="170" height="39" valign="top" bgcolor="#3b2724">\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="7">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style="text-align: center; font-weight: bold; font-size: 11px; font-family: Arial, Helvetica, sans-serif; padding: 0px; color: #fff; padding-left: 20px; margin-bottom: 0px; margin-top: 0px;"><strong> Forward to a friend                                                                 &gt;&gt;</strong></p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--web version link--></td>\r\n</tr>\r\n<tr>\r\n<td height="123"><!--title and date wrapper--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="680">\r\n<tbody>\r\n<tr>\r\n<td width="470" height="123">\r\n<h1 style="color: #6b4f49; margin: 0px 0px 10px; text-shadow: #ccc 2px 2px 2px; font-size: 48px; font-family: Georgia, ''Times New Roman'', Times, serif;">{$title}</h1>\r\n</td>\r\n<td width="210" height="123" valign="bottom"><!--date--> \r\n<table id="date" style="background-color: #85bc73; position: relative; right: 0px; bottom: -25px; color: #fff; font-size: 21px; font-weight: bold; font-family: Georgia, ''Times New Roman'', Times, serif; border-radius: 6px;" border="0" cellspacing="0" cellpadding="5" width="200" bgcolor="#85bc73">\r\n<tbody>\r\n<tr>\r\n<td width="31" height="31"><img src="{$baseurl}uploads/template/clock-icon.jpg" alt="" width="31" height="31" align="left" /></td>\r\n<td width="149" height="31">{$subtitle}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/date--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/title and date wrapper--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/header--> <!--brown intro--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="680">\r\n<tbody>\r\n<tr>\r\n<td height="21" align="left"><img style="margin-top: 0px; margin-bottom: 0px; display: block;" src="{$baseurl}uploads/template/intro-top-spikes.jpg" alt="intro" width="680" height="21" align="left" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border="0" cellspacing="0" cellpadding="0" width="680" bgcolor="#392622">\r\n<tbody>\r\n<tr>\r\n<td id="brown-intro" style="background-color: #392622; background-image: url({$baseurl}uploads/template/intro-bg.jpg); background-position: top; background-repeat: repeat-y;" valign="top"><!--table to pad content for x browser--> \r\n<table border="0" cellspacing="0" cellpadding="20" width="680">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<h2 style="color: #fff; margin: 0px; text-shadow: #000 2px 2px 0px; font-size: 30px; font-family: Georgia, ''Times New Roman'', Times, serif;">{$intro_title}</h2>\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="12">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style="color: #fff; margin-top: 0px; margin-bottom: 0px; text-shadow: #000 2px 2px 0px; font-size: 20px; font-family: Georgia, ''Times New Roman'', Times, serif;">{$Intro}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="75"><img style="margin: 0px; padding: 0px; display: block;" src="{$baseurl}uploads/template/intro-bg-bottom.jpg" alt="" width="680" height="75" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/brown intro--> <!--main content--> \r\n<table id="content-wrapper" style="border-bottom: solid 1px #dfedd7; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px;" border="0" cellspacing="0" cellpadding="20" width="680" bgcolor="#fffcf5">\r\n<tbody>\r\n<tr>\r\n<td id="content"><!--content area 1--> <!--/content area 1--> <!--line break--> {foreach name=art item=article from=$articles} <!--content area 2--> {if !empty($article.img)}  {/if} \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<h1 style="color: #9daf76; margin: 0px; font-size: 24px;">{$article.title}</h1>\r\n<br />\r\n<p style="color: #6b4f49; margin: 0px 0px 12px; font-size: 16px; font-family: Georgia, ''Times New Roman'', Times, serif;">{$article.Content}</p>\r\n{if !empty($article.more)}\r\n<p style="color: #6b4f49; margin: 0px 0px 12px; font-size: 16px;"><a style="color: #ff881c; text-decoration: underline;" href="{$baseurl}{$article.more}">Read more &gt;&gt;</a></p>\r\n{/if}</td>\r\n<td width="249">[image src={$article.img} w=209]<br /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/content area 2--> <!--line break--> <img src="{$baseurl}uploads/template/line-break.jpg" alt="break" width="631" height="20" />{/foreach} <!--content area 3--><!--/content area 3--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/main content--> <!--footer--> \r\n<table id="footer" border="0" cellspacing="0" cellpadding="20" width="680">\r\n<tbody>\r\n<tr>\r\n<td width="300" valign="top">\r\n<p style="color: #c2bcab; margin: 0px 0px 12px; font-weight: bold; font-size: 13px; font-family: Arial, Helvetica, sans-serif;">You&rsquo;re receiving this newsletter because you&rsquo;ve subscribed to our Newsletter.                                                     <a href="{$baseurl}{$unsubscribe}">Unsubscribe instantly</a>.</p>\r\n</td>\r\n<td width="300" valign="top">\r\n<table border="0" cellspacing="0" cellpadding="0" width="220">\r\n<tbody>\r\n<tr>\r\n<td valign="top"><a style="color: #ff881c; text-decoration: underline;" href="#"><img src="{$baseurl}uploads/template/1home-icon.png" border="0" alt="visit us" width="31" height="19" /></a></td>\r\n<td>\r\n<p style="color: #c2bcab; margin: 0px 0px 12px; font-weight: bold; font-size: 13px; font-family: Arial, Helvetica, sans-serif;">Newism<br /> 178 King Street Newcastle<br /> NSW, Australtia 2300</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/footer--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--container--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/leaf background--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/100% body table-->\r\n</body>\r\n</html>', '/uploads/natural_full_1col.jpeg', 'title\r\nsubtitle\r\nintro_title\r\nIntro\r\n<articles>\r\ntitle\r\nmore\r\nContent\r\nimg_imgchooser\r\n</articles>', 0, 'a:5:{i:0;s:5:"title";i:1;s:8:"subtitle";i:2;s:11:"intro_title";i:3;s:5:"Intro";s:8:"articles";a:4:{i:0;s:5:"title";i:1;s:4:"more";i:2;s:7:"Content";i:3;s:14:"img_imgchooser";}}'),
(10, 'Clouds', '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">\r\n    <head>\r\n        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />	\r\n        <title>{$subject}</title>\r\n        <!--general stylesheet-->\r\n        <style type="text/css">\r\n            p { padding: 0; margin: 0; }\r\n            h1, h2, h3, p, li { font-family: Georgia, Helvetica, sans-serif, Arial; }\r\n            td { vertical-align:top;}\r\n            ul, ol { margin: 0; padding: 0;}\r\n        </style>\r\n    </head>\r\n    <body marginheight="0" topmargin="0" marginwidth="0" leftmargin="0" bgcolor="#cad3db" background="uploads/template/4body-bg.jpg" style="margin: 0px; background-color: #cad3db; background-image: url(''uploads/template/4body-bg.jpg''); background-repeat: repeat;">\r\n<table style="margin: 0px;" border="0" cellspacing="0" cellpadding="0" width="100%" align="center">\r\n<tbody>\r\n<tr valign="top">\r\n<td style="margin: 0px; background-image: url({$baseurl}uploads/template/4body-bg.jpg); background-repeat: repeat;" valign="top" background="uploads/template/4body-bg.jpg"><!--container--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<table style="padding-left: 5px; padding-right: 5px;" border="0" cellspacing="0" cellpadding="0" width="600" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<table style="background-image: url({$baseurl}uploads/template/clouds-bg.jpg); background-repeat: no-repeat; background-position: center top;" border="0" cellspacing="0" cellpadding="0" align="center" background="uploads/template/clouds-bg.jpg">\r\n<tbody>\r\n<tr>\r\n<td style="padding-top: 15px; padding-bottom: 8px;" colspan="2" valign="top">\r\n<p style="margin: 0; color: #6e7c88; font-size: 12px; line-height: 1.4;">You''re receiving this newsletter because you bought widgets from us. <br /> Not interested anymore? Unsubscribe. Having trouble viewing this email?  																			<a href="{$baseurl}{$browerslink}">View it in your browser.</a></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="vertical-align: baseline; text-align: left;" valign="baseline">\r\n<h1 style="margin: 0; font-size: 34px; text-transform: uppercase; font-weight: normal; color: #ffffff; letter-spacing: 2px; font-family: Gill Sans, Trebuchet MS, Helvetica, Arial, sans-serif;">{$title}</h1>\r\n</td>\r\n<td style="vertical-align: baseline; text-align: right;" valign="baseline"><span class="date" style="font-size: 12px; color: #6e7c88;">{$subtitle}</span></td>\r\n</tr>\r\n<tr>\r\n<td style="padding-top: 10px; padding-bottom: 15px;" colspan="2" valign="top"><img style="display: block;" src="{$baseurl}uploads/template/divider.png" alt="" width="600" height="7" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td colspan="2" valign="top">\r\n<table style="margin: 0px;" border="0" cellspacing="0" cellpadding="0" width="600" align="center">\r\n<tbody>\r\n<tr>\r\n<td width="400" valign="top">\r\n<table style="margin: 0px;" border="0" cellspacing="0" cellpadding="0" width="400" align="center">\r\n<tbody>\r\n<!---foreach name=art item=article from=$articles---> \r\n<tr>\r\n<td valign="top">\r\n<table style="border: 10px; border-style: solid; border-color: #f3f5f7; background-color: #dfe4e9;" border="0" cellspacing="0" cellpadding="0" width="400" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="padding-bottom: 20px; padding-top: 20px;" valign="top">\r\n<h2 style="margin: 0; padding-left: 20px; padding-top: 3px; padding-bottom: 3px; font-size: 24px; color: #626b73; font-weight: normal; background-color: #cbd3db; background-image: url({$baseurl}uploads/template/article-title-bg.jpg); background-repeat: repeat; background-position: center center;">{$article.title}</h2>\r\n<table style="padding-top: 10px;" border="0" cellspacing="0" cellpadding="0" width="340" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<div style="padding-bottom: 12px;">[image src={$article.img} w=341 border=10 bcolor=C2CAD2 ]</div>\r\n<p style="color: #798692; font-size: 14px; line-height: 20px;">{$article.Content}</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="top">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<!---if !$smarty.foreach.art.last---> \r\n<tr>\r\n<td style="padding-top: 20px; padding-bottom: 20px;" valign="top"><img style="display: block;" src="{$baseurl}uploads/template/divider-small.png" alt="" width="400" height="8" /></td>\r\n</tr>\r\n<!---/if---><!---/foreach--->\r\n</tbody>\r\n</table>\r\n</td>\r\n<td style="background-image: url({$baseurl}uploads/template/cloud-3.jpg); background-repeat: no-repeat; background-position: left bottom;" width="200" valign="top" background="uploads/template/2cloud-3.jpg">\r\n<table border="0" cellspacing="0" cellpadding="0" width="170" align="right">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<table style="padding-bottom: 20px;" border="0" cellspacing="0" cellpadding="0" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<h4 style="margin: 0; padding-bottom: 5px; color: #ffffff; text-transform: uppercase; font-size: 10px; letter-spacing: 2px;">Contents</h4>\r\n<img style="margin: 0; padding: 0; display: block;" src="{$baseurl}uploads/template/sidebar-sep.jpg" alt="" width="170" height="2" /></td>\r\n</tr>\r\n<tr>\r\n<td valign="top">\r\n<table style="padding-top: 5px; color: #3d464c; font-style: italic; font-weight: lighter; font-family: Georgia;" border="0" cellspacing="0" cellpadding="0" width="170" align="left">\r\n<tbody>\r\n<!---foreach name=art item=article from=$articles---> \r\n<tr>\r\n<td style="padding-top: 2px; padding-bottom: 2px; vertical-align: top;" width="12" height="10" valign="top"><img style="padding-top: 5px; padding-bottom: 2px; display: block;" src="{$baseurl}uploads/template/bullet-1.jpg" alt="" width="5" height="5" /></td>\r\n<td style="padding-top: 2px; padding-bottom: 2px; vertical-align: top; font-size: 12px;" valign="top">{$article.title}</td>\r\n</tr>\r\n<!---/foreach--->\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="top">\r\n<table style="padding-bottom: 20px;" border="0" cellspacing="0" cellpadding="0" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<h4 style="margin: 0; padding-bottom: 5px; color: #ffffff; text-transform: uppercase; font-size: 10px; letter-spacing: 2px;">In short</h4>\r\n<img style="margin: 0; padding: 0; display: block;" src="{$baseurl}uploads/template/sidebar-sep.jpg" alt="" width="170" height="2" /></td>\r\n</tr>\r\n<tr>\r\n<td valign="top">\r\n<p style="margin: 0; padding-top: 5px; color: #6e7c88; font-size: 12px; line-height: 18px;">Nunc ipsum metus, iaculis sit amet, interdum at. Donce imperiat ccumsan felis.</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td valign="top">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td valign="top">\r\n<table style="padding-bottom: 20px;" border="0" cellspacing="0" cellpadding="0" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<h4 style="margin: 0; padding-bottom: 5px; color: #ffffff; text-transform: uppercase; font-size: 10px; letter-spacing: 2px;">Unsubscribe</h4>\r\n<img style="margin: 0; padding: 0; display: block;" src="{$baseurl}uploads/template/sidebar-sep.jpg" alt="" width="170" height="2" /></td>\r\n</tr>\r\n<tr>\r\n<td valign="top">\r\n<p style="margin: 0; padding-top: 5px; color: #6e7c88; font-size: 12px; line-height: 18px;">Don''t want to receive these emails any more. Please <a href="{$baseurl}{$unsubscribe}"><span style="font-style: italic; color: #353d43;">unsubscribe instantly</span></a>.</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td class="footer" style="padding: 10px 0 20px;" colspan="2" valign="top">\r\n<p style="font-family: Georgia; margin: 0; padding-bottom: 3px; padding-top: 3px; color: #6e7c88; font-size: 12px;">ABCWidgets Corp - 123 Some Street, City, ST 99999. Ph +1 4 1477 89 745</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 20px;" colspan="2" valign="top"><img style="display: block;" src="{$baseurl}uploads/template/divider.png" alt="" width="600" height="7" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/container--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</body>\r\n</html>', '/uploads/clouds_full_2col_right.jpeg', 'title\r\nsubtitle\r\n<articles>\r\ntitle\r\nContent\r\nimg_imgchooser\r\n</articles>', 1, 'a:3:{i:0;s:5:"title";i:1;s:8:"subtitle";s:8:"articles";a:3:{i:0;s:5:"title";i:1;s:7:"Content";i:2;s:14:"img_imgchooser";}}'),
(11, 'Super', '<html>\r\n<head>\r\n<title>{$subject}</title>\r\n</head>\r\n<body style="margin:0;">\r\n<!--100% body table--><!--100% body table--><!--100% body table--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#f7f2e4">\r\n<tbody>\r\n<tr>\r\n<td><!--top links--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="45" align="center" valign="middle">\r\n<p style="font-size: 14px; line-height: 24px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #b0a08b; margin: 0px;">Is this email not displaying correctly? <a href="{$baseurl}{$browerslink}">Try the web version</a>.</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--header--> \r\n<table style="background: url({$baseurl}uploads/template/2header-bg.jpg); background-repeat: no-repeat; background-position: center; background-color: #fffdf9;" border="0" cellspacing="0" cellpadding="0" width="684" align="center">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td width="173" valign="top"><!--ribbon--> \r\n<table border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td width="45" height="120">&nbsp;</td>\r\n<td width="80" height="120" valign="top" bgcolor="#c72439" background="uploads/template/ribbon.jpg">\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="35" align="center" valign="bottom">\r\n<p style="font-size: 14px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #ffffff; margin-top: 0px; margin-bottom: 0px;">ISSUE</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td align="center" valign="top">\r\n<p style="font-size: 36px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #ffffff; margin-top: 0px; margin-bottom: 0px; text-shadow: 1px 1px 1px #333;">{$issue}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--ribbon--></td>\r\n<td width="493" valign="middle">\r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="60">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<h1 style="color: #333; margin-top: 0px; margin-bottom: 0px; font-weight: normal; font-size: 48px; font-family: Georgia, ''Times New Roman'', Times, serif;">{$title}<em></em></h1>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td height="40">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--date--> \r\n<table border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td style="width: 357px; height: 42px; background-color: #312c26; background-image: url({$baseurl}uploads/template/date-bg.jpg);" align="center" valign="middle">\r\n<p style="font-size: 24px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #ffffff; margin-top: 0px; margin-bottom: 0px;">{$date}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/date--></td>\r\n<td width="18">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/header--> <!--email container--> \r\n<table border="0" cellspacing="0" cellpadding="30" width="684" align="center" bgcolor="#fffdf9">\r\n<tbody>\r\n<tr>\r\n<td><!--email content--> \r\n<table id="email-content" border="0" cellspacing="0" cellpadding="0" width="624">\r\n<tbody>\r\n<tr>\r\n<td><!--section 1--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td align="center" valign="top">{if !empty($topimage)} [image src={$topimage} w=622 ]<!--line break--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="50" valign="bottom"><img src="{$baseurl}uploads/template/1line-break.jpg" alt="" width="622" height="27" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/line break-->{/if}\r\n<h1 style="font-size: 36px; font-weight: normal; color: #333333; font-family: Georgia, ''Times New Roman'', Times, serif; margin-top: 0px; margin-bottom: 20px;"><em>{$subtitle}</em><em></em></h1>\r\n<p style="font-size: 16px; line-height: 22px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #333; margin: 0px;">{$Content}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/section 1--> <!--line break--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="72"><img src="{$baseurl}uploads/template/line-break-2.jpg" alt="" width="622" height="72" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/line break--> <!--section 2--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/section 2--> <!--section 3--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td><!--line break--> <!--/line break--> {foreach name=art item=article from=$articles}                         \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td width="378" valign="top">\r\n<h1 style="font-size: 24px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #333333; margin-top: 0px; margin-bottom: 12px;">{$article.title}</h1>\r\n<p style="font-size: 16px; line-height: 22px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #333; margin: 0px;">{$article.Content}</p>\r\n</td>\r\n<td style="width: 246px;" align="right" valign="top">{if !empty($article.img)} [image src={$article.img} w=250 h=250 ] {/if}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--line break--> \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="50" valign="bottom"><img src="{$baseurl}uploads/template/1line-break.jpg" alt="" width="622" height="27" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/line break--> {/foreach}                         \r\n<table border="0" cellspacing="0" cellpadding="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td height="72"><img src="{$baseurl}uploads/template/line-break-2.jpg" alt="" width="622" height="72" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/line break--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/section 3--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/email content--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/email container--> <!--footer--> \r\n<table border="0" cellspacing="0" cellpadding="30" width="680" align="center">\r\n<tbody>\r\n<tr>\r\n<td valign="top">\r\n<p style="font-size: 14px; line-height: 24px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #b0a08b; margin: 0px;">You&rsquo;re receiving this newsletter because you&rsquo;ve subscribed to our newsletter<br /> Not interested anymore? <a href="{$baseurl}{$unsubscribe}">Unsubscribe instantly</a>.</p>\r\n</td>\r\n<td valign="top">\r\n<p style="font-size: 14px; line-height: 24px; font-family: Georgia, ''Times New Roman'', Times, serif; color: #b0a08b; margin: 0px;">Newism     178 King Street Newcastle      NSW, Australtia 2300</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td height="30">&nbsp;</td>\r\n<td height="30">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/footer--></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!--/100% body table-->\r\n</body>\r\n</html>', '/uploads/classic_full_1col_alt.jpeg', 'title\r\nissue\r\ndate\r\ntopimage_imgchooser\r\nsubtitle\r\nContent\r\n<articles>\r\ntitle\r\nContent\r\nimg_imgchooser\r\n</articles>', 1, 'a:7:{i:0;s:5:"title";i:1;s:5:"issue";i:2;s:4:"date";i:3;s:19:"topimage_imgchooser";i:4;s:8:"subtitle";i:5;s:7:"Content";s:8:"articles";a:3:{i:0;s:5:"title";i:1;s:7:"Content";i:2;s:14:"img_imgchooser";}}'),
(12, 'Harboat', '<html lang="en">\r\n  <head>\r\n    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />\r\n    <title>{$subject}</title>\r\n    \r\n  \r\n<style type="text/css">\r\na:hover { text-decoration: none !important; }\r\n</style>\r\n</head>\r\n  <body bgcolor="#e8e4ce" style="text-align: left; padding: 0; margin: 0; background: #e8e4ce url(''uploads/template/2bg_email.png'');">\r\n<table style="background: #e8e4ce url({$baseurl}uploads/template/2bg_email.png);" border="0" cellspacing="0" cellpadding="0" width="100%" align="center" bgcolor="#e8e4ce">\r\n<tbody>\r\n<tr>\r\n<td style="font-family: Helvetica, Arial, sans-serif;" align="center" valign="top">\r\n<table border="0" cellspacing="0" cellpadding="0" width="600">\r\n<tbody>\r\n<tr>\r\n<td style="padding: 0 0 16px; font-family: Helvetica, Arial, sans-serif;" align="left">\r\n<table class="unsubscribe" border="0" cellspacing="0" cellpadding="0" width="600" align="left">\r\n<tbody>\r\n<tr>\r\n<td style="padding: 12px 0; font-family: Helvetica, Arial, sans-serif;" align="center">\r\n<p style="padding: 0; font-size: 12px; line-height: 16px; color: #666; margin: 0;">You''re receiving this newsletter because you signed up at yoursite.com or bought widgets from us.</p>\r\n<p style="padding: 0; font-size: 12px; line-height: 16px; color: #666; margin: 0;">Having trouble reading this email? <a href="{$baseurl}{$browerslink}">View it in your browser</a>. Not interested anymore? <a href="{$baseurl}{$unsubscribe}">Unsubscribe Instantly</a>.</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!-- unsubscribe --></td>\r\n</tr>\r\n<tr>\r\n<td style="padding: 0 0 20px; font-family: Helvetica, Arial, sans-serif;" align="left">\r\n<table class="header" border="0" cellspacing="0" cellpadding="0" width="600" align="left">\r\n<tbody>\r\n<tr>\r\n<td style="font-family: Helvetica, Arial, sans-serif;"><img style="display: block;" src="{$baseurl}uploads/template/bg_header_top.jpg" alt="divider" /></td>\r\n</tr>\r\n<tr>\r\n<td style="font-family: Helvetica, Arial, sans-serif;" align="left" valign="top">\r\n<table style="background: url({$baseurl}uploads/template/bg_header.png) repeat scroll 0% 0% #2d807c; width: 600px;" border="0" cellspacing="0" align="left">\r\n<tbody>\r\n<tr>\r\n<td style="padding: 10px; font-family: Helvetica, Arial, sans-serif; background: #2d807c url({$baseurl}uploads/template/bg_header.png);" valign="top" bgcolor="#2d807c">\r\n<h1 style="font-weight: bold; padding: 0; font-size: 60px; line-height: 76px; color: #ffe3a9; margin: 10px 0 0;">{$title}</h1>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="padding: 10px; font-family: Helvetica, Arial, sans-serif; background: #2d807c url({$baseurl}uploads/template/bg_header.png);" valign="top" bgcolor="#2d807c">\r\n<table style="background: #2d807c url({$baseurl}uploads/template/bg_header.png);" border="0" cellspacing="0" cellpadding="0" align="left" bgcolor="#2d807c">\r\n<tbody>\r\n<tr>\r\n<td style="padding: 0 0 10px; font-family: Helvetica, Arial, sans-serif; background: #2d807c url({$baseurl}uploads/template/bg_header.png);" width="204" valign="top" bgcolor="#2d807c">\r\n<p style="padding: 0; font-size: 13px; line-height: 16px; color: #f9e9c1; margin: 0 0 10px;"><strong>In This Issue</strong></p>\r\n<ul style="padding: 0; border-bottom: 1px solid #6ba5a2; margin: 0; list-style: none; list-style-position: outside;">\r\n<!---foreach name=art item=article key=k from=$articles--->\r\n<li style="font-size: 13px; line-height: 15px; color: #f9e9c1; list-style-type: none; border-top: 1px solid #6ba5a2; margin: 0; padding: 4px 0;"><a style="text-decoration: none; color: #f9e9c1;" href="#{$k}">{$article.title}</a></li>\r\n<!---/foreach---> \r\n</ul>\r\n</td>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #2d807c url({$baseurl}uploads/template/bg_header.png);" width="38" bgcolor="#2d807c">&nbsp;</td>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #2d807c url({$baseurl}uploads/template/bg_header.png);" valign="top" bgcolor="#2d807c">\r\n<h3 style="font-weight: normal; padding: 0; font-size: 16px; line-height: 20px; color: #f9e9c1; margin: 6px 0 0;">{$Content}</h3>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<!-- header --></td>\r\n</tr>\r\n<!---foreach name=art item=article from=$articles---> \r\n<tr>\r\n<td style="padding: 0 0 20px; font-family: Helvetica, Arial, sans-serif;" align="left">\r\n<table class="content-item" style="background: #fff; border: 1px solid #bddacb;" border="0" cellspacing="0" cellpadding="0" width="600" align="left" bgcolor="#000000">\r\n<tbody>\r\n<tr>\r\n<td style="padding: 10px 10px 0; font-family: Helvetica, Arial, sans-serif; background: #fff;" colspan="2" bgcolor="#000000">\r\n<h2 style="padding: 6px 10px; background: #9ebbb0; font-weight: bold; font-size: 14px; line-height: 16px; margin: 0; color: #fdfdfd;">{$article.title}</h2>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="padding: 10px; font-family: Helvetica, Arial, sans-serif; background: #fff;" valign="top" bgcolor="#000000">\r\n<p style="padding: 0; font-size: 13px; line-height: 16px; color: #2d817d; margin: 0 0 20px;">{$article.Content}</p>\r\n<a class="read-more" style="font-size: 12px; text-decoration: underline; color: #000;" href="#">Read more</a></td>\r\n<td style="padding: 10px; font-family: Helvetica, Arial, sans-serif; background: #fff;" valign="top" bgcolor="#000000">{if !empty($article.img)} [image src={$article.img} w=257 ] {/if}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="padding: 0 0 20px; font-family: Helvetica, Arial, sans-serif;" align="left">&nbsp;</td>\r\n</tr>\r\n<!---/foreach---> \r\n<tr>\r\n<td style="padding: 0 0 20px; font-family: Helvetica, Arial, sans-serif;" align="left">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style="padding: 0 0 10px; font-family: Helvetica, Arial, sans-serif;" align="left">\r\n<table class="footer" style="background: #5b9b83 url({$baseurl}uploads/template/bg_footer.png);" border="0" cellspacing="0" cellpadding="10" width="600" align="left" bgcolor="#5b9b83">\r\n<tbody>\r\n<tr>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #1b4a15;" width="10" bgcolor="#1b4a15">&nbsp;</td>\r\n<td style="font-family: Helvetica,Arial,sans-serif; background: url({$baseurl}none) repeat scroll 0% 0% #1b4a15; width: 285px;">\r\n<h4 style="padding: 0; font-size: 13px; line-height: 14px; color: #fff; margin: 0;">UNSUBSCRIBE</h4>\r\n</td>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #1b4a15;" width="5" bgcolor="#1b4a15">&nbsp;</td>\r\n<td style="font-family: Helvetica,Arial,sans-serif; background: url({$baseurl}none) repeat scroll 0% 0% #1b4a15; width: 285px;">\r\n<h4 style="padding: 0; font-size: 13px; line-height: 14px; color: #fff; margin: 0;">CONTACT US</h4>\r\n</td>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #1b4a15;" width="10" bgcolor="#1b4a15">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #5b9b83 url({$baseurl}uploads/template/bg_footer.png);" bgcolor="#5b9b83">&nbsp;</td>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #5b9b83 url({$baseurl}uploads/template/bg_footer.png);" valign="top" bgcolor="#5b9b83">\r\n<p style="padding: 0; font-size: 13px; line-height: 16px; color: #ffe3a9; margin: 0 0 14px;">You''re receiving this newsletter because you signed up for the ABC Widget Newsletter.</p>\r\n<a href="{$baseurl}{$unsubscribe}">UNSUBSCRIBE</a></td>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #5b9b83 url({$baseurl}uploads/template/bg_footer.png);" bgcolor="#5b9b83">&nbsp;</td>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #5b9b83 url({$baseurl}uploads/template/bg_footer.png);" valign="top" bgcolor="#5b9b83">\r\n<p style="padding: 0; font-size: 13px; line-height: 16px; color: #ffe3a9; margin: 0 0 14px;">123 Some Street<br />City, State<br />99999<br />(147) 789 7745<br /><a style="font-size: 13px; font-weight: normal; text-decoration: underline; color: #fff;" href="http://www.abcwidgets.com/">www.abcwidgets.com</a><br /><a style="font-size: 13px; font-weight: normal; text-decoration: underline; color: #fff;" href="mailto:info@abcwidgets.com">info@abcwidgets.com</a></p>\r\n</td>\r\n<td style="font-family: Helvetica, Arial, sans-serif; background: #5b9b83 url({$baseurl}uploads/template/bg_footer.png);" bgcolor="#5b9b83">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</body>\r\n</html>', '/uploads/harbourcoat_full_1col.jpeg', 'title\r\nissue\r\ndate\r\ntopimage_imgchooser\r\nsubtitle\r\nContent\r\n<articles>\r\ntitle\r\nContent\r\nimg_imgchooser\r\n</articles>', 0, 'a:7:{i:0;s:5:"title";i:1;s:5:"issue";i:2;s:4:"date";i:3;s:19:"topimage_imgchooser";i:4;s:8:"subtitle";i:5;s:7:"Content";s:8:"articles";a:3:{i:0;s:5:"title";i:1;s:7:"Content";i:2;s:14:"img_imgchooser";}}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created`, `last_login`) VALUES
(2, 'admin', '3cb776c5c921753f715b8e7b964246b3c6544b50', '2010-11-20 13:45:34', '2010-11-20 13:45:00');

CREATE TABLE IF NOT EXISTS `importtasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `connection` longtext NOT NULL,
  `query` longtext NOT NULL,
  `fields` longtext NOT NULL,
  `categories` longtext NOT NULL,
  `description` longtext NOT NULL,
  `act` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `forms` ADD `confirm_mail` LONGTEXT NOT NULL ,
ADD `notify_mail` LONGTEXT NOT NULL ,
ADD `notify` BOOLEAN NOT NULL ;
ALTER TABLE `forms` ADD `confirm` BOOLEAN NOT NULL ;
ALTER TABLE `forms` ADD `notify_addresse` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `forms` ADD `confirm_text` LONGTEXT NOT NULL ,
ADD `confirm_title` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `forms` ADD `configuration_id` INT NOT NULL ;
ALTER TABLE `importtasks` ADD `form` INT NOT NULL ;
UPDATE `forms` SET `configuration_id` = 1, `confirm` = 0, `notify` = 0, `notify_addresse` = 'yourmail@gmail.com', `notify_mail` = 'a:2:{s:5:\"title\";s:14:\"New Subscriber\";s:7:\"content\";s:297:\"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html>\r\n<head>\r\n<title>Untitled document</title>\r\n</head>\r\n<body>\r\n<p>First Name: {$first_name}</p>\r\n<p>Last Name: {$last_name}</p>\r\n<p>E-Mail: {$email}</p>\r\n</body>\r\n</html>\";}', `confirm_mail` = 'a:2:{s:5:\"title\";s:25:\"Confirm your subscription\";s:7:\"content\";s:462:\"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html>\r\n<head>\r\n<title>Untitled document</title>\r\n</head>\r\n<body>\r\n<p><a href=\"{$confirm}\"><span style=\"font-size: large;\"><strong>Confirm Subscription</strong></span></a></p>\r\n<p>If you have received this email by mistake simply delete it.<br /> You won\'t be subscribed if you dont click the confirmation link above.</p>\r\n</body>\r\n</html>\";}' WHERE 1=1;
UPDATE `forms` SET `confirm_title` = 'Please click on the link in the confirmation mail' WHERE 1=1;


CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `forever` tinyint(1) NOT NULL,
  `categories` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

ALTER TABLE `mails` ADD `campaign_id` INT NOT NULL DEFAULT '0',
ADD `active` BOOLEAN NOT NULL DEFAULT '1';
ALTER TABLE `mails` ADD `delay` INT NOT NULL ;
ALTER TABLE `campaigns` ADD `sendtoold` BOOLEAN NOT NULL ;
ALTER TABLE `campaigns` ADD `last_check` DATETIME NOT NULL ;
ALTER TABLE `configurations` DROP `wait`;
ALTER TABLE `configurations` ADD `mails_per_time` INT NOT NULL DEFAULT '500',
ADD `time` INT NOT NULL DEFAULT '60';
ALTER TABLE `configurations` ADD `mcount` INT NOT NULL ;

ALTER TABLE `mails` ADD `sendtof` INT NOT NULL DEFAULT '0';
ALTER TABLE `banned_domains` DEFAULT CHARACTER SET utf8;
ALTER TABLE `banned_domains` CHANGE `name` `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `campaigns` DEFAULT CHARACTER SET utf8;
ALTER TABLE `campaigns` CHANGE `description` `description` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `campaigns` CHANGE `categories` `categories` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `categories` DEFAULT CHARACTER SET utf8;
ALTER TABLE `categories` CHANGE `name` `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `categories` CHANGE `description` `description` text CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `categories_subscribers` DEFAULT CHARACTER SET utf8;
ALTER TABLE `configurations` DEFAULT CHARACTER SET utf8;
ALTER TABLE `configurations` CHANGE `name` `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `description` `description` text CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `host` `host` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `username` `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `password` `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `from` `from` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `reply_to` `reply_to` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `inbox_host` `inbox_host` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `inbox_flags` `inbox_flags` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `mailbox` `mailbox` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `inbox_username` `inbox_username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `configurations` CHANGE `inbox_password` `inbox_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` DEFAULT CHARACTER SET utf8;
ALTER TABLE `forms` CHANGE `name` `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `description` `description` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `title` `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `content` `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `selected_categories` `selected_categories` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `thanks_text` `thanks_text` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `style` `style` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `unsubscribe_text` `unsubscribe_text` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `unsubscribe_title` `unsubscribe_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `thanks_title` `thanks_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `forms` CHANGE `confirm_mail` `confirm_mail` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `forms` CHANGE `notify_mail` `notify_mail` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `forms` CHANGE `notify_addresse` `notify_addresse` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `forms` CHANGE `confirm_text` `confirm_text` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `forms` CHANGE `confirm_title` `confirm_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `importtasks` DEFAULT CHARACTER SET utf8;
ALTER TABLE `importtasks` CHANGE `name` `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `importtasks` CHANGE `connection` `connection` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `importtasks` CHANGE `query` `query` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `importtasks` CHANGE `fields` `fields` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `importtasks` CHANGE `categories` `categories` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `importtasks` CHANGE `description` `description` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `importtasks` CHANGE `act` `act` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL  ;
ALTER TABLE `links` DEFAULT CHARACTER SET utf8;
ALTER TABLE `links` CHANGE `url` `url` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `mails` DEFAULT CHARACTER SET utf8;
ALTER TABLE `mails` CHANGE `subject` `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `mails` CHANGE `content_html` `content_html` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `mails` CHANGE `content_text` `content_text` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `mails` CHANGE `final_html` `final_html` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `mails` CHANGE `prepared` `prepared` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL   DEFAULT '0';
ALTER TABLE `mails_categories` DEFAULT CHARACTER SET utf8;
ALTER TABLE `recipients` DEFAULT CHARACTER SET utf8;
ALTER TABLE `subscribers` DEFAULT CHARACTER SET utf8;
ALTER TABLE `subscribers` CHANGE `first_name` `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `subscribers` CHANGE `last_name` `last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `subscribers` CHANGE `mail_adresse` `mail_adresse` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `subscribers` CHANGE `notes` `notes` text CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `subscribers` CHANGE `unsubscribe_code` `unsubscribe_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `templates` DEFAULT CHARACTER SET utf8;
ALTER TABLE `templates` CHANGE `name` `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `templates` CHANGE `content` `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `templates` CHANGE `preview` `preview` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `templates` CHANGE `fields` `fields` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `templates` CHANGE `fields_array` `fields_array` longtext CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `users` DEFAULT CHARACTER SET utf8;
ALTER TABLE `users` CHANGE `username` `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `users` CHANGE `password` `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL   DEFAULT NULL ;
ALTER TABLE `subscribers` ADD `custom1` VARCHAR( 255 ) NOT NULL ,
ADD `custom2` VARCHAR( 255 ) NOT NULL ,
ADD `custom3` VARCHAR( 255 ) NOT NULL ,
ADD `custom4` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `recipients` ADD `country` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `templates` ADD `comment_array` LONGTEXT NOT NULL ;
ALTER TABLE `recipients` ADD `open_count` INT NOT NULL DEFAULT '0';

ALTER TABLE `configurations` DROP `smtp`;
ALTER TABLE `configurations` ADD `delivery` INT NOT NULL DEFAULT '1';
ALTER TABLE `configurations` ADD `aws_access_key` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `configurations` ADD `aws_secret_key` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `configurations` ADD `sendmail_path` VARCHAR( 500 ) NOT NULL;
ALTER TABLE `subscribers` CHANGE `custom1` `custom1` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `subscribers` CHANGE `custom2` `custom2` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `subscribers` CHANGE `custom3` `custom3` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `subscribers` CHANGE `custom4` `custom4` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `mails` CHANGE `delay` `delay` INT( 11 ) NULL ;
DELETE FROM `categories` WHERE `name` IS NULL;
ALTER TABLE `categories` CHANGE `name` `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `users` ADD `level` INT NOT NULL DEFAULT '0';

ALTER TABLE `importtasks` ADD `resubscribe` BOOLEAN NOT NULL; 

ALTER TABLE `mails` ADD `private` BOOLEAN NOT NULL;

ALTER TABLE `configurations` ADD `bounce_to` VARCHAR( 255 ) NOT NULL;