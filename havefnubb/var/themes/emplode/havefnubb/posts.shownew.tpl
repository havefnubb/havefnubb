<table class="data_table" width="100%">
    <thead>
        <tr>
            <th>{@havefnubb~forum.forumlist.title@}</th>
            <th>{@havefnubb~member.common.author@}</th>
            <th>{@havefnubb~forum.forumlist.responses@}</th>
            <th>{@havefnubb~forum.forumlist.views@}</th>
            <th>{@havefnubb~forum.forumlist.last.comments@}</th>             
        </tr>
    </thead>
    <tbody>
    {foreach $posts as $post}
    {hook 'hfbPostsLists',array('id_post'=>$id_post)}
    <tr>
        <td><a href="{jurl 'havefnubb~posts:view', array('id_post'=>$post->parent_id,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>           
        </td>
        <td>
            <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a>
        </td>
        <td>
            {zone 'havefnubb~responsettl',array('id_post'=>$post->id_post)}
        </td>
        <td>
            {zone 'havefnubb~viewedttl',array('id_post'=>$post->id_post)}
        </td>
        <td>{zone 'havefnubb~postlc',array('id_post'=>$post->id_post)}
        </td>
    </tr>
    {/foreach}
    </tbody>
</table>
