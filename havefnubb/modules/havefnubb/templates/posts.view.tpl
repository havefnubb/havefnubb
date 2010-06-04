<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> > {$subject|eschtml}</h3>
</div>
{zone 'hfnusearch~hfnuquicksearch'}
{ifuserconnected}
{zone 'havefnubb~mark_forum',array('currentIdForum'=>$currentIdForum)}
{/ifuserconnected}
<div class="clear"></div>
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<div id="post-message">{jmessage}</div>
{/ifacl2}

{ifacl2 'hfnu.posts.list','forum'.$forum->id_forum}


<div class="fake-button-left grid_8 alpha">&nbsp;
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{ifacl2 'hfnu.posts.reply','forum'.$forum->id_forum}
    {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
<a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post,'parent_id'=>$parent_id)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
    {else}
        {ifacl2 'hfnu.admin.post'}
<a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post,'parent_id'=>$parent_id)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
        {/ifacl2}
    {/if}
{/ifacl2}
</div>

{ifacl2 'hfnu.posts.view','forum'.$forum->id_forum}
{hook 'hfbBeforePostReplies',array('id_post'=>$id_post)}
<div class="pager-posts grid_8 omega">
{@havefnubb~main.common.page@}{pagelinks 'posts:view', array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$forum->id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}
<div class="clear"></div>
<br/>
{assign $i=0}
{foreach $posts as $post}

    {hook 'hfbPostReplies',array('id_post'=>$id_post)}
    {assign $parent_id = $post->parent_id}
    {assign $id_forum = $post->id_forum}

    {ifacl2 'hfnu.posts.view','forum'.$id_forum}
    {social_networks
        array(  'jurl'=>'havefnubb~posts:view',
                'jurlparams'=>array('id_post'=>$post->parent_id,
                    'parent_id'=>$post->parent_id,
                    'id_forum'=>$post->id_forum,
                    'ftitle'=>$post->forum_name,
                    'ptitle'=>$post->subject),
                'title'=>$post->subject)}

<div class="box">
    <h2><span class="post-status-icon-{$statusAvailable[$post->status -1]}">&nbsp;</span><span class="post-status-{$statusAvailable[$post->status -1]}">[{jlocale 'havefnubb~post.status.'.$statusAvailable[$post->status -1]}]</span> {$post->subject|eschtml} {zone 'havefnubb~i_read_this_post',array('id_post'=>$post->id_post,'id_forum'=>$post->id_forum)}</h2>
    <div class="block">
        {* rate ON the FIRST post of the thread *}
        <div class="grid_4">
        {if $post->parent_id == $post->id_post}
        {zone 'hfnurates~rates' , array('id_source'=>$post->id_post,
                'source'=>'post',
                'return_url'=>'havefnubb~posts:view',
                'return_url_params'=>array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle)
                )}
        {/if}&nbsp;
        </div>
        <div class="grid_8 postheading">
        <h5><a id="p{$post->id_post}" href="{jurl 'havefnubb~posts:view',array('id_post'=>$post->id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$post->subject)}#p{$post->id_post}">{if $i >0}<span class="post-reply-idx">{jlocale 'havefnubb~post.reply.number',array('#'.$i)}</span>{/if} {$post->date_created|jdatetime:'timestamp':'lang_datetime'} {@havefnubb~main.by@} {$post->login|eschtml}</a></h5>
        </div>
        {if count($tags) > 0}
        <div class="grid_2 postheading-tags">
        <ul>{foreach $tags as $t}<li>{$t}</li>{/foreach}</ul>
        </div>
        {/if}
        <div class="clear"></div>
        <div class="grid_4">
        {zone 'havefnubb~memberprofile',array('id'=>$post->id_user)}
        </div>
        <div class="grid_12 postbody">
        {if $statusAvailable[$post->status -1] == 'censored'}
            {$post->censored_msg|wiki:'hfb_rule'}
            {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
            <div class="censor-warning">****{@havefnubb~main.censor.moderator.warning@}*****</div>
            {$post->message|wiki:'hfb_rule'}
            {/ifacl2}
        {else}
            {$post->message|wiki:'hfb_rule'}
        {/if}
        </div>
        <div class="clear"></div>
        <div class="prefix_4 grid_12">
        {if $post->member_comment != ''}<hr/>
        {$post->member_comment|wiki:'hfb_rule'}
        {/if}
        </div>
        <div class="clear"></div>
    </div>
    <div class="postfoot fake-button">
        &nbsp;
        {hook 'hfbPostRepliesFooter',
                    array('action'=>'havefnubb~posts:view',
                          'parms'=>array('id_post'=>$post->id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle)
                          )
        }
        {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
        <span class="postsplit"><a href="{jurl 'posts:splitTo', array('id_post'=>$post->id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.split.this.message@}">{@havefnubb~main.split.this.message@}</a> </span>
        {/ifacl2}
        {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
        {if $statusAvailable[$post->status -1] == 'censored'}
        <span class="postcensor"><a href="{jurl 'posts:uncensor', array('id_post'=>$post->id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.uncensor.this.message@}">{@havefnubb~main.uncensor.this.message@}</a> </span>
        {else}
        <span class="postcensor"><a href="{jurl 'posts:censor', array('id_post'=>$post->id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.censor.this.message@}">{@havefnubb~main.censor.this.message@}</a> </span>
        {/if}
        {/ifacl2}
        {ifacl2 'hfnu.posts.notify','forum'.$id_forum}
        <span class="postnotify"><a href="{jurl 'posts:notify', array('id_post'=>$post->id_post)}" title="{@havefnubb~main.notify@}">{@havefnubb~main.notify@}</a> </span>
         {/ifacl2}
        {ifacl2 'hfnu.posts.delete','forum'.$id_forum}
        <span class="postdelete"><a href="{jurl 'posts:delete', array('id_post'=>$post->id_post,'id_forum'=>$post->id_forum)}" title="{@havefnubb~main.delete@}" onclick="return confirm({@havefnubb~post.listinpost.confirm.deletion@})">{@havefnubb~main.delete@}</a> </span>
        {/ifacl2}
        {if $post->login == $current_user}
            {ifacl2 'hfnu.posts.edit.own','forum'.$id_forum}
        <span class="postedit"><a href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@havefnubb~main.edit@}">{@havefnubb~main.edit@}</a> </span>
            {/ifacl2}
        {else}
            {ifacl2 'hfnu.posts.edit','forum'.$id_forum}
        <span class="postedit"><a href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@havefnubb~main.edit@}">{@havefnubb~main.edit@}</a> </span>
            {/ifacl2}
        {/if}
        {ifacl2 'hfnu.posts.edit','forum'.$id_forum}
            {if $subscribed}
            <span class="postsub"><a href="{jurl 'posts:unsubscribe' ,array('id_post'=>$parent_id)}" title="{@havefnubb~post.unsubscribe.to.this.post@}">{@havefnubb~post.unsubscribe.to.this.post@}</a> </span>
            {else}
            <span class="postsub"><a href="{jurl 'posts:subscribe' ,array('id_post'=>$parent_id)}" title="{@havefnubb~post.subscribe.to.this.post@}">{@havefnubb~post.subscribe.to.this.post@}</a> </span>
            {/if}
        {/ifacl2}
        {ifacl2 'hfnu.posts.quote','forum'.$id_forum}
            {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
        <span class="postquote"><a href="{jurl 'posts:quote' ,array('parent_id'=>$post->parent_id,'id_post'=>$post->id_post)}" title="{@havefnubb~main.quote@}">{@havefnubb~main.quote@}</a></span>
            {else}
                {ifacl2 'hfnu.admin.post'}
        <span class="postquote"><a href="{jurl 'posts:quote' ,array('parent_id'=>$post->parent_id,'id_post'=>$post->id_post)}" title="{@havefnubb~main.quote@}">{@havefnubb~main.quote@}</a></span>
                {/ifacl2}
            {/if}
        {/ifacl2}
    </div>
</div>
    {/ifacl2}
    {assign $i++}
{/foreach}

<div class="fake-button-left grid_8 alpha">&nbsp;
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{ifacl2 'hfnu.posts.reply','forum'.$forum->id_forum}
    {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
<a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post,'parent_id'=>$parent_id)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
    {else}
        {ifacl2 'hfnu.admin.post'}
<a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post,'parent_id'=>$parent_id)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
        {/ifacl2}
    {/if}
{/ifacl2}
</div>

{ifacl2 'hfnu.posts.view','forum'.$forum->id_forum}
{hook 'hfbAfterPostsReplies',array('id_post'=>$id_post)}
<div class="pager-posts grid_8 omega">
{@havefnubb~main.common.page@}{pagelinks 'posts:view', array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}
<div class="clear"></div>
<br/>
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
{* 'closed' *}
    {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
        {zone 'havefnubb~quickreply',array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$forum->id_forum)}
    {else}
        {ifacl2 'hfnu.admin.post'}
        {zone 'havefnubb~quickreply',array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$forum->id_forum)}
        {/ifacl2}
    {/if}
{/ifacl2}
{ifacl2 'hfnu.forum.goto'}
    {zone 'havefnubb~jumpto',array('id_forum'=>$forum->id_forum)}
{/ifacl2}
{ifacl2 'hfnu.admin.post'}
<div class="box">
    <h2>{@havefnubb~post.status.change.the.status.of.the.post@}</h2>
{form $formStatus, 'havefnubb~posts:status',array('parent_id'=>$parent_id)}
    <div class="block">
    {ctrl_label 'status'} {ctrl_control 'status'} {formsubmit 'validate'}
    </div>
{/form}
</div>
<div class="box">
    <h2>{@havefnubb~forum.move.this.thread@}</h2>
{form $formMove, 'havefnubb~posts:moveToForum',array('id_post'=>$id_post)}
    <div class="block">
    {ctrl_label 'id_forum'} {ctrl_control 'id_forum'} {formsubmit 'validate'}
    </div>
{/form}
</div>
{/ifacl2}

{/ifacl2}
