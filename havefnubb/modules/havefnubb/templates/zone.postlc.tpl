{if $msg == ''}
<a href="{jurl 'havefnubb~posts:viewtogo',
    array('id_post'=>$post->id_last_msg,
        'parent_id'=>$post->parent_id,
        'id_forum'=>$post->id_forum,
        'ftitle'=>$post->forum_name,
        'ptitle'=>$title,
        'go'=>$post->id_last_msg)}#p{$post->id_last_msg}"
   title="{@havefnubb~main.goto_this_message@}">{$post->date_last_post|jdatetime:'timestamp':'lang_datetime'}</a> {@havefnubb~main.by@} <a href="{jurl 'jcommunity~account:show',array('user'=>$user->login)}" title="{$user->nickname|eschtml}">{$user->nickname|eschtml}</a>
{else}
{$msg}
{/if}
