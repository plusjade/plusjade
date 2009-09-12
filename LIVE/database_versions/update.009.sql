
-- updating tools to interact and instance better throughout the system.

ALTER TABLE `pages_tools` ADD `id` INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;

ALTER TABLE `tools` CHANGE `tool_id` `parent_id` INT( 7 ) UNSIGNED NOT NULL ;