
-- updating tools to interact and instance better throughout the system.

ALTER TABLE `pages_tools` CHANGE `tool_id` `tool_id` INT( 7 ) UNSIGNED NOT NULL ;
ALTER TABLE `pages_tools` DROP PRIMARY KEY   ;
ALTER TABLE `pages_tools` ADD `id` INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;

ALTER TABLE `tools` CHANGE `tool_id` `parent_id` INT( 7 ) UNSIGNED NOT NULL ;

-- create reviews schema

create TABLE reviews LIKE formats;


-- add reviews to system tool list

INSERT INTO `plusjade`.`system_tools` (
`id` ,
`name` ,
`protected` ,
`enabled` ,
`visible` ,
`type` ,
`desc`
)
VALUES (NULL , 'Reviews', 'yes', 'yes', 'yes', 'reviews', 'Set up reviews on your site.');



INSERT INTO `plusjade`.`system_tool_types` (
`id` ,
`system_tool_id` ,
`type` ,
`view` ,
`desc`
)
VALUES (NULL , '12', 'reviews', 'list', 'lists reviews normally.');


-- add campaign monitor id field to plusjade user accounts.

ALTER TABLE `account_users` ADD `cm_id` INT( 35 ) NOT NULL AFTER `token` ;
ALTER TABLE `account_users` CHANGE `cm_id` `cm_id` VARCHAR( 35 ) NOT NULL ;

-- add a new page_builder : newsletter

INSERT INTO `plusjade`.`system_tools` (
`id` ,
`name` ,
`protected` ,
`enabled` ,
`visible` ,
`type` ,
`desc`
)
VALUES (
NULL , 'Newsletter', 'yes', 'yes', 'yes', 'newsletters', 'manage an email newsletter'
);


INSERT INTO `plusjade`.`system_tool_types` (`id`, `system_tool_id`, `type`, `view`, `desc`) VALUES (NULL, '13', 'newsletters', 'stock', 'blah');


CREATE TABLE newsletters LIKE formats;




-- Table structure for table `review_items`
--

CREATE TABLE IF NOT EXISTS `review_items` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `review_id` int(9) unsigned NOT NULL,
  `fk_site` int(9) unsigned NOT NULL,
  `account_user_id` int(9) NOT NULL,
  `name` varchar(155) NOT NULL,
  `image` varchar(155) NOT NULL,
  `body` text NOT NULL,
  `rating` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


UPDATE `plusjade`.`version` SET `at` = '009';





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











