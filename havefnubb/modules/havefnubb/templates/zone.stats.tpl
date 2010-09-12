{hook 'hfbBeforeStats'}
<div class="box">
    <h2>{@havefnubb~main.stats@}</h2>
    <div class="block">
    {hook 'hfbStats'}
    <ul>
        <li>{$posts} {@havefnubb~main.messages@} {jlocale 'havefnubb~main.in.threads', array($threads)} {jlocale 'havefnubb~main.posted.by.members' , array($members)}</li>
        <li>{@havefnubb~main.last.post@} : <a href="{jurl 'havefnubb~posts:viewtogo',array('id_post'=>$lastPost->id_first_msg,'parent_id'=>$lastPost->parent_id,'id_forum'=>$lastPost->id_forum,'ptitle'=>$lastPost->subject,'ftitle'=>$forum->forum_name,'go'=>$lastPost->id_last_msg)}#p{$lastPost->id_last_msg}" title="{@havefnubb~main.goto_this_message@}">{$lastPost->subject|eschtml}</a></li>
        <li>{@havefnubb~main.last.member@} : <a href="{jurl 'jcommunity~account:show',array('user'=>$lastMember->login)}" title="{jlocale 'havefnubb~member.memberlist.profile.of', array($lastMember->login)}">{$lastMember->nickname|eschtml}</a></li>
    </ul>
    </div>
</div>
{hook 'hfbAfterStats'}
