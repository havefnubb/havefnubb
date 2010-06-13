--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET check_function_bodies = false;
SET client_min_messages = warning;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE hf_bans (
    ban_id SERIAL NOT NULL,
    ban_username character varying(200),
    ban_ip character varying(255),
    ban_email character varying(50),
    ban_message character varying(255),
    ban_expire bigint,
    CONSTRAINT ban_id_pk PRIMARY KEY (ban_id)
);

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_bans', 'ban_id'), 1, false);


CREATE TABLE hf_category (
    id_cat SERIAL NOT NULL,
    cat_name character varying(255) NOT NULL,
    cat_order integer NOT NULL,
    CONSTRAINT id_cat PRIMARY KEY (id_cat)
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_category', 'id_cat'), 1, false);


CREATE TABLE hf_connected (
    id_user integer DEFAULT 1 NOT NULL,
    member_ip character varying(200) NOT NULL,
    connected bigint DEFAULT 0::bigint NOT NULL,
    idle integer DEFAULT 0,
    CONSTRAINT id_user_connected PRIMARY KEY (id_user)
);



CREATE TABLE hf_forum (
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
    CONSTRAINT id_forum PRIMARY KEY (id_forum)
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_forum', 'id_forum'), 1, false);


CREATE TABLE hf_jacl2_group (
    id_aclgrp serial NOT NULL,
    name character varying(150) NOT NULL,
    grouptype smallint DEFAULT 0::smallint NOT NULL,
    ownerlogin character varying(50)
);

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_jacl2_group', 'id_aclgrp'), 1, false);


CREATE TABLE hf_jacl2_rights (
    id_aclsbj character varying(100) NOT NULL,
    id_aclgrp integer DEFAULT 0 NOT NULL,
    id_aclres character varying(100) NOT NULL
);



CREATE TABLE hf_jacl2_subject (
    id_aclsbj character varying(100) NOT NULL,
    label_key character varying(100)
);




CREATE TABLE hf_jacl2_user_group (
    login character varying(50) NOT NULL,
    id_aclgrp integer DEFAULT 0 NOT NULL
);



CREATE TABLE hf_jmessenger (
    id serial NOT NULL,
    id_from integer DEFAULT 0 NOT NULL,
    id_for integer DEFAULT 0 NOT NULL,
    date timestamp without time zone NOT NULL,
    title character varying(255) NOT NULL,
    content text NOT NULL,
    "isSeen" smallint NOT NULL,
    "isArchived" smallint NOT NULL,
    "isReceived" smallint NOT NULL,
    "isSend" smallint NOT NULL
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_jmessenger', 'id'), 1, false);



CREATE TABLE hf_member (
    id_user serial NOT NULL,
    member_login character varying(50) NOT NULL,
    member_password character varying(50) NOT NULL,
    member_email character varying(255) NOT NULL,
    member_nickname character varying(50),
    member_status smallint DEFAULT 0::smallint NOT NULL,
    member_keyactivate character varying(10),
    member_request_date timestamp without time zone,
    member_website character varying(255),
    member_firstname character varying(40),
    member_birth date DEFAULT '1971-01-01'::date NOT NULL,
    member_country character varying(100),
    member_town character varying(100),
    member_comment character varying(255),
    member_avatar character varying(255),
    member_last_connect integer,
    member_show_email character varying(1) DEFAULT 'N'::character varying,
    member_language character varying(40) DEFAULT 'fr_FR'::character varying,
    member_nb_msg integer DEFAULT 0,
    member_last_post integer DEFAULT 0 NOT NULL,
    member_created timestamp without time zone,
    member_gravatar integer DEFAULT 0 NOT NULL,
    CONSTRAINT member_login PRIMARY KEY (member_login)
);

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_member', 'id_user'), 1, false);

CREATE TABLE hf_member_custom_fields (
    id_user integer NOT NULL,
    type character varying(30) NOT NULL,
    data text NOT NULL,
    CONSTRAINT id_user_type PRIMARY KEY (id_user,type)
);



CREATE TABLE hf_notify (
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
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_notify', 'id_notify'), 1, false);


CREATE TABLE hf_posts (
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
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_posts', 'id_post'), 1, false);



CREATE TABLE hf_rank (
    id_rank serial NOT NULL,
    rank_name character varying(40) NOT NULL,
    rank_limit integer NOT NULL,
    CONSTRAINT id_rank PRIMARY KEY (id_rank)
);



CREATE TABLE hf_rates (
    id_user integer NOT NULL,
    id_source integer NOT NULL,
    source character varying(40) NOT NULL,
    ip character varying(80) NOT NULL,
    level double precision NOT NULL,
    CONSTRAINT rates_id PRIMARY KEY(id_user,id_source,source)
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_rank', 'id_rank'), 1, false);

CREATE TABLE hf_read_forum (
    id_user integer NOT NULL,
    id_forum integer NOT NULL,
    date_read integer NOT NULL,
    CONSTRAINT user_forum_date  PRIMARY KEY  (id_user,id_forum,date_read)
);

CREATE TABLE hf_read_posts (
    id_user integer NOT NULL,
    id_forum integer NOT NULL,
    id_post integer NOT NULL,
    CONSTRAINT user_forum_post PRIMARY KEY  (id_user,id_forum,id_post)
);

CREATE TABLE hf_sc_tags (
    tag_id bigint NOT NULL,
    tag_name character varying(50) NOT NULL,
    nbuse integer DEFAULT 0,
    CONSTRAINT tag_id PRIMARY KEY (tag_id),
    CONSTRAINT tag_name UNIQUE (tag_name)
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_sc_tags', 'tag_id'), 1, false);


CREATE TABLE hf_sc_tags_tagged (
    tt_id bigint NOT NULL,
    tag_id bigint NOT NULL,
    tt_scope_id character varying(50) NOT NULL,
    tt_subject_id bigint NOT NULL,
    CONSTRAINT tt_id PRIMARY KEY (tt_id)
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('hf_sc_tags_tagged', 'tt_id'), 1, false);


CREATE TABLE hf_search_words (
    id character varying(30) NOT NULL,
    datasource character varying(40) NOT NULL,
    words character varying(255) NOT NULL,
    weight integer NOT NULL,
    CONSTRAINT source_id PRIMARY KEY (id,datasource,words)
);



CREATE TABLE hf_subscriptions (
    id_user integer NOT NULL,
    id_post integer NOT NULL,
    CONSTRAINT user_post PRIMARY KEY (id_user,id_post)
);


INSERT INTO hf_category (id_cat, cat_name, cat_order) VALUES (1, 'My First Forum', 1);
INSERT INTO hf_category (id_cat, cat_name, cat_order) VALUES (2, 'My Second forum', 2);


INSERT INTO hf_connected (id_user, member_ip, connected, idle) VALUES (1, '127.0.0.1', 1276004461, 0);

INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire) VALUES (1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0, '', 0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire) VALUES (2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0, '', 0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire) VALUES (3, 'Light', 2, 'Soo light', 1, 0, 0, 0, '', 0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level, forum_type, forum_url, post_expire) VALUES (4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0, '', 0);


INSERT INTO hf_jacl2_group (id_aclgrp, name, grouptype, ownerlogin) VALUES (1, 'admins', 0, NULL);
INSERT INTO hf_jacl2_group (id_aclgrp, name, grouptype, ownerlogin) VALUES (2, 'users', 1, NULL);
INSERT INTO hf_jacl2_group (id_aclgrp, name, grouptype, ownerlogin) VALUES (3, 'moderators', 0, NULL);
INSERT INTO hf_jacl2_group (id_aclgrp, name, grouptype, ownerlogin) VALUES (4, 'admin', 2, 'admin');



INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.modify', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.user.modify', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.user.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.change.password', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.change.password', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.modify', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.modify', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.view', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.change.password', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.list', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.modify', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.ban', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.ban', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.cache', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.cache', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.cache.clear', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.category', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.category', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.category.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.category.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.category.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.config', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.config', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.config.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.contact', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.forum', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.forum.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.forum.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.forum.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.index', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.index', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member.ban', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member.ban', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member.create', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member.delete', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.member.edit', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.notify.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.notify.delete', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.notify.list', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.notify.list', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.post', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.post', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.rank.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.rank.create', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.rank.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.rank.delete', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.rank.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.rank.edit', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.search', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.server.info', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.serverinfo', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.themes', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.goto', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.goto', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.goto', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.list', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.forum.view', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.list', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.list', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.list', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.search', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.search', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.search', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.view', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.member.view', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.create', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.delete', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.delete', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.delete', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.delete', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.edit.own', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.list', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.notify', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.quote', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.reply', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.rss', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.posts.view', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.search', 0, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.search', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.search', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.search', 3, '');



INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('acl.group.create', 'jelix~acl2db.acl.group.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('acl.group.delete', 'jelix~acl2db.acl.group.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('acl.group.modify', 'jelix~acl2db.acl.group.modify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('acl.group.view', 'jelix~acl2db.acl.group.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('acl.user.modify', 'jelix~acl2db.acl.user.modify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('acl.user.view', 'jelix~acl2db.acl.user.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.user.change.password', 'jelix~auth.acl.user.change.password');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.user.modify', 'jelix~auth.acl.user.modify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.user.view', 'jelix~auth.acl.user.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.users.change.password', 'jelix~auth.acl.users.change.password');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.users.create', 'jelix~auth.acl.users.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.users.delete', 'jelix~auth.acl.users.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.users.list', 'jelix~auth.acl.users.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.users.modify', 'jelix~auth.acl.users.modify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('auth.users.view', 'jelix~auth.acl.users.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.category.create', 'havefnubb~acl2.admin.category.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.category.delete', 'havefnubb~acl2.admin.category.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.category.edit', 'havefnubb~acl2.admin.category.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.config.edit', 'havefnubb~acl2.admin.config.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.category', 'havefnubb~acl2.admin.category');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.forum.create', 'havefnubb~acl2.admin.forum.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.forum.delete', 'havefnubb~acl2.admin.forum.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.forum.edit', 'havefnubb~acl2.admin.forum.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.index', 'havefnubb~acl2.admin.index');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.member.ban', 'havefnubb~acl2.admin.member.ban');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.member.create', 'havefnubb~acl2.admin.member.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.member.delete', 'havefnubb~acl2.admin.member.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.member.edit', 'havefnubb~acl2.admin.member.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.notify.delete', 'havefnubb~acl2.admin.notify.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.notify.list', 'havefnubb~acl2.admin.notify.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.rank.create', 'havefnubb~acl2.admin.rank.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.rank.delete', 'havefnubb~acl2.admin.rank.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.rank.edit', 'havefnubb~acl2.admin.rank.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.server.info', 'havefnubb~acl2.admin.server.info');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.category.list', 'havefnubb~acl2.category.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.category.view', 'havefnubb~acl2.category.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.forum.goto', 'havefnubb~acl2.forum.goto');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.forum.list', 'havefnubb~acl2.forum.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.forum.view', 'havefnubb~acl2.forum.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.member.list', 'havefnubb~acl2.member.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.member.search', 'havefnubb~acl2.member.search');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.member.view', 'havefnubb~acl2.member.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.create', 'havefnubb~acl2.posts.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.delete', 'havefnubb~acl2.posts.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.edit', 'havefnubb~acl2.posts.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.list', 'havefnubb~acl2.posts.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.moderate', 'havefnubb~acl2.posts.moderate');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.notify', 'havefnubb~acl2.posts.notify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.quote', 'havefnubb~acl2.posts.quote');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.reply', 'havefnubb~acl2.posts.reply');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.view', 'havefnubb~acl2.posts.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.rss', 'havefnubb~acl2.posts.rss');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.search', 'havefnubb~acl2.search');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.posts.edit.own', 'havefnubb~acl2.posts.edit.own');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.cache.clear', 'havefnu~acl2.admin.cache.clear');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.search', 'havefnubb~acl2.admin.search');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.cache', 'havefnubb~acl2.admin.cache');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.config', 'havefnubb~acl2.admin.config');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.forum', 'havefnubb~acl2.admin.forum');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.member', 'havefnubb~acl2.admin.member');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.post', 'havefnubb~acl2.admin.post');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.ban', 'havefnubb~acl2.admin.ban');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.serverinfo', 'havefnubb~acl2.admin.serverinfo');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.contact', 'hfnucontact~acl2.admin.contact');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.themes', 'havefnubb~acl2.admin.themes');



INSERT INTO hf_jacl2_user_group (login, id_aclgrp) VALUES ('admin', 1);
INSERT INTO hf_jacl2_user_group (login, id_aclgrp) VALUES ('admin', 4);



INSERT INTO hf_member (id_user, member_login, member_password, member_email, member_nickname, member_status, member_keyactivate, member_request_date, member_website, member_firstname, member_birth, member_country, member_town, member_comment, member_avatar, member_last_connect, member_show_email, member_language, member_nb_msg, member_last_post, member_created, member_gravatar) VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.net', 'admin', 1, NULL, '2010-06-08 10:21:43', NULL, NULL, '1980-01-01', NULL, NULL, NULL, NULL, 1276004461, NULL, NULL, 0, 0, '2010-06-08 10:21:43', 0);

INSERT INTO hf_posts (id_post, id_user, id_forum, parent_id, status, subject, message, date_created, date_modified, viewed, poster_ip, censored_msg, read_by_mod) VALUES (1, 1, 1, 1, 'opened', 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)', 1275985285, 1275985285, 1, '127.0.0.1', NULL, 1);

INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (1, 'new member', 0);
INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (2, 'member', 40);
INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (3, 'active member', 100);


INSERT INTO hf_sc_tags (tag_id, tag_name, nbuse) VALUES (1, 'install', 1);

INSERT INTO hf_sc_tags_tagged (tt_id, tag_id, tt_scope_id, tt_subject_id) VALUES (1, 1, 'forumscope', 1);


INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'first', 1);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'life', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'new', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'start', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'and', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'remov', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'now', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'complet', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'instal', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'your', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'that', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'conclud', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'can', 4);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'post', 5);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'thi', 4);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'read', 2);
INSERT INTO hf_search_words (id, datasource, words, weight) VALUES ('1', '', 'you', 6);


ALTER TABLE ONLY hf_jacl2_group
    ADD CONSTRAINT hf_jacl2_group_pkey PRIMARY KEY (id_aclgrp);
ALTER TABLE ONLY hf_jacl2_rights
    ADD CONSTRAINT hf_jacl2_rights_pkey PRIMARY KEY (id_aclgrp, id_aclres, id_aclsbj);

ALTER TABLE ONLY hf_jacl2_subject
    ADD CONSTRAINT hf_jacl2_subject_pkey PRIMARY KEY (id_aclsbj);

ALTER TABLE ONLY hf_jmessenger
    ADD CONSTRAINT hf_jmessenger_pkey PRIMARY KEY (id);

CREATE INDEX child_level ON hf_forum USING btree (child_level);

CREATE INDEX date_read ON hf_read_forum USING btree (date_read);

CREATE INDEX forum_type ON hf_forum USING btree (forum_type);

CREATE INDEX id_cat_idx ON hf_forum USING btree (id_cat);

CREATE INDEX id_forum_idx ON hf_read_forum USING btree (id_forum);

CREATE INDEX id_forum_read_forum_idx ON hf_read_posts USING btree (id_forum);

CREATE INDEX id_post_notify_idx ON hf_notify USING btree (id_post);

CREATE INDEX id_post_read_post_idx ON hf_read_posts USING btree (id_post);

CREATE INDEX id_source_ix ON hf_rates USING btree (id_source);

CREATE INDEX id_use_notify_idx ON hf_notify USING btree (id_user);

CREATE INDEX id_user_post_idx ON hf_posts USING btree (id_user, id_forum, parent_id, status);

CREATE INDEX id_user_rates_idx ON hf_rates USING btree (id_user);

CREATE INDEX id_user_read_forum_idx ON hf_read_forum USING btree (id_user);

CREATE INDEX id_user_read_posts_idx ON hf_read_posts USING btree (id_user);

CREATE INDEX idx1_tt ON hf_sc_tags_tagged USING btree (tt_scope_id, tt_subject_id);

CREATE INDEX idx2_tt ON hf_sc_tags_tagged USING btree (tag_id);

CREATE INDEX login ON hf_jacl2_user_group USING btree (login, id_aclgrp);

CREATE INDEX parent_id ON hf_forum USING btree (parent_id);

CREATE INDEX source ON hf_rates USING btree (source);

CREATE INDEX words ON hf_search_words USING btree (words);
