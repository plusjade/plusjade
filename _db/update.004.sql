ALTER TABLE `themes` ADD `enabled` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'yes';
UPDATE `plusjade`.`themes` SET `enabled` = 'no' WHERE `themes`.`id` =3 LIMIT 1 ;
UPDATE `plusjade`.`themes` SET `enabled` = 'no' WHERE `themes`.`id` =6 LIMIT 1 ;
UPDATE `plusjade`.`themes` SET `enabled` = 'no' WHERE `themes`.`id` =7 LIMIT 1 ;
UPDATE `plusjade`.`themes` SET `enabled` = 'no' WHERE `themes`.`id` =8 LIMIT 1 ;

ALTER TABLE `themes` ADD `image_ext` VARCHAR( 3 ) NOT NULL;
UPDATE `plusjade`.`themes` SET `image_ext` = 'gif' WHERE `themes`.`id` =1 LIMIT 1 ;
UPDATE `plusjade`.`themes` SET `image_ext` = 'gif' WHERE `themes`.`id` =2 LIMIT 1 ;
UPDATE `plusjade`.`themes` SET `image_ext` = 'jpg' WHERE `themes`.`id` =4 LIMIT 1 ;
UPDATE `plusjade`.`themes` SET `image_ext` = 'gif' WHERE `themes`.`id` =5 LIMIT 1 ;
UPDATE `plusjade`.`themes` SET `image_ext` = 'gif' WHERE `themes`.`id` =9 LIMIT 1 ;

INSERT INTO `plusjade`.`themes` (`id`, `name`, `enabled`, `image_ext`) VALUES (NULL, 'natural_essence', 'yes', 'jpg');

ALTER TABLE `showrooms` ADD `home_cat` VARCHAR( 50 ) NOT NULL AFTER `root_id` 


CREATE TABLE `plusjade`.`version` (
`at` INT( 5 ) NOT NULL
) ENGINE = MYISAM ;

INSERT INTO `plusjade`.`version` (`at`) VALUES ('004');
 ALTER TABLE `version` CHANGE `at` `at` FLOAT( 9 ) NOT NULL  