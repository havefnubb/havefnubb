DROP TABLE IF EXISTS `hf_subscriptions`;
CREATE TABLE IF NOT EXISTS `hf_subscriptions` (
	`id_user` int(12) NOT NULL,
	`id_post` int(12) NOT NULL,
	PRIMARY KEY (`id_user` , `id_post`)
) DEFAULT CHARSET=utf8;