    {foreach $posts as $post}
    <tr>
        <td><span class="colicone-{zone 'havefnubb~newestposts',array('source'=>'post',
                        'id_post'=>$post->id_post,
                        'status'=>$post->status,
                        'id_forum'=>$id_forum,
                        'display'=>'icon')}">&nbsp;</span>
        </td>
        <td class="coltitle linkincell pined">
            <span class="newestposts">{jlocale 'havefnubb~post.status.'.$post->status}</span> <a href="{jurl 'havefnubb~posts:view', array('id_post'=>$post->parent_id,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
                {social_networks
                    array(  'jurl'=>'havefnubb~posts:view',
                            'jurlparams'=>array('id_post'=>$post->parent_id,
                                'parent_id'=>$post->parent_id,
                                'id_forum'=>$post->id_forum,
                                'ftitle'=>$post->forum_name,
                                'ptitle'=>$post->subject),
                            'title'=>$post->subject)}
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