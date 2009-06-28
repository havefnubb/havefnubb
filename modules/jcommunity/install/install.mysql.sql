DROP TABLE IF EXISTS `community_users`;
CREATE TABLE IF NOT EXISTS `community_users` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nickname` varchar(50) default NULL,
  `status` tinyint(4) NOT NULL default '0',
  `keyactivate` varchar(10) default NULL,
  `request_date` datetime default NULL,
  PRIMARY KEY  (`login`),
  UNIQUE KEY `id` (`id`)
);