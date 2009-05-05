<div id="stats" class="col">
    <div class="headings_footer">
        <h3><span>{@havefnubb~main.stats@}</span></h3>
    </div>
    <p class="footer_para">
{$posts} {@havefnubb~main.messages@} {jlocale 'havefnubb~main.in.threads', array($threads)} 
{jlocale 'havefnubb~main.posted.by.members' , array($members)}<br/>
{@havefnubb~main.last.post@} : <a href="{jurl 'havefnubb~posts:view',array('id_post'=>$lastPost->parent_id,'parent_id'=>$lastPost->parent_id,'id_forum'=>$lastPost->id_forum,'ptitle'=>$lastPost->subject,'ftitle'=>$forum->forum_name)}" title="{@havefnubb~main.goto.this.message@}">{$lastPost->subject|eschtml}</a><br/>
{@havefnubb~main.last.member@} : <a href="{jurl 'jcommunity~account:show',array('user'=>$lastMember->login)}" title="{$lastMember->login|eschtml}">{$lastMember->login|eschtml}</a>
   </p>
</div>