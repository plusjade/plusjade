-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 29, 2009 at 08:04 PM
-- Server version: 5.1.30
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `plusjade`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `page_id` int(3) NOT NULL,
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(10) NOT NULL DEFAULT 'lightbox',
  `params` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id`, `page_id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 19, 1, 'Test Album', 'lightbox', '77'),
(2, 77, 1, 'Asian Album', 'lightbox', 'bottom'),
(8, 77, 1, 'Tasty MmmM', 'lightbox', ''),
(9, 77, 1, 'asdfa', 'lightbox', '');

-- --------------------------------------------------------

--
-- Table structure for table `album_items`
--

CREATE TABLE IF NOT EXISTS `album_items` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `parent_id` int(3) NOT NULL,
  `fk_site` int(3) NOT NULL,
  `path` varchar(20) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `position` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=123 ;

--
-- Dumping data for table `album_items`
--

INSERT INTO `album_items` (`id`, `parent_id`, `fk_site`, `path`, `caption`, `position`) VALUES
(100, 2, 1, 'Picture-051.jpg', '', 1),
(79, 1, 1, 'avatar.jpg', 'I''m happy', 3),
(78, 1, 1, 'BEAR.jpg', 'Bear! <3', 5),
(85, 1, 1, 'image5.jpg', 'Leaves', 2),
(90, 1, 1, 'snapshot3.jpg', '<3', 6),
(76, 1, 1, 'cali.jpg', 'Cawichu', 4),
(83, 1, 1, 'image2.jpg', 'Red', 7),
(101, 2, 1, '198928335_l.jpg', '', 4),
(92, 1, 1, 'sample.jpg', 'web emb website', 1),
(98, 2, 1, 'dicking_around.jpg', '', 2),
(96, 2, 1, 'wowzers.jpg', '', 0),
(99, 2, 1, 'DSC00444.JPG', '', 3),
(112, 8, 1, 'one.jpg', 'Tanks', 5),
(104, 8, 1, 'capt.2ba15823cc.jpg', 'Japanese Model', 4),
(105, 8, 1, 'asdfasdf-1.jpg', 'group pic', 2),
(110, 9, 1, 'bear.jpg', 'bear close up', 1),
(111, 9, 1, 'bear_grylls.jpg', 'bear in the wild', 2),
(113, 9, 1, 'next_arrow.gif', '', 3),
(115, 8, 1, 'sample.jpg', '', 7),
(116, 8, 1, 'sunflower.jpg', 'hihi', 8),
(119, 9, 1, 'smiley.jpg', '', 4);

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE IF NOT EXISTS `calendars` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `page_id` int(3) NOT NULL,
  `fk_site` int(3) NOT NULL,
  `name` varchar(30) NOT NULL,
  `view` varchar(30) NOT NULL,
  `params` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`id`, `page_id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 89, 1, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `calendar_items`
--

CREATE TABLE IF NOT EXISTS `calendar_items` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `parent_id` int(3) NOT NULL,
  `fk_site` int(3) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `calendar_items`
--

INSERT INTO `calendar_items` (`id`, `parent_id`, `fk_site`, `year`, `month`, `day`, `title`, `desc`) VALUES
(2, 1, 1, 2009, 3, 12, 'i am cool', '<div style="text-align: center;"><h3><span style="font-weight: bold;">You said it!!</span></h3></div>'),
(3, 1, 1, 2009, 3, 26, 'Today !!', ''),
(4, 1, 1, 2009, 3, 26, 'super duper', ''),
(5, 1, 1, 2009, 3, 27, 'Yippy!', 'yah boi'),
(6, 1, 1, 2009, 3, 31, 'Mutemath Concert', '<div style="text-align: center;"><h3><br></h3><h3>YAH BOI!<br></h3><h3>Yay come to this concert it is going to be off the chizzzaneeeeee!!!</h3><br><img src="http://www.lancecrawford.net/lancecrawfordnet/images/2007/09/21/mutemath.jpg"></div><div style="text-align: center;"><br class="webkit-block-placeholder"></div><div style="text-align: center;">tee hee!<br><br></div>'),
(7, 1, 1, 2009, 3, 31, 'Starcraft', '<img src="http://www.videogamesblogger.com/wp-content/uploads/2007/06/starcraft-ghost-nova-big.jpg">'),
(8, 1, 1, 2009, 3, 21, 'Spring Time! ', 'What a nice spring day! Last forever !!!<br>');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `page_id` int(3) NOT NULL,
  `fk_site` int(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  `view` varchar(40) NOT NULL,
  `params` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `page_id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 20, 1, 'Standard', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `contact_items`
--

CREATE TABLE IF NOT EXISTS `contact_items` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `parent_id` int(3) NOT NULL,
  `fk_site` smallint(3) unsigned NOT NULL,
  `type` smallint(2) unsigned NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `position` tinyint(2) unsigned NOT NULL,
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=103 ;

--
-- Dumping data for table `contact_items`
--

INSERT INTO `contact_items` (`id`, `parent_id`, `fk_site`, `type`, `display_name`, `value`, `position`, `enable`) VALUES
(66, 1, 1, 2, 'Email', 'plusjade@gmail.com', 1, 'yes'),
(2, 1, 1, 1, 'Phone', '626-433-3534', 5, 'no'),
(4, 1, 1, 4, 'Business Hours', '<span style="font-style: italic;">Call support</span>\n<br><br>\n<div style="margin-left: 40px;">Monday - Sat: 8am - 8pm<br>\nSunday: Closed<br><br>\n</div>\n\n<span style="font-style: italic;">Email support</span><span style="font-weight: bold;"></span><br><br>\n<div style="margin-left: 40px;">Daily response time: within one hour\n</div>', 2, 'yes'),
(92, 0, 40, 1, 'Phone', '(626) - 795 3031', 1, 'yes'),
(93, 0, 40, 3, 'Address', 'Sam &amp; Leon Furniture Co.<br>365 S. Rosemead Blvd.<br>Pasadena, CA 91107', 0, 'yes'),
(94, 0, 40, 4, 'Store Hours', 'Tuesday - Saturday : <span style="font-weight: bold;">10pm - 5 pm</span><br>\nSundays - <span style="font-weight: bold;">11pm - 5 pm </span><br>Mondays - <span style="font-weight: bold;">Closed</span>', 2, 'yes'),
(76, 0, 12, 1, 'phone', '626-433-3434', 0, 'yes'),
(77, 0, 12, 2, 'email', 'bob@djshrek.com', 0, 'yes'),
(78, 0, 12, 3, 'address', 'UpInDaClub <br>Santa Fe, NM 93243', 0, 'yes'),
(79, 0, 16, 1, 'phone', ' 626.288.6607', 1, 'yes'),
(80, 0, 16, 2, 'email', 'help@motorcycles-plus.com', 4, 'yes'),
(81, 0, 19, 1, 'mobile', '626 - 433 - 3534', 0, 'yes'),
(82, 0, 19, 2, 'email', 'superjadex12@gmail.com', 0, 'yes'),
(83, 0, 19, 5, 'aim', 'superjadex12', 0, 'yes'),
(85, 0, 16, 8, 'map', '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;q=729+E+Garvey+Ave,+Monterey+Park,+Los+Angeles,+California+91755&amp;sll=34.077119,-118.130043&amp;sspn=0.011801,0.01781&amp;g=709+W+Ramona+Rd,+Alhambra,+CA+91803&amp;ie=UTF8&amp;cd=1&amp;geocode=FULBBwId57_1-A&amp;split=0&amp;ll=34.07328,-118.108091&amp;spn=0.047207,0.071239&amp;z=14&amp;iwloc=addr&amp;output=embed&amp;s=AARTsJpITF_ZNK9olySinFQZsaskXOxDfQ"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;q=729+E+Garvey+Ave,+Monterey+Park,+Los+Angeles,+California+91755&amp;sll=34.077119,-118.130043&amp;sspn=0.011801,0.01781&amp;g=709+W+Ramona+Rd,+Alhambra,+CA+91803&amp;ie=UTF8&amp;cd=1&amp;geocode=FULBBwId57_1-A&amp;split=0&amp;ll=34.07328,-118.108091&amp;spn=0.047207,0.071239&amp;z=14&amp;iwloc=addr" style="color:#0000FF;text-align:left">View Larger Map</a></small>', 5, 'yes'),
(86, 0, 16, 3, 'address', 'Motorcycles Plus<br>729 E. Garvey Ave.<br>Monterey Park, CA 91755', 2, 'yes'),
(87, 0, 16, 4, 'Business Hours', 'Monday - Saturday: 9am - 6pm<br>Sunday: Please Call', 3, 'yes'),
(90, 0, 3, 3, 'Address', 'Pandaland Drive', 1, 'yes'),
(91, 0, 3, 2, 'Email', 'panda@pandaland.com', 2, 'yes'),
(100, 1, 1, 5, 'aim', '', 4, 'no'),
(101, 1, 1, 8, 'map', '', 3, 'no'),
(99, 1, 1, 3, 'Address', 'Alhambra, CA 91803', 0, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `contact_types`
--

CREATE TABLE IF NOT EXISTS `contact_types` (
  `type_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `contact_types`
--

INSERT INTO `contact_types` (`type_id`, `type`) VALUES
(1, 'phone'),
(2, 'email'),
(3, 'address'),
(4, 'hours'),
(5, 'aim'),
(6, 'skype'),
(8, 'map');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `page_id` int(3) NOT NULL,
  `fk_site` int(3) NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(25) NOT NULL,
  `params` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `faqs`
--


-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(5) unsigned NOT NULL,
  `page_id` int(3) NOT NULL,
  `page_name` varchar(25) NOT NULL,
  `display_name` varchar(25) DEFAULT NULL,
  `position` tinyint(2) unsigned NOT NULL DEFAULT '99',
  `group` varchar(25) NOT NULL DEFAULT '1',
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `fk_site`, `page_id`, `page_name`, `display_name`, `position`, `group`, `enable`) VALUES
(7, 16, 0, 'home', 'home', 1, '1', 'yes'),
(8, 16, 0, 'product', 'Services', 2, '1', 'yes'),
(9, 16, 0, 'about', 'About', 3, '1', 'yes'),
(40, 1, 24, 'examples', 'Examples', 2, '1', 'yes'),
(93, 40, 79, 'contact', 'Contact', 1, '1', 'yes'),
(28, 19, 0, 'water', 'BASIC', 2, '1', 'yes'),
(29, 19, 0, 'home', '', 1, '1', 'yes'),
(30, 1, 13, 'pricing', 'Pricing', 1, '1', 'yes'),
(34, 1, 19, 'home', 'Home', 0, '1', 'yes'),
(35, 1, 20, 'contact', 'Contact', 5, '1', 'yes'),
(41, 16, 0, 'contact', 'Contact', 4, '1', 'yes'),
(44, 20, 0, 'home', 'Home', 0, '1', 'yes'),
(45, 20, 0, 'contact', 'Contact', 1, '1', 'yes'),
(47, 3, 32, 'about', 'About', 2, '1', 'yes'),
(53, 3, 38, 'home', 'Home Sweet Home =)', 1, '1', 'yes'),
(59, 3, 44, 'box', 'Box', 3, '1', 'yes'),
(92, 40, 78, 'home', 'Home', 0, '1', 'yes'),
(82, 1, 68, 'tools', 'Tools', 4, '1', 'yes'),
(81, 38, 67, 'home', 'Home', 0, '1', 'yes'),
(83, 38, 69, 'about', 'about', 1, '1', 'yes'),
(84, 38, 70, 'samples', 'samples', 2, '1', 'yes'),
(85, 1, 71, 'themes', 'Themes', 3, '1', 'yes'),
(86, 38, 72, 'intercraft', 'intercraft', 3, '1', 'yes'),
(87, 38, 73, 'corporate', 'corporate', 4, '1', 'yes'),
(88, 38, 74, 'green_web', 'green web', 5, '1', 'yes'),
(90, 38, 76, 'biege', 'biege', 6, '1', 'yes'),
(91, 1, 77, 'test', 'Pics', 6, '1', 'yes'),
(101, 1, 89, 'new_page', 'Calendar', 7, '1', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `fk_site` smallint(3) unsigned NOT NULL,
  `page_name` varchar(30) NOT NULL,
  `title` varchar(50) NOT NULL,
  `meta` tinytext NOT NULL,
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `fk_site`, `page_name`, `title`, `meta`, `enable`) VALUES
(3, 16, 'home', 'Moto Home Page teee hee', '', 'yes'),
(4, 16, 'about', 'reviews sample page for moto', '', 'yes'),
(6, 16, 'product', 'Motorcycles Plus Waterglass Services', 'services for motorcycles in the san gabriel valley', 'yes'),
(24, 1, 'examples', '+Jade website examples', 'asafsadfsa', 'yes'),
(12, 19, 'water', 'Water and all its miracles', 'Research paper on water ', 'yes'),
(13, 1, 'pricing', '+Jade - Breakdown of website service package', 'website hosting service package from +Jade', 'yes'),
(38, 3, 'home', '', '', 'yes'),
(14, 19, 'home', '', '', 'yes'),
(71, 1, 'themes', '+Jade Proffessional theme creation for your busine', '+Jade Proffessional theme creation for your busine', 'yes'),
(19, 1, 'home', '+Jade managed website hosting for small businesses', '+Jade managed website hosting for small businesses', 'yes'),
(20, 1, 'contact', 'Contact +Jade', '', 'yes'),
(25, 16, 'contact', '', '', 'yes'),
(28, 20, 'home', 'Sample Home page', '', 'yes'),
(29, 20, 'Contact', '', '', 'yes'),
(32, 3, 'about', '', '', 'yes'),
(44, 3, 'box', 'Smello', 'Jello', 'yes'),
(68, 1, 'tools', '', '', 'yes'),
(78, 40, 'home', 'Sam & Leon Furniture Co - Fine Oriental Furniture ', 'Fine Oriental Furniture & Accessories in Pasadena, CA and the greater San Gabriel Valley.', 'yes'),
(79, 40, 'contact', 'Contact us : Sam & Leon Furniture Co', '', 'yes'),
(67, 38, 'home', 'Pinkys homepage', '', 'yes'),
(69, 38, 'about', '', '', 'yes'),
(70, 38, 'samples', 'sample page', '', 'yes'),
(72, 38, 'intercraft', '', '', 'yes'),
(73, 38, 'corporate', '', '', 'yes'),
(74, 38, 'green_web', '', '', 'yes'),
(76, 38, 'biege', '', '', 'yes'),
(77, 1, 'test', 'Cool Album page', 'Tee Hee !', 'yes'),
(89, 1, 'new_page', 'New page', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `pages_tools`
--

CREATE TABLE IF NOT EXISTS `pages_tools` (
  `guid` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(3) unsigned NOT NULL,
  `fk_site` int(3) NOT NULL,
  `tool` int(3) NOT NULL COMMENT 'fk_tools_list',
  `tool_id` int(3) unsigned NOT NULL,
  `tag` varchar(25) NOT NULL,
  `position` int(1) unsigned NOT NULL,
  PRIMARY KEY (`guid`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `pages_tools`
--

INSERT INTO `pages_tools` (`guid`, `page_id`, `fk_site`, `tool`, `tool_id`, `tag`, `position`) VALUES
(1, 19, 1, 4, 1, '', 2),
(2, 19, 1, 2, 1, '', 3),
(3, 24, 1, 3, 1, '', 1),
(4, 68, 1, 3, 2, '', 1),
(5, 71, 1, 3, 3, '', 1),
(6, 20, 1, 5, 1, '', 3),
(8, 77, 1, 2, 2, '', 3),
(9, 77, 1, 2, 8, '', 2),
(10, 77, 1, 2, 9, '', 4),
(11, 19, 1, 1, 1, '', 1),
(12, 19, 1, 1, 2, 'footer', 4),
(13, 13, 1, 1, 3, '', 2),
(27, 90, 1, 1, 17, '', 2),
(17, 3, 1, 1, 9, '', 2),
(25, 89, 1, 7, 1, '', 2),
(26, 89, 1, 1, 16, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_users`
--

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES
(1, 1),
(6, 1),
(9, 1),
(10, 1),
(11, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `showrooms`
--

CREATE TABLE IF NOT EXISTS `showrooms` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(3) unsigned NOT NULL,
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `view` varchar(40) NOT NULL,
  `params` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `showrooms`
--

INSERT INTO `showrooms` (`id`, `page_id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 24, 1, 'examples', 'list', ''),
(2, 68, 1, 'tools', 'list', ''),
(3, 71, 1, 'themes', 'gallery', '');

-- --------------------------------------------------------

--
-- Table structure for table `showroom_items`
--

CREATE TABLE IF NOT EXISTS `showroom_items` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(3) NOT NULL,
  `fk_site` int(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `intro` text NOT NULL,
  `body` text NOT NULL,
  `price` float unsigned NOT NULL,
  `position` int(11) NOT NULL,
  `image` varchar(18) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `showroom_items`
--

INSERT INTO `showroom_items` (`id`, `parent_id`, `fk_site`, `name`, `intro`, `body`, `price`, `position`, `image`) VALUES
(30, 2, 1, 'tshirt', 'Music!<br>', 'supervisor', 900, 0, '1238299570.jpg'),
(2, 1, 1, 'http://motorcycles-plus.com', 'Motorcycle shop needed simple website to convey shop hours, rates, services, and preferred contact method. <div><br class="webkit-block-placeholder"></div><div>Makes use of the gallery module for sweet effect</div>', '', 200, 2, '1233651256.jpg'),
(3, 1, 1, 'http://webemb.com', 'Embroidery shop was looking into getting a professional website done. Too many solutions and not enough time to sit down and talk out his take-over-the-world plans ; we finally convinced the owner to just get something up already! Webemb is now in our system currently getting made!<br>', '', 45, 1, '1233651385.jpg'),
(4, 1, 1, 'http://pasadena-furniture.com', 'Local furniture store just added to the system today. This is a good example of how easy it is to port a free theme over to our system. Thanks to the great guys at <a target="" title="" href="http://www.freecsstemplates.org/">http://www.freecsstemplates.org/</a> the furniture company did not have to pay a cent toward design costs and can now worry about gathering content for their pages and of course: marketing! =D', '', 4456, 3, '1233651275.jpg'),
(6, 2, 1, 'Showroom', 'Prominently display your core products or services in a beautifully organized showroom display.<br>Customize item layout, enable juicy user functionality and more ...', 'The showroom offers a quick and easy way to effectively highlight your core product or service offering in a gallery format.<br><br><ul><li>Organize your core products and/or services into an easy to view gallery.</li><li>Create individual product pages to convey more detailed information.</li><li>Manage and display product images cleanly using sexy image gallery tools.</li></ul><br>The tools page and all the modules on them (including this page) was built using the Showroom tool. ', 200, 1, '1235525135.gif'),
(7, 2, 1, 'Contacts', 'Instantly build a highly effective "Contact page" to prompt user action. Elegantly informs and guides users on various ways to contact<br>your business. ', 'Contact pages are frequently the most heavily used "action" taken by a user.<br>Once the user gains interest into your company, he will look for a "next step" action to perform.<br>Commonly this will be a phone number to call, an email form to fill out, or a location to visit. <br><br>The Contact Manager allows you to <span style="font-style: italic;">instantly </span>create clean, organized, and actionable methods of contact.<br><br><ul><li>Preferred phone number(s)</li><li>Preferred email address(es)</li><li>Store or location address (with map)</li><li>Hours of operation</li><li>Online Chat availability.<br></li><li>Form emailers (email sent right from your website)<br></li></ul>', 0, 2, '1235525157.gif'),
(8, 2, 1, 'FAQ Manager', 'A simple Frequently Asked Questions builder optimized around usability. Get relevant information to your customers - fast, without interaction on your part. Display question/answer pairs sexily without needing to reload the page. View example.', '', 0, 3, '1235525233.gif'),
(9, 2, 1, 'Slide Panel', 'Have a lot of information to get across to your customers? Organize and display pertinent information in a concise, guided, and easily digestable manner using our slide panel builder.', '', 0, 4, '1235525255.gif'),
(10, 2, 1, 'Customer Reviews', 'Provide an easy method for customers to submit reviews or testimonials regarding your company.<br>Manage your reviews right from your site, choose which to display first or highlight. <br>Easy reply feature shows customers you care! ', 'Reviews and testimonials are great assets online. This "user generated" content builds your reputation online and earns valuable trust points with new customers browsing your site. <br><br>The reviews module also allows you to tap into 3rd party review sites like yelp.com or insider pages. These sites help spread your name to external websites, thus increasing your market reach. ', 0, 5, '1235528870.gif'),
(11, 2, 1, 'Image Gallery', 'A cool image gallery ! Huzza =D', '', 0, 6, '1235534987.gif'),
(15, 3, 1, 'Green Web', 'Green Web ', 'Sample <br>', 22, 0, '1236051511.jpg'),
(14, 3, 1, 'Intercraft', '', '', 0, 1, '1236051546.jpg'),
(17, 3, 1, 'Corporate', '<div style="text-align: center;">Corporate red theme<br></div>', '', 0, 2, '1236051558.jpg'),
(18, 3, 1, 'Beige Fixed', 'Beige', '', 0, 3, '1236051570.jpg'),
(29, 2, 1, 'Event Calendar', 'Let customers know where your next event will be with a clear and easy to use calendar.<br><br>Users can elect to be updated on the day of the event via email.', '', 0, 7, '1238131381.gif');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(25) NOT NULL,
  `domain` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `banner` varchar(50) NOT NULL,
  `theme` varchar(50) NOT NULL,
  PRIMARY KEY (`site_id`),
  KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`site_id`, `url`, `domain`, `name`, `address`, `phone`, `email`, `banner`, `theme`) VALUES
(1, 'jade', 'plusjade.com', '', '', '626-433-3534', '', '1235438951_ban.png', 'redcross'),
(2, 'webemb', 'webemb.com', 'Dexterious Industries', '709 w Ramona Rd<br>Alhambra, Ca 91803', '626-433-3534', 'sales@webemb.com', 'logo_black.png', 'redcross'),
(3, 'panda', 'basegroups.com', 'PandaLand', '1115 MountBatten ave<br>Glendale, Ca 91207', '818-219-1494', 'Panda@pandaland.com', 'cute_panda.jpg', 'redcross'),
(40, 'bob', '', '', '', '', '', '', 'nonzero'),
(16, 'moto', 'motorcycles-plus.com', '', '729 e. Garvey Ave.  Montery Park, Ca 91755', '626.288.6607', 'service@motorycles-plus.com', '1233375317_ban.png', 'green_web'),
(19, 'basic', 'blockbasic.com', 'basic', 'waterPolo', '626-433-3534', '', '1232757176_ban.png', 'custom'),
(20, 'dad', '', 'dad', '', '', '', '', 'misc'),
(38, 'pinky', '', '', '', '', '', '1236039190_ban.gif', 'beige');

-- --------------------------------------------------------

--
-- Table structure for table `slide_panels`
--

CREATE TABLE IF NOT EXISTS `slide_panels` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(3) unsigned NOT NULL,
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(40) NOT NULL,
  `params` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `slide_panels`
--

INSERT INTO `slide_panels` (`id`, `page_id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 19, 1, '', 'standard', '');

-- --------------------------------------------------------

--
-- Table structure for table `slide_panel_items`
--

CREATE TABLE IF NOT EXISTS `slide_panel_items` (
  `id` mediumint(3) NOT NULL AUTO_INCREMENT,
  `parent_id` int(3) unsigned NOT NULL,
  `fk_site` smallint(3) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `position` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `slide_panel_items`
--

INSERT INTO `slide_panel_items` (`id`, `parent_id`, `fk_site`, `title`, `body`, `position`) VALUES
(1, 1, 1, '1. Create', '<h2>1. Create and Customize Your Website.</h2>\n<div class="image_placeholder"></div>\n<span style="font-weight: bold;">Design -</span>\n<br><br>\n<div style="margin-left: 40px;">Choose from one of our many quick-start themes and be on your way in no time, or\n\n<span style="font-weight: bold;"></span> use our theme panel to easily customize and edit the markup and css.<br><br>A professionally designed custom theme can be easily uploaded to your account to give you a truly custom professional look <span style="font-style: italic; font-weight: bold;">without</span> the outrageous custom programming price.<br></div><span style="font-weight: bold;"><br></span><span style="font-weight: bold;">Content -</span><br><br><div style="margin-left: 40px;">Each site automatically loads 6 core pages commonly found in the best of business websites:<br></div><ul style="margin-left: 40px;"><li>Home page</li><li>Product(s) page </li><li>Reviews/Testimonials </li><li>Frequently Asked Questions</li><li>About</li><li>Contact</li></ul>Every account is a full fledged hosted website. We manage all the technology so you can focus on content and ... your customers! =D<br>\n', 0),
(2, 1, 1, '2. Market', '<h2><span style="font-weight: bold;">2. Market Your Website.</span></h2>\n<div class="image_placeholder"></div>Follow step-by-step tutorials on various kinds of internet marketing and growing your userbase from the ground up.<br><br>Marketing and promotion are the most crucial part of creating a website.<br>Otherwise what is the point?<br><div style="margin-left: 40px;"><br></div>', 1),
(3, 1, 1, '3. Analyze', '<h2><span style="font-weight: bold;">3. Track and Analyze Data.</span></h2><div class="image_placeholder"></div>Seamless integration with both free and premium web analytics trackers including google, clicky, and crazyegg. <br><br>We show you how to use this data to build better pages, launch a/b split tests, and organically optimize your website. Properly reading and responding to data is crucial to growing your business presence online.', 2),
(4, 1, 1, '4. Grow', '<h2><span style="font-weight: bold;">4. Respond and Grow.</span></h2><div class="image_placeholder"></div>As a business, you know the importance of getting down to business. Your website needs to produce an ROI. <br><br>Create your content -&gt; <br>Market your site -&gt; <br>Respond to data -&gt;<br>Grow your customer base!<br><br>Sure it takes work and planning. <br>Join our team and instantly eliminate all your technology obstacles,<br>Oh, and we have the plan too, so let''s get started...', 3);

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(3) unsigned NOT NULL,
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(25) NOT NULL,
  `params` varchar(25) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `texts`
--

INSERT INTO `texts` (`id`, `page_id`, `fk_site`, `name`, `view`, `params`, `body`) VALUES
(1, 19, 1, 'header', '', '', '<h1 style="font-weight: normal; text-align: center;">STOP putting your website plans on hold!<br></h1>\n<div style="text-align: center; margin-bottom: 10px;">Get an effective website up and running for your business by day''s end, guaranteed!<br><br>No web experience? No problem!</div>\n'),
(2, 19, 1, '', '', '', '<div style="text-align: center; margin-top: 15px;">\n<h2>Take the Tour!</h2>\n<h2>Sign up!</h2></div>'),
(3, 13, 1, 'pricing text', '', '', '<span style="font-weight: bold;"><br></span><h3><span style="font-weight: bold;">Custom Website Design and Page Creation.</span></h3><h3><span style="font-weight: bold;"></span></h3><span style="font-weight: bold;"><br></span><div style="margin-left: 40px;">Your website is hand designed to your custom specifications. <br>We also personally configure your pages with your content. <br>Includes a consultation for discussing main business content and images. <br>We provide copy and image editing/optimization. This is needed to ensure your content clearly and concisely conveys your business message.  <br>Page creation includes the 6 core page modules. Extra pages can be implemented at any time for a small fee. <br><br>Pricing for this service ranges from $200 - $500 depending on design and content complexity. <br>This is a one-time setup fee. If at any time you decide to cancel your hosting and management services (see below) with +Jade, your website and its content will still belong to you. <br>You can easily export a full static rendition of your website and all its pages.<br><br>At this early stage this is our only option. Shortly we will provide page editing and creation tools to our users. This will allow you to edit and configure your pages yourself from within your browser window. There will be no extra cost for self-editing.<br><br></div><span style="font-weight: bold;"></span><br><h3>Full Website Hosting, Management, and Optimization Services.</h3><ul><li>Full website hosting (link describing what hosting is)</li><li>Step-by-step plan of action tutorials and documention for marketing your website and collecting and analyzing data.</li><li>Web analytics dashboard used to track and measure your data.</li><li>User interface for responding to data by changing elements of your site.</li><li>A/B split testing platform. Easily create 2 different pages and test them against eachother to see which interests/compels users more effectively.</li><li>Progress reports, for showing how much you have grown and steps to improve.<br><br>Team -<br><br></li><li>Continual codebase refinement and optimization. </li><li>Continual rollout of website functions and toolsets. (available to all users)</li><li>Up to date technology - your website never gets old. Always roll out latest practices.</li><li>Knowledge base - Proactive tutorials regarding things that produce results for you. <br>(Not learning how to be a computer scientist)<br></li></ul><div style="margin-left: 40px;"><br><span style="font-weight: bold;">Price:</span> $12.00 monthly<br><br>OR $108 per year if paid upfront. A 25% cost savings or $9.00 monthly.<br><br><br><br></div>'),
(9, 3, 16, '', '', '', '<div style="text-align: center;"><h1>sa;lfkjsf;lsjfds;ljfs;lkajfs;ajfa</h1></div>'),
(16, 89, 1, '', '', '', '<h2 style="text-align: center;">Join us on the following event days,</h2><h2 style="text-align: center;"> where we will be showcasing our newest products!</h2>');

-- --------------------------------------------------------

--
-- Table structure for table `text_items`
--

CREATE TABLE IF NOT EXISTS `text_items` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(3) unsigned NOT NULL,
  `fk_site` int(3) unsigned NOT NULL,
  `body` text NOT NULL,
  `position` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `text_items`
--

INSERT INTO `text_items` (`id`, `parent_id`, `fk_site`, `body`, `position`) VALUES
(1, 1, 1, '<h1 style="font-weight: normal; text-align: center;">STOP putting your website plans on hold!<br></h1>\r\n<div style="text-align: center;">Get an effective website up and running for your business by day''s end, guaranteed!<br><br>No web experience? No problem! </div>', 1),
(2, 1, 1, '<div style="text-align: center;" jquery1234417452080="89">\r\n<h2>Take the Tour!</h2>\r\n<h2>Sign up!<br></h2></div>', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tools_list`
--

CREATE TABLE IF NOT EXISTS `tools_list` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tools_list`
--

INSERT INTO `tools_list` (`id`, `name`) VALUES
(1, 'Text'),
(2, 'Album'),
(3, 'Showroom'),
(4, 'Slide_Panel'),
(5, 'Contact'),
(6, 'Faq'),
(7, 'Calendar');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_site_id` int(3) NOT NULL,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` char(50) NOT NULL,
  `token` varchar(32) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `client_site_id`, `email`, `username`, `password`, `token`, `logins`, `last_login`) VALUES
(1, 1, 'jade@jade.com', 'jade', '62a69de60a2d1bb13bf2edf2bd976870b72597985683325617', 'bvAkj6hoXDQrly9c9UTFZh6IthD7NuGs', 473, 1238372640),
(6, 3, 'panda@panda.com', 'panda', 'c02579ffc93034639d51302de51929d5eaaf7500919956aa7f', 'LzY1HCX22CnOy04uKiRzcN0wokbDsKJk', 47, 1235968621),
(9, 38, 'pinky@pinky.com', 'pinky', 'bd87948b911056bef5c9b803ceae2b01e79418f1907cd2e6fd', 'UGm5XB6trg8UBtytYh6JVT7T2n5OzGon', 33, 1236046294),
(10, 16, 'moto@moto.com', 'moto', '57a94fe0315a32718adc8c6a3cb46f0f1555aa7e7b00e49aac', 'Om56X42I4RJyLfbkxblhw53qP4GcujGq', 7, 1237655109),
(11, 40, 'bob@bob.com', 'bob', 'b96bad111e88a3a3bc6f102008a05fdf46bf9a7141874acaae', 'yXsfSrz7p9GbVJK97dTCSVvGJN71UJWX', 2, 1236730164);

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=166 ;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `user_agent`, `token`, `created`, `expires`) VALUES
(1, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Ard3ZedHRWZkVZyPrJk23ohgVLLjjmQZ', 1235108458, 1236318058),
(2, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'IXD96hWMVuNZn2XyMQePSLAN4RdXfENR', 1235109079, 1236318679),
(3, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'ug1SEzcpRcV3F1IgEHiiZq9cMhNFadGz', 1235110024, 1236319624),
(4, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'ikMBwWl7PTN8RRue60k4DwUEZZEWxVsQ', 1235113085, 1236322685),
(5, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'MvlBwkSsVb0xZ4b0A7s2NShT0gDg2gzf', 1235113365, 1236322965),
(6, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'KBImnJiZnAg994cOfpwbJrjFrDQ0dpzL', 1235115219, 1236324819),
(8, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'VH6SzhPGN3AELfaqVAdLcOO7DW1b1tOB', 1235115448, 1236325048),
(10, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'fR5ALtJxHNo6Te0WTLHRO9UDU5cyNAmO', 1235115598, 1236325198),
(13, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'tXL9lIWGc6yiPXtznoD4AODwgDNgApVK', 1235116800, 1236326400),
(14, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '9ApVERWuwebRuwAv3VtCfARjw4Wy0Ojm', 1235117589, 1236327189),
(15, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Iq6SX70WRkDOrr6zCTv3wqojlfOtu0ve', 1235169987, 1236379587),
(16, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'fryOwWjqro9MHaFzapfDGLSL5kt5yvVc', 1235173403, 1236383003),
(17, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'nBk1qzgy16Qd6ONWdfsvYgQYsICzsU2x', 1235176498, 1236386098),
(18, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '9x3ujjQGh2WEyDG4SXLsb1kDQ3eORg8z', 1235177176, 1236386776),
(19, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '8uIhzUEl1Bb1m64SP9JPQsu4EzI5juU2', 1235177243, 1236386843),
(20, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'bIyf50Mu6TBNd0GeluWW1eiyWBkXtSRT', 1235177405, 1236387005),
(21, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'an8lKDcbIVfpkZW24rmSWAqiCqlrx8la', 1235179546, 1236389146),
(22, 6, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'Vmhx7yy8pHmm5QlxReA8ud4jO24Cz8s6', 1235180109, 1236389709),
(23, 6, '18442aeab9cd632a5946133c9e268c6e253938d2', 'GIEcqo7AYVGRA4roranV9USFkFIW08vn', 1235180399, 1236389999),
(24, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'fZtNzb7mdY6QvHxhsT2UK0vrpVnp8GmR', 1235183068, 1236392668),
(25, 1, '933721a538996ca3a6f9e3ef80481beadc1cea92', 'ymd1j3rt1S3uHoTuVg3wUwb0Hc2Ha1zr', 1235292284, 1236501884),
(26, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'HDFCwO1O8pWTwh3sjWUNfEkkDOs01Il6', 1235292399, 1236501999),
(27, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'ZHcYaEjKH1x4I2uJjkwF9FWj9wRleg7t', 1235347370, 1236556970),
(28, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '1GpPF5k3Fnt6LH2ETno74lb2gR7H7zg4', 1235432682, 1236642282),
(29, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '28TiJFsWkKnEW0reXbUJsV04xs19BlmK', 1235435422, 1236645022),
(30, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'yjpYAc9hviWLLX8z7pQwmtjrgHoaQolD', 1235435601, 1236645201),
(31, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Nf2gxtxDz3h7OUOf8nki0qez1CPbVOIz', 1235435702, 1236645302),
(32, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'dFKmgFEj7clnbYxzVvxNBJ6cWqRzgRnS', 1235436584, 1236646184),
(33, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Wrer2aQDyTve8WbOR5O8W6G6N3SzImrl', 1235438927, 1236648527),
(34, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'kSWBZ5rlmZNQIsG6dLsnqtt2MFXbiWTw', 1235439034, 1236648634),
(35, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'mAiqxXgZqCqNUHP36gxcatxCRtbnkESi', 1235440303, 1236649903),
(36, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '2BaPm1KOUKVEr1twCztn9wDUKYnuiTpo', 1235440436, 1236650036),
(37, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', '33LGLy2M2TEV4d07AheiuMijcut7rhh0', 1235440674, 1236650274),
(38, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'YSeXcEwbYicEdF5bEl6bkbJVzEEIBAOS', 1235442148, 1236651748),
(39, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'kkR2cnVjvsoSIGwnA4YrSAUjjZPQoWmc', 1235447052, 1236656652),
(40, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '1AYVNcqCss6d8wx4ghlE1gnh7R7DWuj3', 1235448489, 1236658089),
(41, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'IqjpROqqzoAEjABF4nkGZEdZK4JrBVxI', 1235451200, 1236660800),
(42, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'odtL7U9qWL4xHDQmFU1YN49Rtpj9TiSM', 1235520378, 1236729978),
(43, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '3nNQn8OvTad19YyvwQvafsjpYNW8Xl0j', 1235523041, 1236732641),
(44, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'h6Jh9QUc6SEUiSm6RJ4HmtHs0jARlwQK', 1235536654, 1236746254),
(45, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'NFHnBUINS51ebt9yVhfqz1UT4DocKe5P', 1235537661, 1236747261),
(46, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'j4mDLFNrQfsmX4S8x80oTi4FW6eFa9Qn', 1235537808, 1236747408),
(47, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'lPPTViu2fEXHkxTfxEFx1H3qxoPjrBgR', 1235541916, 1236751516),
(48, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '5X72PjZXTvGSis9kZm6lFQtBJURIsMqs', 1235542021, 1236751621),
(49, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '5ZnZEtm2vQk6GqNlUfojmnNsk76HDa7M', 1235544481, 1236754081),
(50, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'zVgE1skiMePty72QOGOhmHZHAomLHMJX', 1235552807, 1236762407),
(51, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'Ies0VpNKZ5qcgtHMt28ikreupUfxBvO9', 1235555447, 1236765047),
(52, 6, '18442aeab9cd632a5946133c9e268c6e253938d2', 'eSQPrONym1GrRIVO3fOdQIf1o17ZemSC', 1235555469, 1236765069),
(53, 6, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'bxUsSo8PnFdb6dIHbpR76qVuxcs2MzCq', 1235555608, 1236765208),
(54, 1, '9f6032a2129aef2d0b7f06522bcbf30855aecdb0', 'vTj1HvVMFosLmJNm03MkxrXckJmZF4oS', 1235555912, 1236765513),
(55, 1, '9f6032a2129aef2d0b7f06522bcbf30855aecdb0', 'lL3zttLFZ7Q8r5NUVmtbtogZBk6TB6rA', 1235601322, 1236810922),
(56, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'UANAoiIiyWXMsnWQnxgYTTSyhUYndVo9', 1235601554, 1236811154),
(57, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'p5eWaBMXQdYCbYh37zHj1UJzThF250x9', 1235602155, 1236811755),
(58, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'VGxI5X9z5000u24zvICnLDNUie9Pr96K', 1235614313, 1236823913),
(59, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'Bn0pkCAkUl5xy8cct54TJpR7crqrkoRN', 1235614410, 1236824010),
(60, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', '16Wh9IeuMruKc8oH7LtxG8LGmdIRKH9n', 1235614773, 1236824373),
(61, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'iIa6wisIgjAhDRCgn1j6Jr2hM14GbVdo', 1235617226, 1236826826),
(62, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '3wpJit3zAP5Iu1peNvELUaPyE19uJ78F', 1235624881, 1236834481),
(63, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'QCvSKUEJoIMbF5XWwLN7HtjAz9XKRV3R', 1235641155, 1236850755),
(64, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'efiZScqObK5e1suA8I7GUI2Z9oN0LjKB', 1235779546, 1236989146),
(65, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'OIReU6jFPkPF4RebKyjaljfKCNqkKse2', 1235780516, 1236990116),
(66, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'vqwkaq10aTkNBk7p5DKwy6ORYvJlMJa4', 1235785724, 1236995324),
(67, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'gQkPamT0XTU69GJ7ynuhbulbHLOiiX79', 1235788596, 1236998197),
(68, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', '1HYZsZUsk0UX8GgWEAaFKMkYj49KZvTk', 1235792923, 1237002523),
(69, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'GKMhRibKv7OvaUfDj0UJKn1GaTmYKqJp', 1235802761, 1237012361),
(70, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'j39Asw6ZM7lx5OeqjIX95iVGFCa76tpP', 1235809378, 1237018978),
(71, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'PWiNRlMkikmXKUiKDIkT0SOvhhSVGoKj', 1235809451, 1237019051),
(72, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'B1eeWYml6tCzsxF0L4xcRXrrT0MbuUa0', 1235962679, 1237172279),
(73, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'jBVvTvsTA3o0sRtsZFaLfnSGWRu4fPax', 1235964648, 1237174248),
(74, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'T6hv04YSsVvlmDXLyKSs77edQOOfo1jM', 1235968460, 1237178060),
(75, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'pwnbSPo9ZzXh0e3EFSvIBOyrvVhxPfTM', 1235968703, 1237178303),
(76, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'jQx7XP7bFqLnT87gLfqJbcULfOWXCnWJ', 1235968840, 1237178440),
(77, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'oHxwfFjJxNMqDeiCJniJuhCHTKxct415', 1235970677, 1237180277),
(78, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'BtWbCjsEufYsoK7pizQp9lM7Mg4sqXBN', 1235971422, 1237181022),
(79, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'W5nbmRUuZp5122gZuJPFyEeJ11V4mqXV', 1235973140, 1237182740),
(80, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'rmAmeBf1dbGZkwK4TYZbSh3RHZ3H089j', 1235975136, 1237184736),
(81, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'auwhSyN5bR1nUTNWXeU23umAUKhLNt3w', 1235976883, 1237186483),
(82, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'L2kwWhN6R4hocBFa2rU1Jt4xEyQfgGGH', 1235976897, 1237186497),
(83, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'vtw2bPDLaP6oaDBwufREeDLmS1LRiGKn', 1235978811, 1237188411),
(84, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '0jTGjzcAaebVVbwv7eGsE1WXD96av4WT', 1236036557, 1237246157),
(85, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '0aLbG8wDPmADrMrpe3S3cgp8MIWHjI1s', 1236039096, 1237248696),
(86, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'zRCdEz0dghyxEtWrZvQLQYBIHKsRN9f4', 1236039107, 1237248707),
(87, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'mqMIO0OO57abtsykCCZIjAWtAAx7oJmx', 1236042154, 1237251754),
(88, 10, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'U7tPaiNPtjqiZSAqgpFtJVhKZ4XIn86g', 1236042492, 1237252092),
(89, 9, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Z1GZ3Rbr6UjylCREYzx1D0x2TpT11ji7', 1236044633, 1237254233),
(90, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'yA3nH1cpx8My9iG7cGyaXMHCAvIU2Zgr', 1236048568, 1237258168),
(91, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'xyD44y2dIlWhnKxf16Y4w6nJ1wqqCVNh', 1236142044, 1237351644),
(92, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '04i7VuIGihrsD8Q7IgRug3t2lvRH3aKp', 1236147999, 1237357599),
(93, 10, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'gP9nonL8ysBUrmGGmH0BlAdzWsCcQuuu', 1236151844, 1237361444),
(94, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'p7i3tXzbyun6URZWMFlKTgvmDbH8l85H', 1236213855, 1237423455),
(95, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'NIot1bHD0jNv8zyFiRBYT9AwUyoFOmKs', 1236237186, 1237446786),
(96, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '3VYW562qPHrwCfsSLiFlGtxBKblwvVJ3', 1236248595, 1237458195),
(97, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'i0f7m3sg4dKifwTgv2Iiggm6iKIEVjq5', 1236249026, 1237458626),
(98, 1, '9f6032a2129aef2d0b7f06522bcbf30855aecdb0', 'ruKrU47ZjFG470fTwpOsC1xxyYyeEMZM', 1236249092, 1237458692),
(99, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'NW07kHHDyiAOIHovYYdhdMknJfvQbKJH', 1236296276, 1237505876),
(100, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'Bt356Un3LNlaDTUmGfJUCoqJKaRQVcb7', 1236296310, 1237505910),
(101, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'jFxEWvTXlWEaaf6tDj1FKRyVopLKr39I', 1236305883, 1237515483),
(102, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'v7vlRSzArV7vndM5Re2D1ZMIDyhSE5EE', 1236310516, 1237520116),
(103, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'VcSYhMLlW1SRS9Erjxekz0kr6hkhqLT2', 1236311256, 1237520856),
(104, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'vRDxAOsFnwSYet4sNnrk8WP9dLmjEbwa', 1236312949, 1237522549),
(105, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'DvP6xQSTNNhwuT2SKFcm7b0heQ1ssyMY', 1236386906, 1237596506),
(106, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'XJj5PQzY67V18BqJB9wd7qXxBqk25Ltl', 1236496257, 1237705857),
(107, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Ql9aywOGesG4TsctNwAtIkSL6GaePTV8', 1236668409, 1237878009),
(108, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'VdJ0mCpF6bV8PKL9vQRtpqEtbhvPURHt', 1236728115, 1237937715),
(109, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'tP8UJSnOWlwyzb824aAUYaTApdDsW9zo', 1236730678, 1237940278),
(110, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'wWrYEkIm2Ih33PLgggitldCEz47TkS8G', 1236745772, 1237955372),
(111, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'HAtCUhlSYn1kEzx3gkbmBLEgGRoUHjUI', 1236811640, 1238021240),
(112, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'lDYNRlMf3hoQeXp5mN9NCwb5UKL2S9l9', 1236827176, 1238036776),
(113, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'mFMnxM5TMHhw1eQEdxYtpvgt7dWBTl6f', 1236852887, 1238062487),
(114, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'gXGKfNXYzYj5rYj2SOvfbQP3TqLcC7bf', 1236857020, 1238066620),
(115, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '3O7L3L7v8zawhydIyXMRzwt5uOi4FFLP', 1236899979, 1238109580),
(116, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'A1wQhURkopLmmPsBrXerERLMXMAbauAT', 1236984716, 1238194316),
(117, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'IKJzXrHLd5epgooufLpq22KDf3nA4B0W', 1237072328, 1238281928),
(118, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'Q44hMjlmW2CMZh1mCLIAZ2Zbo3ULPypP', 1237077008, 1238286608),
(119, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'nx0dyIhIoVsGfYHzPzNULzTbgt46iYeS', 1237077135, 1238286735),
(120, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'AsCaCzW024WZUfrnDiOBL5B9qzJ1cw57', 1237092063, 1238301663),
(121, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'VOZ4YOXsApQPSBcyS5RicthAEgSGs20Z', 1237161175, 1238370775),
(122, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'nj9TBpyTOHuvrNXZnLE152nxFO4irwlB', 1237261788, 1238471388),
(123, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'dYKaJ9J2WfCAKqrtyiUYqsuck8zrcGy5', 1237332935, 1238542535),
(124, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'djxf4j2ZuSOQOPh8To5Jz0ET3ONELEbD', 1237350172, 1238559772),
(125, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'MhyxBLvkhjY1xnjVZAr7CZT96iUEaW6B', 1237418867, 1238628467),
(126, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'XtdJtYLQEla8nvoOcFCCdLfPxO1pEN8Q', 1237439303, 1238648903),
(127, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'BA55cFephr1RiYThuqQRRGaDT8LjW0wn', 1237439857, 1238649457),
(128, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'pitwQ2D12YPH1p7ZIpnXSlEea85yy9yf', 1237501780, 1238711380),
(129, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '53EhsCCDce6s5GNgSNrh2niNnyeEOsgU', 1237535885, 1238745485),
(130, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'J0S060S0GIB2uX44IAqKwezXVrLZHgaG', 1237589630, 1238799230),
(131, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'vYx789L34Ojac5s7uHR5Ap0Hg9WaVYnd', 1237654763, 1238864363),
(132, 10, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'UAkhevfemwrlhK98HcMq0AQdSezVsu2o', 1237655102, 1238864702),
(133, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'wKOOinFxtf2dtory9bVVQJ1bvcUSybm8', 1237655185, 1238864785),
(134, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'JD8hc57vtqy1Oxm3EPdFOgNLUwbaDX3g', 1238017792, 1239227393),
(135, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'PokotzMlVpnML3tRBo7fIPBI6TuKrQPY', 1238039984, 1239249584),
(136, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'gAQIZtzJcsVfJdGyn0fpgAYdAXCJyhGx', 1238047612, 1239257213),
(137, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'QLTpGsmvu4OD7XanDKp3wHk9Bn15lfuD', 1238048107, 1239257707),
(138, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'PPHndn3ZMDVYDWTxu9iL62Q2WhrmnZqH', 1238102992, 1239312592),
(139, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'aFg33HYThH8AzxUX1wAousqQdy3cJMyG', 1238103235, 1239312835),
(140, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'cmfOEnGcRUTb4nLE97H8OWvoOdJ53L5M', 1238106947, 1239316547),
(141, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '6nuLhUS3dVN0Y02CIgvz8uT5LOvU57RS', 1238116217, 1239325817),
(142, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'x2iNrGYFLyXIWi0t5l1GMM46TLqe7jJp', 1238118049, 1239327649),
(143, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'OnyIF7Y6txFoO2fSB7Duw6Q2UHTA3uq3', 1238120338, 1239329938),
(144, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'vhsrY70Kyk4Yws6vM7oaLFUZ6qn2oJrL', 1238141712, 1239351312),
(145, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'O9tQZYCgYiDMul41hY745EKMlzFqAfSm', 1238200225, 1239409825),
(146, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'VyhZ5MhJY34AjBLBrOcS2iTBTXO0QVId', 1238204457, 1239414057),
(147, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'wmPKNJHgkv6LD0I6QFQAurpWEkIbbEGn', 1238206887, 1239416487),
(148, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'muhqElBdVoT0n3jz6VaqN6AvR4ZQltYo', 1238219534, 1239429134),
(149, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'ypGar8OBpWFPaM5jIjqfNMQTODdC8uEY', 1238222303, 1239431903),
(150, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'flagqfalMdEYQTPEXbdE6vxskNFeiKVl', 1238222817, 1239432417),
(151, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'uaMinnsMemIsA9B8AM6lfGEGOVwIgAkU', 1238273543, 1239483143),
(152, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'ka0gX4t563MtV3oKP9vRTq63FdVDEgtB', 1238277211, 1239486811),
(153, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', 'KdJhQ3Qoq5S371rl4LgQToCP9rJ5CqDM', 1238280620, 1239490220),
(154, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '0vZnBuu0eysyuazcIvepvjUdugoaRiaT', 1238299006, 1239508606),
(155, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'DMKqJ0jYn49c1nXUJeSTZ60X1EQ0MWzb', 1238301362, 1239510962),
(156, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'zIwWum8xgRoIBgz3hnuNx7MOveO4GMcl', 1238301760, 1239511360),
(157, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'kSYLkm4KIFYCrTEzTqXxihGlIHP37l8s', 1238315112, 1239524712),
(158, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'hAyOQQaPdiuswIFH2YzUg9MLsuP24tFD', 1238318973, 1239528573),
(159, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'JUzJzGNbwgfr0OKhj0MyRWWZKqhOXoQL', 1238320479, 1239530079),
(160, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '6y1brDtZT8KpzKItISQvNpM9PLuSx91M', 1238329707, 1239539307),
(161, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'Dy92XexGA0nHztspY9bvix0RzBLuAZ8W', 1238358660, 1239568260),
(162, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'g68a4jaH241H9hn0Ip25sWHF9woGYSwZ', 1238360015, 1239569615),
(163, 1, '18442aeab9cd632a5946133c9e268c6e253938d2', '2Rluc2yoCZeuMCUi2KHjlqr4K1xs8Hs4', 1238365847, 1239575447),
(164, 1, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'uFiGNd6sFI7TrSVja7vmERqmZ4m03HCS', 1238371251, 1239580851),
(165, 1, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'wqGHPa14hzCBmgK0KhsYaWwYggjHvqB5', 1238372637, 1239582237);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `roles_users`
--
ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
