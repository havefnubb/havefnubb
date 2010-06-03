    <table class="data_table" width="100%">
        <caption>{@havefnubb~post.list.of.new.posts@} : {$posts->rowCount()}</caption>
        <thead>
            <tr><th></th>
                <th>{@havefnubb~forum.forumlist.title@}</th>
                <th>{@havefnubb~member.common.author@}</th>
                <th>{@havefnubb~forum.forumlist.responses@}</th>
                <th>{@havefnubb~forum.forumlist.views@}</th>
                <th>{@havefnubb~forum.forumlist.last.comments@}</th>
            </tr>
        </thead>
        <tbody>
    {if $posts->rowCount() > 0}
        {foreach $posts as $post}
            {if $post->parent_id == $post->id_post}
        <tr>
            <td class="colicone-{zone 'havefnubb~newestposts',array('source'=>'post',
                        'id_post'=>$post->id_post,
                        'status'=>$post->status,
                        'id_forum'=>$post->id_forum,
                        'display'=>'icon')}" > </td>
            <td> <a href="{jurl 'havefnubb~posts:view', array('id_post'=>$post->id_post,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a></td>
            <td>
                <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a>
            </td>
        <td>
            {$post->nb_replies}
        </td>
        <td>
            {$post->nb_viewed}
        </td>
            <td>{zone 'havefnubb~postlc',array('id_post'=>$post->id_post)}
            </td>
        </tr>
            {/if}
        {/foreach}
    {else}
    <tr><td colspan="6">{@havefnubb~post.no.new.post@}</td></tr>
    {/if}

        </tbody>
    </table>
