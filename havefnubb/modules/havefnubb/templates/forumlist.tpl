<div class="linkpages">
{pagelinks 'forum:view', array('id'=>$id),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
    <table width="100%">
        <tr class="forumlistheader">
            <th colspan="2">
            </th>
            <th>{@forum.title@}</th>
            <th>{@member.author@}</th>
            <th>{@forum.responses@}</th>
            <th>{@forum.views@}</th>
            <th>{@forum.last.comments@}</th>        
        </tr>
        {foreach $posts as $post}
        <tr>
            <td class="forumlistline"></td>
            <td class="forumlistline"></td>    
            <td class="coltitle"><a href="{jurl 'posts:view',array('id_post'=>$post->id_post)}" title="{@forum.view.this.subject@}">{$post->subject|eschtml}</a></td>
            <td class="colposter">{zone 'poster',array('id_user'=>$post->id_user)}</td>
            <td class="colnum">{zone 'responsettl',array('id_post'=>$post->id_post)}</td>
            <td class="colnum">{zone 'viewedttl',array('id_post'=>$post->id_post)}</td>
            <td class="coldate">{zone 'postlc',array('id_post'=>$post->id_post)}</td>
        </tr>
        {/foreach}
    </table>
<div class="linkpages">
{pagelinks 'forum:view', array('id'=>$id),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>