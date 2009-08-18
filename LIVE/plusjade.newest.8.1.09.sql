-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 02, 2009 at 12:39 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6-2ubuntu4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'plusjade'
--

-- --------------------------------------------------------

--
-- Table structure for table 'accounts'
--

CREATE TABLE IF NOT EXISTS accounts (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(25) character set utf8 NOT NULL,
  `view` varchar(25) character set utf8 NOT NULL,
  login_title varchar(50) character set utf8 NOT NULL,
  create_title varchar(50) character set utf8 NOT NULL,
  attributes varchar(35) character set utf8 NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY fk_site (fk_site)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table 'account_roles'
--

CREATE TABLE IF NOT EXISTS account_roles (
  id int(11) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY uniq_name (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table 'account_users'
--

CREATE TABLE IF NOT EXISTS account_users (
  id int(11) unsigned NOT NULL auto_increment,
  fk_site int(7) NOT NULL,
  email varchar(127) NOT NULL,
  username varchar(32) NOT NULL default '',
  `password` char(60) NOT NULL,
  token varchar(32) NOT NULL,
  logins int(10) unsigned NOT NULL default '0',
  last_login int(10) unsigned default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY token (token),
  KEY email (email)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table 'account_users_sites'
--

CREATE TABLE IF NOT EXISTS account_users_sites (
  account_user_id int(9) unsigned NOT NULL,
  site_id int(9) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'account_user_meta'
--

CREATE TABLE IF NOT EXISTS account_user_meta (
  id int(7) NOT NULL auto_increment,
  fk_site int(9) unsigned NOT NULL,
  account_user_id int(9) unsigned NOT NULL,
  `key` varchar(72) NOT NULL,
  `value` tinytext NOT NULL,
  PRIMARY KEY  (id),
  KEY account_user_id (account_user_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table 'account_user_tokens'
--

CREATE TABLE IF NOT EXISTS account_user_tokens (
  id int(11) unsigned NOT NULL auto_increment,
  account_user_id int(11) unsigned NOT NULL,
  user_agent varchar(40) NOT NULL,
  token varchar(32) NOT NULL,
  created int(10) unsigned NOT NULL,
  expires int(10) unsigned NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY uniq_token (token),
  KEY fk_friend_id (account_user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table 'albums'
--

CREATE TABLE IF NOT EXISTS albums (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(30) character set utf8 NOT NULL,
  `view` varchar(10) character set utf8 NOT NULL default 'lightbox',
  params varchar(40) character set utf8 NOT NULL,
  images text NOT NULL,
  attributes varchar(35) character set utf8 NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table 'blogs'
--

CREATE TABLE IF NOT EXISTS blogs (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(25) character set utf8 NOT NULL,
  `view` varchar(25) character set utf8 NOT NULL,
  params varchar(25) character set utf8 NOT NULL,
  sticky_posts varchar(50) character set utf8 NOT NULL,
  attributes varchar(35) character set utf8 NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table 'blog_posts'
--

CREATE TABLE IF NOT EXISTS blog_posts (
  id int(7) unsigned NOT NULL auto_increment,
  blog_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  url varchar(200) NOT NULL,
  `status` enum('draft','publish') NOT NULL default 'publish',
  created datetime NOT NULL,
  updated datetime NOT NULL,
  title varchar(80) character set utf8 NOT NULL,
  body text character set utf8 NOT NULL,
  PRIMARY KEY  (id),
  KEY parent_id (blog_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table 'blog_post_comments'
--

CREATE TABLE IF NOT EXISTS blog_post_comments (
  id int(11) unsigned NOT NULL auto_increment,
  blog_id int(9) unsigned NOT NULL,
  blog_post_id int(9) unsigned NOT NULL,
  fk_site int(9) unsigned NOT NULL,
  body longtext,
  `name` varchar(250) default NULL,
  url varchar(128) default NULL,
  email varchar(128) default NULL,
  author_ip int(10) default '0',
  author_agent varchar(255) default '',
  `status` varchar(32) default 'denied',
  created_at datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (id),
  KEY item_id (blog_post_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table 'blog_post_tags'
--

CREATE TABLE IF NOT EXISTS blog_post_tags (
  id int(9) unsigned NOT NULL auto_increment,
  blog_id int(9) unsigned NOT NULL,
  blog_post_id int(9) unsigned NOT NULL,
  fk_site int(9) unsigned NOT NULL,
  `value` varchar(36) NOT NULL,
  PRIMARY KEY  (id),
  KEY parent_id (blog_id),
  KEY item_id (blog_post_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table 'calendars'
--

CREATE TABLE IF NOT EXISTS calendars (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `view` varchar(30) NOT NULL,
  params varchar(30) NOT NULL,
  attributes varchar(35) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table 'calendar_items'
--

CREATE TABLE IF NOT EXISTS calendar_items (
  id int(7) unsigned NOT NULL auto_increment,
  calendar_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  `year` int(4) unsigned NOT NULL,
  `month` int(2) unsigned NOT NULL,
  `day` int(2) unsigned NOT NULL,
  title varchar(50) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table 'contacts'
--

CREATE TABLE IF NOT EXISTS contacts (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `view` varchar(40) NOT NULL,
  params varchar(40) NOT NULL,
  attributes varchar(35) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table 'contact_items'
--

CREATE TABLE IF NOT EXISTS contact_items (
  id int(7) unsigned NOT NULL auto_increment,
  parent_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  `type` smallint(2) unsigned NOT NULL,
  display_name varchar(50) NOT NULL,
  `value` text NOT NULL,
  `view` varchar(25) NOT NULL,
  position tinyint(2) unsigned NOT NULL,
  `enable` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (id),
  KEY fk_site (fk_site)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table 'contact_types'
--

CREATE TABLE IF NOT EXISTS contact_types (
  type_id tinyint(1) unsigned NOT NULL auto_increment,
  `type` varchar(25) NOT NULL,
  `desc` varchar(400) NOT NULL,
  PRIMARY KEY  (type_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table 'faqs'
--

CREATE TABLE IF NOT EXISTS faqs (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  title varchar(35) NOT NULL,
  `view` varchar(10) NOT NULL default 'lightbox',
  params varchar(40) NOT NULL,
  attributes varchar(35) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table 'faq_items'
--

CREATE TABLE IF NOT EXISTS faq_items (
  id int(7) unsigned NOT NULL auto_increment,
  parent_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  question varchar(50) NOT NULL,
  answer text NOT NULL,
  position int(3) unsigned NOT NULL,
  PRIMARY KEY  (id),
  KEY fk_site (fk_site)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table 'forums'
--

CREATE TABLE IF NOT EXISTS forums (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(25) character set utf8 NOT NULL,
  `view` varchar(25) character set utf8 NOT NULL,
  params varchar(25) character set utf8 NOT NULL,
  sticky_posts varchar(50) character set utf8 NOT NULL,
  attributes varchar(35) character set utf8 NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table 'forum_cats'
--

CREATE TABLE IF NOT EXISTS forum_cats (
  id int(7) unsigned NOT NULL auto_increment,
  forum_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  url varchar(60) NOT NULL,
  `name` varchar(60) NOT NULL,
  position int(4) unsigned NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table 'forum_cat_posts'
--

CREATE TABLE IF NOT EXISTS forum_cat_posts (
  id int(9) unsigned NOT NULL auto_increment,
  fk_site int(9) unsigned NOT NULL,
  forum_cat_id int(9) unsigned NOT NULL,
  title varchar(72) NOT NULL,
  forum_cat_post_comment_id int(7) NOT NULL,
  comment_count int(7) NOT NULL default '0',
  last_active int(10) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table 'forum_cat_post_comments'
--

CREATE TABLE IF NOT EXISTS forum_cat_post_comments (
  id int(9) unsigned NOT NULL auto_increment,
  fk_site int(9) unsigned NOT NULL,
  forum_cat_post_id int(9) unsigned NOT NULL,
  account_user_id int(9) unsigned NOT NULL,
  body text NOT NULL,
  created int(10) unsigned NOT NULL,
  vote_count int(9) NOT NULL,
  is_post enum('0','1') NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table 'forum_comment_votes'
--

CREATE TABLE IF NOT EXISTS forum_comment_votes (
  account_user_id int(9) NOT NULL,
  forum_cat_post_comment_id int(9) NOT NULL,
  fk_site int(7) NOT NULL,
  KEY author_id (account_user_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'navigations'
--

CREATE TABLE IF NOT EXISTS navigations (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  root_id int(7) unsigned NOT NULL,
  title varchar(80) NOT NULL,
  `view` varchar(10) NOT NULL,
  params varchar(40) NOT NULL,
  attributes varchar(50) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table 'navigation_items'
--

CREATE TABLE IF NOT EXISTS navigation_items (
  id int(7) unsigned NOT NULL auto_increment,
  parent_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  display_name varchar(40) NOT NULL,
  `type` varchar(40) NOT NULL,
  `data` varchar(100) NOT NULL,
  local_parent int(7) unsigned NOT NULL,
  lft int(3) unsigned NOT NULL,
  rgt int(3) unsigned NOT NULL,
  position int(3) unsigned NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table 'pages'
--

CREATE TABLE IF NOT EXISTS pages (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  page_name varchar(60) NOT NULL,
  label varchar(35) NOT NULL,
  template varchar(40) NOT NULL,
  title varchar(100) NOT NULL,
  meta tinytext NOT NULL,
  position int(4) unsigned NOT NULL,
  menu enum('yes','no') NOT NULL default 'no',
  `enable` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (id),
  KEY title (title),
  KEY fk_site (fk_site)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table 'pages_tools'
--

CREATE TABLE IF NOT EXISTS pages_tools (
  guid int(7) unsigned NOT NULL auto_increment,
  page_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  system_tool_id int(2) unsigned NOT NULL,
  tool_id int(7) unsigned NOT NULL,
  container int(1) unsigned NOT NULL default '1',
  position int(3) NOT NULL COMMENT 'can be neg.',
  PRIMARY KEY  (guid),
  KEY page_id (page_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Table structure for table 'showrooms'
--

CREATE TABLE IF NOT EXISTS showrooms (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  root_id int(7) unsigned NOT NULL,
  home_cat varchar(50) NOT NULL,
  `name` varchar(40) NOT NULL,
  `view` varchar(40) NOT NULL default 'gallery',
  params varchar(40) NOT NULL,
  attributes varchar(35) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table 'showroom_cats'
--

CREATE TABLE IF NOT EXISTS showroom_cats (
  id int(7) unsigned NOT NULL auto_increment,
  showroom_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  url varchar(60) NOT NULL,
  `name` varchar(60) NOT NULL,
  local_parent int(7) unsigned NOT NULL,
  lft int(4) unsigned NOT NULL,
  rgt int(4) unsigned NOT NULL,
  position int(4) unsigned NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table 'showroom_cat_items'
--

CREATE TABLE IF NOT EXISTS showroom_cat_items (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  url varchar(80) NOT NULL,
  showroom_cat_id int(4) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  intro tinytext NOT NULL,
  body text NOT NULL,
  images text NOT NULL,
  position int(4) unsigned NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table 'sites'
--

CREATE TABLE IF NOT EXISTS sites (
  id int(7) unsigned NOT NULL auto_increment,
  subdomain varchar(50) NOT NULL,
  custom_domain varchar(50) NOT NULL,
  homepage varchar(30) NOT NULL default 'home',
  banner varchar(20) NOT NULL,
  theme varchar(30) NOT NULL,
  claimed enum('yes','no') NOT NULL default 'no',
  created int(10) NOT NULL,
  PRIMARY KEY  (id),
  KEY url (subdomain)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table 'slide_panels'
--

CREATE TABLE IF NOT EXISTS slide_panels (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(40) NOT NULL,
  params varchar(40) NOT NULL,
  attributes varchar(35) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table 'slide_panel_items'
--

CREATE TABLE IF NOT EXISTS slide_panel_items (
  id int(7) NOT NULL auto_increment,
  parent_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  title varchar(50) NOT NULL,
  body text NOT NULL,
  position int(3) unsigned NOT NULL,
  PRIMARY KEY  (id),
  KEY fk_site (fk_site)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table 'system_tools'
--

CREATE TABLE IF NOT EXISTS system_tools (
  id int(2) unsigned NOT NULL auto_increment,
  `name` varchar(25) NOT NULL,
  protected enum('yes','no') NOT NULL default 'no',
  enabled enum('yes','no') NOT NULL default 'no',
  visible enum('yes','no') NOT NULL default 'yes',
  `desc` tinytext NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table 'texts'
--

CREATE TABLE IF NOT EXISTS texts (
  id int(7) unsigned NOT NULL auto_increment,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `view` varchar(25) NOT NULL,
  params varchar(25) NOT NULL,
  body text NOT NULL,
  attributes varchar(35) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table 'themes'
--

CREATE TABLE IF NOT EXISTS themes (
  id int(2) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  enabled enum('yes','no') NOT NULL default 'yes',
  image_ext varchar(3) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table 'version'
--

CREATE TABLE IF NOT EXISTS version (
  at varchar(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_user_tokens`
--
ALTER TABLE `account_user_tokens`
  ADD CONSTRAINT account_user_tokens_ibfk_1 FOREIGN KEY (account_user_id) REFERENCES account_users (id) ON DELETE CASCADE;
