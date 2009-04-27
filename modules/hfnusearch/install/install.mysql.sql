DROP TABLE IF EXISTS `search_words`;
CREATE TABLE IF NOT EXISTS `search_words` (
  `id` int(12) NOT NULL,
  `words` varchar(255) NOT NULL,
  `weight` int(4) NOT NULL,
  KEY `words` (`words`)
);

INSERT INTO `jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('hfnu.search', 0, ''),
('hfnu.search', 1, ''),
('hfnu.search', 2, ''),
('hfnu.search', 3, '');

INSERT INTO `jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('hfnu.search', 'havefnubb~acl2.search');