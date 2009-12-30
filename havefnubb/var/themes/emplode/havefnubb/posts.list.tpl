<div id="breadcrumbtop" class="headbox">
	<h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > {$forum->forum_name|eschtml}</h3>
</div>
{ifacl2 'hfnu.forum.list','forum'.$id_forum}
{zone 'havefnubb~forumchild', array('id_forum'=>$id_forum,'lvl'=>$lvl+1,'calledFrom'=>'posts.list')}
{/ifacl2}
{hook 'hfbBeforePostsLists',array('id_post'=>$id_post)}
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<div id="post-message">{jmessage}</div>
{/ifacl2}
<div class="newmessage">
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
</div>
{ifacl2 'hfnu.posts.list','forum'.$forum->id_forum}
<div class="pager-posts">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:lists', array('id_forum'=>$id_forum),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
<table class="data_table" width="100%">
	<tr>
		<th class="listcol"> </th>
		<th class="listcol">{@havefnubb~forum.forumlist.title@}</th>
		<th class="listcol">{@havefnubb~member.common.author@}</th>
		<th class="listcol">{@havefnubb~forum.forumlist.responses@}</th>
		<th class="listcol">{@havefnubb~forum.forumlist.views@}</th>
		<th class="listcol">{@havefnubb~forum.forumlist.last.comments@}</th>
	</tr>
	{zone 'havefnubb~pinedposts', array('id_forum'=>$id_forum)}
	{foreach $posts as $post}
	{hook 'hfbPostsLists',array('id_post'=>$id_post)}
	<tr>
		<td><span class="post-status-icon-{zone 'havefnubb~newestposts',array('source'=>'post','id_post'=>$post->id_post,'status'=>$post->status,'id_forum'=>$id_forum)}" > </span> </td>
		<td class="coltitle linkincell">{zone 'havefnubb~newestposts',array('source'=>'post',
												'id_post'=>$post->id_post,
												'status'=>$post->status,
												'id_forum'=>$id_forum,
												'display'=>'text')}
			<a href="{jurl 'havefnubb~posts:view', array('id_post'=>$post->parent_id,'parent_id'=>$post->parent_id,'id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'ptitle'=>$post->subject)}" title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
				{zone 'havefnubb~i_read_this_post',array('id_post'=>$post->id_post,'id_forum'=>$post->id_forum)}
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
		<td class="colright linkincell">{zone 'havefnubb~postlc',array('id_post'=>$post->id_post)}
		</td>
	</tr>
	{/foreach}
</table>

<div class="newmessage">
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
</div>

<div class="pager-posts">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~posts:lists', array('id_forum'=>$id_forum),  $nbPosts, $page, $nbPostPerPage, "page", $properties}
</div>
{/ifacl2}
{hook 'hfbAfterPostsLists',array('id_post'=>$id_post)}
{ifacl2 'hfnu.forum.goto'}
{zone 'havefnubb~jumpto',array('id_forum'=>$forum->id_forum)}
{/ifacl2}
