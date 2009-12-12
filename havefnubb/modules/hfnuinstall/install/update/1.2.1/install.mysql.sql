ALTER TABLE `hf_forum` ADD `post_expire` INT( 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `hf_posts` ADD `censored_msg` VARCHAR( 50 ) NULL ;
ALTER TABLE `hf_posts` ADD `read_by_mod` INT(1) DEFAULT '0' ;

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

