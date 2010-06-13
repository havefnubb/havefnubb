;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=hfnuinstall
startAction="default:index"

modulesPath="lib:jelix-admin-modules/,lib:jelix-modules/,app:modules/,app:../modules-hook/"
pluginsPath="app:plugins/"



[coordplugins]
hfnuinstalled="havefnubb/hfnuinstalled.coord.ini.php"
autolocale=autolocale.coord.ini.php
auth="havefnubb/auth.coord.ini.php"

[responses]
html=installHtmlResponse

[simple_urlengine_entrypoints]
install="hfnuinstall~*@classic"

[urlengine]
; name of url engine :  "simple" or "significant"
; engine=simple
; engine=basic_significant
engine=significant
enableParser=on
multiview=on

[modules]
master_admin.access=1
jacl2db_admin.access=1
jauthdb_admin.access=1
jacl2db.access=2
hfnuhardware.access=2
hfnuinstall.access=2
hfnuim.access=2
