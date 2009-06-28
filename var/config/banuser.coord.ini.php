;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

; What to do if a user is banned 
; 1 = generate an error. This value should be set for web services (xmlrpc, jsonrpc...)
; 2 = redirect to an action
on_error = 2

; action to execute when a user is banned when on_error=2
on_error_action = "havefnubb~banuser:index"