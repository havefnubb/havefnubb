ALTER TABLE `hf_forum` ADD `forum_type` int( 1 ) NOT NULL ,
ADD INDEX ( forum_type ) ;

ALTER TABLE `hf_forum` ADD `forum_url` varchar( 255 ) DEFAULT NULL ;
