DROP TABLE `album_items`;

ALTER TABLE `calendar_items` CHANGE `parent_id` `calendar_id` INT( 7 ) UNSIGNED NOT NULL ;

RENAME TABLE blog_items TO blog_posts;
RENAME TABLE blog_items_comments TO blog_post_comments;
RENAME TABLE blog_items_tags TO blog_post_tags;
ALTER TABLE `blog_posts` CHANGE `parent_id` `blog_id` INT( 7 ) UNSIGNED NOT NULL ;
ALTER TABLE `blog_post_comments` CHANGE `parent_id` `blog_id` INT( 9 ) UNSIGNED NOT NULL ;
ALTER TABLE `blog_post_tags` CHANGE `parent_id` `blog_id` INT( 9 ) UNSIGNED NOT NULL ;

ALTER TABLE `blog_post_comments` CHANGE `item_id` `blog_post_id` INT( 9 ) UNSIGNED NOT NULL ;
ALTER TABLE `blog_post_tags` CHANGE `item_id` `blog_post_id` INT( 9 ) UNSIGNED NOT NULL 



ALTER TABLE `faq_items` CHANGE `parent_id` `faq_id` INT( 7 ) UNSIGNED NOT NULL ;

ALTER TABLE `navigation_items` CHANGE `parent_id` `navigation_id` INT( 7 ) UNSIGNED NOT NULL;

RENAME TABLE showroom_items TO showroom_cats;
ALTER TABLE `showroom_cats` CHANGE `parent_id` `showroom_id` INT( 7 ) UNSIGNED NOT NULL ;

RENAME TABLE showroom_items_meta TO showroom_cat_items;

ALTER TABLE `showroom_cat_item` CHANGE `cat_id` `showroom_cat_id` INT( 4 ) UNSIGNED NOT NULL;

UPDATE `plusjade`.`tools_list` SET `enabled` = 'no' WHERE `tools_list`.`id` =5 LIMIT 1 ;

ALTER TABLE `sites_users` CHANGE `fk_users` `user_id` INT( 7 ) UNSIGNED NOT NULL ,
CHANGE `fk_site` `site_id` INT( 7 ) UNSIGNED NOT NULL ;

ALTER TABLE `sites` CHANGE `site_id` `id` INT( 7 ) UNSIGNED NOT NULL AUTO_INCREMENT ;

RENAME TABLE tools_list TO system_tools;
ALTER TABLE `pages_tools` CHANGE `tool` `system_tool_id` INT( 2 ) UNSIGNED NOT NULL;

ALTER TABLE `system_tools` ADD `visible` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'yes' AFTER `enabled`;
UPDATE `plusjade`.`system_tools` SET `visible` = 'no' WHERE `system_tools`.`id` =10 LIMIT 1 ;

ALTER TABLE `account_users` DROP INDEX `uniq_email`;
ALTER TABLE `account_users` ADD INDEX ( `email` ) ;

ALTER TABLE `account_users` DROP INDEX `uniq_username` ;

ALTER TABLE `accounts` CHANGE `params` `login_title` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `sticky_posts` `create_title` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 


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

ALTER TABLE `forum_comment_votes` ADD `fk_site` INT( 7 ) NOT NULL ;

ALTER TABLE `sites` ADD `claimed` ENUM( 'yes', 'no' ) NOT NULL ,
ADD `created` INT( 10 ) NOT NULL ;

ALTER TABLE `sites` CHANGE `claimed` `claimed` ENUM( 'yes', 'no' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no'

UPDATE `plusjade`.`themes` SET `enabled` = 'no' WHERE `themes`.`id` =9 LIMIT 1 ;






