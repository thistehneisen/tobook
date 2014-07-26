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