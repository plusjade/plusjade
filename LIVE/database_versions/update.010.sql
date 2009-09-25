

ALTER TABLE `showrooms` CHANGE `home_cat` `home_cat` INT( 9 ) NOT NULL ;

-- add cache field to texts so we can store parsed token texts 

ALTER TABLE `texts` ADD `cache` TEXT NOT NULL AFTER `body` ;
UPDATE texts SET cache=body;

