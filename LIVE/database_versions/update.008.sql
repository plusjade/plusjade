
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

--
-- Table structure for table `system_tool_types`
--

CREATE TABLE IF NOT EXISTS `system_tool_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_tool_id` int(3) NOT NULL,
  `type` varchar(55) NOT NULL,
  `view` varchar(50) NOT NULL COMMENT 'default',
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `system_tool_types`
--

INSERT INTO `system_tool_types` (`id`, `system_tool_id`, `type`, `view`, `desc`) VALUES
(1, 4, 'people', 'filmstrip', 'Organize people using this format.'),
(2, 4, 'faqs', 'simple', 'Create a list of frequently asked questions'),
(3, 4, 'contacts', 'list', 'Create a list of common methods used to contact you or your business.'),
(4, 1, 'basic', 'stock', 'Insert basic textual content into your page.'),
(5, 2, 'images', 'lightbox', 'Create photo albums.'),
(6, 3, 'display', 'list', 'This showroom displays products or items but does not have any shopping cart or ecommerce support. It is meant to catalogue and display items only.'),
(7, 7, 'small', 'list', 'This smaller calendar shows which dates have events on them with a click to display those events.'),
(8, 8, 'lists', 'stock', 'Create nestable lists. Lists can include text, page links, email links, or external links.'),
(9, 8, 'menus', 'stock', 'Menus automatically create a site map of all your sites pages.'),
(10, 9, 'blogs', 'stock', 'a basic blog'),
(11, 10, 'accounts', 'stock', 'the default basic accounts tool.'),
(12, 11, 'forums', 'stock', 'a basic forum with voting functionality.'),
(13, 4, 'tabs', 'stock', 'Organize content into a tab interface.');


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
ALTER TABLE `texts` ADD `type` VARCHAR( 55 ) NOT NULL AFTER `name` ;
-- ALTER TABLE `texts` ADD `view` VARCHAR( 55 ) NOT NULL AFTER `type` ;


-- drop uneeded tables.

-- DROP TABLE `menu_items`;
-- DROP TABLE `menus`;




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

-- clone pages_tools to tools
CREATE TABLE tools LIKE pages_tools;
INSERT tools SELECT * FROM pages_tools;

-- update tools table
ALTER TABLE `tools` 
CHANGE `guid` `id` INT( 7 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  DROP `page_id`,
  DROP `container`,
  DROP `position`;
  
-- update the pages_tools table

ALTER TABLE `pages_tools`
 CHANGE `guid` `tool_id` INT( 7 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  DROP `system_tool_id`,
  DROP `tool_id`;
  


-- update the version
UPDATE `version` SET `at` = '008';
