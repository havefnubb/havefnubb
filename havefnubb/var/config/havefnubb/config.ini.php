;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=havefnubb
startAction="default:index"

; list of modules : module,module,module
trustedModules = "jauth,jacl2db,havefnubb,hfnucontact,hfnusearch,hfnurates,hfnuthemes,jcommunity,jmessenger,jtags,hook,hfnuim,hfnuhardware,hfnucal"
hiddenModules =  "hfnuadmin,hfnuinstall,jauthdb"
unusedModules = "jacldb,junittests,jWSDL,servinfo"

[coordplugins]
hfnuinstalled="havefnubb/hfnuinstalled.coord.ini.php"
autolocale = "autolocale.coord.ini.php"
auth="havefnubb/auth.coord.ini.php"
jacl2="havefnubb/jacl2.coord.ini.php"
banuser="havefnubb/banuser.coord.ini.php"
timeout="havefnubb/timeout.coord.ini.php"
history="havefnubb/history.coord.ini.php"
flood="havefnubb/flood.coord.ini.php"

[responses]
html=fnuHtmlResponse

[acl2]
driver=db

[logfiles]
DEBUG=havefnubbdebug.log