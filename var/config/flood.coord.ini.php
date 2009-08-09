;<?php die(''); ?>
;for security reasons , don't remove or modify the first line
elapsed_time_between_two_post_by_same_ip=60

elapsed_time_after_posting_before_editing=60

; What to do if a right is required but the user has not this right
; 1 = generate an error. This value should be set for web services (xmlrpc, jsonrpc...)
; 2 = redirect to an action
on_error=2

; action to execute on a missing authentification when on_error=2
on_error_action_same_ip="havefnubb~flood:sameip"
on_error_action_editing="havefnubb~flood:editing"








