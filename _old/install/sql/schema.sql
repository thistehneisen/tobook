
-- Dumping structure for table EasyCreate.dummy
DROP TABLE IF EXISTS `dummy`;
CREATE TABLE IF NOT EXISTS `dummy` (
  `num` int(11) NOT NULL default '0',
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_cms
DROP TABLE IF EXISTS `tbl_cms`;
CREATE TABLE IF NOT EXISTS `tbl_cms` (
  `section_id` int(11) NOT NULL auto_increment,
  `section_name` varchar(250) NOT NULL,
  `section_help` int(11) NOT NULL COMMENT '1=>true,0=>false',
  `section_title` varchar(255) NOT NULL,
  `section_content` text NOT NULL,
  `section_price` float NOT NULL,
  `section_status` int(11) NOT NULL COMMENT '1=>Active,2=>Inactive,3=>Deleted',
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`section_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_cms_bkp
DROP TABLE IF EXISTS `tbl_cms_bkp`;
CREATE TABLE IF NOT EXISTS `tbl_cms_bkp` (
  `page` varchar(250) NOT NULL,
  `cmsContent` text NOT NULL,
  PRIMARY KEY  (`page`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_country
DROP TABLE IF EXISTS `tbl_country`;
CREATE TABLE IF NOT EXISTS `tbl_country` (
  `tc_id` bigint(20) NOT NULL auto_increment,
  `tc_code` varchar(20) default NULL,
  `tc_name` varchar(250) default NULL,
  `tc_status` char(1) default 'A',
  PRIMARY KEY  (`tc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- Dumping structure for table EasyCreate.tbl_editor_apps
DROP TABLE IF EXISTS `tbl_editor_apps`;
CREATE TABLE IF NOT EXISTS `tbl_editor_apps` (
  `app_id` int(11) NOT NULL auto_increment,
  `app_cat` int(3) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `app_class` varchar(100) NOT NULL,
  `app_title` varchar(255) NOT NULL,
  `app_image` varchar(255) NOT NULL,
  `app_status` int(1) NOT NULL COMMENT '1-> active; 0-> inactive',
  `app_alias` varchar(250) NOT NULL,
  PRIMARY KEY  (`app_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_editor_apps_params
DROP TABLE IF EXISTS `tbl_editor_apps_params`;
CREATE TABLE IF NOT EXISTS `tbl_editor_apps_params` (
  `param_id` int(11) NOT NULL auto_increment,
  `app_id` int(11) NOT NULL,
  `param_title` varchar(255) NOT NULL,
  `param_img` varchar(255) NOT NULL,
  `param_class` varchar(100) NOT NULL,
  `param_status` int(1) NOT NULL COMMENT '0-> active; 1 -> disabled',
  `param_mandate` int(1) NOT NULL COMMENT '0-> not mandate ; 1-> mandatory',
  `param_type` varchar(100) NOT NULL COMMENT 'text box, radio, select, text area',
  PRIMARY KEY  (`param_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_files
DROP TABLE IF EXISTS `tbl_files`;
CREATE TABLE IF NOT EXISTS `tbl_files` (
  `nfile_id` int(8) NOT NULL auto_increment,
  `vfile_name` varchar(100) default NULL,
  `nsite_id` int(8) default NULL,
  `vlocation` varchar(200) default NULL,
  `ddate` datetime default NULL,
  `vremote_dir` varchar(20) default NULL,
  PRIMARY KEY  (`nfile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_gallery
DROP TABLE IF EXISTS `tbl_gallery`;
CREATE TABLE IF NOT EXISTS `tbl_gallery` (
  `nimg_id` int(8) NOT NULL auto_increment,
  `vimg_name` varchar(100) default NULL,
  `vimg_url` varchar(100) default NULL,
  `ngcat_id` int(8) default NULL,
  PRIMARY KEY  (`nimg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_gallery_category
DROP TABLE IF EXISTS `tbl_gallery_category`;
CREATE TABLE IF NOT EXISTS `tbl_gallery_category` (
  `ngcat_id` int(8) NOT NULL auto_increment,
  `vcat_name` varchar(50) default NULL,
  `vcat_desc` varchar(100) default NULL,
  PRIMARY KEY  (`ngcat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_joomla_template_files
DROP TABLE IF EXISTS `tbl_joomla_template_files`;
CREATE TABLE IF NOT EXISTS `tbl_joomla_template_files` (
  `njoomla_template_file_id` int(11) NOT NULL auto_increment,
  `ntemplate_mast_id` int(11) NOT NULL,
  `vjoomla_template_file` varchar(255) NOT NULL,
  `ejoomla_template_file_type` enum('J','C') NOT NULL COMMENT 'J-js,C-css',
  PRIMARY KEY  (`njoomla_template_file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_logo
DROP TABLE IF EXISTS `tbl_logo`;
CREATE TABLE IF NOT EXISTS `tbl_logo` (
  `nlogo_id` int(8) NOT NULL auto_increment,
  `vlogo_url` varchar(200) default NULL,
  PRIMARY KEY  (`nlogo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




-- Dumping structure for table EasyCreate.tbl_lookup
DROP TABLE IF EXISTS `tbl_lookup`;
CREATE TABLE IF NOT EXISTS `tbl_lookup` (
  `vname` varchar(100) NOT NULL default '',
  `vvalue` text,
  PRIMARY KEY  (`vname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_payment
DROP TABLE IF EXISTS `tbl_payment`;
CREATE TABLE IF NOT EXISTS `tbl_payment` (
  `npayment_id` int(8) NOT NULL auto_increment,
  `ntempsite_id` int(8) default '0',
  `nsite_id` int(8) default '0',
  `nuser_id` int(8) default NULL,
  `namount` float default NULL,
  `ddate` datetime default NULL,
  `vpayment_type` varchar(10) default NULL,
  `vtxn_id` varchar(100) default NULL,
  `vuniqid` varchar(50) default NULL,
  PRIMARY KEY  (`npayment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_payment_bkp
DROP TABLE IF EXISTS `tbl_payment_bkp`;
CREATE TABLE IF NOT EXISTS `tbl_payment_bkp` (
  `npayment_id` int(8) NOT NULL auto_increment,
  `ntempsite_id` int(8) default '0',
  `nsite_id` int(8) default '0',
  `nuser_id` int(8) default NULL,
  `namount` float default NULL,
  `ddate` datetime default NULL,
  `vpayment_type` varchar(10) default NULL,
  `vtxn_id` varchar(100) default NULL,
  `vuniqid` varchar(50) default NULL,
  PRIMARY KEY  (`npayment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_site_mast
DROP TABLE IF EXISTS `tbl_site_mast`;
CREATE TABLE IF NOT EXISTS `tbl_site_mast` (
  `nsite_id` int(8) NOT NULL auto_increment,
  `vsite_name` varchar(50) default NULL,
  `nuser_id` int(8) default NULL,
  `ncat_id` int(11) NOT NULL,
  `ntemplate_id` int(8) default NULL,
  `ntheme_id` int(11) NOT NULL,
  `vtype` varchar(20) default NULL,
  `vtitle` varchar(100) default NULL,
  `vmeta_description` varchar(250) default NULL,
  `vmeta_key` varchar(250) default NULL,
  `vlogo` varchar(100) default NULL,
  `vlogo_name` varchar(100) NOT NULL,
  `vcompany` varchar(100) default NULL,
  `vcompany_style` varchar(255) NOT NULL,
  `vcaption` varchar(100) default NULL,
  `vcaption_style` varchar(255) NOT NULL,
  `vlinks` text,
  `vcolor` varchar(10) default NULL,
  `vemail` varchar(100) default NULL,
  `ddate` datetime default NULL,
  `vdelstatus` smallint(1) default NULL,
  `vsub_logo` varchar(100) default NULL,
  `vsub_caption` varchar(100) default NULL,
  `vsub_company` varchar(100) default NULL,
  `vsub_sitelinks` text,
  `ndel_status` tinyint(4) default '0',
  `is_published` int(11) NOT NULL COMMENT '0=>Draft,1=>Published',
  `fb_page_id` bigint(20) NOT NULL,
  `google_analytics_code` varchar(255) NOT NULL,
  PRIMARY KEY  (`nsite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_site_pages
DROP TABLE IF EXISTS `tbl_site_pages`;
CREATE TABLE IF NOT EXISTS `tbl_site_pages` (
  `nsp_id` int(8) NOT NULL auto_increment,
  `nsite_id` int(8) default NULL,
  `vpage_name` varchar(100) default NULL,
  `vpage_title` varchar(100) default NULL,
  `vpage_link` varchar(255) NOT NULL,
  `vpage_type` varchar(20) default NULL,
  `vtype` varchar(20) default NULL,
  PRIMARY KEY  (`nsp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `tbl_site_details`;
CREATE TABLE `tbl_site_details` (
`site_details_id` INT(10) NOT NULL AUTO_INCREMENT,
`site_id` INT(10) NOT NULL DEFAULT '0',
`site_data` TEXT NOT NULL,
`created_on` DATETIME NOT NULL,
PRIMARY KEY (`site_details_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM;


-- Dumping structure for table EasyCreate.tbl_site_page_contents
DROP TABLE IF EXISTS `tbl_site_page_contents`;
CREATE TABLE IF NOT EXISTS `tbl_site_page_contents` (
  `site_page_content_id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL,
  `panel_id` int(11) NOT NULL,
  `parent_panel_id` int(11) NOT NULL,
  `panel_content` text NOT NULL,
  `is_app` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`site_page_content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_site_page_external_contents
DROP TABLE IF EXISTS `tbl_site_page_external_contents`;
CREATE TABLE IF NOT EXISTS `tbl_site_page_external_contents` (
  `site_page_external_content_id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL,
  `external_widget_type_id` int(11) NOT NULL,
  `external_widget_panel_position` int(11) NOT NULL,
  `external_widget_id` int(11) NOT NULL,
  `external_widget_content` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`site_page_external_content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_template_category
DROP TABLE IF EXISTS `tbl_template_category`;
CREATE TABLE IF NOT EXISTS `tbl_template_category` (
  `ncat_id` int(8) NOT NULL auto_increment,
  `vcat_name` varchar(50) default NULL,
  `vcat_desc` varchar(100) default NULL,
  `vcat_thumpnail` varchar(100) default NULL,
  PRIMARY KEY  (`ncat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_template_externalboxes
DROP TABLE IF EXISTS `tbl_template_externalboxes`;
CREATE TABLE IF NOT EXISTS `tbl_template_externalboxes` (
  `box_id` int(11) NOT NULL auto_increment,
  `box_title` varchar(255) NOT NULL,
  `box_code` text NOT NULL,
  `box_type` int(1) NOT NULL,
  `box_status` tinyint(1) NOT NULL COMMENT '0-> active; 1-> inactive; ',
  PRIMARY KEY  (`box_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


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


-- Dumping structure for table EasyCreate.tbl_tempsite_mast
DROP TABLE IF EXISTS `tbl_tempsite_mast`;
CREATE TABLE IF NOT EXISTS `tbl_tempsite_mast` (
  `ntempsite_id` int(8) NOT NULL auto_increment,
  `vsite_name` varchar(50) default NULL,
  `nuser_id` int(8) default NULL,
  `ntemplate_id` int(8) default NULL,
  `vtype` varchar(20) default NULL,
  `vtitle` varchar(100) default NULL,
  `vmeta_description` varchar(250) default NULL,
  `vmeta_key` varchar(250) default NULL,
  `vlogo` varchar(100) default NULL,
  `vcompany` varchar(100) default NULL,
  `vcaption` varchar(100) default NULL,
  `vlinks` text,
  `vcolor` varchar(10) default NULL,
  `vemail` varchar(100) default NULL,
  `vsub_logo` varchar(100) default NULL,
  `vsub_caption` varchar(100) default NULL,
  `vsub_company` varchar(100) default NULL,
  `vsub_sitelinks` text,
  `ddate` datetime default NULL,
  `vdelstatus` smallint(1) default NULL,
  PRIMARY KEY  (`ntempsite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_tempsite_pages
DROP TABLE IF EXISTS `tbl_tempsite_pages`;
CREATE TABLE IF NOT EXISTS `tbl_tempsite_pages` (
  `ntempsp_id` int(8) NOT NULL auto_increment,
  `ntempsite_id` int(8) default NULL,
  `vpage_name` varchar(100) default NULL,
  `vpage_title` varchar(100) default NULL,
  `vpage_type` varchar(20) default NULL,
  `vtype` varchar(20) default NULL,
  PRIMARY KEY  (`ntempsp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_useruploadimages
DROP TABLE IF EXISTS `tbl_useruploadimages`;
CREATE TABLE IF NOT EXISTS `tbl_useruploadimages` (
  `img_id` bigint(15) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `added_on` varchar(30) NOT NULL,
  `img_type` int(2) NOT NULL,
  `status` int(2) NOT NULL COMMENT '0->show; 1->hide',
  PRIMARY KEY  (`img_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- Dumping structure for table EasyCreate.tbl_user_mast
DROP TABLE IF EXISTS `tbl_user_mast`;
CREATE TABLE IF NOT EXISTS `tbl_user_mast` (
  `nuser_id` int(8) NOT NULL auto_increment,
  `vuser_login` varchar(50) default NULL,
  `vuser_password` varchar(50) default NULL,
  `vuser_name` varchar(100) default NULL,
  `vuser_lastname` varchar(100) default NULL,
  `vuser_address1` varchar(200) default NULL,
  `vuser_address2` varchar(200) default NULL,
  `vcity` varchar(100) default NULL,
  `vstate` varchar(100) default NULL,
  `vzip` varchar(20) default NULL,
  `vcountry` varchar(50) default NULL,
  `vuser_email` varchar(100) default NULL,
  `vuser_phone` varchar(30) default NULL,
  `vuser_fax` varchar(30) default NULL,
  `duser_join` datetime default NULL,
  `vuser_style` varchar(20) default NULL,
  `naff_id` int(11) default '0',
  `vdel_status` int(2) default NULL,
  PRIMARY KEY  (`nuser_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
