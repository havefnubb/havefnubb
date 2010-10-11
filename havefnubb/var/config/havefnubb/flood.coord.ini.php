;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

only_same_ip=1
time_interval=30

; What to do if a right is required but the user has not this right
; 1 = generate an error. This value should be set for web services (xmlrpc, jsonrpc...)
; 2 = redirect to an action
on_error=2

; action to execute on a missing authentification when on_error=2
on_error_action="havefnubb~flood:error"

elapsed_time_between_two_post=0










