--
-- Base de données: `havefnu`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id_cat` int(12) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_order` int(4) NOT NULL,
  PRIMARY KEY (`id_cat`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id_cat`, `cat_name`, `cat_order`) VALUES
(1, 'My First Forum', 1),
(2, 'My Second forum', 2);

-- --------------------------------------------------------

--
-- Structure de la table `forum`
--

DROP TABLE IF EXISTS `forum`;
CREATE TABLE IF NOT EXISTS `forum` (
  `id_forum` int(12) NOT NULL AUTO_INCREMENT,
  `forum_name` varchar(255) NOT NULL,
  `id_cat` int(12) NOT NULL,
  `forum_desc` varchar(255) NOT NULL,
  `forum_order` int(4) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `child_level` int(4) NOT NULL,
  PRIMARY KEY (`id_forum`),
  KEY `id_cat` (`id_cat`),
  KEY `parent_id` (`parent_id`),
  KEY `child_level` (`child_level`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum`
--

INSERT INTO `forum` (`id_forum`, `forum_name`, `id_cat`, `forum_desc`, `forum_order`, `parent_id`, `child_level`) VALUES
(1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0),
(2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0),
(3, 'Light', 2, 'Soo light', 1, 0, 0),
(4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_group`
--

DROP TABLE IF EXISTS `jacl2_group`;
CREATE TABLE IF NOT EXISTS `jacl2_group` (
  `id_aclgrp` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `grouptype` tinyint(4) NOT NULL DEFAULT '0',
  `ownerlogin` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_aclgrp`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jacl2_group`
--

INSERT INTO `jacl2_group` (`id_aclgrp`, `name`, `grouptype`, `ownerlogin`) VALUES
(1, 'admins', 0, NULL),
(2, 'users', 0, NULL),
(3, 'moderators', 0, NULL),
(4, 'havefnu', 2, 'havefnu'),
(5, 'foxmask', 2, 'foxmask');

-- --------------------------------------------------------


DROP TABLE IF EXISTS `jacl2_rights`;
CREATE TABLE IF NOT EXISTS `jacl2_rights` (
  `id_aclsbj` varchar(100) NOT NULL DEFAULT '',
  `id_aclgrp` int(11) NOT NULL DEFAULT '0',
  `id_aclres` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_aclsbj`,`id_aclgrp`,`id_aclres`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jacl2_rights`
--

INSERT INTO `jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
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
('hfnu.admin.ban.create', 1, ''),
('hfnu.admin.ban.delete', 1, ''),
('hfnu.admin.ban.edit', 1, ''),
('hfnu.admin.cache.clear', 1, ''),
('hfnu.admin.category.create', 1, ''),
('hfnu.admin.category.delete', 1, ''),
('hfnu.admin.category.edit', 1, ''),
('hfnu.admin.config.edit', 1, ''),
('hfnu.admin.config.view', 1, ''),
('hfnu.admin.forum.create', 1, ''),
('hfnu.admin.forum.delete', 1, ''),
('hfnu.admin.forum.edit', 1, ''),
('hfnu.admin.index', 1, ''),
('hfnu.admin.index', 3, ''),
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
('hfnu.admin.rank.create', 1, ''),
('hfnu.admin.rank.create', 3, ''),
('hfnu.admin.rank.delete', 1, ''),
('hfnu.admin.rank.delete', 3, ''),
('hfnu.admin.rank.edit', 1, ''),
('hfnu.admin.rank.edit', 3, ''),
('hfnu.admin.server.info', 1, ''),
('hfnu.category.list', 1, ''),
('hfnu.category.list', 2, ''),
('hfnu.category.list', 3, ''),
('hfnu.category.view', 1, ''),
('hfnu.category.view', 2, ''),
('hfnu.category.view', 3, ''),
('hfnu.forum.goto', 1, ''),
('hfnu.forum.goto', 2, ''),
('hfnu.forum.goto', 3, ''),
('hfnu.forum.list', 0, 'forum1'),
('hfnu.forum.list', 0, 'forum2'),
('hfnu.forum.list', 0, 'forum3'),
('hfnu.forum.list', 0, 'forum4'),
('hfnu.forum.list', 1, ''),
('hfnu.forum.list', 1, 'forum1'),
('hfnu.forum.list', 1, 'forum2'),
('hfnu.forum.list', 1, 'forum3'),
('hfnu.forum.list', 1, 'forum4'),
('hfnu.forum.list', 2, ''),
('hfnu.forum.list', 2, 'forum1'),
('hfnu.forum.list', 2, 'forum2'),
('hfnu.forum.list', 2, 'forum3'),
('hfnu.forum.list', 2, 'forum4'),
('hfnu.forum.list', 3, ''),
('hfnu.forum.list', 3, 'forum1'),
('hfnu.forum.list', 3, 'forum2'),
('hfnu.forum.list', 3, 'forum3'),
('hfnu.forum.list', 3, 'forum4'),
('hfnu.forum.view', 0, 'forum1'),
('hfnu.forum.view', 0, 'forum2'),
('hfnu.forum.view', 0, 'forum3'),
('hfnu.forum.view', 0, 'forum4'),
('hfnu.forum.view', 1, ''),
('hfnu.forum.view', 1, 'forum1'),
('hfnu.forum.view', 1, 'forum2'),
('hfnu.forum.view', 1, 'forum3'),
('hfnu.forum.view', 1, 'forum4'),
('hfnu.forum.view', 2, ''),
('hfnu.forum.view', 2, 'forum1'),
('hfnu.forum.view', 2, 'forum2'),
('hfnu.forum.view', 2, 'forum3'),
('hfnu.forum.view', 2, 'forum4'),
('hfnu.forum.view', 3, ''),
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
('hfnu.posts.create', 1, ''),
('hfnu.posts.create', 1, 'forum1'),
('hfnu.posts.create', 1, 'forum2'),
('hfnu.posts.create', 1, 'forum3'),
('hfnu.posts.create', 1, 'forum4'),
('hfnu.posts.create', 2, ''),
('hfnu.posts.create', 2, 'forum1'),
('hfnu.posts.create', 2, 'forum2'),
('hfnu.posts.create', 2, 'forum3'),
('hfnu.posts.create', 2, 'forum4'),
('hfnu.posts.create', 3, ''),
('hfnu.posts.create', 3, 'forum1'),
('hfnu.posts.create', 3, 'forum2'),
('hfnu.posts.create', 3, 'forum3'),
('hfnu.posts.create', 3, 'forum4'),
('hfnu.posts.delete', 1, ''),
('hfnu.posts.delete', 1, 'forum1'),
('hfnu.posts.delete', 1, 'forum2'),
('hfnu.posts.delete', 1, 'forum3'),
('hfnu.posts.delete', 1, 'forum4'),
('hfnu.posts.delete', 3, ''),
('hfnu.posts.edit', 1, ''),
('hfnu.posts.edit', 1, 'forum1'),
('hfnu.posts.edit', 1, 'forum2'),
('hfnu.posts.edit', 1, 'forum3'),
('hfnu.posts.edit', 1, 'forum4'),
('hfnu.posts.edit', 2, ''),
('hfnu.posts.edit', 3, ''),
('hfnu.posts.edit', 3, 'forum1'),
('hfnu.posts.edit', 3, 'forum2'),
('hfnu.posts.edit', 3, 'forum3'),
('hfnu.posts.edit', 3, 'forum4'),
('hfnu.posts.edit.own', 1, ''),
('hfnu.posts.edit.own', 1, 'forum1'),
('hfnu.posts.edit.own', 1, 'forum2'),
('hfnu.posts.edit.own', 1, 'forum3'),
('hfnu.posts.edit.own', 1, 'forum4'),
('hfnu.posts.edit.own', 2, 'forum1'),
('hfnu.posts.edit.own', 2, 'forum2'),
('hfnu.posts.edit.own', 2, 'forum3'),
('hfnu.posts.edit.own', 2, 'forum4'),
('hfnu.posts.edit.own', 3, ''),
('hfnu.posts.edit.own', 3, 'forum1'),
('hfnu.posts.edit.own', 3, 'forum2'),
('hfnu.posts.edit.own', 3, 'forum3'),
('hfnu.posts.edit.own', 3, 'forum4'),
('hfnu.posts.list', 0, 'forum1'),
('hfnu.posts.list', 0, 'forum2'),
('hfnu.posts.list', 0, 'forum3'),
('hfnu.posts.list', 0, 'forum4'),
('hfnu.posts.list', 1, ''),
('hfnu.posts.list', 1, 'forum1'),
('hfnu.posts.list', 1, 'forum2'),
('hfnu.posts.list', 1, 'forum3'),
('hfnu.posts.list', 1, 'forum4'),
('hfnu.posts.list', 2, ''),
('hfnu.posts.list', 2, 'forum1'),
('hfnu.posts.list', 2, 'forum2'),
('hfnu.posts.list', 2, 'forum3'),
('hfnu.posts.list', 2, 'forum4'),
('hfnu.posts.list', 3, ''),
('hfnu.posts.list', 3, 'forum1'),
('hfnu.posts.list', 3, 'forum2'),
('hfnu.posts.list', 3, 'forum3'),
('hfnu.posts.list', 3, 'forum4'),
('hfnu.posts.moderate', 1, ''),
('hfnu.posts.moderate', 3, ''),
('hfnu.posts.notify', 1, ''),
('hfnu.posts.notify', 1, 'forum1'),
('hfnu.posts.notify', 1, 'forum2'),
('hfnu.posts.notify', 1, 'forum3'),
('hfnu.posts.notify', 1, 'forum4'),
('hfnu.posts.notify', 2, ''),
('hfnu.posts.notify', 2, 'forum1'),
('hfnu.posts.notify', 2, 'forum2'),
('hfnu.posts.notify', 2, 'forum3'),
('hfnu.posts.notify', 2, 'forum4'),
('hfnu.posts.notify', 3, ''),
('hfnu.posts.notify', 3, 'forum1'),
('hfnu.posts.notify', 3, 'forum2'),
('hfnu.posts.notify', 3, 'forum3'),
('hfnu.posts.notify', 3, 'forum4'),
('hfnu.posts.quote', 1, ''),
('hfnu.posts.quote', 1, 'forum1'),
('hfnu.posts.quote', 1, 'forum2'),
('hfnu.posts.quote', 1, 'forum3'),
('hfnu.posts.quote', 1, 'forum4'),
('hfnu.posts.quote', 2, ''),
('hfnu.posts.quote', 2, 'forum1'),
('hfnu.posts.quote', 2, 'forum2'),
('hfnu.posts.quote', 2, 'forum3'),
('hfnu.posts.quote', 2, 'forum4'),
('hfnu.posts.quote', 3, ''),
('hfnu.posts.quote', 3, 'forum1'),
('hfnu.posts.quote', 3, 'forum2'),
('hfnu.posts.quote', 3, 'forum3'),
('hfnu.posts.quote', 3, 'forum4'),
('hfnu.posts.reply', 1, ''),
('hfnu.posts.reply', 1, 'forum1'),
('hfnu.posts.reply', 1, 'forum2'),
('hfnu.posts.reply', 1, 'forum3'),
('hfnu.posts.reply', 1, 'forum4'),
('hfnu.posts.reply', 2, ''),
('hfnu.posts.reply', 2, 'forum1'),
('hfnu.posts.reply', 2, 'forum2'),
('hfnu.posts.reply', 2, 'forum3'),
('hfnu.posts.reply', 2, 'forum4'),
('hfnu.posts.reply', 3, ''),
('hfnu.posts.reply', 3, 'forum1'),
('hfnu.posts.reply', 3, 'forum2'),
('hfnu.posts.reply', 3, 'forum3'),
('hfnu.posts.reply', 3, 'forum4'),
('hfnu.posts.view', 0, 'forum1'),
('hfnu.posts.view', 0, 'forum2'),
('hfnu.posts.view', 0, 'forum3'),
('hfnu.posts.view', 0, 'forum4'),
('hfnu.posts.view', 1, ''),
('hfnu.posts.view', 1, 'forum1'),
('hfnu.posts.view', 1, 'forum2'),
('hfnu.posts.view', 1, 'forum3'),
('hfnu.posts.view', 1, 'forum4'),
('hfnu.posts.view', 2, ''),
('hfnu.posts.view', 2, 'forum1'),
('hfnu.posts.view', 2, 'forum2'),
('hfnu.posts.view', 2, 'forum3'),
('hfnu.posts.view', 2, 'forum4'),
('hfnu.posts.view', 3, ''),
('hfnu.posts.view', 3, 'forum1'),
('hfnu.posts.view', 3, 'forum2'),
('hfnu.posts.view', 3, 'forum3'),
('hfnu.posts.view', 3, 'forum4'),
('hfnu.search', 1, ''),
('hfnu.search', 2, ''),
('hfnu.search', 3, '');


-- --------------------------------------------------------

--
-- Structure de la table `jacl2_subject`
--

DROP TABLE IF EXISTS `jacl2_subject`;
CREATE TABLE IF NOT EXISTS `jacl2_subject` (
  `id_aclsbj` varchar(100) NOT NULL DEFAULT '',
  `label_key` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_aclsbj`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jacl2_subject`
--

INSERT INTO `jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
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
('hfnu.admin.ban.create', 'hfnu~acl2.admin.ban.create'),
('hfnu.admin.ban.delete', 'hfnu~acl2.admin.ban.delete'),
('hfnu.admin.ban.edit', 'hfnu~acl2.admin.ban.edit'),
('hfnu.admin.category.create', 'hfnu~acl2.admin.category.create'),
('hfnu.admin.category.delete', 'hfnu~acl2.admin.category.delete'),
('hfnu.admin.category.edit', 'hfnu~acl2.admin.category.edit'),
('hfnu.admin.config.edit', 'hfnu~acl2.admin.config.edit'),
('hfnu.admin.config.view', 'hfnu~acl2.admin.config.view'),
('hfnu.admin.forum.create', 'hfnu~acl2.admin.forum.create'),
('hfnu.admin.forum.delete', 'hfnu~acl2.admin.forum.delete'),
('hfnu.admin.forum.edit', 'hfnu~acl2.admin.forum.edit'),
('hfnu.admin.index', 'hfnu~acl2.admin.index'),
('hfnu.admin.member.ban', 'hfnu~acl2.admin.member.ban'),
('hfnu.admin.member.create', 'hfnu~acl2.admin.member.create'),
('hfnu.admin.member.delete', 'hfnu~acl2.admin.member.delete'),
('hfnu.admin.member.edit', 'hfnu~acl2.admin.member.edit'),
('hfnu.admin.notify.delete', 'hfnu~acl2.admin.notify.delete'),
('hfnu.admin.notify.list', 'hfnu~acl2.admin.notify.list'),
('hfnu.admin.rank.create', 'hfnu~acl2.admin.rank.create'),
('hfnu.admin.rank.delete', 'hfnu~acl2.admin.rank.delete'),
('hfnu.admin.rank.edit', 'hfnu~acl2.admin.rank.edit'),
('hfnu.admin.server.info', 'hfnu~acl2.admin.server.info'),
('hfnu.category.list', 'hfnu~acl2.category.list'),
('hfnu.category.view', 'hfnu~acl2.category.view'),
('hfnu.forum.goto', 'hfnu~acl2.forum.goto'),
('hfnu.forum.list', 'hfnu~acl2.forum.list'),
('hfnu.forum.view', 'hfnu~acl2.forum.view'),
('hfnu.member.list', 'hfnu~acl2.member.list'),
('hfnu.member.search', 'hfnu~acl2.member.search'),
('hfnu.member.view', 'hfnu~acl2.member.view'),
('hfnu.posts.create', 'hfnu~acl2.posts.create'),
('hfnu.posts.delete', 'hfnu~acl2.posts.delete'),
('hfnu.posts.edit', 'hfnu~acl2.posts.edit'),
('hfnu.posts.list', 'hfnu~acl2.posts.list'),
('hfnu.posts.moderate', 'hfnu~acl2.posts.moderate'),
('hfnu.posts.notify', 'hfnu~acl2.posts.notify'),
('hfnu.posts.quote', 'hfnu~acl2.posts.quote'),
('hfnu.posts.reply', 'hfnu~acl2.posts.reply'),
('hfnu.posts.view', 'hfnu~acl2.posts.view'),
('hfnu.search', 'hfnu~acl2.search'),
('hfnu.posts.edit.own', 'hfnu~acl2.posts.edit.own');

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_user_group`
--

DROP TABLE IF EXISTS `jacl2_user_group`;
CREATE TABLE IF NOT EXISTS `jacl2_user_group` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_aclgrp` int(11) NOT NULL DEFAULT '0',
  KEY `login` (`login`,`id_aclgrp`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jacl2_user_group`
--

INSERT INTO `jacl2_user_group` (`login`, `id_aclgrp`) VALUES
('havefnu', 1),
('havefnu', 4);

-- --------------------------------------------------------

--
-- Structure de la table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `id_user` int(12) NOT NULL AUTO_INCREMENT,
  `member_login` varchar(50) NOT NULL,
  `member_password` varchar(50) NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `member_nickname` varchar(50) DEFAULT NULL,
  `member_status` tinyint(4) NOT NULL DEFAULT '0',
  `member_keyactivate` varchar(10) DEFAULT NULL,
  `member_request_date` datetime DEFAULT NULL,
  `member_website` varchar(255) DEFAULT NULL,
  `member_firstname` varchar(40) DEFAULT NULL,
  `member_birth` date NOT NULL DEFAULT '1980-01-01',
  `member_country` varchar(100) DEFAULT NULL,
  `member_town` varchar(100) DEFAULT NULL,
  `member_comment` varchar(255) DEFAULT NULL,
  `member_avatar` varchar(255) DEFAULT NULL,
  `member_xfire` varchar(80) DEFAULT NULL,
  `member_icq` varchar(80) DEFAULT NULL,
  `member_hotmail` varchar(255) DEFAULT NULL,
  `member_yim` varchar(255) DEFAULT NULL,
  `member_aol` varchar(255) DEFAULT NULL,
  `member_gtalk` varchar(255) DEFAULT NULL,
  `member_jabber` varchar(255) DEFAULT NULL,
  `member_proc` varchar(40) DEFAULT NULL,
  `member_mb` varchar(40) DEFAULT NULL,
  `member_card` varchar(40) DEFAULT NULL,
  `member_ram` varchar(40) DEFAULT NULL,
  `member_display` varchar(40) DEFAULT NULL,
  `member_screen` varchar(40) DEFAULT NULL,
  `member_mouse` varchar(40) DEFAULT NULL,
  `member_keyb` varchar(40) DEFAULT NULL,
  `member_os` varchar(40) DEFAULT NULL,
  `member_connection` varchar(40) DEFAULT NULL,
  `member_last_connect` datetime DEFAULT NULL,
  `member_show_email` varchar(1) DEFAULT 'N',
  `member_language` varchar(40) DEFAULT 'fr_FR',
  `member_nb_msg` int(12) DEFAULT '0',
  `member_last_post` int(12) DEFAULT NULL,
  PRIMARY KEY (`member_login`),
  UNIQUE KEY `id_user` (`id_user`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `member`
--

INSERT INTO `member` (`id_user`, `member_login`, `member_password`, `member_email`, `member_nickname`, `member_status`, `member_keyactivate`, `member_request_date`, `member_website`, `member_firstname`, `member_birth`, `member_country`, `member_town`, `member_comment`, `member_avatar`, `member_xfire`, `member_icq`, `member_hotmail`, `member_yim`, `member_aol`, `member_gtalk`, `member_jabber`, `member_proc`, `member_mb`, `member_card`, `member_ram`, `member_display`, `member_screen`, `member_mouse`, `member_keyb`, `member_os`, `member_connection`, `member_last_connect`, `member_show_email`, `member_language`, `member_nb_msg`, `member_last_post`) VALUES
(1, 'havefnu', '0dc12261c353a4c2dfa1b6e01ded9bed', 'havefnu@foxmask.info', 'havefnu', 2, NULL, '2009-02-03 10:28:51', 'http://forge.jelix.org/projects/havefnubb', NULL, '1969-01-14', 'France', 'Paris', '', '0', '123456Toto', 'abscde', 'tata@live.fr', 'toto@yahoo.com', 'truc@aol.com', 'tata@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2009-02-24 16:30:55', 'N', 'fr_FR', 51, 1234960470);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id_post` int(12) NOT NULL AUTO_INCREMENT,
  `id_user` int(12) NOT NULL,
  `id_forum` int(12) NOT NULL,
  `parent_id` int(12) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `viewed` int(12) NOT NULL,
  `poster_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id_post`),
  KEY `id_user` (`id_user`,`id_forum`,`parent_id`,`status`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id_post`, `id_user`, `id_forum`, `parent_id`, `status`, `subject`, `message`, `date_created`, `date_modified`, `viewed`, `poster_ip`) VALUES
(1, 1, 1, 1, 1, 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)', '2009-02-03 10:28:51', '2009-02-03 10:28:51', 26, '');

-- --------------------------------------------------------

--
-- Structure de la table `rank`
--

DROP TABLE IF EXISTS `rank`;
CREATE TABLE IF NOT EXISTS `rank` (
  `id_rank` int(12) NOT NULL AUTO_INCREMENT,
  `rank_name` varchar(40) NOT NULL,
  `rank_limit` int(9) NOT NULL,
  PRIMARY KEY (`id_rank`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `rank`
--

INSERT INTO `rank` (`id_rank`, `rank_name`, `rank_limit`) VALUES
(1, 'new member', 10),
(2, 'member', 40),
(3, 'active member', 100);

-- --------------------------------------------------------

--
-- Structure de la table `sc_tags`
--

DROP TABLE IF EXISTS `sc_tags`;
CREATE TABLE IF NOT EXISTS `sc_tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) NOT NULL,
  `nbuse` int(11) DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `uk_tag` (`tag_name`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `sc_tags`
--


-- --------------------------------------------------------

--
-- Structure de la table `sc_tags_tagged`
--

DROP TABLE IF EXISTS `sc_tags_tagged`;
CREATE TABLE IF NOT EXISTS `sc_tags_tagged` (
  `tt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `tt_scope_id` varchar(50) NOT NULL,
  `tt_subject_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tt_id`),
  KEY `idx1_tt` (`tt_scope_id`,`tt_subject_id`),
  KEY `idx2_tt` (`tag_id`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `sc_tags_tagged`
--


-- --------------------------------------------------------

--
-- Structure de la table `search_words`
--

DROP TABLE IF EXISTS `search_words`;
CREATE TABLE IF NOT EXISTS `search_words` (
  `id` int(12) NOT NULL,
  `words` varchar(255) NOT NULL,
  `weight` int(4) NOT NULL,
  KEY `words` (`words`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `search_words`
--


DROP TABLE IF EXISTS `notify`; 
CREATE TABLE IF NOT EXISTS `notify` ( 
  `id_notify` int(12) NOT NULL AUTO_INCREMENT, 
  `id_user` int(12) NOT NULL, 
  `id_post` int(12) NOT NULL,
  `id_forum` int(12) NOT NULL,  
  `subject` varchar(255) NOT NULL, 
  `message` text NOT NULL, 
  `date_created` datetime NOT NULL, 
  `date_modified` datetime NOT NULL, 
  PRIMARY KEY (`id_notify`), 
  KEY `id_user` (`id_user`), 
  KEY `id_post` (`id_post`) 
); 
