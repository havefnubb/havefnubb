;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

[coordplugins]
autolocale=1
auth="hfnuadmin/auth.coord.ini.php"
jacl2=1

[responses]
html=adminHtmlResponse
htmlauth=adminLoginHtmlResponse

[autolocale]
availableLanguageCode="fr_FR,en_US"

;active la detection du changement de langage via l'url fournie
enableUrlDetection=on

;indique le nom du parametre url qui contient la langue choisie par l'utilisateur
urlParamNameLanguage=lang


; utilisation du langage indiqué dans le navigateur
useDefaultLanguageBrowser=on

[acl2]
enableAcl2DbEventListener=on

[activeusers_admin]
pluginconf="havefnubb/activeusers.coord.ini.php"

[jacl2]
; What to do if a right is required but the user has not this right
; 1 = generate an error. This value should be set for web services (xmlrpc, jsonrpc...)
; 2 = redirect to an action
on_error=2

; locale key for the error message when on_error=1
error_message="jelix~errors.acl.action.right.needed"

; action to execute on a missing authentification when on_error=2
on_error_action="jelix~error:badright"
