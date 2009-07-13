ALTER TABLE `showroom_items_meta` CHANGE `img` `img` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL; 
ALTER TABLE `albums` ADD `images` TEXT NOT NULL AFTER `params` ;
ALTER TABLE `showroom_items_meta` CHANGE `img` `images` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

