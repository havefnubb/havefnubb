
CREATE TABLE hf_category (
  id_cat INTEGER NOT NULL PRIMARY KEY,
  cat_name varchar(255) NOT NULL,
  cat_order int(4) NOT NULL
);


CREATE TABLE hf_forum  (
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
CREATE INDEX hf_forum_id_cat  ON hf_forum  (  id_cat ) ;
CREATE INDEX hf_forum_parent_id  ON hf_forum  (  parent_id ) ;
CREATE INDEX hf_forum_child_level  ON hf_forum  (  child_level ) ;
CREATE INDEX hf_forum_forum_type  ON hf_forum  (  forum_type ) ;



CREATE TABLE hf_member (
  id_user INTEGER NOT NULL ,
  member_login varchar(50) NOT NULL PRIMARY KEY ,
  member_password varchar(50) NOT NULL,
  member_email varchar(255) NOT NULL,
  member_nickname varchar(50) DEFAULT NULL,
  member_status tinyint(4) NOT NULL default '0',
  member_keyactivate varchar(10) DEFAULT NULL,
  member_request_date datetime DEFAULT NULL,
  member_website varchar(255) DEFAULT NULL,
  member_firstname varchar(40) DEFAULT NULL,
  member_birth date NOT NULL DEFAULT '1980-01-01',
  member_country varchar(100) DEFAULT NULL,
  member_town varchar(100) DEFAULT NULL,
  member_comment varchar(255) DEFAULT NULL,
  member_avatar varchar(255) DEFAULT NULL,
  member_last_connect INTEGER DEFAULT NULL,
  member_show_email varchar(1) DEFAULT 'N',
  member_language varchar(40) DEFAULT 'fr_FR',
  member_nb_msg INTEGER DEFAULT '0',
  member_last_post INTEGER NOT NULL DEFAULT '0',
  member_created datetime DEFAULT NULL,
  member_gravatar INT( 1 ) NOT NULL DEFAULT '0'
) ;
CREATE UNIQUE INDEX  hf_member_id_user ON hf_member  (  id_user ) ;

CREATE TABLE hf_member_custom_fields (
  id_user int(11) NOT NULL,
  type varchar(30) NOT NULL,
  data text NOT NULL,
  PRIMARY KEY  (id_user,type)
);



CREATE TABLE hf_posts (
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
CREATE INDEX hf_posts_id_user ON hf_posts  (  id_user );
CREATE INDEX hf_posts_id_forum ON hf_posts  (  id_forum );
CREATE INDEX hf_posts_parent_id ON hf_posts  (  parent_id );
CREATE INDEX hf_posts_status ON hf_posts  (  status );


CREATE TABLE hf_rank (
  id_rank INTEGER NOT NULL PRIMARY KEY  ,
  rank_name varchar(40) NOT NULL,
  rank_limit int(9) NOT NULL
) ;



CREATE TABLE hf_notify (
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

CREATE INDEX hf_notify_id_user ON hf_notify (id_user);
CREATE INDEX hf_notify_id_post ON hf_notify (id_post);


CREATE TABLE hf_bans (
  ban_id INTEGER NOT NULL PRIMARY KEY ,
  ban_username varchar(200) DEFAULT NULL,
  ban_ip varchar(255) DEFAULT NULL,
  ban_email varchar(50) DEFAULT NULL,
  ban_message varchar(255) DEFAULT NULL,
  ban_expire int(10) DEFAULT NULL
) ;




CREATE TABLE hf_connected (
    id_user INTEGER NOT NULL DEFAULT '1'  PRIMARY KEY,
    member_ip VARCHAR(200) NOT NULL DEFAULT '',
    connected INT(10) NOT NULL DEFAULT 0,
    idle TINYINT(1) NOT NULL DEFAULT 0
) ;




CREATE TABLE hf_read_forum (
  id_user INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  date_read INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX read_forum_id ON hf_read_forum (id_user,id_forum,date_read);
CREATE INDEX hf_read_forum_id_user ON hf_read_forum ( id_user );
CREATE INDEX hf_read_forum_id_forum ON hf_read_forum( id_forum );
CREATE INDEX hf_read_forum_date_read ON hf_read_forum( date_read );


CREATE TABLE hf_read_posts (
  id_user INTEGER NOT NULL,
  id_forum INTEGER NOT NULL,
  id_post INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX hf_read_posts_id ON hf_read_posts (id_user,id_forum,id_post);
CREATE INDEX hf_read_posts_id_user ON hf_read_posts ( id_user );
CREATE INDEX hf_read_posts_id_forum ON hf_read_posts( id_forum );
CREATE INDEX hf_read_posts_id_post ON hf_read_posts( id_post );

CREATE TABLE hf_subscriptions (
	id_user INTEGER NOT NULL,
	id_post INTEGER NOT NULL
) ;
CREATE UNIQUE INDEX hf_subscriptions_id ON hf_subscriptions (id_user , id_post);


--- ========================= DATA


INSERT INTO hf_category (id_cat, cat_name, cat_order) VALUES (1, 'My First Forum', 1);
INSERT INTO hf_category (id_cat, cat_name, cat_order) VALUES (2, 'My Second forum', 2);



INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0,'',0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0,'',0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (3, 'Light', 2, 'Soo light', 1, 0, 0, 0,'',0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0,'',0);


INSERT INTO hf_posts (id_post, id_user, id_forum, parent_id, status, subject, message, date_created, date_modified, viewed, poster_ip, censored_msg,read_by_mod ) VALUES
(1, 1, 1, 1, 3, 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)',
strftime('%s','now') ,strftime('%s','now') , 1, '127.0.0.1',NULL,1);


INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (1, 'new member', 0);
INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (2, 'member', 40);
INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (3, 'active member', 100);

