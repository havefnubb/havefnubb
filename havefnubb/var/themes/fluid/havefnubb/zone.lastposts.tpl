<div class="box">
    <h2>{@havefnubb~main.last.messages@}</h2>
    <div class="block">        
    <table>
{foreach $lastPost as $post}
    <tr>
        <td><a href="{jurl 'havefnubb~posts:view',array('id_post'=>$post->id_post,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ptitle'=>$post->subject,'ftitle'=>$post->forum_name)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a></td>
        <td class="lastposts-date">{$post->date_modified|jdatetime:'timestamp':'lang_datetime'}</td>
    </tr>
{/foreach}
    </table>
    </div>
</div>