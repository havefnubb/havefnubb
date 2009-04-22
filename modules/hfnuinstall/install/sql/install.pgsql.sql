SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;


CREATE SEQUENCE ban_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


CREATE TABLE hf_bans (
    ban_id integer DEFAULT nextval('ban_id_seq'::regclass) NOT NULL,
    ban_username character varying(200) DEFAULT NULL::character varying,
    ban_ip character varying(255) DEFAULT NULL::character varying,
    ban_email character varying(50) DEFAULT NULL::character varying,
    ban_message character varying(255) DEFAULT NULL::character varying,
    ban_expire integer
);



CREATE SEQUENCE id_cat_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


CREATE TABLE hf_category (
    id_cat integer DEFAULT nextval('id_cat_seq'::regclass) NOT NULL,
    cat_name character varying(255) NOT NULL,
    cat_order integer NOT NULL
);



CREATE TABLE hf_connected (
    id_user integer DEFAULT 1 NOT NULL,
    member_ip character varying(200) DEFAULT ''::character varying NOT NULL,
    connected integer DEFAULT 0 NOT NULL,
    idle numeric(1,0) DEFAULT 0 NOT NULL
);



CREATE TABLE hf_forum (
    id_forum integer NOT NULL,
    forum_name character varying(255),
    id_cat integer,
    forum_desc character varying(255),
    forum_order numeric(4,0),
    parent_id integer,
    child_level numeric(4,0)
);



CREATE SEQUENCE hf_forum_id_forum_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



ALTER SEQUENCE hf_forum_id_forum_seq OWNED BY hf_forum.id_forum;



CREATE SEQUENCE id_aclgrp_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


CREATE TABLE hf_jacl2_group (
    id_aclgrp integer DEFAULT nextval('id_aclgrp_seq'::regclass) NOT NULL,
    name character varying(150) NOT NULL,
    grouptype numeric(4,0) NOT NULL,
    ownerlogin character varying(50)
);



CREATE TABLE hf_jacl2_rights (
    id_aclsbj character varying(100) DEFAULT ''::character varying NOT NULL,
    id_aclgrp integer DEFAULT 0 NOT NULL,
    id_aclres character varying(100) DEFAULT ''::character varying NOT NULL
);


CREATE TABLE hf_jacl2_user_group (
    login character varying(50) DEFAULT ''::character varying NOT NULL,
    id_aclgrp integer DEFAULT 0 NOT NULL
);


CREATE SEQUENCE id_messsenger_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


CREATE TABLE hf_jmessenger (
    id integer DEFAULT nextval('id_messsenger_seq'::regclass) NOT NULL,
    id_from integer DEFAULT 0 NOT NULL,
    id_for integer DEFAULT 0 NOT NULL,
    date timestamp without time zone,
    title character varying(255) NOT NULL,
    content text NOT NULL,
    isseen numeric(4,0) NOT NULL,
    isarchived numeric(4,0) NOT NULL,
    isreceived numeric(4,0) NOT NULL,
    issend numeric(4,0) NOT NULL
);


CREATE SEQUENCE id_user_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



CREATE TABLE hf_member (
    id_user integer DEFAULT nextval('id_user_seq'::regclass) NOT NULL,
    member_login character varying(50) NOT NULL,
    member_password character varying(50) NOT NULL,
    member_email character varying(255) NOT NULL,
    member_nickname character varying(50) DEFAULT NULL::character varying,
    member_status character varying(12) DEFAULT 'opened'::character varying NOT NULL,
    member_keyactivate character varying(10) DEFAULT NULL::character varying,
    member_request_date timestamp without time zone,
    member_website character varying(255) DEFAULT NULL::character varying,
    member_firstname character varying(40) DEFAULT NULL::character varying,
    member_birth date DEFAULT '1980-01-01'::date NOT NULL,
    member_country character varying(100) DEFAULT NULL::character varying,
    member_town character varying(100) DEFAULT NULL::character varying,
    member_comment character varying(255) DEFAULT NULL::character varying,
    member_avatar character varying(255) DEFAULT NULL::character varying,
    member_xfire character varying(80) DEFAULT NULL::character varying,
    member_icq character varying(80) DEFAULT NULL::character varying,
    member_hotmail character varying(255) DEFAULT NULL::character varying,
    member_yim character varying(255) DEFAULT NULL::character varying,
    member_aol character varying(255) DEFAULT NULL::character varying,
    member_gtalk character varying(255) DEFAULT NULL::character varying,
    member_jabber character varying(255) DEFAULT NULL::character varying,
    member_proc character varying(40) DEFAULT NULL::character varying,
    member_mb character varying(40) DEFAULT NULL::character varying,
    member_card character varying(40) DEFAULT NULL::character varying,
    member_ram character varying(40) DEFAULT NULL::character varying,
    member_display character varying(40) DEFAULT NULL::character varying,
    member_screen character varying(40) DEFAULT NULL::character varying,
    member_mouse character varying(40) DEFAULT NULL::character varying,
    member_keyb character varying(40) DEFAULT NULL::character varying,
    member_os character varying(40) DEFAULT NULL::character varying,
    member_connection character varying(40) DEFAULT NULL::character varying,
    member_last_connect integer,
    member_show_email character varying(1) DEFAULT 'N'::character varying,
    member_language character varying(40) DEFAULT 'fr_FR'::character varying,
    member_nb_msg integer DEFAULT 0,
    member_last_post integer
);



CREATE SEQUENCE id_notify_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



CREATE TABLE hf_notify (
    id_notify integer DEFAULT nextval('id_notify_seq'::regclass) NOT NULL,
    id_user integer NOT NULL,
    id_post integer NOT NULL,
    id_forum integer NOT NULL,
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    date_created integer NOT NULL,
    date_modified integer NOT NULL
);

CREATE SEQUENCE id_post_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE TABLE hf_posts (
    id_post integer DEFAULT nextval('id_post_seq'::regclass) NOT NULL,
    id_user integer NOT NULL,
    id_forum integer NOT NULL,
    parent_id integer NOT NULL,
    status numeric(2,0) DEFAULT (1)::numeric NOT NULL,
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    date_created integer DEFAULT date_part('epoch'::text, now()),
    date_modified integer DEFAULT date_part('epoch'::text, now()),
    viewed integer NOT NULL,
    poster_ip character varying(15) NOT NULL
);


CREATE SEQUENCE id_rank_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


CREATE TABLE hf_rank (
    id_rank integer DEFAULT nextval('id_rank_seq'::regclass) NOT NULL,
    rank_name character varying(40) NOT NULL,
    rank_limit numeric(9,0) NOT NULL
);


CREATE SEQUENCE tag_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



CREATE TABLE hf_sc_tags (
    tag_id integer DEFAULT nextval('tag_id_seq'::regclass) NOT NULL,
    tag_name character varying(50) NOT NULL,
    nbuse integer DEFAULT 0
);


CREATE SEQUENCE tt_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

CREATE TABLE hf_sc_tags_tagged (
    tt_id integer DEFAULT nextval('tt_id_seq'::regclass) NOT NULL,
    tag_id integer NOT NULL,
    tt_scope_id character varying(50) NOT NULL,
    tt_subject_id integer NOT NULL
);



CREATE TABLE hf_search_words (
    id integer NOT NULL,
    words character varying(255) NOT NULL,
    weight numeric(4,0) NOT NULL
);



CREATE TABLE hf_subscription (
    id_user integer DEFAULT 0 NOT NULL,
    id_forum integer DEFAULT 0 NOT NULL,
    id_post integer DEFAULT 0 NOT NULL
);



CREATE SEQUENCE id_search_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



ALTER TABLE hf_forum ALTER COLUMN id_forum SET DEFAULT nextval('hf_forum_id_forum_seq'::regclass);


ALTER TABLE ONLY hf_bans
    ADD CONSTRAINT hf_bans_pkey PRIMARY KEY (ban_id);

ALTER TABLE ONLY hf_category
    ADD CONSTRAINT hf_category_pkey PRIMARY KEY (id_cat);


ALTER TABLE ONLY hf_forum
    ADD CONSTRAINT hf_forum_pkey PRIMARY KEY (id_forum);

ALTER TABLE ONLY hf_jacl2_group
    ADD CONSTRAINT hf_jacl2_group_pkey PRIMARY KEY (id_aclgrp);


ALTER TABLE ONLY hf_jacl2_rights
    ADD CONSTRAINT hf_jacl2_rights_pkey PRIMARY KEY (id_aclsbj, id_aclgrp, id_aclres);


ALTER TABLE ONLY hf_jmessenger
    ADD CONSTRAINT hf_jmessenger_pkey PRIMARY KEY (id);

ALTER TABLE ONLY hf_member
    ADD CONSTRAINT hf_member_id_user_key UNIQUE (id_user);

ALTER TABLE ONLY hf_member
    ADD CONSTRAINT hf_member_pkey PRIMARY KEY (member_login);

    ALTER TABLE ONLY hf_notify
    ADD CONSTRAINT hf_notify_pkey PRIMARY KEY (id_notify);

ALTER TABLE ONLY hf_posts
    ADD CONSTRAINT hf_posts_pkey PRIMARY KEY (id_post);

ALTER TABLE ONLY hf_rank
    ADD CONSTRAINT hf_rank_pkey PRIMARY KEY (id_rank);


ALTER TABLE ONLY hf_sc_tags
    ADD CONSTRAINT hf_sc_tags_pkey PRIMARY KEY (tag_id);



ALTER TABLE ONLY hf_sc_tags
    ADD CONSTRAINT hf_sc_tags_tag_name_key UNIQUE (tag_name);



ALTER TABLE ONLY hf_sc_tags_tagged
    ADD CONSTRAINT hf_sc_tags_tagged_pkey PRIMARY KEY (tt_id);



ALTER TABLE ONLY hf_subscription
    ADD CONSTRAINT hf_subscription_pkey PRIMARY KEY (id_user, id_forum, id_post);


CREATE INDEX id ON hf_search_words USING btree (id);

CREATE INDEX id_cat_idx ON hf_forum USING btree (id_cat);

CREATE INDEX id_post_ix_notif ON hf_notify USING btree (id_post);

CREATE INDEX id_user_ix ON hf_posts USING btree (id_user, id_forum, parent_id, status);

CREATE INDEX id_user_ix_notif ON hf_notify USING btree (id_user);

CREATE INDEX idx1_tt ON hf_sc_tags_tagged USING btree (tt_scope_id, tt_subject_id);

CREATE INDEX login_idx ON hf_jacl2_user_group USING btree (login, id_aclgrp);

CREATE UNIQUE INDEX parent_id_idx ON hf_forum USING btree (parent_id);

CREATE INDEX tag_id_ix ON hf_sc_tags_tagged USING btree (tag_id);

CREATE INDEX words ON hf_search_words USING btree (words);


INSERT INTO hf_category (id_cat, cat_name, cat_order) VALUES
(1, 'My First Forum', 1),
(2, 'My Second forum', 2);

INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level) VALUES
(1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0),
(2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0),
(3, 'Light', 2, 'Soo light', 1, 0, 0),
(4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1);

INSERT INTO hf_jacl2_group (id_aclgrp, name, grouptype, ownerlogin) VALUES
(1, 'admins', 0, NULL),
(2, 'users', 1, NULL),
(3, 'moderators', 0, NULL);
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('acl.group.create', 1, ''),
('acl.group.delete', 1, ''),
('acl.group.modify', 1, ''),
('acl.group.view', 1, ''),
('acl.user.modify', 1, ''),
('acl.user.view', 1, ''),
('auth.user.change.password', 1, ''),
('auth.user.change.password', 2, ''),
('auth.user.modify', 1, ''),
('auth.user.modify', 2, ''),
('auth.user.view', 1, ''),
('auth.user.view', 2, ''),
('auth.users.change.password', 1, ''),
('auth.users.create', 1, ''),
('auth.users.delete', 1, ''),
('auth.users.list', 1, ''),
('auth.users.modify', 1, ''),
('auth.users.view', 1, ''),
('hfnu.admin.serverinfo',1,''),
('hfnu.admin.ban', 1, ''),
('hfnu.admin.ban', 3, ''),
('hfnu.admin.cache', 1, ''),
('hfnu.admin.cache', 3, ''),
('hfnu.admin.cache.clear', 1, ''),
('hfnu.admin.category', 1, ''),
('hfnu.admin.category', 3, ''),
('hfnu.admin.category.create', 1, ''),
('hfnu.admin.category.delete', 1, ''),
('hfnu.admin.category.edit', 1, ''),
('hfnu.admin.config', 1, ''),
('hfnu.admin.config', 3, ''),
('hfnu.admin.config.edit', 1, ''),
('hfnu.admin.forum', 1, ''),
('hfnu.admin.forum.create', 1, ''),
('hfnu.admin.forum.delete', 1, ''),
('hfnu.admin.forum.edit', 1, ''),
('hfnu.admin.index', 1, ''),
('hfnu.admin.index', 3, ''),
('hfnu.admin.member', 1, ''),
('hfnu.admin.member', 3, ''),
('hfnu.admin.member.ban', 1, ''),
('hfnu.admin.member.ban', 3, ''),
('hfnu.admin.member.create', 1, ''),
('hfnu.admin.member.create', 3, ''),
('hfnu.admin.member.delete', 1, ''),
('hfnu.admin.member.delete', 3, ''),
('hfnu.admin.member.edit', 1, ''),
('hfnu.admin.member.edit', 3, ''),
('hfnu.admin.notify.delete', 1, ''),
('hfnu.admin.notify.delete', 3, ''),
('hfnu.admin.notify.list', 1, ''),
('hfnu.admin.notify.list', 3, ''),
('hfnu.admin.post', 1, ''),
('hfnu.admin.post', 3, ''),
('hfnu.admin.rank.create', 1, ''),
('hfnu.admin.rank.create', 3, ''),
('hfnu.admin.rank.delete', 1, ''),
('hfnu.admin.rank.delete', 3, ''),
('hfnu.admin.rank.edit', 1, ''),
('hfnu.admin.rank.edit', 3, ''),
('hfnu.admin.search', 1, ''),
('hfnu.admin.server.info', 1, ''),
('hfnu.forum.goto', 1, ''),
('hfnu.forum.goto', 2, ''),
('hfnu.forum.goto', 3, ''),
('hfnu.forum.list', 0, 'forum1'),
('hfnu.forum.list', 0, 'forum2'),
('hfnu.forum.list', 0, 'forum3'),
('hfnu.forum.list', 0, 'forum4'),
('hfnu.forum.list', 1, 'forum1'),
('hfnu.forum.list', 1, 'forum2'),
('hfnu.forum.list', 1, 'forum3'),
('hfnu.forum.list', 1, 'forum4'),
('hfnu.forum.list', 2, 'forum1'),
('hfnu.forum.list', 2, 'forum2'),
('hfnu.forum.list', 2, 'forum3'),
('hfnu.forum.list', 2, 'forum4'),
('hfnu.forum.list', 3, 'forum1'),
('hfnu.forum.list', 3, 'forum2'),
('hfnu.forum.list', 3, 'forum3'),
('hfnu.forum.list', 3, 'forum4'),
('hfnu.forum.view', 0, 'forum1'),
('hfnu.forum.view', 0, 'forum2'),
('hfnu.forum.view', 0, 'forum3'),
('hfnu.forum.view', 0, 'forum4'),
('hfnu.forum.view', 1, 'forum1'),
('hfnu.forum.view', 1, 'forum2'),
('hfnu.forum.view', 1, 'forum3'),
('hfnu.forum.view', 1, 'forum4'),
('hfnu.forum.view', 2, 'forum1'),
('hfnu.forum.view', 2, 'forum2'),
('hfnu.forum.view', 2, 'forum3'),
('hfnu.forum.view', 2, 'forum4'),
('hfnu.forum.view', 3, 'forum1'),
('hfnu.forum.view', 3, 'forum2'),
('hfnu.forum.view', 3, 'forum3'),
('hfnu.forum.view', 3, 'forum4'),
('hfnu.member.list', 1, ''),
('hfnu.member.list', 2, ''),
('hfnu.member.list', 3, ''),
('hfnu.member.search', 1, ''),
('hfnu.member.search', 2, ''),
('hfnu.member.search', 3, ''),
('hfnu.member.view', 1, ''),
('hfnu.member.view', 2, ''),
('hfnu.member.view', 3, ''),
('hfnu.posts.create', 1, 'forum1'),
('hfnu.posts.create', 1, 'forum2'),
('hfnu.posts.create', 1, 'forum3'),
('hfnu.posts.create', 1, 'forum4'),
('hfnu.posts.create', 2, 'forum1'),
('hfnu.posts.create', 2, 'forum2'),
('hfnu.posts.create', 2, 'forum3'),
('hfnu.posts.create', 2, 'forum4'),
('hfnu.posts.create', 3, 'forum1'),
('hfnu.posts.create', 3, 'forum2'),
('hfnu.posts.create', 3, 'forum3'),
('hfnu.posts.create', 3, 'forum4'),
('hfnu.posts.delete', 1, 'forum1'),
('hfnu.posts.delete', 1, 'forum2'),
('hfnu.posts.delete', 1, 'forum3'),
('hfnu.posts.delete', 1, 'forum4'),
('hfnu.posts.edit', 1, 'forum1'),
('hfnu.posts.edit', 1, 'forum2'),
('hfnu.posts.edit', 1, 'forum3'),
('hfnu.posts.edit', 1, 'forum4'),
('hfnu.posts.edit', 3, 'forum1'),
('hfnu.posts.edit', 3, 'forum2'),
('hfnu.posts.edit', 3, 'forum3'),
('hfnu.posts.edit', 3, 'forum4'),
('hfnu.posts.edit.own', 1, 'forum1'),
('hfnu.posts.edit.own', 1, 'forum2'),
('hfnu.posts.edit.own', 1, 'forum3'),
('hfnu.posts.edit.own', 1, 'forum4'),
('hfnu.posts.edit.own', 2, 'forum1'),
('hfnu.posts.edit.own', 2, 'forum2'),
('hfnu.posts.edit.own', 2, 'forum3'),
('hfnu.posts.edit.own', 2, 'forum4'),
('hfnu.posts.edit.own', 3, 'forum1'),
('hfnu.posts.edit.own', 3, 'forum2'),
('hfnu.posts.edit.own', 3, 'forum3'),
('hfnu.posts.edit.own', 3, 'forum4'),
('hfnu.posts.list', 0, 'forum1'),
('hfnu.posts.list', 0, 'forum2'),
('hfnu.posts.list', 0, 'forum3'),
('hfnu.posts.list', 0, 'forum4'),
('hfnu.posts.list', 1, 'forum1'),
('hfnu.posts.list', 1, 'forum2'),
('hfnu.posts.list', 1, 'forum3'),
('hfnu.posts.list', 1, 'forum4'),
('hfnu.posts.list', 2, 'forum1'),
('hfnu.posts.list', 2, 'forum2'),
('hfnu.posts.list', 2, 'forum3'),
('hfnu.posts.list', 2, 'forum4'),
('hfnu.posts.list', 3, 'forum1'),
('hfnu.posts.list', 3, 'forum2'),
('hfnu.posts.list', 3, 'forum3'),
('hfnu.posts.list', 3, 'forum4'),
('hfnu.posts.notify', 1, 'forum1'),
('hfnu.posts.notify', 1, 'forum2'),
('hfnu.posts.notify', 1, 'forum3'),
('hfnu.posts.notify', 1, 'forum4'),
('hfnu.posts.notify', 2, 'forum1'),
('hfnu.posts.notify', 2, 'forum2'),
('hfnu.posts.notify', 2, 'forum3'),
('hfnu.posts.notify', 2, 'forum4'),
('hfnu.posts.notify', 3, 'forum1'),
('hfnu.posts.notify', 3, 'forum2'),
('hfnu.posts.notify', 3, 'forum3'),
('hfnu.posts.notify', 3, 'forum4'),
('hfnu.posts.quote', 1, 'forum1'),
('hfnu.posts.quote', 1, 'forum2'),
('hfnu.posts.quote', 1, 'forum3'),
('hfnu.posts.quote', 1, 'forum4'),
('hfnu.posts.quote', 2, 'forum1'),
('hfnu.posts.quote', 2, 'forum2'),
('hfnu.posts.quote', 2, 'forum3'),
('hfnu.posts.quote', 2, 'forum4'),
('hfnu.posts.quote', 3, 'forum1'),
('hfnu.posts.quote', 3, 'forum2'),
('hfnu.posts.quote', 3, 'forum3'),
('hfnu.posts.quote', 3, 'forum4'),
('hfnu.posts.reply', 1, 'forum1'),
('hfnu.posts.reply', 1, 'forum2'),
('hfnu.posts.reply', 1, 'forum3'),
('hfnu.posts.reply', 1, 'forum4'),
('hfnu.posts.reply', 2, 'forum1'),
('hfnu.posts.reply', 2, 'forum2'),
('hfnu.posts.reply', 2, 'forum3'),
('hfnu.posts.reply', 2, 'forum4'),
('hfnu.posts.reply', 3, 'forum1'),
('hfnu.posts.reply', 3, 'forum2'),
('hfnu.posts.reply', 3, 'forum3'),
('hfnu.posts.reply', 3, 'forum4'),
('hfnu.posts.view', 0, 'forum1'),
('hfnu.posts.view', 0, 'forum2'),
('hfnu.posts.view', 0, 'forum3'),
('hfnu.posts.view', 0, 'forum4'),
('hfnu.posts.view', 1, 'forum1'),
('hfnu.posts.view', 1, 'forum2'),
('hfnu.posts.view', 1, 'forum3'),
('hfnu.posts.view', 1, 'forum4'),
('hfnu.posts.view', 2, 'forum1'),
('hfnu.posts.view', 2, 'forum2'),
('hfnu.posts.view', 2, 'forum3'),
('hfnu.posts.view', 2, 'forum4'),
('hfnu.posts.view', 3, 'forum1'),
('hfnu.posts.view', 3, 'forum2'),
('hfnu.posts.view', 3, 'forum3'),
('hfnu.posts.view', 3, 'forum4'),

('hfnu.posts.rss', 0, 'forum1'),
('hfnu.posts.rss', 0, 'forum2'),
('hfnu.posts.rss', 0, 'forum3'),
('hfnu.posts.rss', 0, 'forum4'),
('hfnu.posts.rss', 1, 'forum1'),
('hfnu.posts.rss', 1, 'forum2'),
('hfnu.posts.rss', 1, 'forum3'),
('hfnu.posts.rss', 1, 'forum4'),
('hfnu.posts.rss', 2, 'forum1'),
('hfnu.posts.rss', 2, 'forum2'),
('hfnu.posts.rss', 2, 'forum3'),
('hfnu.posts.rss', 2, 'forum4'),
('hfnu.posts.rss', 3, 'forum1'),
('hfnu.posts.rss', 3, 'forum2'),
('hfnu.posts.rss', 3, 'forum3'),
('hfnu.posts.rss', 3, 'forum4'),

('hfnu.search', 1, ''),
('hfnu.search', 2, ''),
('hfnu.search', 3, '');

INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('acl.group.create', 'jelix~acl2db.acl.group.create'),
('acl.group.delete', 'jelix~acl2db.acl.group.delete'),
('acl.group.modify', 'jelix~acl2db.acl.group.modify'),
('acl.group.view', 'jelix~acl2db.acl.group.view'),
('acl.user.modify', 'jelix~acl2db.acl.user.modify'),
('acl.user.view', 'jelix~acl2db.acl.user.view'),
('auth.user.change.password', 'jelix~auth.acl.user.change.password'),
('auth.user.modify', 'jelix~auth.acl.user.modify'),
('auth.user.view', 'jelix~auth.acl.user.view'),
('auth.users.change.password', 'jelix~auth.acl.users.change.password'),
('auth.users.create', 'jelix~auth.acl.users.create'),
('auth.users.delete', 'jelix~auth.acl.users.delete'),
('auth.users.list', 'jelix~auth.acl.users.list'),
('auth.users.modify', 'jelix~auth.acl.users.modify'),
('auth.users.view', 'jelix~auth.acl.users.view'),
('hfnu.admin.category.create', 'havefnubb~acl2.admin.category.create'),
('hfnu.admin.category.delete', 'havefnubb~acl2.admin.category.delete'),
('hfnu.admin.category.edit', 'havefnubb~acl2.admin.category.edit'),
('hfnu.admin.config.edit', 'havefnubb~acl2.admin.config.edit'),
('hfnu.admin.category', 'havefnubb~acl2.admin.category'),
('hfnu.admin.forum.create', 'havefnubb~acl2.admin.forum.create'),
('hfnu.admin.forum.delete', 'havefnubb~acl2.admin.forum.delete'),
('hfnu.admin.forum.edit', 'havefnubb~acl2.admin.forum.edit'),
('hfnu.admin.index', 'havefnubb~acl2.admin.index'),
('hfnu.admin.member.ban', 'havefnubb~acl2.admin.member.ban'),
('hfnu.admin.member.create', 'havefnubb~acl2.admin.member.create'),
('hfnu.admin.member.delete', 'havefnubb~acl2.admin.member.delete'),
('hfnu.admin.member.edit', 'havefnubb~acl2.admin.member.edit'),
('hfnu.admin.notify.delete', 'havefnubb~acl2.admin.notify.delete'),
('hfnu.admin.notify.list', 'havefnubb~acl2.admin.notify.list'),
('hfnu.admin.rank.create', 'havefnubb~acl2.admin.rank.create'),
('hfnu.admin.rank.delete', 'havefnubb~acl2.admin.rank.delete'),
('hfnu.admin.rank.edit', 'havefnubb~acl2.admin.rank.edit'),
('hfnu.admin.server.info', 'havefnubb~acl2.admin.server.info'),
('hfnu.category.list', 'havefnubb~acl2.category.list'),
('hfnu.category.view', 'havefnubb~acl2.category.view'),
('hfnu.forum.goto', 'havefnubb~acl2.forum.goto'),
('hfnu.forum.list', 'havefnubb~acl2.forum.list'),
('hfnu.forum.view', 'havefnubb~acl2.forum.view'),
('hfnu.member.list', 'havefnubb~acl2.member.list'),
('hfnu.member.search', 'havefnubb~acl2.member.search'),
('hfnu.member.view', 'havefnubb~acl2.member.view'),
('hfnu.posts.create', 'havefnubb~acl2.posts.create'),
('hfnu.posts.delete', 'havefnubb~acl2.posts.delete'),
('hfnu.posts.edit', 'havefnubb~acl2.posts.edit'),
('hfnu.posts.list', 'havefnubb~acl2.posts.list'),
('hfnu.posts.moderate', 'havefnubb~acl2.posts.moderate'),
('hfnu.posts.notify', 'havefnubb~acl2.posts.notify'),
('hfnu.posts.quote', 'havefnubb~acl2.posts.quote'),
('hfnu.posts.reply', 'havefnubb~acl2.posts.reply'),
('hfnu.posts.view', 'havefnubb~acl2.posts.view'),
('hfnu.posts.rss', 'havefnubb~acl2.posts.rss'),
('hfnu.search', 'havefnubb~acl2.search'),
('hfnu.posts.edit.own', 'havefnubb~acl2.posts.edit.own'),
('hfnu.admin.cache.clear', 'havefnu~acl2.admin.cache.clear'),
('hfnu.admin.search', 'havefnubb~acl2.admin.search'),
('hfnu.admin.cache', 'havefnubb~acl2.admin.cache'),
('hfnu.admin.config', 'havefnubb~acl2.admin.config'),
('hfnu.admin.forum', 'havefnubb~acl2.admin.forum'),
('hfnu.admin.member', 'havefnubb~acl2.admin.member'),
('hfnu.admin.post', 'havefnubb~acl2.admin.post'),
('hfnu.admin.ban', 'havefnubb~acl2.admin.ban'),
('hfnu.admin.serverinfo', 'havefnubb~acl2.admin.serverinfo');

INSERT INTO hf_posts (id_post, id_user, id_forum, parent_id, status, subject, message, date_created, date_modified, viewed, poster_ip) VALUES
(1, 1, 1, 1, 1, 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)', date_part('epoch'::text, now()), date_part('epoch'::text, now()), 1, '127.0.0.1');

INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES
(1, 'new member', 0),
(2, 'member', 40),
(3, 'active member', 100);

INSERT INTO hf_sc_tags (tag_id, tag_name, nbuse) VALUES
(1, 'install', 1);

INSERT INTO hf_sc_tags_tagged (tt_id, tag_id, tt_scope_id, tt_subject_id) VALUES
(1, 1, 'forumscope', 1);


INSERT INTO hf_search_words (id, words, weight) VALUES
(1, 'first', 1),
(1, 'life', 2),
(1, 'new', 2),
(1, 'start', 2),
(1, 'and', 2),
(1, 'remov', 2),
(1, 'now', 2),
(1, 'complet', 2),
(1, 'instal', 2),
(1, 'your', 2),
(1, 'that', 2),
(1, 'conclud', 2),
(1, 'can', 4),
(1, 'post', 5),
(1, 'thi', 4),
(1, 'read', 2),
(1, 'you', 6);
