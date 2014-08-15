-- 2014-07-21
DROP TABLE IF EXISTS `tbl_loyalty_consumer`;
CREATE TABLE `tbl_loyalty_consumer` (                  
	`loyalty_consumer` int(11) NOT NULL AUTO_INCREMENT,  
	`owner` int(11) NOT NULL,                            
	`first_name` varchar(64) DEFAULT NULL,               
	`last_name` varchar(64) DEFAULT NULL,                
	`email` varchar(64) DEFAULT NULL,                    	
	`phone` varchar(32) DEFAULT NULL,                   
	`address1` varchar(64) DEFAULT NULL,                 
	`city` varchar(64) DEFAULT NULL,                     
	`created_time` varchar(19) NOT NULL,                 
	`updated_time` varchar(19) NOT NULL,                 
	PRIMARY KEY (`loyalty_consumer`)                     
  ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

DROP TABLE IF EXISTS `tbl_loyalty_stamp`;
CREATE TABLE `tbl_loyalty_stamp` (                  
	 `loyalty_stamp` int(11) NOT NULL AUTO_INCREMENT,  
	 `owner` int(11) NOT NULL,                         
	 `stamp_name` varchar(64) NOT NULL,                
	 `cnt_required` int(11) NOT NULL,                  
	 `cnt_free` int(11) NOT NULL,                      
	 `valid_yn` char(1) NOT NULL DEFAULT 'Y',          
	 `created_time` varchar(19) NOT NULL,              
	 `updated_time` varchar(19) NOT NULL,              
	 PRIMARY KEY (`loyalty_stamp`)                     
   ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

DROP TABLE IF EXISTS `tbl_loyalty_consumer_stamp`;
CREATE TABLE `tbl_loyalty_consumer_stamp` (                  
  `loyalty_consumer_stamp` int(11) NOT NULL AUTO_INCREMENT,  
  `loyalty_consumer` int(11) NOT NULL,                       
  `loyalty_stamp` int(11) NOT NULL,                          
  `created_time` varchar(19) NOT NULL,                       
  `updated_time` varchar(19) NOT NULL,                       
  PRIMARY KEY (`loyalty_consumer_stamp`)                     
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

DROP TABLE IF EXISTS `tbl_loyalty_point`;
 CREATE TABLE `tbl_loyalty_point` (                  
 `loyalty_point` int(11) NOT NULL AUTO_INCREMENT,  
 `owner` int(11) NOT NULL,                         
 `point_name` varchar(64) NOT NULL,                
 `score_required` int(11) NOT NULL,                
 `discount` int(11) NOT NULL,                      
 `valid_yn` char(1) NOT NULL DEFAULT 'Y',          
 `created_time` varchar(19) NOT NULL,              
 `updated_time` varchar(19) NOT NULL,              
 PRIMARY KEY (`loyalty_point`)                     
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

DROP TABLE IF EXISTS `tbl_loyalty_consumer_point`;
CREATE TABLE `tbl_loyalty_consumer_point` (                  
  `loyalty_consumer_point` int(11) NOT NULL AUTO_INCREMENT,  
  `loyalty_consumer` int(11) NOT NULL,                       
  `loyalty_point` int(11) NOT NULL,                          
  `score` int(11) NOT NULL,                                  
  `created_time` varchar(19) NOT NULL,                       
  `updated_time` varchar(19) NOT NULL,                       
  PRIMARY KEY (`loyalty_consumer_point`)                     
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

-- 2014-07-23
alter table `tbl_loyalty_consumer` add column `current_score` int  DEFAULT '0' NOT NULL  after `city`;

-- 2014-07-25
alter table `tbl_loyalty_consumer_stamp` add column `cnt_used` int   NOT NULL  after `loyalty_stamp`;
alter table `tbl_loyalty_consumer_stamp` add column `cnt_free` int   NOT NULL  after `cnt_used`;

-- 2014-08-08
alter table `tbl_loyalty_stamp` add column `auto_add_yn` char (1) DEFAULT 'N' NOT NULL  after `cnt_free`;