;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=havefnubb
startAction="default:index"

[coordplugins]
hfnuinstalled="havefnubb/hfnuinstalled.coord.ini.php"
autolocale=autolocale.coord.ini.php
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
[modules]
jacl2db.access=2
hfnuhardware.access=2
servinfo.access=0
hfnuadmin.access=1
hfnuim.access=2
hfnucal.access=2
hook.access=2
