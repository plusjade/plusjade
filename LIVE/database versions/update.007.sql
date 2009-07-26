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





