<h2>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a>
    &gt; {@havefnubb~post.list.of.new.posts@}</h2>
    <div class="pager-posts">
    {@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:shownew', array(), $nbPosts, $page, $nbPostPerPage, "page", $properties}
    </div>
    <table class="data_table">
        <caption>{@havefnubb~post.list.of.new.posts@} : {$nbPosts}</caption>
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
    {if $nbPosts > 0}
        {foreach $posts as $post}
            <tr>
                <td> <a href="{jurl 'havefnubb~posts:view', array('id_post'=>$post->id_last_msg,'thread_id'=>$post->thread_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject)}#p{$post->id_last_msg}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a></td>
                <td>
                    {if $post->login == null} {@havefnubb~member.guest@}{else} <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a>{/if}
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
    {else}
        <tr><td colspan="6">{@havefnubb~post.no.new.post@}</td></tr>
    {/if}
        </tbody>
    </table>
    <div class="pager-posts">
    {@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:shownew', array(), $nbPosts, $page, $nbPostPerPage, "page", $properties}
    </div>
