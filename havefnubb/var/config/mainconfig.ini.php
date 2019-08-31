;<?php die(''); ?>
;for security reasons , don't remove or modify the first line
;this file doesn't list all possible properties. See lib/jelix/core/defaultconfig.ini.php for that


; IF YOU WANT TO MODIFY THIS FILE, PUT NEW VALUES INTO localconfig.ini.php



locale=fr_FR
charset=UTF-8
availableLocales="fr_FR,en_US"
fallbackLocale=en_US


theme=default

; see http://www.php.net/manual/en/timezones.php for supported values
timeZone="Europe/Paris"


modulesPath="lib:jelix-admin-modules/,lib:jelix-modules/,app:modules/,app:admin-modules/,app:../modules-hook/"
pluginsPath="app:plugins/,module:jacl2db/plugins,module:jacl2/plugins"

[modules]
jelix.access=2

master_admin.access=1
jacl2db_admin.access=1
jauthdb_admin.access=1

jacl2.access=1
jacl2db.access=1
jacldb.access=0
jauth.access=2
jauthdb.access=1
jsoap.access=0

havefnubb.access=2
hfnucal.access=1
hfnucal.installparam=nocopyfiles
hfnucontact.access=2
hfnuhardware.access=1
hfnuim.access=1
hfnurates.access=2
hfnurates.installparam=nocopyfiles
hfnusearch.access=2
hfnusearch.installparam=nocopyfiles
hfnuthemes.access=2
jcommunity.access=2
jcommunity.installparam="defaultuser;manualconfig"
jmessenger.access=2
jtags.access=2

hook.access=1

hfnuadmin.access=1

;servinfo.access=2

[coordplugins]
auth="havefnubb/auth.coord.ini.php"

[tplplugins]
defaultJformsBuilder=html


[jResponseHtml]
minifyEntryPoint=minify.php
;concatene et compress les fichier CSS
minifyCSS=on
;concatene et compress les fichier JS
minifyJS=off

; check all filemtime() of original js files to check if minify's cache should be generated again.
; Should be set to "off" on production servers (i.e. manual empty cache needed when a file is changed) :
minifyCheckCacheFiletime=off


[error_handling]
messageLogFormat="%date%\t%ip%\t[%code%]\t%msg%\t%file%\t%line%\n\t%url%\n%params%\n%trace%\n\n"
errorMessage="Une erreur technique est survenue. Désolé pour ce désagrément."

[compilation]
checkCacheFiletime=on
force=off

[urlengine]
; name of url engine :  "simple" or "significant"
; engine=simple
; engine=basic_significant
engine=significant

; enable the parsing of the url. Set it to off if the url is already parsed by another program
; (like mod_rewrite in apache), if the rewrite of the url corresponds to a simple url, and if
; you use the significant engine. If you use the simple url engine, you can set to off.
enableParser=on

multiview=on

; basePath corresponds to the path to the base directory of your application.
; so if the url to access to your application is http://foo.com/aaa/bbb/www/index.php, you should
; set basePath = "/aaa/bbb/www/".
; if it is http://foo.com/index.php, set basePath="/"
; Jelix can guess the basePath, so you can keep basePath empty. But in the case where there are some
; entry points which are not in the same directory (ex: you have two entry point : http://foo.com/aaa/index.php
; and http://foo.com/aaa/bbb/other.php ), you MUST set the basePath (ex here, the higher entry point is index.php so
; : basePath="/aaa/" )
basePath=

; backendBasePath is used when the application is behind a proxy, and when the base path on the frontend
; server doesn't correspond to the base path on the backend server.
; you MUST define basePath when you define backendBasePath
backendBasePath=


; this is the url path to the jelix-www content (you can found this content in lib/jelix-www/)
; because the jelix-www directory is outside the yourapp/www/ directory, you should create a link to
; jelix-www, or copy its content in yourapp/www/ (with a name like 'jelix' for example)
; so you should indicate the relative path of this link/directory to the basePath, or an absolute path.
; if you change it, change also all pathes in [htmleditors]
; at runtime, it contains the absolute path (basePath+the value) if you give a relative path
jelixWWWPath="jelix/"
jqueryPath="jelix/jquery/"


defaultEntrypoint=forums

; leave empty to have jelix error messages
notfoundAct="havefnubb~hfnuerror:notfound"
;notfoundAct = "jelix~error:notfound"

[simple_urlengine_entrypoints]
; parameters for the simple url engine. This is the list of entry points
; with list of actions attached to each entry points

; script_name_without_suffix = "list of action selectors separated by a space"
; selector syntax :
;   m~a@r    -> for the action "a" of the module "m" and for the request of type "r"
;   m~*@r    -> for all actions of the module "m" and for the request of type "r"
;   @r       -> for all actions for the request of type "r"

forums="@classic"

; hfnuadmin="jacl2db~*@classic jauth~*@classic jacl2db_admin~*@classic jauthdb_admin~*@classic master_admin~*@classic hfnuadmin~*@classic servinfo~default@classic hfnusearch~admin@classic, hfnupoll~admin@classic hfnucontact~admin@classic downloads~mgr:index@classic downloads~mgr:manage@classic downloads~mgr:config@classic downloads~mgr:dls@classic"
hfnuadmin="jacl2db~*@classic jauth~*@classic jacl2db_admin~*@classic jauthdb_admin~*@classic master_admin~*@classic hfnuadmin~*@classic servinfo~*@classic activeusers_admin~*@classic"

[basic_significant_urlengine_entrypoints]
; for each entry point, it indicates if the entry point name
; should be include in the url or not
forums=on
hfnuadmin=on
install=on
minify=on


[mailer]
webmasterEmail="webmaster@domain.com"
webmasterName=webmaster

; how to send mail : "mail" (mail()), "sendmail" (call sendmail), or "smtp" (send directly to a smtp)
mailerType=mail
; Sets the hostname to use in Message-Id and Received headers
; and as default HELO string. If empty, the value returned
; by SERVER_NAME is used or 'localhost.localdomain'.
hostname=
sendmailPath="/usr/sbin/sendmail"

; if mailer = smtp , fill the following parameters

; SMTP hosts.  All hosts must be separated by a semicolon : "smtp1.example.com:25;smtp2.example.com"
smtpHost=localhost
; default SMTP server port
smtpPort=
; SMTP HELO of the message (Default is hostname)
smtpHelo=
; SMTP authentication
smtpAuth=off
smtpUsername=
smtpPassword=
; SMTP server timeout in seconds
smtpTimeout=10


[acl2]
driver=db


[sessions]
; to disable sessions, set the following parameter to 0
start=1
; You can change the session name by setting the following parameter (only accepts alpha-numeric chars) :
; name = "mySessionName"
; Use alternative storage engines for sessions
;
; usage :
;
; storage = "files"
; files_path = "app:var/sessions/"
;
; or
;
; storage = "dao"
; dao_selector = "jelix~jsession"
; dao_db_profile = ""


[forms]
; define input type for datetime widgets : "textboxes" or "menulists"
controls.datetime.input=menulists
; define the way month labels are displayed widgets: "numbers", "names" or "shortnames"
controls.datetime.months.labels=names
; define the default config for datepickers in jforms
datepicker=default

[datepickers]
default="jelix/js/jforms/datepickers/default/init.js"

[wikieditors]
default.engine.name=wr3
default.wiki.rules=wr3_to_xhtml
; path to the engine file
default.engine.file="jelix/markitup/jquery.markitup.js"
; define the path to the "internationalized" file to translate the label of each button
default.config.path="jelix/markitup/sets/wr3/"
; define the path to the image of buttons of the toolbar
default.image.path="jelix/markitup/sets/wr3/images/"
default.skin="jelix/markitup/skins/simple/style.css"

[havefnubb]
title="HaveFnuBB!"
description="Where Everything is Fnu"
version=1.5.1
rules=
admin_email="admin@localhost.net"
url_check_version="https://havefnubb.jelix.org/last_version"
avatar_max_width=60
avatar_max_height=75
important_nb_views=100
important_nb_replies=100
posts_per_page=10
replies_per_page=10
members_per_page=25
stats_nb_of_lastpost=3
post_max_size=0
; if the hfnuadmin module is in an other web site or application, set its url here
admin_url=
keywords="Forum, Community, PHP5, Jelix"

[hfnucontact]
to_contact=
email_contact=

[social_networks]
twitter=1
digg=1
delicious=1
facebook=1
reddit=1
netvibes=1
images_path="images/social-network"

; set here the name of the directory that contains the smileys in the dir hfnu/images/smileys/
[smileys_pack]
name=famfamfam
; the available smileys are :
; emoticon_evilgrin.png
; emoticon_grin.png
; emoticon_happy.png
; emoticon_smile.png
; emoticon_surprised.png
; emoticon_tongue.png
; emoticon_unhappy.png
; emoticon_waii.png
; emoticon_wink.png
; so your own smileys would have to be named like them
