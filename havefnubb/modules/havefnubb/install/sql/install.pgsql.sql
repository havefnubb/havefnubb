SET client_encoding = 'UTF8';

CREATE TABLE %%PREFIX%%bans (
    ban_id SERIAL NOT NULL,
    ban_username character varying(200),
    ban_ip character varying(255),
    ban_email character varying(50),
    ban_message character varying(255),
    ban_expire bigint,
    CONSTRAINT ban_id_pk PRIMARY KEY (ban_id)
);

CREATE TABLE %%PREFIX%%category (
    id_cat SERIAL NOT NULL,
    cat_name character varying(255) NOT NULL,
    cat_order integer NOT NULL,
    CONSTRAINT id_cat PRIMARY KEY (id_cat)
);

CREATE TABLE %%PREFIX%%connected (
    id_user integer DEFAULT 1 NOT NULL,
    member_ip character varying(200) NOT NULL,
    connected bigint DEFAULT 0::bigint NOT NULL,
    idle integer DEFAULT 0,
    CONSTRAINT id_user_connected PRIMARY KEY (id_user)
);

CREATE TABLE %%PREFIX%%forum (
    id_forum serial NOT NULL,
    forum_name character varying(255) NOT NULL,
    id_cat integer NOT NULL,
    forum_desc character varying(255) NOT NULL,
    forum_order integer NOT NULL,
    parent_id integer NOT NULL,
    child_level integer NOT NULL,
    forum_type integer NOT NULL,
    forum_url character varying(255),
    post_expire integer DEFAULT 0,
    id_last_msg integer NOT NULL,
    date_last_msg integer NOT NULL,
    CONSTRAINT id_forum PRIMARY KEY (id_forum)
);

ALTER TABLE %%PREFIX%%community_users ADD  website character varying(255);
ALTER TABLE %%PREFIX%%community_users ADD  firstname character varying(40);
ALTER TABLE %%PREFIX%%community_users ADD  birth date DEFAULT '1971-01-01'::date NOT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  country character varying(100);
ALTER TABLE %%PREFIX%%community_users ADD  town character varying(100);
ALTER TABLE %%PREFIX%%community_users ADD  comment character varying(255);
ALTER TABLE %%PREFIX%%community_users ADD  avatar character varying(255);
ALTER TABLE %%PREFIX%%community_users ADD  last_connect integer;
ALTER TABLE %%PREFIX%%community_users ADD  show_email character varying(1) DEFAULT 'N'::character varying;
ALTER TABLE %%PREFIX%%community_users ADD  language character varying(40) DEFAULT 'fr_FR'::character varying;
ALTER TABLE %%PREFIX%%community_users ADD  nb_msg integer DEFAULT 0;
ALTER TABLE %%PREFIX%%community_users ADD  last_post integer DEFAULT 0 NOT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  gravatar integer DEFAULT 0 NOT NULL;

CREATE TABLE %%PREFIX%%member_custom_fields (
    id_user integer NOT NULL,
    type character varying(30) NOT NULL,
    data text NOT NULL,
    CONSTRAINT id_user_type PRIMARY KEY (id_user,type)
);

CREATE TABLE %%PREFIX%%notify (
    id_notify integer NOT NULL,
    id_user integer NOT NULL,
    id_post integer NOT NULL,
    parent_id integer NOT NULL,
    id_forum integer NOT NULL,
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    date_created integer NOT NULL,
    date_modified integer NOT NULL,
    CONSTRAINT id_notify PRIMARY KEY (id_notify)
);

CREATE TABLE %%PREFIX%%posts (
    id_post serial NOT NULL,
    id_user integer NOT NULL,
    id_forum integer NOT NULL,
    parent_id integer NOT NULL,
    status character varying(12) DEFAULT 'opened'::character varying NOT NULL,
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    date_created integer NOT NULL,
    date_modified integer NOT NULL,
    viewed integer NOT NULL,
    poster_ip character varying(15) NOT NULL,
    censored_msg character varying(50),
    read_by_mod integer DEFAULT 0,
    CONSTRAINT id_post PRIMARY KEY (id_post)
);

CREATE TABLE %%PREFIX%%threads (
  id_thread serial NOT NULL,
  id_forum int(11) NOT NULL,
  id_user int(11) NOT NULL,
  status int(11) NOT NULL,
  id_first_msg int(11) NOT NULL,
  id_last_msg int(11) NOT NULL,
  date_created int(11) NOT NULL,
  date_last_post int(11) DEFAULT NULL,
  nb_viewed int(11) DEFAULT '0',
  b_replies int(11) DEFAULT '0',
  ispined int(1) NOT NULL DEFAULT '0',
  iscensored int(1) NOT NULL DEFAULT '0',
  CONSTRAINT id_thread PRIMARY KEY (id_thread)
);

CREATE TABLE %%PREFIX%%rank (
    id_rank serial NOT NULL,
    rank_name character varying(40) NOT NULL,
    rank_limit integer NOT NULL,
    CONSTRAINT id_rank PRIMARY KEY (id_rank)
);

CREATE TABLE %%PREFIX%%subscriptions (
    id_user integer NOT NULL,
    id_post integer NOT NULL,
    CONSTRAINT user_post PRIMARY KEY (id_user,id_post)
);

-- forum index
CREATE INDEX child_level ON %%PREFIX%%forum USING btree (child_level);
CREATE INDEX parent_id ON %%PREFIX%%forum USING btree (parent_id);
CREATE INDEX forum_type ON %%PREFIX%%forum USING btree (forum_type);
CREATE INDEX id_cat_idx ON %%PREFIX%%forum USING btree (id_cat);

-- notify index
CREATE INDEX id_post_notify_idx ON %%PREFIX%%notify USING btree (id_post);
CREATE INDEX id_use_notify_idx ON %%PREFIX%%notify USING btree (id_user);

-- posts index
CREATE INDEX id_user_post_idx ON %%PREFIX%%posts USING btree (id_user, id_forum, parent_id, status);

-- threads index
CREATE INDEX id_forum ON %%PREFIX%%threads USING btree (id_forum);
CREATE INDEX id_user ON %%PREFIX%%threads USING btree (id_user);
CREATE INDEX status ON %%PREFIX%%threads USING btree (status);
CREATE INDEX id_first_msg ON %%PREFIX%%threads USING btree (id_first_msg);
CREATE INDEX id_last_msg ON %%PREFIX%%threads USING btree (id_last_msg);
CREATE INDEX ispined ON %%PREFIX%%threads USING btree (ispined);
CREATE INDEX iscensored ON %%PREFIX%%threads USING btree (iscensored);

-- ================================== DATA
-- category
INSERT INTO %%PREFIX%%category (id_cat, cat_name, cat_order) VALUES (1, 'My First Forum', 1);
INSERT INTO %%PREFIX%%category (id_cat, cat_name, cat_order) VALUES (2, 'My Second forum', 2);

-- forum
INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire, id_last_msg, date_last_msg)
    VALUES (1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0, '', 0, 1, 0);
INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire, id_last_msg, date_last_msg)
    VALUES (2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0, '', 0, 0,0);
INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire, id_last_msg, date_last_msg)
    VALUES (3, 'Light', 2, 'Soo light', 1, 0, 0, 0, '', 0, 0,0);
INSERT INTO %%PREFIX%%forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire, id_last_msg, date_last_msg)
    VALUES (4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0, '', 0, 0,0);

-- threads
INSERT INTO %%PREFIX%%threads (id_forum,id_user,status,id_first_msg,id_last_msg,date_created,date_last_post,nb_viewed,nb_replies,ispined,iscensored)
VALUES (1,1,3,1,1,UNIX_TIMESTAMP(),UNIX_TIMESTAMP(),0,0,0,0);

-- posts
INSERT INTO %%PREFIX%%posts (id_user, id_forum, parent_id, status, subject, message, date_created, date_modified, viewed, poster_ip, censored_msg, read_by_mod) VALUES (1, 1, 1, 'opened', 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)', UNIX_TIMESTAMP(),UNIX_TIMESTAMP(), 1, '127.0.0.1', NULL, 1);

-- ranks
INSERT INTO %%PREFIX%%rank ( rank_name, rank_limit) VALUES ('new member', 0);
INSERT INTO %%PREFIX%%rank ( rank_name, rank_limit) VALUES ('member', 40);
INSERT INTO %%PREFIX%%rank ( rank_name, rank_limit) VALUES ('active member', 100);

