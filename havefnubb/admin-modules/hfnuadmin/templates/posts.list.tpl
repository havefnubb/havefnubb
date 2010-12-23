<h1>{@hfnuadmin~admin.unread.posts@}</h1>
<h2>{@hfnuadmin~admin.unread.posts.list@}</h2>
<table width="100%" class="records-list">
    <thead>
        <tr class="{cycle array('odd,even')}">
            <th>{@havefnubb~forum.forumlist.title@}</th>
            <th>{@havefnubb~member.common.author@}</th>
            <th>{@havefnubb~forum.forumlist.responses@}</th>
            <th>{@havefnubb~forum.forumlist.views@}</th>
            <th>{@havefnubb~forum.forumlist.last.comments@}</th>
        </tr>
    </thead>
    <tbody>
    {foreach $posts as $post}
    <tr>
        <td>
            <a href="{jurl 'havefnubb~posts:view',
                            array(  'id_post'=>$post->id_post,
                                    'thread_id'=>$post->thread_id,
                                    'id_forum'=>$post->id_forum,
                                    'ftitle'=>$post->forum_name,
                                    'ptitle'=>$post->subject)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
        </td>
        <td>
            <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a>
        </td>
        <td>
            {$post->nb_replies}
        </td>
        <td>
            {$post->nb_viewed}
        </td>
        <td>{zone 'havefnubb~postlc',array('thread_id'=>$post->thread_id)}
        </td>
    </tr>
    {/foreach}
    </tbody>
</table>
