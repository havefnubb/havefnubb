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

[modules]
hfnucal.installparam=nocopyfiles
hfnurates.installparam=nocopyfiles
hfnusearch.installparam=nocopyfiles

jelix.enabled=on
master_admin.enabled=on
jacl2db_admin.enabled=on
jauthdb_admin.enabled=on
jacl2.enabled=on
jacl2db.enabled=on
jacldb.enabled=off
jauth.enabled=off
jauthdb.enabled=off
jsoap.enabled=off
havefnubb.enabled=on
hfnucal.enabled=on
hfnucontact.enabled=on
hfnuhardware.enabled=on
hfnuim.enabled=on
hfnurates.enabled=on
hfnusearch.enabled=on
hfnuthemes.enabled=on
jcommunity.enabled=on
jmessenger.enabled=on
jtags.enabled=on
hook.enabled=on
hfnuadmin.enabled=on
activeusers.enabled=on
servinfo.enabled=on
modulesinfo.enabled=on
jelixcache.enabled=on
activeusers_admin.enabled=on
jelix.installparam[wwwfiles]=copy
jsitemap.enabled=on

jcommunity.installparam[manualconfig]=on
jcommunity.installparam[masteradmin]=on
jcommunity.installparam[defaultuser]=on
jcommunity.installparam[migratejauthdbusers]=off
jcommunity.installparam[eps]="[forums]"

[coordplugins]
auth="havefnubb/auth.coord.ini.php"

[tplplugins]
defaultJformsBuilder=html


[jResponseHtml]


[error_handling]
messageLogFormat="%date%\t%ip%\t[%code%]\t%msg%\t%file%\t%line%\n\t%url%\n%params%\n%trace%\n\n"
errorMessage="Une erreur technique est survenue. Désolé pour ce désagrément."

[compilation]
checkCacheFiletime=on
force=off

[urlengine]

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



; leave empty to have jelix error messages
notfoundAct="havefnubb~hfnuerror:notfound"

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

[wikieditors]
default.engine.name=wr3
default.wiki.rules=wr3_to_xhtml

[havefnubb]
title="HaveFnuBB!"
description="Where Everything is Fnu"
version=2.0.0-pre
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

[webassets]
useCollection=common

[webassets_common]

havefnubb.css[] = "$theme/css/app.css"
havefnubb.css[] = "$theme/css/hfnu.css"
havefnubb.css[] = "$theme/css/nav.css"
havefnubb.css[] = "$theme/css/theme.css"
havefnubb.require = jquery

hfnuadmin.css[] = "$jelix/design/master_admin.css"
hfnuadmin.css[] = "$theme/css/hfnuadmin.css"
hfnuadmin.css[] = "$jelix/design/jacl2.css"
hfnuadmin.css[] = "$jelix/design/jform.css"
hfnuadmin.css[] = "$jelix/design/records_list.css"
hfnuadmin.js[] = "hfnu/admin/hfnuadmin.js"
hfnuadmin.require = jquery,jqueryui

hfnuaccount.css[] = "$theme/css/tabnav.css"
hfnuaccount.js[] = "hfnu/js/accounts.js"
hfnuaccount.require = jqueryui

hfnumessenger.require = jquery

hfnucal.css[] = "$theme/css/hfnucal.css"

hfnurates.css[] = "hfnu/images/star-rating/jquery.rating.css"
hfnurates.js[] = "$jelix/jquery/include/jquery.include.js"
hfnurates.js[] = "hfnu/js/jquery.MetaData.js"
hfnurates.js[] = "hfnu/js/jquery.form.js"
hfnurates.js[] = "hfnu/js/jquery.rating.pack.js"
hfnurates.js[] = "hfnu/js/rates.js"
hfnurates.require = jquery

hfnusearch.css[] = "$theme/css/hfnusearch.css"
hfnusearch.css[] = "hfnu/js/jquery.autocomplete.css"
hfnusearch.js[] = "hfnu/js/jquery.autocomplete.pack.js"
hfnusearch.js[] = "hfnu/js/hfnusearch.js"
hfnusearch.require = jquery

hfnuthemes.css[] = "$theme/css/hfnuthemes.css"
hfnuthemes.require = jqueryui

[responses]
sitemap="jsitemap~jResponseSitemap"

[session]
storage=

[jcommunity]
loginResponse=html
verifyNickname=off
passwordChangeEnabled=on
accountDestroyEnabled=on
useJAuthDbAdminRights=off
registrationEnabled=on
resetPasswordEnabled=on
resetPasswordAdminEnabled=on
disableJPref=on
publicProperties[]=login
publicProperties[]=nickname
publicProperties[]=create_date


[banuser]
; What to do if a user is banned
; 1 = generate an error. This value should be set for web services (xmlrpc, jsonrpc...)
; 2 = redirect to an action
on_error=2

; action to execute when a user is banned when on_error=2
on_error_action="havefnubb~banuser:index"

[flood]
only_same_ip=0
time_interval=30

; What to do if a right is required but the user has not this right
; 1 = generate an error. This value should be set for web services (xmlrpc, jsonrpc...)
; 2 = redirect to an action
on_error=2

; action to execute on a missing authentification when on_error=2
on_error_action="havefnubb~flood:error"

elapsed_time_between_two_post=0


[jwiki]
defaultRules=hfb_rule
