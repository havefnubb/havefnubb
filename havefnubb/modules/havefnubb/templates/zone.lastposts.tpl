<div class="headings">
    <h3><span>{@havefnubb~main.last.messages@}</span></h3>
</div>
<div id="lastposts">
<ul>    
{foreach $lastPost as $post}
    <li class="left"><a href="{jurl 'havefnubb~posts:view',array('id_post'=>$post->id_post,'parent_id'=>$post->parent_id,'ptitle'=>$post->subject,'ftitle'=>$post->forum_name)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
    {@havefnubb~main.by@} <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a></li>
    <li class="right">{$post->date_modified|jdatetime:'db_datetime':'lang_datetime'}</li>
{/foreach}
</ul> 
</div>