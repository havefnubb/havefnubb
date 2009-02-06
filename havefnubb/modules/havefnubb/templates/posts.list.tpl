<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > {$forum->forum_name|eschtml}</h3>
</div>
{ifacl2 'hfnu.forum.list','forum'.$id_forum}
{zone 'havefnubb~forumchild', array('id_forum'=>$id_forum,'lvl'=>$lvl+1,'calledFrom'=>'posts.list')}
{/ifacl2}

{ifacl2 'hfnu.posts.create'}
<div id="post-message">{jMessage}</div>
{/ifacl2}
{ifacl2 'hfnu.posts.create','forum'.$id_forum}
<div class="newmessage"><a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a></div>
{/ifacl2}

{ifacl2 'hfnu.posts.list','forum'.$forum->id_forum}
<div class="linkpages">
{pagelinks 'havefnubb~posts:lists', array('id_forum'=>$id_forum),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
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
            <td class="line forumlistline" colspan="2"></td>
            <td class="line coltitle linkincell"><a href="{jurl 'posts:view',array('id_post'=>$post->id_post)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a></td>
            <td class="line colposter linkincell">
                <a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a>
            </td>
            <td class="line colnum">{zone 'havefnubb~responsettl',array('id_post'=>$post->id_post)}</td>
            <td class="line colnum">{zone 'havefnubb~viewedttl',array('id_post'=>$post->id_post)}</td>
            <td class="line coldate linkincell">{zone 'havefnubb~postlc',array('id_post'=>$post->id_post)}</td>
        </tr>
        {/foreach}
    </table>

<div class="linkpages">
{pagelinks 'havefnubb~posts:lists', array('id_forum'=>$id_forum),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
{/ifacl2}

{ifacl2 'hfnu.forum.list'}
{zone 'havefnubb~jumpto',array('id_forum'=>$forum->id_forum)}
{/ifacl2}