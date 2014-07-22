-- 2014-07-03
CREATE TABLE `tbl_auto_email` (                          
  `auto_email` int(11) NOT NULL AUTO_INCREMENT,          
  `owner` int(11) NOT NULL,                              
  `title` varchar(128) DEFAULT NULL,                     
  `email_campaign` int(11) NOT NULL,                     
  `plan_group_code` varchar(2) NOT NULL,                 
  `cnt_previous_booking` varchar(11) DEFAULT NULL,       
  `days_previous_booking` varchar(11) DEFAULT NULL,      
  `created_time` varchar(19) NOT NULL,                   
  `updated_time` varchar(19) NOT NULL,                   
  PRIMARY KEY (`auto_email`)                             
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=UTF8;

CREATE TABLE `tbl_auto_email_history` (                  
  `auto_email_history` int(11) NOT NULL AUTO_INCREMENT,  
  `owner` int(11) NOT NULL,                              
  `email_campaign` int(11) NOT NULL,                     
  `email_address` varchar(64) NOT NULL,                  
  `plan_group_code` varchar(2) DEFAULT NULL,             
  `created_time` varchar(19) NOT NULL,                   
  `updated_time` varchar(19) NOT NULL,                   
  PRIMARY KEY (`auto_email_history`)                     
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

-- 2014-07-07

CREATE TABLE `tbl_marketing_auto` (                      
  `marketing_auto` int(11) NOT NULL AUTO_INCREMENT,      
  `owner` int(11) NOT NULL,                              
  `title` varchar(256) NOT NULL,                         
  `email_campaign` int(11) DEFAULT NULL,                 
  `sms_content` varchar(512) DEFAULT NULL,               
  `type` varchar(16) NOT NULL COMMENT 'email, sms',      
  `plan_group_code` varchar(2) NOT NULL,                 
  `cnt_previous_booking` varchar(4) DEFAULT NULL,        
  `days_previous_booking` varchar(4) DEFAULT NULL,       
  `created_time` varchar(19) NOT NULL,                   
  `updated_time` varchar(19) NOT NULL,                   
  PRIMARY KEY (`marketing_auto`)                         
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=UTF8;

CREATE TABLE `tbl_marketing_auto_history` (                  
  `marketing_auto_history` int(11) NOT NULL AUTO_INCREMENT,  
  `marketing_auto` int(11) NOT NULL,                         
  `destination` varchar(64) NOT NULL,                        
  `created_time` varchar(19) NOT NULL,                       
  `updated_time` varchar(19) NOT NULL,                       
  PRIMARY KEY (`marketing_auto_history`)                     
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

-- 2014-07-16
CREATE TABLE `tbl_marketing_group` (                  
   `marketing_group` int(11) NOT NULL AUTO_INCREMENT,  
   `owner` int(11) NOT NULL,                           
   `group_name` varchar(128) NOT NULL,                 
   `created_time` varchar(19) NOT NULL,                
   `updated_time` varchar(19) NOT NULL,                
   PRIMARY KEY (`marketing_group`)                     
 ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;
 
CREATE TABLE `tbl_marketing_group_member` (                  
  `marketing_group_member` int(11) NOT NULL AUTO_INCREMENT,  
  `marketing_group` int(11) NOT NULL,                        
  `plan_group_code` varchar(8) NOT NULL,                     
  `email` varchar(128) DEFAULT NULL,                         
  `phone` varchar(64) DEFAULT NULL,                          
  `customerId` int(11) DEFAULT NULL,                         
  `created_time` varchar(19) NOT NULL,                       
  `updated_time` varchar(19) NOT NULL,                       
  PRIMARY KEY (`marketing_group_member`)                     
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;
