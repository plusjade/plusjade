
-- new format type "form"

INSERT INTO `plusjade`.`system_tool_types` (
`id` ,
`system_tool_id` ,
`type` ,
`view` ,
`desc`
)
VALUES (
NULL , '4', 'forms', 'list', 'create a custom html form to be emailed to the address of your choice.'
);


-- update the format item tables for more generic purpose

ALTER TABLE `format_items` CHANGE `image` `meta` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;



ALTER TABLE `showrooms` CHANGE `home_cat` `home_cat` INT( 9 ) NOT NULL ;

-- add cache field to texts so we can store parsed token texts 

ALTER TABLE `texts` ADD `cache` TEXT NOT NULL AFTER `body` ;
UPDATE texts SET cache=body;

ALTER TABLE `version` CHANGE `at` `at` INT( 9 ) NOT NULL  ;
UPDATE `staging`.`version` SET `at` = '10' 

