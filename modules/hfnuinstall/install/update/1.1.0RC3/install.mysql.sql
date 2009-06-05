ALTER TABLE `hf_forum` ADD `forum_type` int( 1 ) NOT NULL ,
ADD INDEX ( forum_type ) ;

ALTER TABLE `hf_forum` ADD `forum_url` varchar( 255 ) DEFAULT NULL ;

update `hf_member` set member_status = 0 where member_status = 1;
update `hf_member` set member_status = 1 where member_status = 2;

alter table `hf_member` add `member_created` datetime DEFAULT NULL;

update `hf_member` set member_created = member_request_date;

INSERT INTO `hf_jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('hfnu.admin.contact', 1, '');

INSERT INTO `hf_jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('hfnu.admin.contact', 'hfnucontact~acl2.admin.contact');