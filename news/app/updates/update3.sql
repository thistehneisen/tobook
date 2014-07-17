
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