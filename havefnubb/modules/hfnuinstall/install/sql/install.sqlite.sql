CREATE TABLE hf_category (
  id_cat INTEGER NOT NULL PRIMARY KEY,
  cat_name varchar(255) NOT NULL,
  cat_order int(4) NOT NULL
);

INSERT INTO hf_category (id_cat, cat_name, cat_order) VALUES (1, 'My First Forum', 1);
INSERT INTO hf_category (id_cat, cat_name, cat_order) VALUES (2, 'My Second forum', 2);

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

INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0,'',0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0,'',0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (3, 'Light', 2, 'Soo light', 1, 0, 0, 0,'',0);
INSERT INTO hf_forum (id_forum, forum_name, id_cat, forum_desc, forum_order, parent_id, child_level,forum_type,forum_url,post_expire) VALUES (4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0,'',0);

CREATE TABLE hf_jacl2_group (
  id_aclgrp INTEGER NOT NULL PRIMARY KEY,
  name varchar(150) NOT NULL DEFAULT '',
  grouptype tinyint(4) NOT NULL DEFAULT '0',
  ownerlogin varchar(50) DEFAULT NULL
) ;


INSERT INTO hf_jacl2_group (id_aclgrp, name, grouptype, ownerlogin) VALUES
(1, 'admins', 0, NULL);
INSERT INTO hf_jacl2_group (id_aclgrp, name, grouptype, ownerlogin) VALUES
(2, 'users', 1, NULL);
INSERT INTO hf_jacl2_group (id_aclgrp, name, grouptype, ownerlogin) VALUES
(3, 'moderators', 0, NULL);



CREATE TABLE hf_jacl2_rights (
  id_aclsbj varchar(100) NOT NULL DEFAULT '',
  id_aclgrp int(11) NOT NULL DEFAULT '0',
  id_aclres varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (id_aclsbj,id_aclgrp,id_aclres)
) ;


INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('acl.group.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('acl.group.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('acl.group.modify', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('acl.group.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('acl.user.modify', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('acl.user.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.user.change.password', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.user.change.password', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.user.modify', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.user.modify', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.user.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.user.view', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.users.change.password', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.users.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.users.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.users.list', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.users.modify', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('auth.users.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.serverinfo',1,'');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.ban', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.ban', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.cache', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.cache', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.cache.clear', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.category', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.category', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.category.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.category.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.category.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.config', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.config', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.config.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.forum', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.forum.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.forum.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.forum.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.index', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.index', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member.ban', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member.ban', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member.create', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member.delete', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.member.edit', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.notify.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.notify.delete', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.notify.list', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.notify.list', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.post', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.post', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.rank.create', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.rank.create', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.rank.delete', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.rank.delete', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.rank.edit', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.rank.edit', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.search', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.server.info', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.goto', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.goto', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.goto', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.list', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.forum.view', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.list', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.list', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.list', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.search', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.search', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.search', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.view', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.view', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.member.view', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.create', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.delete', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.delete', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.delete', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.delete', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.edit.own', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.list', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.notify', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.quote', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.reply', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.view', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 0, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 0, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 0, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 0, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 1, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 1, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 1, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 1, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 2, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 2, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 2, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 2, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 3, 'forum1');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 3, 'forum2');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 3, 'forum3');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.posts.rss', 3, 'forum4');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.search', 0, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.search', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.search', 2, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.search', 3, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.contact', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.admin.themes', 1, '');


CREATE TABLE hf_jacl2_subject (
  id_aclsbj varchar(100) NOT NULL DEFAULT '',
  label_key varchar(100) DEFAULT NULL,
  PRIMARY KEY (id_aclsbj)
) ;

INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('acl.group.create', 'jelix~acl2db.acl.group.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('acl.group.delete', 'jelix~acl2db.acl.group.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('acl.group.modify', 'jelix~acl2db.acl.group.modify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('acl.group.view', 'jelix~acl2db.acl.group.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('acl.user.modify', 'jelix~acl2db.acl.user.modify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('acl.user.view', 'jelix~acl2db.acl.user.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.user.change.password', 'jelix~auth.acl.user.change.password');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.user.modify', 'jelix~auth.acl.user.modify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.user.view', 'jelix~auth.acl.user.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.users.change.password', 'jelix~auth.acl.users.change.password');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.users.create', 'jelix~auth.acl.users.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.users.delete', 'jelix~auth.acl.users.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.users.list', 'jelix~auth.acl.users.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.users.modify', 'jelix~auth.acl.users.modify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('auth.users.view', 'jelix~auth.acl.users.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.category.create', 'havefnubb~acl2.admin.category.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.category.delete', 'havefnubb~acl2.admin.category.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.category.edit', 'havefnubb~acl2.admin.category.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.config.edit', 'havefnubb~acl2.admin.config.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.category', 'havefnubb~acl2.admin.category');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.forum.create', 'havefnubb~acl2.admin.forum.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.forum.delete', 'havefnubb~acl2.admin.forum.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.forum.edit', 'havefnubb~acl2.admin.forum.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.index', 'havefnubb~acl2.admin.index');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.member.ban', 'havefnubb~acl2.admin.member.ban');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.member.create', 'havefnubb~acl2.admin.member.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.member.delete', 'havefnubb~acl2.admin.member.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.member.edit', 'havefnubb~acl2.admin.member.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.notify.delete', 'havefnubb~acl2.admin.notify.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.notify.list', 'havefnubb~acl2.admin.notify.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.rank.create', 'havefnubb~acl2.admin.rank.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.rank.delete', 'havefnubb~acl2.admin.rank.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.rank.edit', 'havefnubb~acl2.admin.rank.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.server.info', 'havefnubb~acl2.admin.server.info');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.category.list', 'havefnubb~acl2.category.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.category.view', 'havefnubb~acl2.category.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.forum.goto', 'havefnubb~acl2.forum.goto');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.forum.list', 'havefnubb~acl2.forum.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.forum.view', 'havefnubb~acl2.forum.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.member.list', 'havefnubb~acl2.member.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.member.search', 'havefnubb~acl2.member.search');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.member.view', 'havefnubb~acl2.member.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.create', 'havefnubb~acl2.posts.create');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.delete', 'havefnubb~acl2.posts.delete');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.edit', 'havefnubb~acl2.posts.edit');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.list', 'havefnubb~acl2.posts.list');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.moderate', 'havefnubb~acl2.posts.moderate');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.notify', 'havefnubb~acl2.posts.notify');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.quote', 'havefnubb~acl2.posts.quote');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.reply', 'havefnubb~acl2.posts.reply');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.view', 'havefnubb~acl2.posts.view');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.rss', 'havefnubb~acl2.posts.rss');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.search', 'havefnubb~acl2.search');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.posts.edit.own', 'havefnubb~acl2.posts.edit.own');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.cache.clear', 'havefnu~acl2.admin.cache.clear');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.search', 'havefnubb~acl2.admin.search');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.cache', 'havefnubb~acl2.admin.cache');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.config', 'havefnubb~acl2.admin.config');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.forum', 'havefnubb~acl2.admin.forum');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.member', 'havefnubb~acl2.admin.member');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.post', 'havefnubb~acl2.admin.post');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.ban', 'havefnubb~acl2.admin.ban');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.serverinfo', 'havefnubb~acl2.admin.serverinfo');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.contact', 'hfnucontact~acl2.admin.contact');
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.admin.themes', 'havefnubb~acl2.admin.themes');



CREATE TABLE hf_jacl2_user_group (
  login varchar(50) NOT NULL DEFAULT '',
  id_aclgrp int(11) NOT NULL DEFAULT '0'
) ;
CREATE UNIQUE INDEX hf_jacl2_user_group_login ON hf_jacl2_user_group (login);



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


INSERT INTO hf_posts (id_post, id_user, id_forum, parent_id, status, subject, message, date_created, date_modified, viewed, poster_ip, censored_msg,read_by_mod ) VALUES
(1, 1, 1, 1, 3, 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)',
strftime('%s','now') ,strftime('%s','now') , 1, '127.0.0.1',NULL,1);



CREATE TABLE hf_rank (
  id_rank INTEGER NOT NULL PRIMARY KEY  ,
  rank_name varchar(40) NOT NULL,
  rank_limit int(9) NOT NULL
) ;


INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (1, 'new member', 0);
INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (2, 'member', 40);
INSERT INTO hf_rank (id_rank, rank_name, rank_limit) VALUES (3, 'active member', 100);


CREATE TABLE hf_sc_tags (
  tag_id int(10) NOT NULL PRIMARY KEY  ,
  tag_name varchar(50) NOT NULL,
  nbuse int(11) DEFAULT '0'
) ;

CREATE UNIQUE INDEX hf_sc_tags_uk_tag ON hf_sc_tags  (  tag_name );
INSERT INTO hf_sc_tags (tag_id, tag_name, nbuse) VALUES (1, 'install', 1);

CREATE TABLE hf_sc_tags_tagged (
  tt_id int(10)  NOT NULL PRIMARY KEY,
  tag_id int(10)  NOT NULL,
  tt_scope_id varchar(50) NOT NULL,
  tt_subject_id int(10) NOT NULL
) ;
CREATE INDEX hf_sc_tags_tagged_idx1_tt ON hf_sc_tags_tagged  (tt_scope_id,tt_subject_id);
CREATE INDEX hf_sc_tags_tagged_idx2_tt ON hf_sc_tags_tagged  (tag_id);

INSERT INTO hf_sc_tags_tagged (tt_id, tag_id, tt_scope_id, tt_subject_id) VALUES
(1, 1, 'forumscope', 1);


CREATE TABLE hf_search_words (
  id varchar(30) NOT NULL,
  datasource varchar(40),
  words varchar(255) NOT NULL,
  weight int(4) NOT NULL
);
CREATE UNIQUE INDEX hf_search_words_source_id ON hf_search_words (id,datasource,words);
CREATE INDEX hf_search_words_words ON hf_search_words (words);

INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'first', 1);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'life', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'new', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'start', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'and', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'remov', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'now', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'complet', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'instal', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'your', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'that', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'conclud', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'can', 4);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'post', 5);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'thi', 4);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'read', 2);
INSERT INTO hf_search_words (id, words, weight) VALUES (1, 'you', 6);


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




CREATE TABLE hf_jmessenger (
  id INTEGER NOT NULL PRIMARY KEY,
  id_from int(11) NOT NULL default '0',
  id_for int(11) NOT NULL default '0',
  date datetime NOT NULL,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  isSeen tinyint(4) NOT NULL,
  isArchived tinyint(4) NOT NULL,
  isReceived tinyint(4) NOT NULL,
  isSend tinyint(4) NOT NULL
) ;


CREATE TABLE hf_connected (
    id_user INTEGER NOT NULL DEFAULT '1'  PRIMARY KEY,
    member_ip VARCHAR(200) NOT NULL DEFAULT '',
    connected INT(10) NOT NULL DEFAULT 0,
    idle TINYINT(1) NOT NULL DEFAULT 0
) ;


CREATE TABLE hf_rates (
    id_user   INT(10) NOT NULL ,
    id_source INT(10) NOT NULL ,
    source    VARCHAR(40) NOT NULL,
    ip        VARCHAR(80) NOT NULL,
    level FLOAT NOT NULL
);
CREATE UNIQUE INDEX rates_id  ON hf_rates  (  id_user , id_source , source ) ;
CREATE INDEX hf_rates_id_user ON hf_rates ( id_user );
CREATE INDEX hf_rates_id_source ON hf_rates( id_source );
CREATE INDEX hf_rates_source ON hf_rates( source );


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
