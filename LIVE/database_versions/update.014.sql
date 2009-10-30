
-- i already did this on the live site so larasgift can have longer names.
ALTER TABLE `showroom_cat_items` CHANGE `name` `name` VARCHAR( 120 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
-- we probably dont need the url if we query by id.
 ALTER TABLE `showroom_cat_items` CHANGE `url` `url` VARCHAR( 120 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL  