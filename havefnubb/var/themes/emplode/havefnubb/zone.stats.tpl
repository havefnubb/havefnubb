{hook 'hfbBeforeStats'}
<div class="headings">
    <h3><span>{@havefnubb~main.stats@}</span></h3>
</div>
<div id="stats">
    {hook 'hfbStats'}
    <ul>
        <li>{$posts} {@havefnubb~main.messages@} {jlocale 'havefnubb~main.in.threads', array($threads)} {jlocale 'havefnubb~main.posted.by.members' , array($members)}</li>
        <li>{@havefnubb~main.last.post@} : <a href="{jurl 'havefnubb~posts:view',array('id_post'=>$lastPost->id_post,'parent_id'=>$lastPost->parent_id,'id_forum'=>$lastPost->id_forum,'ptitle'=>$lastPost->subject,'ftitle'=>$forum->forum_name)}#p{$lastPost->id_post}" title="{@havefnubb~main.goto_this_message@}">{$lastPost->subject|eschtml}</a></li>
        <li>{@havefnubb~main.last.member@} : <a href="{jurl 'jcommunity~account:show',array('user'=>$lastMember->login)}" title="{jlocale 'havefnubb~member.memberlist.profile.of', array($lastMember->login)}">{$lastMember->login|eschtml}</a></li>
    </ul>
</div>
{hook 'hfbAfterStats'}
