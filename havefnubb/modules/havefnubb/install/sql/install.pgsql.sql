SET client_encoding = 'UTF8';

DROP TABLE IF EXISTS %%PREFIX%%hfnu_bans;
CREATE TABLE %%PREFIX%%hfnu_bans (
    ban_id SERIAL,
    ban_username TEXT,
    ban_ip TEXT,
    ban_email TEXT,
    ban_message TEXT,
    ban_expire INTEGER,
    CONSTRAINT %%PREFIX%%hfnu_bans_ban_id_pk PRIMARY KEY (ban_id)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_forum_category;
CREATE TABLE %%PREFIX%%hfnu_forum_category (
    id_cat SERIAL,
    cat_name TEXT NOT NULL,
    cat_order INTEGER NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_forum_category_id_cat_pk PRIMARY KEY (id_cat)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_connected;
CREATE TABLE %%PREFIX%%hfnu_connected (
    id_user INTEGER DEFAULT 1 NOT NULL,
    member_ip TEXT NOT NULL,
    connected INTEGER DEFAULT 0 NOT NULL,
    idle INTEGER DEFAULT 0,
    CONSTRAINT %%PREFIX%%connected_id_user_pk PRIMARY KEY (id_user)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_forum;
CREATE TABLE %%PREFIX%%hfnu_forum (
    id_forum SERIAL,
    forum_name TEXT NOT NULL,
    id_cat INTEGER NOT NULL,
    forum_desc TEXT NOT NULL,
    forum_order INTEGER NOT NULL,
    parent_id INTEGER NOT NULL,
    child_level INTEGER NOT NULL,
    forum_type INTEGER NOT NULL,
    forum_url TEXT,
    post_expire INTEGER DEFAULT 0,
    id_last_msg INTEGER NOT NULL,
    date_last_msg TIMESTAMP NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_forum_id_forum_pk PRIMARY KEY (id_forum)
);

ALTER TABLE %%PREFIX%%community_users ADD  website TEXT;
ALTER TABLE %%PREFIX%%community_users ADD  firstname TEXT;
ALTER TABLE %%PREFIX%%community_users ADD  birth date DEFAULT '1971-01-01'::date NOT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  country TEXT;
ALTER TABLE %%PREFIX%%community_users ADD  town TEXT;
ALTER TABLE %%PREFIX%%community_users ADD  comment TEXT;
ALTER TABLE %%PREFIX%%community_users ADD  avatar TEXT;
ALTER TABLE %%PREFIX%%community_users ADD  last_connect INTEGER;
ALTER TABLE %%PREFIX%%community_users ADD  show_email TEXT DEFAULT 'N';
ALTER TABLE %%PREFIX%%community_users ADD  language TEXT DEFAULT 'fr_FR';
ALTER TABLE %%PREFIX%%community_users ADD  nb_msg INTEGER DEFAULT 0;
ALTER TABLE %%PREFIX%%community_users ADD  last_post INTEGER DEFAULT 0 NOT NULL;
ALTER TABLE %%PREFIX%%community_users ADD  gravatar INTEGER DEFAULT 0 NOT NULL;

DROP TABLE IF EXISTS %%PREFIX%%hfnu_member_custom_fields;
CREATE TABLE %%PREFIX%%hfnu_member_custom_fields (
    id_user INTEGER NOT NULL,
    type TEXT NOT NULL,
    data TEXT NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_member_custom_fields_id_user_type_pk PRIMARY KEY (id_user,type)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_notify;
CREATE TABLE %%PREFIX%%hfnu_notify (
    id_notify INTEGER NOT NULL,
    id_user INTEGER NOT NULL,
    id_post INTEGER NOT NULL,
    parent_id INTEGER NOT NULL,
    id_forum INTEGER NOT NULL,
    subject TEXT NOT NULL,
    message TEXT NOT NULL,
    date_created TIMESTAMP NOT NULL,
    date_modified TIMESTAMP NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_notify_id_notify_pk PRIMARY KEY (id_notify)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_posts;
CREATE TABLE %%PREFIX%%hfnu_posts (
    id_post SERIAL,
    id_user INTEGER NOT NULL,
    id_forum INTEGER NOT NULL,
    parent_id INTEGER NOT NULL,
    status INTEGER DEFAULT 3 NOT NULL,
    subject TEXT NOT NULL,
    message TEXT NOT NULL,
    date_created TIMESTAMP NOT NULL,
    date_modified TIMESTAMP NOT NULL,
    viewed INTEGER NOT NULL,
    poster_ip TEXT NOT NULL,
    censored_msg TEXT,
    read_by_mod INTEGER DEFAULT 0,
    ispined INTEGER NOT NULL DEFAULT '0',
    iscensored INTEGER NOT NULL DEFAULT '0',
    censored_by INTEGER,
    CONSTRAINT %%PREFIX%%hfnu_posts_id_post_pk PRIMARY KEY (id_post)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_threads;
CREATE TABLE %%PREFIX%%hfnu_threads (
    id_thread SERIAL,
    id_forum INTEGER NOT NULL,
    id_user INTEGER NOT NULL,
    status INTEGER NOT NULL,
    id_first_msg INTEGER NOT NULL,
    id_last_msg INTEGER NOT NULL,
    date_created TIMESTAMP NOT NULL,
    date_last_post TIMESTAMP DEFAULT NULL,
    nb_viewed INTEGER DEFAULT '0',
    nb_replies INTEGER DEFAULT '0',
    ispined INTEGER NOT NULL DEFAULT '0',
    iscensored INTEGER NOT NULL DEFAULT '0',
    CONSTRAINT %%PREFIX%%hfnu_threads_id_thread_pk PRIMARY KEY (id_thread)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_rank;
CREATE TABLE %%PREFIX%%hfnu_rank (
    id_rank SERIAL,
    rank_name TEXT NOT NULL,
    rank_limit INTEGER NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_rank_id_rank_pk PRIMARY KEY (id_rank)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_read_forum;
CREATE TABLE %%PREFIX%%hfnu_read_forum (
    id_user INTEGER NOT NULL,
    id_forum INTEGER NOT NULL,
    date_read INTEGER NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_read_forum_id_user_id_forum_date_read_pk PRIMARY KEY (id_user,id_forum,date_read)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_read_posts;
CREATE TABLE %%PREFIX%%hfnu_read_posts (
    id_user INTEGER NOT NULL,
    id_forum INTEGER NOT NULL,
    id_post INTEGER NOT NULL,
    date_read INTEGER NOT NULL,
    parent_id INTEGER NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_read_posts_id_user_id_forum_date_read_pk PRIMARY KEY (id_user,id_forum,id_post)
);

DROP TABLE IF EXISTS %%PREFIX%%hfnu_subscriptions;
CREATE TABLE %%PREFIX%%hfnu_subscriptions (
    id_user INTEGER NOT NULL,
    id_post INTEGER NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_subscriptions_id_user_id_post_pk PRIMARY KEY (id_user,id_post)
);

-- forum index
DROP INDEX IF EXISTS %%PREFIX%%hfnu_forum_child_level_idx;
CREATE INDEX %%PREFIX%%hfnu_forum_child_level_idx ON %%PREFIX%%hfnu_forum USING btree (child_level);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_forum_parent_id_idx;
CREATE INDEX %%PREFIX%%hfnu_forum_parent_id_idx ON %%PREFIX%%hfnu_forum USING btree (parent_id);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_forum_forum_type_idx;
CREATE INDEX %%PREFIX%%hfnu_forum_forum_type_idx ON %%PREFIX%%hfnu_forum USING btree (forum_type);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_forum_id_cat_idx;
CREATE INDEX %%PREFIX%%hfnu_forum_id_cat_idx ON %%PREFIX%%hfnu_forum USING btree (id_cat);

-- notify index
DROP INDEX IF EXISTS %%PREFIX%%hfnu_notify_id_post_idx;
CREATE INDEX %%PREFIX%%hfnu_notify_id_post_idx ON %%PREFIX%%hfnu_notify USING btree (id_post);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_notify_id_user_idx;
CREATE INDEX %%PREFIX%%hfnu_notify_id_user_idx ON %%PREFIX%%hfnu_notify USING btree (id_user);

-- posts index
DROP INDEX IF EXISTS %%PREFIX%%hfnu_postsid_user_id_forum_parent_id_status_idx;
CREATE INDEX %%PREFIX%%hfnu_postsid_user_id_forum_parent_id_status_idx ON %%PREFIX%%hfnu_posts USING btree (id_user, id_forum, parent_id, status);

-- threads index
DROP INDEX IF EXISTS %%PREFIX%%hfnu_threads_id_forum_idx;
CREATE INDEX %%PREFIX%%hfnu_threads_id_forum_idx ON %%PREFIX%%hfnu_threads USING btree (id_forum);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_threads_id_user_idx;
CREATE INDEX %%PREFIX%%hfnu_threads_id_user_idx ON %%PREFIX%%hfnu_threads USING btree (id_user);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_threads_status_idx;
CREATE INDEX %%PREFIX%%hfnu_threads_status_idx ON %%PREFIX%%hfnu_threads USING btree (status);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_threads_id_first_msg_idx;
CREATE INDEX %%PREFIX%%hfnu_threads_id_first_msg_idx ON %%PREFIX%%hfnu_threads USING btree (id_first_msg);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_threads_id_last_msg_idx;
CREATE INDEX %%PREFIX%%hfnu_threads_id_last_msg_idx ON %%PREFIX%%hfnu_threads USING btree (id_last_msg);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_threads_ispined_idx;
CREATE INDEX %%PREFIX%%hfnu_threads_ispined_idx ON %%PREFIX%%hfnu_threads USING btree (ispined);
DROP INDEX IF EXISTS %%PREFIX%%hfnu_threads_iscensored_idx;
CREATE INDEX %%PREFIX%%hfnu_threads_iscensored_idx ON %%PREFIX%%hfnu_threads USING btree (iscensored);

-- ================================== DATA
-- category
INSERT INTO %%PREFIX%%hfnu_forum_category (cat_name, cat_order) VALUES ('My First Forum', 1);
INSERT INTO %%PREFIX%%hfnu_forum_category (cat_name, cat_order) VALUES ('My Second forum', 2);
-- forum
INSERT INTO %%PREFIX%%hfnu_forum (forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire, id_last_msg, date_last_msg)
    VALUES ('My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0, '', 0, 1, NOW());
INSERT INTO %%PREFIX%%hfnu_forum (forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire, id_last_msg, date_last_msg)
    VALUES ('My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0, '', 0, 0,NOW());
INSERT INTO %%PREFIX%%hfnu_forum (forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire, id_last_msg, date_last_msg)
    VALUES ('Light', 2, 'Soo light', 1, 0, 0, 0, '', 0, 0,NOW());
INSERT INTO %%PREFIX%%hfnu_forum (forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire, id_last_msg, date_last_msg)
    VALUES ('My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0, '', 0, 0,NOW());

-- threads
INSERT INTO %%PREFIX%%hfnu_threads (id_forum,id_user,status,id_first_msg,id_last_msg,date_created,date_last_post,nb_viewed,nb_replies,ispined,iscensored)
VALUES (1,1,3,1,1,NOW(),NOW(),0,0,0,0);

-- posts
INSERT INTO %%PREFIX%%hfnu_posts (id_user, id_forum, parent_id, status, subject, message, date_created, date_modified, viewed, poster_ip, censored_msg, read_by_mod) VALUES (1, 1, 1, 3, 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)', NOW(),NOW(), 1, '127.0.0.1', NULL, 1);

-- ranks
INSERT INTO %%PREFIX%%hfnu_rank ( rank_name, rank_limit) VALUES ('new member', 0);
INSERT INTO %%PREFIX%%hfnu_rank ( rank_name, rank_limit) VALUES ('member', 40);
INSERT INTO %%PREFIX%%hfnu_rank ( rank_name, rank_limit) VALUES ('active member', 100);
