ALTER TABLE `hf_forum` ADD `post_expire` INT( 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `hf_posts` ADD `censored_msg` VARCHAR( 50 ) NULL ;