DROP TABLE `album_items`;

ALTER TABLE `calendar_items` CHANGE `parent_id` `calendar_id` INT( 7 ) UNSIGNED NOT NULL ;

-- blog stuff
RENAME TABLE blog_items TO blog_posts;
RENAME TABLE blog_items_comments TO blog_post_comments;
RENAME TABLE blog_items_tags TO blog_post_tags;
ALTER TABLE `blog_posts` CHANGE `parent_id` `blog_id` INT( 7 ) UNSIGNED NOT NULL ;
ALTER TABLE `blog_post_comments` CHANGE `parent_id` `blog_id` INT( 9 ) UNSIGNED NOT NULL ;
ALTER TABLE `blog_post_tags` CHANGE `parent_id` `blog_id` INT( 9 ) UNSIGNED NOT NULL ;
ALTER TABLE `blog_post_comments` CHANGE `item_id` `blog_post_id` INT( 9 ) UNSIGNED NOT NULL ;
ALTER TABLE `blog_post_tags` CHANGE `item_id` `blog_post_id` INT( 9 ) UNSIGNED NOT NULL ;



ALTER TABLE `faq_items` CHANGE `parent_id` `faq_id` INT( 7 ) UNSIGNED NOT NULL ;

ALTER TABLE `navigation_items` CHANGE `parent_id` `navigation_id` INT( 7 ) UNSIGNED NOT NULL;

RENAME TABLE showroom_items TO showroom_cats;
ALTER TABLE `showroom_cats` CHANGE `parent_id` `showroom_id` INT( 7 ) UNSIGNED NOT NULL ;
RENAME TABLE showroom_items_meta TO showroom_cat_items;
ALTER TABLE `showroom_cat_items` CHANGE `cat_id` `showroom_cat_id` INT( 4 ) UNSIGNED NOT NULL;

-- tools
UPDATE `plusjade`.`tools_list` SET `enabled` = 'no' WHERE `tools_list`.`id` =5 LIMIT 1 ;

ALTER TABLE `sites_users` CHANGE `fk_users` `user_id` INT( 7 ) UNSIGNED NOT NULL ,
CHANGE `fk_site` `site_id` INT( 7 ) UNSIGNED NOT NULL ;

ALTER TABLE `sites` CHANGE `site_id` `id` INT( 7 ) UNSIGNED NOT NULL AUTO_INCREMENT ;

RENAME TABLE tools_list TO system_tools;
ALTER TABLE `pages_tools` CHANGE `tool` `system_tool_id` INT( 2 ) UNSIGNED NOT NULL;

ALTER TABLE `system_tools` ADD `visible` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'yes' AFTER `enabled`;
UPDATE `plusjade`.`system_tools` SET `visible` = 'no' WHERE `system_tools`.`id` =10 LIMIT 1 ;

-- accounts
ALTER TABLE `account_users` DROP INDEX `uniq_email`;
ALTER TABLE `account_users` ADD INDEX ( `email` ) ;

ALTER TABLE `account_users` DROP INDEX `uniq_username` ;

ALTER TABLE `accounts` CHANGE `params` `login_title` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `sticky_posts` `create_title` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;


ALTER TABLE `accounts` ADD UNIQUE (
`fk_site`
);


CREATE TABLE `plusjade`.`account_users_sites` (
`account_user_id` INT( 9 ) UNSIGNED NOT NULL ,
`site_id` INT( 9 ) UNSIGNED NOT NULL
) ENGINE = MYISAM ;


DROP TABLE `sites_users`;
DROP TABLE `user_tokens`;
DROP TABLE `roles_users`;
DROP TABLE `roles`;
DROP TABLE `users`;

-- forum stuff
-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 01, 2009 at 04:07 PM
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
  last_active int(10) NOT NULL,
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
  is_post enum('0','1') NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

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




ALTER TABLE `sites` ADD `claimed` ENUM( 'yes', 'no' ) NOT NULL ,
ADD `created` INT( 10 ) NOT NULL ;

ALTER TABLE `sites` CHANGE `claimed` `claimed` ENUM( 'yes', 'no' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no';

UPDATE `plusjade`.`themes` SET `enabled` = 'no' WHERE `themes`.`id` =9 LIMIT 1 ;

UPDATE `plusjade`.`themes` SET `enabled` = 'no' WHERE `themes`.`id` =5 LIMIT 1 ;

UPDATE `plusjade`.`themes` SET `image_ext` = 'jpg' WHERE `themes`.`id` =1 LIMIT 1 ;

ALTER TABLE `pages` CHANGE `title` `title` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;


UPDATE `plusjade`.`version` SET `at` = '007' WHERE CONVERT( `version`.`at` USING utf8 ) = '004' LIMIT 1 ;
