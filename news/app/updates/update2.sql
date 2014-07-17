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
