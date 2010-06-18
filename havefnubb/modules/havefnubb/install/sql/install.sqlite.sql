
CREATE TABLE %%PREFIX%%category (
  id_cat INTEGER NOT NULL PRIMARY KEY,
  cat_name varchar(255) NOT NULL,
  cat_order int(4) NOT NULL
);


CREATE TABLE %%PREFIX%%forum  (
    id_forum INTEGER NOT  NULL PRIMARY  KEY ,
    forum_name  VARCHAR ( 255 ) NOT  NULL ,
    id_cat INTEGER  NOT NULL  ,
    forum_desc VARCHAR ( 255  )  NOT NULL  ,
    forum_order INT ( 4  )  NOT NULL  ,
    parent_id INT ( 11  )  NOT NULL  ,
    child_level INT ( 4  )  NOT NULL  ,
    forum_type INT ( 1  )  NOT NULL  ,
    forum_url VARCHAR ( 255  )  NOT NULL  ,
    post_expire INT ( 5  )  NOT NULL
);
CREATE INDEX %%PREFIX%%forum_id_cat  ON %%PREFIX%%forum  (  id_cat ) ;
CREATE INDEX %%PREFIX%%forum_parent_id  ON %%PREFIX%%forum  (  parent_id ) ;
CREATE INDEX %%PREFIX%%forum_child_level  ON %%PREFIX%%forum  (  child_level ) ;
CREATE INDEX %%PREFIX%%forum_forum_type  ON %%PREFIX%%forum  (  forum_type ) ;



ALTER TABLE %%PREFIX%%community_users ADD  website varchar(255) DEFAULT NULL,
ALTER TABLE %%PREFIX%%community_users ADD  firstname varchar(40) DEFAULT NULL,
ALTER TABLE %%PREFIX%%community_users ADD  birth date NOT NULL DEFAULT '1980-01-01',
ALTER TABLE %%PREFIX%%community_users ADD  country varchar(100) DEFAULT NULL,
ALTER TABLE %%PREFIX%%community_users ADD  town varchar(100) DEFAULT NULL,
ALTER TABLE %%PREFIX%%community_users ADD  comment varchar(255) DEFAULT NULL,
ALTER TABLE %%PREFIX%%community_users ADD  avatar varchar(255) DEFAULT NULL,
ALTER TABLE %%PREFIX%%community_users ADD  last_connect INTEGER DEFAULT NULL,
ALTER TABLE %%PREFIX%%community_users ADD  show_email varchar(1) DEFAULT 'N',
ALTER TABLE %%PREFIX%%community_users ADD  language varchar(40) DEFAULT 'fr_FR',
ALTER TABLE %%PREFIX%%community_users ADD  nb_msg INTEGER DEFAULT '0',
ALTER TABLE %%PREFIX%%community_users ADD  last_post INTEGER NOT NULL DEFAULT '0',
ALTER TABLE %%PREFIX%%community_users ADD  gravatar INT( 1 ) NOT NULL DEFAULT '0'


CREATE TABLE %%PREFIX%%member_custom_fields (
  id_user int(11) NOT NULL,
  type varchar(30) NOT NULL,
  data text NOT NULL,
  PRIMARY KEY  (id_user,type)
);



CREATE TABLE %%PREFIX%%posts (
  id_post INTEGER NOT NULL PRIMARY KEY ,
  id_user INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  parent_id INTEGER NOT NULL,
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
CREATE INDEX %%PREFIX%%posts_id_user ON %%PREFIX%%posts  (  id_user );
CREATE INDEX %%PREFIX%%posts_id_forum ON %%PREFIX%%posts  (  id_forum );
CREATE INDEX %%PREFIX%%posts_parent_id ON %%PREFIX%%posts  (  parent_id );
CREATE INDEX %%PREFIX%%posts_status ON %%PREFIX%%posts  (  status );


CREATE TABLE %%PREFIX%%rank (
  id_rank INTEGER NOT NULL PRIMARY KEY  ,
  rank_name varchar(40) NOT NULL,
  rank_limit int(9) NOT NULL
) ;



CREATE TABLE %%PREFIX%%notify (
  id_notify INTEGER NOT NULL PRIMARY KEY ,
  id_user INTEGER NOT NULL,
  id_post INTEGER NOT NULL,
  parent_id INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  subject varchar(255) NOT NULL,
  message text NOT NULL,
  date_created INTEGER NOT NULL,
  date_modified INTEGER NOT NULL
) ;

CREATE INDEX %%PREFIX%%notify_id_user ON %%PREFIX%%notify (id_user);
CREATE INDEX %%PREFIX%%notify_id_post ON %%PREFIX%%notify (id_post);


CREATE TABLE %%PREFIX%%bans (
  ban_id INTEGER NOT NULL PRIMARY KEY ,
  ban_username varchar(200) DEFAULT NULL,
  ban_ip varchar(255) DEFAULT NULL,
  ban_email varchar(50) DEFAULT NULL,
  ban_message varchar(255) DEFAULT NULL,
  ban_expire int(10) DEFAULT NULL
) ;




CREATE TABLE %%PREFIX%%connected (
    id_user INTEGER NOT NULL DEFAULT '1'  PRIMARY KEY,
    member_ip VARCHAR(200) NOT NULL DEFAULT '',
    connected INT(10) NOT NULL DEFAULT 0,
    idle TINYINT(1) NOT NULL DEFAULT 0
) ;




CREATE TABLE %%PREFIX%%read_forum (
  id_user INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  date_read INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX read_forum_id ON %%PREFIX%%read_forum (id_user,id_forum,date_read);
CREATE INDEX %%PREFIX%%read_forum_id_user ON %%PREFIX%%read_forum ( id_user );
CREATE INDEX %%PREFIX%%read_forum_id_forum ON %%PREFIX%%read_forum( id_forum );
CREATE INDEX %%PREFIX%%read_forum_date_read ON %%PREFIX%%read_forum( date_read );


CREATE TABLE %%PREFIX%%read_posts (
  id_user INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  id_post INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX %%PREFIX%%read_posts_id ON %%PREFIX%%read_posts (id_user,id_forum,id_post);
CREATE INDEX %%PREFIX%%read_posts_id_user ON %%PREFIX%%read_posts ( id_user );
CREATE INDEX %%PREFIX%%read_posts_id_forum ON %%PREFIX%%read_posts( id_forum );
CREATE INDEX %%PREFIX%%read_posts_id_post ON %%PREFIX%%read_posts( id_post );

CREATE TABLE %%PREFIX%%subscriptions (
	id_user INTEGER NOT NULL,
	id_post INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX %%PREFIX%%subscriptions_id ON %%PREFIX%%subscriptions (id_user , id_post);


-- ========================= DATA


INSERT INTO %%PREFIX%%category (id_cat, cat_name, cat_order) VALUES (1, 'My First Forum', 1);
INSERT INTO %%PREFIX%%category (id_cat, cat_name, cat_order) VALUES (2, 'My Second forum', 2);



INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0,'',0);
INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0,'',0);
INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (3, 'Light', 2, 'Soo light', 1, 0, 0, 0,'',0);
INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0,'',0);


INSERT INTO %%PREFIX%%threads (id_thread, id_forum,id_user,status,id_first_msg,id_last_msg,date_created,date_last_post,nb_viewed,nb_replies,ispined,iscensored)
VALUES (1,1,1,3,1,1,UNIX_TIMESTAMP(),0,0,0,0,0);



INSERT INTO %%PREFIX%%posts (id_post, id_user, id_forum, parent_id, status, subject, message, date_created, date_modified, viewed, poster_ip, censored_msg,read_by_mod ) VALUES
(1, 1, 1, 1, 3, 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)',
strftime('%s','now') ,strftime('%s','now') , 1, '127.0.0.1',NULL,1);


INSERT INTO %%PREFIX%%rank (id_rank, rank_name, rank_limit) VALUES (1, 'new member', 0);
INSERT INTO %%PREFIX%%rank (id_rank, rank_name, rank_limit) VALUES (2, 'member', 40);
INSERT INTO %%PREFIX%%rank (id_rank, rank_name, rank_limit) VALUES (3, 'active member', 100);

