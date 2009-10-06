


-- album tool is getting json paramaters 
ALTER TABLE `albums` CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
-- enable toggling of elements within the album view.
ALTER TABLE `albums` ADD `toggle` VARCHAR( 50 ) NOT NULL AFTER `params` ;



-- move the helper method to the database.

DROP TABLE `system_tools`;

CREATE TABLE IF NOT EXISTS `system_tools` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `protected` enum('yes','no') NOT NULL DEFAULT 'no',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'no',
  `visible` enum('yes','no') NOT NULL DEFAULT 'yes',
  `type` varchar(30) NOT NULL COMMENT 'default',
  `desc` tinytext NOT NULL,
  `helper_method` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `system_tools`
--

INSERT INTO `system_tools` (`id`, `name`, `protected`, `enabled`, `visible`, `type`, `desc`, `helper_method`) VALUES
(1, 'Text', 'no', 'yes', 'yes', 'basic', 'Manage blocks of textual and image based content using helpful templates.', 'add'),
(2, 'Album', 'no', 'yes', 'yes', 'images', 'Organize groups of images into albums. Specify captions for your images.', 'manage'),
(3, 'Showroom', 'yes', 'yes', 'yes', 'display', 'Installs a product gallery on this page.', 'manage'),
(4, 'Format', 'no', 'yes', 'yes', 'people', 'Create a wide variety of content very easily!<br>Select the type of format below:', 'add'),
(7, 'Calendar', 'yes', 'yes', 'yes', 'small', 'Installs an Event Calendar to organize and display various events associated with your group.', 'add'),
(8, 'Navigation', 'no', 'yes', 'yes', 'lists', 'Create nested lists for navigation menus, or to highlight relevant content.', 'manage'),
(9, 'Blog', 'yes', 'yes', 'yes', 'blogs', 'Installs a blogging engine on this page.', 'add'),
(10, 'Account', 'yes', 'yes', 'no', 'accounts', 'Enable friends of your site to signup for accounts enabling them to post on forums, blogs, etc. ', 'add'),
(11, 'Forum', 'yes', 'yes', 'yes', 'forums', 'Install a forum onto your website.', 'manage'),
(12, 'Review', 'yes', 'yes', 'yes', 'reviews', 'Setup reviews on your site.', 'add'),
(13, 'Newsletter', 'yes', 'yes', 'yes', 'newsletters', 'manage an email newsletter', 'add');



UPDATE `staging`.`version` SET `at` = '11' ;

