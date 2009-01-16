<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > {$forum->forum_name|eschtml}</h3>
</div>
<div class="linkpages">
{pagelinks 'forum:view', array('id'=>$id),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
    <table width="100%">
        <tr>
            <th colspan="2" class="forumlistcol">
            </th>
            <th class="forumlistcol">{@havefnubb~forum.forumlist.title@}</th>
            <th class="forumlistcol">{@havefnubb~member.common.author@}</th>
            <th class="forumlistcol">{@havefnubb~forum.forumlist.responses@}</th>
            <th class="forumlistcol">{@havefnubb~forum.forumlist.views@}</th>
            <th class="forumlistcol">{@havefnubb~forum.forumlist.last.comments@}</th>        
        </tr>
        {foreach $posts as $post}
        <tr>
            <td class="forumlistline" colspan="2"></td>
            <td class="coltitle linkincell"><a href="{jurl 'posts:view',array('id_post'=>$post->id_post)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a></td>
            <td class="colposter linkincell">{zone 'poster',array('id_user'=>$post->id_user)}</td>
            <td class="colnum">{zone 'responsettl',array('id_post'=>$post->id_post)}</td>
            <td class="colnum">{zone 'viewedttl',array('id_post'=>$post->id_post)}</td>
            <td class="coldate linkincell">{zone 'postlc',array('id_post'=>$post->id_post)}</td>
        </tr>
        {/foreach}
    </table>
<div class="linkpages">
{pagelinks 'forum:view', array('id'=>$id),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>