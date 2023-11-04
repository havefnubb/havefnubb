;<?php die(''); ?>
;for security reasons , don't remove or modify the first line



[coordplugins]
autolocale=1
auth="havefnubb/auth.coord.ini.php"
jacl2=1
banuser=1
activeusers=1
history=1
flood=1

[responses]
html="\Havefnubb\Havefnubb\Response\HtmlResponse"
htmlauth="\Havefnubb\Havefnubb\Response\HtmlResponse"

[autolocale]
availableLanguageCode="fr_FR,en_US"

;active la detection du changement de langage via l'url fournie
enableUrlDetection=on

;indique le nom du parametre url qui contient la langue choisie par l'utilisateur
urlParamNameLanguage=lang


; utilisation du langage indiqué dans le navigateur
useDefaultLanguageBrowser=on


[jacl2]
; What to do if a right is required but the user has not this right
; 1 = generate an error. This value should be set for web services (xmlrpc, jsonrpc...)
; 2 = redirect to an action
on_error=2

; locale key for the error message when on_error=1
error_message="jelix~errors.acl.action.right.needed"

; action to execute on a missing authentification when on_error=2
; on_error_action = "jelix~error:badright"
on_error_action="havefnubb~hfnuerror:badright"

[history]
; session variable name
session_name=HFNUHISTO

; max size of the history
maxsize=8

; if single = true if each page will be registered only once in history
; the latter will be retained.
single=true

; If double = false pages will not appear twice a follow time in history.
double=false

; if time = true a variable record time spent on site
time=true
; the name of this variable
session_time_name=HFNUHISTOTIME



[jcommunity]
loginResponse=htmlauth
