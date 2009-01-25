;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=master_admin
startAction="default:index"

modulesPath="lib:jelix-admin-modules/,lib:jelix-modules/,app:modules/,app:../modules/"
[coordplugins]
;nom = file_ini_name or 1

auth="hfnuadmin/auth.coord.ini.php"
jacl2="hfnuadmin/jacl2.coord.ini.php"
[responses]

html=adminHtmlResponse
htmlauth=adminLoginHtmlResponse
[acl2]
enableAcl2DbEventListener=on
[simple_urlengine_entrypoints]
hfnuadmin="jacl2db~*@classic, jauth~*@classic, jacl2db_admin~*@classic, jauthdb_admin~*@classic, master_admin~*@classic, hfnuadmin~*@classic"
