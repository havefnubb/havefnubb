
UPDATE %%PREFIX%%forum AS f SET id_last_msg = (SELECT max(id_last_msg) FROM %%PREFIX%%hfnu_threads AS t WHERE f.id_forum = t.id_forum );
UPDATE %%PREFIX%%forum AS f SET date_last_msg = (SELECT max(date_last_msg) FROM %%PREFIX%%hfnu_threads AS t WHERE f.id_forum = t.id_forum );

RENAME TABLE `%%PREFIX%%member`  TO `%%PREFIX%%community_users` ;
ALTER TABLE `%%PREFIX%%community_users` CHANGE `id_user` `id` INT( 12 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_login` `login` varchar(50) NOT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_password` `password` varchar(50) NOT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_email` `email` varchar(255) NOT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_nickname` `nickname` varchar(50) DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_status` `status` tinyint(4) NOT NULL DEFAULT '0';
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_keyactivate` `keyactivate` varchar(10) DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_request_date` `request_date` datetime DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_website` `website` varchar(255) DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_firstname` `firstname` varchar(40) DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_birth` `birth` date NOT NULL DEFAULT '1980-01-01';
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_country` `country` varchar(100) DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_town` `town` varchar(100) DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_comment` `comment` varchar(255) DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_avatar` `avatar` varchar(255) DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_show_email` `show_email` varchar(1) DEFAULT 'N';
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_language` `language` varchar(40) DEFAULT 'fr_FR';
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_nb_msg` `nb_msg` int(12) DEFAULT '0';
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_last_post` `last_post` int(12) NOT NULL DEFAULT '0';
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_created` `create_date` datetime DEFAULT NULL;
ALTER TABLE `%%PREFIX%%community_users` CHANGE   `member_gravatar` `gravatar` int(1) NOT NULL DEFAULT '0';

ALTER TABLE `%%PREFIX%%community_users` DROP   `member_last_connect`;
ALTER TABLE `%%PREFIX%%community_users` DROP   `member_connection`;


ALTER TABLE %%PREFIX%%read_posts ADD date_read int(12) NOT NULL DEFAULT '0';
ALTER TABLE %%PREFIX%%read_posts ADD thread_id int(12) NOT NULL DEFAULT '0';

ALTER TABLE %%PREFIX%%jacl2_group ADD COLUMN code varchar(30) default NULL;

RENAME TABLE  %%PREFIX%%member_custom_fields TO %%PREFIX%%hfnu_member_custom_fields;
RENAME TABLE  %%PREFIX%%bans TO %%PREFIX%%hfnu_bans;
RENAME TABLE  %%PREFIX%%category TO %%PREFIX%%hfnu_forum_category;
RENAME TABLE  %%PREFIX%%forum TO %%PREFIX%%hfnu_forum;
RENAME TABLE  %%PREFIX%%notify TO %%PREFIX%%hfnu_notify;
RENAME TABLE  %%PREFIX%%posts TO %%PREFIX%%hfnu_posts;
RENAME TABLE  %%PREFIX%%rank TO %%PREFIX%%hfnu_rank;
RENAME TABLE  %%PREFIX%%rates TO %%PREFIX%%hfnu_rates;
RENAME TABLE  %%PREFIX%%read_forum TO %%PREFIX%%hfnu_read_forum;
RENAME TABLE  %%PREFIX%%read_posts TO %%PREFIX%%hfnu_read_posts;
RENAME TABLE  %%PREFIX%%search_words TO %%PREFIX%%hfnu_search_words;
RENAME TABLE  %%PREFIX%%subscriptions TO %%PREFIX%%hfnu_subscriptions;

DROP TABLE IF EXISTS %%PREFIX%%connected;
DROP TABLE IF EXISTS %%PREFIX%%hfnu_connected;

ALTER TABLE %%PREFIX%%hfnu_notify CHANGE `parent_id` `thread_id` INT( 12 ) NOT NULL

ALTER TABLE %%PREFIX%%hfnu_posts CHANGE `parent_id` `thread_id` INT( 12 ) NOT NULL

CREATE TABLE IF NOT EXISTS %%PREFIX%%connectedusers (
    sessionid  varchar(40) NOT NULL,
    login varchar(50) NULL,
    name varchar(50) NULL,
    member_ip VARCHAR(25) NOT NULL DEFAULT '',
    connection_date INT(10) UNSIGNED NOT NULL DEFAULT 0,
    last_request_date INT(10) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (sessionid)
) DEFAULT CHARSET=utf8;

--- Suppression des droits obsolètes

DELETE FROM %%PREFIX%%jacl2_subject WHERE id_aclsbj = 'hfnu.admin.cache.clear';
DELETE FROM %%PREFIX%%jacl2_rights WHERE id_aclsbj = 'hfnu.admin.cache.clear';
DELETE FROM %%PREFIX%%jacl2_subject WHERE id_aclsbj = 'hfnu.admin.cache';
DELETE FROM %%PREFIX%%jacl2_rights WHERE id_aclsbj = 'hfnu.admin.cache';


DELETE FROM %%PREFIX%%jacl2_subject WHERE id_aclsbj = 'hfnu.admin.server.info';
DELETE FROM %%PREFIX%%jacl2_rights WHERE id_aclsbj = 'hfnu.admin.server.info';
INSERT INTO %%PREFIX%%jacl2_subject (id_aclsbj, label_key) VALUES ('servinfo.access', 'servinfo~servinfo.acl.access');
INSERT INTO %%PREFIX%%jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('servinfo.access', 1, '');

INSERT INTO %%PREFIX%%jacl2_subject (id_aclsbj, label_key) VALUES ('modulesinfo.access', 'modulesinfo~modulesinfo.acl.access');
INSERT INTO %%PREFIX%%jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('modulesinfo.access', 1, '');

INSERT INTO %%PREFIX%%jacl2_subject (id_aclsbj, label_key) VALUES ('jelixcache.access', 'jelixcache~jelixcache.acl.access');
INSERT INTO %%PREFIX%%jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('jelixcache.access', 1, '');


--- TODO suppression des champs member* relatif à hardware and im
