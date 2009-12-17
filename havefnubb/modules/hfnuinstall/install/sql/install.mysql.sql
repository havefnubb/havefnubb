--
-- Base de données: `havefnu`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `hf_category`;
CREATE TABLE IF NOT EXISTS `hf_category` (
  `id_cat` int(12) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_order` int(4) NOT NULL,
  PRIMARY KEY (`id_cat`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `category`
--

INSERT INTO `hf_category` (`id_cat`, `cat_name`, `cat_order`) VALUES
(1, 'My First Forum', 1),
(2, 'My Second forum', 2);

-- --------------------------------------------------------

--
-- Structure de la table `forum`
--

DROP TABLE IF EXISTS `hf_forum`;
CREATE TABLE IF NOT EXISTS `hf_forum` (
  `id_forum` int(12) NOT NULL AUTO_INCREMENT,
  `forum_name` varchar(255) NOT NULL,
  `id_cat` int(12) NOT NULL,
  `forum_desc` varchar(255) NOT NULL,
  `forum_order` int(4) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `child_level` int(4) NOT NULL,
  `forum_type` INT( 1 ) NOT NULL,
  `forum_url` varchar( 255 ) DEFAULT NULL,
  `post_expire` INT ( 5 ) DEFAULT '0',  
  PRIMARY KEY (`id_forum`),
  KEY `id_cat` (`id_cat`),
  KEY `parent_id` (`parent_id`),
  KEY `child_level` (`child_level`),
  KEY `forum_type` (`forum_type` )
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum`
--

INSERT INTO `hf_forum` (`id_forum`, `forum_name`, `id_cat`, `forum_desc`, `forum_order`, `parent_id`, `child_level`,`forum_type`,`forum_url`,`post_expire`) VALUES
(1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0, 0,'',0),
(2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0, 0,'',0),
(3, 'Light', 2, 'Soo light', 1, 0, 0, 0,'',0),
(4, 'My SubForum is Smooth', 1, 'Smoothy', 1, 1, 1, 0,'',0);

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_group`
--

DROP TABLE IF EXISTS `hf_jacl2_group`;
CREATE TABLE IF NOT EXISTS `hf_jacl2_group` (
  `id_aclgrp` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `grouptype` tinyint(4) NOT NULL DEFAULT '0',
  `ownerlogin` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_aclgrp`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jacl2_group`
--

INSERT INTO `hf_jacl2_group` (`id_aclgrp`, `name`, `grouptype`, `ownerlogin`) VALUES
(1, 'admins', 0, NULL),
(2, 'users', 1, NULL),
(3, 'moderators', 0, NULL);

-- --------------------------------------------------------

DROP TABLE IF EXISTS `hf_jacl2_rights`;
CREATE TABLE IF NOT EXISTS `hf_jacl2_rights` (
  `id_aclsbj` varchar(100) NOT NULL DEFAULT '',
  `id_aclgrp` int(11) NOT NULL DEFAULT '0',
  `id_aclres` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_aclsbj`,`id_aclgrp`,`id_aclres`)
) DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jacl2_rights`
--

INSERT INTO `hf_jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
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
('hfnu.search', 0, ''),
('hfnu.search', 1, ''),
('hfnu.search', 2, ''),
('hfnu.search', 3, ''),

('hfnu.admin.contact', 1, ''),

('hfnu.admin.themes', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `jacl2_subject`
--

DROP TABLE IF EXISTS `hf_jacl2_subject`;
CREATE TABLE IF NOT EXISTS `hf_jacl2_subject` (
  `id_aclsbj` varchar(100) NOT NULL DEFAULT '',
  `label_key` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_aclsbj`)
) DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jacl2_subject`
--

INSERT INTO `hf_jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
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
('hfnu.admin.serverinfo', 'havefnubb~acl2.admin.serverinfo'),
('hfnu.admin.contact', 'hfnucontact~acl2.admin.contact'),
('hfnu.admin.themes', 'havefnubb~acl2.admin.themes');

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_user_group`
--

DROP TABLE IF EXISTS `hf_jacl2_user_group`;
CREATE TABLE IF NOT EXISTS `hf_jacl2_user_group` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_aclgrp` int(11) NOT NULL DEFAULT '0',
  KEY `login` (`login`,`id_aclgrp`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `member`
--

DROP TABLE IF EXISTS `hf_member`;
CREATE TABLE IF NOT EXISTS `hf_member` (
  `id_user` int(12) NOT NULL AUTO_INCREMENT,
  `member_login` varchar(50) NOT NULL,
  `member_password` varchar(50) NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `member_nickname` varchar(50) DEFAULT NULL,
  `member_status` tinyint(4) NOT NULL default '0',  
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
  `member_last_connect` int(12) DEFAULT NULL,
  `member_show_email` varchar(1) DEFAULT 'N',
  `member_language` varchar(40) DEFAULT 'fr_FR',
  `member_nb_msg` int(12) DEFAULT '0',
  `member_last_post` int(12) NOT NULL DEFAULT '0',
  `member_created` datetime DEFAULT NULL,
  `member_gravatar` INT( 1 ) NOT NULL DEFAULT '0', 
  PRIMARY KEY (`member_login`),
  UNIQUE KEY `id_user` (`id_user`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `hf_posts`;
CREATE TABLE IF NOT EXISTS `hf_posts` (
  `id_post` int(12) NOT NULL AUTO_INCREMENT,
  `id_user` int(12) NOT NULL,
  `id_forum` int(12) NOT NULL,
  `parent_id` int(12) NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'opened',  
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_created` int(12) NOT NULL,
  `date_modified` int(12) NOT NULL,
  `viewed` int(12) NOT NULL,
  `poster_ip` varchar(15) NOT NULL,
  `censored_msg` VARCHAR( 50 ) NULL,
  `read_by_mod` int(1) DEFAULT '0',
  PRIMARY KEY (`id_post`),
  KEY `id_user` (`id_user`,`id_forum`,`parent_id`,`status`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `posts`
--

INSERT INTO `hf_posts` (`id_post`, `id_user`, `id_forum`, `parent_id`, `status`, `subject`, `message`, `date_created`, `date_modified`, `viewed`, `poster_ip`, `censored_msg`,`read_by_mod` ) VALUES
(1, 1, 1, 1, 'opened', 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, '127.0.0.1',NULL,1);

-- --------------------------------------------------------

--
-- Structure de la table `rank`
--

DROP TABLE IF EXISTS `hf_rank`;
CREATE TABLE IF NOT EXISTS `hf_rank` (
  `id_rank` int(12) NOT NULL AUTO_INCREMENT,
  `rank_name` varchar(40) NOT NULL,
  `rank_limit` int(9) NOT NULL,
  PRIMARY KEY (`id_rank`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `rank`
--

INSERT INTO `hf_rank` (`id_rank`, `rank_name`, `rank_limit`) VALUES
(1, 'new member', 0),
(2, 'member', 40),
(3, 'active member', 100);

-- --------------------------------------------------------

--
-- Structure de la table `sc_tags`
--

DROP TABLE IF EXISTS `hf_sc_tags`;
CREATE TABLE IF NOT EXISTS `hf_sc_tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) NOT NULL,
  `nbuse` int(11) DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `uk_tag` (`tag_name`)
) DEFAULT CHARSET=utf8;

--
-- Contenu de la table `sc_tags`
--

INSERT INTO `hf_sc_tags` (`tag_id`, `tag_name`, `nbuse`) VALUES
(1, 'install', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sc_tags_tagged`
--

DROP TABLE IF EXISTS `hf_sc_tags_tagged`;
CREATE TABLE IF NOT EXISTS `hf_sc_tags_tagged` (
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

INSERT INTO `hf_sc_tags_tagged` (`tt_id`, `tag_id`, `tt_scope_id`, `tt_subject_id`) VALUES
(1, 1, 'forumscope', 1);



-- --------------------------------------------------------


DROP TABLE IF EXISTS `hf_search_words`;
CREATE TABLE IF NOT EXISTS `hf_search_words` (
  `id` varchar(30) NOT NULL,
  `datasource` varchar(40),
  `words` varchar(255) NOT NULL,
  `weight` int(4) NOT NULL,
  KEY `words` (`words`),
  PRIMARY KEY source_id (id,datasource,words)
)DEFAULT CHARSET=utf8;

--
-- Contenu de la table `search_words`
--

INSERT INTO `hf_search_words` (`id`, `words`, `weight`) VALUES
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



DROP TABLE IF EXISTS `hf_notify`; 
CREATE TABLE IF NOT EXISTS `hf_notify` ( 
  `id_notify` int(12) NOT NULL AUTO_INCREMENT, 
  `id_user` int(12) NOT NULL, 
  `id_post` int(12) NOT NULL,
  `id_forum` int(12) NOT NULL,  
  `subject` varchar(255) NOT NULL, 
  `message` text NOT NULL, 
  `date_created` int(12) NOT NULL,
  `date_modified` int(12) NOT NULL,
  PRIMARY KEY (`id_notify`), 
  KEY `id_user` (`id_user`), 
  KEY `id_post` (`id_post`) 
) DEFAULT CHARSET=utf8;


--
-- Structure de la table `hf_bans`
--

DROP TABLE IF EXISTS `hf_bans`;
CREATE TABLE IF NOT EXISTS `hf_bans` (
  `ban_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ban_username` varchar(200) DEFAULT NULL,
  `ban_ip` varchar(255) DEFAULT NULL,
  `ban_email` varchar(50) DEFAULT NULL,
  `ban_message` varchar(255) DEFAULT NULL,
  `ban_expire` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ban_id`)
) DEFAULT CHARSET=utf8;


--
-- Structure de la table `hf_jmessenger`
--

DROP TABLE IF EXISTS `hf_jmessenger`;
CREATE TABLE IF NOT EXISTS `hf_jmessenger` (
  `id` int(11) NOT NULL auto_increment,
  `id_from` int(11) NOT NULL default '0',
  `id_for` int(11) NOT NULL default '0',
  `date` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `isSeen` tinyint(4) NOT NULL,
  `isArchived` tinyint(4) NOT NULL,
  `isReceived` tinyint(4) NOT NULL,
  `isSend` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;


--
-- Structure de la table `hf_connected`
-- table utilisée pour conserver l'activité des utilisateurs : (non-)connecté / (in)actif
--

DROP TABLE IF EXISTS `hf_connected`;
CREATE TABLE IF NOT EXISTS `hf_connected` (
    `id_user` int(12) NOT NULL DEFAULT '1',
    member_ip VARCHAR(200) NOT NULL DEFAULT '',
    connected INT(10) UNSIGNED NOT NULL DEFAULT 0,
    idle TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_user`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hf_rates`;
CREATE TABLE IF NOT EXISTS `hf_rates` (	
    `id_user`   INT NOT NULL ,
    `id_source` INT NOT NULL ,
    `source`    VARCHAR(40) NOT NULL,
    `ip`        VARCHAR(80) NOT NULL,
    level FLOAT NOT NULL ,
    INDEX ( `id_user` ),
    INDEX ( `id_source` ),    
    INDEX ( `source` ),
    PRIMARY KEY rates_id (id_user,id_source,source)
)DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `hf_read_forum`;
CREATE TABLE IF NOT EXISTS `hf_read_forum` (
  `id_user` int(12) NOT NULL,
  `id_forum` int(12) NOT NULL,
  `date_read` int(12) NOT NULL,
  PRIMARY KEY  (`id_user`,`id_forum`,`date_read`),
  KEY `id_user` (`id_user`),
  KEY `id_forum` (`id_forum`),
  KEY `date_read` (`date_read`)
) DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `hf_read_posts`;
CREATE TABLE IF NOT EXISTS `hf_read_posts` (
  `id_user` int(12) NOT NULL,
  `id_forum` int(12) NOT NULL,
  `id_post` int(12) NOT NULL,
  PRIMARY KEY  (`id_user`,`id_forum`,`id_post`),
  KEY `id_user` (`id_user`),
  KEY `id_forum` (`id_forum`),
  KEY `id_post` (`id_post`)
) DEFAULT CHARSET=utf8;

