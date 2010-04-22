CREATE TABLE `hf_member_custom_fields` (
  `id_user` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY  (`id_user`,`type`)
) DEFAULT  CHARSET=utf8;

insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'im:icq', member_icq FROM hf_member WHERE member_icq <> '' AND member_icq IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'im:xfire', member_xfire FROM hf_member WHERE member_xfire <> '' AND member_xfire IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'im:yim', member_yim FROM hf_member WHERE member_yim <> '' AND member_yim IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'im:hotmail', member_hotmail FROM hf_member WHERE member_hotmail <> '' AND member_hotmail IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'im:aol', member_aol FROM hf_member WHERE member_aol <> '' AND member_aol IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'im:gtalk', member_gtalk FROM hf_member WHERE member_gtalk <> '' AND member_gtalk IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'im:jabber', member_jabber FROM hf_member WHERE member_jabber <> '' AND member_jabber IS NOT NULL;

insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:connection', member_connection FROM hf_member WHERE member_connection <> '' AND member_connection IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:os', member_os FROM hf_member WHERE member_os <> '' AND member_os IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:proc', member_proc FROM hf_member WHERE member_proc <> '' AND member_proc IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:ram', member_ram FROM hf_member WHERE member_ram <> '' AND member_ram IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:display', member_display FROM hf_member WHERE member_display <> '' AND member_display IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:screen', member_screen FROM hf_member WHERE member_screen <> '' AND member_screen IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:mouse', member_mouse FROM hf_member WHERE member_mouse <> '' AND member_mouse IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:keyb', member_keyb FROM hf_member WHERE member_keyb <> '' AND member_keyb IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:mb', member_mb FROM hf_member WHERE member_mb <> '' AND member_mb IS NOT NULL;
insert into `hf_member_custom_fields` (id_user, type, `data`)
 SELECT id_user, 'hw:card', member_card FROM hf_member WHERE member_card <> '' AND member_card IS NOT NULL;

alter table `hf_member` drop member_icq, drop member_xfire, drop member_yim,
drop member_hotmail, drop member_aol, drop member_gtalk, drop member_jabber,
drop member_connection, drop member_os, drop member_proc, drop member_ram, drop member_display,
drop member_screen, drop member_mouse, drop member_keyb, drop member_mb, drop member_card;
