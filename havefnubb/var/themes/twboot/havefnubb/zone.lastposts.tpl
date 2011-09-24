<div class="span5">
    <h3>{@havefnubb~main.last.messages@}</h3>
    <table class="zebra-striped">
{foreach $lastPost as $post}
        <tr>
            <td><a href="{jurl 'havefnubb~posts:viewtogo',array('id_post'=>$post->id_post,
                                                                'thread_id'=>$post->thread_id,
                                                                'id_forum'=>$post->id_forum,
                                                                'ptitle'=>$post->subject,
                                                                'ftitle'=>$post->forum_name,
                                                                'go'=>$post->id_last_msg)}#p{$post->id_last_msg}"
                    title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a></td>
            <td class="lastposts-date">{$post->date_last_post|jdatetime:'timestamp':'lang_datetime'}</td>
        </tr>
{/foreach}
    </table>
</div>
