{hook 'hfbBeforeStats'}
<div class="block-col">
    <h3>{@havefnubb~main.stats@}</h3>
    {hook 'hfbStats'}
    <ul>
        <li>
        {if $posts > 1}{$posts} {@havefnubb~main.messages@}{elseif $posts == 1}{@havefnubb~main.one.message@}{else}{@havefnubb~main.no.message@}{/if}
        {if $threads > 1}{jlocale 'havefnubb~main.in.threads', array($threads)}{elseif $threads ==1}{@havefnubb~main.in.one.thread@}{/if}
        {if $members > 1}{jlocale 'havefnubb~main.posted.by.members' , array($members)}{elseif $members ==1}{@havefnubb~main.posted.by.one.member@}{/if}
        </li>
        <li>{@havefnubb~main.last.post@} : <a href="{jurl 'havefnubb~posts:viewtogo',array('id_post'=>$lastPost->id_post,     'thread_id'=>$lastPost->thread_id,'id_forum'=>$lastPost->id_forum,'ptitle'=>$lastPost->subject,'ftitle'=>$forum->forum_name,'go'=>$lastPost->id_last_msg)}#p{$lastPost->id_post}" title="{@havefnubb~main.goto_this_message@}">{$lastPost->subject|eschtml}</a></li>
        <li>{@havefnubb~main.last.member@} : <a href="{jurl 'jcommunity~account:show',array('user'=>$lastMember->login)}" title="{jlocale 'havefnubb~member.memberlist.profile.of', array($lastMember->login)}">{$lastMember->nickname|eschtml}</a></li>
    </ul>
</div>
{hook 'hfbAfterStats'}
