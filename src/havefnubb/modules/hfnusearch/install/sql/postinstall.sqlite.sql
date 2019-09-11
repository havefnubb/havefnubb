INSERT INTO %%PREFIX%%jacl2_rights (id_aclsbj, id_aclgrp, id_aclres) VALUES
('hfnu.search', '__anonymous', ''),
('hfnu.search', 'admins', ''),
('hfnu.search', 'users', ''),
('hfnu.search', 'moderators', ''),
('hfnu.admin.search', '__anonymous',''),
('hfnu.admin.search', 'admins',''),
('hfnu.admin.search', 'users',''),
('hfnu.admin.search', 'moderators','');

INSERT INTO %%PREFIX%%jacl2_subject (id_aclsbj, label_key) VALUES
('hfnu.search', 'havefnubb~acl2.search'),
('hfnu.admin.search', 'havefnubb~acl2.admin.search');

