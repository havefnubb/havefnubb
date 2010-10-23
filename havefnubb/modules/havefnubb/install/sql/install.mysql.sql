

CREATE TABLE IF NOT EXISTS %%PREFIX%%bans (
  ban_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  ban_username varchar(200) DEFAULT NULL,
  ban_ip varchar(255) DEFAULT NULL,
  ban_email varchar(50) DEFAULT NULL,
  ban_message varchar(255) DEFAULT NULL,
  ban_expire int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (ban_id)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS %%PREFIX%%category (
  id_cat int(12) NOT NULL AUTO_INCREMENT,
  cat_name varchar(255) NOT NULL,
  cat_order int(4) NOT NULL,
  PRIMARY KEY (id_cat)
) DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS %%PREFIX%%connected (
    id_user int(12) NOT NULL DEFAULT '1',
    member_ip VARCHAR(200) NOT NULL DEFAULT '',
    connected INT(10) UNSIGNED NOT NULL DEFAULT 0,
    idle TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id_user)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS %%PREFIX%%forum (
  id_forum int(12) NOT NULL AUTO_INCREMENT,
  forum_name varchar(255) NOT NULL,
  id_cat int(12) NOT NULL,
  forum_desc varchar(255) NOT NULL,
  forum_order int(4) NOT NULL,
  parent_id int(11) NOT NULL,
  child_level int(4) NOT NULL,
  forum_type INT( 1 ) NOT NULL,
  forum_url varchar( 255 ) DEFAULT NULL,
  post_expire INT ( 5 ) DEFAULT '0',
  id_last_msg int(11) NOT NULL DEFAULT '0',
  date_last_msg int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_forum),
  KEY id_cat (id_cat),
  KEY parent_id (parent_id),
  KEY child_level (child_level),
  KEY forum_type (forum_type )
) DEFAULT CHARSET=utf8;


ALTER TABLE %%PREFIX%%community_users ADD  website varchar(255) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  firstname varchar(40) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  birth date NOT NULL DEFAULT '1980-01-01';
ALTER TABLE %%PREFIX%%community_users ADD  country varchar(100) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  town varchar(100) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  comment varchar(255) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  avatar varchar(255) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  last_connect int(12) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  show_email varchar(1) DEFAULT 'N';
ALTER TABLE %%PREFIX%%community_users ADD  `language` varchar(40) DEFAULT 'fr_FR';
ALTER TABLE %%PREFIX%%community_users ADD  nb_msg int(12) DEFAULT '0';
ALTER TABLE %%PREFIX%%community_users ADD  last_post int(12) NOT NULL DEFAULT '0';
ALTER TABLE %%PREFIX%%community_users ADD  gravatar INT( 1 ) NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS %%PREFIX%%member_custom_fields (
  id_user int(11) NOT NULL,
  type varchar(30) NOT NULL,
  data text NOT NULL,
  PRIMARY KEY  (id_user,type)
) DEFAULT  CHARSET=utf8;

CREATE TABLE IF NOT EXISTS %%PREFIX%%notify (
  id_notify int(12) NOT NULL AUTO_INCREMENT,
  id_user int(12) NOT NULL,
  id_post int(12) NOT NULL,
  parent_id int(12) NOT NULL,
  id_forum int(12) NOT NULL,
  subject varchar(255) NOT NULL,
  message text NOT NULL,
  date_created int(12) NOT NULL,
  date_modified int(12) NOT NULL,
  PRIMARY KEY (id_notify),
  KEY id_user (id_user),
  KEY id_post (id_post)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS %%PREFIX%%posts (
  id_post int(12) NOT NULL AUTO_INCREMENT,
  id_user int(12) NOT NULL,
  id_forum int(12) NOT NULL,
  parent_id int(12) NOT NULL,
  status int(2) NOT NULL DEFAULT 3,
  subject varchar(255) NOT NULL,
  message text NOT NULL,
  date_created int(12) NOT NULL,
  date_modified int(12) NOT NULL,
  viewed int(12) NOT NULL,
  poster_ip varchar(15) NOT NULL,
  censored_msg VARCHAR( 50 ) NULL,
  read_by_mod int(1) DEFAULT '0',
  ispined INT( 1 ) NOT NULL DEFAULT '0',
  iscensored INT( 1 ) NOT NULL DEFAULT '0',
  censored_by int(12),
  PRIMARY KEY (id_post),
  KEY id_user (id_user,id_forum,parent_id,status)
) DEFAULT CHARSET=utf8;
ALTER TABLE %%PREFIX%%posts ADD INDEX ( ispined ) ;
ALTER TABLE %%PREFIX%%posts ADD INDEX ( iscensored ) ;


CREATE TABLE IF NOT EXISTS %%PREFIX%%threads (
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


CREATE TABLE IF NOT EXISTS %%PREFIX%%rank (
  id_rank int(12) NOT NULL AUTO_INCREMENT,
  rank_name varchar(40) NOT NULL,
  rank_limit int(9) NOT NULL,
  PRIMARY KEY (id_rank)
) DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS %%PREFIX%%read_forum (
  id_user int(12) NOT NULL,
  id_forum int(12) NOT NULL,
  date_read int(12) NOT NULL,
  PRIMARY KEY  (id_user,id_forum,date_read),
  KEY id_user (id_user),
  KEY id_forum (id_forum),
  KEY date_read (date_read)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS %%PREFIX%%read_posts (
  id_user int(12) NOT NULL,
  id_forum int(12) NOT NULL,
  id_post int(12) NOT NULL,
  date_read int(12) NOT NULL,
  parent_id int(12) NOT NULL,
  PRIMARY KEY  (id_user,id_forum,id_post),
  KEY id_user (id_user),
  KEY id_forum (id_forum),
  KEY id_post (id_post),
  KEY parent_id (parent_id)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS %%PREFIX%%subscriptions (
    id_user int(12) NOT NULL,
    id_post int(12) NOT NULL,
    PRIMARY KEY (id_user , id_post)
) DEFAULT CHARSET=utf8;



-- ============================================ DATA

INSERT INTO %%PREFIX%%category (id_cat, cat_name, cat_order) VALUES
(1, 'My First Forum', 1),
(2, 'My Second forum', 2);

INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire, id_last_msg, date_last_msg) VALUES
(1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0,'',0,1,0),
(2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0,'',0,0,0),
(3, 'Light', 2, 'Soo light', 1, 0, 0, 0,'',0,0,0),
(4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0,'',0,0,0);

INSERT INTO %%PREFIX%%threads (id_thread, id_forum,id_user,status,id_first_msg,id_last_msg,date_created,date_last_post,nb_viewed,nb_replies,ispined,iscensored)
VALUES (1,1,1,3,1,1,UNIX_TIMESTAMP(),UNIX_TIMESTAMP(),0,0,0,0);


INSERT INTO %%PREFIX%%posts (id_post, id_user, id_forum, parent_id, status, subject, message, date_created, date_modified, viewed, poster_ip, censored_msg,read_by_mod,ispined,iscensored ) VALUES
(1, 1, 1, 1, 3, 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, '127.0.0.1',NULL,1,0,0);


INSERT INTO %%PREFIX%%rank (id_rank, rank_name, rank_limit) VALUES
(1, 'new member', 0),
(2, 'member', 40),
(3, 'active member', 100);
