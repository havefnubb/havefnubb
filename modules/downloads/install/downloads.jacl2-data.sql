--
-- Contenu de la table `jacl2_rights`
--
INSERT INTO `jacl2_rights` (`id_aclsbj`, `id_aclgrp`, `id_aclres`) VALUES
('downloads.index', 1, ''),
('downloads.list', 1, ''),
('downloads.config', 1, ''),
('downloads.edit', 1, ''),
('downloads.delete', 1, '');
-- --------------------------------------------------------

--
-- Contenu de la table `jacl2_subject`
--
INSERT INTO `jacl2_subject` (`id_aclsbj`, `label_key`) VALUES
('downloads.index', 'downloads~acl2.downloads.index'),
('downloads.list', 'downloads~acl2.downloads.list'),
('downloads.config', 'downloads~acl2.downloads.config'),
('downloads.edit', 'downloads~acl2.downloads.edit'),
('downloads.delete', 'downloads~acl2.downloads.delete');