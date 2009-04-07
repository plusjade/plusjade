-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2009 at 06:36 PM
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
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(10) NOT NULL DEFAULT 'lightbox',
  `params` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 1, 'Test Album', 'lightbox', '77'),
(2, 1, 'Asian Album', 'lightbox', 'bottom'),
(8, 1, 'Tasty MmmM', 'lightbox', ''),
(9, 1, 'asdfa', 'lightbox', ''),
(11, 3, '', 'cycle', ''),
(15, 56, '', 'galleria', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=132 ;

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
(123, 11, 3, 'hello_kitty.gif', 'Colorful kitty', 1),
(119, 9, 1, 'smiley.jpg', '', 4),
(124, 11, 3, 'hello-kitty-200.jpg', 'blue kitty', 2),
(125, 11, 3, 'hellokitty1.jpg', 'Red kitty', 3),
(129, 15, 56, 'Picture-051.jpg', '', 1),
(130, 15, 56, 'CIMG2612.sized.jpg', '', 2),
(131, 15, 56, 'hello_kitty.gif', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE IF NOT EXISTS `calendars` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `fk_site` int(3) NOT NULL,
  `name` varchar(30) NOT NULL,
  `view` varchar(30) NOT NULL,
  `params` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 1, '', '', ''),
(2, 3, '', '', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `calendar_items`
--

INSERT INTO `calendar_items` (`id`, `parent_id`, `fk_site`, `year`, `month`, `day`, `title`, `desc`) VALUES
(10, 2, 3, 2009, 4, 5, 'Sweepo Party', 'Yay!!'),
(2, 1, 1, 2009, 3, 12, 'i am cool', '<div style="text-align: center;"><h3><span style="font-weight: bold;">=DYou said it!!</span></h3></div>'),
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
  `fk_site` int(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  `view` varchar(40) NOT NULL,
  `params` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 1, 'Standard', '', '');

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
  `view` varchar(25) NOT NULL,
  `position` tinyint(2) unsigned NOT NULL,
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

--
-- Dumping data for table `contact_items`
--

INSERT INTO `contact_items` (`id`, `parent_id`, `fk_site`, `type`, `display_name`, `value`, `view`, `position`, `enable`) VALUES
(66, 1, 1, 2, 'Email', 'plusjade@gmail.com', '', 0, 'yes'),
(2, 1, 1, 1, 'Phone', '626-433-3534', '', 5, 'no'),
(4, 1, 1, 4, 'Business Hours', '<span style="font-style: italic;">Call support</span>\n<br><br>\n<div style="margin-left: 40px;">Monday - Sat: 8am - 8pm<br>\nSunday: Closed<br><br>\n</div>\n\n<span style="font-style: italic;">Email support</span><span style="font-weight: bold;"></span><br><br>\n<div style="margin-left: 40px;">Daily response time: within one hour\n</div>', '', 2, 'yes'),
(79, 0, 16, 1, 'phone', ' 626.288.6607', '', 1, 'yes'),
(80, 0, 16, 2, 'email', 'help@motorcycles-plus.com', '', 4, 'yes'),
(85, 0, 16, 8, 'map', '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;q=729+E+Garvey+Ave,+Monterey+Park,+Los+Angeles,+California+91755&amp;sll=34.077119,-118.130043&amp;sspn=0.011801,0.01781&amp;g=709+W+Ramona+Rd,+Alhambra,+CA+91803&amp;ie=UTF8&amp;cd=1&amp;geocode=FULBBwId57_1-A&amp;split=0&amp;ll=34.07328,-118.108091&amp;spn=0.047207,0.071239&amp;z=14&amp;iwloc=addr&amp;output=embed&amp;s=AARTsJpITF_ZNK9olySinFQZsaskXOxDfQ"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;q=729+E+Garvey+Ave,+Monterey+Park,+Los+Angeles,+California+91755&amp;sll=34.077119,-118.130043&amp;sspn=0.011801,0.01781&amp;g=709+W+Ramona+Rd,+Alhambra,+CA+91803&amp;ie=UTF8&amp;cd=1&amp;geocode=FULBBwId57_1-A&amp;split=0&amp;ll=34.07328,-118.108091&amp;spn=0.047207,0.071239&amp;z=14&amp;iwloc=addr" style="color:#0000FF;text-align:left">View Larger Map</a></small>', '', 5, 'yes'),
(86, 0, 16, 3, 'address', 'Motorcycles Plus<br>729 E. Garvey Ave.<br>Monterey Park, CA 91755', '', 2, 'yes'),
(87, 0, 16, 4, 'Business Hours', 'Monday - Saturday: 9am - 6pm<br>Sunday: Please Call', '', 3, 'yes'),
(100, 1, 1, 5, 'aim', '', '', 4, 'no'),
(101, 1, 1, 8, 'map', 'http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=Alhambra,+CA+91803&sll=37.0625,-95.677068&sspn=50.823846,82.089844&ie=UTF8&ll=34.076052,-118.133755&spn=0.052396,0.080166&t=h&z=14&iwloc=addr', '', 3, 'yes'),
(99, 1, 1, 3, 'Address', 'Alhambra, CA 91803', '', 1, 'yes');

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
  `fk_guid` int(3) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=124 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `fk_site`, `page_id`, `page_name`, `display_name`, `position`, `group`, `enable`) VALUES
(7, 16, 0, 'home', 'home', 1, '1', 'yes'),
(8, 16, 0, 'product', 'Services', 2, '1', 'yes'),
(9, 16, 0, 'about', 'About', 3, '1', 'yes'),
(40, 1, 24, 'examples', 'Examples', 2, '1', 'yes'),
(93, 40, 79, 'contact', 'Contact', 1, '1', 'yes'),
(105, 3, 93, 'New_Page', 'New Page', 0, '1', 'yes'),
(28, 19, 0, 'water', 'BASIC', 2, '1', 'yes'),
(29, 19, 0, 'home', '', 1, '1', 'yes'),
(30, 1, 13, 'pricing', 'Pricing', 1, '1', 'yes'),
(34, 1, 19, 'home', 'Home', 0, '1', 'yes'),
(35, 1, 20, 'contact', 'Contact', 5, '1', 'yes'),
(41, 16, 0, 'contact', 'Contact', 4, '1', 'yes'),
(47, 3, 32, 'about', 'About', 2, '1', 'yes'),
(53, 3, 38, 'home', 'Home Sweet Home =)', 1, '1', 'yes'),
(121, 56, 109, 'home', 'Home', 0, '1', 'yes'),
(59, 3, 44, 'box', 'Box', 3, '1', 'yes'),
(92, 40, 78, 'home', 'Home', 0, '1', 'yes'),
(82, 1, 68, 'tools', 'Tools', 4, '1', 'yes'),
(85, 1, 71, 'themes', 'Themes', 3, '1', 'yes'),
(91, 1, 77, 'test', 'Pics', 6, '1', 'yes'),
(101, 1, 89, 'new_page', 'Calendar', 7, '1', 'yes'),
(123, 56, 111, 'Jade', 'Jade', 1, '1', 'yes');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `fk_site`, `page_name`, `title`, `meta`, `enable`) VALUES
(3, 16, 'home', 'Moto Home Page teee hee', '', 'yes'),
(4, 16, 'about', 'reviews sample page for moto', '', 'yes'),
(6, 16, 'product', 'Motorcycles Plus Waterglass Services', 'services for motorcycles in the san gabriel valley', 'yes'),
(24, 1, 'examples', '+Jade website examples', 'asafsadfsa', 'yes'),
(93, 3, 'New_Page', 'First page since Massive Updates', '', 'yes'),
(12, 19, 'water', 'Water and all its miracles', 'Research paper on water ', 'yes'),
(13, 1, 'pricing', '+Jade - Breakdown of website service package', 'website hosting service package from +Jade', 'yes'),
(38, 3, 'home', '', '', 'yes'),
(14, 19, 'home', '', '', 'yes'),
(71, 1, 'themes', '+Jade Proffessional theme creation for your busine', '+Jade Proffessional theme creation for your busine', 'yes'),
(19, 1, 'home', '+Jade managed website hosting for small businesses', '+Jade managed website hosting for small businesses', 'yes'),
(20, 1, 'contact', 'Contact +Jade', '', 'yes'),
(25, 16, 'contact', '', '', 'yes'),
(32, 3, 'about', 'About page', '', 'yes'),
(109, 56, 'home', '', '', 'yes'),
(44, 3, 'box', 'Smello', 'Jello', 'yes'),
(68, 1, 'tools', '', '', 'yes'),
(78, 40, 'home', 'Sam & Leon Furniture Co - Fine Oriental Furniture ', 'Fine Oriental Furniture & Accessories in Pasadena, CA and the greater San Gabriel Valley.', 'yes'),
(79, 40, 'contact', 'Contact us : Sam & Leon Furniture Co', '', 'yes'),
(77, 1, 'test', 'Cool Album page', 'Tee Hee !', 'yes'),
(89, 1, 'new_page', 'New page', '', 'yes'),
(111, 56, 'Jade', '', '', 'yes');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `pages_tools`
--

INSERT INTO `pages_tools` (`guid`, `page_id`, `fk_site`, `tool`, `tool_id`, `tag`, `position`) VALUES
(1, 19, 1, 4, 1, '', 2),
(2, 77, 1, 2, 1, '', 5),
(3, 24, 1, 3, 1, '', 2),
(4, 68, 1, 3, 2, '', 1),
(5, 71, 1, 3, 3, '', 1),
(6, 20, 1, 5, 1, '', 1),
(8, 77, 1, 2, 2, '', 3),
(9, 77, 1, 2, 8, '', 2),
(10, 77, 1, 2, 9, '', 4),
(11, 19, 1, 1, 1, '', 1),
(12, 19, 1, 1, 2, 'footer', 4),
(13, 13, 1, 1, 3, '', 2),
(56, 109, 56, 4, 3, '', 3),
(17, 3, 1, 1, 9, '', 2),
(25, 89, 1, 7, 1, '', 1),
(26, 89, 1, 1, 16, '', 2),
(43, 93, 3, 1, 32, '', 1),
(44, 93, 3, 7, 2, '', 3),
(45, 93, 3, 2, 11, '', 2),
(55, 109, 56, 1, 39, '', 2),
(51, 77, 1, 1, 35, '', 1),
(57, 111, 56, 2, 15, '', 2);

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
(6, 1),
(10, 1),
(11, 1),
(23, 1),
(24, 1),
(27, 1),
(24, 2);

-- --------------------------------------------------------

--
-- Table structure for table `showrooms`
--

CREATE TABLE IF NOT EXISTS `showrooms` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `view` varchar(40) NOT NULL,
  `params` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `showrooms`
--

INSERT INTO `showrooms` (`id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 1, 'examples', 'list', ''),
(2, 1, 'tools', 'gallery', ''),
(3, 1, 'themes', 'gallery', '');

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
(30, 2, 1, 'Amateur Apsurd', 'Yahboi@', 'supervisor', 900, 0, '1238299570.jpg'),
(2, 1, 1, 'http://motorcycles-plus.com', 'Motorcycle shop needed simple website to convey shop hours, rates, services, and preferred contact method. <div><br class="webkit-block-placeholder"></div><div>Makes use of the gallery module for sweet effect</div>', '', 200, 2, '1233651256.jpg'),
(3, 1, 1, 'http://webemb.com', 'Embroidery shop was looking into getting a professional website done. Too many solutions and not enough time to sit down and talk out his take-over-the-world plans ; we finally convinced the owner to just get something up already! Webemb is now in our system currently getting made!<br>', '', 45, 1, '1233651385.jpg'),
(4, 1, 1, 'http://pasadena-furniture.com', 'Local furniture store just added to the system today. This is a good example of how easy it is to port a free theme over to our system. Thanks to the great guys at <a target="" title="" href="http://www.freecsstemplates.org/">http://www.freecsstemplates.org/</a> the furniture company did not have to pay a cent toward design costs and can now worry about gathering content for their pages and of course: marketing! =D', '', 4456, 3, '1233651275.jpg'),
(6, 2, 1, 'Showroom', 'Prominently display your core products or services in a beautifully organized showroom display.<br>Customize item layout, enable juicy user functionality and more ...', 'The showroom offers a quick and easy way to effectively highlight your core product or service offering in a gallery format.<br><br><ul><li>Organize your core products and/or services into an easy to view gallery.</li><li>Create individual product pages to convey more detailed information.</li><li>Manage and display product images cleanly using sexy image gallery tools.</li></ul><br>The tools page and all the modules on them (including this page) was built using the Showroom tool. ', 200, 2, '1235525135.gif'),
(7, 2, 1, 'Contacts', 'Instantly build a highly effective "Contact page" to prompt user action. Elegantly informs and guides users on various ways to contact<br>your business. ', 'Contact pages are frequently the most heavily used "action" taken by a user.<br>Once the user gains interest into your company, he will look for a "next step" action to perform.<br>Commonly this will be a phone number to call, an email form to fill out, or a location to visit. <br><br>The Contact Manager allows you to <span style="font-style: italic;">instantly </span>create clean, organized, and actionable methods of contact.<br><br><ul><li>Preferred phone number(s)</li><li>Preferred email address(es)</li><li>Store or location address (with map)</li><li>Hours of operation</li><li>Online Chat availability.<br></li><li>Form emailers (email sent right from your website)<br></li></ul>', 0, 3, '1235525157.gif'),
(8, 2, 1, 'FAQ Manager', 'A simple Frequently Asked Questions builder optimized around usability. Get relevant information to your customers - fast, without interaction on your part. Display question/answer pairs sexily without needing to reload the page. View example.', '', 0, 4, '1235525233.gif'),
(9, 2, 1, 'Slide Panel', 'Have a lot of information to get across to your customers? Organize and display pertinent information in a concise, guided, and easily digestable manner using our slide panel builder.', '', 0, 5, '1235525255.gif'),
(10, 2, 1, 'Customer Reviews', 'Provide an easy method for customers to submit reviews or testimonials regarding your company.<br>Manage your reviews right from your site, choose which to display first or highlight. <br>Easy reply feature shows customers you care! ', 'Reviews and testimonials are great assets online. This "user generated" content builds your reputation online and earns valuable trust points with new customers browsing your site. <br><br>The reviews module also allows you to tap into 3rd party review sites like yelp.com or insider pages. These sites help spread your name to external websites, thus increasing your market reach. ', 0, 6, '1235528870.gif'),
(11, 2, 1, 'Image Gallery', 'A cool image gallery ! Huzza =D', '', 0, 7, '1235534987.gif'),
(15, 3, 1, 'Green Web', 'Green Web ', 'Sample <br>', 22, 0, '1236051511.jpg'),
(14, 3, 1, 'Intercraft', '', '', 0, 1, '1236051546.jpg'),
(17, 3, 1, 'Corporate', '<div style="text-align: center;">Corporate red theme<br></div>', '', 0, 2, '1236051558.jpg'),
(18, 3, 1, 'Beige Fixed', 'Beige', '', 0, 3, '1236051570.jpg'),
(29, 2, 1, 'Event Calendar', 'Let customers know where your next event will be with a clear and easy to use calendar.<br><br>Users can elect to be updated on the day of the event via email.', '', 0, 1, '1238131381.gif');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

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
(56, 'test', '', '', '', '', '', '', 'snowflakes');

-- --------------------------------------------------------

--
-- Table structure for table `slide_panels`
--

CREATE TABLE IF NOT EXISTS `slide_panels` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(40) NOT NULL,
  `params` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `slide_panels`
--

INSERT INTO `slide_panels` (`id`, `fk_site`, `name`, `view`, `params`) VALUES
(1, 1, '', 'standard', ''),
(3, 56, '', '', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `slide_panel_items`
--

INSERT INTO `slide_panel_items` (`id`, `parent_id`, `fk_site`, `title`, `body`, `position`) VALUES
(1, 1, 1, '1. Create', '<h2>1. Create and Customize Your Website.</h2>\n<div class="image_placeholder"></div>\n<span style="font-weight: bold;">Design -</span>\n<br><br>\n<div style="margin-left: 40px;">Choose from one of our many quick-start themes and be on your way in no time, or\n\n<span style="font-weight: bold;"></span> use our theme panel to easily customize and edit the markup and css.<br><br>A professionally designed custom theme can be easily uploaded to your account to give you a truly custom professional look <span style="font-style: italic; font-weight: bold;">without</span> the outrageous custom programming price.<br></div><span style="font-weight: bold;"><br></span><span style="font-weight: bold;">Content -</span><br><br><div style="margin-left: 40px;">Each site automatically loads 6 core pages commonly found in the best of business websites:<br></div><ul style="margin-left: 40px;"><li>Home page</li><li>Product(s) page </li><li>Reviews/Testimonials </li><li>Frequently Asked Questions</li><li>About</li><li>Contact</li></ul>Every account is a full fledged hosted website. We manage all the technology so you can focus on content and ... your customers! =D<br>\n', 0),
(2, 1, 1, '2. Market', '<h2><span style="font-weight: bold;">2. Market Your Website.</span></h2>\n<div class="image_placeholder"></div>Follow step-by-step tutorials on various kinds of internet marketing and growing your userbase from the ground up.<br><br>Marketing and promotion are the most crucial part of creating a website.<br>Otherwise what is the point?<br><div style="margin-left: 40px;"><br></div>', 1),
(3, 1, 1, '3. Analyze', '<h2><span style="font-weight: bold;">3. Track and Analyze Data.</span></h2><div class="image_placeholder"></div>Seamless integration with both free and premium web analytics trackers including google, clicky, and crazyegg. <br><br>We show you how to use this data to build better pages, launch a/b split tests, and organically optimize your website. Properly reading and responding to data is crucial to growing your business presence online.', 2),
(4, 1, 1, '4. Grow', '<h2><span style="font-weight: bold;">4. Respond and Grow.</span></h2><div class="image_placeholder"></div>As a business, you know the importance of getting down to business. Your website needs to produce an ROI. <br><br>Create your content -&gt; <br>Market your site -&gt; <br>Respond to data -&gt;<br>Grow your customer base!<br><br>Sure it takes work and planning. <br>Join our team and instantly eliminate all your technology obstacles,<br>Oh, and we have the plan too, so let''s get started...', 3),
(45, 3, 56, 'New', 'Suckassss!!1', 1),
(46, 3, 56, 'Skippy', 'Tee hee!', 2);

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(3) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(25) NOT NULL,
  `params` varchar(25) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `texts`
--

INSERT INTO `texts` (`id`, `fk_site`, `name`, `view`, `params`, `body`) VALUES
(1, 1, 'header', '', '', '<h1 style="font-weight: normal; text-align: center;">STOP putting your website plans on hold!<br></h1>\n<div style="text-align: center; margin-bottom: 10px;">Get an effective website up and running for your business by day''s end, guaranteed!<br><br>No web experience? No problem!</div>\n'),
(2, 1, '', '', '', '<div style="text-align: center; margin-top: 15px;">\n<h2>Take the Tour!</h2>\n<h2>Sign up!</h2></div>'),
(3, 1, 'pricing text', '', '', '<span style="font-weight: bold;"><br></span><h3><span style="font-weight: bold;">Custom Website Design and Page Creation.</span></h3><h3><span style="font-weight: bold;"></span></h3><span style="font-weight: bold;"><br></span><div style="margin-left: 40px;">Your website is hand designed to your custom specifications. <br>We also personally configure your pages with your content. <br>Includes a consultation for discussing main business content and images. <br>We provide copy and image editing/optimization. This is needed to ensure your content clearly and concisely conveys your business message.  <br>Page creation includes the 6 core page modules. Extra pages can be implemented at any time for a small fee. <br><br>Pricing for this service ranges from $200 - $500 depending on design and content complexity. <br>This is a one-time setup fee. If at any time you decide to cancel your hosting and management services (see below) with +Jade, your website and its content will still belong to you. <br>You can easily export a full static rendition of your website and all its pages.<br><br>At this early stage this is our only option. Shortly we will provide page editing and creation tools to our users. This will allow you to edit and configure your pages yourself from within your browser window. There will be no extra cost for self-editing.<br><br></div><span style="font-weight: bold;"></span><br><h3>Full Website Hosting, Management, and Optimization Services.</h3><ul><li>Full website hosting (link describing what hosting is)</li><li>Step-by-step plan of action tutorials and documention for marketing your website and collecting and analyzing data.</li><li>Web analytics dashboard used to track and measure your data.</li><li>User interface for responding to data by changing elements of your site.</li><li>A/B split testing platform. Easily create 2 different pages and test them against eachother to see which interests/compels users more effectively.</li><li>Progress reports, for showing how much you have grown and steps to improve.<br><br>Team -<br><br></li><li>Continual codebase refinement and optimization. </li><li>Continual rollout of website functions and toolsets. (available to all users)</li><li>Up to date technology - your website never gets old. Always roll out latest practices.</li><li>Knowledge base - Proactive tutorials regarding things that produce results for you. <br>(Not learning how to be a computer scientist)<br></li></ul><div style="margin-left: 40px;"><br><span style="font-weight: bold;">Price:</span> $12.00 monthly<br><br>OR $108 per year if paid upfront. A 25% cost savings or $9.00 monthly.<br><br><br><br></div>'),
(9, 16, '', '', '', '<div style="text-align: center;"><h1>sa;lfkjsf;lsjfds;ljfs;lkajfs;ajfa</h1></div>'),
(16, 1, '', '', '', '<h2 style="text-align: center;">Join us on the following event days,</h2><h2 style="text-align: center;"> where we will be showcasing our newest products!</h2>'),
(32, 3, '', '', '', '<h1 style="text-align: center;">Pandaland Princess</h1>'),
(35, 1, '', '', '', '<h2 style="text-align: center;">Tee hee</h2>'),
(39, 56, '', '', '', '<div style="text-align: center;"><h1>Hello how are you ???</h1></div>');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `text_items`
--

INSERT INTO `text_items` (`id`, `parent_id`, `fk_site`, `body`, `position`) VALUES
(1, 1, 1, '<h1 style="font-weight: normal; text-align: center;">STOP putting your website plans on hold!<br></h1>\r\n<div style="text-align: center;">Get an effective website up and running for your business by day''s end, guaranteed!<br><br>No web experience? No problem! </div>', 1),
(2, 1, 1, '<div style="text-align: center;" jquery1234417452080="89">\r\n<h2>Take the Tour!</h2>\r\n<h2>Sign up!<br></h2></div>', 2);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `name`) VALUES
(1, 'redcross'),
(2, 'nonzero'),
(3, 'misc'),
(4, 'snowflakes'),
(5, 'green_web'),
(6, 'corporate'),
(7, 'intercraft'),
(8, 'beige'),
(9, 'custom');

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
  `password` char(60) NOT NULL,
  `token` varchar(32) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `client_site_id`, `email`, `username`, `password`, `token`, `logins`, `last_login`) VALUES
(6, 3, 'panda@panda.com', 'panda', 'c02579ffc93034639d51302de51929d5eaaf7500919956aa7f', 'LzY1HCX22CnOy04uKiRzcN0wokbDsKJk', 54, 1238634842),
(10, 16, 'moto@moto.com', 'moto', '57a94fe0315a32718adc8c6a3cb46f0f1555aa7e7b00e49aac', 'Om56X42I4RJyLfbkxblhw53qP4GcujGq', 7, 1237655109),
(11, 40, 'bob@bob.com', 'bob', 'b96bad111e88a3a3bc6f102008a05fdf46bf9a7141874acaae', 'yXsfSrz7p9GbVJK97dTCSVvGJN71UJWX', 2, 1236730164),
(22, 51, 'sample@sample.com', 'sample', '21a7ff9dcd25a4ccfe9fb7a54b9cc949bb19124e79bbf3e322', 'GRJobpJzpVtrHsUmzWrqqkEjW7EoiKk5', 0, NULL),
(23, 52, 'sauce@sauce.com', 'sauce', '4c44e7004f23656efd8f534c1e4ebe64ef6e094380e4ff4309', 'bAA39MEvvGuuznV7HLCe7nUxFLYUyQyj', 3, 1238650782),
(24, 1, 'admin@plusjade.com', 'jade', 'ec645008500510eb408b40b52a9c85f361bb8d273a6d5b6d6a', 'JfzZgQAGItl10qNm5ucykMsdVU13mRVS', 37, 1238795855),
(27, 56, 'test@test.com', 'test', 'fa3d5b9fa4cbd5edabe3f9e271e6849e45d0213773fde26ea7', 'l2KYFIFTpdEzBqkuRRXQUSmFH7Q86WeW', 12, 1238739547);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=208 ;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `user_agent`, `token`, `created`, `expires`) VALUES
(132, 10, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'UAkhevfemwrlhK98HcMq0AQdSezVsu2o', 1237655102, 1238864702),
(181, 6, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'kua3W4f9766tyg0nxWf6ukihdkfOJs9M', 1238572325, 1239781925),
(185, 23, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'PiJtOAwYMRYdfSshszMhZDMZ58gjaHhX', 1238649843, 1239859443),
(186, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '6XPc0UTyMCPRJyze6kD84iGWUqxjlstU', 1238650430, 1239860030),
(187, 23, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '5VoeTk4iin5hyxT7Ck1tzpG7qbwmDFUY', 1238650782, 1239860382),
(188, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'XpPpiy2QjQNE2JAbv6aPeauIcTmSY83w', 1238650937, 1239860537),
(189, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'wJIn2AEmwVmaWMoXk5Gd2RYTqJakt5Kz', 1238655925, 1239865525),
(190, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'h3Y64agXtvAOf0AVIJJjYAeCtMiwd970', 1238655961, 1239865561),
(191, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '54b7KdA9UlHEBY8rpvqcm4vgK33VweKi', 1238656334, 1239865934),
(192, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'BE7NSzPdUW3HSRovFw8PY2zjAdinA2Kh', 1238656722, 1239866322),
(193, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '6xyZOUqtq2VmLhq2NuOc9TYknqYxqFlf', 1238707350, 1239916950),
(194, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'eFUCsUdOrBfwYOOJnFDxN38zgcfyMuo1', 1238722871, 1239932471),
(195, 27, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', '1hAGvkrk4J2djGpGAZykFqAm7mcLvYDq', 1238722909, 1239932509),
(196, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'oTprHQiyDfbDCozvLPwDGs8VwWF6gT3X', 1238731020, 1239940621),
(197, 24, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'P8bdtzMWM1wJiVf0AoodzBuLgVfrDgx2', 1238731751, 1239941351),
(198, 27, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'zmrdJyb7zh7Sh0MUdZIFXOKfiLdeP4qy', 1238732810, 1239942410),
(199, 27, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'AnCeyd2mBFtjiemtXVnFhF219W9ZvI6y', 1238736397, 1239945997),
(200, 27, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'KxcbWMSZqsw3OzKg8pKWfMHIVGKgpIf7', 1238739543, 1239949143),
(201, 24, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', '3DSV22OXuWT6gpei5NNAEyGvZ834D0gs', 1238741327, 1239950927),
(202, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'YweS8Sz6ln63Bl58580HhZgCZbZ6b20Y', 1238743963, 1239953563),
(203, 24, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'eDiS5kLyWtFPq97rFT37d2W8N1u2JHDy', 1238744910, 1239954510),
(204, 24, '18442aeab9cd632a5946133c9e268c6e253938d2', 'mJvKUFeLHYCGeuFoRY74ZzjaFW8myLR8', 1238748998, 1239958598),
(205, 24, 'a02a3a6223d7c8e4f841a344a5bf67be6a2a452c', 'sXEd4Y7Mh2W5FPiOlxzYSgeu6gWIWkGX', 1238792459, 1240002059),
(206, 24, 'f5650ba256dfe6650412a51e8b46b72a39cbf46b', 'BJq9kcShGTuuCHMVs60kcjHrhbyn7qn0', 1238795024, 1240004624),
(207, 24, '18442aeab9cd632a5946133c9e268c6e253938d2', '3Yi91g6x9W9ikFfIyGnqMRwGnzqJ2xfP', 1238795662, 1240005262);

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
