{if $msg == ''}
{$post->date_modified|jdatetime:'db_datetime':'lang_datetime'} {@main.by@} {$user->login|eschtml}
{else}
{$msg}
{/if}