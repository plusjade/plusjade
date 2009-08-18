
-- SCHEMA UPDATES FOR THE ACCOUNT TOOL.
-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 15, 2009 at 08:34 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: 'plusjade'
--

-- --------------------------------------------------------

--
-- Table structure for table 'accounts'
--

CREATE TABLE IF NOT EXISTS accounts (
  id int(7) unsigned NOT NULL AUTO_INCREMENT,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(25) CHARACTER SET utf8 NOT NULL,
  `view` varchar(25) CHARACTER SET utf8 NOT NULL,
  params varchar(25) CHARACTER SET utf8 NOT NULL,
  sticky_posts varchar(50) CHARACTER SET utf8 NOT NULL,
  attributes varchar(35) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'account_roles'
--

CREATE TABLE IF NOT EXISTS account_roles (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_name (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'account_users'
--

CREATE TABLE IF NOT EXISTS account_users (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  fk_site int(7) NOT NULL,
  email varchar(127) NOT NULL,
  username varchar(32) NOT NULL DEFAULT '',
  `password` char(60) NOT NULL,
  token varchar(32) NOT NULL,
  logins int(10) unsigned NOT NULL DEFAULT '0',
  last_login int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_username (username),
  UNIQUE KEY uniq_email (email),
  UNIQUE KEY token (token)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'account_user_meta'
--

CREATE TABLE IF NOT EXISTS account_user_meta (
  id int(7) NOT NULL AUTO_INCREMENT,
  fk_site int(9) unsigned NOT NULL,
  account_user_id int(9) unsigned NOT NULL,
  `key` varchar(72) NOT NULL,
  `value` tinytext NOT NULL,
  PRIMARY KEY (id),
  KEY account_user_id (account_user_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'account_user_tokens'
--

CREATE TABLE IF NOT EXISTS account_user_tokens (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  account_user_id int(11) unsigned NOT NULL,
  user_agent varchar(40) NOT NULL,
  token varchar(32) NOT NULL,
  created int(10) unsigned NOT NULL,
  expires int(10) unsigned NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_token (token),
  KEY account_user_id (account_user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_user_tokens`
--
ALTER TABLE `account_user_tokens`
  ADD CONSTRAINT account_user_tokens_ibfk_1 FOREIGN KEY (account_user_id) REFERENCES account_users (id) ON DELETE CASCADE;

  
 -- FORUM STUFF
 
INSERT INTO `plusjade`.`tools_list` (
`id` ,
`name` ,
`protected` ,
`enabled` ,
`desc`
)
VALUES (
NULL , 'Forum', 'yes', 'yes', 'Install a forum onto your website.'
);

 
-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 20, 2009 at 01:28 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: 'plusjade'
--

-- --------------------------------------------------------

--
-- Table structure for table 'forums'
--

CREATE TABLE IF NOT EXISTS forums (
  id int(7) unsigned NOT NULL AUTO_INCREMENT,
  fk_site int(7) unsigned NOT NULL,
  `name` varchar(25) CHARACTER SET utf8 NOT NULL,
  `view` varchar(25) CHARACTER SET utf8 NOT NULL,
  params varchar(25) CHARACTER SET utf8 NOT NULL,
  sticky_posts varchar(50) CHARACTER SET utf8 NOT NULL,
  attributes varchar(35) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'forum_cats'
--

CREATE TABLE IF NOT EXISTS forum_cats (
  id int(7) unsigned NOT NULL AUTO_INCREMENT,
  forum_id int(7) unsigned NOT NULL,
  fk_site int(7) unsigned NOT NULL,
  url varchar(60) NOT NULL,
  `name` varchar(60) NOT NULL,
  position int(4) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'forum_cat_posts'
--

CREATE TABLE IF NOT EXISTS forum_cat_posts (
  id int(9) unsigned NOT NULL AUTO_INCREMENT,
  fk_site int(9) unsigned NOT NULL,
  forum_cat_id int(9) unsigned NOT NULL,
  title varchar(72) NOT NULL,
  forum_cat_post_comment_id int(7) NOT NULL,
  comment_count int(7) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'forum_cat_post_comments'
--

CREATE TABLE IF NOT EXISTS forum_cat_post_comments (
  id int(9) unsigned NOT NULL AUTO_INCREMENT,
  fk_site int(9) unsigned NOT NULL,
  forum_cat_post_id int(9) unsigned NOT NULL,
  account_user_id int(9) unsigned NOT NULL,
  body text NOT NULL,
  created int(10) unsigned NOT NULL,
  vote_count int(9) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'forum_comment_votes'
--

CREATE TABLE IF NOT EXISTS forum_comment_votes (
  account_user_id int(9) NOT NULL,
  forum_cat_post_comment_id int(9) NOT NULL,
  KEY author_id (account_user_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
 
  
  
  ALTER TABLE `forum_cat_posts` ADD `last_active` INT( 10 ) NOT NULL 