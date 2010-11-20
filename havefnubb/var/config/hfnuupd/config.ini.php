;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=hfnuupd
startAction="default:index"

modulesPath="lib:jelix-modules/,app:admin-modules/,app:modules/,app:../modules-hook/"

[sessions]
; to disable sessions, set the following parameter to 0
start=0

[coordplugins]
autolocale=autolocale.coord.ini.php
auth="hfnuupd/auth.coord.ini.php"

[responses]
html=fnuUpdHtmlResponse

[acl2]
driver=db
[logfiles]
DEBUG=hfnuupd.log

[modules]
jelix.access=2

master_admin.access=0
jacl2db_admin.access=0
jauthdb_admin.access=0

jacl2db.access=2
jauth.access=2
jauthdb.access=1

havefnubb.access=2
hfnuadmin.access=1
hfnucal.access=2
hfnucontact.access=2
hfnuhardware.access=2
hfnuim.access=2
hfnurates.access=2
hfnusearch.access=2
hfnuthemes.access=2
jcommunity.access=2
jmessenger.access=2
jtags.access=2
servinfo.access=0

hook.access=1

modulesinfo.access=0

jelixcache.access=0

hfnuupd.access=2
