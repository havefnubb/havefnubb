;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=master_admin
startAction="default:index"

modulesPath="lib:jelix-admin-modules/,lib:jelix-modules/,app:modules/,app:../modules-hook/"

[coordplugins]
autolocale=autolocale.coord.ini.php
auth="hfnuadmin/auth.coord.ini.php"
jacl2="hfnuadmin/jacl2.coord.ini.php"

[responses]
html=adminHtmlResponse
htmlauth=adminLoginHtmlResponse

[acl2]
enableAcl2DbEventListener=on

[simple_urlengine_entrypoints]
hfnuadmin="jacl2db~*@classic jauth~*@classic jacl2db_admin~*@classic jauthdb_admin~*@classic master_admin~*@classic hfnuadmin~*@classic servinfo~default@classic hfnusearch~*@classic hfnucontact~*@classic servinfo~*@classic hfnuthemes~*@classic"


[logfiles]
DEBUG=havefnubbdebug.log

[modules]
master_admin.access=2
jacl2db_admin.access=2
jauthdb_admin.access=2
jauth.access=1
hfnuhardware.access=2
jtags.access=1
servinfo.access=2
jcommunity.access=1
hfnuinstall.access=0
havefnubb.access=1
jmessenger.access=1
hfnuim.access=2
hfnurates.access=1
