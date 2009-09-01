
-- make sure albums can handle long text data.
ALTER TABLE `albums` CHANGE `images` `images` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;


-- update format to the system_tools list.
UPDATE `plusjade`.`system_tools` SET `name` = 'Format' WHERE `system_tools`.`id` =4 LIMIT 1 ;


-- drop tools we no longer are using.
DROP TABLE `faqs`;
DROP TABLE `faq_items`;

DROP TABLE `slide_panels`;
DROP TABLE `slide_panel_items`;

DROP TABLE `contacts`;
DROP TABLE `contact_items`;
DROP TABLE `contact_types`;

-- delete the system tool row for FAQ, slide_panel, contact (faq has been replaced by formats)
DELETE FROM system_tools WHERE id in (5,6);

-- delete the data from pages_tools? //DELETE FROM pages_tools WHERE system_tool_id ='5'


-- add the new system_tool_types logic.

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



-- alter tables to include type and view fields.

ALTER TABLE `accounts` ADD `type` VARCHAR( 25 ) NOT NULL AFTER `name` ;
ALTER TABLE `albums` ADD `type` VARCHAR( 25 ) NOT NULL AFTER `name`; 
ALTER TABLE `blogs` ADD `type` VARCHAR( 25 ) NOT NULL AFTER `name`; 
ALTER TABLE `calendars` ADD `type` VARCHAR( 25 ) NOT NULL AFTER `name`; 
ALTER TABLE `forums` ADD `type` VARCHAR( 25 ) NOT NULL AFTER `name`; 
ALTER TABLE `navigations` ADD `type` VARCHAR( 25 ) NOT NULL AFTER `title` ;
ALTER TABLE `showrooms` ADD `type` VARCHAR( 25 ) NOT NULL AFTER `name`; 
ALTER TABLE `navigations` CHANGE `title` `name` VARCHAR( 80 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
ALTER TABLE `system_tools` ADD `type` VARCHAR( 30 ) NOT NULL COMMENT 'default' AFTER `visible` ;
ALTER TABLE `texts` ADD `view` VARCHAR( 55 ) NOT NULL AFTER `type` ;


-- drop uneeded tables.

DROP TABLE `menu_items`;
DROP TABLE `menus`;




--
-- Table structure for table `formats`
--

CREATE TABLE IF NOT EXISTS `formats` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `type` varchar(50) NOT NULL,
  `view` varchar(50) NOT NULL,
  `params` varchar(100) NOT NULL,
  `attributes` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `format_items`
--

CREATE TABLE IF NOT EXISTS `format_items` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `format_id` int(9) unsigned NOT NULL,
  `fk_site` int(9) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(155) NOT NULL,
  `image` varchar(155) NOT NULL,
  `album` int(11) NOT NULL,
  `body` text NOT NULL,
  `position` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



-- UPDATES TO THE tools, pages, pages_tool schema
-- this centralizes the tool instances and places them in pages by reference only
-- this way one tool can be on multiple pages, it has no physical attachment to a page.

--
-- Table structure for table `tools`
--

CREATE TABLE IF NOT EXISTS `tools` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `system_tool_id` int(2) unsigned NOT NULL,
  `tool_id` int(7) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- backup our data.
RENAME TABLE pages_tools TO pages_tools_bak;


--
-- Table structure for table `pages_tools`
--

CREATE TABLE IF NOT EXISTS `pages_tools` (
  `tool_id` int(7) unsigned NOT NULL,
  `page_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `container` int(1) unsigned NOT NULL DEFAULT '1',
  `position` int(3) NOT NULL COMMENT 'can be neg.',
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- REMEMBER:: I need to manually transfer over the old pages_tools data
-- to tools, and the new pages_tools.



-- update the version
UPDATE `version` SET `at` = '008';
