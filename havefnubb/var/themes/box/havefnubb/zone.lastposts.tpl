<div id="lastposts" class="col">
    <div class="headings_footer">
        <h3><span>{@havefnubb~main.last.messages@}</span></h3>
    </div>
{foreach $lastPost as $post}
    <p class="footer_para"><a href="{jurl 'havefnubb~posts:view',array('id_post'=>$post->id_post,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ptitle'=>$post->subject,'ftitle'=>$post->forum_name)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
    {@havefnubb~main.by@} <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a>, 
    {$post->date_modified|jdatetime:'timestamp':'lang_datetime'}</p>
{/foreach}
</div>