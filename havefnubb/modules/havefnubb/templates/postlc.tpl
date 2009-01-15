{if $msg == ''}
{$post->date_modified|jdatetime:'db_datetime':'lang_datetime'} {@havefnubb~main.by@} <a href="{jurl '',array('user'=>$user->login)}" title="{$user->login|eschtml}">{$user->login|eschtml}</a>
{else}
{$msg}
{/if}