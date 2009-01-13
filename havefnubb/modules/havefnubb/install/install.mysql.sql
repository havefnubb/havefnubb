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

DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
`id_forum` INT( 12 ) NOT NULL AUTO_INCREMENT ,
`forum_name` VARCHAR( 255 ) NOT NULL,
`id_cat` INT( 12 ) NOT NULL ,
`forum_desc` VARCHAR( 255 ) NOT NULL ,
`forum_order` INT( 4 ) NOT NULL ,
PRIMARY KEY ( `id_forum` ) ,
INDEX ( `id_cat` )
);

INSERT INTO `forum` (`id_forum` ,`forum_name` ,`id_cat` ,`forum_desc` ,`forum_order`)
VALUES 
(NULL , 'My Forum is Fun', '1', 'Everything is Fnu', '1'), 
(NULL , 'My Forum is Fast', '1', 'Goooooooooooooooood', '1');

INSERT INTO `havefnu`.`forum` (`id_forum` ,`forum_name` ,`id_cat` ,`forum_desc` ,`forum_order`)VALUES (NULL , 'Light', '2', 'Soo light', '1');

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
CREATE TABLE `jacl2_group` (
  `id_aclgrp` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `grouptype` tinyint(4) NOT NULL DEFAULT '0',
  `ownerlogin` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_aclgrp`)
) AUTO_INCREMENT=4 ;

--
-- Contenu de la table `jacl2_group`
--
INSERT INTO `jacl2_group` (`id_aclgrp`, `name`, `grouptype`, `ownerlogin`) VALUES
(1, 'admins', 0, NULL),
(2, 'users', 0, NULL),
(3, 'havefnu', 2, 'havefnu');

--
-- Structure de la table `jacl2_rights`
--

DROP TABLE IF EXISTS `jacl2_rights`;
CREATE TABLE `jacl2_rights` (
  `id_aclsbj` varchar(100) NOT NULL DEFAULT '',
  `id_aclgrp` int(11) NOT NULL DEFAULT '0',
  `id_aclres` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_aclsbj`,`id_aclgrp`,`id_aclres`)
) ;

--
-- Contenu de la table `jacl2_rights`
--

INSERT INTO `jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('acl.group.create', 1, ''),
('acl.group.delete', 1, ''),
('acl.group.modify', 1, ''),
('acl.group.view', 1, ''),
('acl.user.modify', 1, ''),
('acl.user.view', 1, '');



--
-- Structure de la table `jacl2_subject`
--

DROP TABLE IF EXISTS `jacl2_subject`;
CREATE TABLE `jacl2_subject` (
  `id_aclsbj` varchar(100) NOT NULL DEFAULT '',
  `label_key` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_aclsbj`)
) ;

--
-- Contenu de la table `jacl2_subject`
--

INSERT INTO `jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('acl.user.view', 'jelix~acl2db.acl.user.view'),
('acl.user.modify', 'jelix~acl2db.acl.user.modify'),
('acl.group.modify', 'jelix~acl2db.acl.group.modify'),
('acl.group.create', 'jelix~acl2db.acl.group.create'),
('acl.group.delete', 'jelix~acl2db.acl.group.delete'),
('acl.group.view', 'jelix~acl2db.acl.group.view');


--
-- Structure de la table `jacl2_user_group`
--

DROP TABLE IF EXISTS `jacl2_user_group`;
CREATE TABLE `jacl2_user_group` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_aclgrp` int(11) NOT NULL DEFAULT '0',
  KEY `login` (`login`,`id_aclgrp`)
) ;

--
-- Contenu de la table `jacl2_user_group`
--

INSERT INTO `jacl2_user_group` (`login`, `id_aclgrp`) VALUES
('havefnu', 1),
('havefnu', 8);

-- --------------------------------------------------------


--
-- Structure de la table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id_user` int(12) NOT NULL AUTO_INCREMENT,
  `member_login` varchar(50) NOT NULL,
  `member_password` varchar(50) NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `member_nickname` varchar(50) DEFAULT NULL,
  `member_website` VARCHAR( 255 ) NULL,  
  `member_status` tinyint(4) NOT NULL DEFAULT '0',
  `member_keyactivate` varchar(10) DEFAULT NULL,
  `member_request_date` datetime DEFAULT NULL,
  `member_firstname` varchar(40) DEFAULT NULL,
  `member_birth` date NOT NULL DEFAULT '1980-01-01',
  `member_town` varchar(100) NOT NULL,
  `member_comment` varchar(255) NOT NULL,
  `member_avatar` varchar(255) NOT NULL,
  `member_last_connect` DATETIME NULL,
  PRIMARY KEY (`member_login`),
  UNIQUE KEY `id_user` (`id_user`)
) ;

--
-- Contenu de la table `member`
--

INSERT INTO `member`
(`id_user`,
`member_login`,
`member_password`,
`member_email`,
`member_nickname`,
`member_website`,
`member_status`,
`member_keyactivate`,
`member_firstname`,
`member_birth`,
`member_town`,
`member_comment`,
`member_avatar`,
`member_last_connect`) VALUES
(1,
'havefnu',
'0dc12261c353a4c2dfa1b6e01ded9bed',
'havefnu@foxmask.info',
'havefnu',
'',
1,
NULL,
NULL,
'1969-12-26',
'',
'',
'',
CURRENT_TIMESTAMP());
