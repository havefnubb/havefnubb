DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
`id_cat` INT( 12 ) NOT NULL AUTO_INCREMENT ,
`cat_name` VARCHAR( 255 ) NOT NULL ,
`cat_order` INT( 4 ) NOT NULL ,
PRIMARY KEY ( `id_cat` )
) ;

INSERT INTO `category` (`id_cat`, `cat_name`, `cat_order`) VALUES
(1, 'My First Forum', 1),
(2, 'My Second forum', 2);


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `forum`
--

INSERT INTO `forum` (`id_forum`, `forum_name`, `id_cat`, `forum_desc`, `forum_order`, `parent_id`, `child_level`) VALUES
(1, 'My Forum is Fun', 1, 'Everything is Fnu', 1, 0, 0),
(2, 'My Forum is Fast', 1, 'Goooooooooooooooood', 1, 0, 0),
(3, 'Light', 2, 'Soo light', 1, 0, 0);

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
`id_post` INT( 12 ) NOT NULL AUTO_INCREMENT ,
`id_user` INT( 12 ) NOT NULL ,
`id_forum` INT( 12 ) NOT NULL ,
`parent_id` INT( 12 ) NOT NULL ,
`status` INT( 2 ) NOT NULL DEFAULT '1',
`subject` VARCHAR( 255 ) NOT NULL ,
`message` TEXT NOT NULL ,
`date_created` DATETIME NOT NULL ,
`date_modified` DATETIME NOT NULL ,
`viewed` INT( 12 ) NOT NULL,
PRIMARY KEY ( `id_post` ) ,
INDEX ( `id_user` , `id_forum` , `parent_id` , `status`)
);


 INSERT INTO `posts` (
`id_post` ,
`id_user` ,
`id_forum` ,
`parent_id` ,
`status` ,
`subject` ,
`message` ,
`date_created` ,
`date_modified` ,
`viewed`
)
VALUES (
NULL , '1', '1', '1', '1', 'My First post', 'If you read this post you can conclude that your installation is complet. You can now remove this post and start a new life ;)', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0
);


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=6 ;

--
-- Contenu de la table `jacl2_group`
--

INSERT INTO `jacl2_group` (`id_aclgrp`, `name`, `grouptype`, `ownerlogin`) VALUES
(1, 'admins', 0, NULL),
(2, 'users', 0, NULL),
(3, 'havefnu', 2, 'havefnu');

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_rights`
--

DROP TABLE IF EXISTS `jacl2_rights`;
CREATE TABLE IF NOT EXISTS `jacl2_rights` (
  `id_aclsbj` varchar(100) NOT NULL DEFAULT '',
  `id_aclgrp` int(11) NOT NULL DEFAULT '0',
  `id_aclres` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_aclsbj`,`id_aclgrp`,`id_aclres`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
('hfnu.admin.category.create', 1, ''),
('hfnu.admin.category.delete', 1, ''),
('hfnu.admin.category.edit', 1, ''),
('hfnu.admin.config.edit', 1, ''),
('hfnu.admin.config.view', 1, ''),
('hfnu.admin.forum.create', 1, ''),
('hfnu.admin.forum.delete', 1, ''),
('hfnu.admin.forum.edit', 1, ''),
('hfnu.admin.index', 1, ''),
('hfnu.admin.member.ban', 1, ''),
('hfnu.admin.member.create', 1, ''),
('hfnu.admin.member.delete', 1, ''),
('hfnu.admin.member.edit', 1, ''),
('hfnu.admin.rank.create', 1, ''),
('hfnu.admin.rank.delete', 1, ''),
('hfnu.admin.rank.edit', 1, ''),
('hfnu.admin.report.delete', 1, ''),
('hfnu.admin.report.list', 1, ''),
('hfnu.category.list', 1, ''),
('hfnu.category.view', 1, ''),
('hfnu.forum.list', 1, ''),
('hfnu.forum.view', 1, ''),
('hfnu.member.list', 1, ''),
('hfnu.member.search', 1, ''),
('hfnu.member.view', 1, ''),
('hfnu.posts.create', 1, ''),
('hfnu.posts.delete', 1, ''),
('hfnu.posts.edit', 1, ''),
('hfnu.posts.list', 1, ''),
('hfnu.posts.moderate', 1, ''),
('hfnu.posts.quote', 1, ''),
('hfnu.posts.reply', 1, ''),
('hfnu.posts.report', 1, ''),
('hfnu.posts.view', 1, ''),
('hfnu.search', 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_subject`
--

DROP TABLE IF EXISTS `jacl2_subject`;
CREATE TABLE IF NOT EXISTS `jacl2_subject` (
  `id_aclsbj` varchar(100) NOT NULL DEFAULT '',
  `label_key` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_aclsbj`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jacl2_subject`
--

INSERT INTO `jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('acl.user.view', 'jelix~acl2db.acl.user.view'),
('acl.user.modify', 'jelix~acl2db.acl.user.modify'),
('acl.group.modify', 'jelix~acl2db.acl.group.modify'),
('acl.group.create', 'jelix~acl2db.acl.group.create'),
('acl.group.delete', 'jelix~acl2db.acl.group.delete'),
('acl.group.view', 'jelix~acl2db.acl.group.view'),
('auth.users.list', 'jelix~auth.acl.users.list'),
('auth.users.view', 'jelix~auth.acl.users.view'),
('auth.users.modify', 'jelix~auth.acl.users.modify'),
('auth.users.create', 'jelix~auth.acl.users.create'),
('auth.users.delete', 'jelix~auth.acl.users.delete'),
('auth.users.change.password', 'jelix~auth.acl.users.change.password'),
('auth.user.view', 'jelix~auth.acl.user.view'),
('auth.user.modify', 'jelix~auth.acl.user.modify'),
('auth.user.change.password', 'jelix~auth.acl.user.change.password'),
('hfnu.category.view', 'hfnu~acl2.category.view'),
('hfnu.category.list', 'hfnu~acl2.category.list'),
('hfnu.forum.view', 'hfnu~acl2.forum.view'),
('hfnu.forum.list', 'hfnu~acl2.forum.list'),
('hfnu.member.view', 'hfnu~acl2.member.view'),
('hfnu.member.list', 'hfnu~acl2.member.list'),
('hfnu.member.search', 'hfnu~acl2.member.search'),
('hfnu.search', 'hfnu~acl2.search'),
('hfnu.posts.view', 'hfnu~acl2.posts.view'),
('hfnu.posts.list', 'hfnu~acl2.posts.list'),
('hfnu.posts.create', 'hfnu~acl2.posts.create'),
('hfnu.posts.edit', 'hfnu~acl2.posts.edit'),
('hfnu.posts.delete', 'hfnu~acl2.posts.delete'),
('hfnu.posts.report', 'hfnu~acl2.posts.report'),
('hfnu.posts.reply', 'hfnu~acl2.posts.reply'),
('hfnu.posts.quote', 'hfnu~acl2.posts.quote'),
('hfnu.posts.moderate', 'hfnu~acl2.posts.moderate'),
('hfnu.admin.index', 'hfnu~acl2.admin.index'),
('hfnu.admin.config.view', 'hfnu~acl2.admin.config.view'),
('hfnu.admin.config.edit', 'hfnu~acl2.admin.config.edit'),
('hfnu.admin.forum.create', 'hfnu~acl2.admin.forum.create'),
('hfnu.admin.forum.delete', 'hfnu~acl2.admin.forum.delete'),
('hfnu.admin.forum.edit', 'hfnu~acl2.admin.forum.edit'),
('hfnu.admin.category.create', 'hfnu~acl2.admin.category.create'),
('hfnu.admin.category.edit', 'hfnu~acl2.admin.category.edit'),
('hfnu.admin.category.delete', 'hfnu~acl2.admin.category.delete'),
('hfnu.admin.member.create', 'hfnu~acl2.admin.member.create'),
('hfnu.admin.member.edit', 'hfnu~acl2.admin.member.edit'),
('hfnu.admin.member.delete', 'hfnu~acl2.admin.member.delete'),
('hfnu.admin.member.ban', 'hfnu~acl2.admin.member.ban'),
('hfnu.admin.rank.create', 'hfnu~acl2.admin.rank.create'),
('hfnu.admin.rank.edit', 'hfnu~acl2.admin.rank.edit'),
('hfnu.admin.rank.delete', 'hfnu~acl2.admin.rank.delete'),
('hfnu.admin.report.list', 'hfnu~acl2.admin.report.list'),
('hfnu.admin.report.delete', 'hfnu~acl2.admin.report.delete');

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_user_group`
--

DROP TABLE IF EXISTS `jacl2_user_group`;
CREATE TABLE IF NOT EXISTS `jacl2_user_group` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_aclgrp` int(11) NOT NULL DEFAULT '0',
  KEY `login` (`login`,`id_aclgrp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jacl2_user_group`
--

INSERT INTO `jacl2_user_group` (`login`, `id_aclgrp`) VALUES
('havefnu', 1),
('havefnu', 3);

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
  PRIMARY KEY (`member_login`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `member`
--

INSERT INTO `member`
(`id_user`, `member_login`, `member_password`, `member_email`, `member_nickname`,
`member_website`, `member_status`, `member_keyactivate`, `member_request_date`,
`member_firstname`, `member_birth`, `member_country`, `member_town`, `member_comment`,
`member_avatar`, `member_xfire`, `member_icq`, `member_hotmail`, `member_yim`, `member_gtalk`,
`member_jabber`, `member_proc`, `member_mb`, `member_card`, `member_ram`, `member_display`,
`member_screen`, `member_mouse`, `member_keyb`, `member_os`, `member_connection`,
`member_last_connect`) VALUES
(1, 'havefnu', '0dc12261c353a4c2dfa1b6e01ded9bed', 'havefnu@foxmask.info', 'havefnu', 'http://forge.jelix.org/projects/havefnubb', 2,
NULL, CURRENT_TIMESTAMP(), NULL, '1969-01-14', 'France', 'Paris', '', '0', NULL, NULL, NULL,
NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
CURRENT_TIMESTAMP());