{if $msg == ''}
<a href="{jurl 'havefnubb~posts:view',array('id_post'=>$post->parent_id)}" title="{@havefnubb~main.goto.this.message@}">{$post->date_modified|jdatetime:'db_datetime':'lang_datetime'}</a> {@havefnubb~main.by@} <a href="{jurl 'jcommunity~account:show',array('user'=>$user->login)}" title="{$user->login|eschtml}">{$user->login|eschtml}</a>
{else}
{$msg}
{/if}