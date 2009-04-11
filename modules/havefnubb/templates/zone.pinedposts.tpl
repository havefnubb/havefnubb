    {foreach $posts as $post}
    <tr>
        <td class="colicone-{$post->status}"> </td>
        <td class="coltitle linkincell pined">
            <span class="post-status">[{jlocale 'havefnubb~post.status.'.$post->status}]</span> <a href="{jurl 'havefnubb~posts:view', array('id_post'=>$post->parent_id,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
        </td>
        <td class="colposter linkincell">
            <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a>
        </td>
        <td class="colnum">
            {zone 'havefnubb~responsettl',array('id_post'=>$post->id_post)}
        </td>
        <td class="colnum">
            {zone 'havefnubb~viewedttl',array('id_post'=>$post->id_post)}
        </td>
        <td
            class="colright linkincell">{zone 'havefnubb~postlc',array('id_post'=>$post->id_post)}
        </td>
    </tr>
    {/foreach}
