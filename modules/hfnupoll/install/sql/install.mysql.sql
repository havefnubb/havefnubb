DROP TABLE IF EXISTS `hf_poll`;
CREATE TABLE IF NOT EXISTS `hf_poll` (
  `id_poll` int(12) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `date_created` int(12) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id_poll`)
) DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `hf_poll_answer`;
CREATE TABLE IF NOT EXISTS `poll_answer` (
  `id_answer` int(11) NOT NULL AUTO_INCREMENT,
  `id_poll` int(11) NOT NULL,  
  `answer` varchar(255) NOT NULL,
  PRIMARY KEY (`id_answer`),
  KEY `id_poll` (`id_poll`)
) DEFAULT CHARSET=utf8;


INSERT INTO `hf_jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('hfnu.admin.poll.list', 1, '');

INSERT INTO `hf_jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('hfnu.admin.poll.add', 1, '');

INSERT INTO `hf_jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('hfnu.admin.poll.edit', 1, '');

INSERT INTO `hf_jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('hfnu.admin.poll.delete', 1, '');

INSERT INTO `hf_jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('hfnu.admin.poll.list', 'hfnupoll~acl2.admin.poll.list');

INSERT INTO `hf_jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('hfnu.admin.poll.add', 'hfnupoll~acl2.admin.poll.add');

INSERT INTO `hf_jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('hfnu.admin.poll.edit', 'hfnupoll~acl2.admin.poll.edit');

INSERT INTO `hf_jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('hfnu.admin.poll.delete', 'hfnupoll~acl2.admin.poll.delete');