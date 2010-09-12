    <div class="pager-posts">
    {@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:shownew', array(), $posts->rowCount(), $page, $nbPostPerPage, "page", $properties}
    </div>
    <table class="data_table">
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
        <tr>
            <td><span class="colicone-{post_status 'post',$post,0}" ></span></td>
            <td> <a href="{jurl 'havefnubb~posts:view', array('id_post'=>$post->id_post,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject)}#p{$post->id_last_msg}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a></td>
            <td>
                <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a>
            </td>
            <td>
                {$post->nb_replies}
            </td>
            <td>
                {$post->nb_viewed}
            </td>
            <td>{zone 'havefnubb~postlc',array('parent_id'=>$post->parent_id)}
            </td>
        </tr>
        {/foreach}
    {else}
        <tr><td colspan="6">{@havefnubb~post.no.new.post@}</td></tr>
    {/if}
        </tbody>
    </table>
    <div class="pager-posts">
    {@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:shownew', array(), $posts->rowCount(), $page, $nbPostPerPage, "page", $properties}
    </div>
