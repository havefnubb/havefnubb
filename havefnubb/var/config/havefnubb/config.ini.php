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

notfoundAct="havefnubb~error:notfound"
[acl2]
driver=db


[havefnubb]
title="HaveFnuBB!"
description="Where Everything is Fnu"
version=1.3.1
rules=
admin_email="admin@admin.net"
url_check_version="http://www.havefnubb.org/last_version"
avatar_max_width=60
avatar_max_height=75
installed=1

posts_per_page=10
replies_per_page=10
members_per_page=25
stats_nb_of_lastpost=3
post_max_size=0

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

;the rule of the forum to manage the render from wiki + smileys
[_pluginsPathList_wr_rules]
hfb_rule="havefnubb/plugins/rules/hfb_rule/"

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