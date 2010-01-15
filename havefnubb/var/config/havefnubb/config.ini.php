;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=havefnubb
startAction="default:index"

checkTrustedModules=off

; list of modules : module,module,module
trustedModules="havefnubb,hfnuadmin,hfnucontact,hfnuinstall,hfnurates,hfnusearch,hfnuthemes,jcommunity,jmessenger,jtags,servinfo,jacl2db_admin,jauthdb_admin,master_admin,jauth,jacl2"

pluginsPath="app:plugins/"

modulesPath="lib:jelix-modules/,app:modules/,app:../modules-hook/"
theme=default

[coordplugins]
autolocale = "autolocale.coord.ini.php"
hfnuinstalled="havefnubb/hfnuinstalled.coord.ini.php"
auth="havefnubb/auth.coord.ini.php"
jacl2="havefnubb/jacl2.coord.ini.php"
banuser="havefnubb/banuser.coord.ini.php"
timeout="havefnubb/timeout.coord.ini.php"
history="havefnubb/history.coord.ini.php"
flood="havefnubb/flood.coord.ini.php"

[responses]
html=fnuHtmlResponse

[urlengine]
; name of url engine :  "simple" or "significant"
; engine=simple
; engine=basic_significant
engine=significant

enableParser=on
multiview=on

defaultEntrypoint=forums
entrypointExtension=.php

[acl2]
driver=db
