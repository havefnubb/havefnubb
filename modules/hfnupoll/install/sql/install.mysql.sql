DROP TABLE IF EXISTS `hf_poll`;
CREATE TABLE IF NOT EXISTS `hf_poll` (
  `id_poll` int(12) NOT NULL,
  `question` varchar(255) NOT NULL,
  `date_created` int(12) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id_poll`)
) DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `hf_poll_answer`;
CREATE TABLE IF NOT EXISTS `poll_answer` (
  `id_answer` int(11) NOT NULL,
  `id_poll` int(11) NOT NULL,  
  `answer` varchar(255) NOT NULL,
  PRIMARY KEY (`id_answer`),
  KEY `id_poll` (`id_poll`)
) DEFAULT CHARSET=utf8;