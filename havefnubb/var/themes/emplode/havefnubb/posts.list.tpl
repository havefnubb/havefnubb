<div id="breadcrumbtop" class="headbox">
    <h2>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$forum->id_cat,'ctitle'=>$forum->cat_name)}" title="{$forum->cat_name}">{$forum->cat_name|eschtml}</a> > {$forum->forum_name|eschtml}</h2>
</div>
{ifacl2 'hfnu.forum.list','forum'.$id_forum}
{zone 'havefnubb~forumchild', array('id_forum'=>$id_forum,'lvl'=>$lvl+1,'calledFrom'=>'posts.list')}
{/ifacl2}
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<div id="post-message">{jmessage}</div>
{/ifacl2}
<div class="newmessage">
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{else}
{ifacl2 'hfnu.posts.create'}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{/ifacl2}
</div>
{ifacl2 'hfnu.posts.list','forum'.$forum->id_forum}
<div class="pager-posts">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:lists', array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
<table class="data_table">
    <tr>
        <th class="listcol"> </th>
        <th class="listcol">{@havefnubb~forum.forumlist.title@}</th>
        <th class="listcol">{@havefnubb~member.common.author@}</th>
        <th class="listcol">{@havefnubb~forum.forumlist.responses@}</th>
        <th class="listcol">{@havefnubb~forum.forumlist.views@}</th>
        <th class="listcol">{@havefnubb~forum.forumlist.last.comments@}</th>
    </tr>
    {if $posts->rowCount() > 0}
    {foreach $posts as $post}
    {hook 'hfbPostsLists',array('id_post'=>$post->id_post)}

    {assign $status = $statusAvailable[ $post->status_thread - 1]}
    <tr>
        <td><span class="colicone-{post_status 'post',$post,$lastMarkThreadAsRead, $forum}" ></span></td>
        <td class="coltitle linkincell"><span class="newestposts">{@havefnubb~post.status.$status@}</span>
                <a class="status-{$status}" href="{jurl 'havefnubb~posts:view',
                                                array(  'id_post'=>$post->id_post,
                                                        'thread_id'=>$post->thread_id,
                                                        'id_forum'=>$post->id_forum,
                                                        'ftitle'=>$post->forum_name,
                                                        'ptitle'=>$post->subject)}"
                    title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
                {ifuserconnected}
                {if $post->date_last_post < $lastMarkThreadAsRead ||
                    $post->date_read_post >= $post->date_last_post}
                {else}
                    <span class="status-post-new">**{@havefnubb~main.common.new@}**</span>
                {/if}
                {/ifuserconnected}
        </td>
        <td class="colposter linkincell">
            {if $post->login == null} {@havefnubb~member.guest@}{else} <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->nickname|eschtml}">{$post->nickname|eschtml}</a>{/if}
        </td>
        <td class="colnum">
            {$post->nb_replies}
        </td>
        <td class="colnum">
            {$post->nb_viewed}
        </td>
        <td class="colright linkincell">
            {zone 'havefnubb~postlc',array('thread_id'=>$post->thread_id)}
        </td>
    </tr>
    {/foreach}
    {else}
        <tr><td colspan="6"></td></tr>
    {/if}
</table>

<div class="newmessage">
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{else}
{ifacl2 'hfnu.posts.create'}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{/ifacl2}
</div>

<div class="pager-posts">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:lists', array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
{/ifacl2}
{ifacl2 'hfnu.forum.goto'}
{zone 'havefnubb~jumpto',array('id_forum'=>$forum->id_forum)}
{/ifacl2}
