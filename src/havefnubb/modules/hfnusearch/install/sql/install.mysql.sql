
CREATE TABLE IF NOT EXISTS `%%PREFIX%%hfnu_search_words` (
  `id` varchar(30) NOT NULL,
  `datasource` varchar(40),
  `words` varchar(255) NOT NULL,
  `weight` int(4) NOT NULL,
  KEY `words` (`words`),
  PRIMARY KEY source_id (id,datasource,words)
)DEFAULT CHARSET=utf8;

