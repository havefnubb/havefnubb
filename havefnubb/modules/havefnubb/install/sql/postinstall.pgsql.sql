


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
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.post', 1, '');
INSERT INTO hf_jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES ('hfnu.admin.post', 3, '');
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
INSERT INTO hf_jacl2_subject (id_aclsbj, label_key) VALUES ('hfnu.admin.post', 'havefnubb~acl2.admin.post');


INSERT INTO hf_jacl2_user_group (login, id_aclgrp) VALUES ('admin', 1);
INSERT INTO hf_jacl2_user_group (login, id_aclgrp) VALUES ('admin', 4);

INSERT INTO hf_member (id_user, member_login, member_password, member_email, member_nickname, member_status, member_keyactivate, member_request_date, member_website, member_firstname, member_birth, member_country, member_town, member_comment, member_avatar, member_last_connect, member_show_email, member_language, member_nb_msg, member_last_post, member_created, member_gravatar) VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.net', 'admin', 1, NULL, '2010-06-08 10:21:43', NULL, NULL, '1980-01-01', NULL, NULL, NULL, NULL, 1276004461, NULL, NULL, 0, 0, '2010-06-08 10:21:43', 0);


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

