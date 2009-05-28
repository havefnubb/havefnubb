DROP TABLE IF EXISTS `hf_search_words`;
CREATE TABLE IF NOT EXISTS `hf_search_words` (
  `id` varchar(30) NOT NULL,
  `datasource` varchar(40),
  `words` varchar(255) NOT NULL,
  `weight` int(4) NOT NULL,
  KEY `words` (`words`),
  PRIMARY KEY source_id (id,datasource,words)
)DEFAULT CHARSET=utf8;

INSERT INTO `jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('hfnu.search', 0, ''),
('hfnu.search', 1, ''),
('hfnu.search', 2, ''),
('hfnu.search', 3, ''),
('hfnu.admin.search', 0,''),
('hfnu.admin.search', 1,''),
('hfnu.admin.search', 2,''),
('hfnu.admin.search', 3,'');

INSERT INTO `jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('hfnu.search', 'havefnubb~acl2.search'),
('hfnu.admin.search', 'havefnubb~acl2.admin.search');