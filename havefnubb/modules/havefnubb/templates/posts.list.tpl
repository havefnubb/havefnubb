<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > {$forum->forum_name|eschtml}</h3>
</div>
{ifacl2 'hfnu.forum.list','forum'.$id_forum}
{zone 'havefnubb~forumchild', array('id_forum'=>$id_forum,'lvl'=>$lvl+1,'calledFrom'=>'posts.list')}
{/ifacl2}
{hook 'BeforePostsLists',array('id_post'=>$id_post)}
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<div id="post-message">{jmessage}</div>
{/ifacl2}
<div class="fake-button-left grid_8 alpha">&nbsp;
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
</div>
{ifacl2 'hfnu.posts.list','forum'.$forum->id_forum}
<div class="pager-posts grid_8 omega">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:lists', array('id_forum'=>$id_forum),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
<div class="clear"></div>
<br/>
<div class="box">
    <div class="block">
    <table>
    <caption>{$forum->forum_name|eschtml}</caption>
        <thead>
            <tr>
                <th> </th>
                <th>{@havefnubb~forum.forumlist.title@}</th>
                <th>{@havefnubb~member.common.author@}</th>
                <th>{@havefnubb~forum.forumlist.responses@}</th>
                <th>{@havefnubb~forum.forumlist.views@}</th>
                <th>{@havefnubb~forum.forumlist.last.comments@}</th>             
            </tr>
        </thead>
        {zone 'havefnubb~pinedposts', array('id_forum'=>$id_forum)}
        <tbody>
        {foreach $posts as $post}
        {hook 'PostsLists',array('id_post'=>$id_post)}
        <tr>
            <td class="colicone-{zone 'havefnubb~newestposts',array('source'=>'post','id_post'=>$post->id_post,'status'=>$post->status)}" > </td>
            <td class="{$post->status}">
                <span class="post-status">[{jlocale 'havefnubb~post.status.'.$post->status}]</span> <a href="{jurl 'havefnubb~posts:view', array('id_post'=>$post->parent_id,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
                {social_networks 
                array(  'imgpath'=>$j_themepath.'images/social-network',
                        'jurl'=>'havefnubb~posts:view',
                        'jurlparam'=>array('id_post'=>$post->parent_id,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject),
                        'title'=>$post->subject)}            
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
    </div>
</div>
<div class="fake-button-left grid_8 alpha">&nbsp;
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
</div>

<div class="pager-posts  grid_8 omega">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:lists', array('id_forum'=>$id_forum),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
<div class="clear"></div>
<br/>
{/ifacl2}
{hook 'AfterPostsLists',array('id_post'=>$id_post)}
{ifacl2 'hfnu.forum.goto'}
{zone 'havefnubb~jumpto',array('id_forum'=>$forum->id_forum)}
{/ifacl2}