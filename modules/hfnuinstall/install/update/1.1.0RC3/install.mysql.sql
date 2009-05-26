ALTER TABLE `hf_forum` ADD `forum_type` int( 1 ) NOT NULL ,
ADD INDEX ( forum_type ) ;

ALTER TABLE `hf_forum` ADD `forum_url` varchar( 255 ) DEFAULT NULL ;

update `hf_member` set member_status = 0 where member_status = 1;
update `hf_member` set member_status = 1 where member_status = 2;