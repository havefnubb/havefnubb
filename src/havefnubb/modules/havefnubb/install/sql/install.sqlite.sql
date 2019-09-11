
CREATE TABLE %%PREFIX%%hfnu_forum_category (
  id_cat INTEGER NOT NULL PRIMARY KEY,
  cat_name varchar(255) NOT NULL,
  cat_order int(4) NOT NULL
);


CREATE TABLE %%PREFIX%%hfnu_forum  (
    id_forum INTEGER NOT  NULL PRIMARY  KEY ,
    forum_name  VARCHAR ( 255 ) NOT  NULL ,
    id_cat INTEGER  NOT NULL  ,
    forum_desc VARCHAR ( 255  )  NOT NULL  ,
    forum_order INT ( 4  )  NOT NULL  ,
    parent_id INT ( 11  )  NOT NULL  ,
    child_level INT ( 4  )  NOT NULL  ,
    forum_type INT ( 1  )  NOT NULL  ,
    forum_url VARCHAR ( 255  )  NOT NULL  ,
    post_expire INT ( 5  )  NOT NULL,
    id_last_msg INTEGER NOT NULL,
    date_last_msg INTEGER NOT NULL
    nb_msg INTEGER NOT NULL,
    nb_thread INTEGER NOT NULL,

);
CREATE INDEX %%PREFIX%%hfnu_forum_id_cat  ON %%PREFIX%%hfnu_forum  (  id_cat ) ;
CREATE INDEX %%PREFIX%%hfnu_forum_parent_id  ON %%PREFIX%%hfnu_forum  (  parent_id ) ;
CREATE INDEX %%PREFIX%%hfnu_forum_child_level  ON %%PREFIX%%hfnu_forum  (  child_level ) ;
CREATE INDEX %%PREFIX%%hfnu_forum_forum_type  ON %%PREFIX%%hfnu_forum  (  forum_type ) ;

ALTER TABLE %%PREFIX%%community_users ADD  website varchar(255) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  firstname varchar(40) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  birth date NOT NULL DEFAULT '1980-01-01';
ALTER TABLE %%PREFIX%%community_users ADD  country varchar(100) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  town varchar(100) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  comment varchar(255) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  avatar varchar(255) DEFAULT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  show_email varchar(1) DEFAULT 'N';
ALTER TABLE %%PREFIX%%community_users ADD  language varchar(40) DEFAULT 'fr_FR';
ALTER TABLE %%PREFIX%%community_users ADD  nb_msg INTEGER DEFAULT '0';
ALTER TABLE %%PREFIX%%community_users ADD  last_post INTEGER NOT NULL DEFAULT '0';
ALTER TABLE %%PREFIX%%community_users ADD  gravatar INT( 1 ) NOT NULL DEFAULT '0';


CREATE TABLE %%PREFIX%%hfnu_member_custom_fields (
  id_user int(11) NOT NULL,
  type varchar(30) NOT NULL,
  data text NOT NULL,
  PRIMARY KEY  (id_user,type)
);



CREATE TABLE %%PREFIX%%hfnu_posts (
  id_post INTEGER NOT NULL PRIMARY KEY ,
  id_user INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  thread_id INTEGER NOT NULL,
  status int(2) NOT NULL DEFAULT 3,
  subject varchar(255) NOT NULL,
  message text NOT NULL,
  date_created INTEGER NOT NULL,
  date_modified INTEGER NOT NULL,
  viewed INTEGER NOT NULL,
  poster_ip varchar(15) NOT NULL,
  censored_msg VARCHAR( 50 ) NULL,
  read_by_mod int(1) DEFAULT '0'
) ;
CREATE INDEX %%PREFIX%%hfnu_posts_id_user ON %%PREFIX%%hfnu_posts  (  id_user );
CREATE INDEX %%PREFIX%%hfnu_posts_id_forum ON %%PREFIX%%hfnu_posts  (  id_forum );
CREATE INDEX %%PREFIX%%hfnu_posts_thread_id ON %%PREFIX%%hfnu_posts  (  thread_id );
CREATE INDEX %%PREFIX%%hfnu_posts_status ON %%PREFIX%%hfnu_posts  (  status );


CREATE TABLE %%PREFIX%%hfnu_rank (
  id_rank INTEGER NOT NULL PRIMARY KEY  ,
  rank_name varchar(40) NOT NULL,
  rank_limit int(9) NOT NULL
) ;



CREATE TABLE %%PREFIX%%hfnu_notify (
  id_notify INTEGER NOT NULL PRIMARY KEY ,
  id_user INTEGER NOT NULL,
  id_post INTEGER NOT NULL,
  thread_id INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  subject varchar(255) NOT NULL,
  message text NOT NULL,
  date_created INTEGER NOT NULL,
  date_modified INTEGER NOT NULL
) ;

CREATE INDEX %%PREFIX%%hfnu_notify_id_user ON %%PREFIX%%hfnu_notify (id_user);
CREATE INDEX %%PREFIX%%hfnu_notify_id_post ON %%PREFIX%%hfnu_notify (id_post);


CREATE TABLE %%PREFIX%%hfnu_bans (
  ban_id INTEGER NOT NULL PRIMARY KEY ,
  ban_username varchar(200) DEFAULT NULL,
  ban_ip varchar(255) DEFAULT NULL,
  ban_email varchar(50) DEFAULT NULL,
  ban_message varchar(255) DEFAULT NULL,
  ban_expire int(10) DEFAULT NULL
) ;


CREATE TABLE %%PREFIX%%hfnu_read_forum (
  id_user INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  date_read INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX hfnu_read_forum_id ON %%PREFIX%%hfnu_read_forum (id_user,id_forum);


CREATE TABLE %%PREFIX%%hfnu_read_posts (
  id_user INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  date_read INTEGER NOT NULL,
  thread_id INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX %%PREFIX%%hfnu_read_posts_id ON %%PREFIX%%hfnu_read_posts (id_user,id_forum,thread_id);


CREATE TABLE %%PREFIX%%hfnu_subscriptions (
	id_user INTEGER NOT NULL,
	id_post INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX %%PREFIX%%hfnu_subscriptions_id ON %%PREFIX%%hfnu_subscriptions (id_user , id_post);


-- ========================= DATA


INSERT INTO %%PREFIX%%hfnu_forum_category (id_cat, cat_name, cat_order) VALUES (1, 'My First Forum', 1);
INSERT INTO %%PREFIX%%hfnu_forum_category (id_cat, cat_name, cat_order) VALUES (2, 'My Second forum', 2);



INSERT INTO %%PREFIX%%hfnu_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire, id_last_msg, date_last_msg) VALUES (1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0,'',0, 1,0,1,1);
INSERT INTO %%PREFIX%%hfnu_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire, id_last_msg, date_last_msg) VALUES (2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0,'',0, 0,0,0,0);
INSERT INTO %%PREFIX%%hfnu_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire, id_last_msg, date_last_msg) VALUES (3, 'Light', 2, 'Soo light', 1, 0, 0, 0,'',0, 0,0,0,0);
INSERT INTO %%PREFIX%%hfnu_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire, id_last_msg, date_last_msg) VALUES (4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0,'',0, 0,0,0,0);


INSERT INTO %%PREFIX%%hfnu_threads (id_thread, id_forum,id_user,status,id_first_msg,id_last_msg,date_created,date_last_post,nb_viewed,nb_replies,ispined,iscensored)
VALUES (1,1,1,3,1,1,UNIX_TIMESTAMP(),UNIX_TIMESTAMP(),0,0,0,0);



INSERT INTO %%PREFIX%%hfnu_posts (id_post, id_user, id_forum, thread_id, status, subject, message, date_created, date_modified, viewed, poster_ip, censored_msg,read_by_mod ) VALUES
(1, 1, 1, 1, 3, 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)',
strftime('%s','now') ,strftime('%s','now') , 1, '127.0.0.1',NULL,1);


INSERT INTO %%PREFIX%%hfnu_rank (id_rank, rank_name, rank_limit) VALUES (1, 'new member', 0);
INSERT INTO %%PREFIX%%hfnu_rank (id_rank, rank_name, rank_limit) VALUES (2, 'member', 40);
INSERT INTO %%PREFIX%%hfnu_rank (id_rank, rank_name, rank_limit) VALUES (3, 'active member', 100);
