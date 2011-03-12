ALTER TABLE %%PREFIX%%posts ADD ispined INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE %%PREFIX%%posts ADD INDEX ( ispined ) ;

ALTER TABLE %%PREFIX%%posts ADD iscensored INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE %%PREFIX%%posts ADD INDEX ( iscensored ) ;

ALTER TABLE %%PREFIX%%posts ADD censored_by int(12);

update %%PREFIX%%posts set status = 3,ispined=1,iscensored=0 where status = "pined";
update %%PREFIX%%posts set status = 4,ispined=1,iscensored=0 where status = "pinedclosed";
update %%PREFIX%%posts set status = 3,ispined=0,iscensored=0 where status = "opened";
update %%PREFIX%%posts set status = 4,ispined=0,iscensored=0 where status = "closed";
update %%PREFIX%%posts set status = 4,ispined=0,iscensored=1 where status = "censored";
update %%PREFIX%%posts set status = 3,ispined=0,iscensored=0 where status = "uncensored";
update %%PREFIX%%posts set status = 7,ispined=0,iscensored=0 where status = "hidden";


ALTER TABLE %%PREFIX%%posts CHANGE status status INT( 2 ) NOT NULL DEFAULT '3';

CREATE TABLE IF NOT EXISTS %%PREFIX%%hfnu_threads (
  id_thread int(11) NOT NULL AUTO_INCREMENT,
  id_forum int(11) NOT NULL,
  id_user INT NOT NULL,
  status int(11) NOT NULL,
  id_first_msg int(11) NOT NULL,
  id_last_msg int(11) NOT NULL,
  date_created int(11) NOT NULL,
  date_last_post int(11),
  nb_viewed int(11) DEFAULT '0',
  nb_replies int(11) DEFAULT '0',
  ispined INT( 1 ) NOT NULL DEFAULT '0',
  iscensored INT( 1 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_thread),
  KEY id_forum (id_forum),
  KEY id_user (id_user),
  KEY status (status),
  KEY id_first_msg (id_first_msg),
  KEY id_last_msg (id_last_msg),
  KEY ispined (ispined),
  KEY iscensored (iscensored)
) DEFAULT CHARSET=utf8;

INSERT INTO %%PREFIX%%hfnu_threads (id_thread,id_forum,id_user,status,nb_viewed,date_created,ispined,iscensored)
    SELECT parent_id,id_forum,id_user,status,viewed,date_created,ispined,iscensored FROM %%PREFIX%%posts WHERE parent_id=id_post;

UPDATE %%PREFIX%%hfnu_threads SET id_first_msg = id_thread ;
UPDATE %%PREFIX%%hfnu_threads SET id_last_msg = ( SELECT id_post FROM %%PREFIX%%posts WHERE %%PREFIX%%hfnu_threads.id_thread = parent_id ORDER BY date_created DESC LIMIT 1),
                                date_last_post =( SELECT date_created FROM %%PREFIX%%posts WHERE %%PREFIX%%hfnu_threads.id_thread = parent_id ORDER BY date_created DESC LIMIT 1);
UPDATE %%PREFIX%%hfnu_threads SET nb_replies = (SELECT count(id_post) -1 FROM %%PREFIX%%posts WHERE %%PREFIX%%hfnu_threads.id_thread = parent_id);


ALTER TABLE %%PREFIX%%forum ADD id_last_msg int(11) NOT NULL DEFAULT '0';
ALTER TABLE %%PREFIX%%forum ADD date_last_msg int(11) NOT NULL DEFAULT '0';

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

DELETE FROM %%PREFIX%%read_forum;
ALTER TABLE %%PREFIX%%read_forum DROP PRIMARY KEY;
ALTER TABLE %%PREFIX%%read_forum DROP INDEX id_user;
ALTER TABLE %%PREFIX%%read_forum DROP INDEX id_forum;
ALTER TABLE %%PREFIX%%read_forum DROP INDEX date_read;
ALTER TABLE %%PREFIX%%read_forum ADD PRIMARY KEY (id_user, id_forum);


DELETE FROM %%PREFIX%%read_posts;
ALTER TABLE %%PREFIX%%read_posts ADD date_read int(12) NOT NULL DEFAULT '0';
ALTER TABLE %%PREFIX%%read_posts ADD thread_id int(12) NOT NULL DEFAULT '0';
ALTER TABLE %%PREFIX%%read_posts DROP PRIMARY KEY;
ALTER TABLE %%PREFIX%%read_posts DROP id_post;
ALTER TABLE %%PREFIX%%read_posts DROP INDEX id_user;
ALTER TABLE %%PREFIX%%read_posts DROP INDEX id_forum;
ALTER TABLE %%PREFIX%%read_posts ADD PRIMARY KEY (id_user, id_forum, thread_id);

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

ALTER TABLE %%PREFIX%%hfnu_notify CHANGE `parent_id` `thread_id` INT( 12 ) NOT NULL;

ALTER TABLE %%PREFIX%%hfnu_posts CHANGE `parent_id` `thread_id` INT( 12 ) NOT NULL;

CREATE TABLE IF NOT EXISTS %%PREFIX%%connectedusers (
    sessionid  varchar(40) NOT NULL,
    login varchar(50) NULL,
    name varchar(50) NULL,
    member_ip VARCHAR(25) NOT NULL DEFAULT '',
    connection_date INT(10) UNSIGNED NOT NULL DEFAULT 0,
    last_request_date INT(10) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (sessionid)
) DEFAULT CHARSET=utf8;

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
