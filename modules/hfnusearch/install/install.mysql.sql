DROP TABLE IF EXISTS `search_words`;
CREATE TABLE IF NOT EXISTS `search_words` (
  `id` int(12) NOT NULL,
  `words` varchar(255) NOT NULL,
  `weight` int(4) NOT NULL,
  KEY `words` (`words`)
);