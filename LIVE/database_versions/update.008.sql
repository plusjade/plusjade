ALTER TABLE `albums` CHANGE `images` `images` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

UPDATE `plusjade`.`system_tools` SET `name` = 'Format' WHERE `system_tools`.`id` =4 LIMIT 1 ;

RENAME TABLE tugboats TO formats;
RENAME TABLE tugboat_items TO format_items;
ALTER TABLE `format_items` CHANGE `tugboat_id` `format_id` INT( 9 ) UNSIGNED NOT NULL ;
ALTER TABLE `format_items` ADD `image` VARCHAR( 155 ) NOT NULL AFTER `title` ;
ALTER TABLE `format_items` ADD `album` INT NOT NULL AFTER `image` ;
ALTER TABLE `formats` CHANGE `type` `view` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

-- remember to delete the system tool row for FAQ

DROP TABLE `faqs`;
DROP TABLE `faq_items`;
DROP TABLE `slide_panels`;
DROP TABLE `slide_panel_items`;

ALTER TABLE `format_items` ADD `type` VARCHAR( 50 ) NOT NULL AFTER `fk_site` ;

-- remember to delete the system tool row for contact
DROP TABLE `contacts`;
DROP TABLE `contact_items`;
DROP TABLE `contact_types`;

DELETE FROM system_tools WHERE id ='5';
-- delete the data DELETE FROM pages_tools WHERE system_tool_id ='5'

CREATE TABLE IF NOT EXISTS `system_tool_views` (
  `system_tool_id` int(3) NOT NULL,
  `view` varchar(55) NOT NULL,
  `desc` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_tool_views`
--

INSERT INTO `system_tool_views` (`system_tool_id`, `view`, `desc`) VALUES
(4, 'people', 'Organize people using this format.'),
(4, 'faqs', 'Create a list of frequently asked questions'),
(4, 'contacts', 'Create a list of common methods used to contact you or your business.');

ALTER TABLE `system_tool_views` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;

RENAME TABLE system_tool_views TO system_tool_types;

ALTER TABLE `system_tool_types` CHANGE `view` `type` VARCHAR( 55 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
