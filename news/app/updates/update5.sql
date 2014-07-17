ALTER TABLE `templates` ADD `comment_array` LONGTEXT NOT NULL ;
ALTER TABLE `recipients` ADD `open_count` INT NOT NULL DEFAULT '0';