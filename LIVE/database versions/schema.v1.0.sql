-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2009 at 01:18 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

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
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `view` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'lightbox',
  `params` varchar(40) CHARACTER SET utf8 NOT NULL,
  `attributes` varchar(35) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `album_items`
--

CREATE TABLE IF NOT EXISTS `album_items` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `path` varchar(25) CHARACTER SET utf8 NOT NULL,
  `caption` varchar(50) CHARACTER SET utf8 NOT NULL,
  `position` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(25) CHARACTER SET utf8 NOT NULL,
  `view` varchar(25) CHARACTER SET utf8 NOT NULL,
  `params` varchar(25) CHARACTER SET utf8 NOT NULL,
  `sticky_posts` varchar(50) CHARACTER SET utf8 NOT NULL,
  `attributes` varchar(35) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_items`
--

CREATE TABLE IF NOT EXISTS `blog_items` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `url` varchar(200) NOT NULL,
  `status` enum('draft','publish') NOT NULL DEFAULT 'draft',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `title` varchar(80) CHARACTER SET utf8 NOT NULL,
  `body` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_items_comments`
--

CREATE TABLE IF NOT EXISTS `blog_items_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(9) unsigned NOT NULL,
  `item_id` int(9) unsigned NOT NULL,
  `fk_site` int(9) unsigned NOT NULL,
  `body` longtext,
  `name` varchar(250) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `author_ip` int(10) DEFAULT '0',
  `author_agent` varchar(255) DEFAULT '',
  `status` varchar(32) DEFAULT 'denied',
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blog_items_tags`
--

CREATE TABLE IF NOT EXISTS `blog_items_tags` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(9) unsigned NOT NULL,
  `item_id` int(9) unsigned NOT NULL,
  `fk_site` int(9) unsigned NOT NULL,
  `value` varchar(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE IF NOT EXISTS `calendars` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `view` varchar(30) NOT NULL,
  `params` varchar(30) NOT NULL,
  `attributes` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_items`
--

CREATE TABLE IF NOT EXISTS `calendar_items` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `year` int(4) unsigned NOT NULL,
  `month` int(2) unsigned NOT NULL,
  `day` int(2) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `view` varchar(40) NOT NULL,
  `params` varchar(40) NOT NULL,
  `attributes` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact_items`
--

CREATE TABLE IF NOT EXISTS `contact_items` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `type` smallint(2) unsigned NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `view` varchar(25) NOT NULL,
  `position` tinyint(2) unsigned NOT NULL,
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_types`
--

CREATE TABLE IF NOT EXISTS `contact_types` (
  `type_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  `desc` varchar(400) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `title` varchar(35) NOT NULL,
  `view` varchar(10) NOT NULL DEFAULT 'lightbox',
  `params` varchar(40) NOT NULL,
  `attributes` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq_items`
--

CREATE TABLE IF NOT EXISTS `faq_items` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `question` varchar(50) NOT NULL,
  `answer` text NOT NULL,
  `position` int(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `navigations`
--

CREATE TABLE IF NOT EXISTS `navigations` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `root_id` int(7) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `view` varchar(10) NOT NULL,
  `params` varchar(40) NOT NULL,
  `attributes` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `navigation_items`
--

CREATE TABLE IF NOT EXISTS `navigation_items` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `display_name` varchar(40) NOT NULL,
  `type` varchar(40) NOT NULL,
  `data` varchar(100) NOT NULL,
  `local_parent` int(7) unsigned NOT NULL,
  `lft` int(3) unsigned NOT NULL,
  `rgt` int(3) unsigned NOT NULL,
  `position` int(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `page_name` varchar(60) NOT NULL,
  `label` varchar(35) NOT NULL,
  `title` varchar(60) NOT NULL,
  `meta` tinytext NOT NULL,
  `position` int(4) unsigned NOT NULL,
  `menu` enum('yes','no') NOT NULL DEFAULT 'no',
  `enable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_tools`
--

CREATE TABLE IF NOT EXISTS `pages_tools` (
  `guid` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `tool` int(2) unsigned NOT NULL,
  `tool_id` int(7) unsigned NOT NULL,
  `container` int(1) unsigned NOT NULL DEFAULT '1',
  `position` int(3) NOT NULL COMMENT 'can be neg.',
  PRIMARY KEY (`guid`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Table structure for table `showrooms`
--

CREATE TABLE IF NOT EXISTS `showrooms` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `root_id` int(7) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `view` varchar(40) NOT NULL DEFAULT 'gallery',
  `params` varchar(40) NOT NULL,
  `attributes` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_items`
--

CREATE TABLE IF NOT EXISTS `showroom_items` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `url` varchar(60) NOT NULL,
  `name` varchar(60) NOT NULL,
  `local_parent` int(7) unsigned NOT NULL,
  `lft` int(4) unsigned NOT NULL,
  `rgt` int(4) unsigned NOT NULL,
  `position` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_items_meta`
--

CREATE TABLE IF NOT EXISTS `showroom_items_meta` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `url` varchar(80) NOT NULL,
  `cat_id` int(4) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `intro` tinytext NOT NULL,
  `body` text NOT NULL,
  `img` varchar(25) NOT NULL,
  `position` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `subdomain` varchar(50) NOT NULL,
  `custom_domain` varchar(50) NOT NULL,
  `homepage` varchar(30) NOT NULL DEFAULT 'home',
  `banner` varchar(20) NOT NULL,
  `theme` varchar(30) NOT NULL,
  PRIMARY KEY (`site_id`),
  KEY `url` (`subdomain`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sites_users`
--

CREATE TABLE IF NOT EXISTS `sites_users` (
  `fk_users` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  KEY `fk_users` (`fk_users`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slide_panels`
--

CREATE TABLE IF NOT EXISTS `slide_panels` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(40) NOT NULL,
  `params` varchar(40) NOT NULL,
  `attributes` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slide_panel_items`
--

CREATE TABLE IF NOT EXISTS `slide_panel_items` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `position` int(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site` (`fk_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `texts`
--

CREATE TABLE IF NOT EXISTS `texts` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(25) NOT NULL,
  `params` varchar(25) NOT NULL,
  `body` text NOT NULL,
  `attributes` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tools_list`
--

CREATE TABLE IF NOT EXISTS `tools_list` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `protected` enum('yes','no') NOT NULL DEFAULT 'no',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'no',
  `desc` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
