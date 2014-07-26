
-- Dumping structure for table EasyCreate.tbl_template_mast
DROP TABLE IF EXISTS `tbl_template_mast`;
CREATE TABLE IF NOT EXISTS `tbl_template_mast` (
  `ntemplate_mast` bigint(15) NOT NULL auto_increment,
  `ncat_id` int(8) default NULL,
  `temp_name` varchar(255) NOT NULL,
  `vthumpnail` varchar(100) default NULL,
  `vtype` varchar(50) default NULL,
  `vtitle` varchar(100) default NULL,
  `vmeta_description` varchar(250) default NULL,
  `vmeta_key` varchar(250) default NULL,
  `vlogo` varchar(100) default NULL,
  `vcompany` varchar(100) default NULL,
  `vcaption` varchar(100) default NULL,
  `vlink_type` varchar(20) default NULL,
  `vlinks` text,
  `vlink_separator` varchar(100) NOT NULL default '',
  `veditable` longtext,
  `vsublink_type` varchar(20) default NULL,
  `vsub_links` text,
  `vsublink_separator` varchar(100) default NULL,
  `vsub_editable` longtext,
  `vhome_url` varchar(100) default NULL,
  `vsub_url` varchar(100) default NULL,
  `vcolor` varchar(20) default NULL,
  `ddate` datetime default NULL,
  `vdelstatus` smallint(1) default NULL,
  `ntemplate_type` enum('J','N') NOT NULL default 'N' COMMENT 'J-joomla,N-normal',
  PRIMARY KEY  (`ntemplate_mast`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_template_pages
DROP TABLE IF EXISTS `tbl_template_pages`;
CREATE TABLE IF NOT EXISTS `tbl_template_pages` (
  `page_id` int(12) NOT NULL auto_increment,
  `temp_id` int(12) NOT NULL,
  `panel_ref` int(12) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `page_alias` VARCHAR(255) NOT NULL,
  PRIMARY KEY  (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_template_panel
DROP TABLE IF EXISTS `tbl_template_panel`;
CREATE TABLE IF NOT EXISTS `tbl_template_panel` (
  `panel_id` bigint(11) NOT NULL auto_increment,
  `temp_id` varchar(30) NOT NULL,
  `page_type` tinyint(2) NOT NULL default '1' COMMENT '1 -> site main page; 2-> sub pages',
  `panel_type` varchar(100) NOT NULL,
  `panel_html` text NOT NULL,
  `temp_page_id` int(12) NOT NULL,
  PRIMARY KEY  (`panel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_template_themes
DROP TABLE IF EXISTS `tbl_template_themes`;
CREATE TABLE IF NOT EXISTS `tbl_template_themes` (
  `theme_id` bigint(11) NOT NULL auto_increment,
  `temp_id` bigint(11) NOT NULL,
  `theme_name` varchar(100) NOT NULL,
  `theme_style` varchar(255) NOT NULL,
  `theme_color` varchar(10) NOT NULL COMMENT 'Hex code of the color',
  `theme_image_thumb` varchar(255) NOT NULL,
  `theme_image_home` varchar(255) NOT NULL,
  `theme_image_sub` varchar(255) NOT NULL,
  `theme_status` tinyint(1) NOT NULL COMMENT '1-> active; 0 -> in active',
  `theme_default` int(1) NOT NULL COMMENT '1-> active ; 0-> inactive',
  PRIMARY KEY  (`theme_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


